<include file="Public/header" />
<div class="main-cont">
<ul id="navigator" class="breadcrumb">
	<li><a href="<{:U('index')}>">首页</a></li>
	<li class="active">[项目]<{$app.name}>[路径:<{$app_root}>/<{$dir}>]</li>
</ul>
<ul class="nav nav-tabs">
<if condition="!empty($app['root_dir'])">
  <li role="presentation"<if condition="$istest==0"> class="active"</if>><a href="<{:U('app',array('appid'=>$app['id'],'istest'=>0))}>">正式环境</a></li>
</if>
<if condition="!empty($app['root_dir_test'])">
  <li role="presentation"<if condition="$istest==1"> class="active"</if>><a href="<{:U('app',array('appid'=>$app['id'],'istest'=>1))}>">测试环境</a></li>
</if>
</ul>
<form id="tableform" action="<{:U('app',array('appid'=>$app['id'],'istest'=>$istest))}>" ajaxform="true" ajaxtype="ajaxsubmit" method="post">
	<input type="hidden" name="dosubmit" value="true" />
	<table id="apptable" class="table table-hover">
		<thead>
			<tr>
			<th><label><input type="checkbox" onclick="checkAll(this)" />&nbsp;#</label></th>
			<th>文件/目录</th>
			<th>权限</th>
			<th>owner/group</th>
			<th>size</th>
			<th>创建/修改时间</th>
			<th style="width:188px">操作</th>
			</tr>
		</thead>
		<tbody>
	<foreach name="dirlist" key="key" item="value">
	<php>$isdir = substr($value['drwx'],0,1)=='d'?1:0;</php>
	<php>$file = urlencode((empty($dir)?'':$dir.'/').$value['filename']);</php>
			<tr>
				<th scope="row"><label><input type="checkbox" checker="true" isdir="<if condition="$isdir==1">true<else/>false</if>" name="dir[]" value="<{$file}>"<if condition="$value['filename']=='..'"> disabled="disabled"</if> />&nbsp;<{$key+1}></label></th>
				<td>
				<if condition="$isdir==1">
					<a href="<{:U('app',array('appid'=>$app['id'],'istest'=>$istest,'dir'=>$file))}>"><{$value['filename']}><if condition="$value['filename']!='..'&&$value['filename']!='.'">/<else/>&nbsp;&nbsp;</if></a>
				<else/>
					<{$value['filename']}>
				</if>
				</td>
				<td><{$value['drwx']}></td>
				<td><{$value['owner']}>/<{$value['group']}></td>
				<td><{:formatsize($value['size'])}></td>
				<td><{$value['lasttime']}></td>
				<td>
				<if condition="$value['filename']=='..'">
					<span class="gray">--</span>
				<else/>
					<a class="btn btn-default" href="javascript:;" role="button" id="export_<{$key}>" onclick="svn_export('<{$file}>',<{$key}>,<{$isdir}>)" data-loading-text="导出中..">导出</a>&nbsp;
					<if condition="(!$istest && !empty($app['rsync_shell'])) || ($istest && !empty($app['rsync_shell_test']))">
					<a class="btn btn-default" href="javascript:;" role="button" id="rsync_<{$key}>" onclick="app_rsync('<{$file}>',<{$key}>,<{$isdir}>)" data-loading-text="发布中..">发布</a>&nbsp;
					</if>
				</if>
				</td>
			</tr>
	</foreach>
		</tbody>
	</table>
	<if condition="empty($dirlist)"><div class="noer">暂无数据..</div></if>
	<button class="btn btn-default" type="submit" id="submitbtn" autocomplete="off" style="display:none">提交</button>
	<div class="toolpanel">
		<button submitbtn="true" submittype="svnexport" class="btn btn-default" type="button" data-loading-text="导出中.." autocomplete="off">导出已选项</button>
		<if condition="(!$istest && !empty($app['rsync_shell'])) || ($istest && !empty($app['rsync_shell_test']))">
		<button submitbtn="true" submittype="apprsync" class="btn btn-default" type="button" data-loading-text="发布中.." autocomplete="off">发布已选项</button>
		</if>
	</div>
</form>
</div><!--//.main-cont-->
<div id="progress-info" class="progress-info">
<div class="progress-header cl">
	progress info:&nbsp;<button class="btn btn-default" style="padding:0 4px" type="button" autocomplete="off" onclick="progress_info_hide()">点此收起或按下Esc键快速收起↑</button>
	<span class="glyphicon glyphicon-menu-up" aria-hidden="true" title="点击收起或按下Esc键收起" onclick="progress_info_hide()"></span>
</div>
<div class="progress-body"></div>
</div>
<style type="text/css">
.toolpanel{padding:5px 0;background-color:#eee;}
.noer{padding:5px 0}
.table th label{vertical-align:middle}
.progress-header {padding-right:10px;font-size:18px}
.progress-header>.glyphicon{padding:0 0;float:right;border:1px solid #999}
.progress-header>.glyphicon-menu-up{font-size:24px;cursor:pointer}
.progress-info{
	width: 100%;
	height: 100%;
	display: none;
	background-color: #fff;
	/*position: fixed;*/
	position: absolute;
	_position: absolute;
	left: 0;
	top: 0;
}
</style>
<script type="text/javascript">
var export_multi_confirm = <if condition="($istest&&$app['export_multi_confirm_test'])||(!$istest&&$app['export_multi_confirm'])">true<else/>false</if>;
var export_single_confirm = <if condition="($istest&&$app['export_single_confirm_test'])||(!$istest&&$app['export_single_confirm'])">true<else/>false</if>;
var rsync_single_confirm = <if condition="($istest&&$app['rsync_single_confirm_test'])||(!$istest&&$app['rsync_single_confirm'])">true<else/>false</if>;
var rsync_multi_confirm = <if condition="($istest&&$app['rsync_multi_confirm_test'])||(!$istest&&$app['rsync_multi_confirm'])">true<else/>false</if>;
</script>
<script type="text/javascript">
function svn_export(file, key, isdir) {
	var isdir = typeof isdir == 'undefined' ? false : (isdir ? true : false);
	var ajaxpost = function(){
		$('#export_'+key).button('loading');
		ajaxprocess({
			type: 'post',
			url: '<{:U('export/svnexport',array('appid'=>$app['id'],'istest'=>$istest))}>',
			dataType: 'json',
			data:'dir='+file+'&dosubmit=true',
			success: function(resp) {
				showAlert(resp.info,function(){
					setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide()})},150);
				});
				progress_info_show('<pre>'+(resp.data?resp.data:'')+'</pre>');
			},
			complete: function(){
				$('#export_'+key).button('reset');
			}
		});
	}
	if(export_single_confirm || (isdir && export_multi_confirm)) {
		showConfirm('确定要导出该'+(isdir?'目录':'文件')+'吗？', function(){
			ajaxpost();
		});
	} else {
		ajaxpost();
	}
}

function app_rsync(file, key, isdir) {
	var isdir = typeof isdir == 'undefined' ? false : (isdir ? true : false);
	var ajaxpost = function(){
		$('#rsync_'+key).button('loading');
		ajaxprocess({
			type: 'post',
			url: '<{:U('rsync/apprsync',array('appid'=>$app['id'],'istest'=>$istest))}>',
			dataType: 'json',
			data:'dir='+file+'&dosubmit=true',
			success: function(resp) {
				showAlert(resp.info,function(){
					setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide()})},150);
				});
				progress_info_show('<pre>'+(resp.data?resp.data:'')+'</pre>');
			},
			complete: function(){
				$('#rsync_'+key).button('reset');
			}
		});
	}
	if(rsync_single_confirm || (isdir && rsync_multi_confirm)) {
		showConfirm('确定要发布该'+(isdir?'目录':'文件')+'吗？', function(){
			ajaxpost();
		});
	} else {
		ajaxpost();
	}
}
function progress_info_show(info) {
	$('#progress-info .progress-body').html(info).parent().show('fast');
}
function progress_info_hide() {
	$(window).off('keydown');
	$('#progress-info .progress-body').html('').parent().hide('fast');
	setTimeout(function(){window.location.reload()},150);
}

function ajaxform_confirm() {
	var title = '', btn_selector = '', op = '';
	if(strpos($('form#tableform').attr('action'), 'svnexport') !== false) {
		title = '确定要导出选中的项吗？';
		op = 'svnexport';
		btn_selector = 'form#tableform button[submitbtn="true"][submittype="svnexport"]';
	} else if(strpos($('form#tableform').attr('action'), 'apprsync') !== false) {
		title = '确定要发布选中的项吗？';
		op = 'apprsync';
		btn_selector = 'form#tableform button[submitbtn="true"][submittype="apprsync"]';
	} else {
		title = '确定要导出或发布选中的项吗？';
	}
	return {
		title:title,
		noConfirm: function(){
			if(op == 'svnexport' || op == 'apprsync') {
				if($('input[type="checkbox"][name="dir[]"]:checked').length==1 
					&& $('input[type="checkbox"][name="dir[]"]:checked').attr('isdir')=='false'
					&& (!export_single_confirm || !rsync_single_confirm)) {
					return true;
				}
				if(!export_multi_confirm || !rsync_multi_confirm) {
					return true;
				}
			}
			return false;
		},
		beforeSubmit: function() {
			$(btn_selector).button('loading');
		},
		success: function(resp) {
			showAlert(resp.info, function(){
				setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide()})},150);
			});
			progress_info_show('<pre>'+(resp.data?resp.data:'')+'</pre>');
		},
		complete: function() {
			$(btn_selector).button('reset');
		}
	};
}
function ajaxform_check() {
	if($('input[type="checkbox"][name="dir[]"]:checked').length < 1) {
		if(strpos($('form#tableform').attr('action'), 'svnexport') !== false) {
			showAlert('没有指定要导出的文件或目录');
		} else if(strpos($('form#tableform').attr('action'), 'apprsync') !== false) {
			showAlert('没有指定要发布的文件或目录');
		} else {
			showAlert('没有指定要导出或发布的文件或目录');
		}
		return false;
	}
	return true;
}
$(function(){
	$('form#tableform button[submitbtn="true"]').click(function(){
		if($(this).attr('submittype') == 'svnexport') {
			$('form#tableform').attr('action', '<{:U('export/svnexport',array('appid'=>$app['id'],'istest'=>$istest))}>');
		} else if($(this).attr('submittype') == 'apprsync') {
			$('form#tableform').attr('action', '<{:U('rsync/apprsync',array('appid'=>$app['id'],'istest'=>$istest))}>');
		}
		$('#submitbtn').trigger('click');
	});
});
</script>
<include file="Public/footer" />