window.onload=function(){
	var page1=document.getElementById("page1");
	var page2=document.getElementById("page2");
		var music=document.getElementById("audio");
	var audio=document.getElementsByTagName("audio")[0];
	audio.play();
	    music.setAttribute("class","play");
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
	page1.addEventListener("touchstart",function(){
		setTimeout(function(){
			page1.style.display="none";
		    page2.style.display="block";
		    page2.setAttribute("class","page flippage-cutCard-top-upward");
		    setTimeout(function(){
		    	page2.style.display="none";
		    page3.style.display="block";
		    },3000);
		},
		100);
	},false);
}
