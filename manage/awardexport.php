<?php	
    include_once('sessionfile.php');
    include_once('chkislogin.php');
    date_default_timezone_set('PRC');
	$outConent = "序号,UID,联系人,手机号码,中奖时间 \n";
	$keyword = '';
	if(isset($_REQUEST["keyword"])){
		$keyword=trim($_REQUEST["keyword"]);
	}
	$beginTime = '';
	if(isset($_REQUEST["beginTime"])){
		$beginTime=trim($_REQUEST["beginTime"]);
	}
	$endTime = '';
	if(isset($_REQUEST["endTime"])){
		$endTime=trim($_REQUEST["endTime"]);
	}
	$where=" 1=1 ";
	if ($keyword!="") {$where =$where. "  and  (contact  like '%".$keyword."%' or mobile  like '%".$keyword."%') ";}
    if ($beginTime!=""){
    	$where =$where. " and 0 <= DATEDIFF(createdtime, '".$beginTime."') ";
    }
    if ($endTime!=""){
    	$where = $where." and DATEDIFF(createdtime, '".$endTime."') <= 0";
    }
    require_once('../data/common.php');
    $common=new common();
    $tables=$common->prefix.'userinfo';
    require_once("../data/db.php");
    $DB = new DB();
    $rows=$DB->get_all('select * from '.$tables.' where '.$where.' order by id desc');
    $i=1;
    foreach($rows as $row){
    	$outConent=$outConent.$i.",".$row["guid"].",".$row['contact'].",".$row['mobile'].",".$row["createdtime"]." \n"; 
    	++$i;
    }
    $filename=date('YmdHis',time());
    header("Content-Type:text/csv;charset=utf-8");
	header("Content-type:application/vnd.ms-excel ");
	header("content-Disposition:filename=".$filename.".xls");
    echo($outConent);
	die();   
?>
