<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title>SVN/GIT+RSYNC代码发布系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=10;chrome=1;" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/bootstrap3.3.7/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/bootstrap-select-1.13.2/css/bootstrap-select.min.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/style.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/rsync/style.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/ui-dialog.css" />
<script type="text/javascript">var URL_MODEL=<{:C('URL_MODEL')}>;var BASE_URL='<{:getSiteUrl()}>';</script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.form.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/bootstrap3.3.7/js/bootstrap.js"></script>
<script type="text/javascript" src="__PUBLIC__/bootstrap-select-1.13.2/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/bootstrap-select-1.13.2/js/i18n/defaults-zh_CN.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/artdialog/dialog-plus.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/ie10-viewport-bug-workaround.js"></script>
</head>
<body>
<div id="append_parent"></div><div id="ajaxwaitid"></div>
<div class="wrapper">
<div class="header cl">
	<div class="uinfo cl">
		<if condition="$Think.session.UID gt 0">
		您好，<{$Think.session.NICKNAME}>&nbsp;|&nbsp;<a href="<{:U('Admin/Public/logout')}>" onclick="logout(<{:C('PANDORA_APPID')}>)">注销</a>
		<else/>
		<a href="<{:U('Admin/Public/login')}>" onclick="login()">登录</a>
		</if>
		&nbsp;|&nbsp;<a href="<{:U('Home/Index/index')}>">主页</a>
		<if condition="$_SERVER['HTTP_HOST'] == 'cms.shouji.sogou-inc.com'">
		<if condition="$Think.session.UID gt 0 AND ($Think.session.UTYPE eq 2 OR is_super_admin())">
		&nbsp;|&nbsp;<a href="<{:U('Admin/Index/index')}>">后台</a>
		</if>
		</if>
	</div>
	<div class="head-cont"><span class="logo">SVN/GIT+RSYNC代码发布系统</span></div>
</div>
