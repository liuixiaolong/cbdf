<?php
//设置session保存路径
$seesionpath =  dirname(__FILE__).'/savetempsessionfile';
ini_set('session.save_path',$seesionpath);
if (!session_id()){session_start();}
?>
