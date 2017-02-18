/*个人页面js代码*/
$(function() {
	
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
	
	var line = document.getElementsByClassName("line");
	var right = document.getElementById("right");
	var right1 = document.getElementById("right1");
	var right2 = document.getElementById("right2");
	var right3 = document.getElementById("right3");
	//地址管理
	line[1].onclick = function() {
			right.style.display = "none";
			right2.style.display = "none";
			right3.style.display = "none";
			right1.style.display = "block";
			line[0].style.color = "black";
			line[2].style.color = "black";
			line[1].style.color = "red";
		}
	//个人信息
	line[0].onclick = function() {
			right.style.display = "none";
			right1.style.display = "none";
			right3.style.display = "none";
			right2.style.display = "block";
			line[1].style.color = "black";
			line[2].style.color = "black";
			line[0].style.color = "red";
		}
	//我的订单
	line[2].onclick = function() {
		right.style.display = "none";
		right1.style.display = "none";
		right2.style.display = "none";
		right3.style.display = "block";
		line[0].style.color = "black";
		line[1].style.color = "black";
		line[2].style.color = "red";
	}
});