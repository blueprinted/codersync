<?php

/**
 * Rsync模块基础Controller
 * zouchao@sogou-inc.com
 * 2018-10-15
 */
namespace Common\Controller;

use Think\Controller;

class RsyncbaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!B('Admin\Behavior\AuthCheck')) {
    			$this->error('请先登录', U('Admin/Public/login'), 1, array('code'=>'need_login'));
    		}
    }
}
