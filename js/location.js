var xmlHttp;
var locData;
var timeID;
var responseResult;
function GetLocation(imei,datetime)
{
  	globalimei=imei;
	if (imei.length!=15 && imei.length!=0)
	{ 
		document.getElementById("txtHint").innerHTML="手机序列号只能是15位"
		return
	}else if(imei.length==0)
	{
		document.getElementById("txtHint").innerHTML="手机拨号面板输入*#06#可查"
		return
	}
    else
    {
    	document.getElementById("txtHint").innerHTML="正在查询，请稍后"
    }
	
	
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	
	var url="data.php"
    url=url+"?imei="+imei
	url=url+"&datetime="+datetime
    url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
	
	
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
   	if(timeID!=null)
    {
    	window.clearInterval(timeID);
    }
   
	if(parseInt(xmlHttp.responseText)==0)
	{
		document.getElementById("txtHint").innerHTML="没有所查IMEI的相关记录"
		new Toast({context:$('body'),message:"没有所查IMEI的相关记录",top:'150px',time:'4000'}).show()
	}
	else if(parseInt(xmlHttp.responseText)==1)
	{
		document.getElementById("txtHint").innerHTML="该IMEI无所查日期当天数据记录"
		new Toast({context:$('body'),message:"该IMEI无所查日期当天数据记录",top:'150px',time:'4000'}).show()
	}
	else
	{
		document.getElementById("txtHint").innerHTML="查询到结果，已显示"
		new Toast({context:$('body'),message:"查询到结果，已显示",top:'150px',time:'4000'}).show()
        responseResult=new Array();
      	responseResult=xmlHttp.responseText.split("!");
		eval("locData="+responseResult[1]);
      	if(responseResult[0]=="Y")
        {
        	//服务器显示返回的为当天数据，应该启动一个定时器定时再次获取数据，调用显示当前
          	timeID=window.setInterval("GetLocation(globalimei,'')",60*1000);
			showLocation(locData);
        }
      	else
        {
        	//服务器显示返回的为历史数据，跟定时器无关，直接调用显示轨迹的函数显示为轨迹即可
			showPath(locData);
        }
	}
	
 }
}

function GetIMEI(datatype,data)
{
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	
	var url="getimei.php"
    url=url+"?datatype="+datatype
	url=url+"&data="+data
    url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChangedIMEI
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)	
}

function stateChangedIMEI() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
   
	if(parseInt(xmlHttp.responseText)==0)
	{
		document.getElementById("txtHint").innerHTML="当前登陆账户未绑定相应IMEI"
		new Toast({context:$('body'),message:"当前登陆账户未绑定相应IMEI",top:'150px',time:'4000'}).show()
	}
	else
	{
		globalimei=xmlHttp.responseText
		globalimei=globalimei.replace(/(^\s*)|(\s*$)/g, "")
		GetLocation(globalimei,'')
	}
	
 }
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 // Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}





