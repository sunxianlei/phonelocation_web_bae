window.onload=function()
{
 var globalimei;
 var shade=document.getElementById("shade");
 var shadedownload=document.getElementById("shadedownload");
 var bt=document.getElementById("down");
 var btclose=document.getElementById("btclose");
 
 bt.onclick=function()
 {
  shade.style.display="block";
  shadedownload.style.display="block";
 }
 btclose.onclick=function()
 {
  shade.style.display="none";
  shadedownload.style.display="none"; 
 }
 
 if(document.getElementById("renrenphoto"))
 {
	var renrenid;
	setTab(0,1);
	document.getElementById("renrenid").style.visibility="visible";
	renrenid=document.getElementById("renrenid").innerText;
	document.getElementById("renrenid").style.visibility="hidden";
	GetIMEI("renrenid",renrenid);
 }
 
	iniTraceCtrl();
	document.getElementById("controller").style.display="none";
 
}
 
 function menuClick(srcname,btname)
 {
	var srcshade=document.getElementById(srcname);
	var closebt=document.getElementById(btname);
	shade.style.display="block";
	srcshade.style.display="block";
	closebt.onclick=function()
	 {
	  shade.style.display="none";
	  srcshade.style.display="none"; 
	 }
 }
function btnSearch()
{
	var datetime=document.getElementById("datepicker").value;
	var imei=document.getElementById("imei").value;
	
	if(!document.getElementById("renrenphoto"))
	{
		if(imei.length!=15)
		{
			new Toast({context:$('body'),message:"上方所输入的IMEI无效，请检查",top:'150px',time:'4000'}).show();
			return;
		}
	}
	else
	{
		imei=globalimei;
	}
	
	if(datetime=="")
	{
		new Toast({context:$('body'),message:"日期不能为空，请检查",top:'150px',time:'4000'}).show();
		return;
	}
	GetLocation(imei,datetime);
	showTraceCtrl();
}

function btnNow()
{
	if(locData!=null)
	{
		showLocation(locData);
		hideTraceCtrl();
	}
	else
	{
		new Toast({context:$('body'),message:"无可显示数据",top:'150px',time:'4000'}).show();
	}
}

function btnPath()
{
	if(locData!=null)
	{
		showPath(locData);
		showTraceCtrl();
	}
	else
	{
		new Toast({context:$('body'),message:"无可显示数据",top:'150px',time:'4000'}).show();
	}
}