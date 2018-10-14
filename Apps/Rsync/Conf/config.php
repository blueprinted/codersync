<?php
return array(
	//'配置项'=>'配置值'
	'LOAD_EXT_CONFIG'		=> 'config.group,applist,config.ignore.code.standards',
	'TMPL_ENGINE_TYPE'		=> 'Think',
	'TMPL_TEMPLATE_SUFFIX'  =>  '.php',     // 默认模板文件后缀

	'DEFAULT_THEME'			=>	'',
	'TMPL_DETECT_THEME'		=>	false,
	'SHOW_PAGE_TRACE'		=> true,

	//'SOGOU_SVN_USERNAME_DEFAULT'		=> 'svn_sogoushouji',
	//'SOGOU_SVN_PASSWORD_DEFAULT'		=> 'aZj123U98uaf',

	'SOGOU_SVN_ROOT'			=> 'http://svn.sogou-inc.com/svn/iweb/',
	'SOGOU_HARBOR_API'  => 'http://pac.sogou/api/t2/records',

	'SVN_BIN_PATH_DEFAULT'      => '/usr/bin/svn',
	'GIT_BIN_PATH_DEFAULT'      => '/usr/bin/git',
	'EXPECT_BIN_PATH_DEFAULT'   => '/usr/bin/expect',

	'FORCE_CODE_STANDARDS_CHECK' => true, //是否强制开启代码规范检测(该配置优先级高于项目内配置)
	'FORCE_CODE_STANDARDS_CHECK_FILE_EXTS' => 'php', //强制代码规范检测的文件名后缀(优先用项目内配置)
	'FORCE_CODE_STANDARDS_CHECK_BIN_PATH' => '/opt/rh/php7.0.16/bin/php /usr/bin/phpcs --standard='.realpath(APP_PATH.MODULE_NAME).'/Conf/code_standards/ruleset.xml -s {filepath}',//强制执行代码规范检查命令的执行路径,缺省为空(优先用项目内配置)
);
