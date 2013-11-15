var timer;     //定时器
var index = 0; //记录播放到第几个point
var followChk, playBtn, pauseBtn, resetBtn; //几个控制按钮
var label;
var traceMarker;
function iniTraceCtrl()
{
	followChk = document.getElementById("follow");
	playBtn = document.getElementById("play");
	pauseBtn = document.getElementById("pause");
	resetBtn = document.getElementById("reset");
	playBtn.disabled = false;
	resetBtn.disabled = false;
}

function showTraceCtrl()
{
	document.getElementById("controller").style.display="";
}

function hideTraceCtrl()
{
	document.getElementById("controller").style.display="none";
}

function play() 
{
	playBtn.disabled = true;
	pauseBtn.disabled = false;
	
	if(label!=null)
	{
		map.removeOverlay(label);
	}
	if(traceMarker!=null)
	{
		map.removeOverlay(traceMarker);
	}
	
	var point=new BMap.Point(locData[index][1],locData[index][2]);
	if(index==0)
	{
		map.clearOverlays();
		var startMarker=new BMap.Marker(point,{icon: new BMap.Icon("img/mapmarker.png", new BMap.Size(20, 20), {imageOffset: new BMap.Size(0, 0)})});
		map.addOverlay(startMarker);
		
	}
	label=new BMap.Label("", {position:point,offset: new BMap.Size(0, 0)});
	label.setContent(locData[index][4]);
	
	traceMarker=new BMap.Marker(point);
	map.addOverlay(traceMarker);
	
	if(index>0)
	{
		//add polyline
		var oldpoint=new BMap.Point(locData[index-1][1],locData[index-1][2]);
		var twoPointPolyline=new BMap.Polyline([oldpoint,point], {strokeColor:"blue", strokeWeight:6, strokeOpacity:0.5});
		map.addOverlay(twoPointPolyline);
		map.addOverlay(label);
		if(index==locData.length-1)
		{
			var endpoint=new BMap.Point(locData[index][1],locData[index][2]);
			var endMarker=new BMap.Marker(endpoint,{icon: new BMap.Icon("img/mapmarker.png", new BMap.Size(20, 20), {imageOffset: new BMap.Size(0, -20)})});
			map.addOverlay(endMarker);
		}
	}
	if(followChk.checked)
	{
		map.panTo(point);
	}
	if(index<locData.length)
	{
		timer = window.setTimeout("play(" + index + ")", 1000);
	}else
	{
		playBtn.disabled = true;
		pauseBtn.disabled = true;
		map.panTo(point);
	}
	index++;
}

function pause() 
{
	playBtn.disabled = false;
	pauseBtn.disabled = true;
	if(timer) {
		window.clearTimeout(timer);
	}
}

function reset() 
{
	followChk.checked = false;
	playBtn.disabled = false;
	pauseBtn.disabled = true;
	if(timer) {
		window.clearTimeout(timer);
	}
	index = 0;
}