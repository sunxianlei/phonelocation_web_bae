<?php 
require_once('bae.php');
$imei=$_GET["imei"];
$datetime=$_GET["datetime"];
if($datetime=="")
{
	$datetime=date('Y-m-d');
}
$Today_Yes_No="N!";
if($datetime==date('Y-m-d'))
{
	$Today_Yes_No="Y!";
}

$No_Infomation_Of_IMEI="0";//没有所查IMEI的相关记录
$No_Data_For_SearchDate="1";//存在所查IMEI的相关记录但所查日期当天没有数据记录

 
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
 

 //接下来就可以使用其它标准php mysql函数操作进行数据库操作
if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$imei."'"))==1)
{
	// 从表中提取信息的sql语句   
	$strsql="SELECT * FROM `".$imei."` where DATE(time)=\"".$datetime."\"";
    // 执行sql查询  
	$result=mysql_query($strsql, $link); 
  	if(mysql_num_rows($result) < 1)
    {
    	echo $No_Data_For_SearchDate;
    }
  	else
	{
    	// 获取查询结果    
    	$row=mysql_fetch_row($result); 
		// 定位到第一条记录   
    	mysql_data_seek($result, 0);   
    	// 循环取出记录   
		$j=0;
    	while($row=mysql_fetch_row($result))   
    	{ 	
			for ($i=0; $i<mysql_num_fields($result); $i++ )   
			{   
				$arraydata[$j][$i]=$row[$i]; 
			}
			$j++;
    	}   
    	// 释放资源   
    	mysql_free_result($result);
  		echo $Today_Yes_No.json_encode($arraydata);
  	} 
}else
{
  echo $No_Infomation_Of_IMEI;
}
//显式关闭连接，非必须
mysql_close($link);

//echo $imei;

//echo 'N!'.'[["1","119.140685","34.929775","45.0","2013-10-01 08:29:35"],["2","119.140813","34.929820","32.0","2013-09-29 11:46:33"]]';
?>  


