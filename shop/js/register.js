

window.onload = function(){
	var btn_clear = document.getElementsByClassName("u-clear");
	var i_input = document.getElementsByClassName("i-inpt");
	/*
	 * 清空按钮的功能
	 */
	for (var i=0; i<btn_clear.length;i++) {
		btn_clear[i].onclick = function(){
			document.getElementById(this.id).value = "";
			this.style.display = "none";
			//document.getElementById(this.id).style.borderColor="#ddd";
			document.getElementById(this.id + "_err").style.display="none";
			document.getElementById(this.id).focus();
		}
	}
	for (var i=0; i<i_input.length; i++) {
		i_input[i].onfocus = function(){
			this.style.borderColor = "deepskyblue";
			this.style.color = "black";
		}
	}
	/*
	 * 判断input中的对错提示
	 *
	 */
	
	var right = i_input.length;
	var uNum = 0; //用户名
	var pNum1 = 0; //密码
	var pNum2 = 0; //确认密码
	var vNum = 0;  //验证码
	var sNum = 0;  //手机号
	var i = 0;
	for (i=0; i<i_input.length-1; i++) {
		i_input[i].onblur = function(){
			if(this.id == "username"){
				this.placeholder = "用户账号名";
				var username = document.getElementById(this.id).value;
				if(document.getElementById(this.id).value.length<6 || document.getElementById(this.id).value.length>20){
					document.getElementById("errOrright1").className="i-err";
					document.getElementById("p-err1").style.display="block";
					this.style.borderColor = "red";
					uNum = 0;
					document.getElementById(this.id + "_err").style.display="block";
				}
				else if(!/^([\u4E00-\uFA29]|[\uE7C7-\uE7F3]|[a-zA-Z0-9])*$/.test(username)){
					document.getElementById("errOrright1").className="i-err";
					document.getElementById("p-err1").innerHTML = "仅支持数字、字母和中文汉字";
					document.getElementById("p-err1").style.display="block";
					this.style.borderColor = "red";
					document.getElementById(this.id + "_err").style.display="block";
					uNum = 0;
				}
				else{
					document.getElementById("errOrright1").className="i-right";
					document.getElementById("p-err1").style.display="none";
					document.getElementById(this.id + "_err").style.display="block";
					document.getElementById(this.id).style.borderColor="#ddd";
					uNum = 1;
				}
				if(document.getElementById(this.id).value.length==0){
					document.getElementById(this.id).style.borderColor="#ddd";
					document.getElementById(this.id + "_err").style.display="none";
					uNum = 0;
				}
			}
			if(this.id == "password1"){
				this.placeholder = "6-16位密码 , 区分大小写";
				var number=document.getElementById(this.id).value.length;
				if(number<6 || number>20){
					document.getElementById("errOrright2").className="i-err";
					document.getElementById("p-err2").style.display="block";
					this.style.borderColor = "red";
					document.getElementById(this.id + "_err").style.display="block";
					pNum1 = 0;
				}
				else{
					document.getElementById("errOrright2").className="i-right";
					document.getElementById("p-err2").style.display="none";
					document.getElementById(this.id + "_err").style.display="block";
					document.getElementById(this.id).style.borderColor="#ddd";
					pNum1 = 1;
				}
				if(document.getElementById(this.id).value.length==0){
					document.getElementById(this.id).style.borderColor="#ddd";
					document.getElementById(this.id + "_err").style.display="none";
					pNum1 = 0;
				}
			}
			if(this.id == "password2"){
				this.placeholder = "再次输入密码";
				var hi=document.getElementById("password1").value;
				var hi1=document.getElementById(this.id).value;
				if(hi!=hi1){
					document.getElementById("errOrright3").className="i-err";
					document.getElementById("p-err3").style.display="block";
					this.style.borderColor = "red";
					document.getElementById(this.id + "_err").style.display="block";
					pNum2 = 0;
				}
				else{
					document.getElementById("errOrright3").className="i-right";
					document.getElementById("p-err3").style.display="none";
					document.getElementById(this.id + "_err").style.display="block";
					document.getElementById(this.id).style.borderColor="#ddd";
					pNum2 = 1;
				}
				if(document.getElementById(this.id).value.length==0){
					document.getElementById(this.id).style.borderColor="#ddd";
					document.getElementById(this.id + "_err").style.display="none";
					pNum2 = 0;
				}
			}
			if(this.id == "validcode"){
				this.placeholder = "请输入验证码";
				var codenumber=document.getElementById(this.id).value;
				if(document.getElementById(this.id).value.length==0){
					document.getElementById(this.id).style.borderColor="#ddd";
					document.getElementById(this.id + "_err").style.display="none";
					vNum = 0;
				}else{
					$.ajax({
						url: "VerifyValidCodeServlet",
						type: "post",
						data:{
							validCode: codenumber.value,
						},
						dataType: "json",
						success: function(data){
							if(codenumber!=data.validCode){
								document.getElementById("errOrright4").className="i-err";
								document.getElementById("p-err4").style.display="block";
								document.getElementById("validcode").style.borderColor = "red";
								document.getElementById("validcode" + "_err").style.display="block";
								vNum = 0;
							}else{
								document.getElementById("errOrright4").className="i-right";
								document.getElementById("p-err4").style.display="none";
								document.getElementById("validcode" + "_err").style.display="block";
								document.getElementById("validcode").style.borderColor="#ddd";
								vNum = 1;
							}
						},
						error: function(){
							alert("验证码请求出错，请稍后重试！");
						}
					});
					
				}
			}
		}
	}
	i_input[i].onblur = function(){
		this.placeholder = "11位手机号";
		var mobile=document.getElementById(this.id).value;
		if(!/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/i.test(mobile)){
			document.getElementById("errOrright5").className="i-err";
			document.getElementById("p-err5").style.display="block";
			this.style.borderColor = "red";
			document.getElementById(this.id + "_err").style.display="block";
			sNum = 0;
		}
		else{
			document.getElementById("errOrright5").className="i-right";
			document.getElementById("p-err5").style.display="none";
			document.getElementById(this.id + "_err").style.display="block";
			document.getElementById(this.id).style.borderColor="#ddd";
			sNum = 1;
		}
		if(document.getElementById(this.id).value.length==0){
			document.getElementById(this.id).style.borderColor="#ddd";
			document.getElementById(this.id + "_err").style.display="none";
			sNum = 0;
		}
	}
	
	$("#imgAuthCode").click(function(){
		var codenumber=document.getElementById("validcode").value;
		if(document.getElementById("validcode").value.length==0){
			document.getElementById("validcode").style.borderColor="#ddd";
			document.getElementById("validcode" + "_err").style.display="none";
			vNum = 0;
		}else{
			$.ajax({
				url: "VerifyValidCodeServlet",
				type: "post",
				data:{
					validCode: codenumber.value,
				},
				dataType: "json",
				success: function(data){
					if(codenumber!=data.validCode){
						document.getElementById("errOrright4").className="i-err";
						document.getElementById("p-err4").style.display="block";
						document.getElementById("validcode").style.borderColor = "red";
						document.getElementById("validcode" + "_err").style.display="block";
						vNum = 0;
					}else{
						document.getElementById("errOrright4").className="i-right";
						document.getElementById("p-err4").style.display="none";
						document.getElementById("validcode" + "_err").style.display="block";
						document.getElementById("validcode").style.borderColor="#ddd";
						vNum = 1;
					}
				},
				error: function(){
					alert("验证码请求出错，请稍后重试！");
				}
			});
			
		}
	});
	/**
	 * 清空按钮的显示与隐藏
	 */
	for (var i=0; i<i_input.length; i++) {
		i_input[i].onkeyup = function(){
			if(this.id == "username"){
				var number = document.getElementById(this.id).value.length;
				if(number == 0){
					document.getElementById(this.id).style.borderColor="#ddd";
					btn_clear[0].style.display="none";
				}else{
					btn_clear[0].style.display="block";
				}
			}
			if(this.id == "password1"){
				var number = document.getElementById(this.id).value.length;
				if(number == 0){
					document.getElementById(this.id).style.borderColor="#ddd";
					btn_clear[1].style.display="none";
				}else{
					btn_clear[1].style.display="block";
				}
			}
			if(this.id == "password2"){
				var number = document.getElementById(this.id).value.length;
				if(number == 0){
					document.getElementById(this.id).style.borderColor="#ddd";
					btn_clear[2].style.display="none";
				}else{
					btn_clear[2].style.display="block";
				}
			}
			if(this.id == "validcode"){
				var number = document.getElementById(this.id).value.length;
				if(number == 0){
					document.getElementById(this.id).style.borderColor="#ddd";
					btn_clear[3].style.display="none";
				}else{
					btn_clear[3].style.display="block";
				}
			}
			if(this.id == "phone"){
				var number = document.getElementById(this.id).value.length;
				if(number == 0){
					document.getElementById(this.id).style.borderColor="#ddd";
					btn_clear[4].style.display="none";
				}else{
					btn_clear[4].style.display="block";
				}
			}
		}
	}
	
	$(".b-btn").click(function(){
		if(right == uNum+pNum1+pNum2+vNum+sNum){
			$.ajax({
				url: "RegisterServlet",
				type:"post",
				data:{
					userName: $("#username").val(),
					password: $("#password1").val(),
					phoneNumber: $("#phone").val(),
				},
				dataType:"json",
				success: function(data){
					alert("恭喜你，注册成功！赶快去登录吧！");
					window.location.href="homePage.jsp?"+data.result;
				},
				error: function(){
					alert("注册请求失败，请稍后再试！");
				}
			});
		}else{
			return;
		}
	});
}
