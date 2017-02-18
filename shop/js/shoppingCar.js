/*购物车页面的js代码*/

$(function(){
	/*通用的页面头开始*********************************************/
	var len = $("#js-funcTab .tab-inner ul li").length;
	for( var i = 0; i < len; i++ ){
		$("#js-functTabWrap #js-funcTab .tab-inner ul li").eq(i).hover(function(){
			$(this).find(".level").stop().fadeIn(100);
			$(this).find(".topLevel").css("color", "#AB2B2B");
		}, function(){
			$(this).find(".level").stop().fadeOut(100);
			$(this).find(".topLevel").css("color", "#333");
		});
	}
	/*通用的页面头结束*********************************************/
	
	
});


