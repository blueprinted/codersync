<?php
/**
 *	admin 后台登录控制器
 *	zouchao@sogou-inc.com
 *	2016-10-30
 */

namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class AdminController extends AdminbaseController {
    function _initialize() {}
    
	/*登录管理后台*/
	public function loginAction() {
		if(!isLogined()) {
			$this->redirect('Public/login');
		}
		if(isset($_SESSION['ADMIN_UID'])) {
			$this->success(L('LOGIN_SUCCESS'),U("Index/index"));
		}
		$site_admin_url_password =C("SP_SITE_ADMIN_URL_PASSWORD");//是否加密后台入口地址
		$upw=session("__SP_UPW__");
		if(!empty($site_admin_url_password) && $upw!=$site_admin_url_password){
			redirect(__ROOT__."/");
		}else{
			session("__SP_ADMIN_LOGIN_PAGE_SHOWED_SUCCESS__",true);//用于验证是否是从登录页面进行登录
			$this->display(":login");
		}
	}
    
	/*登出管理后台*/
    public function logoutAction(){
    	session('ADMIN_UID',null);
		session('ADMIN_USERNAME',null);
		cookie('admin_username','');
    	redirect(__ROOT__."/");
    }
    
	/*登录的提交处理*/
    public function dologinAction(){
        $login_page_showed_success=session("__SP_ADMIN_LOGIN_PAGE_SHOWED_SUCCESS__");
        if(!$login_page_showed_success){
            $this->error('login error!');
        }
    	$verrify = I("post.verify");
    	if(empty($verrify)){
    		$this->error(L('CAPTCHA_REQUIRED'));
    	}
    	//验证码
    	if(!sp_check_verify_code()){
    		$this->error(L('CAPTCHA_NOT_RIGHT'));
    	}else{
    		//登入成功页面跳转
			$_SESSION["ADMIN_UID"]=session("UID");
			$_SESSION["ADMIN_USERNAME"]=session("USERNAME");
			cookie("admin_username",session("USERNAME"));
			$this->success(L('LOGIN_SUCCESS'),U("Index/index"));
    	}
    }

}