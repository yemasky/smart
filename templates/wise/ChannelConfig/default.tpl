<%include file="wise/inc/commonController.tpl"%>
<div class="p-md">
  <div class="box b-a bg-white m-b" ng-controller="<%$__module%>Controller">
    <div class="col-md-10">
      <div class="panel-heading b-b b-light">{{arrayChannel.channel_name}}</div>
      <div class="panel-body">
        <p class="text-muted"><%$channel_config_name%> <!--增加属性--><a class="pull-right fa fa-plus-square" ng-click="addArrtibute('<%$channel_config_key%>', 0)"> </a></p>
        <div class="m-b-lg">
            <div class="m-b">
                <!--房间开始-->
              	<%if $channel_config_key=='room'%>
                <div class="buliding clear m-b" ng-repeat="(buliding_key, buliding) in itemList">
                	<div class="m-b"><i class="fa fa-building text-lg"> {{buliding_key}}楼栋</i></div>
                    <div class="floor clear" ng-repeat="(floor_key, floor) in buliding">
                    	<span class="badge bg-success pos-rlt m-r-xs"><b class="arrow right b-success pull-in"></b>{{floor_key}}楼层</span>
            			<div class="list-group no-radius">
                        <div class="list-group-item col-sm-3 m-xs" ng-repeat="(i, room) in floor">
                          <span class="pull-left w-thumb m-r b-b-2x"><img class="img-responsive" ng-src="<%$__RESOURCE%>images/room.png"></span>
                          <div class="clear">
                            <span class="font-bold block">房间名称/编号:{{room.item_name}} 
                            	<a><i class="glyphicon glyphicon-edit pull-right" ng-click="editItem(room.item_id)"></i></a>
                            </span> 
                            朝向:{{room.item_attr5_value}}  面积:{{room.item_attr4_value}} 类型:{{room.describe}}
                          </div>
                        </div>
                        </div> 
                    </div>
                </div>
            	<%/if%>
                <!--房间结束-->
                <!--房型开始-->
            	<%if $channel_config_key=='layout'%>
                <div class="list-group no-radius">
                    <div class="list-group-item col-sm-3 m-xs"  ng-repeat="(i, item) in itemList">
                      <span class="pull-left w-xs m-r b-b-2x"><img class="img-responsive" width="90" height="90" ng-src="{{item.image_src}}"></span>
                      <div class="clear">
                        <span class="font-bold block">{{item.item_name}}
                            <a><i class="glyphicon glyphicon-edit pull-right" ng-click="editItem(item.item_id)"></i></a>
                        </span> 
                        特色:{{item.describe}}
                      </div>
                    </div>
                </div>
             	<%/if%>
                <!--房型结束-->    
                <!--添加（房间|房型)按钮开始-->
                <div class="w-xl w-auto-xs">
                    <div class="box bg-white m-xs">
                      <div class="box-col w-auto-xs v-m">
                        <img ng-click="addArrtibute('<%$channel_config_key%>', 0)" ng-src="<%$__RESOURCE%>images/a10.jpg" width="120" height="120" class="b p-xs">
                      </div>
                      <div class="box-col p">
                        <a href="" class="pull-right fa fa-plus-square" ng-click="addArrtibute('<%$channel_config_key%>', 0)"> 添加<%$channel_config_name%></a>
                        <div></div>
                      </div>
                    </div>
                </div>
                <!--添加（房间|房型）按钮结束-->
            </div>
        </div>
      </div>
    </div>
    <!--右边comom添加属性开始-->
    <div class="col-md-2 b-l no-border-sm">
      <div class="panel-heading b-b b-light" translate="module.channel.arrtibute">属性</div>
      <div class="list-group no-border no-radius" ng-repeat="(i, Arrtibute) in configArrtibute" ng-if="i==0">
        <div class="list-group-item" ng-repeat="(father_id, childen) in Arrtibute">
            <div class="m-b-xs">
            <!--<a class="pull-right ti-settings" ng-click="config(i)"></a>-->
            <i class="fa fa-fw fa-circle text-info"></i>
            {{childen.attribute_name}}
            </div>
            <ul class="m-l nav b-b" ng-repeat="(j, sub) in configArrtibute[father_id]">
            	<li class="p-xs">{{sub.attribute_name}}</li>
                <li class="col-md-5 p-xs m-l-xs" ng-repeat="(l, subChilden) in configArrtibute[sub.attribute_id]">
                    <span class="fa fa-angle-right fa-fw text-muted"></span> {{subChilden.attribute_name}}
                </li>
            </ul>
        </div>
      </div>
    </div>
    <!--右边添加属性结束-->
  </div>
</div>
<script language="javascript" src="<%$__RESOURCE%>editor/kindeditor/kindeditor-all-min.js"></script>
<script language="JavaScript">
    app.controller('<%$__module%>Controller', function($rootScope, $scope, $httpService, $location, $translate, $aside, $ocLazyLoad, $alert) {
		$ocLazyLoad.load(["<%$__RESOURCE%>editor/kindeditor/themes/default/default.css", "<%$__RESOURCE%>editor/kindeditor/lang/zh-CN.js"]);
		$scope.itemList =  eval('(<%$itemList%>)');
		$scope.arrayConfig = eval('(<%$arrayConfig%>)');$scope.arrayChannel = eval('(<%$arrayChannel%>)');
		$scope.arrayDefaultAttr = eval('(<%$arrayDefaultAttr%>)');console.log($scope.arrayDefaultAttr);
		$scope.haveSelectRoom = {};$scope.attrSelectRoom = {};$scope.haveSelectImages = {};$scope.extend_attr = {};
		//begin commom 右边数据/////////////////////////////////////////////////////////////////////////////////////
        var arrayAttribute = eval('(<%$arrayAttribute%>)'), configArrtibute = {};
		if(arrayAttribute != '') {
			for(var i in arrayAttribute) {
				if(typeof(configArrtibute[arrayAttribute[i].attribute_father_id]) == 'undefined') 
					configArrtibute[arrayAttribute[i].attribute_father_id] = {};
				configArrtibute[arrayAttribute[i].attribute_father_id][arrayAttribute[i].attribute_id] = arrayAttribute[i];
			}
		}
		$scope.configArrtibute = configArrtibute;
		var myArrtibute = '';
		$scope.addArrtibute = function(key, param) {
			$scope.attrSelectRoom = {};$scope.haveSelectRoom = {};$scope.haveSelectImages = {};$scope.extend_attr = {};
			if(param == 0) {
				param = {};param["valid"] = "1";param["image_src"] = $scope._resource+'images/a10.jpg';
				//$('#selectRoom').remove();
			}; 
			$scope.param = param;
            myArrtibute = $aside({scope : $scope, title: $scope.arrayChannel.channel_name+'-<%$channel_config_name%>', content: 'My Content', placement : 'top', animation : 'am-fade-and-slide-top', backdrop : "static", container: 'body', templateUrl: '<%$__RESOURCE%>views/Channel/'+key+'.tpl.html'});
            myArrtibute.$promise.then(function() {
				myArrtibute.show();
				$scope.setImage();
				$(document).ready(function(){
					if(typeof(param.item_id) != 'undefined') $('input[name="item_id"]').val(param.item_id);
					if(typeof(param.describe) != 'undefined' && key == 'room') {//room 
						var describe = param.describe.split(',');
						for(var j in describe) {
							$('.describe[value="'+$.trim(describe[j])+'"]').attr('checked', true);
						}
					}
				});
            })
			
		};
        //end commom 右边数据///////////////////////////////////////////////////////////////////////////////////
        <!--common 编辑-->
		var continue_find = true;
		$scope.editItem = function(item_id) {
			var itemList = $scope.itemList;
			continue_find = true;
			getItem(itemList, item_id);
		};
		function getItem(itemList, item_id) {
			if(continue_find == false) return;
			for(var i in itemList) {
				if(typeof(itemList[i].item_name) != 'undefined') {
					if(itemList[i].item_id == item_id) {
						var param = itemList[i];
						continue_find = false;
						$scope.addArrtibute('<%$channel_config_key%>', param);
						//查找房型attr
                        //<%if $channel_config_key=='layout'%>
                        //
						$scope.attrSelectRoom = {};$scope.haveSelectRoom = {};
						var loading = $alert({content: 'Saving... 80%', placement: 'top', type: 'info', templateUrl: '/loading.html', show: true});
						$httpService.header('method', 'attribute');
                        var postParam = {};postParam.param = '';
						$httpService.post('app.do?channel=<%$channel_config_url%>&id=<%$channel_id%>&item_category_id='+item_id, postParam, function(result){
							if(result.data.success == 1) {
								if(result.data.item != '') setAttribute(result.data.item);
							} 
							loading.hide();
							$httpService.deleteHeader('method');
						});
                        //
                        //<%/if%>
                        //
						break;
					}
				} else {
					getItem(itemList[i], item_id)
				}
			}
		};
        <!--end common 编辑-->
        <!--common保存-->
		$scope.saveItem = function() {
			$scope.beginLoading =! $scope.beginLoading;
			var setItemForm = $.serializeFormat('#setItemForm');
			$scope.param = setItemForm;
			$httpService.header('method', 'save');
			var loading = $alert({content: 'Saving... 80%', placement: 'top', type: 'info', templateUrl: '/loading.html', show: true});
			$httpService.post('app.do?channel=<%$channel_config_url%>&id=<%$channel_id%>', $scope, function(result){
				$scope.beginLoading =! $scope.beginLoading;
				if(result.data.success == 1) {
					myArrtibute.hide();
					$scope.itemList =  result.data.item;
				} 
                $httpService.deleteHeader('method');
				loading.hide();
			});
		};
        <!--end common保存-->
        <!--房间操作开始-->
		$scope.roomList = '';
		$scope.getRoomItem = function() {
			if($scope.roomList != '') {setAttrRoom($scope.roomList);return;}
			$httpService.header('method', 'get');
			var loading = $alert({content: 'Loading Room Data... 80%', placement: 'top', type: 'info', templateUrl: '/loading.html', show: true});
			$httpService.post('app.do?channel=<%$room_url%>&id=<%$channel_id%>', $scope, function(result){
                $httpService.deleteHeader('method');
				if(result.data.success == 1) {
					$scope.roomList =  result.data.item;
					setAttrRoom($scope.roomList);
				}
				loading.hide();
			});
		};
		$scope.haveSelectRoom = {};
		$scope.selectRoom = function($event, item_id) {
			if($event.target.checked) {
				$scope.haveSelectRoom[item_id] = this.room;
			} else {
				delete $scope.haveSelectRoom[item_id];
			}
            $('#channel_item_multipe_delete').attr('name', 'channel_item_multipe[0]');
		};
		function setAttrRoom (roomList) {
			var attrSelectRoom = $scope.attrSelectRoom;
			for(var buliding_key in roomList) {
				var buliding = roomList[buliding_key];
				for(var floor_key in buliding) {
					var _floor = buliding[floor_key];
					for(var i in _floor) {
						var room = _floor[i];
						var item_id = room.item_id;
						if(typeof(attrSelectRoom[item_id]) != 'undefined') $scope.haveSelectRoom[item_id] = room;
					}
				}
			}
		}
        <!--房间操作结束-->
        <!--图片上传及操作-->
		//images
		var isSetImage = false;
		$scope.setImage = function() {
			//if(isSetImage) return;isSetImage = true;
			var uploadJsonUrl = 'app.do?channel=<%$imagesUploadUrl%>&channel_config=<%$channel_config_key%>';
			var fileManagerJsonUrl = 'app.do?channel=<%$imagesManagerUrl%>&channel_config=<%$channel_config_key%>';
			window.K = KindEditor;
			var editor = K.editor({
				uploadJson : uploadJsonUrl,
				fileManagerJson : fileManagerJsonUrl,
				allowFileManager : true
			});
			K('.set_image_src').click(function() {
				var isMain = $(this).attr('isMain');
				editor.loadPlugin('image', function() {
					editor.plugin.imageDialog({
						//imageUrl : K('#image_src').val(),
						clickFn : function(url, title, width, height, border, align) {
							editor.hideDialog();
							if(isMain == 1) {
								K('#image_src').val(url);
								$scope.setMainImages(url, title, width, height, border, align);
							} else {
								$scope.setSubImages(url, title);
							}
						}
					});
				});
			});
		}
		
		$scope.setMainImages = function (url, title, width, height, border, align) {
			$('#main_images').attr('src', url);
		}
		$scope.setSubImages = function (url, title) {
			if(title == '_del_images') {
				delete $scope.haveSelectImages[url];
			} else {
				$scope.haveSelectImages[url] = title;
			}
			$scope.$apply();
		}
        <!--图片上传及操作结束-->
		<!--房型属性编辑开始-->
		function setAttribute(arrayAttributeValue) {
			$scope.extend_attr = {};
			if(typeof(arrayAttributeValue['images']) != 'undefined') {
				var haveSelectImages = {};
				for(var i in arrayAttributeValue['images']) {
					haveSelectImages[arrayAttributeValue['images'][i]['item_images_src']] = arrayAttributeValue['images'][i]['attr_value'];
				}
				$scope.haveSelectImages = haveSelectImages;
			}
			if(typeof(arrayAttributeValue['multipe_room']) != 'undefined') {
				var attrSelectRoom = {};
				for(var i in arrayAttributeValue['multipe_room']) {
					var item_id = arrayAttributeValue['multipe_room'][i]['item_id'];
					attrSelectRoom[item_id] = item_id;
				}
				$scope.attrSelectRoom = attrSelectRoom;
			}
			var extend_attr = {}, k = 0;
			if(typeof(arrayAttributeValue['attr_value']) != 'undefined') {
				$(document).ready(function(){
					for(var i in arrayAttributeValue['attr_value']) {
						var attributeValue = arrayAttributeValue['attr_value'][i];
						var attribute_id = attributeValue.attribute_id;
						var value = $.trim(attributeValue.attr_value);
						var attr_obj = $('.attr_value_'+attribute_id+'[value="'+value+'"]');
						var attr_type = attr_obj.attr('type');
						if(attr_type == 'checkbox' || attr_type == 'radio') {
							attr_obj.attr('checked', true);
						} else {
							if(typeof(arrayAttribute[attribute_id]) != 'undefined') {
								attr_type = arrayAttribute[attribute_id]['input_type'];
							}
							if(attr_type == 'extend_text') {
								if(typeof(extend_attr[attribute_id]) == 'undefined') extend_attr[attribute_id] = {};
								extend_attr[attribute_id][k] = attributeValue;k++;
							} else {
								$('.attr_value_'+attribute_id+'_0').val(value);
							}
						}
					}
					$scope.extend_attr = extend_attr; 
				});
			}
		}
		//
		$scope.attrExtend = function(attr_id) {
			//var attr_id = this.attr_id;
			var attr_count = $('.extend_text_'+attr_id).length;
			var html = '<label class="control-label checkbox-inline no-padding m-b-xs attr_extend">'
				+'<input class="form-control w-xxl" type="text" value="" name="attr_value['+attr_id+']['+attr_count+']"data-id="'+attr_count+'"></label>';
			$('#attr_extend').before(html);
		}
        <!--房型属性编辑结束-->
	});
	//
</script>

