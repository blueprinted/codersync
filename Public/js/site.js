var URL_MODEL = typeof URL_MODEL == 'undefined' ? 1 : URL_MODEL;
function selectAll(checked, attrname, attrvalue, group) {
	var _len = 0;
	var attrname = typeof attrname == 'undefined' ? 'checker' : attrname;
	var attrvalue = typeof attrvalue == 'undefined' ? 'true' : attrvalue;
	var group = typeof group == 'undefined' ? '' : group;
	$('input[type="checkbox"]['+attrname+'="'+attrvalue+'"]:enabled').each(function(){
		$(this).prop('checked', checked);_len++;
	});
	$('input[type="checkbox"][checkall="true"]'+(group!==''?'[group="'+group+'"]':'')).prop('checked', checked);
	return _len;
}
function checkAll(o, attrname, attrvalue, group) {
	var attrname = typeof attrname == 'undefined' ? 'checker' : attrname;
	var attrvalue = typeof attrvalue == 'undefined' ? 'true' : attrvalue;
	var group = typeof group == 'undefined' ? '' : group;
	return selectAll($(o).prop('checked'),attrname,attrvalue,group);
}

function showAlert(msg, callback, timeout, title) {
	var msg = typeof msg == 'undefined' ? '' : msg;
	var timeout = typeof timeout == 'undefined' ? 0 : parseFloat(timeout);
	timeout = isNaN(timeout) ? 0 : timeout;
	var title = typeof title == 'undefined' ? '\u63d0\u793a\u6d88\u606f' : title;
	var ST = null;

	$('#modal_alert .modal-title').html(title);
	$('#modal_alert .modal-body').html(msg);

	$('#modal_alert').modal({
		backdrop:true,keyboard:true,show:true,remote:false
	}).off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
		try{if(timeout > 0){clearTimeout(ST)}callback();}catch(e){}
	});

	if(timeout > 0) {
		ST = setTimeout(function(){$('#modal_alert').modal('hide')}, timeout*1000);
	}
}

function showConfirm(msg, okFunc, canFunc, title) {
	var msg = typeof msg == 'undefined' ? '' : msg;
	var okFunc = typeof okFunc == 'function' ? okFunc : null;
	var canFunc = typeof canFunc == 'function' ? canFunc : null;
	var title = typeof title == 'undefined' ? '\u63d0\u793a\u6d88\u606f' : title;

	$('#modal_confirm .modal-title').html(title);
	$('#modal_confirm .modal-body').html(msg);

	$('#modal_confirm').modal({
		//backdrop:true,keyboard:true,show:true,remote:false
		backdrop:'static',keyboard:true,show:true,remote:false
	}).off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
		if(!$(this).attr('btnyes')) {
			try{canFunc()}catch(e){}
		}
		$(this).removeAttr('btnyes');
	});

	$('#modal_confirm .btn.btn-primary').off('click').on('click', function(){
		try{okFunc()}catch(e){}
		$('#modal_confirm').attr('btnyes', true).modal('hide');
	});
}

function showTip(msg, callback, title) {
	var msg = typeof msg == 'undefined' ? '' : msg;
	var title = typeof title == 'undefined' ? '\u63d0\u793a\u6d88\u606f' : title;

	$('#modal_tip .modal-title').html(title);
	$('#modal_tip .modal-body').html(msg);

	$('#modal_tip').modal({
		backdrop:'static',keyboard:false,show:true,remote:false
	}).off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
		try{callback()}catch(e){}
	});
}
function hideTip() {
	$('#modal_tip').modal('hide');
}

/*通用ajax提交处理*/
function ajaxprocess(options) {
	var defaults = {
		type: 'get',
		url: '',
		data: '',
		dataType: 'json',
		success: function(resp) {
			alert(resp.message);
			if(resp.status==1) {
				setTimeout(function(){window.location.reload()},1250);
			}
		},
		error: function() {
			alert('请求失败[ajax error]');
		},
		complete: function(){}
	};
	var options = $.extend(defaults, options);
	$.ajax({
		type: options.type,
		url: options.url,
		data: options.data,
		dataType: options.dataType,
		success: function(resp) {
			options.success(resp);
		},
		error: function() {
			options.error();
		},
		complete: function() {
			options.complete();
		}
	});
}
/**
 *	登录
 */
function login(rurl, loginurl) {
	var rurl = isUndefined(rurl) || !rurl ? window.location.href : rurl;
	var loginurl = isUndefined(loginurl) ? null : loginurl;
	var base_url = isUndefined(BASE_URL) ? '' : BASE_URL;
	if(!loginurl)
		try{loginurl = $(event.target).attr('href')}catch(e){}
	if(!loginurl)
		loginurl = base_url + 'Admin/Public/login';
	window.location.href = loginurl + '?rurl=' + encodeURIComponent(urlrewrite_encode(rurl));
	doane();
}
/**
 *	注销
 */
function logout(appid, logouturl) {
	var appid = isUndefined(appid) ? 0 : appid;
	var logouturl = isUndefined(logouturl) ? null : logouturl;
	var base_url = isUndefined(BASE_URL) ? '' : BASE_URL;
	if(!logouturl)
		try{logouturl = $(event.target).attr('href')}catch(e){}
	if(!logouturl)
		logouturl = base_url + 'Admin/Public/logout';
	ajaxprocess({
		type: 'get',
		url: logouturl,
		dataType: 'json',
		data: '',
		success: function(resp) {
			if(resp.status > 0) {
				/* 这种方式在搜狗浏览器下会报js错误导致js中断运行(在chrome下能正常执行)：
				pc.jsp:1 Refused to execute script from 'https://oa.sogou-inc.com/logout.jsp' because its MIME type ('text/html') is not executable, and strict MIME type checking is enabled.
				appendscript('https://oa.sogou-inc.com/logout.jsp', '', function(){//之前是 https://oa.sogou-inc.com/sso/logout.jsp , 2018-07-19伟亮发现不能同步注销, 查了一下pandora系统的注销url已经更改, 在此做了一样的更改, 问题解决
					window.location.reload();
				});
				*/
				//window.location.href = 'https://login.sogou-inc.com/logout.jsp?appid='+appid+'&sso_redirect='+encodeURIComponent(base_url+'index.php/Admin/Public/login')+'&targetUrl='+encodeURIComponent(window.location.href);
				window.location.href = base_url + 'Admin/Public/logoutOA?rurl='+encodeURIComponent(window.location.href);
			} else {
				showAlert('\u6ce8\u9500\u5931\u8d25\u3002');
			}
		},
		error: function() {},
		complete: function() {}
	});
	doane();
}

function urlrewrite_encode(string) {
	if(typeof string == 'undefined')
		return '';
	return URL_MODEL == 2 ? string.replace(/\//gm, '|') : string;
}
function urlrewrite_decode(string) {
	if(typeof string == 'undefined')
		return '';
	return URL_MODEL == 2 ? string.replace(/\|/gm, '/') : string;
}

!function($){
	$(window).scroll(function() {
		$(this).scrollTop() > 100 ? $('#back-to-top').fadeIn() : $('#back-to-top').fadeOut()
	});
	$('#back-to-top').on('click', function(b) {
		return b.preventDefault(), $('html, body').animate({
			scrollTop: 0
		}, 100), !1;
	});
}(jQuery);

$(function(){
	'use strict';
	$('form[ajaxform="true"]').each(function(){
		if(!$(this).data('action')) {
			$(this).data('action',$(this).attr('action'));
		}
	});
	$('form[ajaxform="true"][ajaxtype="ajaxform"]').each(function(){
		var _form = $(this);
		if(!$(_form).attr('id')) {
			$(_form).attr('id', 'ajaxform_'+hash((new Date().getTime()).toString(), 8));
		}
		var _formId = $(_form).attr('id');/*表单id*/
		var _submitBtn = $(_form).find('button[type="submit"]');/*提交按钮*/

		$(_form).ajaxForm({
			dataType: 'json',
			beforeSubmit: function() {
				if($(_submitBtn).attr('disabled')) {
					showAlert('请等待当前操作执行完毕');
					return false;
				}
				/*是否有该表单的提交时的验证函数*/
				var ajaxform_check_handel;
				try {
					ajaxform_check_handel = eval('ajaxform_check_'+_formId);
				}catch(e) {
					try{
						ajaxform_check_handel = eval('ajaxform_check');
					}catch(e){}
				}
				if(typeof ajaxform_check_handel == 'function' && !ajaxform_check_handel()) {
					return false;
				}
				$(_submitBtn).attr('disabled', true);
			},
			complete: function(XMLHttpRequest, status) {
				$(_form).attr('action',$(_form).data('action'));
				$(_submitBtn).attr('disabled', false);
			},
			success: function(resp) {
				if(typeof resp != 'object') {
					try{resp = JSON.parse(resp)}catch(e){
						showAlert('解析响应数据失败');
						return;
					}
				}
				if(resp.status > 0) {
					var message = resp.message;
					message += '，'+resp.waiting+'秒后页面自动刷新，请稍等..';
					showAlert(message);
					setTimeout(function(){
						window.location.reload();
					}, resp.waiting*1000);
					return;
				}
				showAlert(resp.message);
			},
			error:function() {
				showAlert('服务器响应失败');
			}
		});
	});
	$('form[ajaxform="true"][ajaxtype="ajaxsubmit"]').each(function(){
		var _form = $(this);
		if(!$(_form).attr('id')) {
			$(_form).attr('id', 'ajaxform_'+hash((new Date().getTime()).toString(), 8));
		}
		var _formId = $(_form).attr('id');/*表单id*/
		var _submitBtn = $(_form).find('button[type="submit"]');/*提交按钮*/

		/*是否有该表单的提交时的确认函数*/
		var ajaxform_confirm_handle;
		try {
			ajaxform_confirm_handle = eval('ajaxform_confirm_'+_formId);
		} catch(e) {
			try{
				ajaxform_confirm_handle = eval('ajaxform_confirm');
			}catch(e){}
		}
		ajaxform_confirm_handle = typeof ajaxform_confirm_handle == 'function' ? ajaxform_confirm_handle : function(){};

		$(_form).submit(function(){
			var postform = function(options) {
				$(_form).ajaxSubmit({
					dataType:'json',
					beforeSubmit:function(){
						$(_submitBtn).attr('disabled', true);
						try{(options.beforeSubmit)()}catch(e){}
					},
					complete:function(XMLHttpRequest, status){
						$(_submitBtn).attr('disabled', false);
						try{(options.complete)()}catch(e){}
					},
					success:function(resp){
						try{(options.success)(resp)}
						catch(e){
							if(resp.status > 0) {
								var message = resp.info;
								message += '，2秒后页面自动刷新，请稍等..';
								showAlert(message);
								setTimeout(function(){
									window.location.reload();
								}, 2000);
							} else {
								showAlert(resp.message);
							}
						}
					},
					error:function(){
						showAlert('服务器响应失败');
					}
				});
			};
			if($(_submitBtn).attr('disabled')) {
				showAlert('请等待当前操作执行完毕');
				return false;
			}
			/*是否有该表单的提交时的验证函数*/
			var ajaxform_check_handel;
			try {
				ajaxform_check_handel = eval('ajaxform_check_'+_formId);
			}catch(e) {
				try{
					ajaxform_check_handel = eval('ajaxform_check');
				}catch(e){}
			}
			if(typeof ajaxform_check_handel == 'function' && !ajaxform_check_handel()) {
				return false;
			}
			if(ajaxform_confirm_handle().noConfirm()) {
				postform(ajaxform_confirm_handle());
			} else {
				showConfirm(ajaxform_confirm_handle().title||'确定要对选中项执行该操作吗？', function(){
					postform(ajaxform_confirm_handle());
				}, function(){try{(ajaxform_confirm_handle().cancelFunc)()}catch(e){}});
			}
			return false;
		});
	});
});
