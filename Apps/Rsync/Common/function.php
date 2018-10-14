<?php
/**
 *	项目公共函数
 */

/**
 *  初始化app配置(会检查必要配置)
 *  @param $app Array 项目配置
 *  @param &$info Array 被填充一些结果信息
 *  @param $istest Boolean 是否为测试环境
 *  @return Array/Boolean(false) Array:通过检测 false:配置有误(错误信息会被填充在info中)
 */
function init_rsync_app_conf($app, &$info) {
	  $info = array();
		$app['id'] = isset($app['id']) ? intval($app['id']) : 0;
		$app['name'] = isset($app['name']) ? $app['name'] : '';
		$app['tips'] = isset($app['tips']) ? $app['tips'] : '';
		$app['link'] = isset($app['link']) ? $app['link'] : '';
		$app['link_test'] = isset($app['link_test']) ? $app['link_test'] : '';
		$app['root_dir'] = isset($app['root_dir']) ? $app['root_dir'] : '';
		$app['root_dir_test'] = isset($app['root_dir_test']) ? $app['root_dir_test'] : '';
		$app['repository'] = isset($app['repository']) ? strtolower($app['repository']) : 'svn';
		$app['repo_is_sogou'] = isset($app['repo_is_sogou']) && $app['repo_is_sogou'] ? true : false;
		$app['repo_auth'] = isset($app['repo_auth']) && $app['repo_auth'] ? true : false;
		$app['repo_url'] = isset($app['repo_url']) ? $app['repo_url'] : '';
		$app['repo_username'] = isset($app['repo_username']) ? $app['repo_username'] : '';
		$app['repo_password'] = isset($app['repo_password']) ? $app['repo_password'] : '';
		$app['repo_auth_test'] = isset($app['repo_auth_test']) && $app['repo_auth_test'] ? true : false;
		$app['repo_url_test'] = isset($app['repo_url_test']) ? $app['repo_url_test'] : '';
		$app['repo_username_test'] = isset($app['repo_username_test']) ? $app['repo_username_test'] : '';
		$app['repo_password_test'] = isset($app['repo_password_test']) ? $app['repo_password_test'] : '';
		$app['repo_bin_path'] = isset($app['repo_bin_path']) ? $app['repo_bin_path'] : '';
		$app['is_sogou_docker'] = isset($app['is_sogou_docker']) ? ($app['is_sogou_docker'] ? true : false) : false;
		$app['docker_deploy_file'] = isset($app['docker_deploy_file']) ? $app['docker_deploy_file'] : '';
		$app['docker_deploy_cfg'] = isset($app['docker_deploy_cfg']) ? $app['docker_deploy_cfg'] : 'mercury,test,phpfpm';
		$app['use_roc_node'] = isset($app['use_roc_node']) && $app['use_roc_node'] ? true : false;
		$app['roc_sid'] = isset($app['roc_sid']) ? trim($app['roc_sid'], ',') : '';
		$app['use_roc_node_test'] = isset($app['use_roc_node_test']) && $app['use_roc_node_test'] ? true : false;
		$app['roc_sid_test'] = isset($app['roc_sid_test']) ? trim($app['roc_sid_test'], ',') : '';
		$app['rsync_shell'] = isset($app['rsync_shell']) ? $app['rsync_shell'] : '';
		$app['rsync_shell_test'] = isset($app['rsync_shell_test']) ? $app['rsync_shell_test'] : '';
		$app['syntax_check'] = isset($app['syntax_check']) && $app['syntax_check'] ? true : false;
		$app['syntax_check_test'] = isset($app['syntax_check_test']) && $app['syntax_check_test'] ? true : false;
		$app['syntax_check_bin_path'] = isset($app['syntax_check_bin_path']) ? $app['syntax_check_bin_path'] : '';
		$app['code_standards_check'] = isset($app['code_standards_check']) && $app['code_standards_check'] ? true : false;
		$app['code_standards_check_test'] = isset($app['code_standards_check_test']) && $app['code_standards_check_test'] ? true : false;
		$app['code_standards_check_file_exts'] = isset($app['code_standards_check_file_exts']) ? $app['code_standards_check_file_exts'] : 'php';
		$app['code_standards_check_bin_path'] = isset($app['code_standards_check_bin_path']) ? $app['code_standards_check_bin_path'] : ('/opt/rh/rh-php71/root/bin/php /usr/bin/phpcs --standard=' . realpath(APP_PATH . MODULE_NAME) . '/Conf/code_standards/ruleset.xml -s {filepath}');
		$app['sogou_sac'] = isset($app['sogou_sac']) && $app['sogou_sac'] ? true : false;
		$app['sac_appid'] = isset($app['sac_appid']) ? intval($app['sac_appid']) : 0;
		$app['sac_app_dir'] = isset($app['sac_app_dir']) ? $app['sac_app_dir'] : '';
		$app['export_single_confirm'] = isset($app['export_single_confirm']) && $app['export_single_confirm'] ? true : false;
		$app['export_multi_confirm'] = isset($app['export_multi_confirm']) && $app['export_multi_confirm'] ? true : false;
		$app['update_single_confirm'] = isset($app['update_single_confirm']) && $app['update_single_confirm'] ? true : false;
		$app['update_multi_confirm'] = isset($app['update_multi_confirm']) && $app['update_multi_confirm'] ? true : false;
		$app['update_vn_confirm'] = isset($app['update_vn_confirm']) && $app['update_vn_confirm'] ? true : false;
		$app['rsync_single_confirm'] = isset($app['rsync_single_confirm']) && $app['rsync_single_confirm'] ? true : false;
		$app['rsync_multi_confirm'] = isset($app['rsync_multi_confirm']) && $app['rsync_multi_confirm'] ? true : false;
		$app['export_single_confirm_test'] = isset($app['export_single_confirm_test']) && $app['export_single_confirm_test'] ? true : false;
		$app['export_multi_confirm_test'] = isset($app['export_multi_confirm_test']) && $app['export_multi_confirm_test'] ? true : false;
		$app['update_single_confirm_test'] = isset($app['update_single_confirm_test']) && $app['update_single_confirm_test'] ? true : false;
		$app['update_multi_confirm_test'] = isset($app['update_multi_confirm_test']) && $app['update_multi_confirm_test'] ? true : false;
		$app['update_vn_confirm_test'] = isset($app['update_vn_confirm_test']) && $app['update_vn_confirm_test'] ? true : false;
		$app['rsync_single_confirm_test'] = isset($app['rsync_single_confirm_test']) && $app['rsync_single_confirm_test'] ? true : false;
		$app['rsync_multi_confirm_test'] = isset($app['rsync_multi_confirm_test']) && $app['rsync_multi_confirm_test'] ? true : false;

		if ($app['id'] < 0) {
			$info[] = 'id 配置不正确(请配置不小于0的值)';
		}
		if (empty($app['root_dir'])) {
			$info[] = 'root_dir 没有配置项目根目录';
		}
		if (!in_array($app['repository'], array('svn', 'git'), true)) {
			$info[] = 'repository 代码仓库类型不正确(目前只支持svn,git)';
		}
		if (empty($app['repo_url'])) {
			$info[] = 'repo_url 没有配置项目的托管仓库地址';
		}
		if ($app['repo_auth']) {
			if (empty($app['repo_username']) && empty($app['repo_password'])) {
				$app['repo_username'] = $app['repository'] == 'svn' ? C('SOGOU_SVN_USERNAME') : C('SOGOU_GIT_USERNAME');
				$app['repo_password'] = $app['repository'] == 'svn' ? C('SOGOU_SVN_PASSWORD') : C('SOGOU_GIT_PASSWORD');
			}
		}
		if (empty($app['repo_url_test'])) {
			  $app['repo_url_test'] = $app['repo_url'];
		}
		if ($app['repo_auth_test']) {
			if (empty($app['repo_username_test']) && empty($app['repo_password_test'])) {
				$app['repo_username_test'] = $app['repository'] == 'svn' ? C('SOGOU_SVN_USERNAME') : C('SOGOU_GIT_USERNAME');
				$app['repo_password_test'] = $app['repository'] == 'svn' ? C('SOGOU_SVN_PASSWORD') : C('SOGOU_GIT_PASSWORD');
			}
		}
		if (empty($app['repo_bin_path'])) {
			if ($app['repository'] == 'svn') {
				$app['repo_bin_path'] = C('SVN_BIN_PATH_DEFAULT');
			} elseif ($app['repository'] == 'git') {
				$app['repo_bin_path'] = C('GIT_BIN_PATH_DEFAULT');
			} else {
				$info[] = '没有配置项目的仓库命令路径';
			}
		}
		if ($info) {
			return false;
		}
		$cup = explode(',', trim(trim($app['docker_deploy_cfg']), ','));
		$cup[0] = isset($cup[0]) && strlen($cup[0]) > 0 ? $cup[0] : 'mercury';
		$cup[1] = isset($cup[1]) && strlen($cup[1]) > 0 ? $cup[1] : 'test';
		$cup[2] = isset($cup[2]) && strlen($cup[2]) > 0 ? $cup[2] : 'containers:name=phpfpm';
		$cup[3] = isset($cup[3]) && strlen($cup[3]) > 0 ? $cup[3] : 'containers:name=phpfpm';
		$app['docker_deploy_cfg'] = "{$cup[0]},{$cup[1]},{$cup[2]},{$cup[3]}";

    return $app;
}

/**
 *  载入app配置
 *  @param $group String/Array 分组的标识码(code)或者分组的数组数据
 *  @return Array
 */
function load_app_list($group = '') {
	$applist = is_array(C('APP_LIST')) ? C('APP_LIST') : array();
	if (is_array($group)) {
		$appgroup = $group;
		$group = ['group_code'];
	} else {
		if(strlen($group) < 1) {
			$group = I('get.group', '');
		}
		$appgroups = C('APP_GROUP');
		$appgroup = isset($appgroups[$group]) ? $appgroups[$group] : array();
	}

	if(!empty($appgroup)) {
		//载入分组app配置
		if(@file_exists($appgroup['group_config_file'])) {
			$group_config = include $appgroup['group_config_file'];
			$group_config = is_array($group_config) ? $group_config : array();
			foreach($group_config as $key => $value)
				C($key, $value);
		}

		//载入组内app配置
		if(@file_exists($appgroup['app_list_file'])) {
			$group_applist = include $appgroup['app_list_file'];
			$group_applist = is_array($group_applist) ? $group_applist : array();
			foreach($group_applist as $key => $value)
				C($key, $value);
		}

		$groupapplist = is_array(C('APP_LIST_GROUP')) ? C('APP_LIST_GROUP') : array();
		foreach ($groupapplist as $id => $app) {
			$applist[$id] = $app;
		}
	}

	return $applist;
}

function load_group($group = '') {
	if (strlen($group) < 1) {
		return array();
	}
	$appgroups = C('APP_GROUP');
	return isset($appgroups[$group]) ? $appgroups[$group] : array();
}

/**
 * 生成项目的语法规范过滤配置
 */
function generate_filter_config($app, $group, &$info) {
	$info = array();
	if($app['root_dir']) {
		$list = dir_file_list($app['root_dir'], false);
		$file = APP_PATH.MODULE_NAME."/Conf/code_standards/{$group}/app_{$app['id']}_filter.php";
		if(!file_exists($file)) {
			write_config_file($file, $list);
		} else {
			$info[] = "appid={$app['id']}, test=0, 配置文件已存在, 不重复能生成";
		}
	}
	if($app['root_dir_test']) {
		$list = dir_file_list($app['root_dir_test'], false);
		$file = APP_PATH.MODULE_NAME."/Conf/code_standards/{$group}/app_{$app['id']}_filter_test.php";
		if(!file_exists($file)) {
			write_config_file($file, $list);
		} else {
			$info[] = "appid={$app['id']}, test=1, 配置文件已存在, 不能重复生成";
		}
	}
}

/** rsync 项目发布
 *  @param $group String 分组标识
 *  @param $appid Integer/Arrag app的id 或 app的配置数组
 *  @param $istest Boolean 是否测试环境 [true:是,false:否]
 *  @param $dirs Array 要同步的文件或目录（相对于项目根目录）
 *  @param $allow_rsync_root_dir 是否允许发布根目录 [true:是,false:否] 缺省否
 *  @return Array
    array(
	    'status' => 0
		'message' => 'succ'
		data => array(),
	)

	data为空数组或如下数组
	array(
	    'cmds' => array(),
		'lines' => array(),
		'files' -> array(),
	)
 */
function app_rsync($group, $appid, $istest, $dirs = array(), $allow_rsync_root_dir = false) {
	$rarr = array(
		'status' => 0,
		'message' => 'succ',
		'data' => array(
		    'cmds' => array(),
				'lines' => array(),
				'files' => array(),
		),
	);
	foreach ($dirs as $key => $dir) {
		$dir = dirfilter($dir);
		if(empty($dir)) {
			unset($dirs[$key]);
		} else {
			$dirs[$key] = $dir;
		}
	}
	if(empty($dirs)) {
		return array_merge($rarr, array(
      'status' => 1,
			'message' => '目录或文件为空',
		));
	}
	if (is_array($appid)) {
		$app = $appid;
		$appid = intval($app['id']);
	} else {
		$applist = load_app_list($group);
		$app = isset($applist[$appid]) ? $applist[$appid] : array();
		if(empty($app)) {
			return array_merge($rarr, array(
	      'status' => 2,
				'message' => '项目不存在',
			));
		}
		$app = init_rsync_app_conf($app, $info);
		if ($app === false) {
			return array_merge($rarr, array(
	      'status' => 88,
				'message' => 'app配置不正确, ' . implode(', ', $info),
			));
		}
	}
	//强制转换istest
	if($app['root_dir'] && empty($app['root_dir_test'])) {//只有正式环境
		$istest = 0;
	} elseif (empty($app['root_dir']) && $app['root_dir_test']) {//只有测试环境
		$istest = 1;
	}
	//获取项目目录
	$app_root = $istest ? realpath($app['root_dir_test']) : realpath($app['root_dir']);
	if($app_root === false) {
		return array_merge($rarr, array(
      'status' => 3,
			'message' => "项目目录不存在[".($istest?$app['root_dir_test']:$app['root_dir'])."]",
		));
	}
	//检测roc节点配置
	$use_roc_node = $istest ? $app['use_roc_node_test'] : $app['use_roc_node'];
	$roc_sid = $istest ? $app['roc_sid_test'] : $app['roc_sid'];
	if($use_roc_node && empty($roc_sid)) {
		return array_merge($rarr, array(
      'status' => 4,
			'message' => "项目的ROC节点ID配置不正确[roc_sid={$roc_sid}][".($istest?"测试":"")."]",
		));
	}
	//获取rsync的shell
	$rsync_shell = $istest ? realpath($app['rsync_shell_test']) : realpath($app['rsync_shell']);
	if($rsync_shell === false) {
		return array_merge($rarr, array(
      'status' => 5,
			'message' => "项目的同步脚本不存在[".($istest?$app['rsync_shell_test']:$app['rsync_shell'])."]",
		));
	}
	//获取项目的语法检查配置
	$syntax_check = false;
	if (($app['syntax_check'] && !$istest) || ($app['syntax_check_test'] && $istest)) {
		$syntax_check = true;
	}
	//获取项目的代码规范配置
	$code_standards_check = false;
	$code_standards_check_bin_path = '';
	$code_standards_check_file_exts = '';
	$ignore_code_standards_conf = C('APP_IGNORE');
	$ignore_code_standards_conf = isset($ignore_code_standards_conf[$group]) && is_array($ignore_code_standards_conf[$group]) ? $ignore_code_standards_conf[$group] : array();
	if((C('FORCE_CODE_STANDARDS_CHECK') && !in_array($appid, $ignore_code_standards_conf)) || ($app['code_standards_check'] && !$istest) || ($app['code_standards_check_test'] && $istest)) {
		$code_standards_check = true;
	}
	if($code_standards_check && empty($app['code_standards_check_bin_path']) && strlen(trim(C('FORCE_CODE_STANDARDS_CHECK_BIN_PATH'))) < 1) {
		return array_merge($rarr, array(
      'status' => 6,
			'message' => "项目开启了文件代码规范检查，但是没有指定执行代码规范检查命令的执行路径，请检查。",
		));
	}
	$code_standards_check_bin_path = empty($app['code_standards_check_bin_path']) ? C('FORCE_CODE_STANDARDS_CHECK_BIN_PATH') : $app['code_standards_check_bin_path'];
	if(strlen(trim(C('FORCE_CODE_STANDARDS_CHECK_FILE_EXTS'))) < 1 && empty($app['code_standards_check_file_exts'])) {
		$code_standards_check_file_exts = 'php';
	} else {
		$code_standards_check_file_exts = empty($app['code_standards_check_file_exts']) ? preg_replace('/\s+/i', '', C('FORCE_CODE_STANDARDS_CHECK_FILE_EXTS')) : preg_replace('/\s+/i', '', $app['code_standards_check_file_exts']);
	}
	$code_standards_filter = array();
	if($code_standards_check) {//获取需要屏蔽语法规范检测的文件配置(该配置是一个数组,一行一个屏蔽的文件,文件路径相对于$app_root)
		$code_standards_filter = @include realpath(APP_PATH.MODULE_NAME)."/Conf/code_standards/{$group}/app_{$app['id']}_filter".($istest?'_test':'').".php";
		$code_standards_filter = is_array($code_standards_filter) ? $code_standards_filter : array();
	}
	//获取项目的重启服务脚本
	$restart_server = false;
	if(isset($app['restart_server']) && $app['restart_server'] && !$istest || isset($app['restart_server_test']) && $app['restart_server_test'] && $istest) {
		$restart_server = true;
	}
	if($restart_server) {
		$restart_shell = $istest ? ($app['restart_shell_test']?realpath($app['restart_shell_test']):false) : ($app['restart_shell']?realpath($app['restart_shell']):false);
		if($restart_shell === false) {
			return array_merge($rarr, array(
				'status' => 7,
				'message' => "项目的重启服务脚本不存在[".($istest?$app['restart_shell_test']:$app['restart_shell'])."]",
			));
		}
	}

	$counter = $counter_succ = 0;
	$cmds = array();
	$delta_flag = false;//是否有增量文件
	$delta_files = array();
	foreach($dirs as $dir) {
		$realdir = realpath($app_root.'/'.$dir);
		if(substr($realdir, 0, strlen($app_root)) != $app_root) {
			//防止读取项目的上级目录
			$realdir = $app_root;
		}
		if(strlen($realdir) > strlen($app_root)) {
			$dir = substr($realdir, strlen($app_root)+1);
		} else {
			$dir = '';
		}
		if($dir == '' && !$allow_rsync_root_dir) {
			return array_merge($rarr, array(
				'status' => 8,
				'message' => '不能发布项目根目录',
			));
		}
		if(is_dir($realdir) && strlen($dir) > 0 && substr($dir, -1) != '/') {
			$dir .= '/';
		}

		//php文件语法检查[会检查目录下(含子目录)所有的php文件](有些并没有改变的文件也会进行检查,检查的文件比实际发布的文件多,会导致运行时间偏长,故停用)
		// if($syntax_check) {
			// $files = dir_file_list($app_root.'/'.$dir, true, 'php');
			// foreach($files as $file) {
				// $cmd = "php -l {$file} 2>&1";
				// exec($cmd, $ret_arr2, $ret_var);
				// if($ret_var !== 0) {
					// return array_merge($rarr, array(
						// 'status' => 9,
						// 'message' => '不能发布，文件有语法错误，请检查['.str_replace($app_root.'/','',$file).']',
						// 'data' => array(
							// 'cmds' => array(),
							// 'lines' => $ret_arr2
						// ),
					// ));
				// }
			// }
		// }

		//需要重启的服务 或 需要执行动态脚本文件语法检查 或 需要进行代码规范检查 的项目需要先取得本次rsync的增量文件
		if($restart_server || $syntax_check || $code_standards_check) {
			$pattern = array(
				'find' => '/rsync(\s+)(.+)(\r|\n|\r\n)*?/i',
				'replace' => 'rsync -n\\1\\2',
			);
			$contents = @preg_replace($pattern['find'], $pattern['replace'], @file_get_contents($rsync_shell), -1, $count);
			if(strlen($contents) > 0 && $count > 0) {
				$fileext = fileext($rsync_shell);
				$rsync_shell_tmp = strlen($fileext) > 0 ? str_replace(".{$fileext}", '', $rsync_shell)."_tmp.{$fileext}" : "{$rsync_shell}_tmp";
				if(false !== @file_put_contents($rsync_shell_tmp, $contents)) {
					$cmd = "sh {$rsync_shell_tmp} ".($use_roc_node&&$roc_sid?"{$roc_sid} ":"")."{$dir} 2>&1";
					$ret_arr2 = array();// 初始化 避免多次循环被多次填充重复的值
					exec($cmd, $ret_arr2, $ret_var);
					if($ret_var === 0) {
						$cmds[] = "{$cmd} [succ]";
						$ret_arr2 = @array_unique($ret_arr2, SORT_STRING);
						foreach($ret_arr2 as $line) {
							$line = trim($line);
							if(strlen($line) > 0 && strpos($line, ' ') === false) {
								$delta_flag = true;
								$line_file = "{$app_root}/{$dir}";
								if(@is_dir("{$app_root}/{$dir}") && $line != './') {
									$line_file = "{$app_root}/{$dir}{$line}";
								}
								$delta_files[] = str_replace("{$app_root}/", '', $line_file);//记录增量文件
								//动态脚本文件语法检查[只检查rsync增量文件]
								if($syntax_check) {
									if(!isset($app['syntax_check_file_exts']) || empty($app['syntax_check_file_exts']) || strlen(trim($app['syntax_check_file_exts'])) < 1) {
										$app['syntax_check_file_exts'] = 'php';
									}
									$app['syntax_check_file_exts'] = preg_replace('/\s+/i', '', trim($app['syntax_check_file_exts']));
									if(@is_file($line_file) && false !== stripos(",{$app['syntax_check_file_exts']},", ','.fileext($line_file).',')) {
										if(isset($app['syntax_check_bin_path']) && $app['syntax_check_bin_path']) {
											$regular = '/([\s\S]+)(\{\w+\})([\s\S]*)/i';
											if(preg_match($regular, $app['syntax_check_bin_path'])) {//需要进行替换
												$cmd = preg_replace($regular, "\\1{$line_file}\\3", $app['syntax_check_bin_path']);
											} elseif(preg_match('/[\s\S]*php[\s\S]*/i', $app['syntax_check_bin_path']) && !preg_match('/\s+-([a-zA-Z]*)l([a-zA-Z]*)\s*/i', $app['syntax_check_bin_path'])) {//没带 -l 参数的php命令
												$cmd = "{$app['syntax_check_bin_path']} -l {$line_file}";
											} else {//其他情况直接用命令检查文件
												$cmd = "{$app['syntax_check_bin_path']} {$line_file}";
											}
										} else {
											$cmd = "php -l {$line_file}";
										}
										$cmd .= " 2>&1";
										exec($cmd, $ret_arr3, $ret_var);
										if($ret_var !== 0) {
											$cmds[] = "{$cmd} [fail exec_ret_var={$ret_var}]";
											return array_merge($rarr, array(
												'status' => 9,
												'message' => '不能发布，文件有语法错误，请检查['.str_replace($app_root.'/','',$line_file).']',
												'data' => array(
													'cmds' => $cmds,
													'lines' => $ret_arr3,
												),
											));
										} else {
											$cmds[] = "{$cmd} [succ]";
										}
									}
								}
								//文件代码规范检查[只检查rsync增量文件]
								if($code_standards_check) {
									$line_file = "{$app_root}/{$dir}";
									if(@is_dir("{$app_root}/{$dir}") && $line != './') {
										$line_file = "{$app_root}/{$dir}{$line}";
									}
									$relate_path = str_replace($app_root.'/', '', $line_file);
									if(
										@is_file($line_file)
										&& false !== stripos(",{$code_standards_check_file_exts},", ','.fileext($line_file).',')
										&& !in_array($relate_path, $code_standards_filter, true)
										&& !is_sub_dir(dirname($relate_path).'/', str_replace(realpath(SITE_PATH).'/', '', realpath(APP_PATH.MODULE_NAME."/Conf/code_standards")))
									) {
										$regular = '/([\s\S]+)(\{\w+\})([\s\S]*)/i';
										if(preg_match($regular, $code_standards_check_bin_path)) {//需要进行替换
											$cmd = preg_replace($regular, "\\1{$line_file}\\3", $code_standards_check_bin_path);
										} else {//其他情况直接用命令检查文件
											$cmd = "{$code_standards_check_bin_path} {$line_file}";
										}
										$cmd .= " 2>&1";
										exec($cmd, $ret_arr4, $ret_var);
										if($ret_var !== 0) {
											$cmds[] = "{$cmd} [fail exec_ret_var={$ret_var}]";
											return array_merge($rarr, array(
												'status' => 10,
												'message' => '不能发布，文件代码没有达到规范，请检查['.str_replace($app_root.'/','',$line_file).']',
												'data' => array(
													'cmds' => $cmds,
													'lines' => $ret_arr4,
												),
											));
										} else {
											$cmds[] = "{$cmd} [succ]";
										}
									}
								}
							}
						}
					} else {
						$cmds[] = "{$cmd} [fail exec_ret_var={$ret_var}]";
						\Think\Log::write("exec sh {$rsync_shell_tmp}文件失败",'INFO');
					}
					@unlink($rsync_shell_tmp);
				} else {
					\Think\Log::write("生成{$rsync_shell_tmp}文件失败",'INFO');
				}
			}
		}

		//rsync
		$cmd = "sh {$rsync_shell} ".($use_roc_node&&$roc_sid?"{$roc_sid} ":"")."{$dir} 2>&1";
		exec($cmd, $ret_arr, $ret_var);
		$counter++;
		if($ret_var !== 0) {
			$cmds[] = "{$cmd} [fail exec_ret_var={$ret_var}]";
		} else {
			$counter_succ++;
			$cmds[] = "{$cmd} [succ]";
		}
	}

	//结果
	$status = 0;
	$message = '';
	if($counter_succ != $counter) {
		if($counter_succ < 1) {
			$status = 11;
			$message .= '发布失败';
		} else {
			$status = 99;
			$message .= '发布成功[部分成功]';
		}
	} else {
		$message .= '发布成功';
	}

	return array_merge($rarr, array(
		'status' => $status,
		'message' => $message,
		'data' => array(
			'cmds' => $cmds,
			'lines' => $ret_arr,
			'files' => $delta_files,
		),
	));
}

/** 项目服务重启
 *  @param $group String 分组标识
 *  @param $appid Integer/Arrag app的id 或 app的配置数组
 *  @param $istest Boolean 是否测试环境 [true:是,false:否]
 *  @return Array
    array(
	    'status' => 0
		'message' => 'succ'
		data => array(),
	)

	data为空数组或如下数组
	array(
	    'cmds' => array(),
		'lines' => array(),
	)
 */
function app_restart($group, $appid, $istest) {
	$rarr = array(
		'status' => 0,
		'message' => 'succ',
		'data' => array(
			  'cmds' => array(),
				'lines' => array(),
		),
	);
	if (is_array($appid)) {
		$app = $appid;
		$appid = intval($app['id']);
	} else {
		$applist = load_app_list($group);
		$app = isset($applist[$appid]) ? $applist[$appid] : array();
		if(empty($app)) {
			return array_merge($rarr, array(
	      'status' => 2,
				'message' => '项目不存在',
			));
		}
		$app = init_rsync_app_conf($app, $info);
		if ($app === false) {
			return array_merge($rarr, array(
	      'status' => 88,
				'message' => 'app配置不正确, ' . implode(', ', $info),
			));
		}
	}
	//强制转换istest
	if($app['root_dir'] && empty($app['root_dir_test'])) {//只有正式环境
		$istest = 0;
	} elseif (empty($app['root_dir']) && $app['root_dir_test']) {//只有测试环境
		$istest = 1;
	}
	//获取项目目录
	$app_root = $istest ? realpath($app['root_dir_test']) : realpath($app['root_dir']);
	if($app_root === false) {
		return array_merge($rarr, array(
			'status' => 3,
			'message' => "项目目录不存在[".($istest?$app['root_dir_test']:$app['root_dir'])."]",
		));
	}
	//检测roc节点配置
	$app['use_roc_node'] = isset($app['use_roc_node']) ? $app['use_roc_node'] : false;
	$app['roc_sid'] = isset($app['roc_sid']) ? trim($app['roc_sid'], ',') : '';
	$app['use_roc_node_test'] = isset($app['use_roc_node_test']) ? $app['use_roc_node_test'] : false;
	$app['roc_sid_test'] = isset($app['roc_sid_test']) ? trim($app['roc_sid_test'], ',') : '';
	$use_roc_node = $istest ? $app['use_roc_node_test'] : $app['use_roc_node'];
	$roc_sid = $istest ? $app['roc_sid_test'] : $app['roc_sid'];
	if($use_roc_node && empty($roc_sid)) {
		return array_merge($rarr, array(
			'status' => 4,
			'message' => "项目的ROC节点ID配置不正确[roc_sid={$roc_sid}][".($istest?"测试":"")."]",
		));
	}
	//获取项目的重启服务脚本
	$restart_server = false;
	if(isset($app['restart_server']) && $app['restart_server'] && !$istest || isset($app['restart_server_test']) && $app['restart_server_test'] && $istest) {
		$restart_server = true;
	}
	if($restart_server) {
		$restart_shell = $istest ? ($app['restart_shell_test']?realpath($app['restart_shell_test']):false) : ($app['restart_shell']?realpath($app['restart_shell']):false);
		if($restart_shell === false) {
			return array_merge($rarr, array(
				'status' => 5,
				'message' => "项目的重启服务脚本不存在[".($istest?$app['restart_shell_test']:$app['restart_shell'])."]",
			));
		}
	} else {
		return array_merge($rarr, array(
			'status' => 6,
			'message' => "项目不需要重启",
		));
	}
	$cmds = array();
	$ret_arr = array();
	$message = '执行完毕';

	//重启服务
	$cmd = "sh {$restart_shell}".($use_roc_node&&$roc_sid?" {$roc_sid}":"")." 2>&1";
	exec($cmd, $ret_arr_tmp, $ret_var);
	$ret_arr = array_merge($ret_arr, $ret_arr_tmp);
	if($ret_var !== 0) {
		$cmds[] = "{$cmd} [fail exec_ret_var={$ret_var}]";
		$message .= ',重启服务失败';
	} else {
		$cmds[] = "{$cmd} [succ]";
	}
	//检查重启的结果
	$jsonstr = implode('', $ret_arr_tmp);
	$jsonarr = json_decode($jsonstr, true);
	if(!is_array($jsonarr) || !is_array($jsonarr['result'])) {
		$message .= ',解析重启脚本返回数据失败';
	} else {
		//检查是否有重启失败的机器
		foreach($jsonarr['result'] as $ip => $info) {
			if($info['execStatusCode'] != 0) {
				$message .= "IP为{$ip}的机器重启服务失败";
				return array_merge($rarr, array(
					'status' => 7,
					'message' => $message,
					'data' => array(
						'cmds' => $cmds,
						'lines' => $ret_arr,
					),
				));
			}
		}
		$message .= ',重启服务成功';
	}
	return array_merge($rarr, array(
		'status' => 0,
		'message' => $message,
		'data' => array(
			'cmds' => $cmds,
			'lines' => $ret_arr,
		),
	));
}

/** 项目代码SVN更新
 *  @param $group String 分组标识
 *  @param $appid Integer/Arrag app的id 或 app的配置数组
 *  @param $istest Boolean 是否测试环境 [true:是,false:否]
 *  @param $dirs Array 要更新的文件或目录（相对于项目根目录）
 *  @param $ver Stirng 安版本号更新 缺省0 如果此参数大于0则会根据$dirs参数安版本号进行更新
 *  @return Array
    array(
	    'status' => 0
		'message' => 'succ'
		data => array(),
	)

	data为空数组或如下数组
	array(
	    'cmds' => array(),
		'lines' => array(),
		'files' -> array(),
	)
 */
function app_svnupdate($group, $appid, $istest, $dirs = array(), $ver = 0) {
	$rarr = array(
		'status' => 0,
		'message' => 'succ',
		'data' => array(),
	);
	foreach($dirs as $key => $dir) {
		$dir = dirfilter($dir);
		if(empty($dir)) {
			unset($dirs[$key]);
		} else {
			$dirs[$key] = $dir;
		}
	}
	if(empty($dirs)) {
		return array_merge($rarr, array(
      'status' => 1,
			'message' => '目录或文件为空',
		));
	}

	if (is_array($appid)) {
		$app = $appid;
		$appid = intval($app['id']);
	} else {
		$applist = load_app_list($group);
		$app = isset($applist[$appid]) ? $applist[$appid] : array();
		if(empty($app)) {
			return array_merge($rarr, array(
	      'status' => 2,
				'message' => '项目不存在',
			));
		}
		$app = init_rsync_app_conf($app, $info);
		if ($app === false) {
			return array_merge($rarr, array(
	      'status' => 88,
				'message' => 'app配置不正确, ' . implode(', ', $info),
			));
		}
	}

	//强制转换istest
	if($app['root_dir'] && empty($app['root_dir_test'])) {//只有正式环境
		$istest = 0;
	} elseif (empty($app['root_dir']) && $app['root_dir_test']) {//只有测试环境
		$istest = 1;
	}

	//获取项目目录
	$app_root = $istest ? realpath($app['root_dir_test']) : realpath($app['root_dir']);
	if($app_root === false) {
		return array_merge($rarr, array(
      'status' => 3,
			'message' => "项目目录不存在[".($istest?$app['root_dir_test']:$app['root_dir'])."]",
		));
	}

	if ($app['repository'] != 'svn') {
		return array_merge($rarr, array(
      'status' => 4,
			'message' => "该项目不是托管在SVN上的项目[{$app['repository']}]",
		));
	}

	if($is_test && $app['repo_url_test']) {
		$repo_auth = $app['repo_auth_test'];
		$repo_username = $app['repo_username_test'];
		$repo_password = $app['repo_password_test'];
	} else {
		$repo_auth = $app['repo_auth'];
		$repo_username = $app['repo_username'];
		$repo_password = $app['repo_password'];
	}
  $repo_bin_path = $app['repo_bin_path'];
	$counter = $counter_succ = 0;
	$cmds = $ret_arr = array();
	foreach($dirs as $dir) {
		$realdir = realpath($app_root.'/'.$dir);
		if(substr($realdir, 0, strlen($app_root)) != $app_root) {
			//防止读取项目的上级目录
			$realdir = $app_root;
		}
		if(strlen($realdir) > strlen($app_root)) {
			$dir = substr($realdir, strlen($app_root)+1);
		} else {
			$dir = '';
		}
		if(is_dir($realdir) && strlen($dir) > 0 && substr($dir, -1) != '/') {
			$dir .= '/';
		}

		//svn update
		$cmd_ver = '';
		if($ver > 0) {
			$cmd_ver = " -r {$ver}";
		}
		$cmd = "{$repo_bin_path} update{$cmd_ver} {$app_root}/{$dir}";
		if($repo_auth) {//需要认证
			$cmd .= " --username={$repo_username} --password={$repo_password}";
		}
		$cmd .= " 2>&1";//将执行过程中的标准错误重定向到标准输出
		exec($cmd, $ret_arr, $ret_var);
		$counter++;
		if($ret_var !== 0) {
			$cmds[] = @preg_replace('/\s?\-\-(password)=\S*/i', '', $cmd)." [fail exec_ret_var={$ret_var}]";
		} else {
			$counter_succ++;
			$cmds[] = @preg_replace('/\s?\-\-(password)=\S*/i', '', $cmd). ' [succ]';
		}
	}

	//结果
	$status = 0;
	$message = '';
	if($counter_succ != $counter) {
		if($counter_succ < 1) {
			$message .= '更新失败'.($ver>0?'[可能是版本号不正确]':'');
		} else {
			$message .= '更新成功[部分成功]';
		}
	} else {
		$message .= '更新成功';
	}

	return array_merge($rarr, array(
		'status' => $status,
		'message' => $message,
		'data' => array(
			'cmds' => $cmds,
			'lines' => $ret_arr,
		),
	));
}

/** 项目代码git pull
 *  @param $group String 分组标识
 *  @param $appid Integer/Arrag app的id 或 app的配置数组
 *  @param $istest Boolean 是否测试环境 [true:是,false:否]
 *  @return Array
    array(
	  'status' => 0
		'message' => 'succ'
		data => array(),
	)

	data为空数组或如下数组
	array(
	    'cmds' => array(),
		'lines' => array(),
		'files' -> array(),
	)
 */
function app_gitpull($group, $appid, $istest) {
	$rarr = array(
		'status' => 0,
		'message' => 'succ',
		'data' => array(),
	);

	if (is_array($appid)) {
		$app = $appid;
		$appid = intval($app['id']);
	} else {
		$applist = load_app_list($group);
		$app = isset($applist[$appid]) ? $applist[$appid] : array();
		if(empty($app)) {
			return array_merge($rarr, array(
	      'status' => 2,
				'message' => '项目不存在',
			));
		}
		$app = init_rsync_app_conf($app, $info);
		if ($app === false) {
			return array_merge($rarr, array(
	      'status' => 88,
				'message' => 'app配置不正确, ' . implode(', ', $info),
			));
		}
	}

	//强制转换istest
	if($app['root_dir'] && empty($app['root_dir_test'])) {//只有正式环境
		$istest = 0;
	} elseif (empty($app['root_dir']) && $app['root_dir_test']) {//只有测试环境
		$istest = 1;
	}

	//获取项目目录
	$app_root = $istest ? realpath($app['root_dir_test']) : realpath($app['root_dir']);
	if($app_root === false) {
		return array_merge($rarr, array(
      'status' => 3,
			'message' => "项目目录不存在[".($istest?$app['root_dir_test']:$app['root_dir'])."]",
		));
	}

	if ($app['repository'] != 'git') {
		return array_merge($rarr, array(
      'status' => 4,
			'message' => "该项目不是托管在GIT上的项目[{$app['repository']}]",
		));
	}

	if($is_test && $app['repo_url_test']) {
		$repo_auth = $app['repo_auth_test'];
		$repo_username = $app['repo_username_test'];
		$repo_password = $app['repo_password_test'];
	} else {
		$repo_auth = $app['repo_auth'];
		$repo_username = $app['repo_username'];
		$repo_password = $app['repo_password'];
	}
	$repo_bin_path = $app['repo_bin_path'];

	$cmds = $ret_arr = array();
	//git pull
	$cmd = "export LC_ALL=zh_CN.UTF-8;cd {$app_root}/ && {$repo_bin_path} pull origin master:master";
	if($repo_auth) {//需要认证
	}
	$cmd .= " 2>&1";//将执行过程中的标准错误重定向到标准输出
	exec($cmd, $ret_arr, $ret_var);
	if($ret_var !== 0) {
		$cmds[] = @preg_replace('/\s?\-\-(password)=\S*/i', '', $cmd)." [fail exec_ret_var={$ret_var}]";
	} else {
		$cmds[] = @preg_replace('/\s?\-\-(password)=\S*/i', '', $cmd). ' [succ]';
	}

	//结果
	$status = 0;
	$message = '';
	if ($ret_var === 0) {
		$message .= 'git pull成功';
	} else {
		$message .= 'git pull失败';
	}

	return array_merge($rarr, array(
		'status' => $status,
		'message' => $message,
		'data' => array(
			'cmds' => $cmds,
			'lines' => $ret_arr,
		),
	));
}

/** 项目代码git update
 *  @param $group String 分组标识
 *  @param $appid Integer/Arrag app的id 或 app的配置数组
 *  @param $istest Boolean 是否测试环境 [true:是,false:否]
 *  @param $dirs Array 要更新的文件或目录（相对于项目根目录）
 *  @return Array
    array(
	    'status' => 0
		'message' => 'succ'
		data => array(),
	)

	data为空数组或如下数组
	array(
	    'cmds' => array(),
		'lines' => array(),
		'files' -> array(),
	)
 */
function app_gitupdate($group, $appid, $istest, $dirs = array()) {
	$rarr = array(
		'status' => 0,
		'message' => 'succ',
		'data' => array(),
	);
	foreach($dirs as $key => $dir) {
		$dir = dirfilter($dir);
		if(empty($dir)) {
			unset($dirs[$key]);
		} else {
			$dirs[$key] = $dir;
		}
	}
	if(empty($dirs)) {
		return array_merge($rarr, array(
      'status' => 1,
			'message' => '目录或文件为空',
		));
	}

	if (is_array($appid)) {
		$app = $appid;
		$appid = intval($app['id']);
	} else {
		$applist = load_app_list($group);
		$app = isset($applist[$appid]) ? $applist[$appid] : array();
		if(empty($app)) {
			return array_merge($rarr, array(
	      'status' => 2,
				'message' => '项目不存在',
			));
		}
		$app = init_rsync_app_conf($app, $info);
		if ($app === false) {
			return array_merge($rarr, array(
	      'status' => 88,
				'message' => 'app配置不正确, ' . implode(', ', $info),
			));
		}
	}
	//强制转换istest
	if($app['root_dir'] && empty($app['root_dir_test'])) {//只有正式环境
		$istest = 0;
	} elseif (empty($app['root_dir']) && $app['root_dir_test']) {//只有测试环境
		$istest = 1;
	}

	//获取项目目录
	$app_root = $istest ? realpath($app['root_dir_test']) : realpath($app['root_dir']);
	if($app_root === false) {
		return array_merge($rarr, array(
      'status' => 3,
			'message' => "项目目录不存在[".($istest?$app['root_dir_test']:$app['root_dir'])."]",
		));
	}

	if ($app['repository'] != 'git') {
		return array_merge($rarr, array(
      'status' => 4,
			'message' => "该项目不是托管在GIT上的项目[{$app['repository']}]",
		));
	}

	if($is_test && $app['repo_url_test']) {
		$repo_auth = $app['repo_auth_test'];
		$repo_username = $app['repo_username_test'];
		$repo_password = $app['repo_password_test'];
	} else {
		$repo_auth = $app['repo_auth'];
		$repo_username = $app['repo_username'];
		$repo_password = $app['repo_password'];
	}
	$repo_bin_path = $app['repo_bin_path'];

	$counter = $counter_succ = 0;
	$cmds = $ret_arr = array();
	foreach($dirs as $dir) {
		$realdir = realpath($app_root.'/'.$dir);
		if(substr($realdir, 0, strlen($app_root)) != $app_root) {
			  //防止读取项目的上级目录
			  $realdir = $app_root;
		}
		if(strlen($realdir) > strlen($app_root)) {
			  $dir = substr($realdir, strlen($app_root)+1);
		} else {
			  $dir = '';
		}

		if(is_dir($realdir) && strlen($dir) > 0 && substr($dir, -1) != '/') {
			  $dir .= '/';
		}
		if ($dir == '') {
			  $dir = './';
		}

		$cmd = "export LC_ALL=zh_CN.UTF-8;cd {$app_root} && {$repo_bin_path} checkout origin/master -- {$dir}";
		if($repo_auth) {//需要认证
		}
		$cmd .= " 2>&1";//将执行过程中的标准错误重定向到标准输出
		exec($cmd, $ret_arr, $ret_var);
		$counter++;
		if($ret_var !== 0) {
			$cmds[] = "{$cmd} [fail exec_ret_var={$ret_var}]";
		} else {
			$counter_succ++;
			$cmds[] = "{$cmd} [succ]";
		}
	}

	//结果
	$status = 0;
	$message = '';
	if($counter_succ != $counter) {
		if($counter_succ < 1) {
			$message .= '更新失败';
		} else {
			$message .= '更新成功[部分成功]';
		}
	} else {
		$message .= '更新成功';
	}

	return array_merge($rarr, array(
		'status' => $status,
		'message' => $message,
		'data' => array(
			'cmds' => $cmds,
			'lines' => $ret_arr,
		),
	));
}

/**
 * 获取发版(deploy)记录数据
 * @return Array
 */
function rsync_deploy_get($id) {
	$deploy = M()->table('__RSYNC_DEPLOY__')->where("id='{$id}'")->find();
	return $deploy ? $deploy : array();
}

/**
 * 更新或新增发版(deploy)记录数据
 * @param $deployinfo Array 发版的数据 如果包含id字段且值大于0则为更新; 否则为新增
 * @return Integer 发版记录的主键id 或者 0表示失败
 */
function rsync_deploy_set($deployinfo) {
	$primaryId = 0;
	if (isset($deployinfo['id'])) {
		$primaryId = intval($deployinfo['id']);
		unset($deployinfo['id']);
	}
	if (isset($deployinfo['msglog']) && strlen($deployinfo['msglog']) > 0) {
		  $deployinfo['msglog'] = htmlspecialchars($deployinfo['msglog']);
	}
	if (isset($deployinfo['remark']) && strlen($deployinfo['remark']) > 0) {
		  $deployinfo['remark'] = htmlspecialchars($deployinfo['remark']);
	}
	if ($primaryId > 0) {
			if (false === M()->table('__RSYNC_DEPLOY__')->where("id={$primaryId}")->save($deployinfo)) { //返回值是影响的记录数 false表示更新失败
				$primaryId = 0;
			}
	} else {
		  if (!($primaryId = M()->table('__RSYNC_DEPLOY__')->add($deployinfo))) {
				$primaryId = 0;
			}
	}
	return $primaryId;
}

/**
 * 删除发版(deploy)记录数据
 * @param $deployid Integer / Array 发版的主键Id或者数据
 * @return Integer/Boolean(false) >=0表示删除成功(返回删除的条数) false表示执行删除操作失败
 */
function rsync_deploy_delete($deployid) {
	if (is_array($deployid)) {
			$deployinfo = $deployid;
			$deployid = intval($deployinfo['id']);
	} else {
			$deployid = intval($deployid);
			$deployinfo = rsync_deploy_get($deployid);
	}
	if (empty($deployinfo)) {
		  return 0;
	}
	return M()->table('__RSYNC_DEPLOY__')->where("id={$deployid}")->delete();
}

/**
 *	从harbor的镜像源读取镜像列表
 *  @param $requrl String 请求镜像数据的url地址
 *	@param $perpage Integer 每页展示数量 0表示不分页
 *  @param $page Integer 页码值 当 $perpage=0 时函数内部会忽略该参数
 *	@param $tag String 检索tag用的关键词（模糊查询） 会根据该关键词筛选符合条件的镜像数据
 *  @param
 *	@return Array
 array (
   'code' => 0,
   'msg' => 'succ',
	 'data' => array(
	   'count' => 0,
	   'list' => array(),
   ),
 )
   code:
	  0: 读取成功
		其他值: 读取失败 失败原因查看msg
 */
function pack_get_list($requrl, $perpage = 0, $page = 1, $tag = '', $revision = '') {
	$resu = array(
		'code' => 0,
		'msg' => 'succ',
		'data' => array(),
	);
	if(1 > ($flag = curl_http($requrl, '', $resp))) {
		$resu['code'] = 1;
		$resu['msg'] = "curl_http fail({$flag})";
		return $resu;
	}

	$resp = json_decode($resp, true);
	if(!is_array($resp)) {
		$resu['code'] = 2;
		$resu['msg'] = "curl_http 收到的数据不为json数组";
		return $resu;
	}

	if($resp['code'] != 200) {
		$resu['code'] = 3;
		$resu['msg'] = "接口code={$resp['code']}";
		return $resu;
	}

	$list = is_array($resp['results']) ? $resp['results'] : array();

	if(empty($list)) {
		$resu['code'] = 4;
		$resu['msg'] = "接口数据为空";
		return $resu;
	}

	if (strlen($tag) > 0) {
		$tmplist = array();
		foreach ($list as $item) {
			if (false !== stripos($item['tags'], $tag)) {
				$tmplist[] = $item;
			}
		}
		$list = $tmplist;
	}

	if (strlen($revision) > 0) {
		$tmplist = array();
		foreach ($list as $item) {
			if ($item['revision'] == $revision) {
				$tmplist[] = $item;
			}
		}
		$list = $tmplist;
	}

	if ($perpage < 1) {
		$resu['data'] = array(
			'count' => count($list),
			'list' => $list,
		);
		return $resu;
	}

	//分页逻辑
	$count = count($list);
	$pages = ceil($count/$perpage);//总页数
	if ($page < 1 || $page > $pages) {
		return $resu;
	}

	$start = ($page - 1) * $perpage;

	$resu['data'] = array(
		'count' => count($list),
		'list' => array_slice($list, $start, $perpage),
	);

	return $resu;
}

/** deploy/rollback 项目发版或回滚
 *  @param $group String 分组标识
 *  @param $appid Integer/Arrag app的id 或 app的配置数组
 *  @param $deployid Integer / Array deployid或者完整的deploy信息
 *  @param $rollback Boolean 是否为回滚操作 true:是 false:否
 *  @param $passport Array 账号与密码 array('username'=>'', 'password'=>'')
 *  @return Array
    array(
	    'status' => 0
		'message' => 'succ'
		data => array(),
	)

	data为空数组或如下数组
	array(
	    'cmds' => array(),
		'lines' => array(),
		'files' -> array(),
	)
 */
function app_deploy($group, $appid, $deployid, $rollback = false, $passport = array()) {
	$rarr = array(
		'status' => 0,
		'message' => 'succ',
		'data' => array(
		    'cmds' => array(),
				'lines' => array(),
				'files' => array(),
		),
	);
	if (is_array($appid)) {
		$app = $appid;
		$appid = intval($app['id']);
	} else {
		$applist = load_app_list($group);
		$app = isset($applist[$appid]) ? $applist[$appid] : array();
		if(empty($app)) {
			return array_replace_recursive($rarr, array(
	      'status' => 1,
				'message' => '项目不存在',
			));
		}
		$app = init_rsync_app_conf($app, $info);
		if ($app === false) {
			return array_replace_recursive($rarr, array(
	      'status' => 2,
				'message' => 'app配置不正确, ' . implode(', ', $info),
			));
		}
	}
	if ($app['repository'] != 'git') {
		return array_replace_recursive($rarr, array(
      'status' => 3,
			'message' => "该项目不是托管在GIT上的项目[{$app['repository']}]",
		));
	}
	if (!$app['is_sogou_docker']) {
		return array_replace_recursive($rarr, array(
      'status' => 4,
			'message' => "该项目不是搜狗Docker项目",
		));
	}
	if (is_array($deployid)) {
		  $deployinfo = $deployid;
		  $deployid = intval($deployinfo['id']);
	} else {
		  $deployid = intval($deployid);
		  $deployinfo = rsync_deploy_get($deployid);
	}
	if (empty($deployinfo)) {
			return array_replace_recursive($rarr, array(
	      'status' => 5,
				'message' => "该项目的发版数据不存在[deployid={$deployid}]",
			));
	}
	if ($deployinfo['username'] != session('USERNAME')) {
			return array_replace_recursive($rarr, array(
				'status' => 6,
				'message' => "只能对自己发起的流程进行操作",
			));
	}
	if ($deployinfo['locked']) {
			return array_replace_recursive($rarr, array(
				'status' => 7,
				'message' => "该发版数据已经锁定，不能进行发版或回滚操作",
			));
	}
	if ($deployinfo['status'] == 2 && !$rollback) {
			return array_replace_recursive($rarr, array(
				'status' => 8,
				'message' => "该版本已经是回滚状态，不能进行发版",
			));
	}
	if ($rollback) {
		  //检查是否还有更晚的已发版或已回滚的任务
			$lastDeployinfo = M()->table('__RSYNC_DEPLOY__')->where("id>{$deployinfo['id']} AND status>0 AND appid={$deployinfo['appid']} AND groupid={$deployinfo['groupid']} AND istest={$deployinfo['istest']}")->order('id ASC')->limit(1)->select();
			if (!empty($lastDeployinfo)) {
				return array_replace_recursive($rarr, array(
					'status' => 99,
					'message' => "不能回滚，还有更晚的版本处于非待发版状态[任务ID={$lastDeployinfo[0]['id']},USER={$lastDeployinfo[0]['username']}]",
				));
			}
	} else {
			//检查是否还有更早的待发版的任务
			$lastDeployinfo = M()->table('__RSYNC_DEPLOY__')->where("id<{$deployinfo['id']} AND status=0 AND appid={$deployinfo['appid']} AND groupid={$deployinfo['groupid']} AND istest={$deployinfo['istest']}")->order('id DESC')->limit(1)->select();
			if (!empty($lastDeployinfo)) {
				return array_replace_recursive($rarr, array(
					'status' => 99,
					'message' => "不能发版，还有更早的版本处于待发版状态[任务ID={$lastDeployinfo[0]['id']},USER={$lastDeployinfo[0]['username']}]",
				));
			}
	}

	$packinfo = array();
	// 判断发版或回滚读取不同的镜像
	if ($rollback) {
		$packinfo = json_decode($deployinfo['back_info'], true);
	} else {
		$packinfo = json_decode($deployinfo['pack_info'], true);
	}
	$appgroup = C('APP_GROUP');
	if(isset($appgroup[$group])) {
		  $appgroup = $appgroup[$group];
	} else {
			return array_replace_recursive($rarr, array(
				'status' => 9,
				'message' => "项目所属分组不存在[group={$group}]",
			));
	}
	if ($appgroup['docker_deploy_repository'] != 'git') {
			return array_replace_recursive($rarr, array(
				'status' => 10,
				'message' => "暂不支持托管在非git上的项目的镜像发布[repository={$appgroup['docker_deploy_repository']}]",
			));
	}

	if (!file_exists($appgroup['docker_deploy_dir'])) {
			return array_replace_recursive($rarr, array(
				'status' => 11,
				'message' => "docker-deploy目录不存在[{$appgroup['docker_deploy_dir']}]",
			));
	}
	if (!file_exists($appgroup['docker_deploy_dir']."/.git")) {
			return array_replace_recursive($rarr, array(
				'status' => 12,
				'message' => "docker-deploy目录不是git关联目录[{$appgroup['docker_deploy_dir']}]",
			));
	}

	$out_arr = array();

	$expect_bin_path = C('EXPECT_BIN_PATH_DEFAULT');
	$cmd = "{$expect_bin_path} -v 2>&1";
	exec($cmd, $out_arr, $out_var);
	$cmds[] = $cmd;
	if ($out_var !== 0) {
		  $cmds[count($cmds)-1] .= " [fail exec_ret_var={$out_var}]";
			return array_replace_recursive($rarr, array(
				'status' => 13,
				'message' => "没有安装expect或命令执行失败",
				'data' => array(
					  'cmds' => $cmds,
						'lines' => $out_arr,
				),
			));
	} else {
		  $cmds[count($cmds)-1] .= " [succ]";
	}
	$docker_deploy_command_path = isset($appgroup['docker_deploy_command_path']) && $appgroup['docker_deploy_command_path'] ? $appgroup['docker_deploy_command_path'] : 'odinctl2';
	$cmd = "{$docker_deploy_command_path} --version 2>&1";
	exec($cmd, $out_arr, $out_var);
	$cmds[] = $cmd;
	if ($out_var !== 0) {
		  $cmds[count($cmds)-1] .= " [fail exec_ret_var={$out_var}]";
			/*
			return array_replace_recursive($rarr, array(
				'status' => 14,
				'message' => empty($appgroup['docker_deploy_command_path']) ? "没有配置odinctl2命令的路径" : "odinctl2命令执行异常或者没有安装",
				'data' => array(
					  'cmds' => $cmds,
						'lines' => $out_arr,
				),
			));
			*/
	} else {
		  $cmds[count($cmds)-1] .= " [succ]";
	}
	$app['docker_deploy_cfg'] = explode(',', $app['docker_deploy_cfg']); //生成数组
	$docker_deploy_dir = "{$appgroup['docker_deploy_dir']}/" . ($deployinfo['istest'] ? $app['docker_deploy_cfg'][1] : $app['docker_deploy_cfg'][0]);
	$docker_deploy_file =  "{$app['docker_deploy_file']}";
	$docker_deploy_file_ext = fileext($docker_deploy_file);
	$docker_deploy_file_fullpath = "{$docker_deploy_dir}/{$docker_deploy_file}";

	if (!file_exists($docker_deploy_file_fullpath)) {
			return array_replace_recursive($rarr, array(
				'status' => 15,
				'message' => "docker-deploy的配置文件不存在[{$docker_deploy_file_fullpath}]",
				'data' => array(
					  'cmds' => $cmds,
						'lines' => $out_arr,
				),
			));
	}
	if ($docker_deploy_file_ext == 'json') {
			if(false === ($file_content = file_get_contents($docker_deploy_file_fullpath))) {
					return array_replace_recursive($rarr, array(
						'status' => 16,
						'message' => "docker-deploy的配置文件读取失败[{$docker_deploy_file_fullpath}]",
						'data' => array(
							  'cmds' => $cmds,
								'lines' => $out_arr,
						),
					));
			}
		  $confarr = json_decode($file_content, true);
	} elseif ($docker_deploy_file_ext == 'yaml') {
		  $confarr = yaml_parse_file($docker_deploy_file_fullpath);
			if ($confarr === false) {
					return array_replace_recursive($rarr, array(
						'status' => 17,
						'message' => "docker-deploy的配置文件读取失败[{$docker_deploy_file_fullpath}]",
						'data' => array(
								'cmds' => $cmds,
								'lines' => $out_arr,
						),
					));
			}
	} else {
			return array_replace_recursive($rarr, array(
				'status' => 18,
				'message' => "docker-deploy项目配置文件既不是json也不是yaml[{$docker_deploy_file_fullpath}]",
				'data' => array(
					  'cmds' => $cmds,
						'lines' => $out_arr,
				),
			));
	}
	if (!is_array($confarr)) {
			return array_replace_recursive($rarr, array(
				'status' => 19,
				'message' => "docker-deploy项目配置文件内容异常[解析完后非数组][{$docker_deploy_file_fullpath}]",
				'data' => array(
					  'cmds' => $cmds,
						'lines' => $out_arr,
				),
			));
	}

	//解析镜像所处节点位置  spec.template.spec.containers:name=gobin 或 containers:name=phpfpm
	if ($deployinfo['istest']) {
		  list($node_path_string, $key_string) = explode(':', $app['docker_deploy_cfg'][3]);
	} else {
		  list($node_path_string, $key_string) = explode(':', $app['docker_deploy_cfg'][2]);
	}
	$node_path_array = explode('.', $node_path_string);
	$key_array = explode('=', $key_string);

	//找到镜像所在的节点并更新镜像
	$key_path_string = '';
	$val_ref = null; //后面会引用赋值
	$key_not_exists = false;
	for ($i = 0; $i < count($node_path_array); $i++) {
		$key = $node_path_array[$i];
		if ($i == 0) {
			if (!isset($confarr[$key])) {
				$key_not_exists = true;
				break;
			}
			$val_ref = &$confarr[$key];
		} else {
			if (!isset($val_ref[$key])) {
				$key_not_exists = true;
				break;
			}
			$val_ref = &$val_ref[$key];
		}
	}
	if ($key_not_exists) {
		return array_replace_recursive($rarr, array(
			'status' => 20,
			'message' => "docker-deploy配置文件中key路径不存在[.".implode('][', $node_path_array).".]",
			'data' => array(
					'cmds' => $cmds,
					'lines' => $out_arr,
			),
		));
	}
	$find_image_node_succ = false;
	foreach ($val_ref as $idx => $item) {
		if (isset($item[$key_array[0]]) && $item[$key_array[0]] == $key_array[1]) {
			$find_image_node_succ = true;
			$tag = strrchr($item['image'], ':');
			if ($tag === false) {
					return array_replace_recursive($rarr, array(
						'status' => 21,
						'message' => "docker-deploy项目配置文件中的镜像名称格式不正确,无法更新(需要以 xxx:0.7.7 这种作为结尾)[{$docker_deploy_file_fullpath}]",
						'data' => array(
							  'cmds' => $cmds,
								'lines' => $out_arr,
						),
					));
			}
			$item['image'] = substr($item['image'], 0, -1*strlen($tag)) . ":{$packinfo['branch']}";
			$val_ref[$idx] = $item;
		}
	}
	unset($val_ref); //销毁引用
	if (!$find_image_node_succ) {
			return array_replace_recursive($rarr, array(
				'status' => 22,
				'message' => "没有找到镜像所在位置,docker-deploy配置文件中路径 {$node_path_string} 里面不存在 {$key_array[0]}={$key_array[1]} 的条目",
				'data' => array(
						'cmds' => $cmds,
						'lines' => $out_arr,
				),
			));
	}
	//加锁
	$lockmsg = '';
	$lockkey = 'rsync_docker_app_deploy' . ($deployinfo['istest'] ? '_test' : '');
	if (!rsync_lock($lockkey)) {
		return array_replace_recursive($rarr, array(
			'status' => 23,
			'message' => "docker-deploy发布前加锁失败[可能有其他人正在发布，请稍后再试]",
			'data' => array(
					'cmds' => $cmds,
					'lines' => $out_arr,
			),
		));
	}
	if ($docker_deploy_file_ext == 'json') {
		  $confstr = jsonFormat($confarr); //美化的json字符串
			$_re = file_put_contents($docker_deploy_file_fullpath, $confstr);
	} elseif ($docker_deploy_file_ext == 'yaml') {
			$_re = yaml_emit_file($docker_deploy_file_fullpath, $confarr);
	}
	if (false === $_re) {
			if (!rsync_unlock($lockkey)) {
				$lockmsg = '[解除锁失败(lockkey={$lockkey})]';
			} else {
				$lockmsg = '';
			}
			return array_replace_recursive($rarr, array(
				'status' => 24,
				'message' => "docker-deploy项目配置文件更新失败[{$docker_deploy_file_fullpath}]" . $lockmsg,
				'data' => array(
					  'cmds' => $cmds,
						'lines' => $out_arr,
				),
			));
	}

	// 生成shell命令文件
	$shell_file_template = realpath(APP_PATH.MODULE_NAME).'/Shell/docker-deploy_template.sh';
	if(false === ($content = file_get_contents($shell_file_template))) {
			if (!rsync_unlock($lockkey)) {
				$lockmsg = '[解除锁失败(lockkey={$lockkey})]';
			} else {
				$lockmsg = '';
			}
			return array_replace_recursive($rarr, array(
				'status' => 25,
				'message' => "读取docker发布的shell模板文件失败[{$shell_file_template}]" . $lockmsg,
				'data' => array(
					  'cmds' => $cmds,
						'lines' => $out_arr,
				),
			));
	}

	//替换生成可执行的正常命令
	$content = str_replace('<{expect_bin_path}>', $expect_bin_path, $content);
	$content = str_replace('<{docker_deploy_dir}>', $docker_deploy_dir, $content);
	$content = str_replace('<{docker_deploy_command_path}>', $docker_deploy_command_path, $content);
	$content = str_replace('<{username}>', $passport['username'], $content);
	$content = str_replace('<{password}>', $passport['password'], $content);
	$content = str_replace('<{docker_deploy_file}>', $docker_deploy_file, $content);

	//临时shell文件
	$shell_file_instance = realpath(RUNTIME_PATH) . '/Temp/docker-deploy-shell_' . random(8, false) . '.sh';
	if (false === file_put_contents($shell_file_instance, $content)) {
			if (!rsync_unlock($lockkey)) {
				$lockmsg = '[解除锁失败(lockkey={$lockkey})]';
			} else {
				$lockmsg = '';
			}
			return array_replace_recursive($rarr, array(
				'status' => 26,
				'message' => "通过模板生成docker-deplpy-shell文件保存失败[{$shell_file_instance}]" . $lockmsg,
				'data' => array(
					  'cmds' => $cmds,
						'lines' => $out_arr,
				),
			));
	}
	$cmd = "chmod a+x {$shell_file_instance} 2>&1";
	exec($cmd, $out_arr, $out_var);
	$cmds[] = $cmd;
	if ($out_var !== 0) {
		  $cmds[count($cmds)-1] .= " [fail exec_ret_var={$out_var}]";
			if (!rsync_unlock($lockkey)) {
				  $lockmsg = '[解除锁失败(lockkey={$lockkey})]';
			} else {
				  $lockmsg = '';
			}
			return array_replace_recursive($rarr, array(
				'status' => 27,
				'message' => "增加可执行权限失败[{$shell_file_instance}]" . $lockmsg,
				'data' => array(
					  'cmds' => $cmds,
						'lines' => $out_arr,
				),
			));
	} else {
		  $cmds[count($cmds)-1] .= " [succ]";
	}

	$cmd = "sh {$shell_file_instance} 2>&1";
	exec($cmd, $out_arr, $out_var);
	$cmds[] = $cmd;
	if ($out_var !== 0) {
		  $cmds[count($cmds)-1] .= " [fail exec_ret_var={$out_var}]";
			if (!rsync_unlock($lockkey)) {
				  $lockmsg = '[解除锁失败(lockkey={$lockkey})]';
			} else {
				  $lockmsg = '';
			}
		  if ($out_var == 1) {
				  $msg = "odinctl2 login失败,可能是账号或密码错误";
			} elseif ($out_var == 2) {
				  $msg = "odinctl2 apply失败";
			} else {
				  $msg = "未知错误";
			}
			return array_replace_recursive($rarr, array(
				'status' => 28,
				'message' => "执行docker发布失败[ret={$out_var},msg={$msg}]" . $lockmsg,
				'data' => array(
					  'cmds' => $cmds,
						'lines' => $out_arr,
				),
			));
	} else {
		  $cmds[count($cmds)-1] .= " [succ]";
	}
	unlink($shell_file_instance);

	//更新本次deploy的状态
	$extrmsg = '';
	$deployinfo['cmds'] = empty($deployinfo['cmds']) ? array() : json_decode($deployinfo['cmds'], true);
	$deployinfo['cmds'] = is_array($deployinfo['cmds']) ? $deployinfo['cmds'] : array();
	$newarr = array(
		'id' => $deployinfo['id'],
		'cmds' => json_encode(array_merge($deployinfo['cmds'], $cmds)),
	);
	if ($rollback) {
		$newarr['status'] = 2;
		$newarr['rollback_time'] = time();
	} else {
		$newarr['status'] = 1;
		$newarr['deploy_time'] = time();
	}
	if (rsync_deploy_set($newarr) < 1) {
		  $extrmsg .= "[更新流程状态失败(deployid={$deployinfo['id']})]";
	}

	//锁定更早的处于非待发版状态的任务
	if (false === M()->table('__RSYNC_DEPLOY__')->where("id<{$deployinfo['id']} AND status>0 AND appid={$deployinfo['appid']} AND groupid={$deployinfo['groupid']} AND istest={$deployinfo['istest']} AND locked=0")->save(array('locked'=>1))) {
		  $extrmsg .= "[锁定更早的非待发版的任务失败]";
	}

	if (!rsync_unlock($lockkey)) {
		$lockmsg = '[解除锁失败(lockkey={$lockkey})]';
	} else {
		$lockmsg = '';
	}

	return array_replace_recursive($rarr, array(
		'status' => 0,
		'message' => '发版成功['.($deployinfo['istest']?'测试':'正式').'环境]' . $lockmsg . $extrmsg,
		'data' => array(
			'cmds' => $cmds,
			'lines' => $out_arr,
			'files' => array(),
		),
	));
}

/**
 *  发布系统流程加锁
 *  @param $lockkey String 锁的名称
 *  @return Boolean false:加锁失败 true:加锁成功
 */
function rsync_lock($lockkey) {
	$primaryId = 0;
	$newarr = array(
		'lockkey' => $lockkey,
		'uid' => session('UID'),
		'username' => session('USERNAME'),
		'update_time' => time(),
	);
	if (!($primaryId = M()->table('__RSYNC_LOCKER__')->add($newarr))) {
		  $primaryId = 0;
	}
	return $primaryId;
}

/**
 *  发布系统流程解锁
 *  @param $lockkey String 锁的名称
 *  @return Boolean false:解锁失败 true:解锁成功
 */
function rsync_unlock($lockkey) {
	$resu = M()->table('__RSYNC_LOCKER__')->where("lockkey='{$lockkey}'")->delete();
	return $resu === false ? false : true;
}
