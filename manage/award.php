<?php
include_once('sessionfile.php');
include_once('chkislogin.php');
ini_set("error_reporting","E_ALL & ~E_NOTICE");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
<title>中奖信息列表</title>
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
$myindex=2;
include "menu.php";
?>
<br />
<div style="text-align:center;padding-bottom:20px;">
联系人或手机号：<input type="text" id="keyword" name="keyword" style="border: 1px solid #abadb3;"/> 开始日期：<input type="text" id="beginTime" name="beginTime" onkeydown="return false;" style="border: 1px solid #abadb3;" /> 截至日期：<input type="text" id="endTime" name="endTime" onkeydown="return false;" style="border: 1px solid #abadb3;" /><button onclick="gridReload()" id="submitButton" style="margin-left:10px;margin-right:10px;">查询</button><button name="btnExport" id="btnExport">导出数据</button>
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
        url: 'getdataaward.php?oper=list',
        datatype: "json",
        colNames: ['序号','UID','联系人','手机号码','中奖时间'],
        colModel: [
        { name: 'id', index: 'id', width:10, align: "center", editable: false, editoptions: { readonly: true, size: 10} },
        { name: 'guid', index: 'guid', width:30, align: "center", editable: true, editoptions: { size: 25} }, 
   		{ name: 'contact', index: 'contact', width:40, align: "center", editable: true, editoptions: { size: 25} },
   		{ name: 'mobile', index: 'mobile', width:45, align: "center", editable: true, editoptions: { size: 25} },
   		{ name: 'createdtime', index: 'createdtime', width:30, align: "center", editable: true, editoptions: { size: 25} }   		
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
        //sortorder: "desc",
        caption: "抽奖信息列表",
        height:'auto',
		width:jqwidth.toString(),
        autowidth: false,
        editurl: "getdataaward.php",
      
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
        jQuery("#navgrid").jqGrid('setGridParam', { url: "getdataaward.php?oper=list&keyword=" + keyword + "&rnd="+Math.random()+"&beginTime=" + beginTime + "&endTime=" + endTime, page: 1 }).trigger("reloadGrid");
    }
 $(function () {
        $("#btnExport").click(function () {
            var beginTime = jQuery("#beginTime").val();
            var endTime = jQuery("#endTime").val();
			var keyword = jQuery("#keyword").val();
            var href = "awardexport.php?keyword="+keyword+"&begintime=" + beginTime + "&endtime=" + endTime+'&rnd='+Math.random();

            var link1 = href;
            window.location.href = link1;
          //  $("#aExport").click();
        });
    });
</script>
</body>
</html>