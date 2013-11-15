<?php
require_once('bae.php');
/*从环境变量里取出数据库连接需要的参数*/
$host = getenv('HTTP_BAE_ENV_ADDR_SQL_IP');
$port = getenv('HTTP_BAE_ENV_ADDR_SQL_PORT');
$user = getenv('HTTP_BAE_ENV_AK');
$pwd = getenv('HTTP_BAE_ENV_SK');
 
/*接着调用mysql_connect()连接服务器*/
$link = @mysql_connect("{$host}:{$port}",$user,$pwd,true);
if(!$link) {
    die("Connect Server Failed: " . mysql_error());
}
/*连接成功后立即调用mysql_select_db()选中需要连接的数据库*/
if(!mysql_select_db($dbname,$link)) {
    die("Select Database Failed: " . mysql_error($link));
}
/*至此连接已完全建立，就可对当前数据库进行相应的操作了*/
/*！！！注意，无法再通过本次连接调用mysql_select_db来切换到其它数据库了！！！*/
/* 需要再连接其它数据库，请再使用mysql_connect+mysql_select_db启动另一个连接*/
 
/**
 * 接下来就可以使用其它标准php mysql函数操作进行数据库操作
 */

/*测试使用*/
/*
$lat = $_GET['lat'];//GET方法为URL参数传递
$lng = $_GET['lng'];
$radius = $_GET['radius'];
$time = $_GET['time'];
$imei=$_GET['imei'];
*/

/*接收数据*/
$response=$_POST["locationdata"];
list($lng,$lat,$radius,$time,$imei) = split ('[,]', $response);

$result;

/*存在以imei为名的表则直接插入，否则新建以imei为名的表再插入数据*/
//if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$imei."'"))==1)
if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$imei."'"))==1)
{
	//$sql = "insert into `".$imei."`(lng,lat,radius,time) values ('$lng','$lat','$radius','$time')";
	$sql = "insert into `$imei`(lng,lat,radius,time) values ('$lng','$lat','$radius','$time')";
	mysql_query($sql);//借SQL语句插入数据
	mysql_close();//关闭MySQL连接
	$result="Insert Done";
}else
{
	/*在数据空中创建表*/
	//$sql = "CREATE TABLE `".$imei."` 
	$sql = "CREATE TABLE `$imei`
	(
	id int NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(id),
	lng double(9,6),
	lat double(9,6),
	radius float(5,1),
	time datetime
	)";
	mysql_query($sql,$link);
	//$insertsql = "insert into `".$imei."`(lng,lat,radius,time) values ('$lng','$lat','$radius','$time')";
	$insertsql = "insert into `$imei`(lng,lat,radius,time) values ('$lng','$lat','$radius','$time')";
	mysql_query($insertsql);//借SQL语句插入数据
	mysql_close();//关闭MySQL连接
	$result="Create Done";
}

mysql_close($link);
echo $result;

//echo "成功录入数据";

//phonelocation.duapp.com/post.php?lat=32.222222&lng=118.111111&radius=123.4&time=2013-1-30 13:36:26&imei=358961040257082
?>