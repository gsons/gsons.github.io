$(function(){
	//弹出设置头像框
	$("#sethead-button").click(function(){
		$("body").append("<div id='sethead-back'></div>")
		$("#sethead-back").addClass("sethead-back").fadeIn();
		$("#sethead-box").fadeIn();
	})
	
	
	//退出头像设置框按钮
	$("#sethead-close").hover(function(){
		$("#sethead-close").stop().css({color: 'green'});
	},function(){
		$("#sethead-close").stop().css({color: 'gray'});
	}).click(function(){
		$("#sethead-box").fadeOut('fast');
		$("#sethead-back").fadeOut('fast');
	});
	
	//保存和取消按钮
	$("#sethead-save").hover(function(){
		$("#sethead-save").stop().css({background: 'red'});
	},function(){
		$("#sethead-save").stop().css({background: '#ab2b2b'});
	});
	
	$("#sethead-cancel").hover(function(){
		$("#sethead-cancel").stop().css({background: 'white'});
	},function(){
		$("#sethead-cancel").stop().css({background: '#f5f5f5'});
	});
	
})

