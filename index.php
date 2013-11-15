<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>位置通告</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
		<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-datepicker.js"></script>
		<script type="text/javascript" src="js/location.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/toast.js"></script>
		<script type="text/javascript" src="js/bmap.js"></script>
		<script type="text/javascript" src="js/trace.js"></script>
  	</head>
    <body>
	<div id="map">
	</div>
	<div id="top">
		<img id="logo" src="img/logo.png" alt="手机定位"/>
		<ul id="menu" class="black">
			<li class="active"><a>&nbsp;&nbsp;首 页&nbsp;&nbsp;</a></li>
          	<li><a onclick="menuClick('shadedownload','btclose');">&nbsp;&nbsp;下 载&nbsp;&nbsp;</a></li>
			<li><a onclick="menuClick('shadehelp','btclosehelp');">&nbsp;&nbsp;帮 助&nbsp;&nbsp;</a></li>
			<li><a onclick="menuClick('shadeabout','btcloseabout');">&nbsp;&nbsp;关 于&nbsp;&nbsp;</a></li>
			<li><a onclick="menuClick('shadecontact','btclosecontact');">&nbsp;&nbsp;联 系&nbsp;&nbsp;</a></li>
		</ul>
	</div>
	
	<!--
	<div id="inputimei">
	<form> 
	IMEI:<input type="text" id="imei"onkeyup="GetLocation(this.value,'')"value="请输入手机序列号" onclick="if(value==defaultValue){value='';this.style.color='#000'}" onBlur="if(!value){value=defaultValue;this.style.color='#999'}" style="color:#999">
	<p><span id="txtHin">手机拨号面板输入*#06#可查</span></p>
	</form>
	</div>
	-->
	<div id="txtHint">
	<p><span id="txtHint">手机拨号面板输入*#06#可查IMEI</span></p>
	</div>
	<!--第一种形式-->
	<div id="tabs0">
		 <ul class="menu0" id="menu0">
			  <li onclick="setTab(0,0)" class="hover">IMEI</li>
			  <li onclick="setTab(0,1)">人人</li>
			  <li onclick="setTab(0,2)"><font color="black">.</font></li>
			  <li onclick="setTab(0,3)"><font color="black">.</font></li>
		 </ul>
		 <div class="main" id="main0">
			  <ul class="block"><li>
					<form> 
					IMEI:<input type="text" id="imei" onkeyup="GetLocation(this.value,'')"value="请输入手机序列号" onclick="if(value==defaultValue){value='';this.style.color='#000'}" onBlur="if(!value){value=defaultValue;this.style.color='#999'}" style="color:#999">
					
					</form></li>
			  </ul>
		  <ul>
			<li>			
		<?php
		session_start(); //此示例中要使用session
		require_once('renren/config.php');
		require_once('renren/renren.php');
		
		if(!isset($_GET['logout']))
		{
			$renren_t=isset($_SESSION['renren_t'])?$_SESSION['renren_t']:'';

			if(isset($_GET['code']) && $_GET['code']!=''){
				$renren=new renrenPHP($renren_k, $renren_s);
				$result=$renren->access_token($callback_url, $_GET['code']);
			}
			if(isset($result['access_token']) && $result['access_token']!=''){
				//保存登录信息，此示例中使用session保存
				$_SESSION['renren_t']=$result['access_token']; //access token
				$_SESSION['renren_r']=$result['refresh_token']; //refresh token
			}
			//检查是否已登录
			if($renren_t!=''){
				$renren=new renrenPHP($renren_k, $renren_s, $renren_t);

				
				//获取登录用户信息
				$result=$renren->me();
				echo '<img id="renrenphoto" src='.$result->response->avatar[0]->url.' height=35px wight=35px>';
				echo '当前用户：';
				echo $result->response->name;
				echo '<a href="http://3.phonelocation.duapp.com/index.php?logout=true">退出</a>';
				//echo $result->response->avatar[0]->url;
				echo '<p id="renrenid" style="visibility:hidden">'.$result->response->id.'<p>';
			}else{
				//生成登录链接
				$renren=new renrenPHP($renren_k, $renren_s);
				$login_url=$renren->login_url($callback_url, $scope);
				echo '<a href="',$login_url,'"><img src="img/renren_login.png"/></a>';
			}
		}
		else
		{
			session_destroy();
			//生成登录链接
			$renren=new renrenPHP($renren_k, $renren_s);
			$login_url=$renren->login_url($callback_url, $scope);
			echo '<a href="',$login_url,'"><img src="img/renren_login.png"/></a>';
		}
		
		?>
			</li>
		  </ul>
		  <ul><li></li></ul>
		  <ul><li></li></ul>
		 </div>
	</div>
	<div id="share">
		<!-- JiaThis Button BEGIN -->
		<div class="jiathis_style">
		<a class="jiathis_button_tsina"></a>
		<a class="jiathis_button_renren"></a>
		<a class="jiathis_button_weixin"></a>
		<a class="jiathis_button_qzone"></a>
		<a class="jiathis_button_tqq"></a>
		<a class="jiathis_button_kaixin001"></a>
		<a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jiathis_separator jtico jtico_jiathis" target="_blank"></a>
		</div>
		<script type="text/javascript" >
		var jiathis_config={
			url:"http://phonelocation.duapp.com",
			summary:"安装手机软件，即可在网页端实时跟踪监控手机的位置，不信你就试试看",
			title:"位置通告 #位置通告#",
			pic:"http://phonelocation.duapp.com/img/sample.jpg",
			shortUrl:false,
			hideMore:false
		}
		</script>
		<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
		<!-- JiaThis Button END -->

	</div>
	<div id="locBtn">
		<button type="button" class="locbutton" onclick="btnNow();">当前位置</button>
		<button type="button" class="locbutton" onclick="btnPath();">今日轨迹</button>
		</br></br>
		<input type="text" id="datepicker" style="width:68px;" readOnly="true"/>
		<button type="button" class="locbutton" onclick="btnSearch();">历史查询</button>
	</div>
	<div id="download">
		<button id="down"><img src="img/download.png" alt="下载安装手机应用"/></button>
      	<br/>
		<a href="http://webscan.360.cn/index/checkwebsite/url/phonelocation.duapp.com"><img border="0" src="http://img.webscan.360.cn/status/pai/hash/0a15b36840f019c9f9297712ab28d6ee"/></a><br />
      	<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_5672651'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s9.cnzz.com/stat.php%3Fid%3D5672651' type='text/javascript'%3E%3C/script%3E"));</script>
		
	</div>
	<div class="shade" id="shade"></div>
	
	<div class="shadedownload" id="shadedownload"><button id="btclose" class="btclose"><img src="img/close.png" alt="关闭"/></button>
		<iframe src="page/download.html" class="frame"></iframe>
	</div> 
	<div class="shadedownload" id="shadecontact" ><button id="btclosecontact" class="btclose"><img src="img/close.png" alt="关闭"/></button>
		<iframe src="page/contact.html" class="frame"></iframe>
	</div> 
	<div class="shadedownload" id="shadehelp" ><button id="btclosehelp" class="btclose"><img src="img/close.png" alt="关闭"/></button>
		<iframe src="page/help.html" class="frame"></iframe>
	</div> 
	<div class="shadedownload" id="shadeabout" ><button id="btcloseabout" class="btclose"><img src="img/close.png" alt="关闭"/></button>
		<iframe src="page/about.html" class="frame"></iframe>
	</div> 
	<div id="controller" align="center">
		<input id="follow" type="checkbox"><span style="font-size:12px;">画面跟随</span></input>
		<input id="play" type="button" value="播放" onclick="play();" disabled />
		<input id="pause" type="button" value="暂停" onclick="pause();" disabled />
		<input id="reset" type="button" value="重置" onclick="reset()" disabled />
	</div>
	<script type="text/javascript" >
	/*第一种形式 第二种形式 更换显示样式*/
	function setTab(m,n){
	 var tli=document.getElementById("menu"+m).getElementsByTagName("li");
	 var mli=document.getElementById("main"+m).getElementsByTagName("ul");
	 for(i=0;i<tli.length;i++){
	  tli[i].className=i==n?"hover":"";
	  mli[i].style.display=i==n?"block":"none";
	  mli[i].style.float="left";
	  mli[i].style.listStyleType="none";
	 }
	}
	
	var $_GET = (function(){
		var url = window.document.location.href.toString();
		var u = url.split("?");
		if(typeof(u[1]) == "string"){
			u = u[1].split("&");
			var get = {};
			for(var i in u){
				var j = u[i].split("=");
				get[j[0]] = j[1];
			}
			return get;
		} else {
			return {};
		}
	})();
	
	if($_GET["code"] && $_GET["code"]!="")
	{
		location.replace("http://3.phonelocation.duapp.com");
	}
	if($_GET["logout"] && $_GET["logout"]!="")
	{
		location.replace("http://3.phonelocation.duapp.com");
		var cookies = document.cookie.split(";");
		for (var i = 0; i < cookies.length; i++) {
			var cookie = cookies[i];
			var eqPos = cookie.indexOf("=");
			var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
			document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
		}
		if(cookies.length > 0)
		{
			for (var i = 0; i < cookies.length; i++) {
				var cookie = cookies[i];
				var eqPos = cookie.indexOf("=");
				var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
			var domain = location.host.substr(location.host.indexOf('.'));
				document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; domain=" + domain;
			}
		}
	}
	</script>

</body>
</html>
<script type="text/javascript">
	// 百度地图API功能
	var map = new BMap.Map("map");            // 创建Map实例
	var point = new BMap.Point(118.763903, 32.058586);    // 创建点坐标
	map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_LEFT, type: BMAP_NAVIGATION_CONTROL_LARGE,offset:new BMap.Size(5,150)}));  //添加默认缩放平移控件
	map.addControl(new BMap.MapTypeControl({mapTypes: [BMAP_NORMAL_MAP,BMAP_HYBRID_MAP],offset:new BMap.Size(180,140)}));     //2D图，卫星图
	map.centerAndZoom(point,15);                     // 初始化地图,设置中心点坐标和地图级别。
	map.enableScrollWheelZoom();                            //启用滚轮放大缩小

	function myFun(result){
		var cityName = result.name;
		map.setCenter(cityName);
		new Toast({context:$('body'),message:'您当前所在地为：'+cityName,top:'150px',time:'5000'}).show();  
	}
	var myCity = new BMap.LocalCity();
	myCity.get(myFun);

	$(function(){
		$("#datepicker").datepicker();
	});

</script>










