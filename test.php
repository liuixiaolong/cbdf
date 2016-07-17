<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>test page</title>
<script type="text/javascript" src="javascript/jquery-2.1.0.min.js"></script>
<style type="text/css">
*{margin:0;padding:0;}
.photolist{width:98%;margin:0 auto;}
.photolist .nodb{color:#ccc;font-size:13px;text-align:center;padding:10px 0;}
.photolist li{width:33%;float:left;list-style:none;text-align:center;}
.photolist li span{float:left;width:100%;height:300px;overflow:hidden;text-align:center;}
.photolist li img{width:200px;height:300px;}
.photolist p{float:left;width:100%;height:30px;overflow:hidden;text-align:center;line-height:30px;padding-bottom:15px;}
.photolist p strong{color:red;}
.photolist p a{color:blue;margin-left:10px;cursor:pointer;}
.photolist p em{list-style:normal;margin-left:10px;color:green;cursor:pointer;}
</style>
</head>

<body>

<input type="button" value="获得uid" style="margin-left:100px;" onclick="getuid(this);" /><br /><br />
<input type="button" value="生成Id等于3图片缩略图" style="margin-left:100px;" onclick="getsmallphoto(this,3);" /><br /><br />
<script type="text/javascript">
<!--
    function getuid(obj){
    	if($(obj).hasClass('hot')){return;}
    	$(obj).addClass('hot');
    	$.post('getdata.php',{action:'uid',rnd:Math.random()},function(data){
    	    $(obj).removeClass('hot');
    		if(data!=null){
    			alert(data.remark);
    		}
    	},'json');
    }
    
    function getsmallphoto(obj,id){
    	if($(obj).hasClass('hot')){return;}
    	$(obj).addClass('hot');
    	$.post('getdata.php',{action:'makephoto',id:id.toString(),rnd:Math.random()},function(data){
    	    $(obj).removeClass('hot');
    		if(data!=null){
    			alert(data.remark);
    		}
    	},'json');
    }
    
	$.post('getdata.php',{action:'list',sortby:'date',pageid:'0',pagesize:'9',uid:'as34234234sdfsdfsdfsd3234',rnd:Math.random()},function(data){
	    if(data!=null){
			if(data.result==0){
			    //获取图片失败
				alert(data.remark);
			}else{
				var total=data.total;
				if(total==0){
					$('.photolist').html('<p class="nodb">'+data.remark+'</p>');
					return;
				}
				var list=data.list;
				var html='';
				//jquery解析json
				$.each(list,function(i,item){
				    var ischeck=item.ischeck;
				    if(ischeck.toString()=='Y'){
				    	ischeck='已审核';
				    }else{
				    	ischeck='未审核';
				    }
					html+='<li><span><img src="upload/'+item.photo+'" /></span><p>点赞次数：<strong>'+item.qty+'</strong><a onclick="photolike(this,'+item.id+');">点赞</a><em onclick="photoshare(this,'+item.id+');">分享</em></p></li>';
				});
				$('.photolist').html(html);
			}
		}
	},'json');
	
	function photolike(obj,id){
		//点赞
		if($(obj).hasClass('hot')){return;}
		$(obj).attr('style','cursor:default;color:#ccc;').addClass('hot');
		$.post('getdata.php',{action:'plike',id:id.toString(),uid:'as34234234sdfsdfsdfsd3234',rnd:Math.random()},function(data){
		   $(obj).removeAttr('style').removeClass('hot');
		   if(data!=null){
		   	   alert(data.remark);
		   	   if(data.result==1){
		   	   	  $(obj).parent().find('strong').html(data.qty.toString());
		   	   }
		   }
        },'json');
	}
	
	function photoshare(obj,id){
		//分享
		if($(obj).hasClass('hot')){return;}
		$(obj).attr('style','cursor:default;color:#ccc;').addClass('hot');
		$.post('getdata.php',{action:'share',id:id.toString(),uid:'as34234234sdfsdfsdfsd3234',rnd:Math.random()},function(data){
		   $(obj).removeAttr('style').removeClass('hot');
		   if(data!=null){
		   	   alert(data.remark);
		   }
        },'json');
	}
	
	function getphotodetail(id){
	    //获得照片详细
		$.post('getdata.php',{action:'photo',id:id.toString(),rnd:Math.random()},function(data){
		   if(data!=null){
		   	   if(data.result==0){
		   	   	   alert(data.remark);
		   	   }else{
		   	   	   //点赞次数
		   	   	   var qty=data.qty;
		   	   	   //照片大图
		   	   	   var bigphoto=data.bigphoto;//显示时面前加上“upload/”
		   	   	   //照片小图
		   	   	   var photo=data.photo;//显示时面前加上“upload/”
		   	   }
		   }
        },'json');
	}
//-->
</script>
<div class="photolist"></div>
</body>
</html>

