<?php 
require_once('bae.php');
$datatype=$_GET["datatype"];
$data=$_GET["data"];

$No_Binding_Of_IMEI="0";//没有所查IMEI的相关记录

//从环境变量里取出数据库连接需要的参数
$host = getenv('HTTP_BAE_ENV_ADDR_SQL_IP');
$port = getenv('HTTP_BAE_ENV_ADDR_SQL_PORT');
$user = getenv('HTTP_BAE_ENV_AK');
$pwd = getenv('HTTP_BAE_ENV_SK');
 
//接着调用mysql_connect()连接服务器
$link = @mysql_connect("{$host}:{$port}",$user,$pwd,true);
if(!$link) {
    die("Connect Server Failed: " . mysql_error());
}
//连接成功后立即调用mysql_select_db()选中需要连接的数据库
if(!mysql_select_db($dbname,$link)) {
    die("Select Database Failed: " . mysql_error($link));
}
//至此连接已完全建立，就可对当前数据库进行相应的操作了
//！！！注意，无法再通过本次连接调用mysql_select_db来切换到其它数据库了！！！
//需要再连接其它数据库，请再使用mysql_connect+mysql_select_db启动另一个连接
 

$result="";

$queryimei="select imei from binding where $datatype = '$data'";//查找表中是否存在该imei项
$queryimeiResult=mysql_query($queryimei);
$queryimeiResultRowCount=mysql_num_rows($queryimeiResult);
if($queryimeiResultRowCount>0)//存在和已知ID绑定的IMEI
{
	$queryimeiResultRow=mysql_fetch_row($queryimeiResult); 
	$result=$queryimeiResultRow[0];
}
else//不存在和已知ID绑定的IMEI
{
	$result=$No_Binding_Of_IMEI;
}

mysql_close();//关闭MySQL连接
mysql_close($link);
echo $result;

//echo 'N!'.'[["1","119.140685","34.929775","45.0","2013-10-01 08:29:35"],["2","119.140813","34.929820","32.0","2013-09-29 11:46:33"]]';
?>  


