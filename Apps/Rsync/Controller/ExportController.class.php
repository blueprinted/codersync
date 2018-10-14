<?php
/**
 *  svn export 控制器
 *  zouchao@sogou-inc.com
 *  2016-05-26
 */

namespace Rsync\Controller;

use Common\Controller\RsyncbaseController;

class ExportController extends RsyncbaseController
{
    protected function _initialize()
    {
    }

    /*svn导出*/
    public function svnexportAction()
    {
        if (I('post.dosubmit')) {
            $appid = I('get.appid', 0, 'intval');
            $istest = I('get.istest', 0, 'intval');
            $dirs = I('post.dir', array());//导出的目录或文件
            $dirs = is_array($dirs) ? $dirs : array($dirs);//处理成规范格式

            foreach ($dirs as $key => $dir) {
                $dir = dirfilter($dir);
                if (empty($dir)) {
                    unset($dirs[$key]);
                } else {
                    $dirs[$key] = $dir;
                }
            }

            if (empty($dirs)) {
                $this->error('没有指定要导出的文件或目录');
            }

            $applist = C('APP_LIST');
            $app = array();
            if ($appid > 0) {
                $app = isset($applist[$appid]) ? $applist[$appid] : array();
            }
            if (empty($app)) {
                $this->error('项目不存在。');
            }

            if ($app['root_dir'] && empty($app['root_dir_test'])) {
                $istest = 0;
            } elseif (empty($app['root_dir']) && $app['root_dir_test']) {
                $istest = 1;
            }

            //获取项目目录
            $app_root = $istest ? realpath($app['root_dir_test']) : realpath($app['root_dir']);
            if ($app_root === false) {
                $this->error("项目目录不存在[".($istest?$app['root_dir_test']:$app['root_dir'])."]");
            }

            $counter = $counter_succ = 0;
            $cmds = array();
            foreach ($dirs as $dir) {
                $realdir = realpath($app_root.'/'.$dir);
                if (substr($realdir, 0, strlen($app_root)) != $app_root) {
                    //防止读取项目的上级目录
                    $realdir = $app_root;
                }
                if (strlen($realdir) > strlen($app_root)) {
                    $dir = substr($realdir, strlen($app_root)+1);
                } else {
                    $dir = '';
                }
                if (is_dir($realdir) && strlen($dir) > 0 && substr($dir, -1) != '/') {
                    $dir .= '/';
                }

                //svn export
                $svn_url = substr($app['svn_url'], -1) == '/' ? $app['svn_url'] : "{$app['svn_url']}/";
                $cmd = "svn export {$svn_url}{$dir} {$app_root}/{$dir} --force";
                if ($app['svn_auth']) {//需要认证
                    $cmd .= " --username={$app['svn_username']} --password={$app['svn_password']}";
                }
                @exec($cmd, $ret_arr, $ret_var);
                $counter++;
                if ($ret_var !== 0) {
                    $cmds[] = @preg_replace('/\s?\-\-(username|password)=\S*/i', '', $cmd).' [fail]';
                } else {
                    $counter_succ++;
                    $cmds[] = @preg_replace('/\s?\-\-(username|password)=\S*/i', '', $cmd). ' [succ]';
                }
            }

            //结果
            if ($counter_succ != $counter) {
                if ($counter_succ < 1) {
                    $message = '导出失败';
                } else {
                    $message = '导出成功[部分成功]';
                }
            } else {
                $message = '导出成功';
            }
            $this->success($message, '', array('data'=>implode("\r\n", $cmds)."\r\n".implode("\r\n", $ret_arr)));
        }

        $this->error('未识别的请求');
    }
}
