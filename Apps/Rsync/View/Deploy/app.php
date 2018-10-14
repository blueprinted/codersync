<include file="Public/header" />
<style type="text/css">
.tag-wrap {
	width: 60%;
	min-width: 320px;
	max-width: 480px;
	padding-bottom: 31px;
	border: 1px solid #ddd;
	position: relative;
	overflow: hidden;
}
.tag-add-wrap, .tag-read-wrap {
	width: 100%;
	padding: 0;
	height: 36px;
	display: block;
	background-color: rgba(240,240,240,0.2);
	position: absolute;
	left: 0;
	bottom: 0;
}
.tag-read-wrap {
	bottom: 20px;
}
.tag-add, .tag-remove {
	height: 36px;
	margin: 0 5px 0 0;
	display: inline-block;
	font-size: 16px;
	line-height: 36px;
	color: blue;
	cursor: pointer;
	position: absolute;
	right: 0;
}
a.tag-remove {
	color: #999;
	text-decoration: none;
}
.tag-item {
	height: 36px;
	line-height: 36px;
	position: relative;
}
.tag-item-handel {
	height: 32px;
	line-height: 32px;
	padding: 0;
	position: absolute;
	right: 0;
	top: 0;
}
.help-block {
	width: 100%;
	display: block;
	margin: 0;
	font-size: 12px;
}
.help-block a {color:green;cursor:pointer}
.tip-block {
	font-size: 14px;
    color: #f00;
    margin-bottom: 15px;
}
</style>
<div class="main-cont">
<div class="toolpanel top cl">
	<ul id="navigator" class="breadcrumb">
		<li><a href="<{:U('index/index',array('group'=>$group))}>">首页</a></li>
		<li class="active">[发起上线流程]<{$app.name}></li>
	</ul>
	<div class="btnsets">
		<a class="btn btn-default" href="<{:U('deploy/index',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>">返回上线列表&lt;&lt;</a>
		<a class="btn btn-default" href="<{:U('index/app',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>">返回项目页&lt;&lt;</a>
	</div>
</div>
<form id="appdeployform" action="<{:U('deploy/app_post',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest,'deployid'=>$deployid))}>" ajaxform="true" ajaxtype="ajaxsubmit" method="post" style="padding:10px 0 10px 5px">
	<input type="hidden" name="dosubmit" value="true" />
	<div class="form-group">
		<label><span class="red">*</span>发版镜像：</label>
		<div class="tag-wrap tag-pack">
		<if condition="!empty($pack)"><p id="pack_<{$pack.task_id}>" class="tag-item"><!--<{$pack.tags}>, --><{$pack.branch}><a class="tag-remove" onclick="remove_pack('<{$pack.task_id}>')">移除(-)</a><input type="hidden" name="pack_taskid" value="<{$pack.task_id}>" /><input type="hidden" name="pack_revision" value="<{$pack.revision}>" /></p></if>
		<p id="pack_none" class="tag-item gray"<if condition="!empty($pack)"> style="display:none"</if>>暂未指定镜像</p>
			<div class="tag-add-wrap tag-pack"<if condition="!empty($pack)"> style="display:none"</if>><span class="tag-add" onclick="show_packlist()">添加(+)</span></div>
		</div>
	</div>
	<div class="form-group">
		<label for="msglog"><span class="red">*</span>发版日志：</label>
		<textarea type="text" id="msglog" class="form-control" name="msglog" placeholder="填写发版日志" style="width:25%;min-width:480px"><{$deployinfo['msglog']}></textarea>
	</div>
	<div class="form-group">
		<label><span class="red">*</span>回滚镜像：</label>
		<div class="tag-wrap tag-back">
		<if condition="!empty($back)"><p id="back_<{$back.task_id}>" class="tag-item"><!--<{$back.tags}>, --><{$back.branch}><a class="tag-remove" onclick="remove_back('<{$back.task_id}>')">移除(-)</a><input type="hidden" name="back_taskid" value="<{$back.task_id}>" /><input type="hidden" name="back_revision" value="<{$back.revision}>" /></p></if>
		<p id="back_none" class="tag-item gray"<if condition="!empty($back)"> style="display:none"</if>>暂未指定回滚镜像</p>
			<div class="tag-add-wrap tag-back"<if condition="!empty($back)"> style="display:none"</if>><span class="tag-add" onclick="show_backlist()">添加(+)</span></div>
		</div>
	</div>
	<div class="form-group">
		<label class="radio-inline"><input type="radio" name="istest" id="istest_0" value="0" <if condition="!empty($deployinfo) && $deployinfo['istest'] == 0">checked="checked"</if> />正式</label>&nbsp;&nbsp;
	  <label class="radio-inline"><input type="radio" name="istest" id="istest_1" value="1" <if condition="!empty($deployinfo) && $deployinfo['istest'] != 0">checked="checked"</if> />测试</label>
	</div>
	<div class="form-group">
		<label for="remark">附加备注(选填)：</label>
		<textarea type="text" id="remark" class="form-control" name="remark" placeholder="填写附加备注信息" style="width:25%;min-width:480px"><{$deployinfo['remark']}></textarea>
	</div>
	<button class="btn btn-default" type="submit" <if condition="$deployinfo['status'] gt 0">disabled="disabled"</if> submitbtn="true" for="appdeployform" data-loading-text="提交中.." autocomplete="off" >提交</button>
	<if condition="$deployinfo['status'] gt 0"><p class="help-block" style="color:red">该版本处于非待发版状态，不能修改</p></if>
</form>
</div><!--//.main-cont-->
<script type="text/javascript">
var packDialog;
function show_packlist() {
	packDialog = dialog({
		title: '请选择要上线的镜像',
		content: '<iframe id="packlist" src="<{:U('deploy/packlist', array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest,'winopen'=>1,'isback'=>0,'perpage'=>10))}>" scrolling="auto" frameborder="0" width="640" height="480" />',
		width: 640,
		height: 480
	});
	packDialog.showModal();
}
var backDialog;
function show_backlist() {
	backDialog = dialog({
		title: '请选择要回滚的镜像',
		content: '<iframe id="backlist" src="<{:U('deploy/packlist', array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest,'winopen'=>1,'isback'=>1,'perpage'=>10))}>" scrolling="auto" frameborder="0" width="640" height="480" />',
		width: 640,
		height: 480
	});
	backDialog.showModal();
}
/**/
function remove_pack(task_id) {
	$('#pack_'+task_id).remove();
	if($('.tag-wrap.tag-pack').children('p[id!="pack_none"]').size() < 1) {
		$('p#pack_none').show();
		$('.tag-add-wrap.tag-pack').show();
	}
}
/**/
function remove_back(task_id) {
	$('#back_'+task_id).remove();
	if($('.tag-wrap.tag-back').children('p[id!="back_none"]').size() < 1) {
		$('p#back_none').show();
		$('.tag-add-wrap.tag-back').show();
	}
}
function ajaxform_confirm_appdeployform() {
	var title = '确定要提交吗？';
	var btn_selector = 'button[submitbtn="true"][for="appdeployform"]';
	return {
		title:title,
		noConfirm: function(){
			return false;
		},
		beforeSubmit: function() {
			$(btn_selector).button('loading');
		},
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
		complete: function() {
			$(btn_selector).button('reset');
		}
	};
}
function ajaxform_check_appdeployform() {
	if($('input[name="pack_taskid"]').size() < 1 || !$('input[name="pack_taskid"]').val()) {
		showAlert('没有指定要发版的镜像');
		return false;
	}
	if($.trim($('#msglog').val()) === '') {
		showAlert('没有填写发版日志');
		return false;
	}
	if($('input[name="back_taskid"]').size() < 1 || !$('input[name="back_taskid"]').val()) {
		showAlert('没有指定要回滚的镜像');
		return false;
	}
	if ($('input[name="pack_revision"]').val() == $('input[name="back_revision"]').val()) {
		showAlert('回滚的镜像与发版的镜像不能是同一个镜像[task_id不一样但是是同一个镜像]');
		return false;
	}
	return true;
}
</script>
<include file="Public/footer" />
