<!DOCTYPE html>
<html>
<head>
	<title>A sample page</title>
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
		}
		#blackFrame{
			width: 800px;
			height: 600px;
			background-color: #000;
			display: none;
		}
	</style>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript">
		function appendBlackbg()
		{
			if(!$("#blackbg").length)
				$("#mainFrame").append("<div id='blackFrame'></div>");
		}
		function addblock(type,id,element,width,height)
		{
			if(!$("#"+id).length)
				$("#mainFrame").append("<div id='"+id+"'></div>");
			$("#"+id).css("width",width);
			$("#"+id).css("height",height);
			switch(type)
			{
				case "top":
				{

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
		function startIntro()
		{
			// This is a told by key.
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
									addBlock("top","pic/intro_scroll.png","intro_scroll",640,440);
								}
								
							});
						});
					});
				});
			});
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function(){
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