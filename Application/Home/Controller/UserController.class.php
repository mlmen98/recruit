<?php

namespace Home\Controller;
use Think\Controller;

/**
 * Class NewsController
 * @package Admin\Controller
 */
class UserController extends AdminBasicController {
    public function _initialize(){
        $this -> checkLogin();
        $this -> user = D('User');
        $this -> reuser = D('Reuser');


    }

    /**
     * 用户列表
     */
    public function userlist(){
        $w['status'] = array('neq',9);
        $name = $_REQUEST['nick_name'];
        if($name){
            $w['uname'] = array('LIKE','%'.$_REQUEST['nick_name'].'%');
        }
        $count =  $this -> user -> where($w)->count();
        $page = new \Think\Page($count,15);
        if ($name) {
            $page->parameter['nick_name'] =  $name;
        }
        $userlist = $this -> user -> where($w) -> order('addtime desc') -> limit($page->firstRow,$page->listRows) -> select();
        $this -> assign('list',$userlist);
        $this -> assign('page',$page->show());
        $this->display();
    }


    /**
     * 反馈列表
     */
    public function report(){
        $w['status'] = array('neq',9);
        $name = $_REQUEST['name'];
        if($name){
            $w['name'] = array('LIKE','%'.$_REQUEST['name'].'%');
        }
        $count =  $this -> reuser -> where($w)->count();
        $page = new \Think\Page($count,15);
        if ($name) {
            $page->parameter['name'] =  $name;
        }
        $list = $this -> reuser -> where($w) -> order('utime desc') -> limit($page->firstRow,$page->listRows) -> select();
        $this -> assign('list',$list);
        $this -> assign('page',$page->show());
        $this->display();
    }


}