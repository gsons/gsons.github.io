/*主页的js文件配置*/
$(function(){
	
	/*标签js******************************************************************************************/
	$(".temp").hover(function(){
		$(".u-indexnavcatedialog ").stop().slideDown(500);
	}, function(){
		$(".u-indexnavcatedialog ").stop().slideUp(500);
	});
	
	/*滑动页面JS开始**********************************************************************************/
	var Index = 0; //当前的index
	var imgLen = $("#slide_list a").length; //图片的总数
	$("#slide_list a:not(:first-child)").hide();
	
	//自动切换定时器处理
	var autoChange=setInterval(function(){
		if( Index < imgLen-1 ){
			Index++;
		}else{
			Index = 0;
		}
		changeTo(Index);
	}, 4000);
	
	//图片切换的处理函数*****************************************************************************
	function changeTo(Index){
		$("#slide_list a").filter(":visible").stop().fadeOut(500).parent().children().eq(Index).stop().fadeIn(1000);
		$(".indexList").find("li").removeClass("indexOn").eq(Index).addClass("indexOn");
	}
	
//	//左箭头悬浮
//	$(".m-slide .lbtn").hover(function(){
//		//滑入清除定时器
//		clearInterval(autoChange);
//	}, function(){
//		//滑出重置定时器
//		autoChangeAgain();
//	});
//	//左箭头点击事件
//	$(".m-slide .lbtn").click(function(){
//		Index = Index>0 ?(--Index): (imgLen-1);
//		changeTo(Index);
//	});
//	
//	//右箭头悬浮
//	$(".m-slide .rbtn").hover(function(){
//		//滑入清除定时器
//		clearInterval(autoChange);
//	}, function(){
//		//滑出重置定时器
//		autoChangeAgain();
//	});
//	//右箭头点击事件
//	$(".m-slide .rbtn").click(function(){
//		Index = Index<imgLen-1 ?(++Index): 0;
//		changeTo(Index);
//	});
	
	//autoChangeAgain函数是重置定时器的函数
	function autoChangeAgain(){
		autoChange=setInterval(function(){
			if( Index < imgLen-1 ){
				Index++;
			}else{
				Index = 0;
			}
			changeTo(Index);
		}, 4000);
	}
	
	//右下角li按钮事件处理{
	$(".indexList").find("li").each(function(item){
		$(this).hover(function(){
			clearInterval(autoChange);
			changeTo(item);
			Index = item;
			}, function(){
			autoChangeAgain();
		});
	});
	/*滑动页面JS结束**********************************************************************************/
	
	
	/*搜索框事件开始*********************************************************************************/
	$(".input_search").focus(function(){
		$(".m-indextopnav .topnav .search .box label").stop().fadeOut(100);
	});
	
	$(".input_search").blur(function(){
		if( $(".input_search").val()==""){
			$(".m-indextopnav .topnav .search .box label").stop().fadeIn(100);
		}
	});
	
	/*商品页面js开始*********************************************************************************/
	
	var liLen = $("#live_home .m-cate ul li").length;
	var i = 0;
	for( i = 0; i < liLen; i++ ){
		//家居
		$("#live_home .m-cate ul li").eq(i).children().hover(function(){
			$(this).find(".desc").stop().slideUp();
			$(this).find(".buy").stop().fadeIn();
		}, function(){
			$(this).find(".buy").stop().fadeOut();
			$(this).find(".desc").stop().slideDown();
		});
		//床品
		$("#bed .m-cate ul li").eq(i).children().hover(function(){
			$(this).find(".desc").stop().slideUp();
			$(this).find(".buy").stop().fadeIn();
		}, function(){
			$(this).find(".buy").stop().fadeOut();
			$(this).find(".desc").stop().slideDown();
		});
		//穿搭
		$("#clothes .m-cate ul li").eq(i).children().hover(function(){
			$(this).find(".desc").stop().slideUp();
			$(this).find(".buy").stop().fadeIn();
		}, function(){
			$(this).find(".buy").stop().fadeOut();
			$(this).find(".desc").stop().slideDown();
		});
	}
	
	
	/*加入购物车动态效果**************************************************************/
	//获得购物车的位置
	var offset = $("#end").offset();
	$(".JoinInCar").click(function(event){
//		$(".joinSuccess").stop().show().animate({width:'150px'}, 200).fadeOut(1000);
		var addcar = $(this);
		var img = addcar.parent().parent().parent().find('img').attr('src');
//		alert(offset.left+", "+offset.top+"----"+event.pageX+", "+event.pageY);
//		alert(img);
		var flyer = $('<img class="u-flyer" src="'+img+'">');
		flyer.fly({
			start: {
				left: event.pageX-10,  //开始坐标
				top: event.pageY-$(window).scroll().scrollTop()-40
			},
			end: {
				left: offset.left+10, //结束坐标
				top: offset.top+10,
				width: 0,
				height: 0
			},
			onEnd: function(){
				$(".joinSuccess").stop().show().animate({width: '150px'}, 200).fadeOut(1000);
				this.destory();
			}
		});
	});
});
