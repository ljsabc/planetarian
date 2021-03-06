<!DOCTYPE html>
<html>
<head>
	<title>planetarian ～ちいさなほしのゆめ～</title>
	<style type="text/css">
		body{
			background-color: #66ccff;
		}
		#mainFrame{
			width: 800px;
			height: 600px;
			margin:0 auto;
			margin-top: 120px;
			background-image: url("pic/system/title.png");
			display: none;
			position:relative;
		}
		#blackFrame{
			width: 800px;
			height: 600px;
			background-color: #000;
			display: none;
		}
		#bg{
			z-index: 0;
		}
		#lh{
			z-index: 2;
		}
		#topimg{
			z-index: 1;
		}
		#talkbox{
			font-size: 19px;
			color:#CCC;
			z-index: 3;
			font-family: "Hiragino Sans GB","WenQuanYi Micro Hei","Microsoft Yahei","Simsun";
			text-shadow: 1px 0px 0px rgba(64, 64, 64, 0.8);
			 -moz-user-select: -moz-none;
			 -khtml-user-select: none;
			 -webkit-user-select: none;
			 -ms-user-select: none;
			 user-select: none;
			 cursor:pointer;
		}
	</style>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="typewriter.js"></script>
	<script type="text/javascript">
		var currentPosition=6;
		var availableCache=0;
		var clearFlag=0;
		var speakLock=0;
		var dialogCache=new Array();
		function beginChapter(position,ending,endingFunction)
		{
			//showNowLoading();
			$.ajax({
			  dataType: "json",
			  url: "getContent.php",
			  method: "get",
			  data: {"id":position,"size":50},
			  success: function(data){
			  	say(data[0].words,data[0].swipescreen,data[0].linebreak,data[0].location,data[0].voice,data[0].bgm,data[0].se,function(){});
			  	currentPosition+=1;
			  	setTimeout(cacheResources(data,50),0);
			  	$("#mainFrame").bind("click",function(e){
			  		if(speakLock===0)
			  		{
			  			speakLock=1;
			  			var currentFrame=dialogCache[currentPosition];
				  		say(currentFrame.words,
				  			currentFrame.swipescreen,
				  			currentFrame.linebreak,
				  			currentFrame.location,
				  			currentFrame.voice,
				  			currentFrame.bgm,
				  			currentFrame.se,
				  			function(){

				  		});
				  		if(currentFrame.lh!='')
				  		{
				  			console.log(currentFrame.lh);
				  			changeImage("lh",currentFrame.lh,0,null);
				  		}
				  		if(currentFrame.bg!='')
				  		{
				  			changeImage("bg",currentFrame.bg,0,null);
				  		}
				  		if(currentFrame.topimg!='')
				  		{
				  			changeImage("topimg",currentFrame.topimg,0,null);
				  		}
				  		currentPosition+=1;
				  		//availableCache-=1;
				  		if(availableCache-currentPosition<=10)
				  		{
				  			$.ajax({
							  dataType: "json",
							  url: "getContent.php",
							  method: "get",
							  data: {"id":currentPosition+9,"size":50},
							  success: function(data){
							  	cacheResources(data,50);
							  }
				  			});
				  		}
				  		if(currentPosition===ending)
				  		{
				  			$("#mainFrame").unbind('click');
				  			if(typeof(endingFunction)==='function')
								endingFunction();
					  	}
			  		}
			  	});
			  }
			});
		}
		function cacheResources(data,length)
		{
			var imageCache=new Array();
			var bgmCache=new Array();
			var seCache=new Array();
			var voiceCache=new Array();
			for(i=0;i<data.length;i++)
			{
				console.log(i);
				console.log(data[i]);
				dialogCache[data[i].location]=data[i];
				if(data[i].voice!="0"&&data[i].voice)
				{
					var t=new Audio();
					t.src="k/k%20("+data[i].voice+").ogg";
					voiceCache.push(t);
				}
				if(data[i].bgm!="0"&&data[i].bgm)
				{
					var t=new Audio();
					t.src="bgm/bgm0"+data[i].bgm+".ogg";
					bgmCache.push(t);
				}
				if(data[i].se!="0"&&data[i].se)
				{
					var t=new Audio();
					t.src="se/se"+data[i].se+".ogg";
					seCache.push(t); 
				}
				if(data[i].lh!="0"&&data[i].lh)
				{
					var t=new Image();
					t.src="pic/lh/"+data[i].lh;
					imageCache.push(t);
				}
			}
			availableCache=dialogCache.length;
		}

		function appendBlackbg()
		{
			if(!$("#blackFrame").length)
				$("#mainFrame").append("<div id='blackFrame'></div>");
		}
		function addBlock(type,id,element,width,height,margin)
		{
			if(!$("#"+id).length)
				$("#mainFrame").append("<div id='"+id+"'></div>");
			$("#"+id).css("width",width);
			$("#"+id).css("height",height);
			$("#"+id).css("margin","0 auto");
			$("#"+id).css("position","absolute");
			switch(type)
			{
				case "top":
				{
					$("#"+id).css("top",margin+"px");
					$("#"+id).css("left",(800-width)/2+"px");
					if(element!='')
						$("#"+id).css("background-image","url('"+element+"')");
					break;
				}
				case "bottom":
				{
					$("#"+id).css("bottom",margin+"px");
					$("#"+id).css("left",(800-width)/2+"px");
					if(element!='')
						$("#"+id).css("background-image","url('"+element+"')");
					break;
				}
			}
		}
		function crossFade(target,timeout)
		{
			target.fadeOut(timeout,function(){
				target.fadeIn(timeout);
			});
		}
		function crossFadeTo(target,timeout,nextframe,type,callback,additionalFunction)
		{
			switch(type)
			{
				case "bg":
				{
					target.fadeOut(timeout,function(){
						$("#mainFrame").css("background-image",nextframe);
						target.fadeIn(timeout,function()
							{
								if(typeof(callback)==="function")
									callback();
							});
					});
					break;
				}
				case "blackbg":
				{
					appendBlackbg();
					$("#blackFrame").fadeIn(timeout,function(){
						$("#mainFrame").css("background-image",nextframe);
						$("#blackFrame").fadeOut(timeout,function()
							{
								if(typeof(callback)==="function")
									callback();
							});
					});
					break;
				}
				case "blackWithNewContent":
				{
					appendBlackbg();
					$("#blackFrame").fadeIn(timeout,function(){
						$("#mainFrame").css("background-image",nextframe);
						additionalFunction();
						$("#blackFrame").fadeOut(timeout,function()
							{
								if(typeof(callback)==="function")
									callback();
							});
					});
					break;
				}
			}
		}
		function playMusic(target,src)
		{
			if(src)
				$("#"+target).attr("src",src);
			$("#"+target).trigger("play");
		}
		function changeImage(source,target,fade,callback)
		{
			//fade not implemented yet/
			console.log(source,target);
			switch(source)
			{
				case "bg":
				{
					$("#bg").css("background-image","url('pic/"+target+"')");
					console.log("bg done");
					break;
				}
				case "topimg":
				{
					$("#topimg").css("background-image","url('pic/"+target+"')");
					console.log("topimg done");
					break;
				}
				case "lh":
				{
					$("#lh").css("background-image","url('pic/lh/"+target+"')");
					console.log("lh done");
					break;
				}
			}
		}
		function clearScreen(timeout,callback)
		{
			$("#mainFrame").children().fadeOut(timeout);
			setTimeout(function(){
				$("#mainFrame").html("");
				if(typeof(callback)==='function')
					callback();
			},timeout);
		}
		function say(words,clear,linebreak,id,voicenum,bgmnum,senum,additionalFunction)
		{
			if(clearFlag===1)
			{
				$("#talkbox").html("");
				clearFlag=0;
			}
			if(clear==='1')
				clearFlag=1;
			if(linebreak==='1')
				$("#talkbox").append("<span id='talk"+id+"'>"+words+"</span><br/>");
			else
				$("#talkbox").append("<span id='talk"+id+"'>"+words+"</span>");
			if(voicenum!="0")
				playMusic("voice","k/k%20("+voicenum+").ogg");
			if(bgmnum!="0")
				playMusic("bgm","bgm/bgm0"+bgmnum+".ogg");
			if(senum!="0")
				playMusic("se","se/se0"+senum+".ogg");
			$("#talk"+id).typewrite({
			    'delay': 25, 		//time in ms between each letter
			    'extra_char': '', 	//"cursor" character to append after each display
			    'trim': true, 		// Trim the string to type (Default: false, does not trim)
			    'callback': function(){speakLock=0;} 	// if exists, called after all effects have finished
			});
			if(typeof(additionalFunction)==='function')
				additionalFunction();
		}
		function startIntro()
		{
			// This is a told by key.
			// Too many manual callbacks
			$("#mainFrame").css("background-color","#000");
			crossFadeTo($("#mainFrame"),1500,"url(pic/cg/01.png)","bg",function()
			{
				crossFadeTo($("#mainFrame"),1500,"url(pic/cg/02.png)","bg",function()
				{
					//First background music.
					playMusic("bgm","bgm/bgm01.ogg");
					crossFadeTo($("#mainFrame"),1500,"url('pic/cg/op10(0000).png')","blackbg",function()
					{
						crossFadeTo($("#mainFrame"),1500,"url('pic/cg/op11(0000).png')","blackbg",function()
						{
							crossFadeTo($("#mainFrame"),1500,"url('pic/cg/op12(0000).png')","blackbg",function()
							{
								//Loads the main dialogs and the scrolling images.
								crossFadeTo($("#mainFrame"),1500,"url('pic/g.png')","blackbg",function()
								{
									addBlock("top","intro_scroll","pic/intro_scroll.png",640,440,17);
									setTimeout(function(){
										var myTransition = ($.browser.webkit)  ? '-webkit-transition' :
									                       ($.browser.mozilla) ? '-moz-transition' : 
									                       ($.browser.msie)    ? '-ms-transition' :
									                       ($.browser.opera)   ? '-o-transition' : 'transition',
					       					 myCSSObj = { "background-position":"-480px 0px" };
					       					 myCSSObj[myTransition] = 'background 15s linear';
    										 $("#intro_scroll").css(myCSSObj);
									},500);
									addBlock("bottom","talkbox","",640,130,0);
									//words,clear,linebreak,id,voicenum,bgmnum,senum,additionalFunction
									say("……欢迎大家光临天象馆……","1","1","1",1,0,0,function(){
										$("#mainFrame").one('click',function(e){
											say("……这里有着无论何时都决不会消失的，美丽的无穷光辉……","1","1","2",2,0,0,function(){
												$("#mainFrame").one('click',function(e){
													say("……满天的星星们正在等待着大家的到来……","1","1","3",3,0,0,function(){
														$("#mainFrame").one('click',function(e){
															say("……欢迎大家光临天象馆……","1","1","4",4,0,0,function(){
																//resizing animation
																var myTransition = ($.browser.webkit)  ? '-webkit-transition' :
															                       ($.browser.mozilla) ? '-moz-transition' : 
															                       ($.browser.msie)    ? '-ms-transition' :
															                       ($.browser.opera)   ? '-o-transition' : 'transition',
											       				myCSSObj = { "background-size":"300% 300%", "background-position":"-700px -150px" };
																myCSSObj[myTransition] = 'none';
						    									$("#intro_scroll").css(myCSSObj);
																$("#intro_scroll").css("background-image","url('pic/bg27o_11.png')");
																$("#intro_scroll").css("background-size","100% 100%");
																$("#intro_scroll").css("background-position","0px 0px");	
																setTimeout(function(){
																	//alert("Here!");
																	var myTransition = ($.browser.webkit)  ? '-webkit-transition' :
															                           ($.browser.mozilla) ? '-moz-transition' : 
															                       	   ($.browser.msie)    ? '-ms-transition' :
															                       	   ($.browser.opera)   ? '-o-transition' : 'transition',
											       					myCSSObj = { "background-size":"300% 300%", "background-position":"-750px -150px" };
											       					myCSSObj[myTransition] = 'background 3s ease-in-out';
						    										$("#intro_scroll").css(myCSSObj);
						    										setTimeout(function(){
						    											var myTransition = ($.browser.webkit)  ? '-webkit-transition' :
																	                       ($.browser.mozilla) ? '-moz-transition' : 
																	                       ($.browser.msie)    ? '-ms-transition' :
																	                       ($.browser.opera)   ? '-o-transition' : 'transition',
													       				myCSSObj = {};
																		myCSSObj[myTransition] = 'none';
								    									$("#intro_scroll").css(myCSSObj);
								    									$("#talkbox").html("");
								    									$("#intro_scroll").fadeOut(1000,function(){
								    										$("#intro_scroll").fadeIn(1000,function(){
								    											addBlock("top","intro_logo","pic/cg/op20(0000).png",640,440,17);
								    											$("#intro_logo").css("background-size","cover");
								    											$("#intro_logo").hide().fadeIn(2000,function(){
									    											clearScreen(1500,function(){
									    												// Fire Up!
									    												// Main Entry Here.
									    												$("#mainFrame").css("background-image","none");
									    												$("#topimg").css("background-image","none");
										    											addBlock("top","topimg","pic/w1.png",640,320,15);
										    											addBlock("bottom","talkbox","pic/talkbox.png",640,210,20);
										    											addBlock("top","bg","pic/system/k.png",800,600,0);
										    											addBlock("top","lh","",800,600,0);
										    											$("#talkbox").css({
										    												"padding":"8px 80px 8px 80px",
										    												"line-height":"1.5em",
										    												"color":"#EEE",
										    												"left":"0px"
										    											});
										    											beginChapter(6,500,function(){});
									    											});									    											
								    											});
								    										});
								    										$("#intro_scroll").css(
								    											{
								    												"background-image":"url('pic/bg27o_01.png')",
								    												"background-size":"100% 100%", 
								    												"background-position":"-0px -0px" 
								    											});
								    									});
						    										},3500);
																},500);

															});
														});
													});
												});
											});
										});
									});
								});
							});
						});
					});
				});
			});
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function(){
			// reimplement jquery.browser
			// may be migrate to another file.
			jQuery.uaMatch = function( ua ) 
			{
			        ua = ua.toLowerCase();

			        var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
			                /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
			                /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
			                /(msie) ([\w.]+)/.exec( ua ) ||
			                ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
			                [];

			        return {
			                browser: match[ 1 ] || "",
			                version: match[ 2 ] || "0"
			        };
			};

			// Don't clobber any existing jQuery.browser in case it's different
			if ( !jQuery.browser ) {
			        matched = jQuery.uaMatch( navigator.userAgent );
			        browser = {};

			        if ( matched.browser ) {
			                browser[ matched.browser ] = true;
			                browser.version = matched.version;
			        }

			        // Chrome is Webkit, but Webkit is also Safari.
			        if ( browser.chrome ) {
			                browser.webkit = true;
			        } else if ( browser.webkit ) {
			                browser.safari = true;
			        }

			        jQuery.browser = browser;
			}
			/* initialization cache push*/
			var startAudioCache=new Array();
			var startImgCache=new Array();
			var ta=new Array();
			ta[0]="k/k%20(0).ogg";
			ta[1]="k/k%20(1).ogg";
			ta[2]="k/k%20(2).ogg";
			ta[3]="k/k%20(3).ogg";
			for(i=0;i<4;i++)
			{
				var tAudio=new Audio();
				tAudio.src=ta[i];
				startAudioCache.push(tAudio);
			}
			var ti=new Array();
			ti[0]="pic/cg/01.png";
			ti[1]="pic/cg/02.png";
			ti[2]="pic/cg/op10(0000).png";
			ti[3]="pic/cg/op11(0000).png";
			ti[4]="pic/cg/op12(0000).png";
			ti[5]="pic/cg/op20(0000).png";
			ti[6]="pic/g.png";
			ti[7]="pic/intro_scroll.png";
			ti[8]="pic/bg27o_11.png";		
			ti[9]="pic/bg27o_01.png";
			for(i=0;i<10;i++)
			{
				var tImg=new Image();
				tImg.src=ti[i];
				startImgCache.push(tImg);
			}
			

			crossFade($("#mainFrame"),1000);
			playMusic("bgm","bgm/bgm04.ogg");
			$("#mainFrame").one("click",function(e){
				startIntro();
			});
		})
	</script>
</head>

<body>
	<div id="mainFrame">
	</div>
	<audio src="" id="bgm" loop controls></audio>
	<audio src="" id="se"></audio>
	<audio src="" id="voice"></audio>
	
</body>
</html>