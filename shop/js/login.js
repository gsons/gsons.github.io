/*login页面js*/
$(function() {
	//登录注册按钮
	$("#j-login").hover(function() {
		$(this).stop().animate({
			opacity: '0.85'
			}, 0);
		}, function() {
			$(this).stop().animate({
				opacity: '1'
		}, 0);
	})
	
	//退出登录框按钮
	$("#lgrg-model #close").hover(function() {
		$(this).stop().css({
			background: 'gainsboro',
			color: 'green'
		});
		$(this).stop().animate({
			opacity: '0.3'
			}, 0)
		}, function() {
		$(this).stop().css({
			background: 'white',
			color: 'black'
		});
	})
	
	//弹出登录框
	$("#j-login").on("click", function() {
		$("body").append("<div id='back'></div>");
		$("#back").addClass("back").fadeIn("slow");
		
		//lgrg-model这个参数是弹出框的整个对象
        var screenWidth = $(window).width(), screenHeigth = $(window).height();
        //获取屏幕宽高
        var scollTop = $(document).scrollTop();
        //当前窗口距离页面顶部的距离
        var objLeft = (screenWidth - $("#lgrg-model").width()) / 2;
        ///弹出框距离左侧距离
        var objTop = (screenHeigth - $("#lgrg-model").height()) / 2 + scollTop;
        ///弹出框距离顶部的距离
        $("#lgrg-model").css({
            left:objLeft + "px",
            top:objTop + "px"
        });
		$("#lgrg-model").fadeIn();
		
		//关掉登录框
		$("#lgrg-model #close").on('click', function() {
			$("#lgrg-model").fadeOut("fast");
			$(".back").css({
				display: 'none'
			});
		});
	
	//输入框设置
	
	$(".input-email .input-form").focus(function(){
		$(".input-email .input-form").css("color", "black");
		$(".input-email").css("border", "1px solid #008000");
		$(".wrong_tip1").css("display", "none");
	});
	
	var email = document.getElementById("email");
	email.oninput=function(){
		if( email.value!="" ){
			$(".email-tip").css("visibility", "visible");
		}
	}
	//用户
	$(".input-email .input-form").blur(function(){
		$(".input-email").css("border", "1px solid #c5cddb");
		$(".wrong_tip2").css("display", "none");
		if($(".input-email .input-form").val()==""){
			$(".input-email .input-form").css("color", "#ddd");
			$(".email-tip").css("visibility", "hidden");
		}else{
			var rel = new RegExp("^([\u4E00-\uFA29]|[\uE7C7-\uE7F3]|[a-zA-Z0-9])*$");
			if(!rel.test(email.value)){
				$(".input-email").css("border", "1px solid red");
				$(".wrong_tip1").css("display", "block");
			}else{
				$(".input-email").css("border", "1px solid #ddd");
				$(".wrong_tip1").css("display", "none");
			}
			$(".email-tip").css("visibility", "visible");
		}
	});
	//密码
	$(".input-password .input-form").focus(function(){
		$(".input-password .input-form").css("color", "black");
		$(".wrong_tip2").css("display", "none");
		$(".input-password").css("border", "1px solid #008000");	
	});
	var password = document.getElementById("password");
	password.oninput=function(){
		if( password.value!="" ){
			$(".password-tip").css("visibility", "visible");
		}
	}
	$(".input-password .input-form").blur(function(){
		$(".input-password").css("border", "1px solid #c5cddb");
		if($(".input-password .input-form").val()==""){
			$(".input-password .input-form").css("color", "#ddd");
			$(".password-tip").css("visibility", "hidden");
		}else{
			$(".password-tip").css("visibility", "visible");
		}
	});
	
	//输入框的close按钮
	$(".email-tip").click(function(){
		$(".input-email .input-form").val("").focus();
		$(".email-tip").css("visibility", "hidden");
	});
	$(".password-tip").click(function(){
		$(".input-password .input-form").val("").focus();
		$(".password-tip").css("visibility", "hidden");
	});
	
	//黑色背景
	$(".back").on("click", function() {
		$("#lgrg-model").fadeOut("fast");
		$(".back").css({
			display: 'none'
			});
		});
	});
	
	//登录按钮
	$("#lgrg-model #login").hover(function(){
		$(this).stop().animate({
			opacity:0.8
		},0);
	},function(){
		$(this).stop().animate({
			opacity:0.6
		},0);
	});
	
	//登录按钮
	$("#lgrg-model #login").click(function(){		
		$.ajax({
			url:"LoginServlet",
			type:"post",
			data:{
				userName:$("#email").val(),
				password:$("#password").val(),
			},
			dataType:"json",
			success:function(data){
				if(data.fail == "fail"){
					$(".wrong_tip2").css("display", "block");
				}else if(data.root=="root"){
					window.location.href="http://localhost:8080/Leaf-Shop/index.jsp";
				}else{
					$(".unlogin").fadeOut(500);
					$("#lgrg-model").fadeOut("fast");
					$(".back").css({
						display: 'none'
					});
					window.location.href="homePage.jsp?"+data.userName;
				}
			},
				error:function(){
					alert("登录请求出错，请稍后再试")
				}					
				
			});
	});
	
	//用户退出
	$(".userExit").click(function(){
		$.ajax({
			url:"UserExitServlet",
			type:"post",
			dataType:"json",
			success:function(data){
				window.location.reload();
			},
			error:function(){
				alert("退出请求出错，请稍后再试")
			}
		});
	});
	
	//登录方式按钮
	$("#lgrg-model .lgmethod").hover(function(){
		$(this).stop().animate({
				opacity:0.9
		},0);
		},function(){
			$(this).stop().animate({
			opacity:0.7
		},0);
	});
	
	//记不记住密码
	$("#slectbox").click(function(){
		if($("#slectbox").hasClass("onSelected")){
			$("#slectbox").css("background", "white");
			$("#slectbox").removeClass("onSelected");
		}else{
			$("#slectbox").addClass("onSelected");
			$("#slectbox").css("background", "url(img/login.img/sprite.png)-330px -92px no-repeat");	
		}
	});
});