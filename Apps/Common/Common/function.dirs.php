<?php
/**
 *	项目公共函数
 */

/**	
 *	功能 目录列表
 *	@param $dir String 要list的目录
 *	@param $grep String 要执行的grep操作
 *	@param $assoc Boolean 是否返回关联数组 缺省true [tue:是,false:否]
 *	@param $orderby String 排序的字段 可能的值[fn:以文件名排序,t:按时间进行文件的排序,S:以文件大小进行排序]
 *	@param $ordersc String 排序的规则 可能的值[空:升序;r:降序]
 *	@param $timestyle String 列出的文件的时间格式
 *	@return Array
 */
function dirlist($dir, $grep = '', $assoc = true, $orderby = 'fn', $ordersc = '', $timestyle = '+%Y-%m-%d %H:%M:%S') {
	$list = array();
	if(!is_dir($dir)) {
		return $list;
	}
	$dir = realpath($dir);
	
	if($orderby == 'fn') {
		$cmd = "cd {$dir} && ls -al --time-style='{$timestyle}' | sort -{$ordersc}k8";
	} else {
		$cmd = "cd {$dir} && ls -al{$orderby}{$ordersc} --time-style='{$timestyle}'";
	}
	if(!empty($grep)) {
		$cmd .= " | {$grep}";
	}
	
	@exec($cmd, $list, $var);
	
	if($var !== 0) {
		$list = array();
	} else {
		$keys = $cup = array();
		foreach($list as $key => $line) {
			$cols = explode(' ', @preg_replace('/\s+/', ' ', trim($line)));//1个以上的空格替换为1个空格,再分割
			if(count($cols) < 8) {
				unset($list[$key]);
				continue;
			}
			if($cols[7] == '.') {
				@array_unshift($keys, $key);
			}
			if($cols[7] == '..') {
				@array_push($keys, $key);
			}
			if($assoc) {
				$list[$key] = array(
					'drwx' => $cols[0],
					'inode' => $cols[1],
					'owner' => $cols[2],
					'group' => $cols[3],
					'size' => $cols[4],
					'day' => $cols[5],
					'time' => $cols[6],
					'filename' => $cols[7],
					'lasttime' => "{$cols[5]} {$cols[6]}",
				);
			} else {
				$list[$key] = $cols;
			}
		}
		$cup = array_merge(isset($keys[1])?array($list[$keys[1]]):array(), isset($keys[0])?array($list[$keys[0]]):array());
		unset($list[$keys[0]],$list[$keys[1]]);
		$list = @array_merge($cup, $list);
	}
	return $list;
}

/**	
 *	功能 目录检查与过滤(支持中文名的目录)
 *	@param $dir String 要检查与过滤的目录
 *	@param $urldecode Boolean 是否使用urldecode处理 缺省false [tue:是,false:否]
 *	@return String
 */
function dirfilter($dir, $urldecode = false) {
	$dir = str_replace('|', '/', $dir);
	
	if(!preg_match('/^[×\w\-\_\%\.\+\/\$\(\)\\\\\x{4e00}-\x{9fa5}]+$/iu', $dir)) {
		$dir = '';
	}
	return $dir;
}

/**	
 *	功能 分割目录路径构建层级目录
 *	@param $dir String 要处理的目录
 *	@return Array
		$dir = 'a/b/c';
		返回：
		array(
			'0_a' => 'a',
			'1_b' => 'a/b',
			'2_c' => 'a/b/c',
		);
 */
function dir_layer_list($dir) {
	$dirs = $cup = array();
	if(!empty($dir)) {
		$dirs = explode('/', $dir);
		for($layer=0;$layer<count($dirs);$layer++) {
			$dirstr = $sepr = '';
			for($ii=0;$ii<=$layer;$ii++) {
				$dirstr .= "{$sepr}{$dirs[$ii]}";
				$sepr = '/';
			}
			$cup[$layer.'_'.$dirs[$layer]] = $dirstr;
		}
		$dirs = $cup;
	}
	return $dirs;
}

/**	
 *	功能 目录文件列表(含子目录)
 *	@param $dir String 要list的目录
 *	@param $allpath Boolead 是否返回绝对路径 缺省true [tue:是,false:否(返回相对路径,相对于$dir)]
 *	@param $list Array 引用传值 存放结果列表
 *	@param $ext String 只读取扩展名为$ext的文件 缺省空字符串(读取所有文件)
 *	@param $pdir String 父级目录 缺省空字符串 该参数无需传值
 *	@return Array array() / array(0=>xxx.php,0=>xxx/xxx.txt,...)
 */
function dir_file_list($dir, $allpath = true, $ext = '', $pdir = '') {
	static $list = array();
	if($pdir === '') {//用到了静态变量存储目录文件列表,如果不加这一段,外部显式先后多次调用该函数时,后一次调用返回的结果总会包含前一次调用的结果。
		$list = array();
	}
	if($pdir !== '' && $pdir != './') {
		$pdir .= DIRECTORY_SEPARATOR;
	}
	static $rootpath = '';
	if(@is_dir($dir)) {
		$dir = realpath($dir);
		if(!$allpath && $pdir === '') {
			$rootpath = $dir;
		}
		if(false !== ($handle = @opendir($dir))) {
			while(false !== ($file = readdir($handle))) {
				if(substr($file,0,1) != '.') {
					dir_file_list($dir.DIRECTORY_SEPARATOR.$file, $allpath, $ext, $dir);
				}
			}
			@closedir($handle);
		}
	} else {
		if($ext === '' || $ext === '*' || ($ext !== '' && fileext($dir) == $ext)) {
			$list[] = ($allpath ? $pdir:str_replace(($rootpath.DIRECTORY_SEPARATOR), '', $pdir)).str_replace($pdir,'',$dir);
		}
	}
	return $list;
}

/**	
 *	功能 目录路径转换(用于url传参,收到后需要用dirfilter函数解读出来)
 *	@param $dir String 目录名(相对或绝对)
 *	@return String
 */
function dir_convert($dir) {
	return str_replace('\\', '|', str_replace('/', '|', $dir));
}

/**	检查$dir是否为$pdir的下级目录
 *	@param $dir 要检查的目录(不能含有.或..)
 *	@param $pdir 要检查的目录(不能含有.或..)
 *	@return Boolean
 */
function is_sub_dir($dir, $pdir) {
	if(strlen($dir) > strlen($pdir) && substr($dir, 0, strlen($pdir)) == $pdir) {
		return true;
	}
	return false;
}

/**	检查$dir是否为$cdir的上级目录
 *	@param $dir 要检查的目录(不能含有.或..)
 *	@param $cdir 要检查的目录(不能含有.或..)
 *	@return Boolean
 */
function is_sup_dir($dir, $cdir) {
	return is_sub_dir($cdir, $dir);
}
?>