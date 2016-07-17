<?php
include_once('sessionfile.php');
if(isset($_SESSION['uinfo'])){
	$username=$_SESSION['uinfo']["username"];
	if($username!=''){
		header("Location:list.php");
		return;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
<title>用户登录</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="js/AC.js"></script>
<script type="text/javascript">
	DD_belatedPNG.fix('*');
</script>
<![endif]-->
<style type="text/css">
body {background:rgb(245,245,254);}
div.l_form table {   
     
    width: 300px;
    padding: 18px 27px 30px 20px; 
     
     
    background: rgb(247, 247, 247);
     border: 1px solid rgba(147, 184, 189,0.8); 
     
    -webkit-box-shadow: 0pt 2px 5px rgba(105, 108, 109,  0.7), 0px 0px 

8px 5px rgba(208, 223, 226, 0.4) inset;
     -moz-box-shadow: 0pt 2px 5px rgba(105, 108, 109,  0.7), 0px 0px 

8px 5px rgba(208, 223, 226, 0.4) inset; 
     box-shadow: 0pt 2px 5px rgba(105, 108, 109,  0.7), 0px 0px 8px 

5px rgba(208, 223, 226, 0.4) inset; 
     -webkit-box-shadow: 5px;
     -moz-border-radius: 5px; 
     border-radius: 5px;
    position: relative;
}
div.l_form {
min-height: 560px;
margin: 0px auto;
/* width: 502px; */
position: relative;
    
    
}
input{
	width: 280px;
	margin-top: 4px;
	padding: 10px 5px 10px 10px;	
	border: 1px solid rgb(178, 178, 178);
	-webkit-appearance: textfield;
	-webkit-box-sizing: content-box;
	  -moz-box-sizing : content-box;
	       box-sizing : content-box;
	-webkit-border-radius: 3px;
	   -moz-border-radius: 3px;
	        border-radius: 3px;
	-webkit-box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.6) 

inset;
	   -moz-box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.6) 

inset;
	        box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.6) 

inset;
	-webkit-transition: all 0.2s linear;
	   -moz-transition: all 0.2s linear;
	     -o-transition: all 0.2s linear;
	        transition: all 0.2s linear;
}


input:active,
 input:focus{
	border: 1px solid rgba(91, 90, 90, 0.7);
	background: rgba(238, 236, 240, 0.2);	
	-webkit-box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.9) 

inset;
	   -moz-box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.9) 

inset;
	        box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.9) 

inset;
} 

/*styling both submit buttons */
#btnLogin{
	font-family:"Microsoft Yahei";
	width: 50%;
	cursor: pointer;	
	background: rgb(61, 157, 179);
	padding: 8px 5px;
	font-family: 'BebasNeueRegular','Arial Narrow',Arial,sans-

serif;
	color: #fff;
	font-size: 24px;	
	border: 1px solid rgb(28, 108, 122);	
	margin-bottom: 10px;	
	text-shadow: 0 1px 1px rgba(0, 0, 0, 0.5);
	-webkit-border-radius: 3px;
	   -moz-border-radius: 3px;
	        border-radius: 3px;	
	z-index: 1;
	position: relative;
	-webkit-box-shadow: 0px 1px 6px 4px rgba(0, 0, 0, 0.07) inset,
	        0px 0px 0px 3px rgb(254, 254, 254),
	        0px 5px 3px 3px rgb(210, 210, 210);
	   -moz-box-shadow:0px 1px 6px 4px rgba(0, 0, 0, 0.07) inset,
	        0px 0px 0px 3px rgb(254, 254, 254),
	        0px 5px 3px 3px rgb(210, 210, 210);
	        box-shadow:0px 1px 6px 4px rgba(0, 0, 0, 0.07) inset,
	        0px 0px 0px 3px rgb(254, 254, 254),
	        0px 5px 3px 3px rgb(210, 210, 210);
	-webkit-transition: all 0.2s linear;
	  
	        transition: all 0.2s linear;
}
#btnLogin:hover{
	background: rgb(74, 179, 198);
}
#btnLogin:active,
#btnLogin:focus{
	background: rgb(40, 137, 154);
	position: relative;
	top: 1px;
	border: 1px solid rgb(12, 76, 87);	
	-webkit-box-shadow: 0px 1px 6px 4px rgba(0, 0, 0, 0.2) inset;
	   -moz-box-shadow: 0px 1px 6px 4px rgba(0, 0, 0, 0.2) inset;
	        box-shadow: 0px 1px 6px 4px rgba(0, 0, 0, 0.2) inset;
}

div.l_form td {
    display: block;
    /* text-align: left; */
    color: rgb(64, 92, 96);  
    font-weight: bold;
}
table {}
div.l_form table:after {
    display: block;
    content: "";
    bottom: 0;
	
	
	
	
	
	position: absolute;
	color: rgb(127, 124, 124);
	left: 0px;
	height: 20px;
	
    width: 100%;
	
    padding: 17px 0 20px 0;
	
    z-index: 0;
    font-size: 16px	;
	text-align: right;
	border-top: 1px solid rgb(219, 229, 232);
	-webkit-border-radius: 0 0  5px 5px;
	   -moz-border-radius: 0 0  5px 5px;
	        border-radius: 0 0  5px 5px;
	background: rgb(225, 234, 235);
	background: -moz-repeating-linear-gradient(-45deg, 
	rgb(247, 247, 247) , 
	rgb(247, 247, 247) 15px, 
	rgb(225, 234, 235) 15px, 
	rgb(225, 234, 235) 30px, 
	rgb(247, 247, 247) 30px
	);
	background: -webkit-repeating-linear-gradient(-45deg, 
	rgb(247, 247, 247) , 
	rgb(247, 247, 247) 15px, 
	rgb(225, 234, 235) 15px, 
	rgb(225, 234, 235) 30px, 
	rgb(247, 247, 247) 30px
	);
	background: -o-repeating-linear-gradient(-45deg, 
	rgb(247, 247, 247) , 
	rgb(247, 247, 247) 15px, 
	rgb(225, 234, 235) 15px, 
	rgb(225, 234, 235) 30px, 
	rgb(247, 247, 247) 30px
	);
	background: repeating-linear-gradient(-45deg, 
	rgb(247, 247, 247) , 
	rgb(247, 247, 247) 15px, 
	rgb(225, 234, 235) 15px, 
	rgb(225, 234, 235) 30px, 
	rgb(247, 247, 247) 30px
	);
}
</style>
</head>
<body >
<table><tr><td height="60"> </td></tr></table>
<table width="100%" border="0">
<tr><td height="430">
	<table width="100%" border="0" align="center">
		<tr><td height="373" >
   <div class="l_form">
            <form id="form1"  action="checklogin.php" onsubmit="return chkform(this);"  method="post"> 
            <table width="370" border="0" align="center">
            <tr><td height="10"></td></tr>
                <tr>
                    <td >用户名：</td><td><input id="username"  name="username" maxlength="20" CssClass="text name_text" /></td>
                </tr>
                <tr>
                    <td >密码：</td><td><input  id="pwd"  name="pwd" type="password" maxlength="30" TextMode="Password" CssClass="text pwd_text"/></td>
                </tr>
                <tr>
                    <td align="center" colspan=2>
                    <input type="submit" id="btnLogin" value="登录" />
                    </td>

                </tr>

            </table>
            </form>            </div>
        </td></tr></table> </td></tr></table>
<script type="text/javascript">
<!--
    function chkform(obj){
    	if($(obj).hasClass('hot')){return false;}
    	var username=$.trim($('#username').val());
    	if(username.toString()==''){
    		alert('请输入用户名！');
    		$('#username').focus();
    		return false;
    	}
    	if(username.toString().length < 4){
    		alert('输入的用户名长度不能少于4位！');
    		$('#username').focus();
    		return false;
    	}
    	var pwd=$.trim($('#pwd').val());
    	if(pwd.toString()==''){
    		alert('请输入密码！');
    		$('#pwd').focus();
    		return false;
    	}
    	if(pwd.toString().length < 6){
    		alert('输入的密码长度不能少于6位！');
    		$('#pwd').focus();
    		return false;
    	}
    	var para={
    		action:'login',
    		username:username.toString(),
    		pwd:pwd.toString(),
    		rnd:Math.random()
    	};
    	$(obj).addClass('hot');
    	$.post('checklogin.php',para,function(data){
    		if(data==null){
    			return false;
    		}
    		alert(data.remark);
    		$(obj).removeClass('hot')
    		if(data.result==1){
    			location.href='list.php';
    		}
    	},'json');
    	return false;
    }
//-->
</script>      
</body>
</html>