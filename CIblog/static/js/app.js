$("document").ready(function(){
	app_adapter();
     showUserFormInfo();
	var isshowMenu=false;
	$("#extensiontool").click(
		function(){
			$("#extensiontool-content").toggle();
			if(isshowMenu){$("#icon-down").css({"transform":"rotate(180deg)"});isshowMenu=false}
			else{$("#icon-down").css({"transform":"rotate(0deg)"});isshowMenu=true}
		});

	var is_sidebar=true;
	$("#sidebartoggle").click(
		function(){
			if($(window).width() >= 1024){
				if(is_sidebar){
						$('.sidebar').css({"left":"-240px"});
    	            $('.m-content').css({"margin-left":"0px"});
    	            is_sidebar=false;
				}
				else{
					$('.sidebar').css({"left":"0px"});
               $('.m-content').css({"margin-left":"240px"});
               is_sidebar=true;
				}
			}
		});

	$(window).resize(function() {
		app_adapter();
    });


});
function app_adapter() {
	if ($(window).width() >= 1024) {
		$('.sidebar').css({"left":"0px"});
		$('.m-content').css({"margin-left":"240px"});
	}
	else{
		$('.sidebar').css({"left":"-240px"});
		$('.m-content').css({"margin-left":"0px"});
	}
}

function showUserFormInfo(){
	var newpass;
	var is_post1=false;
	var is_post2=false;
	var is_post3=false;
	$("#ci_password").keyup(
		function(){
			if($(this).val().length<6||$(this).val().length>12)
			{$("#ci_password").siblings().html("<b class='text-danger'>*密码需6-12个字符*</b>");}
		else{
			is_post1=true;
			$("#ci_password").siblings().html("");
		}
		});
	$("#ci_newpassword").keyup(
		function(){
			if($(this).val().length<6||$(this).val().length>12)
			{$("#ci_newpassword").siblings().html("<b class='text-danger'>*密码需6-12个字符*</b>");}
		else{
			newpass=$(this).val();
			is_post2=true;
			$("#ci_newpassword").siblings().html("");
		}
		});
	$("#ci_renewpassword").keyup(
		function(){
			if($(this).val().length<6||$(this).val().length>12)
			{$("#ci_renewpassword").siblings().html("<b class='text-danger'>*密码需6-12个字符*</b>");}
		   else if($(this).val()!=newpass){
       $("#ci_renewpassword").siblings().html("<b class='text-danger'>*密码不一致*</b>");
		   }
		 else{
		 	is_post3=true;
			$("#ci_renewpassword").siblings().html("");
		 }
		});
	$("#submit_user_set").submit(function(){
  return is_post1&&is_post2&&is_post3;
	});
	
} 