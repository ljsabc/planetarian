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
		#talkbox{
			font-size: 18px;
			color:#CCC;
		}
	</style>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="typewriter.js"></script>
	<script type="text/javascript">
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
					$("#"+id).css("background-image","url('"+element+"')");
					break;
				}
				case "bottom":
				{
					$("#"+id).css("bottom",margin+"px");
					$("#"+id).css("left",(800-width)/2+"px");
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
		function say(words,clear,linebreak,voicenum,additionalFunction)
		{
			if(clear)
				$("#talkbox").html("");
			if(linebreak)
				$("#talkbox").append("<span id='"+voicenum+"'>"+words+"</span><br/>");
			else
				$("#talkbox").append("<span id='"+voicenum+"'>"+words+"</span>");
			playMusic("voice","k/k%20("+voicenum+").ogg");
			$("#"+voicenum).typewrite({
			    'delay': 40, //time in ms between each letter
			    'extra_char': '', //"cursor" character to append after each display
			    'trim': true, // Trim the string to type (Default: false, does not trim)
			    'callback': null // if exists, called after all effects have finished
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
					       					 myCSSObj[myTransition] = 'background 15s ease-in-out';
    										 $("#intro_scroll").css(myCSSObj);
									},500);
									addBlock("bottom","talkbox","",640,130,0);
									say("……欢迎大家光临天象馆……",1,1,"1",function(){
										$("#mainFrame").one('click',function(e){
											say("……这里有着无论何时都决不会消失的，美丽的无穷光辉……",1,1,"2",function(){
												$("#mainFrame").one('click',function(e){
													say("……满天的星星们正在等待着大家的到来……",1,1,"3",function(){
														$("#mainFrame").one('click',function(e){
															say("……欢迎大家光临天象馆……",1,1,"4",function(){
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
								    									$("#intro_scroll").fadeOut(1000,function(){
								    										$("#intro_scroll").fadeIn(1000,function(){
								    											addBlock("top","intro_logo","pic/cg/op20(0000).png",640,440,17);
								    											$("#intro_logo").css("background-size","cover");
								    											$("#intro_logo").hide().fadeIn(1000);

								    											// Fire Up!
								    											// Main Entry Here.
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
	<audio src="" id="bgm" controls></audio>
	<audio src="" id="se"></audio>
	<audio src="" id="voice"></audio>
</body>
</html>