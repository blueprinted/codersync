<include file="Public/header" />
<style type="text/css">
.nav-wrap {position: relative}
.nav-sidebar {
height: 100%;
padding-right: 10px;
position: absolute;
right: 0;
top: 0;
}
.nav-sidebar>a {
	display: inline-block;
	padding: 8px 0;
}
.path-clipboard {
	padding: 8px 0;
	float: left;
	font-size: 14px;
	line-height: 24px;
}
.path-clipboard > span {
	text-decoration: none;
	color: #34393C;
	outline: none;
}
.path-clipboard > span.btn-clipboard-hover {
	color: green;
}
.btn-clipboard {padding: 0 2px;cursor: pointer}
#navigator {padding-right: 2px}
.deploy-flow,  .deploy-flow:hover{color:red}
</style>
<div class="main-cont">
<div class="toolpanel cl">
	<ul id="navigator" class="breadcrumb">
		<li><a href="<{:U('index',array('group'=>$group))}>">首页</a></li>
		<li class="active">[项目]<{$app.name}>[<span class="btn-clipboard btn-clipboard-link" enabled="true" link="<{$app_root}>/<{$dir}>">路径</span>:<a href="<{:U('app',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>" title="<{$app_root}>">app_root/</a><foreach name="dirs" key="key" item="value"><a href="<{:U('app',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest,'dir'=>dir_convert($value)))}>"><{:substr($key,strpos($key,'_')+1)}></a><if condition="$dir != $value">/</if></foreach>]</li>
	</ul>
	<!--<div class="path-clipboard"><span class="btn-clipboard btn-clipboard-link" enabled="true" link="<{$app_root}>/<{$dir}>">复制路径</span></div>-->
	<div class="btnsets">
		<if condition="$app['repository']=='svn'">
		<button submitbtn="true" submittype="svnupdate" for="tableform" class="btn btn-default" type="button" data-loading-text="更新中.." autocomplete="off">更新已选项</button>
		</if>
		<if condition="$app['repository']=='git'">
	  <button submitbtn="true" submittype="gitupdate" for="tableform" class="btn btn-default" type="button" data-loading-text="更新中.." autocomplete="off">更新已选项</button>
		<button submitbtn="true" submittype="gitpull" for="tableform" class="btn btn-default" type="button" data-loading-text="git pulling.." autocomplete="off">git pull项目</button>
		</if>
		<if condition="(!$istest && !empty($app['rsync_shell'])) || ($istest && !empty($app['rsync_shell_test']))">
		<button submitbtn="true" submittype="apprsync" for="tableform" class="btn btn-default" type="button" data-loading-text="发布中.." autocomplete="off">发布已选项</button>
		</if>
		<if condition="(!$istest && !empty($app['restart_server'])) || ($istest && !empty($app['restart_server_test']))">
		<button class="btn btn-default btn-restart" type="button" data-loading-text="重启中.." autocomplete="off" onclick="app_restart()">重启服务[<if condition="$istest">测试<else/>正式</if>]</button>
		</if>
		<if condition="!$istest&&$app['sogou_sac']">
		<button submitbtn="false" submittype="appdeploy" for="tableform" class="btn btn-default" type="button" data-loading-text="发布中.." autocomplete="off">SAC发布</button>
		</if>
		<if condition="$app['repository']=='svn'&&$svn_checkout!=true">
		<button submitbtn="true" submittype="svncheckout" for="tableform" class="btn btn-default" type="button" data-loading-text="检出中.." autocomplete="off">检出代码</button>
		</if>
		<if condition="$app['repository']=='git'&&$git_checkout!=true">
		<button submitbtn="true" submittype="gitclone" for="tableform" class="btn btn-default" type="button" data-loading-text="git cloning.." autocomplete="off">git clone</button>
		</if>
	</div>
	<div class="versionbox">
	<form id="verform" action="<{:U('svn/update',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>" ajaxform="true" ajaxtype="ajaxsubmit" method="post">
		<div class="input-group">
			<input type="hidden" name="dosubmit" value="true" />
			<input type="hidden" name="istest" value="<{$istest}>" />
			<input type="hidden" name="dir" value="<{:dir_convert(empty($dir)?'.':$dir)}>" />
			<if condition="$app['repository']=='svn'">
			<input type="text" name="ver" class="form-control" placeholder="按版本号更新.." />
			<span class="input-group-btn"><button class="btn btn-default" type="submit" submitbtn="true" for="verform" data-loading-text="更新中..">更新</button></span>
			</if>
		</div><!--//.input-group-->
	</form>
	</div><!--//.versionbox-->
</div>
<div class="nav-wrap cl">
	<ul class="nav nav-tabs">
	<if condition="!empty($app['root_dir'])">
	  <li role="presentation"<if condition="$istest==0"> class="active"</if>><a href="<{:U('app',str_replace('istest=1','istest=0',$url_params))}>">正式环境</a></li>
	</if>
	<if condition="!empty($app['root_dir_test'])">
	  <li role="presentation"<if condition="$istest==1"> class="active"</if>><a href="<{:U('app',str_replace('istest=0','istest=1',$url_params))}>">测试环境</a></li>
	</if>
	</ul>
	<if condition="$app['is_sogou_docker']">
	<div class="nav-sidebar">
		<a class="deploy-flow" href="<{:U('deploy/packlist',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>">镜像列表&gt;&gt;</a>
		<a class="deploy-flow" href="<{:U('deploy/index',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>">上线流程&gt;&gt;</a>
	</div>
	</if>
</div>
<form id="tableform" action="<{:U('app',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>" ajaxform="true" ajaxtype="ajaxsubmit" method="post">
	<input type="hidden" name="dosubmit" value="true" />
	<table id="apptable" class="table table-hover">
		<thead>
			<tr>
			<th><label><input type="checkbox" checkall="true" onclick="checkAll(this)" />&nbsp;#</label></th>
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
	<php>$file = dir_convert((empty($dir)?'':$dir.'/')).$value['filename'];</php>
			<tr>
				<th scope="row"><label><input type="checkbox" checker="true" isdir="<if condition="$isdir==1">true<else/>false</if>" name="dir[]" value="<{$file}>"<if condition="$value['filename']=='..' OR $value['filename']=='.'"> disabled="disabled"</if> />&nbsp;<{$key+1}></label></th>
				<td>
				<if condition="$isdir==1">
					<a href="<{:U('app',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest,'dir'=>$file))}>"><{$value['filename']}><if condition="$value['filename']!='..'&&$value['filename']!='.'">/<else/>&nbsp;&nbsp;</if></a>
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
				  <if condition="$app['repository']=='svn'">
					<a class="btn btn-default" href="javascript:;" role="button" id="update_<{$key}>" onclick="svn_update('<{$file}>',<{$key}>,<{$isdir}>)" data-loading-text="更新中..">更新</a>&nbsp;
					</if>
					<if condition="$app['repository']=='git'">
					<a class="btn btn-default" href="javascript:;" role="button" id="update_<{$key}>" onclick="git_update('<{$file}>',<{$key}>,<{$isdir}>)" data-loading-text="更新中..">更新</a>&nbsp;
					</if>
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
	<div class="toolpanel cl">
		<div class="input-checker"><label><input type="checkbox" checkall="true" onclick="checkAll(this)">&nbsp;#</label></div>
		<div class="btnsets">
			<if condition="$app['repository']=='svn'">
			<button submitbtn="true" submittype="svnupdate" for="tableform" class="btn btn-default" type="button" data-loading-text="更新中.." autocomplete="off">更新已选项</button>
		  </if>
			<if condition="$app['repository']=='git'">
			<button submitbtn="true" submittype="gitupdate" for="tableform" class="btn btn-default" type="button" data-loading-text="更新中.." autocomplete="off">更新已选项</button>
			<button submitbtn="true" submittype="gitpull" for="tableform" class="btn btn-default" type="button" data-loading-text="git pulling.." autocomplete="off">git pull项目</button>
			</if>
			<if condition="(!$istest && !empty($app['rsync_shell'])) || ($istest && !empty($app['rsync_shell_test']))">
			<button submitbtn="true" submittype="apprsync" for="tableform" class="btn btn-default" type="button" data-loading-text="发布中.." autocomplete="off">发布已选项</button>
			</if>
			<if condition="(!$istest && !empty($app['restart_server'])) || ($istest && !empty($app['restart_server_test']))">
			<button class="btn btn-default btn-restart" type="button" data-loading-text="重启中.." autocomplete="off" onclick="app_restart()">重启服务[<if condition="$istest">测试<else/>正式</if>]</button>
			</if>
			<if condition="!$istest&&$app['sogou_sac']">
			<button submitbtn="false" submittype="appdeploy" for="tableform" class="btn btn-default" type="button" data-loading-text="发布中.." autocomplete="off">SAC发布</button>
			</if>
			<if condition="$app['repository']=='svn'&&$svn_checkout!=true">
			<button submitbtn="true" submittype="svncheckout" for="tableform" class="btn btn-default" type="button" data-loading-text="检出中.." autocomplete="off">检出代码</button>
			</if>
			<if condition="$app['repository']=='git'&&$git_checkout!=true">
			<button submitbtn="true" submittype="gitclone" for="tableform" class="btn btn-default" type="button" data-loading-text="git cloning.." autocomplete="off">git clone</button>
			</if>
		</div>
	</div>
</form>
</div><!--//.main-cont-->
<script type="text/javascript">
var update_multi_confirm = <if condition="($istest&&$app['update_multi_confirm_test'])||(!$istest&&$app['update_multi_confirm'])">true<else/>false</if>;
var update_single_confirm = <if condition="($istest&&$app['update_single_confirm_test'])||(!$istest&&$app['update_single_confirm'])">true<else/>false</if>;
var update_vn_confirm = <if condition="($istest&&$app['update_vn_confirm_test'])||(!$istest&&$app['update_vn_confirm'])">true<else/>false</if>;
var rsync_single_confirm = <if condition="($istest&&$app['rsync_single_confirm_test'])||(!$istest&&$app['rsync_single_confirm'])">true<else/>false</if>;
var rsync_multi_confirm = <if condition="($istest&&$app['rsync_multi_confirm_test'])||(!$istest&&$app['rsync_multi_confirm'])">true<else/>false</if>;
var sogou_sac = <if condition="!$istest&&$app['sogou_sac']">true<else/>false</if>;
var need_restart = <if condition="(!$istest&&$app['restart_server'])||($istest&&$app['restart_server_test'])">true<else/>false</if>;
</script>
<script type="text/javascript">
function svn_update(file, key, isdir) {
	var isdir = typeof isdir == 'undefined' ? false : (isdir ? true : false);
	var ajaxpost = function(){
		$('#update_'+key).button('loading');
		showTip('代码更新中，请稍等..');
		ajaxprocess({
			type: 'post',
			url: '<{:U('svn/update',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>',
			dataType: 'json',
			data:'dir='+file+'&dosubmit=true',
			success: function(resp) {
				hideTip();
				if(resp.code == 'need_login') {
					showConfirm('请先登录', function(){
						login('', '<{:U('Admin/Public/login')}>');
					});
					return;
				}
				showAlert(resp.info,function(){
					setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide()})},150);
				});
				$('.btn-prosess-hide').attr('done', true).unbind('click').bind('click', function(){
					progress_info_hide();
				});
				progress_info_show('<pre>'+(resp.data?resp.data:'')+'</pre>');
			},
			complete: function(){
				if(!$('.btn-prosess-hide').attr('done')) {
					$('.btn-prosess-hide').unbind('click').bind('click', function(){
						progress_info_hide();
					});
				} else {
					$('.btn-prosess-hide').removeAttr('done');
				}
				$('#update_'+key).button('reset');
				hideTip();
			}
		});
	}
	if(update_single_confirm || (isdir && update_multi_confirm)) {
		showConfirm('确定要更新该'+(isdir?'目录':'文件')+'吗？', function(){
			ajaxpost();
		});
	} else {
		ajaxpost();
	}
}

function git_update(file, key, isdir) {
	var isdir = typeof isdir == 'undefined' ? false : (isdir ? true : false);
	var ajaxpost = function(){
		$('#updte_'+key).button('loading');
		showTip('代码更新中，请稍等..');
		ajaxprocess({
			type: 'post',
			url: '<{:U('git/update',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>',
			dataType: 'json',
			data:'dir='+file+'&dosubmit=true',
			success: function(resp) {
				hideTip();
				if(resp.code == 'need_login') {
					showConfirm('请先登录', function(){
						login('', '<{:U('Admin/Public/login')}>');
					});
					return;
				}
				showAlert(resp.info,function(){
					setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide()})},150);
				});
				$('.btn-prosess-hide').attr('done', true).unbind('click').bind('click', function(){
					progress_info_hide();
				});
				progress_info_show('<pre>'+(resp.data?resp.data:'')+'</pre>');
			},
			complete: function(){
				if(!$('.btn-prosess-hide').attr('done')) {
					$('.btn-prosess-hide').unbind('click').bind('click', function(){
						progress_info_hide();
					});
				} else {
					$('.btn-prosess-hide').removeAttr('done');
				}
				$('#updte_'+key).button('reset');
				hideTip();
			}
		});
	}
	if(update_single_confirm || (isdir && update_multi_confirm)) {
		showConfirm('确定要更新该'+(isdir?'目录':'文件')+'吗？', function(){
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
		showTip('代码发布中，请稍等..');
		ajaxprocess({
			type: 'post',
			url: '<{:U('rsync/apprsync',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>',
			dataType: 'json',
			data:'dir='+file+'&dosubmit=true',
			success: function(resp) {
				hideTip();
				if(resp.code == 'need_login') {
					showConfirm('请先登录', function(){
						login('', '<{:U('Admin/Public/login')}>');
					});
					return;
				}
				showAlert(resp.info,function(){
					if(resp.status > 0 && (sogou_sac || need_restart)) {
						$('.btn-prosess-hide').attr('done', true).unbind('click').bind('click', function(){
							progress_info_hide(false, function(){
								if (sogou_sac) {
									showConfirm('SAC项目还需要执行DEPLOY才能上线成功，要执行SAC DEPLOY吗？',function(){
										show_sacform();
									});
								} else {
									showConfirm('该项目还需要执行重启服务操作才能使上线的代码生效，要执行重启服务吗？',function(){
										app_restart(false);
									});
								}
							});
						});
						setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide(false,function(){
							if (sogou_sac) {
								showConfirm('SAC项目还需要执行DEPLOY才能上线成功，要执行SAC DEPLOY吗？',function(){
									show_sacform();
								});
							} else {
								showConfirm('该项目还需要执行重启服务操作才能使上线的代码生效，要执行重启服务吗？',function(){
									app_restart(false);
								});
							}
						})})},150);
					} else {
						$('.btn-prosess-hide').attr('done', true).unbind('click').bind('click', function(){
							progress_info_hide();
						});
						setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide()})},150);
					}
				});
				progress_info_show('<pre>'+(resp.data?resp.data:'')+'</pre>');
			},
			complete: function(){
				if(!$('.btn-prosess-hide').attr('done')) {
					$('.btn-prosess-hide').unbind('click').bind('click', function(){
						progress_info_hide();
					});
				} else {
					$('.btn-prosess-hide').removeAttr('done');
				}
				$('#rsync_'+key).button('reset');
				hideTip();
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
function app_restart(need_confirm) {
	var need_confirm = typeof need_confirm == 'undefined' ? true : (need_confirm ? true : false);
	var ajaxpost = function(){
		$('.btn-restart').button('loading');
		showTip('[<if condition="$istest">测试<else/>正式</if>]服务重启中，请稍等..');
		ajaxprocess({
			type: 'post',
			url: '<{:U('rsync/apprestart',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>',
			dataType: 'json',
			data:'dosubmit=true',
			success: function(resp) {
				hideTip();
				if(resp.code == 'need_login') {
					showConfirm('请先登录', function(){
						login('', '<{:U('Admin/Public/login')}>');
					});
					return;
				}
				showAlert(resp.info,function(){
					setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide()})},150);
				});
				$('.btn-prosess-hide').attr('done', true).unbind('click').bind('click', function(){
					progress_info_hide();
				});
				progress_info_show('<pre>'+(resp.data?resp.data:'')+'</pre>');
			},
			complete: function(){
				if(!$('.btn-prosess-hide').attr('done')) {
					$('.btn-prosess-hide').unbind('click').bind('click', function(){
						progress_info_hide();
					});
				} else {
					$('.btn-prosess-hide').removeAttr('done');
				}
				$('.btn-restart').button('reset');
				hideTip();
			}
		});
	}
	if(need_confirm) {
		showConfirm('确定要重启该服务[<if condition="$istest">测试<else/>正式</if>]吗？', function(){
			ajaxpost();
		});
	} else {
		ajaxpost();
	}
}

function ajaxform_confirm_tableform() {
	var title = '', btn_selector = '', op = '';
	if(strpos($('form#tableform').attr('action'), 'svn/update') !== false) {
		title = '确定要更新选中的项吗？';
		op = 'svnupdate';
		btn_selector = 'button[submitbtn="true"][for="tableform"][submittype="svnupdate"]';
	} else if(strpos($('form#tableform').attr('action'), 'git/update') !== false) {
		title = '确定要更新选中的项吗？';
		op = 'gitupdate';
		btn_selector = 'button[submitbtn="true"][for="tableform"][submittype="gitupdate"]';
	} else if(strpos($('form#tableform').attr('action'), 'git/pull') !== false) {
		title = '确定要git pull整个项目吗？';
		op = 'gitpull';
		btn_selector = 'button[submitbtn="true"][for="tableform"][submittype="gitpull"]';
	} else if(strpos($('form#tableform').attr('action'), 'apprsync') !== false) {
		title = '确定要发布选中的项吗？';
		op = 'apprsync';
		btn_selector = 'button[submitbtn="true"][for="tableform"][submittype="apprsync"]';
	} else if(strpos($('form#tableform').attr('action'), 'svn/checkout') !== false) {
		title = '确定要清空项目根目录[<{$app_root}>/]并检出代码吗？';
		op = 'svncheckout';
		btn_selector = 'button[submitbtn="true"][for="tableform"][submittype="svncheckout"]';
	} else if(strpos($('form#tableform').attr('action'), 'git/clone') !== false) {
		title = '确定要删除项目根目录[<{$app_root}>/]并git clone代码吗？';
		op = 'gitclone';
		btn_selector = 'button[submitbtn="true"][for="tableform"][submittype="gitclone"]';
	} else {
		title = '确定要更新或发布选中的项吗？';
	}
	return {
		title:title,
		noConfirm: function(){
			if(op == 'svnupdate' || op == 'gitupdate' || op == 'apprsync') {
				if($('input[type="checkbox"][name="dir[]"]:checked').length==1
					&& $('input[type="checkbox"][name="dir[]"]:checked').attr('isdir')=='false'
					&& (!update_single_confirm || !rsync_single_confirm)) {
					return true;
				}
				if(!update_multi_confirm || !rsync_multi_confirm) {
					return true;
				}
			}
			return false;
		},
		beforeSubmit: function() {
			if(strpos($('form#tableform').attr('action'), 'svn/update') !== false) {
				showTip('代码更新中，请稍等..');
			} else if(strpos($('form#tableform').attr('action'), 'git/update') !== false) {
				showTip('代码更新中，请稍等..');
			} else if(strpos($('form#tableform').attr('action'), 'git/pull') !== false) {
				showTip('项目git pull中，请稍等..');
			} else if(strpos($('form#tableform').attr('action'), 'apprsync') !== false) {
				showTip('代码发布中，请稍等..');
			} else if(strpos($('form#tableform').attr('action'), 'svn/checkout') !== false) {
				showTip('代码检出中，请稍等..');
			} else if(strpos($('form#tableform').attr('action'), 'git/clone') !== false) {
				showTip('代码git clone中，请稍等..');
			} else {
				showTip('代码更新或发布中，请稍等..');
			}
			$(btn_selector).button('loading');
		},
		success: function(resp) {
			hideTip();
			if(resp.code == 'need_login') {
				showConfirm('请先登录', function(){
					login('', '<{:U('Admin/Public/login')}>');
				});
				return;
			}
			showAlert(resp.info, function(){
				if(resp.status > 0 && sogou_sac && op == 'apprsync') {
					$('.btn-prosess-hide').attr('done', true).unbind('click').bind('click', function(){
						progress_info_hide(false, function(){
							showConfirm('SAC项目还需要执行DEPLOY才能上线成功，要执行SAC DEPLOY吗？',function(){
								show_sacform();
							});
						});
					});
					setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide(false,function(){
						showConfirm('SAC项目还需要执行DEPLOY才能上线成功，要执行SAC DEPLOY吗？',function(){
							show_sacform();
						});
					})})},150);
				} else {
					$('.btn-prosess-hide').attr('done', true).unbind('click').bind('click', function(){
						progress_info_hide();
					});
					setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide()})},150);
				}
			});
			progress_info_show('<pre>'+(resp.data?resp.data:'')+'</pre>');
		},
		complete: function() {
			if(!$('.btn-prosess-hide').attr('done')) {
				$('.btn-prosess-hide').unbind('click').bind('click', function(){
					progress_info_hide();
				});
			} else {
				$('.btn-prosess-hide').removeAttr('done');
			}
			$(btn_selector).button('reset');
			hideTip();
		}
	};
}
function ajaxform_check_tableform() {
	if($('input[type="checkbox"][name="dir[]"]:checked').length < 1) {
		if(strpos($('form#tableform').attr('action'), 'svn/update') !== false) {
			showAlert('没有指定要更新的文件或目录');
		} else if(strpos($('form#tableform').attr('action'), 'git/update') !== false) {
			showAlert('没有指定要更新的文件或目录');
		} else if(strpos($('form#tableform').attr('action'), 'git/pull') !== false) {
			return true;
		} else if(strpos($('form#tableform').attr('action'), 'apprsync') !== false) {
			showAlert('没有指定要发布的文件或目录');
		} else if(strpos($('form#tableform').attr('action'), 'svn/checkout') !== false) {
			return true;
		} else if(strpos($('form#tableform').attr('action'), 'git/clone') !== false) {
			return true;
		} else {
			showAlert('没有指定要更新或发布的文件或目录');
		}
		return false;
	}
	return true;
}

function ajaxform_confirm_verform() {
	var btn_selector = $('button[submitbtn="true"][for="verform"]');
	return {
		title:'确定要按版本号进行更新吗？',
		noConfirm: function(){
			if(!update_vn_confirm) {
				return true;
			}
			return false;
		},
		beforeSubmit: function() {
			showTip('代码安版本号更新中，请稍等..');
			$(btn_selector).button('loading');
		},
		success: function(resp) {
			hideTip();
			if(resp.code == 'need_login') {
				showConfirm('请先登录', function(){
					login('', '<{:U('Admin/Public/login')}>');
				});
				return;
			}
			showAlert(resp.info, function(){
				setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide()})},150);
			});
			$('.btn-prosess-hide').attr('done', true).unbind('click').bind('click', function(){
				progress_info_hide();
			});
			progress_info_show('<pre>'+(resp.data?resp.data:'')+'</pre>');
		},
		complete: function() {
			if(!$('.btn-prosess-hide').attr('done')) {
				$('.btn-prosess-hide').unbind('click').bind('click', function(){
					progress_info_hide();
				});
			} else {
				$('.btn-prosess-hide').removeAttr('done');
			}
			$(btn_selector).button('reset');
			hideTip();
		}
	};
}
function ajaxform_check_verform() {
	var ver = $.trim($('form#verform input[type="text"][name="ver"]').val());
	if(!/^\d+$/.test(ver)) {
		showAlert('没有填写版本号或版本号不正确');
		return false;
	}
	return true;
}

function ajax_svncheckout() {
	showTip('代码检出中，请稍等..');
	ajaxprocess({
		type: 'post',
		url: '<{:U('svn/checkout',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>',
		dataType: 'json',
		data:'dosubmit=true',
		success: function(resp) {
			hideTip();
			if(resp.code == 'need_login') {
				showConfirm('请先登录', function(){
					login('', '<{:U('Admin/Public/login')}>');
				});
				return;
			}
			showAlert(resp.info,function(){
				setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide()})},150);
			});
			$('.btn-prosess-hide').attr('done', true).unbind('click').bind('click', function(){
				progress_info_hide();
			});
			progress_info_show('<pre>'+(resp.data?resp.data:'')+'</pre>');
		},
		complete: function(){
			if(!$('.btn-prosess-hide').attr('done')) {
				$('.btn-prosess-hide').unbind('click').bind('click', function(){
					progress_info_hide();
				});
			} else {
				$('.btn-prosess-hide').removeAttr('done');
			}
			hideTip();
		}
	});
}

function ajax_gitclone() {
	showTip('git clone，请稍等..');
	ajaxprocess({
		type: 'post',
		url: '<{:U('git/clone',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>',
		dataType: 'json',
		data:'dosubmit=true',
		success: function(resp) {
			hideTip();
			if(resp.code == 'need_login') {
				showConfirm('请先登录', function(){
					login('', '<{:U('Admin/Public/login')}>');
				});
				return;
			}
			showAlert(resp.info,function(){
				setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide()})},150);
			});
			$('.btn-prosess-hide').attr('done', true).unbind('click').bind('click', function(){
				progress_info_hide();
			});
			progress_info_show('<pre>'+(resp.data?resp.data:'')+'</pre>');
		},
		complete: function(){
			if(!$('.btn-prosess-hide').attr('done')) {
				$('.btn-prosess-hide').unbind('click').bind('click', function(){
					progress_info_hide();
				});
			} else {
				$('.btn-prosess-hide').removeAttr('done');
			}
			hideTip();
		}
	});
}
</script>
<style type="text/css">
.sac-infos-div {padding:5px 0}
.sac-input {padding:2px 4px;border: 1px solid #ccc}
</style>
<script type="text/javascript">
/*sac验证弹框*/
var deployDialog;
function show_sacform() {
	deployDialog = dialog({
		title: '请输入SAC验证信息',
		content: '<div class="sac-infos-div">账&nbsp;&nbsp;&nbsp;号：<input type="text" id="sac-username" class="sac-input" name="username" value="" autocomplete="off" /></div><div class="sac-infos-div">密&nbsp;&nbsp;&nbsp;码：<input type="password" id="sac-password" class="sac-input" name="password" value="" autocomplete="off" /></div>',
		ok: function(){
			if($.trim($('#sac-username').val()) === '' || $.trim($('#sac-password').val()) === '') {
				if(this.shake) {
					this.shake();
				} else {
					showAlert('没有填写账号或密码');
				}
				return false;
			}
			$('button[submittype="appdeploy"][for="tableform"]').button('loading');
			showTip('SAC DEPLOY中，请稍等..');
			ajaxprocess({
				type: 'post',
				url: '<{:U('rsync/appdeploy',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>',
				dataType: 'json',
				data:'username='+$('#sac-username').val()+'&password='+$('#sac-password').val()+'&dosubmit=true',
				success: function(resp) {
					if(resp.code == 'need_login') {
						showConfirm('请先登录', function(){
							login('', '<{:U('Admin/Public/login')}>');
						});
						return;
					}
					showAlert(resp.info,function(){
						setTimeout(function(){$(window).keydown(function(e){e.which==27&&progress_info_hide()})},150);
					});
					$('.btn-prosess-hide').attr('done', true).unbind('click').bind('click', function(){
						progress_info_hide();
					});
					progress_info_show('<pre>'+(resp.data?resp.data:'')+'</pre>');
				},
				complete: function(){
					if(!$('.btn-prosess-hide').attr('done')) {
						$('.btn-prosess-hide').unbind('click').bind('click', function(){
							progress_info_hide();
						});
					} else {
						$('.btn-prosess-hide').removeAttr('done');
					}
					deployDialog.close();
					hideTip();
					$('button[submittype="appdeploy"][for="tableform"]').button('reset');
				}
			});
		},
		cancel: function(){},
		okValue: '确认',
		cancelValue: '取消',
		fixed: true,
		width: 248,
		height: 72
	});
	deployDialog.showModal();
}
$(function(){
	$('button[submitbtn="true"][for="tableform"]').click(function(){
		if($(this).attr('submittype') == 'svnupdate') {
			$('form#tableform').attr('action', '<{:U('svn/update',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>');
		} else if($(this).attr('submittype') == 'gitupdate') {
			$('form#tableform').attr('action', '<{:U('git/update',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>');
		} else if($(this).attr('submittype') == 'gitpull') {
			$('form#tableform').attr('action', '<{:U('git/pull',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>');
		} else if($(this).attr('submittype') == 'apprsync') {
			$('form#tableform').attr('action', '<{:U('rsync/apprsync',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>');
		} else if($(this).attr('submittype') == 'apprestart') {
			$('form#tableform').attr('action', '<{:U('rsync/apprestart',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>');
		} else if($(this).attr('submittype') == 'svncheckout') {
			$('form#tableform').attr('action', '<{:U('svn/checkout',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>');
		} else if($(this).attr('submittype') == 'gitclone') {
			$('form#tableform').attr('action', '<{:U('git/clone',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>');
		}
		$('#submitbtn').trigger('click');
	});
	$('button[submittype="appdeploy"][for="tableform"]').click(function(){
		show_sacform();
	});
<if condition="$app['repository']=='svn'&&$svn_checkout!=true">
	showConfirm('检测到项目根目录[<{$app_root}>/]还不是svn的检出目录，是否清空该目录并检出svn的代码？', function(){
		ajax_svncheckout();
	});
</if>
<if condition="$app['repository']=='git'&&$git_checkout!=true">
	showConfirm('检测到项目根目录[<{$app_root}>/]还不是git的关联目录，是否删除该目录重新创建并关联git仓库？', function(){
		ajax_gitclone();
	});
</if>
});
</script>
<include file="Public/footer" />
