<?php
/**
 *  svn update 控制器
 *  zouchao@sogou-inc.com
 *  2016-05-26
 */

namespace Rsync\Controller;

use Common\Controller\RsyncbaseController;

class UpdateController extends RsyncbaseController
{
    protected function _initialize()
    {
    }

    /*svn更新*/
    public function svnupdateAction()
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
    public function svncheckoutAction()
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
            if ($istest && $app['svn_test_url']) {
                $svn_url = substr($app['svn_test_url'], -1) == '/' ? $app['svn_test_url'] : "{$app['svn_test_url']}/";
                $svn_auth = $app['svn_test_auth'];
                $svn_username = $app['svn_test_username'];
                $svn_password = $app['svn_test_password'];
            } else {
                $svn_url = substr($app['svn_url'], -1) == '/' ? $app['svn_url'] : "{$app['svn_url']}/";
                $svn_auth = $app['svn_auth'];
                $svn_username = $app['svn_username'];
                $svn_password = $app['svn_password'];
            }
            if (!@file_exists("{$app_root}/.svn")) {
                $cmd = "cd {$app_root}/ && rm -rf ./*";
                $cmds[] = $cmd;
                @exec($cmd, $ret_arr, $ret_var);
                if ($ret_var !== 0) {
                    $this->error("SVN检出时清空项目根目录失败[{$app_root}/]");
                }
                $cmd = "svn checkout {$svn_url} {$app_root}/";
                if ($svn_auth) {//需要认证
                    $cmd .= " --username={$svn_username} --password={$svn_password}";
                }
                @exec($cmd, $ret_arr, $ret_var);
                $cmd = @preg_replace('/\s?\-\-(password)=\S*/i', '', $cmd);
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
