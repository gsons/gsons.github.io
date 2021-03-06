$(document).ready(function(){
	var totalpage=6;//总页码数
	var p1=initpage1();
	var musicaudio=$("audio")[0];
	//显示page1
	p1.show();
	//适配手机
    adapt(412,100);

	$("#audio").click(function(){
	$(this).addClass("musicplay");
	if(musicaudio.paused){
		musicaudio.play();
		$(this).addClass("musicplay");
	}
	else{
		musicaudio.pause();
		$(this).removeClass("musicplay");
	}
});
//当前页码
var currentpage=1;

$("#prev").hide();
$("#next").click(function(){
	$("#prev").show();
	if(currentpage<totalpage) {
		$("#page"+currentpage).hide();
		currentpage++;
		if(currentpage==totalpage) {
			$("#prev").hide();
			$("#next").hide();
		}
		switch(currentpage){
			case 2:
			 initpage2();
			break;
			case 3:
			 initpage3();
			break;
			case 4:
			 initpage4();
			break;
			case 5:
			 initpage5();
			break;
			case 6:
			 initpage6();
			break;
		}
		$("#page"+currentpage).show();
		if(currentpage==2)
		$("#page"+currentpage).addClass("flippage-normalTranslateInUp");
	    else if(currentpage==3)
		$("#page"+currentpage).addClass("rotate-right");
	    else if(currentpage==4)
		$("#page"+currentpage).addClass("flippage-cutCard-top-upward ");
	      else if(currentpage==5)
		$("#page"+currentpage).addClass("rotate-right");
	     else if(currentpage==6)
		$("#page"+currentpage).addClass("flippage-fadeIn");
	}

});

$("#prev").click(function(){
	PlayLoop=false;
	 $("#myfireworks").html("");
        $("#myfireworks").remove();

	$("#next").show();
	if(currentpage>1) {
		$("#page"+currentpage).hide();
		currentpage--;
		if(currentpage==1) $("#prev").hide();
			switch(currentpage){
			case 1:
			 initpage1();
			break;
			case 2:
			 initpage2();
			break;
			case 3:
			 initpage3();
			break;
			case 4:
			 initpage4();
			break;
			case 5:
			 initpage5();
			break;
		}
		$("#page"+currentpage).show();
		if(currentpage==2)
		$("#page"+currentpage).addClass("flippage-normalTranslateInUp");
	    else if(currentpage==3)
		$("#page"+currentpage).addClass("rotate-right");
	    else if(currentpage==4)
		$("#page"+currentpage).addClass("flippage-cutCard-top-upward ");
	 else if(currentpage==5)
		$("#page"+currentpage).addClass("rotate-right");
	}
	});
	});


	/**
	 * 初始化page1
	 * @return {[void]} [description]
	 */
	function initpage1(){
	var text_1=new Item("text_jin","./image/text_jin.png",176,189,100,120,"zoomInDown 1.2s",0);
	var text_2=new Item("text_ji","./image/text_ji.png",87,123,180,270,"zoomInUp 1.2s",0);
	var text_3=new Item("text_he","./image/text_he.png",148,211,80,320,"zoomInUp 1.2s",0);
	var text_4=new Item("text_sui","./image/text_sui.png",235,272,100,380,"scaleInCenter 1s",0);
	var cloud1=new Item("cloud1","./image/cloud1.png",162,120,0,280,"zoomInUp 2.5s linear",0);
	var cloud2=new Item("cloud2","./image/cloud2.png",162,80,300,500,"cloud_2 1.5s linear",0);
      var bg= new BgItem("bg","./image/p1_bg.jpg","120%","",0);
       var snow= new BgItem("snow","./image/snow.gif","100%","",1);
        var p1=new Page("page1");$("#"+p1.id).html("");
     p1.addBgItem(bg);
     p1.addBgItem(snow);
      var lantern=[];
		for(var i=0;i<5;i++){
			lantern[i]=new Item("lantern"+i,"./image/lantern.png",65,62,i*80,10,"swingLR 5s linear infinite",0);
			p1.addElement(lantern[i]);
		}
		setTimeout(
			function(){
				$("#page1 .cloud2").css("animation","shake 8.5s linear infinite");
			}
			,1500);
	 var items=[text_1,text_2,text_3,text_4];//定义item数组
	  var times=[1000,400,400,400];//定义时间间隔数组
	 sequence_active(p1,items,times,0);
	 p1.addElement(cloud1);
	 p1.addElement(cloud2);
	 return p1;
	 }
	 /**
	 * 初始化page2
	 * @return {[void]} [description]
	 */
	  function initpage2(){
	var lantern=new Item("lantern","./image/p1_lantern.png",150,390,135,0,"'' .2s linear",0);
    var bg= new BgItem("bg","./image/p3_bg.jpg","120%","",0)
    var snow= new BgItem("snow","./image/snow.gif","120%","",1);
    var imooc=new Item("imooc","./image/p2_imooc.png",89,106,165,500," rubberJelly 2s linear 1s",0);
    var p2=new Page("page2");$("#"+p2.id).html("");
     p2.addBgItem(bg);
     p2.addBgItem(snow);
      p2.addElement(imooc);
       p2.addElement(lantern);
	 return $("#"+p2.id);
	 }

	 /**
	  * 初始化page3
	  * @return {[type]} [description]
	  */
	function initpage3(){
	var text_1=new Item("text_jin","./image/text_jin.png",176,189,100,100,"zoomInDown 1.4s",0);
	var text_2=new Item("text_ji","./image/text_ji.png",87,123,180,250,"zoomInUp 1.4s",0);
	var text_3=new Item("text_he","./image/text_he.png",148,211,80,300,"zoomInUp 1.4s",0);
	var text_4=new Item("text_sui","./image/text_sui.png",235,272,100,360,"rollInDown 1.4s",0);
	var couplet1=new Item("couplet1","./image/couplet.png",130,500,0,100,"rotateInRight .5s",0);
	var couplet2=new Item("couplet2","./image/couplet.png",130,500,280,100,"rotateInLeft .5s",0);
	var couplet_1=new Item("couplet_1","./image/couplet_1.png",57,460,40,115,"zoomInUp .5s linear",0);
	var couplet_2=new Item("couplet_2","./image/couplet_2.png",57,460,320,115,"zoomInUp .5s linear",0);
     var bg= new BgItem("bg","./image/p3_bg.jpg","120%","",0)
    var snow= new BgItem("snow","./image/snow.gif","120%","",1);
    var p3=new Page("page3");$("#"+p3.id).html("");
     p3.addBgItem(bg);
     p3.addBgItem(snow);
	  var items=[text_1,text_2,text_3,text_4,couplet1,couplet_1,couplet2,couplet_2];//定义item数组
	  var times=[1400,300,300,300,600,600,600,600];//定义时间间隔数组
	 sequence_active(p3,items,times,0);
	 return $("#"+p3.id);
	 }

	 /**
	  * 初始化page3
	  * @return {[type]} [description]
	  */
	function initpage4(){
	var text_1=new Item("p4_happy","./image/happy.png",400,80,10,90,"zoomInDown 1.2s",0);
	var text_2=new Item("spring","./image/spring.png",188,36,120,200,"zoomInUp 1.4s",0);
	var blessing=new Item("blessing","./image/p3_blessing.png",130,130,150,250,"rotate 1.4s linear infinite",0);
	var people=new Item("people","./image/people.png",400,390,0,screen.height-screen.width*400/412,"zoomInUp 1.4s",0);
      var bg= new BgItem("bg","./image/p1_bg.jpg","120%","",0);
       var snow= new BgItem("snow","./image/snow.gif","100%","",1);
        var p4=new Page("page4");$("#"+p4.id).html("");
     p4.addBgItem(bg);
     p4.addBgItem(snow);
      var lantern=[];
		for(var i=0;i<5;i++){
			lantern[i]=new Item("lantern"+i,"./image/lantern.png",65,62,i*80,10,"swingLR 5s linear infinite",0);
			p4.addElement(lantern[i]);
		}
	 p4.addElement(text_1);
	 p4.addElement(text_2);
	  p4.addElement(people);
	   p4.addElement(blessing);
	  $(".people").css({"top":"","bottom":"0rem"});
	 return $("#"+p4.id);
	 }	

	 /**
	  * [initpage5 description]
	  * @return {[type]} [description]
	  */
	 	function initpage5(){
	var car_l=new Item("car_l","./image/p5_car_l.png",436,330,300,300,"car_l 2.5s forwards",21);
	var car_r=new Item("car_r","./image/p5_car_r.png",189,141,-200,200,"car_r 2s linear forwards",0);
	var p5_home=new Item("p5_home","./image/p5_home.png",400,200,0,540,"p5_home 6s linear forwards",22);
      var bg= new BgItem("bg","./image/p5_bg.jpg","130%","",0);
       var snow= new BgItem("snow","./image/snow.gif","100%","",1);
        var p5=new Page("page5");
        	$("#"+p5.id).html("");
     p5.addBgItem(bg);
     p5.addBgItem(snow);
      var p5_fire=[];
		for(var i=0;i<12;i++){
			p5_fire[i]=new Item("p5_fire"+i,"./image/p5_fire.png",46,33,412*Math.random(),250*Math.random(),"fire  1s linear "+5*Math.random()+"s infinite",0);
			p5.addElement(p5_fire[i]);
		}
		p5.addElement(p5_home);
		 $(".p5_home").css({"top":"","bottom":"0rem"});
		 $(".fire_container").css({"top":"","bottom":"0rem"});
      var items=[car_l,car_r];
      var times=[6500,1000];
      sequence_active(p5,items,times,0);
	 return $("#"+p5.id);
	 }
	 	 /**
	  * [initpage6 description]
	  * @return {[type]} [description]
	  */
	 	function initpage6(){
	var p6_home=new Item("p6_home","./image/p5_home.png",400,200,0,540,"",22);
	 var bg= new BgItem("bg","./image/p5_bg.jpg","130%","",0);
        var p6=new Page("page6");
       $("#myfireworks").html("");
        $("#myfireworks").remove();
		p6.addElement(p6_home);
		p6.addBgItem(bg)	;
		$(".p6_home").css({"top":"","bottom":"0rem"});
		$("#page6").append("<div id='myfireworks'></div>  ");
		PlayLoop=true;
			setTimeout(function(){ 
		$('#myfireworks').fireworks({ 
		  sound: true, // 声音效果
		  opacity: 0,
		  width: '100%', 
		  height: '100%',
		}); 
      },1200);
	    return $("#"+p6.id);
	 }
	/**
	 * 页面构造函数
	 * @param {[string]} id    [页面id]
	 * @param {[string]} bgsrc [页面背景路径]
	 */
	function Page(id){
		this.id=id;
		/**
		 * [往body里添加页面]
		 * 
		 */
		$("#"+this.id).empty();
		$("#"+this.id).remove();
		var page="<div class='page' id='"+id+"'></div>";
		$("body").append(page);
		
		this.addBgItem=function(bgitem){
	    $("#"+this.id).append("<div class='"+bgitem.mclass+"'></div>");
		$("#"+this.id+" ."+bgitem.mclass).css("position","absolute");
		$("#"+this.id+" ."+bgitem.mclass).css("z-index",bgitem.z_index);
		$("#"+this.id+" ."+bgitem.mclass).css("width","100%");
		$("#"+this.id+" ."+bgitem.mclass).css("height","100%");
		$("#"+this.id+" ."+bgitem.mclass).css("background","url('"+bgitem.imgsrc+"') no-repeat center center");
		$("#"+this.id+" ."+bgitem.mclass).css("background-size",bgitem.bgsize);
		if(bgitem.animation!="")$("#"+this.id+" ."+bgitem.mclass).css("animation",bgitem.animation);
		}
		/**
		 * 添加精灵
		 * @param {[type]} item [精灵]
		 */
		this.addElement=function (item){
		$("#"+this.id).append("<div class='"+item.mclass+"'></div>");
		$("#"+this.id+" ."+item.mclass).css("position","absolute");
		$("#"+this.id+" ."+item.mclass).css("z-index",item.z_index);
		$("#"+this.id+" ."+item.mclass).css("left",item.left+"rem");
		$("#"+this.id+" ."+item.mclass).css("top",item.top+"rem");
		$("#"+this.id+" ."+item.mclass).css("width",item.width+"rem");
		$("#"+this.id+" ."+item.mclass).css("height",item.height+"rem");
		$("#"+this.id+" ."+item.mclass).css("background","url('"+item.imgsrc+"') no-repeat center center");
		$("#"+this.id+" ."+item.mclass).css("background-size","100%");
		//$("#"+this.id+" ."+item.mclass).hide();
		if(item.animation!="")$("#"+this.id+" ."+item.mclass).css("animation",item.animation);
	 }

	//显示页面
   this.show=function(){ 
   	$("#"+this.id).show(); 	
   	}


   
   //隐藏页面
   this.hide=function(){
   	 $("#"+this.id).hide();
   }

    this.hide();
	}

	/**
	 * 背景精灵构造函数
	 * @param {[string]} mclass    [类名]
	 * @param {[string]} imgsrc    [图像路径]
	 * @param {[string]} bgsize    [backagesize]
	 * @param {[string]} animation [动画class]
	 * @param {[int]} z_index   [层级]
	 */
	function BgItem(mclass,imgsrc,bgsize,animation,z_index){
		this.imgsrc=imgsrc;
		this.mclass=mclass;
		this.bgsize=bgsize;
		this.animation=animation;
		this.z_index=z_index;
	}
	/**
	 * 精灵构造函数
	 * @param {[string]} mclass    [类名]
	 * @param {[string]} imgsrc    [图片路径]
	 * @param {[int]} width         [宽]
	 * @param {[int]} height         [高]
	 * @param {[int]} left      [左边距]
	 * @param {[int]} top       [上边距]
	 * @param {[string]} animation [动画]
	 * @param {[int]} z_index [层级]
	 */
	function Item(mclass,imgsrc,width,height,left,top,animation,z_index){		
		this.imgsrc=imgsrc;
		this.mclass=mclass;
		this.width=width/100;
		this.height=height/100;
		this.left=left/100;
		this.top=top/100;
		this.animation=animation;
		this.z_index=z_index;
	}
	/**
	 * 串行动作
	 * @param  {[page]} page  [页面对象]
	 * @param  {[array]} items [item数组]
	 * @param  {Number} i     [时间间隔]
	 * @return {[array]}       [遍历数组]
	 */
	function sequence_active(page,items,times,i){
	if(i==items.length) {i=0;return;}
	setTimeout(function(){
		page.addElement(items[i]);sequence_active(page,items,times,i+1);
	},times[i]);
   }

   // 『REM』手机屏幕适配，兼容更改过默认字体大小的安卓用户
function adapt(designWidth, rem2px) { 
//  designWidth：‘设计图宽度‘   1rem==rem2px+‘px‘                     
    var d = window.document.createElement('div');
    d.style.width = '1rem';
    d.style.display = "none";
    var head = window.document.getElementsByTagName('head')[0];
    head.appendChild(d);
    var defaultFontSize = parseFloat(window.getComputedStyle(d, null).getPropertyValue('width'));
    d.remove();
    document.documentElement.style.fontSize = window.innerWidth / designWidth * rem2px / defaultFontSize * 100 + '%';
    var st = document.createElement('style');
// 适应横屏、竖屏
    var portrait = "@media screen and (min-width: " + window.innerWidth + "px) {html{font-size:" + ((window.innerWidth / (designWidth / rem2px) / defaultFontSize) * 100) + "%;}}";
    var landscape = "@media screen and (min-width: " + window.innerHeight + "px) {html{font-size:" + ((window.innerHeight / (designWidth / rem2px) / defaultFontSize) * 100) + "%;}}"
    st.innerHTML = portrait + landscape;
    head.appendChild(st);
    return defaultFontSize
}
