<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function indexAction(){
		if ($_SERVER['HTTP_HOST'] != 'cms.shouji.sogou-inc.com') {
			$this->display('index.pc');
		} else {
			$this->display();
		}
    }
	public function phpinfoAction(){
		echo phpinfo();exit;
	}
	public function ini_get_allAction(){
		echo'<pre>';print_r(ini_get_all());echo'</pre>';
	}
  public function whoamiAction(){
		echo exec('whoami');
	}
}
