<?php
/**
 *  git 控制器
 *  zouchao@sogou-inc.com
 *  2018-07-25
 */

namespace Rsync\Controller;

use Common\Controller\RsyncbaseController;

class GitController extends RsyncbaseController
{
    protected function _initialize()
    {
    }

    /*git pull*/
    public function pullAction()
    {
        if (I('post.dosubmit')) {
            $group = I('get.group', '');
            $appid = I('get.appid', 0, 'intval');
            $istest = I('get.istest', 0, 'intval');

            $resu = app_gitpull($group, $appid, $istest);
            $info = array(
                'data' => (isset($resu['data']['cmds']) && $resu['data']['cmds'] ? implode("\r\n", $resu['data']['cmds'])."\r\n" : '') . (isset($resu['data']['lines']) && $resu['data']['lines'] ? implode("\r\n", $resu['data']['lines']) : ''),
            );

            if ($resu['status'] != 0) {
                $this->error($resu['message'], '', $info);
            }

            $this->success($resu['message'], '', $info);
        }

        $this->error('未识别的请求');
    }

    /*git pull*/
    public function updateAction()
    {
        if (I('post.dosubmit')) {
            $group = I('get.group', '');
            $appid = I('get.appid', 0, 'intval');
            $istest = I('get.istest', 0, 'intval');
            $dirs = I('post.dir', array());//git更新的目录或文件
            $dirs = is_array($dirs) ? $dirs : array($dirs);//处理成规范格式

            $resu = app_gitupdate($group, $appid, $istest, $dirs);
            $info = array(
                'data' => (isset($resu['data']['cmds']) && $resu['data']['cmds'] ? implode("\r\n", $resu['data']['cmds'])."\r\n" : '') . (isset($resu['data']['lines']) && $resu['data']['lines'] ? implode("\r\n", $resu['data']['lines']) : ''),
            );

            if ($resu['status'] != 0) {
                $this->error($resu['message'], '', $info);
            }

            $this->success($resu['message'], '', $info);
        }

        $this->error('未识别的请求');
    }

    /*git clone*/
    public function cloneAction()
    {
        if (I('post.dosubmit')) {
            $group = I('get.group', '');
            $appid = I('get.appid', 0, 'intval');
            $istest = I('get.istest', 0, 'intval');

            $applist = load_app_list();
            $app = isset($applist[$appid]) ? $applist[$appid] : array();
            if (empty($app)) {
                $this->error('项目不存在。');
            }

            $app = init_rsync_app_conf($app, $retinfo);
            if ($app === false) {
                $this->error('项目配置不正确, ' . implode(', ', $retinfo));
            }
            if ($app['repository'] != 'git') {
                $this->error('该项目不是托管在GIT上的项目');
            }
            //强制转换istest
            if ($app['root_dir'] && empty($app['root_dir_test'])) {//只有正式环境
                $istest = 0;
            } elseif (empty($app['root_dir']) && $app['root_dir_test']) {//只有测试环境
                $istest = 1;
            }

            //获取项目目录
            $app_root = $istest ? realpath($app['root_dir_test']) : realpath($app['root_dir']);
            if ($app_root === false) {
                $this->error("项目目录不存在[".($istest?$app['root_dir_test']:$app['root_dir'])."]");
            }

            $cmds = array();
            if ($istest && $app['repo_url_test']) {
                $repo_url = $app['repo_url_test'];
                $repo_auth = $app['repo_auth_test'];
                $repo_username = $app['repo_username_test'];
                $repo_username = $app['repo_password_test'];
            } else {
                $repo_url = $app['repo_url'];
                $repo_auth = $app['repo_auth'];
                $repo_username = $app['repo_username'];
                $repo_password = $app['repo_password'];
            }
            if (!@file_exists("{$app_root}/.git")) {
                if (strlen($repo_url) < 1) {
                        $this->error("没有配置GIT地址[{$app_root}/]");
                }
                if ($repo_auth && empty($repo_username)) {
                    $this->error("没有配置GIT的用户名[{$app_root}/]");
                }
                $cmd = "cd {$app_root}/ && rm -rf *";
                $cmds[] = $cmd;
                @exec($cmd, $ret_arr, $ret_var);
                if ($ret_var !== 0) {
                    $this->error("git clone前清空项目根目录失败[{$app_root}/]");
                }
                $authstring = '';
                if (false && $repo_auth && strpos($repo_url, "@") === false) {//非ssh协议且需要认证
                    $authstring = urlencode("{$repo_username}:{$repo_password}");
                    if (preg_match('/^https?:\/\/\S+$/i', $repo_url)) {
                        $repo_url = preg_replace('/^(https?:\/\/)(\S+)$/i', "\\1{$authstring}@\\2", $repo_url);
                    }
                }
                                $repo_bin_path = $app['repo_bin_path'];
                                $cmd = "{$repo_bin_path} clone {$repo_url} {$app_root}/ 2>&1";
                @exec($cmd, $ret_arr, $ret_var);
                if ($repo_auth && strlen($authstring) > 0) {
                    $cmd = str_replace("{$authstring}@", '', $cmd);
                }
                if ($ret_var !== 0) {
                                      $cmd = "{$cmd} [ret_var={$ret_var}]";
                                        $cmds[] = $cmd;
                    $this->error("git clone失败[{$cmd}]", '', array('data'=>implode("\r\n", $cmds)."\r\n".implode("\r\n", $ret_arr)));
                }
                                $cmds[] = $cmd;
            }

            $this->success('操作成功', '', array('data'=>implode("\r\n", $cmds)."\r\n".implode("\r\n", $ret_arr)));
        }

        $this->error('未识别的请求');
    }
}
