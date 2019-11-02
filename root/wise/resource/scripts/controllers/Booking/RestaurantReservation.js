//单个channel_id 的数据 并无多个，如需切换，则重新获取数据
app.controller('RestaurantReservationController', function($rootScope, $scope, $httpService, $location, $translate, $aside, $ocLazyLoad, $alert, $filter, $modal, $datepicker) {
    $scope.param = {};
    $ocLazyLoad.load([$scope._resource + "vendor/print/css/print-preview.css",
                      $scope._resource + "vendor/print/jquery.print-preview.js"]);
    //初始化数据
    var billAccount={};
    $scope.bookAta = '0';//预抵人数
    $scope.dueOut = '0';//预离人数
	//选择客源市场
    $scope.market_name = '散客步入';$scope.market_id = '2';$scope.customer_name = '预订人';
	//
	$scope.bookingTypeList = [{'name':'堂食','booking_type':'meal_dine_in'},{'name':'预订','booking_type':'meal_reserve'},
							  {'name':'外卖','booking_type':'meal_take_out'}];
	$scope.param.booking_type = 'meal_dine_in';$scope.booking_type = 'meal_dine_in';//堂食
	//设置酒店餐饮频道 首次进入设置
    $scope.setThisChannel('Meal');//酒店频道
	//获取数据
	var _channel = $scope.$stateParams.channel;
	var _view = $scope.$stateParams.view,common = '';
	var param = 'channel='+_channel+'&view='+_view;
    beginMealStatus();//开始执行
    function beginMealStatus() {
        $scope.loading.show();
        $httpService.post('/app.do?'+param, $scope, function(result){
            $scope.loading.percent();
            if(result.data.success == '0') {
                //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                return;//错误返回
            }
            common = result.data.common;
            $scope.setCommonSetting(common);
            $scope.setThisChannel('Meal');//酒店频道
			$scope.marketList = result.data.item.marketList;//客源市场
            $(document).ready(function(){
                $scope.channel_id = $scope.thisChannel_id;
				$scope.id = $rootScope.employeeChannel[$scope.thisChannel_id].id;
                //$scope.defaultHotel = $scope.thisChannel[$scope.thisChannel_id]["channel_name"];
				//设置客源市场  
            	$scope.selectCustomerMarket($scope.marketList[1].children[2], false);
				$scope.bookList          = result.data.item.bookList;//预订列表
				$scope.roomList          = result.data.item.roomList;//客房列表
				$scope.rowRoomList       = result.data.item.roomList;//客房列表
				//时间
                var _thisDay = result.data.item.in_date;
                var _thisTime = $filter('date')($scope._baseDateTime(), 'HH:mm');
                $scope.param["check_in"] = _thisDay;
                $('.check_in').val(_thisDay);
                $scope.param["in_time"] = _thisDay+'T14:00:00.000Z';$scope.param["out_time"] = _thisDay+'T12:00:00.000Z';
				/*$('.check_date').daterangepicker({singleDatePicker: true, "autoApply": true,"startDate": _thisDay,"locale":{"format" : 'YYYY-MM-DD hh:mm'}
				}, function(start, label) {
					var check_in = start.format('YYYY-MM-DD');
					$scope.param.check_in = check_in;
					$('.check_in').val(check_in);
				});*/
            });
        });	
    }
	//更换餐厅
	$scope.changeChannel = function(channel_id) {
		$scope.id = $rootScope.employeeChannel[channel_id].id;
		$scope.channel_id = $rootScope.employeeChannel[channel_id].channel_id;
        $scope.channel = $rootScope.employeeChannel[channel_id].channel;
		$scope.loading.show();
		$httpService.post('/app.do?'+param+'&id='+$scope.id, $scope, function(result){
			$scope.loading.percent();
			if(result.data.success == '0') {
				return;//
			}
			$scope.bookList          = result.data.item.bookList;//预订列表
			$scope.roomList          = result.data.item.roomList;//客房列表
			$scope.rowRoomList       = result.data.item.roomList;//客房列表
			allCuisineList = '';
			$scope.cuisineList = {};
			$scope.rowCuisineList = {};
			$scope.cuisineCategory = {};
			$scope.cuisineSKU = {};
		});
	}
	//更换预订类型
	$scope.changeBookingType = function(type) {
		if(angular.isUndefined(type)) {
			$scope.param.booking_type = angular.copy($scope.booking_type);
			return;
		}
		$scope.param.booking_type = type;$scope.booking_type = type;//堂食
	}
	$scope.showCustomerMarket = function($event) {
		//console.log($event);
	}
	//选择客源市场
	$scope.receivableList = [];//协议公司数据
    $scope.selectCustomerMarket = function(market, ajaxRoomForcasting) {
        $scope.marketSystemLayout = {};
        if(angular.isDefined(market)) {
            $scope.market_name = market.market_name;
            $scope.market_id = market.market_id;
            $scope.market_father_id =  market.market_father_id;
            $('#customer_ul').next().hide();
            $scope.customer_name = '预订人';
			$scope.param.receivable_id = '';
		    $scope.param.receivable_name = '';
            if(market.market_father_id == '4') {//判断会员是否正确
                $scope.customer_name = market.market_name;
            } else if(market.market_father_id == '6') {//选择协议公司 取出协议公司数据
				if($scope.receivableList == '') {
					$scope.loading.start();$httpService.header('method', 'getReceivable');
					$httpService.post('/app.do?'+param, $scope, function(result){
						$scope.loading.percent();$httpService.deleteHeader('method');
						if(result.data.success === '0') {
							return;
						}
						$scope.receivableList = result.data.item.receivableData;
					});
				}
			}
        }
    };
	$scope.selectReceivable = function(receivable) {
		$scope.param.receivable_id = receivable.receivable_id;
		$scope.param.receivable_name = receivable.receivable_name;
	}
	//开台 预订 结账 加减菜
	$scope.diningTable = function(diningType, table) {
		var diningTypeName = '订单管理';
		$scope.activeBookAccountsEditTab=1;
		if(diningType == 'open') {//$scope.param.number_of_people = 1;
			diningTypeName = '堂食';
			$scope.clearBookTable();$scope.clearBookCuisine();
			$scope.addBookTable(table);
			$scope.param.booking_type = 'meal_dine_in';
		}
		if(diningType == 'book') {diningTypeName = '预订';$scope.param.booking_type = 'meal_reserve';}
		if(diningType == 'cuisine') diningTypeName = '加减菜';
		if(diningType == 'account') diningTypeName = '结账';
		$scope.diningTypeName = diningTypeName;$scope.diningType = diningType;
		var asideRestaurantBook = $aside({scope : $scope, title: $scope.action_nav_name, placement:'top',animation:'am-fade-and-slide-top',backdrop:"static",container:'#MainController', templateUrl: '/resource/views/Booking/Restaurant/tableBook.html',show: false});
		asideRestaurantBook.$promise.then(function() {
			asideRestaurantBook.show();
			$(document).ready(function(){
				$scope.getDiningCuisine();
				$('#customer_ul').mouseover(function(e) {$('#customer_ul').next().show();});
				//$('a.print-contents').printPreview('print_content');
			});
		});
	}
	//解析菜式
	var allCuisineList = '',cuisineCategory = {},cuisineSKU = {};$scope.cuisineList = {};
	$scope.getDiningCuisine = function() {
		if(allCuisineList == '') {
			$scope.loading.show();
			$httpService.header('method', 'cuisineList');
			$httpService.post('/app.do?'+param+'&id='+$scope.id, $scope, function(result){
				$scope.loading.percent();$httpService.deleteHeader('method');
				if(result.data.success == '0') {
					//$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
					return;//错误返回
				}
				allCuisineList = result.data.item.allCuisineList;
				var cuisineList = [], cuisineCategory = {}, cuisineSKU = {}, cuisine_id = 0;
				if(allCuisineList != '') {
					var j = 0;
					for(var i in allCuisineList) {
						cuisine_id = allCuisineList[i].cuisine_id;
						if(allCuisineList[i].cuisine_is_category == '1') {
							cuisineCategory[cuisine_id] = allCuisineList[i];
						} else {
							if(allCuisineList[i].sku == '1') {
								cuisineList[j] = allCuisineList[i];j++;
							} else {
								if(angular.isUndefined(cuisineSKU[allCuisineList[i].sku_cuisine_id])) {
									cuisineSKU[allCuisineList[i].sku_cuisine_id] = {};
								}
								cuisineSKU[allCuisineList[i].sku_cuisine_id][cuisine_id] = allCuisineList[i];
							}
						}
					}
					$scope.cuisineList = cuisineList;
					$scope.rowCuisineList = cuisineList;
					$scope.cuisineCategory = cuisineCategory;
					$scope.cuisineSKU = cuisineSKU;
				}
			});
		}
	}
	//已点菜式
	$scope.haveBookCuisine = {};$scope.thisBookCuisine = {};
	$scope.addBookCuisine = function(cuisine, table_id) {
		if(table_id == 0 && $scope.thisBookTable != '') {table_id = $scope.thisBookTable.item_id;}
		if(angular.isUndefined($scope.haveBookCuisine[table_id])) {
			$scope.haveBookCuisine[table_id] = {};
		}
		if(angular.isUndefined($scope.haveBookCuisine[table_id][cuisine.cuisine_id])) {
			$scope.haveBookCuisine[table_id][cuisine.cuisine_id] = angular.copy(cuisine);
			$scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber = 0;
		}
		$scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber++;
		if(angular.isUndefined($scope.thisBookCuisine[cuisine.cuisine_id])) {$scope.thisBookCuisine[cuisine.cuisine_id] = 0;}
		$scope.thisBookCuisine[cuisine.cuisine_id]++;
		if(table_id > 0) $scope.thisBookTable = $scope.haveBookTable[table_id];
	}
	$scope.reduceBookCuisine = function(cuisine, table_id) {
		if(table_id == 0 && $scope.thisBookTable != '') {table_id = $scope.thisBookTable.item_id;}
		if(angular.isUndefined($scope.haveBookCuisine[table_id])) {return;}
		if(angular.isUndefined($scope.haveBookCuisine[table_id][cuisine.cuisine_id])) {return;}
		$scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber--;
		if($scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber <= 0) 
			delete $scope.haveBookCuisine[table_id][cuisine.cuisine_id];
		$scope.thisBookCuisine[cuisine.cuisine_id]--;
		if($scope.thisBookCuisine[cuisine.cuisine_id] <= 0) 
			delete $scope.thisBookCuisine[cuisine.cuisine_id];
		if(table_id > 0) $scope.thisBookTable = $scope.haveBookTable[table_id];
	}
	$scope.clearBookCuisine = function() {
		$scope.haveBookCuisine = {};
	}
	$scope.setThisTable = function(table_id) {
		$scope.thisBookTable = $scope.haveBookTable[table_id];
	}
	//已点菜匹配桌台
	$scope.setHaveBookCuisineTable = function() {
		if(angular.isDefined($scope.haveBookCuisine[0])) {//0 table_id 为没有选择桌前加的菜式
			var haveBookCuisine = $scope.haveBookCuisine[0];
			delete $scope.haveBookCuisine[0];
			var table_id = $scope.thisBookTable.item_id;
			$scope.haveBookCuisine[table_id] = haveBookCuisine;
		}
	}
	//已选桌台
	$scope.haveBookTable = {};$scope.thisBookTable = '';
	$scope.addBookTable = function(table) {
		if(angular.isUndefined($scope.haveBookTable[table.item_id])) {
			$scope.haveBookTable[table.item_id] = table;
		}
		$scope.thisBookTable = table;
	}
	$scope.reduceBookTable = function(table) {
		if(angular.isUndefined($scope.haveBookTable[table.item_id])) {return;}
		delete $scope.haveBookTable[table.item_id];
		if(angular.isDefined($scope.haveBookCuisine[table.item_id])) delete $scope.haveBookCuisine[table.item_id];
	}
	$scope.clearBookTable = function() {
		$scope.haveBookTable = {};
	}
    //打印
    $scope.printBill = function(print) {
        var consumePrint = $scope.consumePrint,accountPrint = $scope.accountPrint, borrowingPrint = $scope.borrowingPrint;
        var isConsumePrint = false, isAccountPrint = false, isBorrowingPrint = false;
        for(var i in consumePrint) {isConsumePrint = true;break;}
        for(var i in accountPrint) {isAccountPrint = true;break;}
        if(isConsumePrint == false && isAccountPrint == false) {
            $scope.consumePrint = angular.copy($scope.consumeDetail);isConsumePrint = true;
            $scope.accountPrint = angular.copy($scope.accountDetail);isAccountPrint = true;
        }
        if(print == 'borrowing') {
            isBorrowingPrint = true;isConsumePrint = false; isAccountPrint = false;
        } else {
            isBorrowingPrint = false;
        }
        $scope.isConsumePrint = isConsumePrint;$scope.isAccountPrint = isAccountPrint; $scope.isBorrowingPrint = isBorrowingPrint;
    };
    //
});