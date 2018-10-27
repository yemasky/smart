<%include file="wise/inc/commonController.tpl"%>
<div class="p-md" ng-controller="<%$__module%>Controller">
  <div class="row">
  	<form id="channel_Add" name="channel_Add" ng-submit="channelAdd()">
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading bg-white">
          基本信息<br>
          <small class="text-muted"></small>
        </div>
        <div class="panel-body">
          
          	<div class="form-group">
                <label for="channel" translate="module.channel.channel">类型</label>
                <select class="form-control" name="channel" id="channel" ng-model="param.channel" required>
                  <option value="">请选择</option>
                  <%foreach key=type item=name from=$channel_type%>
                  <option value="<%$type%>"><%$name%></option>
                  <%/foreach%>
                </select>
            </div>
            <div class="form-group">
                <label for="channel" translate="module.channel.member_of">隶属于</label>
                <select class="form-control" name="channel_father_id" id="channel_father_id" ng-model="param.channel_father_id" required>
                  <option value="">请选择</option>
                  <option value="0">无隶属</option>
                  <%foreach key=channel_id item=channel from=$arrayChannelList%>
                  <%if $channel.channel_father_id==$channel_id%>
                  <option value="<%$channel_id%>"><%$channel.channel_name%></option>
                  <%/if%>
                  <%/foreach%>
                </select>
            </div>
            <div class="form-group">
              <label for="channel_name" translate="module.channel.channel_name">中文名称</label>
              <input type="text" class="form-control" id="channel_name" name="channel_name" ng-model="param.channel_name" required placeholder="请输入名称">
              <input type="hidden" class="form-control" id="channel_id" name="channel_id" ng-model="param.channel_id" value="">
            </div>
            <div class="form-group row">
            	<div class="col-sm-6">
              		<label for="channel_short_name" translate="module.channel.channel_short_name">中文简称</label>
             		<input type="text" class="form-control" id="channel_short_name" name="channel_short_name" ng-model="param.channel_short_name" required placeholder="请输入简称">
            	</div>
            	<div class="col-sm-6">
              		<label for="channel_en_name" translate="module.channel.channel_en_name">英文名称</label>
              		<input type="text" class="form-control" id="channel_en_name" name="channel_en_name" ng-model="param.channel_en_name" required placeholder="请输入英文名称">
            	</div>
            </div>
            <div class="form-group">
                <label for="web" translate="module.channel.web">网站</label>
                <input type="text" class="form-control" id="web" name="web" ng-model="param.web" placeholder="请输入网站">
            </div>
            <div class="form-group row">
            	<div class="col-sm-3">
              		<label for="phone" translate="module.channel.phone">联系电话</label>
             		<input type="text" class="form-control" id="phone" name="phone" ng-model="param.phone" placeholder="请输入电话">
            	</div>
            	<div class="col-sm-3">
              		<label for="mobile" translate="module.channel.mobile">移动电话</label>
              		<input type="text" class="form-control" id="mobile" name="mobile" ng-model="param.mobile" ng-pattern="/(13|14|15|16|17|18|19)([0-9]{9})$/" placeholder="请输入移动电话">
            	</div>
                <div class="col-sm-3">
              		<label for="email" translate="module.channel.email">Email</label>
             		<input type="email" class="form-control" id="email" name="email" ng-model="param.email" placeholder="请输入Email">
            	</div>
            	<div class="col-sm-3">
              		<label for="fax" translate="module.channel.fax">Fax</label>
              		<input type="text" class="form-control" id="fax" name="fax" ng-model="param.fax" placeholder="请输入Fax">
            	</div>
            </div>
            <div class="form-group">
              		<label for="star" translate="module.channel.star">星级</label>
              		<div class="form-group">
                    	<label class="radio-inline">
                          <input type="radio" value="0" id="star0" name="star" checked> 无
                        </label>
                    	<label class="radio-inline">
                          <input type="radio" value="1" id="star1" name="star"> 1星
                        </label>
                        <label class="radio-inline">
                          <input type="radio" value="2" id="star2" name="star"> 2星
                        </label>
                        <label class="radio-inline">
                          <input type="radio" value="3" id="star3" name="star"> 3星
                        </label>
                        <label class="radio-inline">
                          <input type="radio" value="4" id="star4" name="star"> 4星
                        </label>
                        <label class="radio-inline">
                          <input type="radio" value="5" id="star5" name="star"> 5星
                        </label>
                        <label class="radio-inline">
                          <input type="radio" value="6" id="star6" name="star"> 6星
                        </label>
                        <label class="radio-inline">
                          <input type="radio" value="7" id="star7" name="star"> 7星
                        </label>
                    </div>
            </div>
            <div class="form-group">
                <label for="wifi">WiFi</label>
                <div class="form-group">
                    <label class="radio-inline">
                      <input type="radio" value="1" id="wifi1" name="wifi" checked> 有
                    </label>
                    <label class="radio-inline">
                      <input type="radio" value="0" id="wifi0" name="wifi"> 无
                    </label>
                </div>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading bg-white">
          地理位置信息<br>
          <small class="text-muted"></small>
        </div>
        <div class="panel-body">
            <div class="form-group row">
            	<div class="col-sm-3">
              		<label for="country">国家</label>
             		<select class="form-control" id="country" name="country"><option value="86" selected>中国</option></select>
            	</div>
            	<div class="col-sm-3">
              		<label for="province">省</label>
              		<select class="form-control" id="province" name="province" required placeholder="请输入省"><option value="">请选择</option></select>
            	</div>
                <div class="col-sm-3">
              		<label for="city">市</label>
             		<select type="text" class="form-control" id="city" name="city" required placeholder="请输入市、县"><option value="">请选择</option></select>
            	</div>
            	<div class="col-sm-3">
              		<label for="town">区、县</label>
              		<select type="text" class="form-control" id="town" name="town" required placeholder="请输入城镇"><option value="">请选择</option></select>
            	</div>
            </div>
            <div class="form-group">
              	<label for="address">地址</label>
              	<input type="text" class="form-control" id="address" name="address" ng-model="param.address" required placeholder="请输入地址">
            </div>
            <div class="form-group">
            	<div id="searchResultPanel" class="span6" style="border:1px solid #C0C0C0;height:auto; display:none;"></div>
            	<div style="height:267px;" id="allmap"></div>
            </div>
            <div class="form-group row">
                <div class="form-group col-sm-6">
                    <label class="col-sm-3 control-label" for="longitude">经度</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="longitude" name="longitude" ng-model="param.longitude" required placeholder="请输入经度">
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="col-sm-3 control-label" for="latitude">纬度</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="latitude" name="latitude" ng-model="param.latitude" required placeholder="请输入纬度">
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading bg-white">
          <small class="text-muted"></small>
        </div>
        <div class="panel-body">
            <div class="form-group">
            	<div class="text-center">
              		<button class="btn btn-addon btn-default" type="submit" btn-loading-text="{{'common.hint.LOADING' | translate}}" trigger-loading="beginLoading"><i class="fa fa-save"> </i> <span translate="common.hint.SAVE">Save</span></button>
            	</div>
             </div>
         </div>
       </div>
    </div>
    </form>
  </div>

</div>
<script language="JavaScript">
	var longitude = '', latitude = '',myCityName = "";
	var arrayChannel = eval('(<%$arrayChannel%>)');
	var map_address = '';
	if(typeof(arrayChannel['address']) != 'undefined') {
		map_address = arrayChannel['address'];
		longitude = arrayChannel['longitude'];latitude = arrayChannel['latitude'];
	}
	//<%include file="wise/inc/jscript/baidu.map.js"%>
    app.controller('<%$__module%>Controller', function($rootScope, $scope, $httpService, $modal, $translate, $ocLazyLoad) {
		$scope.param = arrayChannel;
		var xml_data = '';
		$.get('<%$__WEB%>static/area/Area.xml', function(result){
			var xml = result;
			xml_data = xml;
			if(typeof($scope.param.province) == 'undefined') $scope.param.province = '110000';
			$scope.setLocation(xml, $scope.param.province);
			if(typeof($scope.param.city) != 'undefined') {
				setCity($scope.param.city, $scope.param.town);
				longitude = $scope.param.longitude, latitude = $scope.param.latitude;
				$('#channel_id').val($scope.param.id);
			}
		});
		$scope.channelAdd = function() {
			$scope.beginLoading =! $scope.beginLoading;
			$scope.param = $.serializeFormat('#channel_Add');
			$httpService.header('method', 'Add');
			if($scope.param['channel_id'] != '') {
				$httpService.header('method', 'Update');
			}
			$httpService.post('<%$__WEB%>app.do?channel='+$scope.$stateParams.channel, $scope, function(result){
				$scope.beginLoading =! $scope.beginLoading;
				$httpService.header('method', '');//清空操作
				if(result.data.success == 1) {
					var message = $translate.instant("error.code."+result.data.code);
					var modal = $modal({title: 'Success', content: message, templateUrl: '/modal-success.html', show: true, onHide: function() {
						//$scope.redirect('app/Setting/');
					}});
				}
			});
		}
		$scope.setLocation = function(xml, province) {
			$(xml).find("province").each(function(){
				var province_name = $(this).attr("name");//this->
				var location = $(this).attr("location");
				var selected = '';
				if(location == province) {
					selected = 'selected';
				}
				$("#province").append("<option value='"+location+"' "+selected+">"+province_name+"</option>");
			});
			setCity('110100', '110108');
		}
		//市
		$("#province").change(function(){
			$("#city>option").remove();
			//$("#location_province").next().find('span').text("请选择");
			$("#town>option").remove();
			//$("#location_city").next().find('span').text("请选择");
			var pname = $("#province").val();
			$(xml_data).find("province[location='"+pname+"']>city").each(function(){
				var city = $(this).attr("name");//this->
				var location = $(this).attr("location");
				$("#city").append("<option value='"+location+"'>"+city+"</option>");
			});
			///查找<city>下的所有第一级子元素(即区域)
			var cname = $("#city").val();
			$(xml_data).find("city[location='"+cname+"']>country").each(function(){
				var area = $(this).attr("name");//this->
				var location = $(this).attr("location");
				$("#town").append("<option value='"+location+"'>"+area+"</option>");
			});
		});
		//区
		$("#city").change(function(){
			$("#town>option").remove();
			//$("#location_city").next().find('span').text("请选择");
			var cname = $("#city").val();
			$(xml_data).find("city[location='"+cname+"']>country").each(function(){
				var area = $(this).attr("name");//this->
				var location = $(this).attr("location");
				$("#town").append("<option value='"+location+"'>"+area+"</option>");
			});
		});
		function setCity(city_code, town_code) {
			$("#city>option").remove();
			//$("#location_province").next().find('span').text("请选择");
			$("#town>option").remove();
			//$("#location_city").next().find('span').text("请选择");
			var pname = $('#province').val();
			var selected = '';
			$(xml_data).find("province[location='"+pname+"']>city").each(function(){
				var city = $(this).attr("name");//this->
				var location = $(this).attr("location");
				if(location == city_code) {
					selected = 'selected';
				}
				$("#city").append("<option value='"+location+"' "+selected+">"+city+"</option>");
				selected = '';
			});
			///查找<city>下的所有第一级子元素(即区域)
			var cname = $("#city").val();
			$(xml_data).find("city[location='"+cname+"']>country").each(function(){
				var area = $(this).attr("name");//this->
				var location = $(this).attr("location");
				if(location == town_code) {
					selected = 'selected';
				}
				$("#town").append("<option value='"+location+"' "+selected+">"+area+"</option>");
				selected = '';
			});
		}		
	});

	//
	
</script>


