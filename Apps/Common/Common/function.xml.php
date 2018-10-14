<?php
/**
 *	项目公共函数
 */

/** 
 *	解析XML格式的字符串 
 *	@param $str String
 *	@return Boolean/Array 解析正确就返回解析结果,否则返回false,说明字符串不是XML格式 
 */ 
function xml_parser($str) {   
	$xml_parser = xml_parser_create();   
	if(!xml_parse($xml_parser, $str, true)){
		xml_parser_free($xml_parser);
		return false;
	} else {
		return (json_decode(json_encode(@simplexml_load_string($str)),true));
	}
}
?>