window.onload=function(){
	var loding=document.getElementById("loding");
	var page1=document.getElementById("page1");
	var page2=document.getElementById("page2");
	var page3=document.getElementById("page3");
	var music=document.getElementById("audio");
	var audio=document.getElementsByTagName("audio")[0];
	
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
}
