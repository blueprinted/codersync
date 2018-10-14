<?php
/**
 *	登录检测行为
 */

namespace Admin\Behavior;
use Think\Behavior;
class AuthCheckBehavior extends Behavior {
     //行为扩展的执行入口必须是run
	public function run(&$return){
		$return = true;
		if(!isLogined()) {
			$return = false;
		}
		return $return;
	}
}
