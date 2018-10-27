
"use strict"
/* */
var map = '';
function loadScript() {  
	var oHead = document.getElementsByTagName('body').item(0); 
	var script = document.createElement("script");  
	script.type = "text/javascript"; 
	script.src = "http://api.map.baidu.com/api?v=3.0&ak=ONU2RP7mL0D1sQ06HVG04S7I1IRNXGmb&callback=initialize";
	oHead.appendChild( script);
}  
loadScript(); 

function initialize() {
	var map = new BMap.Map('allmap');  
	map.centerAndZoom(new BMap.Point(121.491, 31.233), 11);  

	longitude = longitude == '' ? 121.480174 : longitude;
	latitude = latitude == '' ? 31.236428 : latitude;
	var point = new BMap.Point(longitude, latitude);
	map.centerAndZoom(point,18);
	
	var marker = new BMap.Marker(point);  // 创建标注
	map.addOverlay(marker);               // 将标注添加到地图中
	marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
	
	function myLocation(result){
		var cityName = result.name;
		map.setCenter(cityName);
	}
	if(longitude == '121.480174' || latitude == '31.236428') {
		var myCity = new BMap.LocalCity();
		myCity.get(myLocation);
	}
	
	function G(id) {
		return document.getElementById(id);
	}
	var ac = '';
	if(ac == '') {
		var ac = new BMap.Autocomplete({    //建立一个自动完成的对象
			"input" : "address","location" : map
		});
		ac.setInputValue(map_address);  
	}
	/**/
	ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
		var str = "";
		var _value = e.fromitem.value;
		var value = "";
		if (e.fromitem.index > -1) {
			value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
		}    
		str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;
		
		value = "";
		if (e.toitem.index > -1) {
			_value = e.toitem.value;
			value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
		}    
		str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
		G("searchResultPanel").innerHTML = str;
	});
	var myValue = '';
	ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
		var _value = e.item.value;
		myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
		G("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;
		setPlace();
	});
	
	function setPlace(){//鼠标点击下获取地址和经纬度
		map.clearOverlays();    //清除地图上所有覆盖物
		function mySetMap(){
			var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
			map.centerAndZoom(pp, 18);
			marker = new BMap.Marker(pp);
			marker.enableDragging();
			map.addOverlay(marker);    //添加标注
			$('#longitude').val(pp.lng);
			$('#latitude').val(pp.lat);
			marker.addEventListener("mouseup",function(){
				var p = marker.getPosition();  //获取marker的位置
				$('#longitude').val(p.lng);
				$('#latitude').val(p.lat);	
			});
		}
		var local = new BMap.LocalSearch(map, { //智能搜索
			onSearchComplete: mySetMap
		});
		local.search(myValue);
		$('#address').val(myValue);
		//
	}
	
}
//});
