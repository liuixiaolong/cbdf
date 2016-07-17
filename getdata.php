<?php
header("Content-Type:text/html;charset=utf-8");

ini_set('session.save_path',dirname(__FILE__).'/manage/savetempsessionfile');
session_start();
	
date_default_timezone_set('PRC');
//get data
require_once('data/common.php');
$common=new common();
//判断数据提交方式
$ispost=$common->ispost();
$isdomain=$common->post_isdomain();
$action='';
if($ispost && $isdomain){
	//数据处理
	if(!isset($_POST['action'])){return;}
	$action=trim($_POST['action']);
}
require_once("data/db.php");
$DB = new DB();

switch ($action){
	case 'uid':
	    //get guid
	    $uid=getuid();
	    if($uid==''){
	    	$uid=mkuid($common,$DB);
	    }
	    $common->resposne_json(array('result'=>1,'remark'=>$uid));
	    break;
	case 'photo':
	    //上传照片
	    if(isset($_POST['diyphotobase'])){
	    	$diyphotobase=$_POST['diyphotobase'];
	    	if($diyphotobase==''){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	//获得uid
	    	$uid=getuid();
		    if($uid==''){
		    	$uid=mkuid($common,$DB);
		    }
	    	
			$sort=rand(0,100000);
			$photocode='';
			//照片名次
			if($sort < 10){
				$photocode.='0000';
			}else if($sort < 100){
				$photocode.='000';
			}
			else if($sort < 1000){
				$photocode.='00';
			}else if($sort < 10000){
				$photocode.='0';
			}
			$photocode=date('YmdHis',time()).$photocode;
	    	
	    	$img = str_replace('data:image/png;base64,', '', $diyphotobase);
            $img = str_replace(' ', '+', $img);
	    	$img = base64_decode($img);
	    	//$handle = fopen("mylog.txt", "a+");
			//fwrite($handle,$sql."\r\n");
			//fwrite($handle,date('Y-m-d H:i:s',time())."\r\n");
			//fwrite($handle,$diyphotobase."\r\n");
			//fclose($handle);
	    	$t = $photocode.'.png';
	    	$filepath=date('Ymd',time());
	    	//创建文件夹
	    	if(!file_exists('upload/'.$filepath)){
	    		mkdir('upload/'.$filepath,0777);
	    	}
	    	//生成小图300px
	    	$smallimg=$filepath.'/'.$photocode.'-s.png';
	    	$save = file_put_contents('upload/'.$filepath.'/'.$t,$img);
	    	$filepath.='/'.$t;	    	
	    	$result_img=CreateThumbnail('upload/'.$filepath,'upload/'.$smallimg);
	    	if($result_img['result']==0){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，上传照片失败！'));
	    	}
	    	
	    	$dataArray=array();
	    	$dataArray['guid']=$uid;
	    	$dataArray['photo']=$smallimg;
	    	$dataArray['isshare']='N';
	    	$dataArray['ischeck']='N';
	    	$dataArray['createdtime']=date('Y-m-d H:i:s',time());
	    	$dataArray['qty']=0;
	    	$dataArray['photobase64']=$diyphotobase;
	    	$dataArray['bigphoto']=$filepath;
	    	$table=$common->prefix.'userphoto';
	    	$result=$DB->insert($table,$dataArray);
	    	if($result){
	    		$photoid=$DB->insert_id();
	    		$common->resposne_json(array('result'=>1,'id'=>$photoid,'photo'=>$smallimg));
		    }else{
		    	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，上传照片失败！'));
		    }
	    }
	    break;
	case 'share':
	    //分享照片
	    if(isset($_POST['id']) && isset($_POST['uid'])){
	    	$id=trim($_POST['id']);
	    	$uid=trim($_POST['uid']);
	    	if($id=='' || $uid==''){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	
	    	if((int)$id <= 0){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	
	    	$table=$common->prefix.'userphoto';
	    	$sql='select id from '.$table.' where id='.$id.' and guid=\''.$uid.'\' ';
	    	$data=$DB->get_one($sql);
	    	if(empty($data)){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，未找到您要分享数据！'));
	    	}
	    	$dataArray=array(
	    	   'isshare'=>'Y'
	    	);
	    	$result=$DB->update($table,$dataArray,'id='.$id.' and guid=\''.$uid.'\'');
	    	if($result){
	    		$common->resposne_json(array('result'=>1,'remark'=>'分享照片成功！'));
	    	}else{
	    		$common->resposne_json(array('result'=>1,'remark'=>'抱歉，分享照片失败！'));
	    	}
	    }
	    break;
	case 'saveUserForm':
	    //保存联系方式
	    if(isset($_POST['mobile']) && isset($_POST['username']) && isset($_POST['agree']) && isset($_POST['imageid'])){
	    	$mobile=trim($_POST['mobile']);
	    	$username=trim($_POST['username']);
	    	$agree=trim($_POST['agree']);
	    	$imageid=trim($_POST['imageid']);
	    	//if($mobile=='' || $contact=='' || $agree!='' || $imageid!=''){
		    if($mobile=='' || $username==''||$agree=='' || $imageid==''){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	
	    	//手机号唯一
	    	$table=$common->prefix.'userinfo';
	    	$sql='select id from '.$table.' where mobile=\''.$mobile.'\' ';
	    	$data=$DB->get_one($sql);
	    	if(!empty($data)){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，该手机号已经存在！'));
	    	}
	    	
	    	//获得uid
	    	$uid=getuid();
		    if($uid==''){
		    	$uid=mkuid($common,$DB);
		    }
		    
		    $table=$common->prefix.'userphoto';
		    if($agree=='yes'){
		    	$agree='Y';
		    }else{
		    	$agree='N';
		    }
		    $dataArray=array(
	    	   'isshare'=>$agree
	    	);
	    	$result=$DB->update($table,$dataArray,'id='.$imageid.' and guid=\''.$uid.'\'');
		    
	    	$table=$common->prefix.'userinfo';
		    $dataArray=array();
		    $dataArray['guid']=$uid;
		    $dataArray['mobile']=$mobile;
		    $dataArray['username']=$username;
		    $dataArray['createdtime']=date('Y-m-d H:i:s',time());
		    $dataArray['isdraw']='N';
		    $result=$DB->insert($table,$dataArray);
		    if($result){
		    	$common->resposne_json(array('result'=>1,'remark'=>'恭喜您，保存联系方式成功！'));
		    }else{
		    	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，系统正忙请稍后保存联系方式！'));
		    }
	    }
	    break;
	case 'plike':
	    //照片点赞
	    if(isset($_POST['id']) && isset($_POST['uid'])){
	    	$id=trim($_POST['id']);
	    	$uid=trim($_POST['uid']);
	    	
	    	if($id=='' || $uid==''){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	
	    	if((int)$id <= 0){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
		    
		    $qty=1;$isadd=0;
		    //判断当前点过几次
		    $table=$common->prefix.'userlinks';
		    $sql='select qty from '.$table.' where guid=\''.$uid.'\' and datediff(lastlikesdate,\''.date('Y-m-d H:i:s',time()).'\')=0 ';
		    $data=$DB->get_one($sql);
		    if(!empty($data)){
		    	$qty=(int)$data['qty']+1;
		    	$isadd=1;
		    }
		    if($qty>10){
		    	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，照片您已经点赞过10次！'));
		    }else{
		    	
		    	 $sql='select qty from '.$table.' where guid=\''.$uid.'\'';
		         $data=$DB->get_one($sql);
		         if(!empty($data)){
		         	$isadd=1;
		         }
		    	
		    	 $mydata['qty']=$qty;
		    	 $mydata['lastlikesdate']=date('Y-m-d H:i:s',time());
		    	 if($isadd==0){
		    	 	$mydata['guid']=$uid;
		    	 	$DB->insert($table,$mydata);
		    	 }else{
		    	 	$DB->update($table,$mydata,'guid=\''.$uid.'\'');
		    	 }
		    }
		    
		    $table=$common->prefix.'userphoto';
		    $sql='select qty,ischeck from '.$table.' where id='.$id;
		    $data=$DB->get_one($sql);
		    if(empty($data)){
		    	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，未找到您要点赞照片！'));
		    }else{
		    	$ischeck=$data['ischeck'];
		    	if($ischeck=='N'){
		    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，该照片还未审核，您不能点赞！'));
		    	}
		    	$qty=(int)$data['qty']+1;
		    }
		    
		    $dataArray=array();		    
		    $dataArray['qty']=$qty;		   
		    $result=$DB->update($table,$dataArray,'id='.$id);
		    
		    if($result){
		    	$common->resposne_json(array('result'=>1,'remark'=>'照片点赞成功！','qty'=>$qty));
		    }else{
		    	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，系统正忙请稍后对照片点赞！'));
		    }
	    }
	    break;
	case 'photodetail':
	    //通过分享点击过来，查看图片详情
	    if(isset($_POST['id'])){
	    	$id=trim($_POST['id']);
	    	if($id==''){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	
	    	if((int)$id <= 0){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	$table=$common->prefix.'userphoto';
		    $sql='select photo,qty,bigphoto from '.$table.' where id='.$id;
		    $data=$DB->get_one($sql);
		    if(!empty($data)){
		    	//返回图片src地址、分享次数json数据
		    	$common->resposne_json(array('result'=>1,'photo'=>$data['photo'],'qty'=>$data['qty'],'bigphoto'=>$data['bigphoto']));
		    }else{
		    	$common->resposne_json(array('result'=>0,'remark'=>'抱歉，未找到您要的数据！'));
		    }
	    }
	    break;
	case 'list':
	    //获得照片瀑布流数据(sortby、pageid、pagesize)--排序方式、当前索引页(0开始)、每页显示照片数量
	    if(isset($_POST['sortby']) && isset($_POST['pageid']) && isset($_POST['pagesize']) && isset($_POST['uid'])){
	    	$sortby=trim($_POST['sortby']);
	    	$pageid=trim($_POST['pageid']);
	    	$pagesize=trim($_POST['pagesize']);
	    	$uid=trim($_POST['uid']);
	    	
	    	if($sortby=='' || $pageid=='' || $pagesize=='' || $uid==''){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	
	    	if((int)$pageid < 0 || (int)$pagesize<=0){
	    		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
	    	}
	    	$pageid=(int)$pageid+1;
	    	//日期、点击数
	    	if($sortby!='likes'){
	    		$sortby='date';
	    	}
	    	
	    	$isexists=0;
	    	//判断uid是否存在
	    	$table=$common->prefix.'useruid';
	    	$sql='select id from '.$table.' where uid=\''.$uid.'\' ';
		    $data=$DB->get_one($sql);
		    if(!empty($data)){
		    	$isexists=1;
		    	//$common->resposne_json(array('result'=>0,'remark'=>'抱歉，传递参数有误！'));
		    }
		   
		    //这里考虑要将自己排在前面
		    $qty=0;
		    $table=$common->prefix.'userphoto';
		    $sql='select count(1) as qty from '.$table.' where guid=\''.$uid.'\' ';
		    $data=$DB->get_one($sql);
		    if(!empty($data)){
		    	$qty=(int)$data['qty'];
		    }
		    
		    $userpage=0;
		    //计算当前用户上传照片页数
		    if($qty%$pagesize==0){
		    	$userpage=$qty/$pagesize;
		    }else{
		    	$userpage=(($qty-$qty%$pagesize)/$pagesize)+1;
		    }
		    $returndata=array();
		    if($userpage>$pageid){
		    	$sql='select id,photo,qty,ischeck from '.$table.' where guid=\''.$uid.'\' order by ';
		    	if($sortby=='likes'){
		    		$sql.=' qty desc ';
		    	}else{
		    		$sql.=' createdtime desc ';
		    	}
		    	$sql.=',id desc limit '.(($pageid-1)*$pagesize).','.$pagesize;
		    	$data=$DB->get_all($sql);
		    	if(!empty($data)){
		    		$i=0;
		    		foreach($data as $k=>$v){
		    			$returndata[$i]=array(
		    			    'id'=>$v['id'],
		    			    'photo'=>$v['photo'],
		    			    'qty'=>$v['qty'],
		    			    'ischeck'=>$v['ischeck']
		    			);
		    			++$i;
		    		}
		    	}
		    }else if($userpage<$pageid){
		    	$sql='select id,photo,qty,ischeck from '.$table.' where guid<>\''.$uid.'\' and ischeck=\'Y\' order by ';
		    	if($sortby=='likes'){
		    		$sql.=' qty desc ';
		    	}else{
		    		$sql.=' createdtime desc ';
		    	}
		    	$sql.=',id desc limit '.(($pageid-1)*$pagesize).','.$pagesize;
		    	$data=$DB->get_all($sql);
		    	if(!empty($data)){
		    		$i=0;
		    		foreach($data as $k=>$v){
		    			$returndata[$i]=array(
		    			    'id'=>$v['id'],
		    			    'photo'=>$v['photo'],
		    			    'qty'=>$v['qty'],
		    			    'ischeck'=>$v['ischeck']
		    			);
		    			++$i;
		    		}
		    	}
		    }else{
		    	//获得当前用户最后一页数量
		    	//$qty=$userpage*$pagesize-$qty;
		    	$qty=$qty%$pagesize;
		    	
		    	$i=0;
		    	//取用户的数量
		    	$sql='select id,photo,qty,ischeck from '.$table.' where guid=\''.$uid.'\' order by ';
		    	if($sortby=='likes'){
		    		$sql.=' qty desc ';
		    	}else{
		    		$sql.=' createdtime desc ';
		    	}
		    	$sql.=',id desc limit '.(($pageid-1)*$pagesize).','.$qty;
		    	$data=$DB->get_all($sql);
		    	if(!empty($data)){		    		
		    		foreach($data as $k=>$v){
		    			$returndata[$i]=array(
		    			    'id'=>$v['id'],
		    			    'photo'=>$v['photo'],
		    			    'qty'=>$v['qty'],
		    			    'ischeck'=>$v['ischeck']
		    			);
		    			++$i;
		    		}
		    	}
		    	
		    	//取剩余的数据
		    	$sql='select id,photo,qty,ischeck from '.$table.' where guid<>\''.$uid.'\' and ischeck=\'Y\' order by ';
		    	if($sortby=='likes'){
		    		$sql.=' qty desc ';
		    	}else{
		    		$sql.=' createdtime desc ';
		    	}
		    	$sql.=',id desc limit 0,'.($pagesize-$qty);
		    	$data=$DB->get_all($sql);
		    	if(!empty($data)){
		    		foreach($data as $k=>$v){
		    			$returndata[$i]=array(
		    			    'id'=>$v['id'],
		    			    'photo'=>$v['photo'],
		    			    'qty'=>$v['qty'],
		    			    'ischeck'=>$v['ischeck']
		    			);
		    			++$i;
		    		}
		    	}		    	
		    }
		    if(count($returndata)==0){
		    	$common->resposne_json(array('result'=>1,'total'=>0,'remark'=>'抱歉，暂无数据！'));
		    }else{
		    	$total=0;
		    	$table=$common->prefix.'userphoto';
			    $sql='select count(1) as qty from '.$table.' where  guid<>\''.$uid.'\' and ischeck=\'Y\' ';
			    $data=$DB->get_one($sql);
			    if(!empty($data)){
			    	$total=$total+(int)$data['qty'];
			    }			    
			    $sql='select count(1) as qty from '.$table.' where guid=\''.$uid.'\' ';
			    $data=$DB->get_one($sql);
			    if(!empty($data)){
			    	$total=$total+(int)$data['qty'];
			    }
		    	//list即照片列表json
		    	$common->resposne_json(array('result'=>1,'total'=>$total,'list'=>$returndata));
		    } 
	    }
	    break;
	case 'makephoto':
	    //缩略图
	    makephoto('20151125/1.jpg',$common);
	    break;
	default:
		break;
}

function getuid(){
	//获得uid
	$uid='';
	if(isset($_SESSION["uid"])){
	   $uid=$_SESSION["uid"];
	}
	//如果$uid=''从cookies中获取
	if($uid==''){
	  if(isset($_COOKIE["uid"])){
	     if($_COOKIE["uid"]!=''){
		    $uid = $_COOKIE["uid"];
	     }
	  }
	} 
	return $uid;
}

function mkuid($common,$DB){
	$uid=date('YmdHis').$common->get_password_salt(8);
	$table=$common->prefix.'useruid';
	$sql='select id from '.$table.' where uid=\''.$uid.'\' ';
	$data=$DB->get_one($sql);
	if(!empty($data)){
		unset($data);
		return mkuid($common,$DB);
	}else{
		$dataArray=array(
		   'uid'=>$uid,
		   'created'=>date('Y-m-d H:i:s',time()),
		   'utype'=>'mobile',
		   'clientip'=>$common->get_clientip()
		);
		$result=$DB->insert($table,$dataArray);
		//保存$uid
        $_SESSION["uid"]=$uid;			  
        //cookies 60天失效
        setcookie("uid", $uid, time()+3600*24*60);
		return $uid;
	}
}

function makephoto($imgpath,$common){
	$imgpath='upload/'.$imgpath;
	if(!file_exists($imgpath)){
		$common->resposne_json(array('result'=>0,'remark'=>'抱歉，图片不存在！'));
	}
	$result=CreateThumbnail($imgpath,'upload/20151125/'.date('YmdHis'.rand(100,999)).'.png');
	$common->resposne_json($result);
}

function CreateThumbnail($srcFile, $toFile="") 
 {
     if ($toFile == "")
     { 
            $toFile = $srcFile; 
     }
     $info = "";
     //返回含有4个单元的数组，0-宽，1-高，2-图像类型，3-宽高的文本描述。
     //失败返回false并产生警告。
     $data = getimagesize($srcFile, $info);
     if (!$data)
         return array('result'=>0,'remark'=>'上传图片格式有误！');
     
     //将文件载入到资源变量im中
     switch ($data[2]) //1-GIF，2-JPG，3-PNG
     {
	     case 1:
	         if(!function_exists("imagecreatefromgif"))
	         {
	             return array('result'=>0,'remark'=>"the GD can't support .gif, please use .jpeg or .png");
	             exit();
	         }
	         $im = imagecreatefromgif($srcFile);
	         break;
	         
	     case 2:
	         if(!function_exists("imagecreatefromjpeg"))
	         {
	         	 return array('result'=>0,'remark'=>"the GD can't support .jpeg, please use other picture");
	             exit();
	         }
	         $im = imagecreatefromjpeg($srcFile);
	         break;
	     case 3:
	         $im = imagecreatefrompng($srcFile);    
	         break;
     }
     
     //计算缩略图的宽高
     $srcW = imagesx($im);
     $srcH = imagesy($im);
     $toW = 300;
     $toH = (int)($toW * ($srcH / $srcW));
     
     if (function_exists("imagecreatetruecolor")) 
     {
         $ni = imagecreatetruecolor($toW, $toH); //新建一个真彩色图像
         if ($ni) 
         {
             //重采样拷贝部分图像并调整大小 可保持较好的清晰度
             imagecopyresampled($ni, $im, 0, 0, 0, 0, $toW, $toH, $srcW, $srcH);
         } 
         else 
         {
             //拷贝部分图像并调整大小
             $ni = imagecreate($toW, $toH);
             imagecopyresized($ni, $im, 0, 0, 0, 0, $toW, $toH, $srcW, $srcH);
         }
      }
      else 
      {
         $ni = imagecreate($toW, $toH);
         imagecopyresized($ni, $im, 0, 0, 0, 0, $toW, $toH, $srcW, $srcH);
      }
 
     //保存到文件 统一为.png格式
     imagepng($ni, $toFile); //以 PNG 格式将图像输出到浏览器或文件
     ImageDestroy($ni);
     ImageDestroy($im);
     return array('result'=>1,'remark'=>$toFile);
 }

?>
