<?php
/**
 *  deploy发布控制器
 *  zouchao@sogou-inc.com
 *  2016-05-26
 */

namespace Rsync\Controller;

use Think\Controller;

class DeployController extends Controller
{
    protected function _initialize()
    {
        if (!B('Admin\Behavior\AuthCheck')) {
            $this->error('请先登录', U('Admin/Public/login'), 1, array('code'=>'need_login'));
        }
    }

    /*上线流程列表*/
    public function indexAction()
    {
        $group = I('get.group', '');
        $appid = I('get.appid', 0, 'intval');
        $istest = I('get.istest', 0, 'intval');
        $testval = I('get.testval', -1, 'intval');
        $username = I('get.username', '', 'trim');
        $page = I('get.page', 1, 'intval');
        $perpage = I('get.perpage', 20, 'intval');
        $debug = I('get.debug', '', 'trim');

        $page = max($page, 1);
        $perpage = max(min($perpage, 50), 5);

        $appgroup = load_group($group);
        $applist = load_app_list($appgroup);
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
        if (!$app['is_sogou_docker']) {
            $this->error('该项目不是搜狗docker项目');
        }

        $pageshow = $ordersql = '';
        $wherearr = $list = $args = array();

        $wherearr['appid'] = $app['id'];
        $wherearr['groupid'] = $appgroup['id'];
        if ($testval >= 0) {
            $wherearr['istest'] =  ($testval == 0 ? 0 : 1);
        }
        //构建where及url参数
        if (strlen($username) > 0) {
            $wherearr['username'] = array('like', "%{$username}%");
            $args['username'] = $username;
        }
        $ordersql = "`id` DESC";
        $args['page'] = $page;
        $args['perpage'] = $_GET['perpage'];

        $rsyncDeployModel = M('rsync_deploy');

        $count = $rsyncDeployModel->where($wherearr)->count();
        if ($count > 0) {
            $list = $rsyncDeployModel->where($wherearr)->order($ordersql)->page($page, $perpage)->select();
            $pager = new \Think\Page($count, $perpage);
            $pager->parameter = array_merge($pager->parameter, $args);
            $pageshow = $pager->show();
        }
        $statuses = array(
           0 => '待上线',
           1 => '已上线',
           2 => '已回滚',
        );

        $this->assign('debug', $debug==='true'?true:false);
        $this->assign('group', $group);
        $this->assign('appid', $appid);
        $this->assign('istest', $istest);
        $this->assign('testval', $testval);
        $this->assign('app', $app);
        $this->assign('list', $list);
        $this->assign('statuses', $statuses);
        $this->assign('username', substr(session('USERNAME'), 0, strpos(session('USERNAME'), '@')));

        $this->display();
    }

    /*解除发版锁定 出现异常的时候才会用到*/
    public function unlockAction()
    {
        if (I('post.dosubmit')) {
            $testval = I('post.testval', 0, 'intval');
            $lockkey = 'rsync_docker_app_deploy' . ($testval ? '_test' : '');
            if (!rsync_unlock($lockkey)) {
                $this->error('解除锁定失败['.($testval?'测试':'正式').']');
            }
            $this->success('解除锁定成功['.($testval?'测试':'正式').']');
        }
        $this->error('未识别的请求');
    }

    /*新增或编辑上线流程*/
    public function appAction()
    {
        $group = I('get.group', '');
        $appid = I('get.appid', 0, 'intval');
        $istest = I('get.istest', 0, 'intval');
        $deployid = I('get.deployid', 0, 'intval');

        $appgroup = load_group($group);
        $applist = load_app_list($appgroup);
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
        if (!$app['is_sogou_docker']) {
            $this->error('该项目不是搜狗docker项目');
        }

        $deployinfo = $pack = $back = array();
        $rsyncDeployModel = M('rsync_deploy');
        if ($deployid > 0) {
            $deployinfo = rsync_deploy_get($deployid);
            if (empty($deployinfo)) {
                $this->error('要更新的发版记录不存在');
            }
            $pack = json_decode($deployinfo['pack_info'], true);
            $back = json_decode($deployinfo['back_info'], true);
        }

        $this->assign('group', $group);
        $this->assign('appgroup', $appgroup);
        $this->assign('appid', $appid);
        $this->assign('istest', $istest);
        $this->assign('app', $app);
        $this->assign('deployid', $deployid);
        $this->assign('deployinfo', $deployinfo);
        $this->assign('pack', $pack);
        $this->assign('back', $back);

        $this->display();
    }

    /*新增或编辑上线流程的提交处理*/
    public function app_postAction()
    {
        if (I('post.dosubmit')) {
            $group = I('get.group', '');
            $appid = I('get.appid', 0, 'intval');
            $istest = I('post.istest', null, 'intval');
            $deployid = I('get.deployid', 0, 'intval');
            $pack_revision = I('post.pack_revision', '', 'trim');
            $back_revision = I('post.back_revision', '', 'trim');
            $pack_taskid = I('post.pack_taskid', '', 'trim');
            $back_taskid = I('post.back_taskid', '', 'trim');
            $msglog = I('post.msglog', '', 'trim');
            $remark = I('post.remark', '', 'trim');

            $pack_info = $back_info = array();

            $appgroup = load_group($group);
            $applist = load_app_list($appgroup);
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
            if (!$app['is_sogou_docker']) {
                $this->error('该项目不是搜狗docker项目');
            }

            if (strlen($pack_taskid) < 1) {
                $this->error('没有指定发版的镜像');
            }
            if (strlen($msglog) < 1) {
                $this->error('没有填写发版日志');
            }
            if (strlen($back_taskid) < 1) {
                $this->error('没有指定回滚的镜像');
            }
            if ($pack_revision == $back_revision) {
                $this->error('回滚的镜像与发版的镜像不能是同一个镜像[task_id不同但是是同一个镜像]');
            }
            if (is_null($istest)) {
                $this->error('没有指定正式环境或测试环境');
            }

            if ($deployid > 0) {
                $deployinfo = rsync_deploy_get($deployid);
                if (empty($deployinfo)) {
                    $this->error('要更新的发版记录不存在');
                }
                if ($deployinfo['locked']) {
                    $this->error('该版本已经锁定，不能进行编辑');
                }
                if ($deployinfo['status'] != 0) {
                    $this->error('该版本已经不是待发版状态，不能进行编辑');
                }
                //只能编辑自己的镜像
                if ($deployinfo['uid'] != session('UID')) {
                    $this->error('只能编辑自己发起的发版任务');
                }
            }

            //验证镜像信息
            $requrl = C("SOGOU_HARBOR_API")."?repo_sig=".md5($istest ? $app['repo_url_test'] : $app['repo_url']);
            $resu = pack_get_list($requrl, 0);
            if ($resu['code'] != 0) {
                $this->error("获取镜像列表接口异常[code={$resu['code']},msg={$resu['msg']}]");
            }
            $packlist = $resu['data']['list'];
            foreach ($packlist as $item) {
                if ($pack_taskid == $item['task_id']) {
                    $pack_info = $item;
                }
                if ($back_taskid == $item['task_id']) {
                    $back_info = $item;
                }
                if (!empty($pack_info) && !empty($back_info)) {
                    break;
                }
            }
            if (empty($pack_info)) {
                $this->error('发版的镜像不存在');
            }
            if (empty($back_info)) {
                $this->error('回滚的镜像不存在');
            }

            $newarr = array(
              'id' => $deployid,
              'appid' => $appid,
              'uid' => session('UID'),
              'username' => session('USERNAME'),
              'pack_taskid' => $pack_taskid,
              'pack_revision' => $pack_revision,
              'pack_info' => json_encode($pack_info),
              'back_taskid' => $back_taskid,
              'back_revision' => $back_revision,
              'back_info' => json_encode($back_info),
              'istest' => $istest,
              'msglog' => $msglog,
              'remark' => $remark,
            );
            if ($deployid > 0) {
                $_msg = '更新';
                $newarr['update_time'] = time();
            } else {
                $_msg = '新增';
                $newarr['groupid'] = $appgroup['id'];
                $newarr['status'] = 0;
                $newarr['locked'] = 0;
                $newarr['cmds'] = '';
                $newarr['create_time'] = time();
                $newarr['update_time'] = 0;
                $newarr['deploy_time'] = 0;
                $newarr['rollback_time'] = 0;
            }

            $resu = rsync_deploy_set($newarr);
            if ($resu < 1) {
                $this->error("{$_msg}失败");
            }
            $this->success("{$_msg}成功");
        }

        $this->error('未识别的请求');
    }

    /*删除发版任务*/
    public function deploy_deleteAction()
    {
        if (I('post.dosubmit')) {
            $group = I('get.group', '');
            $appid = I('get.appid', 0, 'intval');
            $deployid = I('post.deployid', 0, 'intval');

            $applist = load_app_list($group);
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
            if (!$app['is_sogou_docker']) {
                $this->error('该项目不是搜狗docker项目');
            }

            $deployinfo = rsync_deploy_get($deployid);
            if (empty($deployinfo)) {
                $this->error("要删除的发版记录不存在");
            }
            if ($deployinfo['uid'] != session('UID')) {
                $this->error('只能删除自己发起的上线流程');
            }
            if ($deployinfo['locked']) {
                $this->error("该版本已经锁定，不能删除");
            }
            if ($deployinfo['status'] != 0) {
                $this->error('该版本已处于非待发版状态，不能进行删除');
            }
            if ($deployinfo['uid'] != session('UID')) {
                $this->error('只能删除自己创建的发版任务');
            }
            if (false === rsync_deploy_delete($deployinfo)) {
                $this->error('删除失败');
            }
            $this->success('删除成功');
        }
        $this->error('未识别的请求');
    }

    /*发版操作*/
    public function app_deployAction()
    {
        if (I('post.dosubmit')) {
            $group = I('get.group', '', 'trim');
            $appid = I('get.appid', 0, 'intval');
            $deployid = I('post.deployid', 0, 'intval');
            $rollback = I('post.rollback', 0, 'intval');
            $username = I('post.username', '', 'trim');
            $password = I('post.password', '', 'trim');

            $opera_name = $rollback ? '回滚' : '发版';
            $deployinfo = rsync_deploy_get($deployid);
            if (empty($deployinfo)) {
                $this->error("要{$opera_name}的发版记录不存在");
            }
            if ($deployinfo['locked']) {
                $this->error("该版本已经锁定，不能进行{$opera_name}");
            }
            if ($deployinfo['status'] == 2 && !$rollback) {
                $this->error("该版本已经是回滚状态，不能进行{$opera_name}");
            }
            if (empty($username)) {
                $this->error('没有填写账号');
            }
            if (empty($password)) {
                $this->error('没有填写密码');
            }
            $passport = array(
                'username' => $username,
                'password' => $password,
            );

            $resu = app_deploy($group, $appid, $deployinfo, $rollback, $passport);
            $info = array(
              'data' => (isset($resu['data']['cmds']) && $resu['data']['cmds'] ? implode("\r\n", $resu['data']['cmds'])."\r\n" : '') . (isset($resu['data']['lines']) && $resu['data']['lines'] ? "\r\n".implode("\r\n", $resu['data']['lines']) : ''),
            );

            if ($resu['status'] != 0) {
                $this->error($resu['message'], '', $info);
            }

            $this->success($resu['message'], '', $info);
        }

        $this->error('未识别的请求');
    }

    /*获取harbor的docker镜像列表*/
    public function packlistAction()
    {
        //获取参数
        $group = I('get.group', '');
        $appid = I('get.appid', 0, 'intval');
        $istest = I('get.istest', 0, 'intval');
        $tag = I('get.tag', '', 'trim');
        $isback = I('get.isback', '', 'intval'); //是否为选择回滚镜像
        $perpage = I('get.perpage', '', 'intval');
        $page = I('get.page', '', 'intval');
        $winopen = I('get.winopen', 0, 'intval');

        $applist = load_app_list();
        $app = isset($applist[$appid]) ? $applist[$appid] : array();

        $errmsg = '';

        if (empty($app)) {
            $errmsg = '项目不存在。';
        }
        if (empty($errmsg)) {
            $app = init_rsync_app_conf($app, $retinfo);
            if ($app === false) {
                $errmsg = '项目配置不正确, ' . implode(', ', $retinfo);
            }
        }
        if (empty($errmsg)) {
            if ($app['repository'] != 'git') {
                $errmsg = '该项目不是托管在GIT上的项目';
            }
        }
        if (empty($errmsg)) {
            if (!$app['is_sogou_docker']) {
                $errmsg = '该项目不是搜狗docker项目';
            }
        }

        //处理参数
        $perpage = in_array($perpage, array(5, 10, 20, 40, 60, 80, 100), true) ? $perpage : 20;
        $page = max($page, 1);
        if ($winopen) {
            $perpage = 6;
        }

        $pageshow = '';
        $list = $args = array();

        //构建url参数
        if (!empty($_GET['tag'])) {
            $args['tag'] = $_GET['tag'];
        }

        if (empty($errmsg)) {
            $requrl = C("SOGOU_HARBOR_API")."?repo_sig=".md5($istest ? $app['repo_url_test'] : $app['repo_url']);
            $resu = pack_get_list($requrl, $perpage, $page, $tag);
            if ($resu['code'] != 0) {
                $errmsg = "获取镜像列表接口异常[code={$resu['code']},msg={$resu['msg']}]";
            }
        }

        if (empty($errmsg)) {
            $count = $resu['data']['count'];
            $list = $resu['data']['list'];
            if ($count > 0) {
                $pager = new \Think\Page($count, $perpage);
                $pager->parameter = array_merge($pager->parameter, $args);
                $pageshow = $pager->show();
            }
        }

        $this->assign('errmsg', $errmsg);
        $this->assign('group', $group);
        $this->assign('appid', $appid);
        $this->assign('app', $app);
        $this->assign('tag', $tag);
        $this->assign('istest', $istest);
        $this->assign('list', $list);
        $this->assign('isback', $isback);
        $this->assign('pageshow', $pageshow);

        if ($winopen) {
            C('SHOW_PAGE_TRACE', false);
            $this->display('packlist_winopen');
        } else {
            $this->display();
        }
    }
}
