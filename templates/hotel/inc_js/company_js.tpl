<script src="<%$__RESOURCE%>js/jquery.validate.js"></script>
<%include file="hotel/inc_js/location_js.tpl"%>
<script type="text/javascript">
	var longitude = "<%$arrayCompany['company_longitude']%>";
	var latitude = "<%$arrayCompany['company_latitude']%>";
	var map = new BMap.Map("allmap");
	var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM});
	
	longitude = longitude == '' ? 121.480174 : longitude;
	latitude = latitude == '' ? 31.236428 : latitude;
	var point = new BMap.Point(longitude, latitude);
	map.centerAndZoom(point,18);
	map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
	map.addControl(top_right_navigation);    
	
	var marker = new BMap.Marker(point);  // 创建标注
	map.addOverlay(marker);               // 将标注添加到地图中
	marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
	
	function myLocation(result){
		var cityName = result.name;
		map.setCenter(cityName);
		//alert("当前定位城市:"+cityName);
	}
	if(longitude == '121.480174' || latitude == '31.236428') {
		var myCity = new BMap.LocalCity();
		myCity.get(myLocation);
	}
	
	function theLocation(){
		var city = document.getElementById("company_address").value;
		if(city != ""){
			map.centerAndZoom(city,18);      // 用城市名设置地图中心点
		}
	}
	
	function G(id) {
		return document.getElementById(id);
	}
	
	var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
		{"input" : "company_address"
		,"location" : map
	});
	
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
	
	var myValue;
	ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
		var _value = e.item.value;
		myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
		G("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;
		setPlace();
	});

	function setPlace(){
		map.clearOverlays();    //清除地图上所有覆盖物
		function mySetMap(){
			var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
			map.centerAndZoom(pp, 18);
			marker = new BMap.Marker(pp);
			map.addOverlay(marker);    //添加标注
			map.addControl(top_right_navigation);    
			marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
			marker.enableDragging();
			//alert(pp.lng lat);
			$('#company_longitude').val(pp.lng);
			$('#company_latitude').val(pp.lat);
		}
		var local = new BMap.LocalSearch(map, { //智能搜索
		  onSearchComplete: mySetMap
		});
		local.search(myValue);
		$('#company_address').val(myValue);
	}	

</script>
<script language="javascript">
$(document).ready(function(){
	// Form Validation
    $("#company_form").validate({
		rules:{
			company_name:{
				required:true
			},
            company_phone:{required:true,isZonePhone:true},company_finance_phone:{required:true,isZonePhone:true},
            company_sales_phone:{required:true,isZonePhone:true},company_information_phone:{required:true,isZonePhone:true},
			company_province:{
				required:true
			},
			company_mobile:{
				required:true,
				number:true,
				isMobile:true
			},
			company_address:{
				required:true,
				minlength:5,
			}
		},
		messages: {
			company_name:"请输入公司名称",
			company_province:"",
			company_mobile:"请输入正确移动电话号码",
			company_address:"请输入公司地址",
            company_phone:"请输入正确的电话号码",company_finance_phone:"请输入正确的电话号码",company_sales_phone:"请输入正确的电话号码",company_information_phone:"请输入正确的电话号码",
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('success');
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	$('#company_address').val("<%$arrayCompany['company_address']%>");
    $('#company_phone,#company_finance_phone,#company_sales_phone,#company_information_phone').bind("click keyup", function(e) {
        if($.trim(this.value) == '') this.value = '-';
        if(this.value == '-') {
            var position = 0;
            var txtFocus = document.getElementById(this.id);
            if ($.browser.msie) {
                var range = txtFocus.createTextRange();
                range.move("character", position);
                range.select();
            }
            else {
                //obj.setSelectionRange(startPosition, endPosition);
                txtFocus.setSelectionRange(position, position);
                txtFocus.focus();
            }
        }
    });
	
});
</script>