// JavaScript Document

app.controller('EmployeeSectorPositionController', function($rootScope, $scope, $httpService, $location, $translate, $aside, $ocLazyLoad, $alert) {
	$ocLazyLoad.load([$scope.__RESOURCE+"editor/kindeditor/themes/default/default.css"]);
	//获取数据
	var _channel = $scope.$stateParams.channel, common = '', sectorList = {}, sectorChildrenList = {}, positionList = {}, sectorPositionList = [],imagesUploadUrl = '',imagesManagerUrl = '';
	var _view = $scope.$stateParams.view;
	var param = 'channel='+_channel+'&view='+_view;
	$scope.loading.show();
	$httpService.post('/app.do?'+param, $scope, function(result){
		$scope.loading.hide();
		if(result.data.success == '0') {
			var message = $scope.getErrorByCode(result.data.code);
			$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
		}
		common = result.data.common;
		$scope.setCommonSetting(common);
		$scope.setThisChannel('ALL');
		$scope.channelSectorList = result.data.item.channelSectorList;//部门职位
		sortChannelSector(result.data.item.channelSectorList);
		imagesUploadUrl = result.data.item.imagesUploadUrl;
		imagesManagerUrl = result.data.item.imagesManagerUrl;
		function sortChannelSector(channelSectorList) {
			var positionList = {}; sectorI = 0,sectorJ = 0, sectorPosition = {}, sectorPositionChildren = {};
			if(channelSectorList != '') {
				for(var sector_id in channelSectorList) {
					var listData = channelSectorList[sector_id];
					var father_id = listData.sector_father_id;
					if(listData.sector_type == 'sector') {//1级部门//father
						if(angular.isUndefined(sectorList[father_id])) {
							sectorChildrenList[father_id] = [];//father
							sectorList[father_id] = channelSectorList[father_id];//father
							sectorList[father_id].label = channelSectorList[father_id].sector_name;
                            sectorList[father_id]['children'] = {};
							//
							sectorPosition[father_id] = {};
							sectorPosition[father_id]['father_id'] = sectorI;
							sectorPosition[father_id]['children_id'] = 0;
							sectorPositionList[sectorI] = {};
							sectorPositionList[sectorI].label = channelSectorList[father_id].sector_name;
							sectorPositionList[sectorI].data = channelSectorList[father_id];
							sectorPositionList[sectorI].children = [];
							sectorI++;
						}
					}
					if(father_id!=sector_id) {//children
						if(listData.sector_type == 'sector') {//2级部门
							var sector_length = sectorChildrenList[father_id].length;
							sectorChildrenList[father_id][sector_length] = sector_id;
							sectorList[father_id]['children'][sector_id] = listData;
							sectorList[father_id]['children'][sector_id].label = listData.sector_name;
							//
							var sectorIfather_id=sectorPosition[father_id].father_id,children_id = sectorPosition[father_id].children_id;
							sectorPositionList[sectorIfather_id].children[children_id] = {};
							sectorPositionList[sectorIfather_id].children[children_id].label = listData.sector_name;
							sectorPositionList[sectorIfather_id].children[children_id].data = listData;
							sectorPositionList[sectorIfather_id].children[children_id].children = [];
							sectorPositionChildren[sector_id] = {};//二级部门职位
							sectorPositionChildren[sector_id]['father_id'] = children_id;
							sectorPositionChildren[sector_id]['children_id'] = 0;
							//2级部门children_id++
							sectorPosition[father_id].children_id++;
						} else {//职位, 职位的father_id是部门ID
							if(angular.isUndefined(positionList[father_id])) {
								positionList[father_id] = {};//father
								positionList[father_id]['children'] = {};
							} 
							positionList[father_id]['children'][sector_id] = listData;
							//
							if(angular.isDefined(sectorPosition[father_id])) {//存在第一级部门的职位
								//
								var sectorIfather_id=sectorPosition[father_id].father_id,children_id = sectorPosition[father_id].children_id;
								sectorPositionList[sectorIfather_id].children[children_id] = {};
								sectorPositionList[sectorIfather_id].children[children_id].label = listData.sector_name;
								sectorPositionList[sectorIfather_id].children[children_id].data = listData;
								sectorPositionList[sectorIfather_id].children[children_id].children = [];
								sectorPosition[father_id].children_id++;
							}
							if(angular.isDefined(sectorPositionChildren[father_id])) {//存在第二级部门的职位
								var sector_father_id = channelSectorList[father_id].sector_father_id;//第一级的sector_id
								var sectorIfather_id = sectorPosition[sector_father_id].father_id,children_id = sectorPositionChildren[father_id].father_id;
								//
								var positionChildren_id = sectorPositionChildren[father_id].children_id;
								//
								sectorPositionList[sectorIfather_id].children[children_id].children[positionChildren_id] = {};
								sectorPositionList[sectorIfather_id].children[children_id].children[positionChildren_id].label = listData.sector_name;
								sectorPositionList[sectorIfather_id].children[children_id].children[positionChildren_id].data = listData;
								sectorPositionList[sectorIfather_id].children[children_id].children[positionChildren_id].children = [];
								sectorPositionChildren[father_id].children_id++;
							}
						}
					}                        
				}
			}
			$scope.sectorList = sectorList;$scope.positionList = positionList;
		}
	});
	//
	var asideEmployee = '';$scope.this_nav_menu_name = '选择部门';
	$scope.addEmployee = function(_this) {
		$scope.param = {}; $scope.param["valid"] = "1";
		if(_this != 0) $scope.param = _this;
		$scope.action = '添加/编辑';
		asideEmployee = $aside({scope : $scope, title: $scope.action_nav_name, placement:'top',animation:'am-fade-and-slide-top',backdrop:"static",container:'#MainController', templateUrl: '/resource/views/Management/EmployeeAddEdit.tpl.html'});
		asideEmployee.$promise.then(function() {
			asideEmployee.show();
			$(document).ready(function(){
				$scope.setImage();
			});
		})
		
	};
	$scope.saveData = function() {
		var param = this.param;
		if(param == null || param == '' || !angular.isDefined(param.payment_father_id)) {
			$alert({title: 'Notice', content: '类型必须选择！', templateUrl: '/modal-warning.html', show: true});
			return;
		}
		$scope.loading.show();
		$scope.param = param;
		$httpService.post('/app.do?channel='+common.saveAddEditUrl, $scope, function(result){
			$scope.loading.hide();
			$scope.dataList = result.data.item;
			asideEmployee.hide();
			
		});
	}
    
    /////////
    $scope.employeeList = []; var thisSectorParam = '';
    $scope.callEmployeeServer = function callEmployeeServer(tableState, sectorParam) {
        $scope.param = {};        
        $scope.param.tableState = tableState;
        $scope.loading.start();
		if(!angular.isString(sectorParam)) sectorParam = '';
		if(thisSectorParam != '') sectorParam = thisSectorParam;
        $httpService.header('method', 'EmployeePagination');
        $httpService.post('/app.do?'+param+'&'+sectorParam, $scope, function(result){
            $scope.loading.percent();
            $httpService.deleteHeader('method');
            $scope.employeeList = result.data.item.employeeList.data;
            tableState.pagination.numberOfPages = result.data.item.employeeList.numberOfPages;
        });
    };
	///////////////////////
	var tree;
	$scope.my_tree_handler = function(branch) {
		var _ref, sectorChildren = '';thisSectorParam = '';
		$scope.output = branch.label;
		if (angular.isDefined(branch.data) && (_ref = branch.data) != null) {
			var sector_id = branch.data.sector_id;
			var sector_father_id = branch.data.sector_father_id;
			var sector_type = branch.data.sector_type;
			if(sector_type == 'sector' && sector_id == sector_father_id) {
				//加上自己
				var sectorChildrenParam = angular.copy(sectorChildrenList[sector_father_id]);
				var length = sectorChildrenParam.length;
				sectorChildrenParam[length] = ""+sector_id;
			    sectorChildren = angular.toJson(sectorChildrenParam);
			}
			thisSectorParam = 'sector_id='+sector_id+'&sector_father_id='+sector_father_id+'&sector_type='+sector_type+'&sectorChildren='+sectorChildren;
		}
		$scope.callEmployeeServer($scope.param.tableState, thisSectorParam);
	};
	$scope.my_data = sectorPositionList;
	$scope.my_tree = tree = {};
	//
	$scope.showCommonNavMenu = function() {
		$('#commonNavMenu').next().show();
	}
	$scope.thisPosition = [];
	$scope.selectCommonNavMenu = function(_this, tree) {
		var thisPosition = [];
		if(angular.isDefined($scope.positionList[_this.sector_id])) {
			var k = 0;
			for(var i in $scope.positionList[_this.sector_id].children) {
				thisPosition[k] = $scope.positionList[_this.sector_id].children[i];k++;
			}
		}
		$scope.thisPosition = thisPosition;
		$scope.param.sector_id = _this.sector_id;
		$scope.param.sector_father_id = _this.sector_father_id;
		$scope.this_nav_menu_name = _this.sector_name;
		if(tree == 'father') {
			
		} else {
			
		}
		$('#commonNavMenu').next().hide();
	}
	//
	<!--图片上传及操作-->
	//images
	var isSetImage = false;
	$scope.setImage = function() {
		//if(isSetImage) return;isSetImage = true;
		var uploadJsonUrl = 'app.do?channel='+imagesUploadUrl+'&channel_config=employee';
		var fileManagerJsonUrl = 'app.do?channel='+imagesManagerUrl+'&channel_config=employee';
		window.K = KindEditor;
		var editor = K.editor({
			uploadJson : uploadJsonUrl,
			fileManagerJson : fileManagerJsonUrl,
			allowFileManager : true
		});
		K('.set_image_src').click(function() {
			editor.loadPlugin('image', function() {
				editor.plugin.imageDialog({
					//imageUrl : K('#image_src').val(),
					clickFn : function(url, title, width, height, border, align) {
						editor.hideDialog();
						$('#main_images').attr('src', url);
					}
				});
			});
		});
	}
	//
	$httpService.deleteHeader('refresh');
});