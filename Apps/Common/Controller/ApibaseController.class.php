<?php

/**
 * API基础Controller
 * zouchao@sogou-inc.com
 * 2018-02-01
 */
namespace Common\Controller;

use Think\Controller;

class ApibaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function apimessage($message, $status = 0, $moredata = array())
    {
        $data = is_array($ajax)?$ajax:array();
        $data['info']   =   $message;
        $data['status'] =   $status;
        $data = @array_merge(is_array($moredata)?$moredata:array(), $data);
        $this->ajaxReturn($data, 'json');
    }
}
