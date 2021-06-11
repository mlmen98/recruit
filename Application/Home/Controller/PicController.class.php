<?php

namespace Home\Controller;
use Think\Controller;

/**
 * Class NewsController
 * @package Admin\Controller
 */
class PicController extends AdminBasicController {

    public function _initialize(){
        $this->checkLogin();
        $this->company = M('Pic');
    }
    public function piclist(){
        $w['status'] = array('neq',9);
        $count = $this -> company -> where($w) -> count();
        $page = new \Think\Page($count,15);
        $res=$this -> company -> where($w) -> limit($page->firstRow,$page->listRows) -> select();
        $this->assign('list',$res);
        $this->assign('page',$page->show());
        $this->display();
    }

    public function addpic(){
        if(empty($_POST)){
            $this->display('addpic');
        }else{
            $data = array(
                'name' => $_POST['name'],
                'logo' => $_POST['logo'],
                'status' => 0,
                'utime' => time()
                );
            $res = $this->company->add($data);
            if (isset($res)) {
                $this->success('操作成功',U('Pic/piclist'));
            }else{
                $this->error('操作失败');
            }
        }
    }




    public function editpic(){
        if(empty($_POST)){
            $w['id'] = $_GET['id'];
            $res1 = $this->company->where($w)->select();
            $this->assign('obj',$res1[0]);
            $this->display('editpic');
        }else{
            $data = array(
                'id' => $_POST['id'],
                'logo' => $_POST['logo'],
                'utime' => time()
                );
            $res = $this->company->save($data);
            if (isset($res)) {
                $this->success('操作成功',U('Pic/piclist'));
            }else{
                $this->error('操作失败');
            }
        }
    }
    
    public function deletepic(){
        if(empty($_REQUEST['id'])){
            $this->error('您未选择任何操作对象');
        }
        $data['id'] = array('IN',I('request.id'));
        $data['status'] = 9;
        $upd_res = $this -> company -> save( $data );
        if($upd_res){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }

    
}