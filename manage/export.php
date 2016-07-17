<?php	
    include_once('sessionfile.php');
    include_once('chkislogin.php');
    date_default_timezone_set('PRC');
	$outConent = "<table><tr><td>序号</td><td>姓名</td><td>手机号码</td><td>微信openid</td><td>微信昵称</td><td>行驶公里</td><td>好友加油次数</td><td>创建时间</td></tr>";
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
	if ($keyword!="") {$where =$where. "  and  (guid  like '%".$keyword."%' or mobile  like '%".$keyword."%')";}
    if ($beginTime!=""){
    	$where =$where. " and 0 <= DATEDIFF(createdtime, '".$beginTime."') ";
    }
    if ($endTime!=""){
    	$where = $where." and DATEDIFF(createdtime, '".$endTime."') <= 0";
    }
    require_once('../data/common.php');
    $common=new common();
    $tables=$common->prefix.'userphoto';
    require_once("../data/db.php");
    $DB = new DB();
    $rows=$DB->get_all('select * from '.$tables.' where '.$where.' order by id desc');
    $i=1;   
    foreach($rows as $row){
    	$outConent=$outConent.'<tr><td>'.$row['id'].'</td><td>'.$row['username'].'</td><td>'.$row['mobile'].'</td><td>'.$row['openid'].'</td><td>'.$row['nickname'].'</td><td>'.($row['kilometre']+$row['sharekilometre']).'</td><td>'.$row['shareqty'].'</td><td>'.$row['createdtime'].'</td></tr>'; 
    	++$i;
    }
    $outConent.='</table>';
    $filename=date('YmdHis',time());
    header("Content-Type:text/csv;charset=utf-8");
	header("Content-type:application/vnd.ms-excel ");
	header("content-Disposition:filename=".$filename.".xls");
    echo($outConent);
	die();   
?>
