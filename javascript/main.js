var supportCSS3=false;
var IE=false;
var winW;
var winH;
var b;

var pagesSwiper;
var animateTimer;

var agreen=false;
var pagename="rules";


$(function(){
	
	window.addEventListener("onorientationchange" in window ? "orientationchange" : "resize", orientationChange, false);
	function orientationChange(){   
		switch(window.orientation) {   
		case 0:$("#screenTips").css({"display":"none"});
		break;
		case 180: $("#screenTips").css({"display":"none"}); 
		break;   
		case -90: $("#screenTips").css({"display":"block"}); 
		break; 
		case 90:$("#screenTips").css({"display":"block"}); 
		break;
		default:break;   
		}   
	}
	
	//获得uid
	$.post('getdata.php',{action:'uid',rnd:Math.random()},function(data){	    
		if(data!=null){
			var uid=data.remark;
		}
	},'json');
	
	});
	




function readyLoading(){
	winW=$(window).width();
	winH=$(window).height();
	b=winW/640;	
	$('*[percent]').filter('[percent!=""]').each(function(index) {
		  percent($(this),$(this).attr('percent'));
	    });    
	
	$("#loading").css({"left":(winW-220*b)/2,"top":(winH-220*b)/2});
	$("#loadingBox").fadeIn(600);	
	
	
	}

function ready(){
		
	winW=$(window).width();
	winH=$(window).height();	
		
	onResizeEvent();
	document.ondragstart=function() {return   false;}
	
	checkPages();
	
	$(".closeBtn_rules").bind("touchstart mousedown",closeRules);	
	
	$("#rpShareBtn").click(function(){
		$(".sharePage").removeClass("panelFadeOut");
		$(".sharePage").css({"display":"block"});
		$(".sharePage").addClass("panelFadeIn");
		
		});
		
	$(".checkBox").click(function(){
		if($(this).hasClass("checkBoxChoosed")){
			$(this).removeClass("checkBoxChoosed");
			}
		else{
			$(this).addClass("checkBoxChoosed")
			}
		
		});
		
	
	$("#submitBtn").click(function(){
		//提交用户表单	
		var userName=$.trim($('input[name="userName"]').val());
		if(userName.toString()==''){
			alert('请输入姓名！');
			$('input[name="userName"]').focus();
			return;
		}
		var mobile=$.trim($('input[name="mobile"]').val());
		if(mobile.toString()==''){
			alert('请输入手机号码！');
			$('input[name="mobile"]').focus();
			return;
		}
		if(mobile.toString().length!=11){
			alert('您输入的手机号码有误！');
			$('input[name="mobile"]').focus();
			return;
		}
		var reg = /^13[0-9]{9}|15[012356789][0-9]{8}|18[0123456789][0-9][0-9]|177[0-9]{8}|147[0-9]{8}$/;
 	  	if (!reg.test(mobile.toString())) {
 	  		alert('您输入的手机号码有误！');
			$('input[name="mobile"]').focus();
			return;
 	  	}
 	  	var agree="yes";
 	  	var imageid='';//获得前面保存的imageid
 	  	var para={
 	  		action:'award',	
 	  		mobile:mobile.toString(),
 	  		contact:userName.toString(),
 	  		agree:agree.toString(),
 	  		imageid:imageid.toString()
 	  	};
 	  	$.post('getdata.php',para,function(data){
 	  		if(data!=null){
 	  			if(data.result==0){
 	  				//失败
 	  				alert(data.remark);
 	  			}else{
 	  				//成功
 	  				alert('保存联系方式成功！');
 	  				$('input[name="userName"]').val('');
 	  				$('input[name="mobile"]').val('');
 	  			}
 	  		}
 	  	},'json');
	});
	
	
	
}

function checkPages(){
	if(getUrlParam("page")!=null){
		pagename=getUrlParam("page");	
	}
	switch(pagename){
		case "rules":
		if($(".rulesPage").length==1){
			$(".rulesPage").css({"display":"block"});
			}
		break;
		case "diy":
		if($(".diy").length==1){
			$(".rulesPage").css({"display":"none"});
			}
		break;
		case "gallery":
		if($(".gallery").length==1){
			//$(".rulesPage").css({"display":"none"});
			}
		break;
		
		default:
		break;
		
		}
	
	}


function closeRules(e){
	e.preventDefault();
	 $(".rulesPage").addClass("outUpBig");
	}


		
function getSwiperPageIndex (_obj){
	var _index=_obj.activeLoopIndex;
	return _index;
	}


function onResizeEvent(){	
	
	b=winW/640;
	
	$('*[percent]').filter('[percent!=""]').each(function(index) {
		  percent($(this),$(this).attr('percent'));
	    });
	    
	   
	  	
	/*if(winH<960*b&&winH>840*b){	
		}
	else if(winH<840*b){
		}
	else if(winH>1000*b&&winH<1010*b){
		}*/
		
	 $("#subLoading").css({"left":(winW-100*b)/2,"top":(winH-60*b)/2-20*b});
	
	/*if(winH>1000*b&&winH<1010*b){
		$(".main").css({"height":winH});
		}
	
	if(winH-1030*b<0)	{
	$(".shareTips").css({"top":10*b-(winH-1030*b)});
	}
	*/
	
	
	}
	
//======================= diy =======================
var _chooseIndex=0;
var _hasPhoto=false;



function _initDiy(){	
	$(".chooseBar .chooseItem").bind('touchstart mousedown',chooseItemTouchStart);
	
	dragPhoto($("#preview"));
	
	$("#confirmBtn").click(function(){
		
		if(!_hasPhoto){
			alert("请上传照片！");
			return false;
			}
		
		if(!$(this).hasClass("on")){
		     $(this).addClass("on");
		     savePhoto();
		}		
		
		
	});
	
	
		
}

	
function chooseItemTouchStart(e){
	e.preventDefault();
	
	if(!_hasPhoto){
			alert("请上传照片！");
			return false;
			}
			
	var _index=$(".chooseBar .chooseItem").index(this);
	chooseStyle(_index);
	
	}
	
function chooseStyle(_index){
	$(".iconFrame").css({"display":"none"});
	$($(".iconFrame")[_index]).css({"display":"block"});
	
	$(".cover").css({"display":"none"});
	$($(".cover")[_index]).css({"display":"block"});
	
	}
	

function resetPhotos(){
	offx=0;
	offy=0;
	currentScale=1;
	targetScale=currentScale;	
	isPinch=false;
	
	currentRotate=0;
	targetRotate=currentRotate;		
	
	
	
	smallPhotoinitW=$("#imghead").width()*.9;
	resetSmallPhoto($("#imghead").attr("src"));
	//transform();
	
	//alert("width: "+$("#imghead").width()+"  height:"+$("#imghead").height());
	
		
      if(!_hasPhoto){
		$("#reUpload").append($("#cameraInput"));
		$(".controlTip").fadeIn(500);
		
		chooseStyle(0);
		$(".step1Bar").addClass("outDownBig");
		$(".step2Bar").addClass("inDownBig");
		
		$(".controlTips").bind("starttouch mousedown",controlTipsTouchEvent);
		_initDiy();
		}
      _hasPhoto=true;
	$("#subLoadingBox").css({"display":"none"});
	
	
	
	}
	
function controlTipsTouchEvent(e){
	e.preventDefault();
	$(".controlTips").fadeOut(500);
	$(".step2Bar .btns").addClass("btnsBg");
	setTimeout(function(){
		$(".controlTips").css({"display":"none"});
		},500);
	}
	


var dx=0;
var dy=0;
var offx=0;
var offy=0;

var currentScale=1;
var targetScale=currentScale;	
var isPinch=false;

var currentRotate=0;
var targetRotate=currentRotate;
	
function dragPhoto(_obj){
	
	
	touch.on(_obj, 'touchstart', function(ev){
		ev.preventDefault();
	});
	
	touch.on(_obj, 'drag', function(ev){
		dx = dx || 0;
		dy = dy || 0;
		//log("当前x值为:" + dx + ", 当前y值为:" + dy +".");
		//offx = dx + ev.x + "px";
		//offy = dy + ev.y + "px";
		offx = dx + ev.x;
		offy = dy + ev.y;
		
		//$(".msg").html("offx:" + offx + "offy: "+offy);	
		transform();
		
	});
	
	touch.on(_obj, 'dragend', function(ev){
		dx += ev.x;
		dy += ev.y;
	});	
	
	
	
      touch.on(_obj, 'pinch', function(ev){
	     		isPinch=true;
			targetScale=currentScale+(ev.scale-1);
			targetScale = targetScale > 2 ? 2 : targetScale;
			targetScale = targetScale < .5 ? .5 : targetScale;
			transform();
			//$(".msg").html("当前缩放比例为:" + ev.scale + ".");				
		});	
	
		
      touch.on(_obj, 'pinchend', function(ev){
		      if(isPinch){
				isPinch=false;
				currentScale=targetScale;
			}
		});
	
	
      touch.on(_obj, 'rotate', function(ev){
			targetRotate=currentRotate+ev.rotation;
			if(ev.fingerStatus === 'end'){
	  	 	   currentRotate=targetRotate;
		      }
			transform();
			$(".msg").html("rotation:" + ev.rotation );
		});
		
}	



var timer=null;


function transform(){	
	$("#preview").css({"left":offx,"top":offy});
	$("#preview1").css({"left":offx,"top":offy});
	$("#smallPhotoBox").css({"left":offx*.9,"top":offy*.9});
	
	
	$("#imghead").css({"-webkit-transform":'scale(' + targetScale + ')'+' rotate('+targetRotate+'deg)'});
	$("#imghead1").css({"-webkit-transform":'scale(' + targetScale + ')'+' rotate('+targetRotate+'deg)'});
	
	clearTimeout(timer);
	timer=null;
	timer=setTimeout(drawSmallPhoto,50);

	}
	
	
//====================== smallPhoto =======================
var canvas;
var ctx;
var smallPhotoinitW=576;
var expectWidth;
var expectHeight;
var img;

var maskX=166;
var maskY=200;
var maskR=290/2;

function resetSmallPhoto(_imgUrl){
	img=null;
	img=new Image();	
	img.onload=function(){
		expectWidth = this.naturalWidth;
		expectHeight = this.naturalHeight; 
		
		 
		var preNum=smallPhotoinitW/expectWidth;
		expectWidth=smallPhotoinitW;
		expectHeight*= preNum;
		
		canvas=null;
		ctx=null;
			
		canvas = document.getElementById("smallPhotoBox");
		ctx = canvas.getContext("2d");
		//canvas.width = expectWidth;
		//canvas.height = expectHeight; 
		canvas.width = 576*b;;
		canvas.height = 907*b;  
		
		
		drawSmallPhoto();
		transform();
	}
	
	img.src=_imgUrl;
	
}


function drawSmallPhoto(){
      ctx.clearRect(0, 0, expectWidth, expectHeight);
	ctx.save(); 
	//ctx.rect(50,50,expectWidth+20,expectHeight+20);
	//ctx.arc(100,100,60,0,Math.PI*2,true); 
	ctx.arc((maskX+maskR)*b,(maskY+maskR)*b,maskR*b,0,Math.PI*2,true);  
	 
      ctx.stroke();
	ctx.clip();
	
	ctx.translate(expectWidth/2,expectHeight/2); //设置中心点
	ctx.rotate(targetRotate* Math.PI / 180); 
	//alert("targetRotate: "+targetRotate)
	ctx.scale(targetScale,targetScale);
	ctx.translate(-expectWidth/2,-expectHeight/2); //恢复中心点
      ctx.drawImage(img,offx,offy,expectWidth,expectHeight);  	
	ctx.restore();
	
	$(".msg").html("offx:" + offx + "offy: "+offy);	
}


function translateSmallPhoto(){
	//targetRotate+=2;
	//targetScale+=0.02;
	//offx+=1;
	//offy+=1;	
	//drawSmallPhoto();
	}	


	
	
	
	
	
function savePhoto(){
	
		$("#subLoadingBox").css({"display":"block"});
	
		html2canvas( $("#diyPhoto") ,{  		
				onrendered: function(canvas){					
				    var photo_canvas = canvas.toDataURL("image/jpg",.8);
				    
				    $("#diyResultPhoto").attr("src",photo_canvas);
				    gotoDiyResultPage();
				    
				    var para={
						action:'photo',
						diyphotobase:photo_canvas,
						rnd:Math.random()
					};
					$.post('getdata.php',para,function(data){
						if(data!=null){
							if(data.result==0){
								alert(data.remark);
							}else{
								//上传图片成功
								var imageid=data.id;//照片id
								var imagepath=data.photo;//照片src路径，如果要访问该图片，前面需要加上upload
								//如:'upload/'+imagepath
							}
						}
					},'json');						
					
				}
			});	
		  
	}
	
	
function gotoDiyResultPage(){
	$(".diyPage").addClass("pageOut");
	
	$(".diyResultPage").removeClass("pageOut");
	$(".diyResultPage").addClass("pageIn");
	
	$("#subLoadingBox").css({"display":"none"});
	
	}
	
	
//======================== activePage  =========================

function activePage(_pageIndex){
	  switch(_pageIndex){
		  case 0:
		  	  if(!$(".page01").hasClass('page01Animate'))
				{
				    $(".page01").addClass("page01Animate");
				    				     
				}
			   prevPageName="page01";
			   
		  	   break;
		  case 1:
		  	  if(!$(".page02").hasClass('page02Animate'))
				{
				    $(".page02").addClass("page02Animate"); 
				    
				}
			  
		  	   break;
			   
		  case 2: 
		  	  if(!$(".page03").hasClass('page03Animate'))
				{
				    $(".page03").addClass("page03Animate"); 
				    
				}
			
		   break;
		  case 3:
		  	  if(!$(".page04").hasClass('page04Animate'))
				{
				    $(".page04").addClass("page04Animate"); 
				    
				}
			
		   break;
		   
		  case 4:
		  	  if(!$(".page05").hasClass('page05Animate'))
				{
				    $(".page05").addClass("page05Animate"); 
				    
				}
			
		   break;
		   
		  case 5:
		  	  if(!$(".page06").hasClass('page06Animate'))
				{
				    $(".page06").addClass("page06Animate"); 
				    
				}
			
		   break;	
		   
		  case 6:
		  	  if(!$(".page07").hasClass('page07Animate'))
				{
				    $(".page07").addClass("page07Animate"); 
				    
				}
			
		   break;		   
		   
		   	   
		  default:break;
		  }
		
	}
	
	
	
	
//======================== other tools =========================


function getUrlParam(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); 
	var r = window.location.search.substr(1).match(reg);  
	if (r!=null) return unescape(r[2]);
	 return null;
} 


function trackEvent(category,action,label) { //跟踪事件
	//_hmt.push(['_trackEvent', category, action,label]);
	//ga('send', 'event', category,action, label);
}
function trackPageview(_page){	
	//_hmt.push(['_trackPageview',_page]);
}



//=============================================================

function percent(obj,str){
	var objAttr = strToJson(str);
	if(objAttr.w){
		obj.css({"width":objAttr.w*b+"px"});
	}
	
	if(objAttr.h){
	    obj.css({"height":objAttr.h*b+"px"});
	}
	
	if(objAttr.f){
		obj.css({"font-size":objAttr.f*b+"px"});
	}
	
	if(objAttr.lh){
		obj.css({"line-height":objAttr.lh*b+"px"});
	}
	
	if(objAttr.bw){
		obj.css({"border-left-width":objAttr.bw*b+"px"});
		obj.css({"border-right-width":objAttr.bw*b+"px"});
		obj.css({"border-top-width":objAttr.bw*b+"px"});
		obj.css({"border-bottom-width":objAttr.bw*b+"px"});
	}
	
	
	if(objAttr.blw){
		obj.css({"border-left-width":objAttr.blw*b+"px"});
	}
	if(objAttr.brw){
		obj.css({"border-right-width":objAttr.brw*b+"px"});
	}
	if(objAttr.btw){
		obj.css({"border-top-width":objAttr.btw*b+"px"});
	}
	if(objAttr.bbw){
		obj.css({"border-bottom-width":objAttr.bbw*b+"px"});
	}
	if(objAttr.t){
		obj.css({"top":objAttr.t*b+"px"});
	}
	if(objAttr.l){
		obj.css({"left":objAttr.l*b+"px"});
	}
	if(objAttr.r){
		obj.css({"right":objAttr.r*b+"px"});
	}
	if(objAttr.b){
		obj.css({"bottom":objAttr.b*b+"px"});
	}
	if(objAttr.mt){
		obj.css({"margin-top":objAttr.mt*b+"px"});
	}
	if(objAttr.ml){
		obj.css({"margin-left":objAttr.ml*b+"px"});
	}
	if(objAttr.mb){
		obj.css({"margin-bottom":objAttr.mb*b+"px"});
	}
	if(objAttr.mr){
		obj.css({"margin-right":objAttr.mr*b+"px"});
	}
	if(objAttr.pt){
		obj.css({"padding-top":objAttr.pt*b+"px"});
	}
	if(objAttr.pl){
		obj.css({"padding-left":objAttr.pl*b+"px"});
	}
	if(objAttr.pb){
		obj.css({"padding-bottom":objAttr.pb*b+"px"});
	}
	if(objAttr.pr){
		obj.css({"padding-right":objAttr.pr*b+"px"});
	}
	
	if(objAttr.br){
		obj.css({"-moz-border-radius":objAttr.br*b+"px","-webkit-border-radius":objAttr.br*b+"px"});
		
	}
	
	if(objAttr.maxH){
		obj.css({"max-height":objAttr.maxH*b+"px"});
		
	}
	
	
	if(objAttr.ls){
		obj.css({"letter-spacing":objAttr.ls*b+"px"});
		
	}
	
	
};


function resizeElements(_obj){
	
	$(_obj).find('*[percent]').filter('[percent!=""]').each(function(index) {
		  percent($(this),$(this).attr('percent'));
		   percent($(_obj),$(_obj).attr('percent'));
	    });
	    
}


function strToJson(str){
var json = eval('(' + str + ')');
return json;
}



