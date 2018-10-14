<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class UserController extends AdminbaseController{
	protected $users_model,$role_model;
	
	function _initialize() {
		parent::_initialize();
		$this->users_model = D("Common/User");
		$this->role_model = D("Common/Role");
		$this->role_user_model = M('role_user');
	}
	function indexAction(){
		$count=$this->users_model->where(array("utype"=>1))->count();
		$page = $this->page($count, 20);
		$users = $this->users_model
		->where(array("utype"=>1))
		->order("create_time DESC")
		->limit($page->firstRow . ',' . $page->listRows)
		->select();
		$roles_src=$this->role_model->select();
		$roles=array();
		foreach ($roles_src as $r){
			$roleid=$r['id'];
			$roles["$roleid"]=$r;
		}
		//补全角色信息
		if($users) {
			foreach($users as $key => $user) {
				$roleusers = array();
				$users[$key]['roleids'] = array();
				$users[$key]['rolenames'] = array();
				$roleusers = $this->role_user_model->where("user_id={$user['uid']}")->select();
				if($roleusers) {
					foreach($roleusers as $roleuser) {
						$users[$key]['roleids'][$roleuser['role_id']] = $roleuser['role_id'];
						$users[$key]['rolenames'][$roleuser['role_id']] = $roles[$roleuser['role_id']]['name'];
					}
				}
			}
		}
		$this->assign("page", $page->show('Admin'));
		$this->assign("roles",$roles);
		$this->assign("users",$users);
		$this->display();
	}
	
	
	function addAction(){
		$roles=$this->role_model->where("status=1")->order("id desc")->select();
		$this->assign("roles",$roles);
		$this->display();
	}
	
	/*添加管理员,只能将已有的用户添加为管理员,不能添加不存在的用户*/
	function add_postAction(){
		if(IS_POST){
			if(!empty($_POST['role_id']) && is_array($_POST['role_id'])){
				$role_ids=$_POST['role_id'];
				$username=$_POST['username'];
				$user = $_POST['username'] ? $this->users_model->where("username='{$username}'")->find() : array();
				if(empty($user)) {
					$this->error('用户不存在，当前只能指定已存在的用户为管理员');
				}
				$_POST['uid'] = $user['uid'];
				$_POST['utype'] = 1;
				unset($_POST['role_id']);
				if ($this->users_model->create()) {
					$result=$this->users_model->where("uid={$user['uid']}")->save();
					if ($result!==false) {
						$role_user_model=M("role_user");
						foreach ($role_ids as $role_id){
							$role_user_model->add(array("role_id"=>$role_id,"user_id"=>$result));
						}
						$this->success("添加成功！", U("user/index"));
					} else {
						$this->error("添加失败！");
					}
				} else {
					$this->error($this->users_model->getError());
				}
			}else{
				$this->error("请为此用户指定角色！");
			}
			
		}
	}
	
	
	function editAction(){
		$uid= intval(I("get.uid"));
		$roles=$this->role_model->where("status=1")->order("id desc")->select();
		$this->assign("roles",$roles);
		$role_user_model=M("role_user");
		$role_ids=$role_user_model->where(array("user_id"=>$uid))->getField("role_id",true);
		$this->assign("role_ids",$role_ids);
			
		$user=$this->users_model->where(array("uid"=>$uid))->find();
		$this->assign('user', $user);
		$this->display();
	}
	
	function edit_postAction(){
		if (IS_POST) {
			if(!empty($_POST['role_id']) && is_array($_POST['role_id'])){
				if(empty($_POST['password'])){
					unset($_POST['password']);
				} else {
					$_POST['password'] = sp_password($_POST['password']);
				}
				$role_ids=$_POST['role_id'];
				unset($_POST['role_id']);
				if ($this->users_model->create()) {
					$result=$this->users_model->save();
					if ($result!==false) {
						$uid=intval($_POST['uid']);
						$role_user_model=M("role_user");
						$role_user_model->where(array("user_id"=>$uid))->delete();
						foreach ($role_ids as $role_id){
							$role_user_model->add(array("role_id"=>$role_id,"user_id"=>$uid));
						}
						$this->success("保存成功！");
					} else {
						$this->error("保存失败！");
					}
				} else {
					$this->error($this->users_model->getError());
				}
			}else{
				$this->error("请为此用户指定角色！");
			}
		}
	}
	
	/**
	 *  删除
	 */
	function deleteAction(){
		$this->error("删除功能已禁用！");
		$uid = intval(I("get.uid"));
		if($uid==1 || $uid == get_super_uid()){
			$this->error("最高管理员不能删除！");
		}
		if ($this->users_model->where("uid=$uid")->delete()!==false) {
			M("role_user")->where(array("user_id"=>$uid))->delete();
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	
	function userinfoAction(){
		$uid=get_current_admin_id();
		$user=$this->users_model->where(array("uid"=>$uid))->find();
		$this->assign('user',$user);
		$this->display();
	}
	
	function userinfo_postAction(){
		if (IS_POST) {
			$_POST['uid']=get_current_admin_id();
			$_POST['password'] = sp_password(random(8));
			$_POST['update_time'] = time();
			$create_result=$this->users_model
			->field("username,last_ip,last_time,create_time,status,utype",true)//排除相关字段
			->create();
			if ($create_result) {
				if ($this->users_model->save()!==false) {
					$this->success("保存成功！");
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->users_model->getError());
			}
		}
	}
	
	function banAction(){
        $uid=intval($_GET['uid']);
    	if ($uid) {
    		$rst = $this->users_model->where(array("uid"=>$uid,"utype"=>1))->setField('status','0');
    		if ($rst) {
    			$this->success("管理员停用成功！", U("user/index"));
    		} else {
    			$this->error('管理员停用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function cancelbanAction(){
    	$uid=intval($_GET['uid']);
    	if ($uid) {
    		$rst = $this->users_model->where(array("uid"=>$uid,"utype"=>1))->setField('status','1');
    		if ($rst) {
    			$this->success("管理员启用成功！", U("user/index"));
    		} else {
    			$this->error('管理员启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
}