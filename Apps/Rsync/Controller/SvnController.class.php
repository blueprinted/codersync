<?php
/**
 *  svn 控制器
 *  zouchao@sogou-inc.com
 *  2018-07-25
 */

namespace Rsync\Controller;

use Common\Controller\RsyncbaseController;

class SvnController extends RsyncbaseController
{
    protected function _initialize()
    {
    }

    /*svn更新*/
    public function updateAction()
    {
        if (I('post.dosubmit')) {
            $group = I('get.group', '');
            $appid = I('get.appid', 0, 'intval');
            $istest = I('get.istest', 0, 'intval');
            $ver = I('post.ver', 0, 'intval');//版本号
            $dirs = I('post.dir', array());//svn更新的目录或文件
            $dirs = is_array($dirs) ? $dirs : array($dirs);//处理成规范格式
            $resu = app_svnupdate($group, $appid, $istest, $dirs, $ver);
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

     /*svn检出*/
    public function checkoutAction()
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
            if ($app['repository'] != 'svn') {
                $this->error('该项目不是托管在SVN上的项目');
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
                $repo_password = $app['repo_password_test'];
            } else {
                $repo_url = $app['repo_url'];
                $repo_auth = $app['repo_auth'];
                $repo_username = $app['repo_username'];
                $repo_password = $app['repo_password'];
            }
            $repo_url = substr($repo_url, -1) == '/' ? $repo_url : "{$repo_url}/";
            if (!@file_exists("{$app_root}/.svn")) {
                if (strlen($repo_url) < 1) {
                    $this->error("没有配置SVN地址[{$app_root}/]");
                }
                if ($repo_auth && empty($repo_username)) {
                    $this->error("没有配置SVN的用户名[{$app_root}/]");
                }
                $cmd = "cd {$app_root}/ && rm -rf *";
                $cmds[] = $cmd;
                @exec($cmd, $ret_arr, $ret_var);
                if ($ret_var !== 0) {
                    $this->error("SVN检出时清空项目根目录失败[{$app_root}/]");
                }
                $repo_bin_path = $app['repo_bin_path'];
                $cmd = "{$repo_bin_path} checkout {$repo_url} {$app_root}/";
                if ($repo_auth) {//需要认证
                    $cmd .= " --username={$repo_username} --password={$repo_password} 2>&1";
                }
                @exec($cmd, $ret_arr, $ret_var);
                //$cmd = @preg_replace('/\s?\-\-(password)=\S*/i', '', $cmd);
                if ($ret_var !== 0) {
                    $this->error("SVN检出失败[{$cmd}]");
                }
                $cmds[] = $cmd;
            }

            $this->success('操作成功', '', array('data'=>implode("\r\n", $cmds)."\r\n".implode("\r\n", $ret_arr)));
        }

        $this->error('未识别的请求');
    }
}
