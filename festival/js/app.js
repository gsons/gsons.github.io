$(document).ready(function(){
	var totalpage=6;//总页码数
    var currentpage=1;//当前页码
	var p1=new Page1();
	var p2=new Page2();
	var p3=new Page3();
	var p4=new Page4();
	var p5=new Page5();
	var p6=new Page6();
	var musicaudio=$("audio")[0];

    //显示p1
    p1.show();
    /** 音乐控制 */
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

//当前页为1 隐藏上一页按钮
$("#prev").hide();

/*点击下一页*/
$("#next").click(function(){
	$("#prev").show();
	if(currentpage<totalpage) {
		$("#page"+currentpage).hide();
		$("#page"+currentpage).children().hide();
		currentpage++;
		if(currentpage==totalpage) {
			$("#prev").hide();
			$("#next").hide();
		}
	
		/*设置页面的翻页动画*/
		if(currentpage==2)
		{p2.show();$("#page"+currentpage).addClass("flippage-normalTranslateInUp");}
	    else if(currentpage==3)
		{p3.show();$("#page"+currentpage).addClass("rotate-right");}
	    else if(currentpage==4)
		{p4.show();$("#page"+currentpage).addClass("flippage-cutCard-top-upward ");}
	      else if(currentpage==5)
		{p5.show();$("#page"+currentpage).addClass("rotate-right");}
	     else if(currentpage==6)
		{p6.show();$("#page"+currentpage).addClass("flippage-fadeIn");}
	}

});

$("#prev").click(function(){
	$("#next").show();
	if(currentpage>1) {
		$("#page"+currentpage).hide();
		$("#page"+currentpage).children().hide();
		currentpage--;
		if(currentpage==1) {p1.show();$("#prev").hide();}
		
        /*设置页面的翻页动画*/
		else if(currentpage==2)
		{p2.show();$("#page"+currentpage).addClass("flippage-normalTranslateInUp");}
	    else if(currentpage==3)
		{p3.show();$("#page"+currentpage).addClass("rotate-right");}
	    else if(currentpage==4)
		{p4.show();$("#page"+currentpage).addClass("flippage-cutCard-top-upward ");}
	      else if(currentpage==5)
		{p5.show();$("#page"+currentpage).addClass("rotate-right");}
	}
	});
	});


	/**
	 * 初始化page1
	 * @return {[void]} [description]
	 */
	function Page1(){
	this.text_1=new Item("text_jin","./image/text_jin.png",176,189,100,120,"zoomInDown 1.2s",0);
	this.text_2=new Item("text_ji","./image/text_ji.png",87,123,180,270,"zoomInUp 1.2s",0);
	this.text_3=new Item("text_he","./image/text_he.png",148,211,80,320,"zoomInUp 1.2s",0);
	this.text_4=new Item("text_sui","./image/text_sui.png",235,272,100,380,"scaleInCenter 1s",0);
	this.cloud1=new Item("cloud1","./image/cloud1.png",162,120,0,280,"zoomInUp 2.5s linear",0);
	this.cloud2=new Item("cloud2","./image/cloud2.png",162,80,300,500,"cloud_2 1.5s linear",0);
    this.bg= new BgItem("bg","./image/p1_bg.jpg","120%","",0);
    this.snow= new BgItem("snow","./image/snow.gif","100%","",1);
    this.lantern=["lantern0","lantern1",,"lantern2","lantern3","lantern4"];
    this.show=function(){
    	$("#page1").children().hide();
    	var items=[this.text_1,this.text_2,this.text_3,this.text_4];
    	var mitems=[this.cloud1,this.cloud2,this.bg,this.snow];
    	var times=[1000,400,400,400];
    	$("#page1").show();
        for (var i in mitems) {
        	$("#page1"+" ."+mitems[i].mclass).show();
        }
        for(var i in this.lantern){
   
        	$("#page1"+" ."+this.lantern[i]).show();
        }
    	sequence_active("page1",items,times,0);
    }
	 }

	 /**
	 * 初始化page2
	 * @return {[void]} [description]
	 */
	  function Page2(){
	this.lantern=new Item("lantern","./image/p1_lantern.png",150,390,135,0,"'' .2s linear",0);
    this.bg= new BgItem("bg","./image/p3_bg.jpg","120%","",0)
    this.snow= new BgItem("snow","./image/snow.gif","120%","",1);
    this.imooc=new Item("imooc","./image/p2_imooc.png",89,106,165,500," rubberJelly 2s linear 1s",0);

    
    
    this.show=function(){
    	$("#page2").children().hide();
    	$("#page2").show();
    	var mitems=[this.bg,this.snow,this.lantern,this.imooc];
        for (var i in mitems) {
        	$("#page2"+" ."+mitems[i].mclass).show();
        }
    }

	 }

	 /**
	  * 初始化page3
	  * @return {[type]} [description]
	  */
	function Page3(){
	 this.text_1=new Item("text_jin","./image/text_jin.png",176,189,100,100,"zoomInDown 1.4s",0);
	 this.text_2=new Item("text_ji","./image/text_ji.png",87,123,180,250,"zoomInUp 1.4s",0);
	 this.text_3=new Item("text_he","./image/text_he.png",148,211,80,300,"zoomInUp 1.4s",0);
	 this.text_4=new Item("text_sui","./image/text_sui.png",235,272,100,360,"rollInDown 1.4s",0);
	this.couplet1=new Item("couplet1","./image/couplet.png",130,500,0,100,"rotateInRight .5s",0);
	this.couplet2=new Item("couplet2","./image/couplet.png",130,500,280,100,"rotateInLeft .5s",0);
	this.couplet_1=new Item("couplet_1","./image/couplet_1.png",57,460,40,115,"zoomInUp .5s linear",0);
	this.couplet_2=new Item("couplet_2","./image/couplet_2.png",57,460,320,115,"zoomInUp .5s linear",0);
     this.bg= new BgItem("bg","./image/p3_bg.jpg","120%","",0)
    this.snow= new BgItem("snow","./image/snow.gif","120%","",1);
	  this.show=function(){
	  	$("#page3").children().hide();
	  	$("#page3").show();
	  	$("#page3 .bg").show();
	  	 $("#page3 .snow").show();
    	 var items=[this.text_1,this.text_2,this.text_3,this.text_4,this.couplet1,this.couplet_1,this.couplet2,this.couplet_2];//定义item数组
	     var times=[1400,300,300,300,600,600,600,600];//定义时间间隔数组
	     sequence_active("page3",items,times,0);
    }

	 }

	 /**
	  * 初始化page3
	  * @return {[type]} [description]
	  */
	function Page4(){
	 this.text_1=new Item("p4_happy","./image/happy.png",400,80,10,90,"zoomInDown 1.2s",0);
	 this.text_2=new Item("spring","./image/spring.png",188,36,120,200,"zoomInUp 1.4s",0);
	 this.blessing=new Item("blessing","./image/p3_blessing.png",130,130,150,250,"rotate 1.4s linear infinite",0);
	 this.people=new Item("people","./image/people.png",400,390,0,screen.height-screen.width*400/412,"zoomInUp 1.4s",0);
       this.bg= new BgItem("bg","./image/p1_bg.jpg","120%","",0);
        this.snow= new BgItem("snow","./image/snow.gif","100%","",1);
        
      this.lantern=[];
  
      this.show=function(){
      	$("#page4").children().hide();
      	$("#page4 .people").css({"top":"","bottom":"0"});
      	 $("#page4").show();
      	 var mitems=[this.bg,this.snow,this.text_1,this.text_2,this.blessing,this.people];
      	  for (var i in mitems) {
        	$("#page4"+" ."+mitems[i].mclass).show();
        }
      	  for(var i in this.lantern){
        	$("#page4"+" ."+this.lantern[i].mclass).show();
        }
      }
		
	 }	

	 /**
	  * [initpage5 description]
	  * @return {[type]} [description]
	  */
	 	function Page5(){
	 this.car_l=new Item("car_l","./image/p5_car_l.png",436,330,300,300,"car_l 2.5s forwards",21);
	 this.car_r=new Item("car_r","./image/p5_car_r.png",189,141,-200,200,"car_r 2s linear forwards",0);
	 this.p5_home=new Item("p5_home","./image/p5_home.png",400,200,0,540,"p5_home 5s linear forwards",22);
       this.bg= new BgItem("bg","./image/p5_bg.jpg","130%","",0);
        this.snow= new BgItem("snow","./image/snow.gif","100%","",1);
         this.pg_fire=[];
       
     
      this.show=function(){
      	$("#page5").children().hide();
       	$("#page5").show();
       	$("#page5 .p5_home").css({"top":"","bottom":"0"});
       	var items=[this.car_l,this.car_r];
        var times=[4500,1000];
         sequence_active("page5",items,times,0);
        var mitems=[this.bg,this.snow,this.p5_home];
        for (var i in mitems) {
        	$("#page5"+" ."+mitems[i].mclass).show();
        }
        for(var i=0;i<12;i++){
        	$("#page5"+" .pg_fire"+i).show();
        }
       }
	 }
	  /**
	  * [initpage6 description]
	  * @return {[type]} [description]
	  */
	 	function Page6(){
	 this.p6_home=new Item("p6_home","./image/p5_home.png",400,200,0,540,"",22);
	  this.bg= new BgItem("bg","./image/p5_bg.jpg","130%","",0);
       
	 this.show=function(){
	 	$("#page6").children().hide();
	 	$("#page6").show();
	 	$("#page6 .bg").show();
	 	$("#page6 .p6_home").show();
	 	$("#myfireworks").show();
	 	setTimeout(function(){
	 			$("#myfireworks").fireworks({ 
		  sound: true, // 声音效果
		  opacity: 0,
		  width: '100%', 
		  height: '100%',
		}); 
	 		},1200);
      
	 }
		
	  
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
			$("#"+this.id+" ."+bgitem.mclass).hide();
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
		 $("#"+this.id+" ."+item.mclass).hide();
		if(item.animation!="")$("#"+this.id+" ."+item.mclass).css("animation",item.animation);
	 }

	//显示页面
   this.show=function(){ 
   	$("#"+this.id).show();
   	$("#"+this.id).children().show();
   	}
   	this.getId=function(){
   		return this.id;
   	}
   //隐藏页面
   this.hide=function(){
   	 $("#"+this.id).hide();
   	 $("#"+this.id).children().hide();
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
	 * 显示精灵的时间间隔
	 * @param  {[page]} page  [页面id]
	 * @param  {[array]} items [class数组]
	 * @param  {Number} i     [时间间隔]
	 * @return {[]}      
	 */
	function sequence_active(pageId,items,times,i){
	if(i==items.length) {return;}
	setTimeout(function(){
		$("#"+pageId+" ."+items[i].mclass).css("display","block");sequence_active(pageId,items,times,i+1);
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
