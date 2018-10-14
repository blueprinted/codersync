<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class SettingController extends AdminbaseController{	
	protected $options_model;
	
	function _initialize() {
		parent::_initialize();
	}
	
	function passwordAction(){
		$this->display();
	}
	
	function password_postAction(){
		if (IS_POST) {
			$this->error("修改密码功能暂不支持！");
			if(empty($_POST['old_password'])){
				$this->error("原始密码不能为空！");
			}
			if(empty($_POST['password'])){
				$this->error("新密码不能为空！");
			}
			$user_obj = D("Common/User");
			$uid=get_current_admin_id();
			$admin=$user_obj->where(array("uid"=>$uid))->find();
			$old_password=$_POST['old_password'];
			$password=$_POST['password'];
			if(sp_compare_password($old_password,$admin['password'])){
				if($_POST['password']==$_POST['repassword']){
					if(sp_compare_password($password,$admin['password'])){
						$this->error("新密码不能和原始密码相同！");
					}else{
						$data['password']=sp_password($password);
						$data['uid']=$uid;
						$r=$user_obj->save($data);
						if ($r!==false) {
							$this->success("修改成功！");
						} else {
							$this->error("修改失败！");
						}
					}
				}else{
					$this->error("密码输入不一致！");
				}
	
			}else{
				$this->error("原始密码不正确！");
			}
		}
	}
	
	//清除缓存
	function clearcacheAction(){
		//sp_clear_cache();
		$this->display();
	}
}