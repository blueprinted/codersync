<?php
namespace Common\Model;
use Common\Model\CommonModel;
class UserModel extends CommonModel
{
	
	protected $_validate = array(
		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('username', 'require', '用户名不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT  ),
		array('nickname', 'require', '用户昵称不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
		array('username','','用户名已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证username字段是否唯一
		//array('email','email','邮箱格式不正确！',0,'',CommonModel:: MODEL_BOTH ), // 验证email字段格式是否正确
	);
	
	protected $_auto = array(
	    array('create_time','mGetDate',CommonModel:: MODEL_INSERT,'callback'),
	    //array('birthday','',CommonModel::MODEL_UPDATE,'ignore')
	);
	
	//用于获取时间，格式为2012-02-03 12:12:12,注意,方法不能为private
	function mGetDate() {
		return time();
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
		
		if(!empty($data['password']) && strlen($data['password'])<25){
			$data['password']=sp_password($data['password']);
		}
	}
	
}

