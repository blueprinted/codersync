<include file="Public/header" />
<style type="text/css">
.back-app-page{
	display: inline-block;
  padding: 6px 12px;
  margin: 0;
  font-size: 14px;
  cursor: pointer;
}
.bootstrap-select {width:112px!important}
.app-infos-div {padding:5px 0}
.app-input {padding:2px 4px;border: 1px solid #ccc}
</style>
<div class="main-cont">
<div class="toolpanel top cl">
	<ul id="navigator" class="breadcrumb">
		<li><a href="<{:U('index/index',array('group'=>$group))}>">首页</a></li>
		<li class="active">[上线列表]<{$app.name}></li>
	</ul>
	<div class="btnsets">
		<if condition="!!$debug">
		<a class="btn btn-default" href="javascript:;" onclick="deploy_unlock(0)">解除正式发版锁</a>
		<a class="btn btn-default" href="javascript:;" onclick="deploy_unlock(1)">解除测试发版锁</a>
		</if>
		<a class="btn btn-default" href="<{:U('app',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>">发起上线流程</a>
		<a class="btn btn-default" href="<{:U('index/app',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>">返回项目页&lt;&lt;</a>
	</div>
</div>
<div class="toolpanel top cl">
	<div class="searchbox">
		<form method="get" action="<{:U('Deploy/index',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>">
			<div class="col-box" style="width:248px">
				<div class="input-group">
					<span class="input-group-addon">发布人</span>
					<input type="text" class="form-control" placeholder="输入发布人" name="username" value="<{$Think.get.username}>" aria-describedby="basic-addon1" />
				</div>
			</div>
			<div class="col-box" style="width:168px">
				<div class="input-group">
					<span class="input-group-addon">Tag</span>
					<input type="text" class="form-control" placeholder="输入Tag" name="tag" value="<{$Think.get.tag}>" aria-describedby="basic-addon1" />
				</div>
			</div>
			<select class="selectpicker" name="testval">
				<option value="-1">不限</option>
				<option <if condition="$testval==0">selected="selected"</if> value="0">正式</option>
			  <option <if condition="$testval==1">selected="selected"</if> value="1">测试</option>
			</select>
			<button class="btn btn-default" type="submit" autocomplete="off">查询</button>
		</form>
	</div><!--//.searchbox-->
</div>
<form id="tableform" ajaxform="true" ajaxtype="ajaxsubmit" method="post">
	<input type="hidden" name="dosubmit" value="true" />
	<table id="deploytable" class="table table-hover">
		<thead>
			<tr>
			<th style="width:76px"><label><input type="checkbox" onclick="checkAll(this)" />&nbsp;ID</label></th>
			<th style="width:148px">项目ID/名称</th>
			<th style="width:140px">发布/回滚Branch</th>
			<th style="width:132px">发布人</th>
			<th style="width:78px">状态</th>
			<th style="width:92px">正式/测试</th>
			<th style="width:148px">创建/更新时间</th>
			<th style="width:148px">发布/回滚时间</th>
			<th>日志/备注</th>
			<th style="width:248px">操作</th>
			</tr>
		</thead>
		<tbody>
		<foreach name="list" key="key" item="value">
			<tr>
				<th scope="row"><label><input type="checkbox" checker="true" name="id[]" value="<{$value['id']}>" />&nbsp;<{$value['id']}></label></th>
				<td><{$value['appid']}><br/><{$app['name']}></td>
				<td><php>$pack_info=json_decode($value['pack_info'],true);$back_info=json_decode($value['back_info'],true);</php><{$pack_info['branch']}><br/><{$back_info['branch']}></td>
				<td><{:substr($value['username'], 0, strpos($value['username'], '@'))}></td>
				<td><if condition="isset($statuses[$value['status']])"><span class="<if condition="$value['status']==1">text-success</if><if condition="$value['status']==2">text-warning</if>"><{$statuses[$value['status']]}></span><else/><span class="red">未知状态</span><if condition="$value['locked'] != 0"><span class="text-info">[locked]</span></if></if></td>
				<td><if condition="$value['istest'] gt 0"><span class="text-muted">测试</span><else/><span class="text-primary">正式</span></if></td>
				<td><{:date('Y-m-d H:i:s', $value['create_time'])}><br/><if condition="$value['update_time'] gt 0"><{:date('Y-m-d H:i:s', $value['update_time'])}><else/><span class="gray">--</span></if></td>
				<td><if condition="$value['deploy_time'] gt 0"><{:date('Y-m-d H:i:s', $value['deploy_time'])}><else/><span class="gray">--</span></if><br/><if condition="$value['rollback_time'] gt 0"><{:date('Y-m-d H:i:s', $value['rollback_time'])}><else/><span class="gray">--</span></if></td>
				<td><{$value['msglog']}><br/><if condition="$value['remark'] !== ''"><{$value['remark']}><else/><span class="gray">--</span></if></td>
				<td>
				<if condition="!$value['locked'] && $value['status'] == 0">
					<a class="btn btn-default" href="javascript:;" role="button" id="deploy_<{$value['id']}>" onclick="show_deployform(<{$value['id']}>)" data-loading-text="发布中..">发布</a>
				<else/>
				  <a class="btn btn-default" href="javascript:;" role="button" id="deploy_<{$value['id']}>" data-loading-text="发布中.." disabled="disabled">发布</a>
			  </if>
				<if condition="!$value['locked'] && $value['status'] == 1">
					<a class="btn btn-default" href="javascript:;" role="button" id="rollback_<{$value['id']}>" onclick="show_rollbackform(<{$value['id']}>)" data-loading-text="回滚中..">回滚</a>
				<else/>
				  <a class="btn btn-default" href="javascript:;" role="button" id="rollback_<{$value['id']}>" data-loading-text="回滚中.." disabled="disabled">回滚</a>
				</if>
				<a class="btn btn-default" href="<{:U('app',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest,'deployid'=>$value['id']))}>" role="button" id="update_<{$value['id']}>" data-loading-text="">编辑</a>
				<if condition="!$value['locked'] && $value['status'] == 0">
					<a class="btn btn-default" href="javascript:;" role="button" id="delete_<{$value['id']}>" onclick="deploy_delete(<{$value['id']}>)" data-loading-text="删除中..">删除</a>
				<else/>
			    <a class="btn btn-default" href="javascript:;" role="button" id="delete_<{$value['id']}>" data-loading-text="删除中.." disabled="disabled">删除</a>
			  </if>
				</td>
			</tr>
		</foreach>
		</tbody>
	</table>
	<if condition="empty($list)"><div class="noer">暂无数据..</div></if>
	<button class="btn btn-default" type="submit" id="submitbtn" autocomplete="off" style="display:none">提交</button>
	<div class="toolpanel bot cl">
		<if condition="$pageshow"><nav class="pagination" style="margin:0"><{$pageshow}></nav></if>
		<div class="btnsets"></div>
	</div>
</form>
</div><!--//.main-cont-->
<script type="text/javascript">
function deploy_delete(id) {
	var id = typeof id == 'undefined' ? 0 : id;
	var ajaxpost = function(){
		var btn_selector = '#delete_'+id;
		$(btn_selector).button('loading');
		ajaxprocess({
			type: 'post',
			url: '<{:U('Deploy/deploy_delete',array('group'=>$group,'appid'=>$app['id']))}>',
			dataType: 'json',
			data:'deployid='+id+'&dosubmit=true',
			success: function(resp) {
				if(resp.code == 'need_login') {
					showConfirm('请先登录', function(){
						login('', '<{:U('Admin/Public/login')}>');
					});
					return;
				}
				showAlert(resp.info, function(){
					if(resp.status > 0) {
						window.document.location.reload();
					}
				});
			},
			error: function(){
				showAlert('请求异常');
			},
			complete: function(){
				$(btn_selector).button('reset');
			}
		});
	}
	showConfirm('确定要删除该上线任务吗？', function(){
		ajaxpost();
	});
}

/*解除锁定*/
function deploy_unlock(testval) {
	var testval = typeof testval == 'undefined' ? 0 : (testval ? 1 : 0);
	var ajaxpost = function(){
		var btn_selector = '#unlock_'+ testval;
		$(btn_selector).button('loading');
		ajaxprocess({
			type: 'post',
			url: '<{:U('Deploy/unlock',array('group'=>$group,'appid'=>$app['id']))}>',
			dataType: 'json',
			data:'testval='+testval+'&dosubmit=true',
			success: function(resp) {
				if(resp.code == 'need_login') {
					showConfirm('请先登录', function(){
						login('', '<{:U('Admin/Public/login')}>');
					});
					return;
				}
				showAlert(resp.info, function(){
					if(resp.status > 0) {
						window.document.location.reload();
					}
				});
			},
			error: function(){
				showAlert('请求异常');
			},
			complete: function(){
				$(btn_selector).button('reset');
			}
		});
	}
	showConfirm('该功能不是常用功能，出现锁异常时才会使用，确定要解除'+(testval?'测试':'正式')+'发版锁吗？', function(){
		ajaxpost();
	});
}

/*发布的验证弹框*/
var deployDialog;
function show_deployform(id, rollback) {
	var id = typeof id == 'undefined' ? 0 : id;
	var rollback = typeof rollback == 'undefined' ? 0 : parseInt(rollback);
	rollback = rollback ? 1 : 0;
	var btn_selector = (rollback ? '#rollback_' : '#deploy_') + id;
	deployDialog = dialog({
		title: '请输入验证信息（' + (rollback ? '回滚' : '发布' ) + '）',
		content: '<div class="app-infos-div">账&nbsp;&nbsp;&nbsp;号：<input type="text" id="deploy-username" class="app-input" name="username" value="<{$username}>" autocomplete="off" /></div><div class="app-infos-div">密&nbsp;&nbsp;&nbsp;码：<input type="password" id="deploy-password" class="app-input" name="password" value="" autocomplete="off" /></div>',
		ok: function(){
			if($.trim($('#deploy-username').val()) === '') {
				if(this.shake) {
					this.shake();
				} else {
					showAlert('没有填写账号');
				}
				return false;
			}
			if($.trim($('#deploy-password').val()) === '') {
				if(this.shake) {
					this.shake();
				} else {
					showAlert('没有填写密码');
				}
				return false;
			}
			$(btn_selector).button('loading');
			showTip('发布中，请稍等..');
			ajaxprocess({
				type: 'post',
				url: '<{:U('Deploy/app_deploy',array('group'=>$group,'appid'=>$app['id']))}>',
				dataType: 'json',
				data:'deployid='+id+'&rollback='+rollback+'&username='+$('#deploy-username').val()+'&password='+$('#deploy-password').val()+'&dosubmit=true',
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
				error: function(xhr, textStatus, errorThrown){
					if (typeof textStatus == 'undefined') {
						  textStatus = '';
					}
					if (typeof errorThrown == 'undefined') {
						  errorThrown = '';
					}
					if (textStatus.length < 1 && errorThrown.length < 1) {
						  showAlert("请求错误");
					} else {
						  showAlert("请求错误：" + textStatus + ' ' + errorThrown);
					}
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
					$(btn_selector).button('reset');
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
function show_rollbackform(id) {
	show_deployform(id, 1);
}

</script>
<script type="text/javascript">
$(function() {
	function a(a) {/*创建a元素内文本选区并全选*/
		var b = document,
			c = a;
		if (b.body.createTextRange) {
			var d = b.body.createTextRange();
			d.moveToElementText(c), d.select()
		} else if (window.getSelection) {
			var e = window.getSelection(),
				d = b.createRange();
			d.selectNodeContents(c), e.removeAllRanges(), e.addRange(d)
		}
	}
	function b() {
		$(".app-item").on("mouseenter", function(b) {
			//a($(b.currentTarget)[0]);
		}).on("mouseleave", function(a) {})
	}
	ZeroClipboard.config({
		hoverClass: "btn-clipboard-hover"
	});
	var c = new ZeroClipboard($(".btn-clipboard[enabled='true']")),
		d = $("#global-zeroclipboard-html-bridge");
	c.on("ready", function() {
		d.data("placement", "top").attr("title", "复制到剪贴板").tooltip(), c.on("copy", function(a) {
			var b = $(a.target).attr('link');
			console.log(a.target,b), $(a.target).hasClass("btn-clipboard-code") && (/\.css$/.test(b) ? b = '<link href="' + b + '" rel="stylesheet">' : /\.js$/.test(b) && (b = '<script src="' + b + '"></'+'script>')), a.clipboardData.setData("text/plain", b)
		}), c.on("aftercopy", function() {
			d.attr("title", "复制成功！").tooltip("fixTitle").tooltip("show").attr("title", "复制到剪贴板").tooltip("fixTitle")
		})
	}), c.on("error", function() {
		$(".zero-clipboard").remove(), ZeroClipboard.destroy(), b()
	});
});
</script>
<include file="Public/footer" />
