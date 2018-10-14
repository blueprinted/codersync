<?php
return array(
	//'配置项'=>'配置值'
	'TMPL_ENGINE_TYPE'		=> 'Think',
	'TMPL_TEMPLATE_SUFFIX'  =>  '.html', // 默认模板文件后缀
	'DEFAULT_THEME'			=>	'default',
	'TMPL_DETECT_THEME'		=>	true,
	
	// 模板配置
	'SP_TMPL_PATH'     		=> 'themes/', // 前台模板文件根目录
	'SP_DEFAULT_THEME'		=> 'simplebootx', // 前台模板文件
	'SP_TMPL_ACTION_ERROR' 	=> 'error', // 默认错误跳转对应的模板文件,注：相对于前台模板路径
	'SP_TMPL_ACTION_SUCCESS' 	=> 'success', // 默认成功跳转对应的模板文件,注：相对于前台模板路径
	'SP_ADMIN_STYLE'		=> 'flat',
	'SP_ADMIN_TMPL_PATH'    => 'admin/themes/', // 各个项目后台模板文件根目录
	'SP_ADMIN_DEFAULT_THEME'=> 'simplebootx', // 各个项目后台模板文件
	'SP_ADMIN_TMPL_ACTION_ERROR' 	=> 'Admin/error.html', // 默认错误跳转对应的模板文件,注：相对于后台模板路径
	'SP_ADMIN_TMPL_ACTION_SUCCESS' 	=> 'Admin/success.html', // 默认成功跳转对应的模板文件,注：相对于后台模板路径
	'TMPL_EXCEPTION_FILE'   => THINK_PATH.'Tpl/think_exception.html',
	
	'TMPL_L_DELIM' => '{', // 模板引擎普通标签开始标记
    'TMPL_R_DELIM' => '}', // 模板引擎普通标签结束标记
	
	// Think模板引擎标签库相关设定
    'TAGLIB_BUILD_IN'       =>  'cx,Common\Lib\Taglib\TagLibSpadmin,Common\Lib\Taglib\TagLibHome', // 内置标签库名称(标签使用不必指定标签库名称),以逗号分隔 注意解析顺序
	
	'TOKEN_ON'				=>	false,  // 是否开启令牌验证 默认关闭
);