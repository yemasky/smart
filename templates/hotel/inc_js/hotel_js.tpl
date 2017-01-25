<script src="<%$__RESOURCE%>js/jquery.validate.js"></script>
<%include file="hotel/inc_js/location_js.tpl"%>
<script type="text/javascript">
	var longitude = "<%$arrayDataInfo['hotel_longitude']%>";
	var latitude = "<%$arrayDataInfo['hotel_latitude']%>";
	var map = new BMap.Map("allmap");
	
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
		//alert("当前定位城市:"+cityName);
	}
	if(longitude == '121.480174' || latitude == '31.236428') {
		var myCity = new BMap.LocalCity();
		myCity.get(myLocation);
	}
	
	function theLocation(){
		var city = document.getElementById("address").value;
		if(city != ""){
			map.centerAndZoom(city,18);      // 用城市名设置地图中心点
		}
	}
	
	function G(id) {
		return document.getElementById(id);
	}
	
	var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
		{"input" : "address"
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
			marker.enableDragging();
			map.addOverlay(marker);    //添加标注
			//alert(pp.lng lat);
			$('#hotel_longitude').val(pp.lng);
			$('#hotel_latitude').val(pp.lat);
		}
		var local = new BMap.LocalSearch(map, { //智能搜索
		  onSearchComplete: mySetMap
		});
		local.search(myValue);
		$('#address').val(myValue);
	}	

</script>
<script language="javascript">
$(document).ready(function(){
	// Form Validation
    var v = $("#hotel_form").validate({
		rules:{
			company_id: {
				required:true
			},
			hotel_name:{
				required:true,
				minlength:5,
				maxlength:200
			},
			hotel_province:{
				required:true
			},
			hotel_mobile:{
				required:true,
				number:true,
				isMobile:true
			},
			hotel_address:{
				required:true,
				minlength:5,
				maxlength:200
			},
			hotel_booking_notes:{
				required:true,
				minlength:5,
				maxlength:1000
			}
		},
		messages: {
			company_id:"请选择属于公司",
			hotel_name:"请输入正确的酒店名称",
			hotel_province:"",
			hotel_mobile:"请输入正确移动电话号码",
			hotel_address:"请输入正确的酒店地址",
			hotel_booking_notes: "请输入正确的预定须知"
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
		},
		submitHandler: function() {
			$('#hotel_attribute_setting a').tab('show');
		}
	});
	$('#address').val("<%$arrayDataInfo['hotel_address']%>");
	$('#hotel_attribute_setting').click(function() {
		if (v.form()) {
			
		} else {
			return false;
		}
	});
	$('#hotel_images_upload').click(function() {
		if (v.form()) {
			if(hotel_id == '' || hotel_id == '0') return false;
		} else {
			return false;
		}
	});

	var v_server = $('#hotel_attr_form').validate({
		submitHandler: function() {
			var param = $("#hotel_form").serialize();
			$.ajax({
			   url : "<%$hotel_update_url%>",
			   type : "post",
			   dataType : "json",
			   data: param,
			   success : function(data) {
			       if(data.success == '1') {
					   hotel_id = data.itemData.hotel_id;
					   saveHotelAttr();
			       } else {
					   $('#modal_fail').modal('show');
					   $('#modal_fail_message').html(data.message);
			       }
			   }
			 });
		}
	});
	
	function saveHotelAttr() {
		var param = $("#hotel_attr_form").serialize();
		$.ajax({
		   url : "<%$add_hotel_attr_val_url%>&hotel_id=" + hotel_id,
		   type : "post",
		   dataType : "json",
		   data: param,
		   success : function(data) {
			   if(data.success == 1) {
				   $('#modal_success').modal('show');
				   $('#modal_success_message').html(data.message);
				   $('#modal_success').on('hidden.bs.modal', function () {
					    <%if $view == 'add'%>
						if(data.redirect != '') {
						   window.location = data.redirect;
						}
						<%else%>
							$('#hotel_images_upload a').tab('show');
						<%/if%>
				   })					
			   } else {
				   $('#modal_fail').modal('show');
				   $('#modal_fail_message').html(data.message);
			   }
		   }
	   });
	}
	<%if $step == 'upload_images'%>$('#hotel_images_upload a').tab('show');<%/if%>
});

$('.addAttr').click(function(e) {
	$(this).before(" ").prev().clone().insertBefore(this).after(" ");
});
</script>
<script language="javascript">
$.datetimepicker.setLocale('en');
$('#hotel_checkout').datetimepicker({
	datepicker:false,
	format:'H:i',
	step:30
});
$('#hotel_checkin').datetimepicker({
	datepicker:false,
	format:'H:i',
	step:30
});
$.datetimepicker.setLocale('ch');
$('#hotel_opening_date').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, 
    yearStart: '1900', yearEnd: '2050;', //yearOffset:1,maxDate:'+1970-01-02',
    onGenerate:function( ct ){
        $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
    },
});
$('#hotel_latest_decoration_date').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, yearEnd: '2050',
    onGenerate:function( ct ){
        $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
    },
});
    

</script>
<script language="javascript">
	function uploadSuccess(img_url, id) {
		if(id == '') {
			img_url = img_url.replace('/data/images/', '');//<%$upload_images_url%>
			$.getJSON('<%$hotel_update_url%>&act=updateImages&url=' + img_url, function(data){
				if(data.success == 1) {
					id = data.itemData.hotel_images_id;
					addLayoutImages(img_url, id);
				} else {
					$('#modal_success').modal('hide');
					$('#modal_fail').modal('show');
					$('#modal_fail_message').html(data.message);
				}
			});
		} else {
			addLayoutImages(img_url, id);
		}

	}

	function addLayoutImages(img_url, id) {
		var html = '<li class="span2"><a class="thumbnail lightbox_trigger" href="'+img_url
				+'"><img id="hotel_'+id+'" src="<%$__IMGWEB%>'+img_url+'" alt="" ></a>'
				+'<div class="actions">'
				+'<a title="" href="#"><i class="icon-pencil icon-white"></i></a>'
				+'<a title="" href="#"><i class="icon-remove icon-white"></i></a>'
				+'</div></li>';
		$('.thumbnails').append(html);
	}
</script>