<?php
/**
 *	rsync发布控制器
 *	zouchao@sogou-inc.com
 *	2016-05-26
 */

namespace Rsync\Controller;
use Think\Controller;
class RsyncController extends Controller {
	protected function _initialize() {
		if(!B('Admin\Behavior\AuthCheck')) {
			$this->error('请先登录', U('Admin/Public/login'), 1, array('code'=>'need_login'));
		}
	}
	
	/*rsync发布*/
	public function apprsyncAction() {
		if(I('post.dosubmit')) {
			$group = I('get.group', '');
			$appid = I('get.appid', 0, 'intval');
			$istest = I('get.istest',0, 'intval');
			$dirs = I('post.dir', array());//rsync发布的目录或文件
			$dirs = is_array($dirs) ? $dirs : array($dirs);//处理成规范格式
			
			$resu = app_rsync($group, $appid, $istest, $dirs);
			$info = array(
				'data' => (isset($resu['data']['cmds']) && $resu['data']['cmds'] ? implode("\r\n",$resu['data']['cmds'])."\r\n" : '') . (isset($resu['data']['lines']) && $resu['data']['lines'] ? implode("\r\n",$resu['data']['lines']) : ''),
			);
			
			if ($resu['status'] != 0) {
				$this->error($resu['message'], '', $info);
			}
			
			$this->success($resu['message'], '', $info);
		}
		
		$this->error('未识别的请求');
    }
	
	/*重启服务*/
	public function apprestartAction() {
		if(I('post.dosubmit')) {
			$group = I('get.group', '');
			$appid = I('get.appid', 0, 'intval');
			$istest = I('get.istest',0, 'intval');
			
			$resu = app_restart($group, $appid, $istest);
			$info = array(
				'data' => (isset($resu['data']['cmds']) && $resu['data']['cmds'] ? implode("\r\n",$resu['data']['cmds'])."\r\n" : '') . (isset($resu['data']['lines']) && $resu['data']['lines'] ? implode("\r\n",$resu['data']['lines']) : ''),
			);
			
			if ($resu['status'] != 0) {
				$this->error($resu['message'], '', $info);
			}
			
			$this->success($resu['message'], '', $info);
		}
		
		$this->error('未识别的请求');
    }
	
	/*sac发布*/
	public function appdeployAction() {
		if(I('post.dosubmit')) {
			$group = I('get.group', '');
			$appid = I('get.appid', 0, 'intval');
			$istest = I('get.istest', 0, 'intval');
			$username = I('post.username', '', 'trim');
			$password = I('post.password', '', 'trim');
			
			if($istest) {
				$this->error('请到正式环境下发布');
			}
			
			$applist = load_app_list();
			$app = array();
			$app = isset($applist[$appid]) ? $applist[$appid] : array();
			if(empty($app)) {
				$this->error('项目不存在。');
			}
			if(!$app['sogou_sac']) {
				$this->error('非SAC项目无需deploy');
			}
			if(empty($app['sac_appid'])) {
				$this->error('没有配置sac的appid');
			}
			if(empty($app['sac_app_dir'])) {
				$this->error('没有配置sac模板目录');
			}
			if(!@is_dir($app['sac_app_dir'])) {
				$this->error('项目的sac模板目录不存在');
			}
			if(substr($app['sac_app_dir'], -1) !== '/') {
				$app['sac_app_dir'] .= '/';
			}
			if($username === '') {
				$this->error('没有输入账号');
			}
			if($password === '') {
				$this->error('没有输入密码');
			}
			
			$cmd = ". /root/.bash_profile;cd {$app['sac_app_dir']} && /usr/local/bin/sac auth -i --no-cache --username={$username} --password={$password} --appid={$app['sac_appid']} 2>&1 && /usr/local/bin/sac deploy 2>&1";
			$ret_msg = @exec($cmd, $ret_arr, $ret_var);
			if(false !== strpos($ret_msg, ' relogin ')) {// 执行失败出现这种提示 auth expired.plz relogin user sac auth -i 尝试多执行一次
				@exec($cmd, $ret_arr, $ret_var);
			}
			$cmd = @preg_replace('/\s?\-\-(password)=\S*/i', '', $cmd);
			if($ret_var !== 0) {
				$this->error('sac deploy失败', '', array('data'=>$cmd." [fail exec_ret_var={$ret_var}]\r\n".implode("\r\n",$ret_arr)));
			} else {
				@exec(". /root/.bash_profile;cd {$app['sac_app_dir']} && /usr/local/bin/sac auth -o --username={$username} 2>&1", $ret_arr);//登出
				$this->success('sac deploy成功', '', array('data'=>$cmd." [succ]\r\n".implode("\r\n",$ret_arr)));
			}
		}
		
		$this->error('未识别的请求');
	}
}