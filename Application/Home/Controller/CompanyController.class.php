<?php

namespace Home\Controller;
use Think\Controller;

/**
 * Class NewsController
 * @package Admin\Controller
 */
class CompanyController extends AdminBasicController {

    public function _initialize(){
        $this->checkLogin();
        $this->company = M('Company');
    }
    public function companylist(){

        $w['status'] = array('neq',9);
        $count = $this -> company -> where($w) -> count();
        $page = new \Think\Page($count,15);
        $res=$this -> company -> where($w) -> limit($page->firstRow,$page->listRows) -> select();
        $this->assign('list',$res);
        $this->assign('page',$page->show());
        $this->display();
    }

    public function addcompany(){
        if(empty($_POST)){
            $this->display('addcompany');
        }else{
            $data = array(
                'name' => $_POST['name'],
                'address' => $_POST['address'],
                'logo' => $_POST['logo'],
                'people' => $_POST['people'],
                'tel' => $_POST['tel'],
                'position' => $_POST['position'],   
                'remark' => $_POST['remark'],
                'desc' => $_POST['content'],
                'ctime' => time(),
                'status' => 0,
                'utime' => time()
                );
            $res = $this->company->add($data);
            if (isset($res)) {
                $this->success('操作成功',U('Company/companylist'));
            }else{
                $this->error('操作失败');
            }
        }
    }




    public function editcompany(){
        if(empty($_POST)){
            $w['id'] = $_GET['id'];
            $res1 = $this->company->where($w)->select();
            $this->assign('obj',$res1[0]);
            $this->display('editcompany');
        }else{
            $data = array(
                'id' => $_POST['id'],
                'name' => $_POST['name'],
                'address' => $_POST['address'],
                'logo' => $_POST['logo'],
                'people' => $_POST['people'],
                'tel' => $_POST['tel'],
                'position' => $_POST['position'],   
                'remark' => $_POST['remark'],
                'desc' => $_POST['content'],
                'utime' => time()
                );
            $res = $this->company->save($data);
            if (isset($res)) {
                $this->success('操作成功',U('Company/companylist'));
            }else{
                $this->error('操作失败');
            }
        }
    }
    
    public function deletecompany(){
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