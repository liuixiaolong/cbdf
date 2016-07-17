<?php
    include_once('sessionfile.php');
    include_once('chkislogin.php');
	header("Content-Type:text/html;charset=utf-8");
	
	$oper=trim($_REQUEST['oper']);
	$re="";
	switch($oper)
	{
		 case"list":
      		$re = getlist();
	 		break;
		case"del":
      		$re = del();
	 		break;
	 	case "down":
	 	    $re = down();
	 	    break;
	 	default:
	 	    break;
	}
 	echo($re);
	die();
	
	function getlist()
	 {
	 	require_once('../data/common.php');
        $common=new common();

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
        //$name =$_REQUEST["name"];
		$where=" 1=1 ";
		if ($keyword!="") {$where =$where. "  and  (mobile  like '%".$keyword."%' or contact  like '%".$keyword."%')";}
	    if ($beginTime!=""){
	    	$where =$where. " and 0 <= DATEDIFF(createdtime, '".$beginTime."') ";
	    }
	    if ($endTime!=""){
	    	$where = $where." and DATEDIFF(createdtime, '".$endTime."') <= 0";
	    }
	    
		$sidx =trim($_REQUEST["sidx"]);
	    $sord =trim($_REQUEST["sord"]);
		if ($sidx == ""){
			$sidx = "id";
		}
	    if ($sord == ""){
	    	$sord = " desc ";
	    }	    
		$column=" * ";
		$tables=$common->prefix.'userinfo';
		$pagesize=$_REQUEST["rows"];
		if (!isset($currentPage)){
			$pagesize=30;
		}
		$currentPage=$_REQUEST["page"];
		if (!isset($currentPage)){
			$currentPage=1;
		}
		require_once("../data/db.php");
        $DB = new DB();
		$strSQL="select id from ".$tables." where ".$where." order by ".$sidx." ".$sord." ";
		
		$result=$DB->query($strSQL);
		$records=$DB->num_rows($result);
		unset($result);
		if (empty($currentPage)|| $currentPage<1){
			$currentPage=1;
		}
	    $pagenum   = (int) ceil($records / $pagesize); 
	    if ($currentPage>$pagenum){
	    	$currentPage=$pagenum;
	    }
	    $recordstart = ($currentPage - 1) *  $pagesize;
 		if($recordstart<0){
 			$recordstart=0;
 		}
		$strSQL='select '.$column.' from '.$tables.' where '.$where.'  order by '.$sidx.' '.$sord.'  limit '.$recordstart.','.$pagesize;
		  
	    $rows = $DB->get_all($strSQL);
	    $responce=(object)null;
	    $responce->page = $currentPage;
	    $responce->total = $pagenum; 
		$responce->records = $records;
		//echo(9);
	    $i=0; 
	    if(!empty($rows)){
	    	foreach($rows as $row){
	    		$responce->rows[$i]=$row;
	            $i++;
	    	}
	    }
		echo json_encode($responce);
	}
	
	function del()
	{
		if(isset($_REQUEST["id"])){
			require_once('../data/common.php');
            $common=new common();
            require_once("../data/db.php");
            $DB = new DB();
            $tables=$common->prefix.'userinfo';
            $ids=trim($_REQUEST["id"]);            
            $DB->delete($tables,'id in('.$ids.')');            
			return "1";
		}		
	}
?>