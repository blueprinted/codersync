<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title>镜像列表</title>
<meta http-equiv="X-UA-Compatible" content="IE=10;chrome=1;" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/bootstrap3.3.7/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/style.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/rsync/style.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/ui-dialog.css" />
<style type="text/css">html, body {font-family:微软雅黑,宋体,serif,Arial,Verdana,Geneva,Helvetica,sans-serif}</style>
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.form.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/bootstrap3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/artdialog/dialog-plus.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/ie10-viewport-bug-workaround.js"></script>
</head>
<body>
<div id="append_parent"></div><div id="ajaxwaitid"></div>
<div class="wrapper">
<div class="toolpanel top cl">
	<div class="searchbox">
		<form method="get" action="<{:U('Deploy/packlist', array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest, 'winopen'=>1))}>">
			<div class="col-box" style="width:168px">
				<div class="input-group">
					<span class="input-group-addon">Tags</span>
					<input type="text" class="form-control" placeholder="输入Tags" name="tag" value="<{$tag}>" aria-describedby="basic-addon1" />
				</div>
			</div>
			<button class="btn btn-default" type="submit" autocomplete="off">查询</button>
		</form>
	</div><!--//.searchbox-->
</div>
<form id="tableform" method="post">
	<input type="hidden" name="dosubmit" value="true" />
	<table id="packlisttable" class="table table-hover" style="margin-bottom:0;">
		<thead>
			<tr>
			<th style="width:88px">项目ID</th>
			<th style="width:128px">Tags</th>
			<th style="width:88px">branch</th>
			<th style="width:86px">状态</th>
			<th style="width:168px">创建时间</th>
			<th style="width:88px">操作</th>
			</tr>
		</thead>
		<tbody>
		<foreach name="list" key="key" item="value">
			<tr>
				<td><{$app['id']}></td>
				<td><{$value['tags']}></td>
				<td><{$value['branch']}></td>
				<td><{$value['status']}></td>
				<td><{$value['create_time']}></td>
				<td>
					<if condition="$isback eq 0">
						<button class="btn btn-default" type="button" id="select_<{$value['task_id']}>" onclick="select_pack('<{$value['task_id']}>')" autocomplete="off">添加</button>
					<else/>
					  <button class="btn btn-default" type="button" id="select_<{$value['task_id']}>" onclick="select_back('<{$value['task_id']}>')" autocomplete="off">添加</button>
				  </if>
				</td>
			</tr>
		</foreach>
		</tbody>
	</table>
	<if condition="!empty($errmsg)">
		<div class="noer red"><{$errmsg}></div>
	<else/>
	  <if condition="empty($list)"><div class="noer">暂无数据..</div></if>
	</if>
	<div class="toolpanel bot cl">
		<if condition="$pageshow"><nav class="pagination" style="margin:0"><{$pageshow}></nav></if>
		<div class="btnsets"></div>
	</div>
</form>
</div><!--//.main-cont-->
<script type="text/javascript">
<php>
$array = array();
foreach($list as $key => $value) {
	$array[$value['task_id']]=$value;
}
echo "var array = ".json_encode($array);
</php>
</script>
<script type="text/javascript">
function select_pack(task_id) {
	var item = array[task_id];
	if(!item || $(window.parent.document).find('#pack_'+item.task_id).size() > 0 || $(window.parent.document).find('.tag-wrap.tag-pack').children('p[id!="pack_none"]').size() > 0) {
		return;
	}
	$(window.parent.document).find('.tag-wrap.tag-pack').children('p#pack_none').hide();
	$('<p id="pack_'+item.task_id+'" class="tag-item">'+item.branch+'<a class="tag-remove" onclick="remove_pack(\''+item.task_id+'\')">移除(-)</a><input type="hidden" name="pack_taskid" value="'+item.task_id+'" /><input type="hidden" name="pack_revision" value="'+item.revision+'" /></p>').insertBefore($(window.parent.document).find('.tag-wrap.tag-pack').children('.tag-add-wrap'));
	$(window.parent.document).find('.tag-add-wrap.tag-pack').hide();
	if(window.parent.packDialog) {
		window.parent.packDialog.close();
	}
}
function select_back(task_id) {
	var item = array[task_id];
	if(!item || $(window.parent.document).find('#back_'+item.task_id).size() > 0 || $(window.parent.document).find('.tag-wrap.tag-back').children('p[id!="back_none"]').size() > 0) {
		return;
	}
	$(window.parent.document).find('.tag-wrap.tag-back').children('p#back_none').hide();
	$('<p id="back_'+item.task_id+'" class="tag-item">'+item.branch+'<a class="tag-remove" onclick="remove_back(\''+item.task_id+'\')">移除(-)</a><input type="hidden" name="back_taskid" value="'+item.task_id+'" /><input type="hidden" name="back_revision" value="'+item.revision+'" /></p>').insertBefore($(window.parent.document).find('.tag-wrap.tag-back').children('.tag-add-wrap'));
	$(window.parent.document).find('.tag-add-wrap.tag-back').hide();
	if(window.parent.backDialog) {
		window.parent.backDialog.close();
	}
}
</script>
</div><!--//.wrapper-->
<include file="Public/bootstrap_modal" />
<script type="text/javascript" src="__PUBLIC__/js/site.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/phpjs.js"></script>
</body>
</html>
