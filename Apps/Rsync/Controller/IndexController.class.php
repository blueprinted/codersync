<?php
/**
 *  首页控制器
 *  zouchao@sogou-inc.com
 *  2016-05-26
 */

namespace Rsync\Controller;

use Think\Controller;

class IndexController extends Controller
{
    protected function _initialize()
    {
        if (!B('Admin\Behavior\AuthCheck')) {
            $this->error('请先登录', U('Admin/Public/login'), 1, array('code'=>'need_login'));
        }
    }

    public function indexAction()
    {
        $group = I('get.group', '');
        $applist = load_app_list();
        $this->assign('group', $group);
        $this->assign('applist', $applist);
        $this->display();
    }

    public function appAction()
    {
        $group = I('get.group', '');
        $appid = I('get.appid', 0);
        $istest = I('get.istest', 0, 'intval');
        $dir = dirfilter(I('get.dir'));//浏览的目录

        $url_params = "group={$group}&appid={$appid}&istest={$istest}&dir=".dir_convert($dir);

        $applist = load_app_list();
        $app = isset($applist[$appid]) ? $applist[$appid] : array();
        if (empty($app)) {
            $this->error('项目不存在。');
        }

        $app = init_rsync_app_conf($app, $retinfo);
				if ($app === false) {
						$this->error('项目配置不正确, ' . implode(', ', $retinfo));
				}

        if ($app['root_dir'] && empty($app['root_dir_test'])) {
            $istest = 0;
        } elseif (empty($app['root_dir']) && $app['root_dir_test']) {
            $istest = 1;
        }

        //获取项目目录
    		$app_root = $istest ? realpath($app['root_dir_test']) : realpath($app['root_dir']);
    		if ($app_root === false) {
    				if (($istest && strlen($app['root_dir_test']) > 0 && !mkdir_recursive($app['root_dir_test'], 0755))
    						|| (!$istest && strlen($app['root_dir']) > 0 && !mkdir_recursive($app['root_dir'], 0755))) {
    						$this->error("项目目录不存在且创建失败[".($istest?$app['root_dir_test']:$app['root_dir'])."]");
    				}
    				$app_root = $istest ? realpath($app['root_dir_test']) : realpath($app['root_dir']);
    		}

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

        $app['repository'] = isset($app['repository']) ? strtolower($app['repository']) : 'svn';

        $dirlist = dirlist($realdir, $app['repository']=='svn'?'grep -v .svn':'grep -v .git');

        //检测是否为svn检出目录
        if (!@file_exists("{$app_root}/.svn")) {
            $svn_checkout = false;
        } else {
            $svn_checkout = true;
        }

                //检测是否为git仓库目录
        if (!@file_exists("{$app_root}/.git")) {
            $git_checkout = false;
        } else {
            $git_checkout = true;
        }

        //分割dir构建层级目录
        $dirs = dir_layer_list($dir);

        $this->assign('group', $group);
        $this->assign('app', $app);
        $this->assign('dirlist', $dirlist);
        $this->assign('dir', $dir);
        $this->assign('app_root', $app_root);
        $this->assign('istest', $istest);
        $this->assign('svn_checkout', $svn_checkout);
        $this->assign('git_checkout', $git_checkout);
        $this->assign('dirs', $dirs);
        $this->assign('url_params', $url_params);

        $this->display();
    }
    public function historyAction()
    {
        $group = I('get.group', '');
        load_app_list($group);
        $type = I('get.type', 0);
        $date2 = date('Y-m-d', strtotime(' last sunday'));
        $date1 = date('Y-m-d', strtotime($date2) - 3600*24*6);
        $svnList = C('SOGOU_SVN_LIST');
        $c = 'svn ls '.C('SOGOU_SVN_ROOT').C('SOGOU_SVN_LIST').' --username '.C('SOGOU_SVN_USERNAME').' --password '.C('SOGOU_SVN_PASSWORD');
        exec($c, $out);
        $svnlist = array();
        foreach ($out as $xm) {
            $svnlist[$xm] = array();

            if ($xm == 'shoujisapp/') {
                continue;
            }

            $cmd  = "svn log ".C('SOGOU_SVN_ROOT').C('SOGOU_SVN_LIST')."$xm --xml -r \{$date1\}:\{$date2\} -v --username ".C('SOGOU_SVN_USERNAME')." --password ".C('SOGOU_SVN_PASSWORD');
            $a = shell_exec($cmd);

            $xml = simplexml_load_string($a);
            foreach ($xml as $x) {
                $version = $x->attributes();

                $b = $x->paths;
                $msg = $x->msg;
                $author = $x->author;
                $time = date('Y-m-d H:i:s', strtotime($x->date));
                $ka = $version.'  ' .$author.'  '.$time . '  '.$msg;
                foreach ($b as $c) {
                    $action = (String)$c->path['action'];
                    $kind = (String)$c->path['kind'][0];
                    $path = (String)$c->path[0];
                    $url = C('SOGOU_SVN_ROOT').$path;
                    $svnlist[$xm][] = array("action"=>$action,"kind"=>$kind,"path"=>$path,"ka"=>$ka,"url"=>$url);
                }
            }
        }
        $this->assign('svnlist', $svnlist);
        $this->assign('group', $group);
        $this->display();
    }

    /**
     *  为每个项目生成语法规范的过滤文件配置
     */
    public function generateFilterConfigAction()
    {
        $appid = I('get.appid', 0);
        $group = I('get.group', '');
        if (empty($group)) {
            $this->error('参数错误。');
        }

        if (I('get.dosubmit')) {
            $app = array();
            $applist = load_app_list();

            $app = isset($applist[$appid]) ? $applist[$appid] : array();
            if (empty($app)) {
                $this->error('项目不存在。');
            }

            if ($app) {
                generate_filter_config($app, $group, $info);
            } else {
                foreach ($applist as $app) {
                    generate_filter_config($app, $group, $info);
                }
            }

            $message = '生成完毕['.($app?"appid={$app['id']}":"all(".count($applist).")").']';
            if ($info) {
                $message .= "<br/>".implode('<br/>', $info);
            }

            $this->success($message);
        }

        $this->error('未识别的请求');
    }
}
