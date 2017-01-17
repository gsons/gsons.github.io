window.onload=function(){
	var loding=document.getElementById("loding");
	var page1=document.getElementById("page1");
	var page2=document.getElementById("page2");
	var page3=document.getElementById("page3");
	var music=document.getElementById("audio");
	var audio=document.getElementsByTagName("audio")[0];
		 var images = new Array()
	// 初始化
	preload(
                "./image/music_disc.png",
                "./image/music_pointer.png",
                "./image/p1_bg.jpg",
                "./image/p1_imooc.png",
                "./image/p1_lantern.png",
                "./image/p2_2016.png",
                "./image/p2_bg.jpg",
                "./image/p2_circle_inner.png",
                "./image/p2_circle_middle.png",
                "./image/p2_circle_outer.png",
                "./image/p3_bg.jpg",
                "./image/p3_blessing.png",
                "./image/p3_couplet_first.png",
                "./image/p3_couplet_second.png",
                "./image/p3_logo.png",
                "./image/p3_title.png"
            );
	loding.style.display="none";
	page1.style.display="block";
	music.setAttribute("class","play");
		audio.play();

	page1.addEventListener("touchstart",function(){
		page1.style.display="none";
		page2.style.display="block";
			page3.style.display="block";
			page3.style.top="100%";
		setTimeout(function(){
			page2.setAttribute("class","page fadeOut");
			page3.setAttribute("class","page fadeIn");
		},5000);
	},false);

	music.addEventListener("touchstart",function(){
		this.setAttribute("class","");
		if(audio.paused){
			audio.play();
			this.setAttribute("class","play");
		}
		else{
			audio.pause();
			this.setAttribute("class","");
		}
	},false);

            function preload() {
                for (i = 0; i < preload.arguments.length; i++) {
                    images[i] = new Image()
                    images[i].src = preload.arguments[i]
                }
            }
            
}
