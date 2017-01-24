
$(document).ready(function(){
	var p1=initpage1();
	var audio=$("audio")[0];
	//显示page1
	p1.show();
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
		}
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
		}
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

	/**
	 * 初始化page1
	 * @return {[void]} [description]
	 */
	function initpage1(){
	var text_1=new Item("text_jin","./image/text_jin.png",176,189,100,120,"zoomInDown 1.2s",0);
	var text_2=new Item("text_ji","./image/text_ji.png",87,123,180,270,"zoomInUp 1.4s",0);
	var text_3=new Item("text_he","./image/text_he.png",148,211,80,320,"zoomInUp 1.6s",0);
	var text_4=new Item("text_sui","./image/text_sui.png",235,272,100,380,"scaleInCenter .9s",0);
	var cloud1=new Item("cloud1","./image/cloud1.png",162,80,0,280,"shake 8.5s linear infinite .3s",0);
	var cloud2=new Item("cloud2","./image/cloud2.png",162,80,300,550,"shake 8.5s linear infinite .3s",0);
      var bg= new BgItem("bg","./image/p1_bg.jpg","120%","",0);
       var snow= new BgItem("snow","./image/snow.gif","100%","",1);
        var p1=new Page("page1");
     p1.addBgItem(bg);
     p1.addBgItem(snow);
      var lantern=[];
		for(var i=0;i<5;i++){
			lantern[i]=new Item("lantern"+i,"./image/lantern.png",65,62,i*80,10,"swingLR 5s linear infinite",0);
			p1.addElement(lantern[i]);
		}
	 var items=[text_1,text_2,text_3,text_4];//定义item数组
	  var times=[0,300,300,300];//定义时间间隔数组
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
    var p2=new Page("page2");
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
	var couplet_1=new Item("couplet_1","./image/couplet_1.png",57,460,40,115,"scaleInCenter .5s linear",0);
	var couplet_2=new Item("couplet_2","./image/couplet_2.png",57,460,320,115,"scaleInCenter .5s linear",0);
     var bg= new BgItem("bg","./image/p3_bg.jpg","120%","",0)
    var snow= new BgItem("snow","./image/snow.gif","120%","",1);
    var p3=new Page("page3");
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
	var text_1=new Item("p4_happy","./image/happy.png",283,58,70,120,"zoomInDown 1.2s",0);
	var text_2=new Item("spring","./image/spring.png",188,36,100,200,"zoomInUp 1.4s",0);
	var blessing=new Item("blessing","./image/p3_blessing.png",130,130,150,250,"rotate 1.4s linear infinite",0);
	var people=new Item("people","./image/people.png",400,390,0,screen.height-screen.width*400/412,"zoomInUp 1.4s",0);
      var bg= new BgItem("bg","./image/p1_bg.jpg","120%","",0);
       var snow= new BgItem("snow","./image/snow.gif","100%","",1);
        var p4=new Page("page4");
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
	  $(".people").css({"top":"","bottom":"-0.2rem"});
	 return $("#"+p4.id);
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

