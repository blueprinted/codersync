<?php
/**
 *	admin 后台框架首页控制器
 *	zouchao@sogou-inc.com
 *	2016-05-26
 */

namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class IndexController extends AdminbaseController {
    function _initialize() {
	    empty($_GET['upw'])?"":session("__SP_UPW__",$_GET['upw']);//设置后台登录加密码	    
		parent::_initialize();
		$this->initMenu();
	}
	
	public function indexAction(){
		if (C('LANG_SWITCH_ON',null,false)){
    		$this->load_menu_lang();
    	}
        $this->assign("SUBMENU_CONFIG", D("Common/Menu")->menu_json());
		$this->display();
    }
	
	private function load_menu_lang(){
		$apps=sp_scan_dir(APP_PATH."*",GLOB_ONLYDIR);
		$error_menus=array();
		foreach ($apps as $app){
			if(is_dir(APP_PATH.$app)){
				$admin_menu_lang_file=APP_PATH.$app."/Lang/".LANG_SET."/admin_menu.php";
				if(is_file($admin_menu_lang_file)){
					$lang=include $admin_menu_lang_file;
					L($lang);
				}
			}
		}
	}
}