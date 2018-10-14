<include file="Public/header" />
<div class="main-cont">
<div class="toolpanel cl">
	<ul id="navigator" class="breadcrumb">
		<li><a href="<{:U('index',array('type'=>'last','group'=>$group))}>">首页</a></li>
<a class="btn-clipboard btn-clipboard-link" href="<{:U('history',array('type'=>'last','group'=>$group))}>">导出上周svn修改</a>
	</ul>
</div>
<style type="text/css">
.opera-panel {
	padding-right: 20px;
	display:none;
	text-align: right;
	position: absolute;
    top: -2px;
    left: 520px;
}
@media (min-width:768px){
	.opera-panel{display:block}
}
.btn-clipboard {
    display: inline;
    padding: 5px 8px;
    font-size: 12px;
    color: #767676;
    cursor: pointer;
    background-color: #fff;
    border: 1px solid #e1e1e8;
    border-radius: 0 4px 0 4px;
}
.btn-clipboard.gray{color:gray}
.btn-clipboard.gray2{color:#ccc}
.btn-flag.red {color:red}
.btn-flag.blue {color:blue}

.btn-app-linkto {
	padding: 2px 8px;
	font-size: 12px;
	text-decoration: none;
	cursor: pointer;
}
.btn-app-linkto:hover,.btn-app-linkto:active,.btn-app-linkto:visited {
	text-decoration: none;
}
.btn-app-linkto.gray {
	color:gray;
}
.btn-app-linkto.gray2 {
	color:#ccc;
}
.btn-app-linkto>span{font-family:微软雅黑}
.list-group-item {
    background-color: #ffffff;
    border: 1px solid #ecf0f1;
    font-size: 1.1em;
    padding: 10px 20px 10px 20px;
}
.app-info {
	color: #99CC99;
}
.btn-clipboard-hover{color:#fff;background-color:#27AE60;border-color:#27AE60}
</style>
<div class="app-list-cont">
	<ul class="app-list list-group">
	<foreach name="applist" item="app">
		<li class="app-item list-group-item">
			<div class="opera-panel">
				<span class="btn-clipboard btn-clipboard-link" enabled="true" link="<{$app['repo_url']}>"><i class="fa fa-hdd-o"></i>复制正式<{:strtoupper($app['repository'])}>地址</span>
				<span class="btn-clipboard btn-clipboard-link<{$app['repo_url_test']?'':' gray2'}>"<{$app['repo_url_test']?' enabled="true"':''}> link="<{$app['repo_url_test']}>"><i class="fa fa-hdd-o"></i>复制测试<{:strtoupper($app['repository'])}>地址</span>
				<span class="btn-clipboard btn-clipboard-link<{$app['link']?'':' gray2'}>"<{$app['link']?' enabled="true"':''}> link="<{$app['link']}>"><i class="fa fa-link"></i>复制正式地址</span>
				<span class="btn-clipboard btn-clipboard-link<{$app['link_test']?'':' gray2'}>"<{$app['link_test']?' enabled="true"':''}> link="<{$app['link_test']}>"><i class="fa fa-link"></i>复制测试地址</span>
				<if condition="$app['link']">
				<a href="<{$app['link']}>" class="btn-app-linkto glyphicon glyphicon-share-alt" target="_blank"><span>正式</span></a>
				<else/>
				<span class="btn-app-linkto glyphicon glyphicon-share-alt gray2"><span>正式</span></span>
				</if>
				<if condition="$app['link_test']">
				<a href="<{$app['link_test']}>" class="btn-app-linkto glyphicon glyphicon-share-alt" target="_blank"><span>测试</span></a>
				<else/>
				<span class="btn-app-linkto glyphicon glyphicon-share-alt gray2"><span>测试</span></span>
				</if>
			</div>
			<if condition="false && $app['id'] == 0 && $_SERVER['HTTP_HOST'] != 'cms.shouji.sogou-inc.com'">
				<a class="app-name" href="http://cms.shouji.sogou-inc.com/toolsets/rsync/index/app/group/shouji/appid/0" target="_blank"><{$app.name}></a>
			<else/>
				<a class="app-name" href="<{:U('index/app',array('group'=>$group,'appid'=>$app['id']))}>"><{$app.name}></a>
			</if>
			<div class="app-info">
				<if condition="!empty($app['tips'])"><span><{$app['tips']}></span></if>
				<span class="btn-flag flag-sogousvn red">[<if condition="$app['repo_is_sogou']">Sogou</if><{:strtoupper($app['repository'])}>]</span>
				<if condition="$app['sogou_sac']"><span class="btn-flag flag-sogousac blue">[SAC]</span></if>
				<if condition="C('FORCE_CODE_STANDARDS_CHECK') || $app['code_standards_check']"><span class="btn-flag flag-sogousac green">[代码规范]</span></if>
			</div>
		</li>
	</foreach>
	</ul>
</div>
</div><!--//.main-cont-->
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
	})
});
</script>
<include file="Public/footer" />
