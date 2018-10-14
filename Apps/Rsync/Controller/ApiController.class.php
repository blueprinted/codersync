<?php

/**
 *  代码发布系统API接口
 *  zouchao@sogou-inc.com
 *  2018-02-01
 */

namespace Rsync\Controller;

use Common\Controller\ApibaseController;

class ApiController extends ApibaseController
{
    public function updateAction()
    {
        $group = I('post.group', '');
        $appid = I('post.appid', 0, 'intval');
        $istest = I('post.istest', 0, 'intval');
        $ver = I('post.ver', 0, 'intval');//版本号
        $dirs = I('post.dir', array());//svn更新的目录或文件
        $dirs = is_array($dirs) ? $dirs : array($dirs);//处理成规范格式

        $resu = app_svnupdate($group, $appid, $istest, $dirs, $ver);
        $info = array(
            'data' => array(
                'cmds' => isset($resu['data']['cmds']) ? $resu['data']['cmds'] : array(),
                'lines' => isset($resu['data']['lines']) ? $resu['data']['lines'] : array()
            ),
        );

        $this->apimessage($resu['message'], $resu['status'], $info);
    }

    public function rsyncAction()
    {
        $group = I('post.group', '');
        $appid = I('post.appid', 0, 'intval');
        $istest = I('post.istest', 0, 'intval');
        $dirs = I('post.dir', array());//rsync发布的目录或文件
        $dirs = is_array($dirs) ? $dirs : array($dirs);//处理成规范格式

        $resu = app_rsync($group, $appid, $istest, $dirs);
        $info = array(
            'data' => array(
                'cmds' => isset($resu['data']['cmds']) ? $resu['data']['cmds'] : array(),
                'lines' => isset($resu['data']['lines']) ? $resu['data']['lines'] : array(),
                'files' => isset($resu['data']['files']) ? $resu['data']['files'] : array(),
            ),
        );

        $this->apimessage($resu['message'], $resu['status'], $info);
    }

    public function readmeAction()
    {
        $this->display();
    }
}
