<?php
include_once('sessionfile.php');
include_once('chkislogin.php');
ini_set("error_reporting","E_ALL & ~E_NOTICE");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
<title>上传图片列表</title>
<link href="js/jquery-ui-1.10.3.custom/jquery-ui-1.10.3.custom.min.css" rel="Stylesheet" media="screen" type="text/css" />
<link href="js/jquery.jqGrid-4.5.4/css/ui.jqgrid.css"rel="stylesheet" media="screen" type="text/css" />
<script src="js/jquery-1.9.0.min.js" type="text/javascript"></script>
<script src="js/jquery.jqGrid-4.5.4/js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="js/jquery.jqGrid-4.5.4/js/i18n/grid.locale-cn.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.10.3.custom/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="styles/style.css" />
<script src="js/Common.js" type=""></script>
</head>
<body>
<div style="margin-left:10px;">
<br />
<?php
$myindex=1;
include "menu.php";
?>
<br /><!--  
<div style="text-align:center">
状态：<select id="imgisdown" name="imgisdown"><option value="">不限</option><option value="0" selected="selected">未下载</option><option value="1">已下载</option></select> 微信昵称或照片编号：<input type="text" id="keyword" name="keyword" style="border: 1px solid #abadb3;"/> 开始日期：<input type="text" id="beginTime" name="beginTime" onkeydown="return false;" style="border: 1px solid #abadb3;" /> 截至日期：<input type="text" id="endTime" name="endTime" onkeydown="return false;" style="border: 1px solid #abadb3;" /> <input type="button"  name="btnExport" id="btnExport" value="导出数据" />
<br/><br/>
<button onclick="gridReload()" id="submitButton" style="margin-left:30px;">查询</button><a href="downimg.php" target="_blank" onclick="return checkselected(this);" style="padding:3px 5px;width:100px;background:#f0f0f0;color:blue;margin-left:10px;margin-top:1px;">下载已选的图片</a>
</div>-->
<div style="text-align:center;padding-bottom:15px;">
状态：<select id="imgischeck" style="margin-right:15px;" name="imgischeck"><option value="">不限</option><option value="N">未审核</option><option value="Y">已审核</option></select>UID：<input type="text" id="keyword" name="keyword" style="border: 1px solid #abadb3;"/> <input type="text" id="beginTime" name="beginTime" onkeydown="return false;" style="border: 1px solid #abadb3;display:none;" /><input type="text" id="endTime" name="endTime" onkeydown="return false;" style="border: 1px solid #abadb3;display:none;" /> <button onclick="gridReload()" id="submitButton" style="margin-left:10px;margin-right:10px;">查询</button><button name="btnExport" id="btnExport" />导出数据</button><a href="downimg.php" target="_blank" onclick="return checkselected(this);" style="padding:3px 5px;width:100px;background:#f0f0f0;color:blue;margin-left:10px;margin-top:1px;display:none;">下载已选的图片</a>
</div>

<table id="navgrid" ></table>
<div id="pagernav" ></div>
<div id="Div1" ></div>
</div>
<script type="text/javascript" language="javascript">
    $(function () {
        $("#beginTime").datepicker();
        $("#endTime").datepicker();
        $("#beginTime").datepicker('option', { dateFormat: 'yy-mm-dd',maxDate: new Date() });
        $("#endTime").datepicker('option', { dateFormat: 'yy-mm-dd',maxDate: new Date() });
    });
   var jqwidth = $(window).width() - 40;
   jQuery("#navgrid").jqGrid({
        url: 'getdata.php?oper=list',
        datatype: "json",
        colNames: ['序号','UID','上传照片','点赞次数','创建时间','状态','操作'],
        colModel: [
        { name: 'id', index: 'id', width:10, align: "center", editable: false, editoptions: { readonly: true, size: 10} },        
		{ name: 'guid', index: 'guid', width:30, align: "center", editable: true, editoptions: { size: 25} },
   		{ name: 'photo', index: 'photo', width:30, align: "center", editable: true, editoptions: { size: 25},formatter:function(cellvalue, options, rowObject){
   			return '<a href="../upload/'+cellvalue+'" target="_blank"><img src="../upload/'+cellvalue+'" style="width:80px;height:80px;margin:5px 0;" /></a>';
   		}},
   		{ name: 'qty', index: 'qty', width:10, align: "center", editable: true, editoptions: { size: 25} },
   		{ name: 'createdtime', index: 'createdtime', width:20, align: "center", editable: true, editoptions: { size: 25} },
   		{ name: 'ischeck', index: 'ischeck', width:20, align: "center", editable: true, editoptions: { size: 25},formatter:function(cellvalue, options, rowObject){   			
   			//var imgpath=rowObject.diyphoto;
   			var mystr='';
   			var isshare=rowObject.isshare;
   			if(cellvalue=='N'){
   				mystr='未审核';
   			}else{
   				mystr='<span style="color:green;">已审核</span>';
   			}
   			mystr+='&nbsp;|&nbsp;';
   			if(isshare=='N'){
   				mystr+='<span style="color:red;">未分享</span>';
   			}else{
   				mystr+='<span style="color:green;">已分享</span>';
   			}   			
   			
   			return mystr;
   		}},
   		{ name: 'isdown', index: 'isdown', width:10, align: "center", editable: true, editoptions: { size: 25},formatter:function(cellvalue, options, rowObject){   			
   			//var imgpath=rowObject.diyphoto;
   			var ischeck=rowObject.ischeck;
   			var id=rowObject.id;
   			if(ischeck=='Y'){
   				return '暂无';
   			}else{
   				return '<span style="color:red;cursor:pointer;" onclick="checkuploadphoto(this,'+id+');">审核</span>';
   			}
   		} }
		],
        rowNum: 30,
        rowList: [10, 20, 30],
        jsonReader: {
            root: "rows",
            page: "page",
            total: "total",
            records: "records",
            repeatitems: false,
            id: "id"
        },
        multiselect: true,
        pager: '#pagernav',
        name: 'name',
        viewrecords: true,
        sortorder: "desc",
        caption: "上传图片列表",
        height:'auto',
		width:jqwidth.toString(),
        autowidth: false,
        editurl: "getdata.php",
      
    });

    jQuery("#navgrid").jqGrid('navGrid', "#pagernav", { edit: false, add: false, del: true });
    jQuery("#navgrid").jqGrid('navGrid', '#pagernav', {},
    { height: 600, reloadAfterSubmit: false },
    { reloadAfterSubmit: true },
    {}
);

    //查询商品列表
    function gridReload() {
        //获取文本框中的值
        var keyword = jQuery("#keyword").val();
        var beginTime = jQuery("#beginTime").val();
        var endTime = jQuery("#endTime").val();
        var imgischeck = jQuery("#imgischeck").val();
        var name = '';
        jQuery("#navgrid").jqGrid('setGridParam', { url: "getdata.php?oper=list&keyword=" + keyword + "&rnd="+Math.random()+"&beginTime=" + beginTime + "&endTime=" + endTime + "&name=" + name+"&imgischeck="+imgischeck, page: 1 }).trigger("reloadGrid");
    }
 $(function () {
        $("#btnExport").click(function () {
            var beginTime = jQuery("#beginTime").val();
            var endTime = jQuery("#endTime").val();
			var keyword = jQuery("#keyword").val();
			var imgischeck = jQuery("#imgischeck").val();
            var href = "export.php?keyword="+keyword+"&name=&begintime=" + beginTime + "&endtime=" + endTime+'&imgischeck='+imgischeck+'&rnd='+Math.random();

            var link1 = href;
            window.location.href = link1;
          //  $("#aExport").click();
        });
    });
    function changedownstatus(id){
    	$.post('getdata.php',{oper:'down',id:id.toString(),rnd:Math.random()},function(){
    		
    	});
    }

    function checkuploadphoto(obj,id){
    	$.post('getdata.php',{oper:'check',id:id.toString(),rnd:Math.random()},function(data){
    		if(data!=null){    			
    			if(data.result==1){
    				alert('审核照片成功！');
    				$(obj).html('暂无').css('color','#000');
    				var html=data.remark;
    				$('#'+id.toString()).find('td:eq(6)').html(html.toString());
    			}else{
    				alert(data.remark);
    			}
    		}
    	},'json');
    }
</script>
</body>
</html>