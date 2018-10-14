<?php
/**
 *	应用公共函数(默认公共函数)
 */


/**	功能 调试变量(针对浏览器界面友好输出)
 *	@param $var Mixed 要调试的变量
 *	@param $vardump Boolean 是否使用vardump函数输出变量信息 [false:使用print_r输出,true:使用var_dump输出]
 *	@param $exit Boolean 输出完是否终止运行
 *	@teturn void
 */
function sdebug($var = null, $vardump = false, $exit = false) {
	$sapi_prefix = substr(php_sapi_name(), 0, 3);
	echo $sapi_prefix == 'cli' ? '' : '<pre>';
	if($vardump) {
		var_dump($var);
	} else {
		print_r($var);
	}
	echo $sapi_prefix == 'cli' ? '' : '</pre>';
	if($exit)exit();
}

/**	过滤id字符串
 *	@param $idstr String 要过滤的字符串
 *	@param $int_id Boolean 是否为整型id 缺省true
 *	@param $no_repeat Boolean 是否去重 缺省true
 *	@param $return_arr Boolean 是否返回数组 缺省false
 *	@param $sep String 要过滤的字符串的分割符 缺省为英文半角逗号
 *	@teturn String/Array
 */
function filter_idstr($idstr, $int_id = true, $no_repeat = true, $return_arr = false, $sep=',') {
    $idstr = preg_replace("/\r|\n|\s/", '', trim($idstr, $sep));//处理为规范的格式
    if($idstr === '') {
        return $return_arr ? array() : $idstr;
    }
    //过滤无效
    $filter = array();//去重
    $idstr = explode($sep, $idstr);
    foreach($idstr as $key => $id) {
        if($no_repeat) {
            if(in_array($id, $filter, !0)) {
                unset($idstr[$key]);continue;
            }
            $filter[] = $id;
        }
    	if($int_id) {
    	    $idstr[$key] = intval($id);
	    	if($idstr[$key] < 1) {
        		unset($idstr[$key]);continue;
        	}
    	} else {
    	    $idstr[$key] = trim($id);
    	}
    }
    return $return_arr ? $idstr : implode($sep, $idstr);
}

/**	由数组元素生成连接字符串
 *	@param $arr Array 数组
 *	@param $sep String 用于连接数组元素的连接符
 *	@param $wrap String 数组元素修饰符 可能的值[', ", `, ...]
 *	@teturn String/Integer(0)
 */
function simplode($arr, $sep = ',', $wrap = '\'') {
	$arr = is_array($arr) ? $arr : (array)$arr;
	if(!empty($arr)) {
		return $wrap.implode($wrap.$sep.$wrap, $arr).$wrap;
	} else {
		return 0;
	}
}

/**	功能 根据偏移时间戳生成格式化的时间表示
 *	@param $timeoffset Integer 偏移时间戳(相对当日的起始时间戳)
 *	@param $showall Boolean 是否显示完整的时间格式 缺省true [true:是,false:否]
 *	@return String '08:00:00'或'08:00'或'22:05:01'
 */
function timeformat($timeoffset, $showall = true) {
	$timeoffset = intval($timeoffset);
	$hour = sprintf('%02d', $timeoffset/3600);
	$minute = ':'.sprintf('%02d', ($timeoffset%3600)/60);
	$second = '';
	if($showall) {
		$second = ':'.sprintf('%02d', $timeoffset%60);
	}
	return "{$hour}{$minute}{$second}";
}

/**	功能 是否ajax请求
 *	@return Boolean
 */
if(!function_exists('isajax')) {
	function isajax() {
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
			if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])){
				return true;
			}
		}
		if(!empty($_POST['inajax']) || !empty($_GET['inajax']))
			// 判断Ajax方式提交
			return true;
		return false;
	}
}

function ajaxmessage($message, $status = 0, $jumpurl = '', $waiting = 2, $data = '') {
	$ret_arr = array('message'=>$message,'status'=>$status,'url'=>$jumpurl,'waiting'=>$waiting,'data'=>$data);
	echo json_encode($ret_arr);exit;
}

/**	功能 获取文件名后缀
 *	@param $filename String 带后缀的文件名 必须 如 'data.txt','data.sql'
 *	@return String ''或'sql','txt',...
 */
function fileext($filename) {
	return addslashes(strtolower(substr(strrchr($filename, '.'), 1, 10)));
}

/**	功能 格式化字节大小
 *	@param $size Integer 文件字节数
 *	@return String 如:10.1KB, 0.99MB, ...
 */
function formatsize($size) {
	$prec=3;
	$size = round(abs($size));
	$units = array(0=>" B ", 1=>" KB", 2=>" MB", 3=>" GB", 4=>" TB");
	if ($size==0) return str_repeat(" ", $prec)."0$units[0]";
	$unit = min(4, floor(log($size)/log(2)/10));
	$size = $size * pow(2, -10*$unit);
	$digi = $prec - 1 - floor(log($size)/log(10));
	$size = round($size * pow(10, $digi)) * pow(10, -$digi);
	return $size.$units[$unit];
}

/**	功能 格式化数值大小
 *	@param $size Integer 数值
 *	@return String 如:9999, 1.2万, ...
 */
function formatnumber($number) {
	$number = abs($number);
	$units = array(0=>"", 1=>"万", 2=>"亿");
	if($number < pow(10, 4)) return round($number, 1).$units[0];
	if($number < pow(10, 8)) return round($number/pow(10, 4), 1).$units[1];
	return round($number/pow(10, 8), 1).$units[2];
}

/**	功能 递归的方式将数组转换为字符串描述形式
 *	@param $array Array/String 数组或字符串 必须
 *	@param $level Integer 层级 可选,缺省0
 *	@return String
 */
function arrayeval($array, $level = 0) {
	if(!is_array($array)) {
		return "'".$array."'";
	}
	if(is_array($array) && function_exists("var_export")) {
		return var_export($array, true);
	}

	$comma = $space = '';
	for($i = 0; $i < $level; $i++) {
		$space .= "\t";
	}
	$evaluate = "Array\n$space(\n";
	$comma = $space;
	if(is_array($array)) {
		foreach($array as $key => $val) {
			$key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
			$val = !is_array($val) && (!preg_match("/^\-?[1-9]\d*$/", $val) || strlen($val) > 12) ? '\''.addcslashes($val, '\'\\').'\'' : $val;
			if(is_array($val)) {
				$evaluate .= "$comma\t$key => ".arrayeval($val, $level + 1);
			} else {
				$evaluate .= "$comma\t$key => $val";
			}
			$comma = ",\n$space";
		}
	}
	$evaluate .= "\n$space)";
	return $evaluate;
}

/**	功能 检测字符串是否存在
 *	@param $haystack String 被查找的字符串
 *	@param $needle String 要查找的字符串
 *	@param $case Boolean 大小写敏感 缺省true(大小写敏感)
 *	@return Boolean 存在则返回true 不存在则返回false
 */
function strexists($haystack, $needle, $case = true){
	return $case ?  !(strpos($haystack, $needle) === false) : !(stripos($haystack, $needle)===false);
}

/**	功能 处理搜索关键字
 *	@param $string String 要搜索的关键字
 *	@return String
 */
function stripsearchkey($string) {
	$string = trim($string);
	$string = str_replace('*', '%', addcslashes($string, '%_'));
	$string = str_replace('_', '\_', $string);
	return $string;
}

/**	功能 转义$string中的HTML代码
 *	@param $string String
 *	@return String
 */
function shtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = shtmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
			str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

/**	功能 转换时间戳,将 $daytime 转换为 时间戳(整数)或者完整的时间字符串:2014-08-22 17:50
 *	@param $daytime String '2014-08-22' 或 '2014-08-22 17:50' 或 '2014-08-22 17:50:00'
 *	@param $return_timestamp Boolean 是否返回时间戳 [true:返回时间戳,false:返回完整的时间字符串]
 *	@return Integer/String
 */
function daytime($daytime, $return_timestamp = false) {
	$ret = $return_timestamp ? 0 : '';
	$daytime = trim($daytime);
	if(empty($daytime)) {
		return $ret;
	}
	$preg_daytime = "/^[1-9]\d{3}-\d{2}-\d{2}( \d{2}:\d{2}(:\d{2})?)?$/";
	if(!preg_match($preg_daytime, $daytime)) {
		return $ret;
	}
	$daytime = strtotime($daytime);
	if($daytime === false) {
		return $ret;
	}
	return $return_timestamp ? $daytime : date('Y-m-d H:i:s', $daytime);
}

/**	功能 交换时间戳,将 $daytime1与$daytime2按大小互换,结果是 $daytime1<=$daytime2
 *	@param $daytime1 String/Integer '2014-08-22' 或 '2014-08-22 17:50' 或 '2014-08-22 17:50:00' 或 1345878951
 *	@param $daytime2 String/Integer 同$daytime1
 *	@return Boolean(true)
 */
function daytime_swap(&$daytime1, &$daytime2) {
	if($daytime1 > $daytime2) {
		$tmp = $daytime1;
		$daytime1 = $daytime2;
		$daytime2 = $tmp;
	}
	return true;
}

/**	功能 递归创建目录
 *	@param $dir String 要创建的目录名 必须
 *	@param $mode Integer 目录权限 可选 缺省0775
 *	@param $exit Boolean 创建失败是否exit 可选 缺省false
 *	@return Boolean [true:'创建成功',false:'创建失败']
 */
function smkdir($dir, $mode = 0775, $exit = false){
	$exit = $exit ? true : false;
	if(!is_dir($dir)) {
		smkdir(dirname($dir), $mode, $exit);
		$flag = @mkdir($dir,$mode);
		if(!$flag && $exit)
			exit("创建目录失败:{$dir}");
	} else {
		$flag = true;
	}
	return $flag ? true : false;
}

/**	功能 递归创建目录
 *	@param $dir String 要创建的目录名 必须
 *	@param $mode Integer 目录权限 可选 缺省0775
 *	@return Boolean [true:'创建成功',false:'创建失败']
 */
function mkdir_recursive($dirname, $mode = 0755) {
	is_dir(dirname($dirname)) || mkdir_recursive(dirname($dirname), $mode);
	return is_dir($dirname) || @mkdir($dirname, $mode);
}

/**	功能 添加转义
 *	@param $string String/Array
 *	@return String/Array
 */
function saddslashes($string) {
	if(is_array($string)) {
		$keys = array_keys($string);
		foreach($keys as $key) {
			$val = $string[$key];
			unset($string[$key]);
			$string[addslashes($key)] = saddslashes($val);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

/**	功能 去除转义
 *	@param $string String/Array 待处理的串
 *	@return String/Array 去除转义处理后的串
 */
function sstripslashes($string) {
	if(empty($string)) return $string;
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = sstripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}

/**	功能 截取字符串
 *	@param $string String 要截取的字符串
 *	@param $length Integer 截取的长度
 *	@param $dot String 截取后追加的串(没有截取则不会追加)
 *	@param $rule Integer 截取规则 缺省0 [0:按照字节长度截取,utf8或非utf8,1个中文字符字节长度记为3或2,1个非中文字符字节长度记为1或1, 1或其他值:按照字长度截取,不论何种编码,不论是否中文字符,1个字符的字长均记为1]
 *	@param $charset String $string参数的字符编码类型 缺省utf-8
 *	@return String
 */
function cutstr($string, $length, $dot = '...', $rule = 0, $charset = 'utf-8') {
	if($string === '')
		return $string;
	$charset = $charset === '' ? 'utf-8' : strtolower($charset);
	$charset = $charset == 'utf-8' || $charset == 'utf8' ? 'utf-8' : $charset;

	$strlen = strlen($string);//字符串的字节长度
	if($rule == 0 && $strlen <= $length) {
		return $string;
	}

	$wl = $rule == 0 ? ($charset == 'utf-8' ? 3 : 2) : 1;//1个非英文字符用于计算的长度
	$strcut = '';
	if($charset == 'utf-8') {
		$n = $tn = $noc = 0;
		while($n < $strlen) {
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || $t == 13 || (32 <= $t && $t <= 126)) {//水平制表符,换行符,回车符,及空格,字母普通字符
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += $wl;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += $wl;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += $wl;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += $wl;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += $wl;
			} else {// [0,8] U {11,12} U [14,31] U [127,193] U {254,255} 均为控制符,不计入长度,但产生字节位移
				$n++;
			}

			if($noc >= $length) {
				break;
			}

		}
		if($noc > $length) {
			$n -= $tn;
		}
	} else {
		$n = $tn = $noc = 0;
		while($n < $strlen) {
			if(ord($string[$n]) <= 127) {
				$tn = 1; $n++; $noc++;
			} else {
				$tn = 2; $n += 2; $noc += $wl;
			}
			if($noc >= $length) {
				break;
			}
		}
		if($noc > $length) {
			$n -= $tn;
		}
	}
	$strcut = substr($string, 0, $n);

	$pos = strrpos($strcut, chr(1));
	if($pos !== false) {
		$strcut = substr($strcut,0,$pos);
	}
	return strlen($strcut) == $strlen ? $strcut : $strcut.$dot;
}

/**	功能 隐藏(替换为空或其他掩盖性字符)字符串
 *	@param $string String 要隐藏的字符串
 *	@param $hidlen Integer 隐藏的字的长度(中文或非中文字符,1个字符记为1字长)
 *	@param $replace String 隐藏的字符被该参数替换,该参数可以是空或其他掩盖性字符
 *	@param $maxlen Integer 字符串的最大字长(超过该值会进行截取) 缺省0(0为不限制)
 *	@param $charset String $string参数的字符编码类型 缺省utf-8
 *	@return String
 */
function hiddenstr($string, $hidlen, $replace = '*', $maxlen = 0, $charset = 'uft-8') {
	$hidlen = abs(intval($hidlen));
	$maxlen = abs(intval($maxlen));
	$charset = $charset === '' ? 'utf-8' : strtolower($charset);
	$charset = $charset == 'utf-8' || $charset == 'utf8' ? 'utf-8' : $charset;

	$strlen = strlen($string);//字符串的字节长度

	if($maxlen) {
		$string = cutstr($string, $maxlen, '', $charset, 1);
	}

	if($hidlen) {
		$wordlength = wordlength($string, 1, $charset);
		if($wordlength > $hidlen) {
			$string = cutstr($string, ($wordlength - $hidlen), '', $charset, 1);
			for($i = 0; $i < $hidlen; $i++) {
				$string .= $replace;
			}
		}
	}

	return $string;
}

/**	功能 计算字符串的字长(字符串的字节数或字符个数,视计算规则而定)
 *	@param $string String 要计算字长的字符串
 *	@param $rule Integer 计算规则 缺省0 [0:字长等于字节数,utf8/非utf8编码下,中文符1个字符的字长记为3/2,其他字符1个字符的字长记为1/1, 1:字长等于字符个数,即中文或非中文字符,1个字符记为1字长]
 *	@param $charset String $string参数的字符编码类型 缺省utf-8
 *	@return Integer
 */
function wordlength($string, $rule = 0, $charset = 'uft-8'){
	$charset = $charset === '' ? 'utf-8' : strtolower($charset);
	$charset = $charset == 'utf-8' || $charset == 'utf8' ? 'utf-8' : $charset;

	$wl = $rule == 0 ? ($charset == 'utf-8' ? 3 : 2) : 1;//1个非英文字符用于计算的长度
	$strlen = strlen($string);

	if($rule == 0)
		return $strlen;

	if($charset == 'utf-8') {
		$n = $noc = 0;
		while($n < $strlen) {
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || $t == 13 || (32 <= $t && $t <= 126)) {//水平制表符,换行符,回车符,及空格,字母普通字符
				$n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$n += 2; $noc += $wl;
			} elseif(224 <= $t && $t <= 239) {
				$n += 3; $noc += $wl;
			} elseif(240 <= $t && $t <= 247) {
				$n += 4; $noc += $wl;
			} elseif(248 <= $t && $t <= 251) {
				$n += 5; $noc += $wl;
			} elseif($t == 252 || $t == 253) {
				$n += 6; $noc += $wl;
			} else {// [0,8] U {11,12} U [14,31] U [127,193] U {254,255} 均为控制符,不计入长度,但产生字节位移
				$n++;
			}
		}
	} else {
		$n = $noc = 0;
		while($n < $strlen) {
			if(ord($string[$n]) <= 127) {
				$n++; $noc++;
			} else {
				$n += 2; $noc += $wl;
			}
		}
	}

	return $noc;
}

/**	功能 字符串过滤
 *	@param $String String 待处理的输入字符串
 *	@param $length Integer 要截取的长度 缺省为0(0表示不截取)
 *	@rule $rule 截取规则 参照 cutstr()函数的对应参数
 *	@param $in_slashes Integer 输入串是否已转义 缺省0 [0:待处理的字符串已经添加转义,~0:待处理的字符串未添加转义]
 *	@param $out_slashes Integer 输出串是否需要添加转义 缺省0 [0:输出字符串不需要添加转义,~0:输出字符串需要添加转义]
 *	@param $html Integer 是否进行html过滤处理 缺省0 [小于0:对字符串进行去html标签,等于0:对字符串进行转换html标签]
 *	@return String
 */
function getstr($string, $length = 0, $rule = 0, $in_slashes = 0, $out_slashes = 0, $html = 0) {
	$string = trim($string);
	$sppos = strpos($string, chr(0).chr(0).chr(0));
	if($sppos !== false) {
		$string = substr($string, 0, $sppos);
	}
	if($in_slashes) {
		$string = sstripslashes($string);
	}
	if($html < 0) {
		$string = preg_replace("/(\<[^\<]*\>|\r|\n|\s|\[.+?\])/is", ' ', $string);
	} elseif ($html == 0) {
		$string = shtmlspecialchars($string);
	}

	if($length > 0) {
		$string = cutstr($string, $length, '', $rule);
	}

	if($out_slashes) {
		$string = saddslashes($string);
	}
	return trim($string);
}

/**	功能 curl http get/post请求
 *	@param $url String 请求的url地址
 *	@param $params String/Array http 请求的参数[String形式:a=b&c=d, Array形式:array('a'=>'b','c'=>'d')]
 *	@param &$resp Mixed(String/Integer) 请求的结果 或 curl失败的状态码
 *	@param $method $string [get:http get,post:http post]
 *	@param $headers Array 一个用来设置HTTP头字段的数组。使用如下的形式的数组进行设置： array('Content-type: text/plain', 'Content-length: 100')
 *	@return Integer
 *		1: 请求成功
 *		0: url为空
 *		-1: 初始化curl失败
 *		-2: curl_exec失败
 *		-3: 响应的状态码有误(不等于200)
 */
function curl_http($url, $params, &$resp, $method = 'get', $headers = array()) {
	if($url === '')
		return 0;
	$method = strtolower($method);
	if($method == 'get') {
		$url .= stripos($url, '?') === false ? '?' : '';
		$param_str = '';
		if(is_array($params)) {
			$param_str = http_build_query($params);
		} else {
			$param_str .= $params === '' ? '' : ('&' . $params);
		}
		$url .= $param_str === '' ? '' : $param_str;
	}

	$curl = @curl_init();
	if($curl === false)
		return -1;

	@curl_setopt($curl, CURLOPT_URL, $url);
	@curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	@curl_setopt($curl, CURLOPT_HEADER, false);
	@curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);/*不验证https证书*/
	@curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);/*不验证httpsHOST*/
	if($method != 'get') {
		@curl_setopt($curl, CURLOPT_POST, true);
		@curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
	}
	if(!empty($headers)) {
		@curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	}

	$resp = @curl_exec($curl);

	if(($errno = @curl_errno($curl)) !== 0) {
		$resp = $errno;
		@curl_close($curl);
		return -2;
	}

	$info = curl_getinfo($curl);
	if($info['http_code'] != '200') {
		$resp = $info['http_code'];
		@curl_close($curl);
		return -3;
	}

	//释放curl句柄
	@curl_close($curl);

	return 1;
}

/**	功能 获取client ip
 *	@param $only_clientip Boolean 缺省false
	该参数的作用 用户使用代理访问时,取到的可能是多个ip: ip1,ip2,... 当 only_clientip=ture时仅仅返回ip1(clientip), 否则返回全部ip
 *	@return String
 */
function get_onlineip($only_clientip = false) {
	$onlineip = '';
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	if($onlineip && $only_clientip && strpos($onlineip, ',') !== false) {
		$onlineip = substr($onlineip, 0, strpos($onlineip, ','));
	}
	//检查是否为ip
	if(!preg_match('/^\d{1,3}(\.\d{1,3}){3}(,\d{1,3}(\.\d{1,3}){3})*$/i', $onlineip)) {
		$onlineip = '';
	}
	return $onlineip;
}

/**	功能 生成随机字符串
 *	@param $length Integer 生成的随机串的字符长度
 *	@param $numeric Boolean 是否纯数字串 缺省为false [true:是, false:否]
 *	@return String
 */
function random($length, $numeric = false) {
	$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	if($numeric) {
		$hash = '';
	} else {
		$hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
		$length--;
	}
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed{mt_rand(0, $max)};
	}
	return $hash;
}


function isLogined() {
	return session('?UID') ? session('UID') : 0;
}

function check_login() {
	return isLogined();
}

function getSiteUrl() {
    $local_path = dirname($_SERVER['SCRIPT_NAME']);
    //Note that when using ISAPI with IIS, the value will be off if the request was not made through the HTTPS protocol.
    $uri = 'http'.(in_https_mode() ?'s':'').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    return ($rstr=substr($uri,0,strpos($uri,$local_path,in_https_mode()?8:7)+strlen($local_path))).(substr($rstr,-1)=='/'?'':'/');
}
/**
 * Page is in https mode
 * return true or false
 */
function in_https_mode() {
    if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])) {
        return true;
    }
    return false;
}

/**
 *	功能url rewrite模式下url参数的编码
 *	@return String
 */
function urlrewrite_encode($string) {
	return C('URL_MODEL') == 2 ? str_replace('/', '|', $string) : $string;
}

/**
 *	功能url rewrite模式下url参数的解码
 *	@return String
 */
function urlrewrite_decode($string) {
	return C('URL_MODEL') == 2 ? str_replace('|', '/', $string) : $string;
}

/**
 *	格式化秒数
 *	@param $sec Integer 格式化的秒数
 *	@return String 如 '00:00:05'
 */
function format_sec($sec) {
	return sprintf('%02d',(int)($sec/3600)).':'.sprintf('%02d',(int)(($sec%3600)/60)).':'.sprintf('%02d',($sec%60));
}

/**
 *	根据文件名判断文件是否为图片文件
 *	@param $sec Integer 格式化的秒数
 *	@return String 如 '00:00:05'
 */
function file_is_image($filename) {
	return in_array(fileext($filename), array('jpg','jpeg','gif','png','gif'), true);
}

function isip($ip) {
	return preg_match('/^\d{1,3}(\.\d{1,3}){3}$/', $ip) ? true : false;
}

function microtime_float() {
	list($usec, $sec) = explode(' ', microtime());
	return ((float)$usec + (float)$sec);
}

/**
 * 生成配置文件
 */
function write_config_file($file, $array) {
	$strlen = @file_put_contents($file, "<?php\n//".date('Y-m-d H:i:s')."\nreturn ".arrayeval($array).";\n?>");
	@chmod($file, 0777);
	return $strlen;
}

/** 版本格式化 如 当seg=4 bits=4 时，将 8.11.3 格式化为 8.0011.0003.0; 当seg=3 bits=4时，将 8.11.3 格式化为 8.0011.0003
 *  @param $num Integer/String 整型数字或数字型字符串
 *  @param $seg Integer 区段数
 *  @param $bits 每个区段的位数
 *  @return String
 */
function version_format($ver, $seg = 4, $bits = 4) {
	if (empty($ver)) {
		return '0.0000.0000.0';
	}
	$ver = trim($ver, '.');
	$arr = array();
	if (false !== strpos($ver, '.')) {
		$arr = explode('.', $ver);
	} else {
		$arr[] = $ver;
	}
	$count = count($arr);
	if ($count < $seg) {
		for($idx = 0; $idx < $seg - $count; $idx++) {
			$arr[] = '0';
		}
	} else {
		$arr = array_slice($arr, 0, $seg);
	}
	$str = $comma = '';
	foreach($arr as $idx => $val) {
		$str .= "{$comma}".number_buwei($val, $bits, $idx==0?true:false, $idx>=$seg-1?true:false);
		$comma = '.';
	}
	return $str;
}

/** 数字左补位 如 当bits=4 ishead=false istail=false时 0 格式化为 0000, 1 格式化为 0001 12 格式化为 0012
 *  @param $num Integer/String 整型数字或数字型字符串
 *  @param $bits Integer 补位数
 *  @param $ishead Boolean 是否放在段位头部
 *  @param $istail Boolean 是否放在段位尾部
 *  @return String
 */
function number_buwei($num, $bits = 4, $ishead = false, $istail = false) {
	$strlen = strlen($num);
	if ($strlen > $bits) {
		return substr($num, 0, $bits);
	} else {
		if ($ishead || ($num == 0 && $istail)) {
			return "{$num}";
		} else {
			return str_repeat('0', $bits-$strlen)."{$num}";
		}
	}
}

/** Json数据格式化 来源: https://blog.csdn.net/fdipzone/article/details/28766357
* @param  Mixed  $data   数据 数组或字符串
* @param  String $indent 缩进字符，默认4个空格
* @return JSON
*/
function jsonFormat($data, $indent = null)
{
		// 对数组中每个元素递归进行urlencode操作，保护中文字符
		array_walk_recursive($data, 'jsonFormatProtect');

		// json encode
		$data = json_encode($data);

		// 将urlencode的内容进行urldecode
		$data = urldecode($data);

    // 缩进处理
    $ret = '';
    $pos = 0;
    $length = strlen($data);
    $indent = isset($indent) ? $indent : '    ';
    $newline = "\n";
    $prevchar = '';
    $outofquotes = true;

    for ($i=0; $i<=$length; $i++) {
        $char = substr($data, $i, 1);

        if ($char=='"' && $prevchar!='\\') {
            $outofquotes = !$outofquotes;
        } elseif (($char=='}' || $char==']') && $outofquotes) {
            $ret .= $newline;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $ret .= $indent;
            }
        }

        $ret .= $char;

        if (($char==',' || $char=='{' || $char=='[') && $outofquotes) {
            $ret .= $newline;
            if ($char=='{' || $char=='[') {
                $pos ++;
            }

            for ($j=0; $j<$pos; $j++) {
                $ret .= $indent;
            }
        }

        $prevchar = $char;
    }

    return $ret;
}


/** 将数组元素进行urlencode
* @param String $val
*/
function jsonFormatProtect(&$val){
    if(is_string($val)){
        $val = urlencode($val);
    }
}

/**
 * 通过模板结合完整数据生需要的成配置数据
 * @param $template Array 模板数据
 * @param $config Array 完整的数据
 * @param &$notsetkeys Array 引用传值 会被填充模板中存在但数据中不存在的key
 * @param $keystring String 数组的深度累计字符串
 * @return Array 与$template字段数一致的填充完$config对应字段值结果数据
 */
function common_generate_conf_by_template($template, $config, &$notsetkeys, $keystring = '') {
		if (is_array($template) && !empty($template)) {
			  foreach ($template as $key => $value) {
	          if (!isset($config[$key])) {
	              $notsetkeys[] = $keystring."[{$key}]";
	          } else {
	              $template[$key] = common_generate_conf_by_template($template[$key], $config[$key], $notsetkeys, "{$keystring}[{$key}]");
	          }
	      }
		} else {
			  $template = $config;
		}
    return $template;
}
