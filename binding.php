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
$imei=$_GET['imei'];;//GET方法为URL参数传递
$datatype = $_GET['datatype'];
$data = $_GET['data'];
$mode = $_GET['mode'];
*/


/*接收数据*/
$response=$_POST["bindingdata"];
list($imei,$datatype,$data,$mode) = split ('[,]', $response);

$result="";

$queryimei="select $datatype from binding where imei = '$imei'";//查找表中是否存在该imei项
$queryimeiResult=mysql_query($queryimei);
$queryimeiResultRowCount=mysql_num_rows($queryimeiResult);
if($queryimeiResultRowCount>0)
{
	switch($mode)
	{
	case "binding":
		if(mysql_query("update binding set $datatype = '$data' where imei = '$imei'"))
		{
			$result="Binding";
		}
		else
		{
			$result="UnBinding";
		}
		break;
	case "unbinding":
		if(mysql_query("update binding set $datatype = '' where imei = '$imei'"))
		{
			$result="UnBinding";
		}
		else
		{
			$result="Binding";
		}
		break;
	case "query":
		$queryimeiResultRow=mysql_fetch_row($queryimeiResult); 
		if($queryimeiResultRow[0])
		{
			if($queryimeiResultRow[0]==$data)
			{
				$result="Binding";
			}
			else
			{
				$result=$queryimeiResultRow[0];
			}
		}
		else
		{
			$result="UnBinding";
		}
		break;
	default:
		$result="YESDefault";
	}
}
else
{
	switch($mode)
	{
	case "binding":
		if(mysql_query("insert into binding (imei,$datatype) values ('$imei','$data')"))
		{
			$result="Binding";
		}
		else
		{
			$result="UnBinding";
		}
		break;
	case "unbinding":
		$result="UnBinding";
		break;
	case "query":
		$result="UnBinding";
		break;
	default:
		$result="NODefault";
	}
}



mysql_close();//关闭MySQL连接
mysql_close($link);
echo $result;


//echo "成功录入数据";

//phonelocation.duapp.com/binding.php?imei=358961040257082&datatype=renrenid&data=123456789&mode=binding
?>