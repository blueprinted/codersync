<?php
/**
 *	公用方法控制器
 *	zouchao@sogou-inc.com
 *	2016-05-26
 */

namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller {
    public function indexAction(){
        $this->show('<p>欢迎！</p>');
    }
	
	/*登录*/
    public function loginAction() {
		$rurl = urlrewrite_decode(I('get.rurl', ''));
		if(empty($rurl)) {
			$rurl = $_SERVER['HTTP_REFERER'];
		}
		if(!@preg_match('@'.getSiteUrl().'@', $rurl)) {
			$rurl = U('Index/index');
		}
    	if(session('?UID') || session('?USERNAME')){//已经登录
			$this->success('登录成功', $rurl);
    	}
		if(cookie('jpassport-sp')) {
			if(preg_match('/\{username:([^,\s]+),/i', cookie('jpassport-sp'), $matches)) {
				$username = $matches[1];
				$nickname = substr($matches[1], 0, (($pos=strpos($matches[1],'@'))===false?strlen($matches[1]):$pos));
				$userModel = M('user');
				$where['username'] = $username;
				$user = $userModel->where($where)->find();
				if(empty($user)) {
					$ips = get_onlineip();
					$regip = empty($ips) || strpos($ips, ',') === false ? $ips : substr($ips, 0, strpos($ips, ','));
					$user = array(
						'username' => $username,
						'nickname' => $nickname,
						'email' => $username,
						'regip' => $regip,
						'ips' => $ips,
						'salt' => random(8),
						'status' => 1,
						'create_time' => time(),
					);
					if($insertid = $userModel->add($user)) {
						$user['uid'] = $insertid;
					} else {
						$user['uid'] = 0;
					}
				} else {
					$newarr = array(
						'uid' => $user['uid'],
						'last_ip' => get_onlineip(true),
						'last_time' => time(),
					);
					$userModel->save($newarr);
				}
				if($user['uid'] > 0) {
					session('UID', $user['uid']);
					session('USERNAME', $user['username']);
					session('NICKNAME', $user['nickname']);
					session('UTYPE',$user['utype']);
					$this->success('登录成功', $rurl);
				}
			}
		}
		$loginurl = U('Admin/Public/login', array('rurl'=>urlrewrite_encode($rurl)));
		$rurl = 'http://passport.sogou-inc.com/?shire='.rawurlencode(getSiteUrl().'shire.jsp').'&target='.($loginurl);
		$this->error('请先登录', $rurl, 0);
    }
    
	/*注销*/
    public function logoutAction(){
		$rurl = urlrewrite_decode(I('get.rurl', '', 'urldecode'));
		if(empty($rurl)) {
			$rurl = $_SERVER['HTTP_REFERER'];
		}
		if(!@preg_match('@'.getSiteUrl().'@', $rurl)) {
			$rurl = U('Index/index');
		}
		
    	session('UID',null);
		session('USERNAME',null);
		session('NICKNAME',null);
		session('UTYPE',null);
		cookie('jpassport-sp', null);
		setcookie('jpassport-sp', '', time()-3600, '/');
		$message = '注销成功<script type="text/javascript" src="">$.get(\'http://passport.sogou-inc.com/logout.jsp\')</script>';
		$this->success($message, $rurl);//通过js注销passport用以便实现同步注销
    }
}