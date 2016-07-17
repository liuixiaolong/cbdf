<?php
    include_once('sessionfile.php');
    include_once('chkislogin.php');
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
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
	 	case "check":
	 	    check();
	 	    break;
	 	default:
	 	    break;
	}
	if($re!=''){
		echo($re);
	} 	
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
		if ($keyword!="") {$where =$where. "  and  (guid  like '%".$keyword."%')";}
	    if ($beginTime!=""){
	    	$where =$where. " and 0 <= DATEDIFF(createdtime, '".$beginTime."') ";
	    }
	    if ($endTime!=""){
	    	$where = $where." and DATEDIFF(createdtime, '".$endTime."') <= 0";
	    }	   
	    $imgischeck='';
	    if(isset($_REQUEST["imgischeck"])){
	    	$imgischeck=trim($_REQUEST["imgischeck"]);
	    	if($imgischeck!=''){
	    		$where.=' and ischeck=\''.$imgischeck.'\' ';
	    	}
	    }
	    
	    
		$sidx =trim($_REQUEST["sidx"]);
	    $sord =trim($_REQUEST["sord"]);
		if ($sidx == ""){
			$sidx = "id";
		}
	    if ($sord == ""){
	    	$sord = " desc ";
	    }	    
		$column=" id,guid,photo,isshare,ischeck,checkdate,createdtime,bigphoto,qty ";
		$tables=$common->prefix.'userphoto';
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
            $tables=$common->prefix.'userphoto';
            $ids=trim($_REQUEST["id"]);            
            $DB->delete($tables,'id in('.$ids.')');            
			return "1";
		}		
	}
	
	function check(){
		if(isset($_POST['id'])){
			$id=(int)trim($_POST['id']);
			if($id > 0){
				require_once('../data/common.php');
                $common=new common();
        
				require_once("../data/db.php");
                $DB = new DB();
                $table=$common->prefix.'userphoto';
                $sql='select isshare,ischeck from '.$table.' where id='.$id;
                $data=$DB->get_one($sql);
                if(!empty($data)){
                	$ischeck=$data['ischeck'];
                	if($ischeck=='Y'){
                		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，该照片已经审核您不能在审核！'));
                		return;
                	}                	
                	$result=$DB->update($table,array('ischeck'=>'Y','checkdate'=>date('Y-m-d H:i:s',time())),'id='.$id);
	                if($result==false){
	                	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，审核照片失败！'));
	                }else{
	                	$isshare=$data['isshare'];
	                	$html='<span style="color:green;">已审核</span>&nbsp;|&nbsp;';
	                	if($isshare=='N'){
	                		$html.='<span style="color:red;">未分享</span>';
	                	}else{
	                		$html.='<span style="color:green;">已分享</span>';
	                	}
	                	$common->resposne_json(array('result'=>1,'remark'=>$html));
	                }
                }else{
                	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，未找到您要的照片！'));
                }                
			}
		}
	}
?>