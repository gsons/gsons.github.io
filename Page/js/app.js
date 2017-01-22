$(document).ready(function(){
	var p1=initpage1();
	var p2=initpage2();
	var p3=initpage3();
	var p4=initpage4();
	var audio=$("audio")[0];
	p1.show();
/*	$("#audio").click(function(){
	$(this).addClass("musicplay");
	if(audio.paused){
		audio.play();
		$(this).addClass("musicplay");
	}
	else{
		audio.pause();
		$(this).removeClass("musicplay");
	}
});*/
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
	p1.click(function(){
		p1.hide();
		p2.show();
		p2.addClass("flippage-cutCard-top-upward ");
	});
	p2.click(function(){
	p2.hide();
	p3.show();
	p3.addClass("flippage-cutCard-top-upward ");
});
	p3.click(function(){
	p3.hide();
	p4.show();
	p4.addClass("flippage-cutCard-top-upward ");
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
	var cloud1=new Item("cloud1","./image/cloud1.png",162,80,0,280,"shake 7.5s linear infinite .3s",0);
	var cloud2=new Item("cloud2","./image/cloud2.png",162,80,300,580,"shake 7.5s linear infinite .3s",0);
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
	 p1.addElement(text_1);
	 p1.addElement(text_2);
	 p1.addElement(text_3);
	 p1.addElement(text_4);
	 p1.addElement(cloud1);
	 p1.addElement(cloud2);
	 return $("#"+p1.id);
	 }
	 /**
	 * 初始化page2
	 * @return {[void]} [description]
	 */
	  function initpage2(){
	var text_1=new Item("text_jin","./image/text_jin.png",176,189,100,120,"zoomInDown 1.2s",0);
	var text_2=new Item("text_ji","./image/text_ji.png",87,123,180,270,"zoomInUp 1.4s",0);
	var text_3=new Item("text_he","./image/text_he.png",148,211,80,320,"zoomInUp 1.6s",0);
	var text_4=new Item("text_sui","./image/text_sui.png",235,272,100,380,"scaleInCenter .5s",0);
	var text_4=new Item("text_sui","./image/text_sui.png",235,272,100,380,"scaleInCenter .5s",0);
	var couplet1=new Item("couplet1","./image/couplet.png",130,480,0,130,"scaleInCenter .5s",0);
	var couplet2=new Item("couplet2","./image/couplet.png",130,480,280,130,"scaleInCenter .5s",0);
	var couplet_1=new Item("couplet_1","./image/couplet_1.png",57,480,40,130,"scaleInCenter .5s linear 1s",0);
	var couplet_2=new Item("couplet_2","./image/couplet_2.png",57,480,320,130,"scaleInCenter .5s linear 1s",0);
     var bg= new BgItem("bg","./image/p2_bg.jpg","120%","",0)
        var snow= new BgItem("snow","./image/snow.gif","120%","",1);
        var p2=new Page("page2");
     p2.addBgItem(bg);
     p2.addBgItem(snow);
	 p2.addElement(text_1);
	 p2.addElement(text_2);
	 p2.addElement(text_3);
	 p2.addElement(text_4);
	 p2.addElement(couplet1);
	 p2.addElement(couplet2);
	  p2.addElement(couplet_1);
	  p2.addElement(couplet_2);
	 return $("#"+p2.id);
	 }

	function initpage3(){
	var text_1=new Item("text_jin","./image/text_jin.png",176,189,100,120,"zoomInDown 1.2s",0);
	var text_2=new Item("text_ji","./image/text_ji.png",87,123,180,270,"zoomInUp 1.4s",0);
	var text_3=new Item("text_he","./image/text_he.png",148,211,80,320,"zoomInUp 1.6s",0);
	var text_4=new Item("text_sui","./image/text_sui.png",235,272,100,380,"scaleInCenter .5s",0);
	var text_4=new Item("text_sui","./image/text_sui.png",235,272,100,380,"scaleInCenter .5s",0);
	var couplet1=new Item("couplet1","./image/couplet.png",130,480,0,130,"scaleInCenter .5s",0);
	var couplet2=new Item("couplet2","./image/couplet.png",130,480,280,130,"scaleInCenter .5s",0);
	var couplet_1=new Item("couplet_1","./image/couplet_1.png",57,480,40,130,"scaleInCenter .5s linear",0);
	var couplet_2=new Item("couplet_2","./image/couplet_2.png",57,480,320,130,"scaleInCenter .5s linear",0);
	var blessing=new Item("blessing","./image/p3_blessing.png",120,120,135,600,"rotate 2.5s linear infinite",2);
     var bg= new BgItem("bg","./image/p3_bg.jpg","120%","",0)
    var snow= new BgItem("snow","./image/snow.gif","120%","",1);
    var p3=new Page("page3");
     p3.addBgItem(bg);
     p3.addBgItem(snow);
	 p3.addElement(text_1);
	 p3.addElement(text_2);
	 p3.addElement(text_3);
	 p3.addElement(text_4);
	 p3.addElement(couplet1);
	 p3.addElement(couplet2);
	  p3.addElement(couplet_1);
	   p3.addElement(blessing);
	   p3.addElement(couplet_2);
	 return $("#"+p3.id);
	 }
	function initpage4(){
	var text_1=new Item("p4_happy","./image/happy.png",283,58,70,120,"zoomInDown 1.2s",0);
	var text_2=new Item("spring","./image/spring.png",188,36,100,200,"zoomInUp 1.4s",0);
	var people=new Item("people","./image/people.png",400,390,0,340,"zoomInUp 1.4s",0);
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
	 return $("#"+p4.id);
	 }
	 function addFixedItem(){
	 	
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
		$("#"+this.id+" ."+item.mclass).css("left",item.left+"px");
		$("#"+this.id+" ."+item.mclass).css("top",item.top+"px");
		$("#"+this.id+" ."+item.mclass).css("width",item.width+"px");
		$("#"+this.id+" ."+item.mclass).css("height",item.height+"px");
		$("#"+this.id+" ."+item.mclass).css("background","url('"+item.imgsrc+"') no-repeat center center");
		$("#"+this.id+" ."+item.mclass).css("background-size","100%");
		if(item.animation!="")$("#"+this.id+" ."+item.mclass).css("animation",item.animation);
	}
	   this.show=function(){
	   	 $("#"+this.id).show();
	   }
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
		this.width=width;
		this.height=height;
		this.left=left;
		this.top=top;
		this.animation=animation;
		this.z_index=z_index;
	}
});
