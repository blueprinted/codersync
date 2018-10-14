<include file="Public/header" />
<style type="text/css">
.back-app-page{
	display: inline-block;
  padding: 6px 12px;
  margin: 0;
  font-size: 14px;
  cursor: pointer;
}
</style>
<div class="main-cont">
<div class="toolpanel top cl">
	<ul id="navigator" class="breadcrumb">
		<li><a href="<{:U('index/index',array('group'=>$group))}>">首页</a></li>
		<li class="active">[镜像列表]<{$app.name}></li>
	</ul>
	<div class="btnsets">
		<a class="btn btn-default" href="<{:U('app',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>">发起上线流程</a>
		<a class="btn btn-default" href="<{:U('index/app',array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest))}>">返回项目页&lt;&lt;</a>
	</div>
</div>
<div class="toolpanel top cl">
	<div class="searchbox">
		<form method="get" action="<{:U('Deploy/packlist', array('group'=>$group,'appid'=>$app['id'],'istest'=>$istest,'winopen'=>0))}>">
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
<form id="tableform" ajaxform="true" ajaxtype="ajaxsubmit" method="post">
	<input type="hidden" name="dosubmit" value="true" />
	<table id="packlisttable" class="table table-hover">
		<thead>
			<tr>
			<th style="width:88px">项目ID</th>
			<th>项目名称</th>
			<th style="width:128px">Tags</th>
			<th style="width:282px">task_id/revision</th>
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
				<td><{$app['name']}></td>
				<td><{$value['tags']}></td>
				<td><{$value['task_id']}><br/><{$value['revision']}></td>
				<td><{$value['branch']}></td>
				<td><{$value['status']}></td>
				<td><{$value['create_time']}></td>
				<td><span class="gray">--</span></td>
			</tr>
		</foreach>
		</tbody>
	</table>
	<if condition="!empty($errmsg)">
		<div class="noer red"><{$errmsg}></div>
	<else/>
	  <if condition="empty($list)"><div class="noer">暂无数据..</div></if>
	</if>
	<button class="btn btn-default" type="submit" id="submitbtn" autocomplete="off" style="display:none">提交</button>
	<div class="toolpanel bot cl">
		<if condition="$pageshow"><nav class="pagination" style="margin:0"><{$pageshow}></nav></if>
		<div class="btnsets"></div>
	</div>
</form>
</div><!--//.main-cont-->
<include file="Public/footer" />
