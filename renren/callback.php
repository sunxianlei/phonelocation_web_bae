<?php
//授权回调页面，即配置文件中的$callback_url
session_start(); //此示例中要使用session
require_once('config.php');
require_once('renren.php');

if(isset($_GET['code']) && $_GET['code']!=''){
	$renren=new renrenPHP($renren_k, $renren_s);
	$result=$renren->access_token($callback_url, $_GET['code']);
}
if(isset($result['access_token']) && $result['access_token']!=''){
	echo '授权完成，请记录<br/>access token：<input size="50" value="',$result['access_token'],'"><br/>refresh token：<input size="50" value="',$result['refresh_token'],'">';

	//保存登录信息，此示例中使用session保存
	$_SESSION['renren_t']=$result['access_token']; //access token
	$_SESSION['renren_r']=$result['refresh_token']; //refresh token
}else{
	echo '授权失败';
}
echo '<br/><a href="demo.php">返回</a>';

?>