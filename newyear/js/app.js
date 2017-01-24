
$(document).ready(function(){
	var audio=$("audio")[0];
	//显示page1
	$("#page1").show();
	//适配手机
	 var fontsize=screen.width*100/412;
	$("html").css("font-size",fontsize+"px");
	
	$("#audio").click(function(){
	$(this).addClass("musicplay");
	if(audio.paused){
		audio.play();
		$(this).addClass("musicplay");
	}
	else{
		audio.pause();
		$(this).removeClass("musicplay");
	}
});
//当前页码
var currentpage=1;

$("#prev").hide();
$("#next").click(function(){
	$("#prev").show();
	if(currentpage<4) {
		$("#page"+currentpage).hide();
		currentpage++;
		if(currentpage==4) $("#next").hide();
		$("#page"+currentpage).show();
		if(currentpage==2)
		$("#page"+currentpage).addClass("flippage-normalTranslateInUp");
	    else if(currentpage==3)
		$("#page"+currentpage).addClass("rotate-right");
	    else if(currentpage==4)
		$("#page"+currentpage).addClass("flippage-cutCard-top-upward ");
	}

});

$("#prev").click(function(){
	$("#next").show();
	if(currentpage>1) {
		$("#page"+currentpage).hide();
		currentpage--;
		if(currentpage==1) $("#prev").hide();	
		$("#page"+currentpage).show();
		if(currentpage==2)
		$("#page"+currentpage).addClass("flippage-normalTranslateInUp");
	    else if(currentpage==3)
		$("#page"+currentpage).addClass("rotate-right");
	    else if(currentpage==4)
		$("#page"+currentpage).addClass("flippage-cutCard-top-upward ");
	}
});
	
});


