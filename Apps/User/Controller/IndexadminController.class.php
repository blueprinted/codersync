<?php

/**
 * 会员
 */
namespace User\Controller;
use Common\Controller\AdminbaseController;
class IndexadminController extends AdminbaseController {
    function indexAction(){
    	$users_model=M("User");
    	$count=$users_model->where(1)->count();
    	$page = $this->page($count, 20);
    	$lists = $users_model
    	->where(1)
    	->order("create_time DESC")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
    	$this->assign('lists', $lists);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display(":index");
    }
    
    function banAction(){
    	$uid=intval($_GET['uid']);
    	if ($uid) {
    		$rst = M("user")->where(array("uid"=>$uid,"utype"=>0))->setField('status','2');
    		if ($rst) {
    			$this->success("会员拉黑成功！", U("Indexadmin/index"));
    		} else {
    			$this->error('会员拉黑失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function cancelbanAction(){
    	$uid=intval($_GET['uid']);
    	if ($uid) {
    		$rst = M("user")->where(array("uid"=>$uid,"utype"=>0))->setField('status','1');
    		if ($rst) {
    			$this->success("会员启用成功！", U("Indexadmin/index"));
    		} else {
    			$this->error('会员启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
}
