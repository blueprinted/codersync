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
	padding-left: 200px;
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
	font-size:14px;
	float:left;
	width:auto
}
.btn-clipboard-hover{color:#fff;background-color:#27AE60;border-color:#27AE60}
</style>
<div class="app-list-cont">
	<ul class="app-list list-group">
		<foreach name="svnlist" item="svn">
			<li class="app-item list-group-item">
				<div><{$key}></div>
			</li>
			<foreach name="svn" item="msg">
				<li class="app-item list-group-item">
					<div class="app-info">
						<span><{$msg['action']}>&nbsp;<{$msg['kind']}></span>
						<a target="_blank" class="app-name" href="<{$msg['url']}>"><{$msg['path']}></a>
					</div>
					<div class="opera-panel">
						<span class="btn-app-linkto"><{$msg['ka']}></span>	
					</div>
				</li>
			</foreach>
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
