
//当前位置按钮的响应函数
function showLocation(locData)
{
	if(responseResult[0]=="Y")
	{
		map.clearOverlays();
		showActPoint(locData[locData.length-1],true);
		PanToPoint(locData[locData.length-1][1],locData[locData.length-1][2]);
		if(timeID!=null)
		{
			window.clearInterval(timeID);
			timeID=window.setInterval("GetLocation(globalimei,'')",60*1000);
		}
	}
	else
	{
		new Toast({context:$('body'),message:"当前数据为历史数据，无法显示当前位置",top:'150px',time:'4000'}).show();
		showTraceCtrl();
	}
}
//今日轨迹以及历史查询按钮的响应函数
function showPath(locData)
{
	if(timeID!=null)
    {
    	window.clearInterval(timeID);
    }
	map.clearOverlays();
	DrawPointsAndPolyline(locData);
	showActPoint(locData[locData.length-1],false);
	PanToPoint(locData[locData.length-1][1],locData[locData.length-1][2]);
	
}
//添加当前位置点
function showActPoint(latestRow,showcircle)
{
	var myIcon = new BMap.Icon("http://api.map.baidu.com/img/markers.png", new BMap.Size(23, 25),{
		imageOffset: new BMap.Size(0, -250) // 设置图片偏移
	});
	
	var point=new BMap.Point(latestRow[1],latestRow[2]);
	var marker=new BMap.Marker(point,{icon:myIcon});
	map.addOverlay(marker);
	
	
	var str;
	if(showcircle==true)
	{
		var circle=new BMap.Circle(point,latestRow[3]);
		circle.setFillColor("#F6C4C4");
		map.addOverlay(circle);
		
		str="当前位置";
      	marker.setAnimation(BMAP_ANIMATION_BOUNCE);
	}else
	{
		str="最终位置";
	}
	
	
	var inWindow=new BMap.InfoWindow(str);
	marker.addEventListener("click", function(){          
		this.openInfoWindow(inWindow);
	});
	
	var label = new BMap.Label(latestRow[4],{offset:new BMap.Size(20,-10)});
	marker.setLabel(label);
}
//判断所给点是否在当前显示范围之内，如果否则移动过去
function PanToPoint(lng,lat)
{
	var bs=map.getBounds();
	var bssw=bs.getSouthWest();
	var bsne=bs.getNorthEast();
	if(lng<bssw.lng || lng>bsne.lng || lat<bssw.lat || lat>bsne.lat)
	{
		map.panTo(new BMap.Point(lng,lat));
	}
}
//在地图上添加点和线
function DrawPointsAndPolyline(locData)
{
	map.clearOverlays();

	var arrPoints=new Array();
	
	for(var i=0;i<locData.length;i++)
	{
		var point = new BMap.Point(locData[i][1],locData[i][2]);
		arrPoints[i]=point;
		if(i!=locData.length-1)
		{
			AddMarker(point,locData[i][4]);
		}
	}
	var polyline=new BMap.Polyline(arrPoints,{strokeColor:"blue", strokeWeight:6, strokeOpacity:0.5});
	map.addOverlay(polyline);
	
}
//添加标注信息
function AddMarker(point,str)
{
	var marker=new BMap.Marker(point);
	
	map.addOverlay(marker);
	str="这个时间出现在这里：<br >"+str;
	var inWindow=new BMap.InfoWindow(str);
	marker.addEventListener("click", function(){          
		this.openInfoWindow(inWindow);
	});
}