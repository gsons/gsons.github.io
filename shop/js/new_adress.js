$(function(){
	var nump = null;
	var numc = null;
	//弹出地址管理框
	$("#new-address").click(function(){
		$("select").hide();
		$("#new-address-province, #new-address-city, #new-address-area").show();
		
		$("#new-address-province").change(function(){
			var adrp = $(this);
			nump= adrp.val();
			$(".new-address-city,.new-address-area").hide();
			$("#" + nump).show();
			$("#new-address-area").show();
		});
		
		$(".new-address-city").change(function(){
			var adrc = $(this);
			numc= adrc.val();
			$(".new-address-area").hide();
			$("#" + numc).show();
		});
		$("body").append("<div id='new-address-back'></div>")
		$("#new-address-back").addClass("new-address-back").fadeIn();
		$("#new-address-box").fadeIn();
	}).hover(function(){
		$("#new-address").css({text: 'underline'});
	});
	
	
	//退出头像设置框按钮
	$("#new-address-close").hover(function(){
		$("#new-address-close").stop().css({color: 'green'});
	},function(){
		$("#new-address-close").stop().css({color: 'gray'});
	}).click(function(){
		$("select").show();
		$("#new-address-box").fadeOut('fast');
		$("#new-address-back").fadeOut('fast');
	});
	
	//确认和取消按钮
	$("#new-address-sure").hover(function(){
		$("#new-address-sure").stop().css({background: 'red'});
	},function(){
		$("#new-address-sure").stop().css({background: '#ab2b2b'});
	}).click(function(){
		var name = $("#new-address-people").val();
		var phone = $("#new-address-phone").val();
		var province = $("#new-address-province option:selected").text();
		var city = $("#" + nump + ' option:selected').text();
		var area = $("#" + numc + ' option:selected').text();
		var detailed = $("#new-address-detailed").val();
		$.ajax({
			url:"newaddressServlet",
			data:{
				peoplename: name,
				peoplephone: phone,
				peopleprovince: province,
				peoplecity: city,
				peoplearea: area,
				peopledetailed: detailed,
			},
			type:"post",
			dataType:"json",
			success:function(data){
				
			},
			error:function(){
				 alert("系统升级中，请稍后再试"); 
		}
		
	}) 
	});
	
	$("#new-address-cancel").hover(function(){
		$("#new-address-cancel").stop().css({background: 'white'});
	},function(){
		$("#new-address-cancel").stop().css({background: '#f5f5f5'});
	});	
})