 
 /**
 * 统一弹出层提示
 * @param {Object} oDom 必选。弹出层容器
 * @param {Function} fCallBack 可选。出来提示的同时运行的回调
 */
 
// JavaScript Document
 function MessageBoxs(title,msg,w,h,isShowButton,delay,backFun){
  
	if(!document.getElementById("MessageShowBox_Joe")){
		$("body").append('<div id=MessageShowBox_Joe  class=niupingjie_mask_show ></div>')
	}else
	{
		$("#MessageShowBox_Joe")
		.find("iframe")
		.each(function(){
			this.contentWindow.document.write('');
			this.contentWindow.close();			
			this.src="abort:blank"	
		})
		
	 
		$("#MessageShowBox_Joe").html("")
		try{
			CollectGarbage();
			setTimeout("CollectGarbage()",25);
		}catch(e){}
	} 
	
/* 	var msgBox='<table id="MessageShowBox_Joe" border="0" align="center" cellpadding="0" cellspacing="0" width="'+w+'" style="border:1px solid #555; border-top:0"  background="../images/eor12.gif"  ><tr><td background="../images/eor2.gif"><table   border="0" width="98%" align="center" cellpadding="0" cellspacing="0" style="height:29px;" ><tr><td width="1%" align="left" valign=top style="padding-top:4px;"><img src="../images/logo.jpg" ></td><td width="98%" nowrap="nowrap" class="backbig" style="word-break:no-break"><b>&nbsp;'+title+'</b></td><td width="1%"><img src="../images/close1.gif" onMouseMove=this.src="../images/close2.gif" onMouseOut=this.src="../images/close1.gif" onMouseDown=this.src="../images/close3.gif" class="niupingjie_mask_close" style="cursor:pointer" /></td></tr></table></td></tr><tr><td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td height="100%" valign="top" class="backbig"><DIV id="msgTable" style="overflow-y:auto ;width:100%;height:'+h+'px; margin-top:5px; word-break:break-all">'+msg+'</DIV></td></tr></table></td></tr>'
	if(isShowButton){	
		msgBox=msgBox+'<tr><td align="center" ><button  class="queding2 niupingjie_mask_close" onmouseover="this.className=\'queding1 niupingjie_mask_close\'" onmouseout="this.className=\'queding2 niupingjie_mask_close\'" onmousedown="this.className=\'queding3 niupingjie_mask_close\'" id="queding_ok_Joe" style="margin-top:10px; margin-bottom:10px;"></button></td></tr>' 
	} 
	msgBox=msgBox+'</table>'
	
*/	
	
	var msgBox = 
	'<table id="MessageShowBox_Joe" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #555; border-top:0"  background="../images/eor12.gif"  >\
	  <tr>\
		<td background="../images/eor2.gif"><table   border="0" width="100%" align="center" cellpadding="0" cellspacing="0" style="height:29px;" >\
			<tr>\
			  <td width="1%" align="left" style="padding-left: 6px;"><img src="../images/logo.jpg" ></td>\
			  <td width="98%" nowrap="nowrap" class="backbig" style="word-break:no-break"><b>&nbsp;$title</b></td>\
			  <td width="1%" style="padding-right:4px;"><img src="../images/close1.gif" onMouseMove=this.src="../images/close2.gif" onMouseOut=this.src="../images/close1.gif" onMouseDown=this.src="../images/close3.gif" class="niupingjie_mask_close" style="cursor:pointer" /></td>\
			</tr>\
		  </table></td>\
	  </tr>\
	  <tr>\
		<td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">\
			<tr>\
			  <td height="100%" valign="top" class="backbig"><DIV id="msgTable" style="overflow-y:auto ; word-break:break-all">$msg</DIV></td>\
			</tr>\
		  </table></td>\
	  </tr>';
	  
		if(isShowButton){
			msgBox += '<tr>\
				<td align="center" ><button  class="queding2 niupingjie_mask_close" onmouseover="this.className=\'queding1 niupingjie_mask_close\'" onmouseout="this.className=\'queding2 niupingjie_mask_close\'" onmousedown="this.className=\'queding3 niupingjie_mask_close\'" id="queding_ok_Joe" style="margin-top:10px; margin-bottom:10px;"></button></td>\
			  </tr>';  
		}
		
		msgBox +='</table>';
		
		msgBox = msgBox.replace(/\$title/, title)
						.replace(/\$msg/, msg)

 	$("#MessageShowBox_Joe").append(msgBox) 
 	MessageBoxShow(document.getElementById("MessageShowBox_Joe"),
		function (){
			if(delay>0 ){
				setTimeout("document.getElementById(\"MessageShowBox_Joe\").style.display.toLowerCase()!=\"none\"?$('.niupingjie_mask_close')[0].click():''",delay*1000);
			} 
 		},
		backFun
	)
	if($("#queding_ok_Joe")[0]){  
		$("#queding_ok_Joe")[0].focus(); 
	}
}
function MessageBoxShow(oDom, fCallBack) {
	var doc = document,
		shadow = doc.createElement('div'),
		shadowIframe = doc.createElement('iframe'),
		$dom = $(oDom),
		$iframe = $dom.find('iframe');
		
	shadow.className = 'niupingjie_mask_show_bg';
	$(shadow).css({
		'background-color': '#000'
	});
	shadowIframe.className = 'niupingjie_mask_show_bg_iframe';
	shadowIframe.style.width = '100%';
	shadowIframe.style.height = '100%';
	$(shadowIframe).css({
		opacity: 0
	});
	shadowIframe.frameborder = "0";
	shadowIframe.scrolling = "no";
	shadow.appendChild(shadowIframe);
	doc.body.appendChild(shadow);
	shadow.style.display = 'block';

	$dom.css({
		left: ($(window).width()-$dom.outerWidth())/2,
		top: ($(window).height()-$dom.outerHeight())/2
	}).show();
	
	$iframe.data('defaultWidth', $iframe.width());
	$iframe.data('defaultHeight', $iframe.height());
	
	$dom
	.find('.niupingjie_mask_close')
	.click(function(){
		oDom.style.display = 'none';
		$('.niupingjie_mask_show_bg, .niupingjie_mask_show_bg_iframe').remove();
	});

	$dom.data('titleHeight', $dom.find('>table>tbody>tr:first').outerHeight());
	
	$dom.data('bottomHeight', 0);
	if($dom.find('#queding_ok_Joe').length){
		$dom.data('bottomHeight', $dom.find('>tr:last').outerHeight());
	}
	
	if($iframe.length){
		var resizeFun = function(){
			setTimeout(function(){
				$(shadow).unbind('resize');
				$iframe.css({
					width: $iframe.data('defaultWidth'),
					height: $iframe.data('defaultHeight')
				});
				
				var domWidth = $dom.width(),
					domHeight = $dom.height(),
					$win = $(window),
					wWidth = $win.width(),
					wHeight = $win.height();

				
				if(domWidth > wWidth || domHeight > wHeight){
					if(domWidth > wWidth){
						$iframe.css({
							width: wWidth - 50
						});
					}
					
					if(domHeight > wHeight){
						$iframe.css({
							height: wHeight - $dom.data('titleHeight') - $dom.data('bottomHeight') - 20
						});
					}
				}
				
				$dom.css({
					left: (wWidth- $dom.width())/2,
					top: (wHeight- $dom.height())/2
				});	
						
				$(shadow).bind('resize', function(){
					resizeFun();
				});
			});			
		};
		resizeFun();
	}else{
		$(shadow).bind('resize', function(){
			setTimeout(function(){
				$dom.css({
					left: ($(window).width()-$(oDom).outerWidth())/2,
					top: ($(window).height()-$(oDom).outerHeight())/2
				}).show();
			});
		}).trigger('resize');
	}
	if(fCallBack) fCallBack();
}


/*
function MessageBoxs(title,msg,w,h,isShowButton,delay,backFun){
 	if(isShowButton){
  		Dialog.alert({
			title: title,
			content: msg,
			style: {
				width: w,
				height: h
			},
			button: {
				'取 消': function(){
 					return false;
				}
			}
 		});
	}else
	{
		Dialog.alert({
			title: title,
			content: msg, 
			style: {
				width:w,
				height: h,
				overflow:"hidden"
			},
			button: {
				'确 定': false
			}
		});	
	}
}
 */


function request(name){  
  var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");  
  var r = window.location.search.substr(1).match(reg);  
  if (r!=null) return unescape(r[2]); return "";  
}  
 
 

function DisableButtom(buttom,times,flg){
	//提醒用户
	ShowTempMessage(times+"后将禁用提交按钮",5*100)
	setTimeout(function(){
		document.getElementById(buttom).disabled=!flg;
		document.getElementById(buttom).enabled=!flg;
	},times*1000)		
}

/*
function ShowTempMessage(message,times){
	
	 if(message==""){
		 if(document.getElementById("temp_message")){
			document.getElementById("temp_message").style.display="none"; 
			return ;
		 }
	  }
 	 try{
 		clearTimeout(xx)
 	 }catch(e){}
	 
	 if(!document.getElementById("temp_message")){
		$("body").append("<div id='temp_message' style='padding:5px;color: #A71023; position: absolute; right: 1px; top: 1px; height:15px; background-color: #FFFFE1;border:1px solid #999; padding-top:5px;'>"+message+"</div>");
	 }else{
		 
		document.getElementById("temp_message").style.display="inline"; 
		document.getElementById("temp_message").innerHTML=message;
	 }
	xx=setTimeout(function(){
		 if(document.getElementById("temp_message")){
			 	document.getElementById("temp_message").style.display="none";
		  }
	},times*1000)		
}

*/
function ShowTempMessage(msg, delaySeconds,option){
	if(top && top.Dialog && top.Dialog.tip){
		
		top.Dialog.tip(msg, delaySeconds);
		return 
	}
 	if(msg=="")return 
	var  Tip = $('<span>'+msg+'</span>'),
		move = 30;	
	option={
		//display: 'none',
		position: 'absolute',
		padding: '5px 10px',
		color: '#fff',
		left: '50%',
		top: '50%',
		opacity: 0,
		"line-height":"20px",
		//'max-width': 50,
		'z-index': 99999,
		'background-color': '#333',
		'margin-top': -Tip.outerHeight()/2,
		'margin-left': -Tip.outerWidth()/2			
	}
 	Tip.appendTo(document.body).css(option);
	if(Tip.width()>300){
		Tip.css({width:300});
		Tip.css({'margin-left': -Tip.outerWidth()/2	});
	}
	Tip.addClass("tip"); 
	 
 	var showTipTimer=setTimeout(function(){
		var top = Tip.offset().top;
		Tip.css({
			top: top + move/2
		});
		Tip.animate({
			top: top - move/2,
			opacity: 1
		}, function(){
			setTimeout(function(){
				var top = Tip.offset().top;
				Tip.animate({
					top: top - move,
					opacity: 0
				}, function(){
					Tip.remove();
				});
			}, delaySeconds || 1000);
		});			
	});
	return Tip;
}
 
function SetFrameCarsNum(num)
{
	top.ShopCart.setNumber(num);	
}
	
	
/* 
$(function(){
		$('table.GradeViewList>tr:odd').addClass("oddTr");
		$('table.GradeViewList>tr')
		.mouseover(function(){
			$(this).addClass('hover');
		})
		.mouseout(function(){
			$(this).removeClass('hover');
		});	
 
})
 */


//顯示Dialog for iframe
function showDialog(id, url, title, height, width) {
    jQuery("<div id='" + id + "' style='overflow:hidden;'></div>").append(jQuery("<iframe frameborder='0' width='98%' height='96%'  id='frm_" + id + "' src='" + url + "' scrolling='auto' ></iframe>")).dialog({
        show: "scale",
        autoOpen: true,
        modal: true,
        height: height,
        width: width,
        resizable:true,
        draggable: true,
        title: title,
        close: function () {
            try { parent.resizeIframe(false); } catch (e) { }
        },
        open: function (event, ui) {
            AdjustDialogButton(this);
        },
        position: {
            my: 'center top+1%',
            at: 'center top',
            of: 'body'
        }
        //position: ["center", resultY]
        //    position: ["center", "top"]
        //position:[{my: 'left top', at: 'left bottom', of: window}]
    });
    try { parent.resizeIframe(true); } catch (e) { }

}


function AdjustDialogButton(oThis) {
    var dw = $(oThis.parentElement).outerWidth();
    var bw = $(oThis.parentElement).find(".ui-dialog-buttonset").outerWidth();
    $(oThis.parentElement).find(".ui-dialog-buttonset").css("float", "none");
    $(oThis.parentElement).find(".ui-dialog-buttonset").css("width", (bw + 3) + "px");
    $(oThis.parentElement).find(".ui-dialog-buttonset").css("position", "relative");
    $(oThis.parentElement).find(".ui-dialog-buttonset").css("left", parseInt((dw - bw + 1) / 2) + "px");
    $($(oThis.parentElement).find(".ui-dialog-buttonset").find(".ui-button-text")[0]).css("background-color", "#79c4cb");
    $($(oThis.parentElement).find(".ui-dialog-buttonset").find(".ui-button-text")[0]).css("color", "#fff");
}
function formatTime(val) {
    var re = /-?\d+/;
    var m = re.exec(val);
    var d = new Date(parseInt(m[0]));
    // 按【2012-02-13 09:09:09】的格式返回日期
    return d.format("yyyy-MM-dd hh:mm:ss");
}
