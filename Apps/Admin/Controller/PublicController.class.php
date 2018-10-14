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

	/*检查是否登录*/
	public function checkloginAction() {
		if(session('?UID') || session('?USERNAME')){//已经登录
			$this->success('已登录');
    	} else {
			$this->error('未登录');
		}
	}

	/*登录*/
    public function loginAction() {
		$rurl = urlrewrite_decode(I('get.rurl', ''));
    if (empty($rurl)) {
      $rurl = urlrewrite_decode(I('get.targetUrl', ''));
    }
		if(empty($rurl)) {
			$rurl = $_SERVER['HTTP_REFERER'];
		}
		if(!@preg_match('@'.getSiteUrl().'@', $rurl)) {
      $rurl = U('Home/Index/index');
		}
    	if(session('?UID') || session('?USERNAME')){//已经登录
			$this->success('登录成功', $rurl);
    	}
		if($ptoken = I('get.ptoken', '')) {
			if(($ptoken = base64_decode($ptoken)) !== false) {
				$pemfile = C('PANDORA_PEMFILE');
				$publicKey = openssl_get_publickey(@file_get_contents($pemfile));
				openssl_public_decrypt($ptoken,$sogou_user,$publicKey);
				$sogou_user = json_decode($sogou_user, true);
				if(5 > abs(round($sogou_user['ts']/1000) - time())) {/*验证时间戳 允许5秒时间差*/
					$username = "{$sogou_user['uid']}@sogou-inc.com";
					$nickname = $sogou_user['uid'];
					$unumber = $sogou_user['uno'];/*员工号*/
					$realname = $sogou_user['name'];

					$userModel = M('user');
					$where['username'] = $username;
					$user = $userModel->where($where)->find();
					if(empty($user)) {
						$ips = get_onlineip();
						$regip = empty($ips) || strpos($ips, ',') === false ? $ips : substr($ips, 0, strpos($ips, ','));
						$user = array(
							'username' => $username,
							'nickname' => $nickname,
							'realname' => $realname,
							'unumber' => $unumber,
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
						session('UTYPE', $user['utype']);
						$this->success('登录成功', $rurl);
					}
				}
			}
		}
		$loginurl = U('Admin/Public/login', array('rurl'=>urlrewrite_encode($rurl)));
		$loginurl = substr($loginurl, stripos($loginurl,'Admin/Public/login'));
		$rurl = 'https://login.sogou-inc.com/?appid='.C('PANDORA_APPID').'&sso_redirect='.rawurlencode(getSiteUrl().$loginurl);
		$this->error('请先登录', $rurl, 0);
    }

	/*注销*/
    public function logoutAction(){
		$rurl = urlrewrite_decode(I('get.rurl', '', 'urldecode'));
    if (empty($rurl)) {
      $rurl = urlrewrite_decode(I('get.targetUrl', ''));
    }
		if(empty($rurl)) {
			$rurl = $_SERVER['HTTP_REFERER'];
		}
		if(!@preg_match('@'.getSiteUrl().'@', $rurl)) {
      $rurl = U('Home/Index/index');
		}

    session('UID',null);
		session('USERNAME',null);
		session('NICKNAME',null);
		session('UTYPE',null);
		cookie('jpassport-sp', null);
		setcookie('jpassport-sp', '', time()-3600, '/');
		$message = '注销成功<script type="text/javascript" src="">$.get(\'https://oa.sogou-inc.com/logout.jsp\')</script>';//https://oa.sogou-inc.com/sso/logout.jsp
		$this->success($message, $rurl);//通过js注销passport用以便实现同步注销
    }

    /*注销Pandora*/
    public function logoutOaAction() {
      $rurl = urlrewrite_decode(I('get.rurl', '', 'urldecode'));
  		if(empty($rurl)) {
  			$rurl = $_SERVER['HTTP_REFERER'];
  		}
  		if(!@preg_match('@'.getSiteUrl().'@', $rurl)) {
  			$rurl = U('Home/Index/index');
  		}
      if (substr($rurl, 0, 4) != 'http') {
        $rurl = substr($rurl, 0, 1) == '/' ? $rurl : "/{$rurl}";
        $rurl = "http://{$_SERVER['HTTP_HOST']}{$rurl}";
      }
      $outstr = '';
      $outstr .= '<!DOCTYPE html>';
      $outstr .= '<html lang="zh-CN">';
      $outstr .= '<head>';
      $outstr .= '<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />';
      $outstr .= '<title>正在跳转..</title>';
      $outstr .= '<meta http-equiv="refresh" content="1;url='.$rurl.'">';
      $outstr .= '</head>';
      $outstr .= '<body>正在跳转..</body>';
      $outstr .= '<script type="text/javascript" src="https://oa.sogou-inc.com/logout.jsp"></script>';
      $outstr .= '</html>';
      echo $outstr;
      exit;
    }
}
