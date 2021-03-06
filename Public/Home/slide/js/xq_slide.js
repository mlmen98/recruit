;(function($){
	var self;
	var xq_slide_in;
	var curindex=0;
	var total;
	var time;
	var height;
	var speed;
	var showbar=false;
	var liwidth;
	function ifbar(){
		if(showbar){
			$(".xq_slide_bar span").eq(curindex).addClass("cur").siblings(".cur").removeClass("cur");
		}
		var text = xq_slide_in.find('li').eq(curindex).find('p').text();
		if (text) {
			$(".xq_slide_bar .vatical").text(text);
		}else{
			$(".xq_slide_bar .vatical").text(text);
		}
	}
	function slide(type){
		time=setInterval(function(){
			curindex++;
			if(curindex>=total)curindex=0;
			if(type=="h")xq_slide_in.css({"transform":"translate3d("+(-liwidth*curindex)+"px,0px,0px)"});
			if(type=="v")xq_slide_in.css({"transform":"translate3d(0px,"+(-height*curindex)+"px,0px)"});
			if(type=="o")xq_slide_in.find("li").eq(curindex).css({"opacity":"1"}).siblings().css({"opacity":"0"});
			ifbar();
		},speed);
	}
	function initCss(type){
		xq_slide_in=self.find(".xq_slide_in");
		total=xq_slide_in.find("li").length;
		height=xq_slide_in.height();
		switch(type){
			case "h":
			xq_slide_in.css({"width":total*100+"%"}).find("li").css({"float":"left","width":100/total+"%"});
			break;
			case "v":
			xq_slide_in.css({"width":"100%"}).find("li").css({"width":"100%"});
			break;
			case "o":
			xq_slide_in.css({"width":"100%","position":"relative"}).find("li").css({"width":"100%","position":"absolute","top":"0px","left":"0px","opacity":"0"}).first().css({"opacity":"1"});
			break;
		}
		slide(type);
	}
	function placego(type){
		switch (type){
			case "h":
				xq_slide_in.css({"transform":"translate3d(-"+liwidth*curindex+"px,0px,0px)"});
				break;
			case "v":
				xq_slide_in.css({"transform":"translate3d(0px,-"+height*curindex+"px,0px)"});
				break;
			case "o":
				xq_slide_in.find("li").eq(curindex).css({"opacity":"1"}).siblings().css({"opacity":"0"});
				break;
		}
		ifbar();
	}
	function openmb($self,type){
		var startX;
		var startY;
		var czX;
		var czY;
		$self[0].addEventListener("touchstart",function(event){
			clearInterval(time);
			var e=event || window.event;
			startX=e.touches[0].clientX;
			startY=e.touches[0].clientY;
		});
		$self[0].addEventListener("touchmove",function(event){
			var e=event || window.event;
			var curX=e.touches[0].clientX;
			var curY=e.touches[0].clientY;
			czX=startX-curX;
			czY=startY-curY;
			switch (type){
				case "h":
					xq_slide_in.css({"transition":"none","transform":"translate3d(-"+(liwidth*curindex+czX)+"px,0px,0px)"});
					break;
				case "v":
					xq_slide_in.css({"transition":"none","transform":"translate3d(0px,-"+(height*curindex+czY)+"px,0px)"});
					break;
			}
		})
		$self[0].addEventListener("touchend",function(event){
			switch (type){
				case "v":
					if(czY > 25){
						curindex++;
					}else if(czY < -25){
						curindex--;
					}
					break;
				default:
					if(czX > 25){
						curindex++;
					}else if(czX < -25){
						curindex--;
					}
				break;
			}
			if(curindex>=total){
				curindex=0;
			}
			if(curindex<0){
				curindex=total-1;
			}
			xq_slide_in.css({"transition":"transform 1s ease"});
			placego(type);
			slide(type);
			czX=0;
			czY=0;
		});
	}
	$.fn.xq_slide=function(options){
		self=$(this);
		var defaults={
			type:"v",//????????????  h???????????????v???????????????o????????????
			vatical:false,//????????????????????? true ?????? false?????????
			choseBtn:true,//??????????????????????????????
			speed:1000,//?????????????????????????????????????????????
			mousestop:true,//????????????????????????????????????
			showbar:true,//????????????????????????
			openmb:true//???????????????????????????
		}
		$.extend(defaults,options);
		speed=defaults.speed;
		if(defaults.choseBtn){
			var lh=self.height();
			self.append("<span class='btn prev_btn'><</span><span class='btn next_btn'>></span>").css("line-height",lh+"px");
			self.find(".prev_btn").on("click",function(){
				curindex--;
				if(curindex<0){
					curindex=total-1;
				}
				clearInterval(time);
				placego(defaults.type);
				slide(defaults.type);
			});
			self.find(".next_btn").on("click",function(){
				curindex++;
				if(curindex>=total){
					curindex=0;
				}
				clearInterval(time);
				placego(defaults.type);
				slide(defaults.type);
			});
		}
		initCss(defaults.type);
		liwidth=xq_slide_in.find("li").width();
		if(defaults.mousestop){
			self.on('mousemove',function(){
				clearInterval(time);
			}).on("mouseleave",function() {
				slide(defaults.type);
			});
		}
		if(defaults.showbar){
			showbar=defaults.showbar;
			var slidebar="";
			for (var i=0;i<total;i++) {
				slidebar+="<span></span>";
			}
			self.append("<div class='xq_slide_bar'>"+slidebar+"</div>");
			if (defaults.vatical) {
				var text = xq_slide_in.find('li').eq(0).find('p').text();
				self.find('.xq_slide_bar').prepend("<div class='vatical'>"+text+"</div>");
			}
			ifbar();
			$(".xq_slide_bar span").on('click',function(){
				curindex=$(this).index();
				clearInterval(time);
				placego(defaults.type);
				slide(defaults.type);
			});
		}
		if(defaults.openmb){
			openmb(self.find(".xq_slide_in"),defaults.type);
		}
	}
})(jQuery);
