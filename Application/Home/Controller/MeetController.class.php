<?php

namespace Home\Controller;
use Think\Controller;

/**
 * Class MeetController
 * @package Admin\Controller
 */
class MeetController extends AdminBasicController {
    public $Article = '';
    public function _initialize(){
        $this->checkLogin();
        $this->meet = M('Meet');
        $this->meetclass = M('Meetforclass');

        $this->classobj = M('Class');

    }



    /**
    * addMeet
    */ 
    public function addcla(){
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $remark = $_POST['remark'];
        $time = time();
        $status = 0;
        $data = array(
            'name' => $name,
            'desc' => $desc,
            'remark' => $remark,
            'ctime' => $time,
            'status' => 0,
            'utime' => $time
            );
        $res = $this->classobj->add($data);
        if (isset($res)) {
            $this->success('操作成功',U('Meet/classes'));
        }else{
            $this->error('操作失败');
        }
    }

    /**
    * editMeet
    */
    public function editclass(){
        if(empty($_POST)){
            $where['id'] = $_GET['id'];
            $res = $this->classobj->where($where)->select();
            // dump($res);
            // exit();
            if ( !empty($res) ) {
                $this->assign('classobj',$res[0]);
            }
            $this->display('editclass');
        }else{
            $id = $_POST['id'];
            $name = $_POST['name'];
            $desc = $_POST['desc'];
            $remark = $_POST['remark'];
            $utime = time();
            $status = 0;
            $data = array(
                'id' => $id,
                'name' => $name,
                'desc' => $desc,
                'remark' => $remark,
                'status' => 0,
                'utime' => $utime
                );
            $res = $this->classobj->save($data);
            if (isset($res)) {
                $this->success('操作成功',U('Meet/classes'));
            }else{
                $this->error('操作失败');
            }
        }
    }
    /**
     * 
     */
    public function classes(){

        $w['status'] = array('neq',9);
        $count = $this -> classobj -> where($w) -> count();
        $page = new \Think\Page($count,15);
        $res=$this -> classobj -> where($w) -> limit($page->firstRow,$page->listRows) -> select();
        $this->assign('list',$res);
        $this->assign('page',$page->show());
        $this->display();
    }
    /**
     * 删除新闻
     */
    public function delclass(){

        if(empty($_REQUEST['id'])){
            $this->error('您未选择任何操作对象');
        }
        $data['id'] = array('IN',I('request.id'));
        $data['status'] = 9;
        $upd_res = $this -> classobj -> save( $data );
        if($upd_res){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }

    public function meetlist(){

        $w['status'] = array('neq',9);
        $count = $this -> meetclass -> where($w) -> count();
        $page = new \Think\Page($count,15);
        $res=$this -> meetclass -> where($w) -> limit($page->firstRow,$page->listRows) -> select();
        $this->assign('list',$res);
        $this->assign('page',$page->show());
        $this->display();
    }

    public function addmeet(){
        if(empty($_POST)){
            $w['status'] = array('neq',9);
            $res = $this -> classobj -> where( $w ) -> select();
            $this->assign('classes',$res);
            $this->display('addmeet');
        }else{
            $data = array(
                'title' => $_POST['title'],
                'open_time' => $_POST['open_time'],
                'lnt' => $_POST['lnt'],
                'lat' => $_POST['lat'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'class_id' => $_POST['class_id'],
                'photos' => implode(",", $_POST['photos']),
                'pic' => $_POST['pic'],
                'bgcolor' => $_POST['bgcolor'],
                'remark' => $_POST['remark'],
                'content' => $_POST['content'],
                'c_time' => time(),
                'status' => 0,
                'u_time' => time()
                );
            $res = $this->meet->add($data);
            if (isset($res)) {
                $this->success('操作成功',U('Meet/meetlist'));
            }else{
                $this->error('操作失败');
            }
        }
    }




    public function editmeet(){
        if(empty($_POST)){
            $w['status'] = array('neq',9);
            $res = $this -> classobj -> where( $w ) -> select();
            $w['id'] = $_GET['id'];
            $res1 = $this->meet->where($w)->select();
            // dump($res);
            // exit();
            if ( !empty($res) ) {
                $res1[0]['photos'] = explode(",",$res1[0]['photos']);
                $this->assign('obj',$res1[0]);
            }

            $this->assign('classes',$res);
            $this->display('editmeet');
        }else{
            $data = array(
                'id' => $_POST['id'],
                'title' => $_POST['title'],
                'open_time' => $_POST['open_time'],
                'lnt' => $_POST['lnt'],
                'lat' => $_POST['lat'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'class_id' => $_POST['class_id'],
                'photos' => implode(",", $_POST['photos']),
                'pic' => $_POST['pic'],
                'bgcolor' => $_POST['bgcolor'],
                'content' => $_POST['content'],
                'remark' => $_POST['remark'],
                'u_time' => time()
                );
            $res = $this->meet->save($data);
            if (isset($res)) {
                $this->success('操作成功',U('Meet/meetlist'));
            }else{
                $this->error('操作失败');
            }
        }
    }

    public function meetdelete(){

        if(empty($_REQUEST['id'])){
            $this->error('您未选择任何操作对象');
        }
        $data['id'] = array('IN',I('request.id'));
        $data['status'] = 9;
        $upd_res = $this -> meet -> save( $data );
        if($upd_res){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }


    /**ajax上传图片*/
    public function uploadPic(){
        $pic       = $_POST['pic'];
        $pic_name      = $_POST['pic_name'];
        $temp = explode('.',$pic_name);
        $ext = uniqid().'.'.end($temp);
        $base64    = substr(strstr($pic, ","), 1);
        $image_res = base64_decode($base64);
        $pic_link  = "Uploads/Shop/".date('Y-m-d').'/'.$ext;
        $saveRoot = "Uploads/Shop/".date('Y-m-d').'/';
        /**检查目录是否存在  循环创建目录*/
        if(!is_dir($saveRoot)){
            mkdir($saveRoot, 0777, true);
        }
        $res = file_put_contents($pic_link ,$image_res);
        if($res){
            $result_data['path'] = $pic_link;
                apiResponse( "success","上传成功！",$result_data);
        }else{
            apiResponse( "error","上传失败");
        }
    }
    
}