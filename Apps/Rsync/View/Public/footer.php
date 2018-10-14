<div id="progress-info" class="progress-info">
	<div id="progress-inner" class="progress-inner">
		<div class="progress-header cl">
			progress info:&nbsp;<button class="btn btn-default btn-prosess-hide" style="padding:0 4px" type="button" autocomplete="off">点此收起或按下Esc键快速收起↑</button>
			<span class="glyphicon glyphicon-menu-up btn-prosess-hide" aria-hidden="true" title="点击收起或按下Esc键收起"></span>
		</div>
		<div id="progress-body" class="progress-body"></div>
	</div>
</div>
<div class="footer">
	<p class="copy-right">Developed by shouji &copy;<{:date('Y')}></p>
</div>
<a href="#" id="back-to-top"><i class="fa fa-angle-up"></i></a>
</div><!--//.wrapper-->
<include file="Public/bootstrap_modal" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.nicescroll.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/ZeroClipboard.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/site.js?v1"></script>
<script type="text/javascript" src="__PUBLIC__/js/phpjs.js"></script>
<script type="text/javascript">
function progress_info_show(info) {
	$('#progress-info .progress-body').html(info).parent().parent().show('fast');
}
function progress_info_hide(reload,callback) {
	var reload = typeof reload == 'undefined' ? true : (reload?true:false);
	var callback = typeof callback == 'undefined' ? function(){} : callback;
	$(window).off('keydown');
	$('#progress-info .progress-body').html('').parent().parent().hide('fast');
	if(typeof callback == 'function') {
		callback();
	}
	if(reload) {
		setTimeout(function(){window.location.reload()},150);
	}
}
$(function(){
	//$('.selectpicker').selectpicker();
	$('#progress-info').niceScroll('#progress-inner',{
		cursorcolor:"#F00",
		cursoropacitymax:1,
		boxzoom:false,
		touchbehavior:true
	});
});
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
</body>
