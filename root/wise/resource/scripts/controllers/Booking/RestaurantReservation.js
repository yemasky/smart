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
	$scope.bookingTypeList = [{'name':'堂食','booking_type':'meal_dine_in'},{'name':'预订','booking_type':'meal_reserve'},
							  {'name':'外卖','booking_type':'meal_take_out'}];
	$scope.param.booking_type = 'meal_dine_in';$scope.booking_type = 'meal_dine_in';//堂食
	//设置酒店餐饮频道 首次进入设置
    $scope.setThisChannel('Meal');//酒店频道
	//获取数据
	var _channel = $scope.$stateParams.channel;
	var _view = $scope.$stateParams.view,common = '';
	var param = 'channel='+_channel+'&view='+_view;
	$scope.absurl = '#!/app/booking/'+_view+'/'+_channel;
    beginMealStatus();//开始执行
    function beginMealStatus() {
        $scope.loading.show();
        $httpService.post('app.do?'+param, $scope, function(result){
            $scope.loading.percent();
            if(result.data.success == '0') {
                //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                return;//错误返回
            }
            common = result.data.common;
            $scope.setCommonSetting(common);
            $scope.setThisChannel('Meal');//酒店频道
			$scope.marketList = result.data.item.marketList;//客源市场
			$scope.getHashMarket(result.data.item.marketList);
			$scope.channelDiscountList = result.data.item.channelDiscountList;//折扣
            $(document).ready(function(){
                $scope.channel_id = $scope.thisChannel_id;
				$scope.id = $rootScope.employeeChannel[$scope.thisChannel_id].id;
                //$scope.defaultHotel = $scope.thisChannel[$scope.thisChannel_id]["channel_name"];
				//设置客源市场  
            	$scope.selectCustomerMarket($scope.marketList[1].children[2], false);
				$scope.roomList          = result.data.item.roomList;//客房列表
				$scope.rowRoomList       = result.data.item.roomList;//客房列表
				//$scope.bookList          = result.data.item.bookList;//预订列表
				//时间
                var _thisDay = result.data.item.in_date;
                var _thisTime = $filter('date')($scope._baseDateTime(), 'HH:mm');
                $scope.param["check_in"] = _thisDay;
                $('.check_in').val(_thisDay);
                $scope.param["in_time"] = _thisDay+'T14:00:00.000Z';
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
		$httpService.post('app.do?'+param+'&id='+$scope.id, $scope, function(result){
			$scope.loading.percent();if(result.data.success == '0') {return;}//
			$scope.roomList            = result.data.item.roomList;//客房列表
			$scope.rowRoomList         = result.data.item.roomList;//客房列表
			$scope.channelDiscountList = result.data.item.channelDiscountList;//折扣
			//$scope.bookList            = result.data.item.bookList;//预订列表
			allCuisineList = '';
			$scope.cuisineList = {};$scope.rowCuisineList = {};
			$scope.cuisineCategory = {};$scope.cuisineSKU = {};
			if($scope.activeTab == 3) {$scope.getDiningCuisine();};//菜品页
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
					$httpService.post('app.do?'+param, $scope, function(result){
						$scope.loading.percent();$httpService.deleteHeader('method');
						if(result.data.success === '0') {return;}
						$scope.receivableList = result.data.item.receivableData;
					});
				}
			}
			//重新计算折扣
			for(var table_id in $scope.haveBookCuisine) {
				for(var i in $scope.haveBookCuisine[table_id]) {
					$scope.haveBookCuisine[table_id][i] = countDiscount($scope.haveBookCuisine[table_id][i]);
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
		var asideRestaurantBook = $aside({scope : $scope, title: $scope.action_nav_name, placement:'top',animation:'am-fade-and-slide-top',backdrop:"static",container:'#MainController', templateUrl: 'resource/views/Booking/Restaurant/tableBook.html',show: false});
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
	var thisWeek = $scope.getDay('w');
	$scope.getDiningCuisine = function() {
		if(allCuisineList == '') {
			$scope.loading.show();$httpService.header('method', 'cuisineList');
			$httpService.post('app.do?'+param+'&id='+$scope.id, $scope, function(result){
				$scope.loading.percent();
				if(result.data.success == '0') {
					//$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
					return;//错误返回
				}
				allCuisineList = result.data.item.allCuisineList;
				var cuisineList=[],cuisineCategory={},cuisineSKU={},cuisine_id=0,hashCuisineSKU={},categoryKey={};
				if(allCuisineList != '') {
					var discountList = $scope.channelDiscountList,hashDiscountItem = {},k=0;//折扣 优惠卷
					for(var i in discountList) {
						var discount = discountList[i];
						var week = discount.use_week.split(','),
							discount_item_list = discount.discount_item_list.split(','),
							market_ids = discount.market_ids.split(',');
						for(var j in discount_item_list) {
							if(discount_item_list[j] > 0) {//菜式ID>0
								if(angular.isUndefined(hashDiscountItem[discount_item_list[j]])) 
									hashDiscountItem[discount_item_list[j]] = {};
								hashDiscountItem[discount_item_list[j]][k] = discount;//菜式有几个折扣
								hashDiscountItem[discount_item_list[j]][k]['week'] = week;
								hashDiscountItem[discount_item_list[j]][k]['market_ids'] = market_ids;
								k++;
							}
						}
					}
					var j = 0;
					for(var i in allCuisineList) {
						cuisine_id = allCuisineList[i].cuisine_id;
						if(allCuisineList[i].sku_complete_dinner == 1) {//套菜
							var complete_ids = allCuisineList[i].sku_complete_dinner_ids.split(',');
							allCuisineList[i].complete_ids = complete_ids;
						}
						if(allCuisineList[i].cuisine_is_category == '1') {
							cuisineCategory[cuisine_id] = allCuisineList[i];
						} else {
							cuisineList[j] = allCuisineList[i];
							if(angular.isUndefined(categoryKey[cuisineList[j].cuisine_category_id])) {
								categoryKey[cuisineList[j].cuisine_category_id] = '';
								cuisineList[j].categoryKey = 'category_'+cuisineList[j].cuisine_category_id;
							} else {
								cuisineList[j].categoryKey = '';
							}
							cuisineList[j].discount = '';
							if(angular.isDefined(hashDiscountItem[cuisine_id])) {
								cuisineList[j].discount = hashDiscountItem[cuisine_id];
							}
							j++;
							if(allCuisineList[i].sku == '1') {
							} else {
								if(angular.isUndefined(cuisineSKU[allCuisineList[i].sku_cuisine_id])) {
									cuisineSKU[allCuisineList[i].sku_cuisine_id] = {};
								}
								cuisineSKU[allCuisineList[i].sku_cuisine_id][cuisine_id] = allCuisineList[i];
							}
						}
						hashCuisineSKU[cuisine_id] = allCuisineList[i];
					}
					$scope.cuisineList = cuisineList;$scope.rowCuisineList = cuisineList;
					$scope.cuisineCategory = cuisineCategory;$scope.cuisineSKU = cuisineSKU;
					$scope.hashCuisineSKU = hashCuisineSKU;
				}
			});
		}
	}
	$scope.setCuisineCategory = function($event, category_id) {
		$event.target.value = category_id;
	}
	//已点菜式
	$scope.haveBookCuisine = {};$scope.thisBookCuisine = {};
	function getBookTableId(table_id) {
		if(table_id > 0 && angular.isUndefined($scope.haveBookTable[table_id])) {
			alert('error 0000000');return false;
		}
		if(table_id > 0) $scope.thisBookTable = $scope.haveBookTable[table_id];//thisBookTable
		if(table_id == 0 && angular.isDefined($scope.thisBookTable.item_id)) table_id = $scope.thisBookTable.item_id;
		return table_id;
	}
	function countDiscount(cuisine) {
		var create_time = 0; cuisine['is_discount'] = false; cuisine['discount_price'] = '';
		for(var k in cuisine.discount) {
			var discount = cuisine.discount[k],week = discount.week, market_ids = discount.market_ids;
			if(discount.discount_category == 'discount') {//1打折 2直减 3满减 4积分 5优惠卷 6现金红包 7现金卷
				if(discount.discount_type == '1' || discount.discount_type == '2') {
					var unix_time =  new Date(discount.add_datetime.replace(/-/g,'/')).getTime() - 0;
					if(unix_time > create_time) { create_time = unix_time;
					} else {continue;}//取折扣的最新时间
				} else {continue;}
				var discount_price = -1;
				if(discount.discount_type == '1') {//折扣
					discount_price = $scope.arithmetic(cuisine.cuisine_price,'*',discount.discount,2); 
				}
				if(discount.discount_type == '2') {//直减
					discount_price = $scope.arithmetic(cuisine.cuisine_price,'-',discount.discount,2);
				}
				if(discount_price > -1) {//判断折扣有效性 周比较
					for(var w in week) {
						if(thisWeek == week[w]) {
							for(var m in market_ids) {
								if($scope.market_id == market_ids[m]) {
									cuisine.is_discount = true;
									cuisine.discount_price = discount_price;
								}
								break;
							}
							break;
						}
					}
				}
			}
		}
		return cuisine;
	}
	$scope.addBookCuisine = function(cuisine, table_id) {
		//计算折扣
		cuisine = countDiscount(cuisine);
		/////end 折扣
		table_id = getBookTableId(table_id);
		if(table_id === false) return;
		if(angular.isUndefined($scope.haveBookCuisine[table_id])) $scope.haveBookCuisine[table_id] = {};
		if(angular.isUndefined($scope.haveBookCuisine[table_id][cuisine.cuisine_id])) {//第一次加菜
			$scope.haveBookCuisine[table_id][cuisine.cuisine_id] = angular.copy(cuisine);
			$scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber = 0;//
		}
		$scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber++;//已订桌子已订菜++
		if(angular.isUndefined($scope.thisBookCuisine[cuisine.cuisine_id])) {$scope.thisBookCuisine[cuisine.cuisine_id] = 0;}
		$scope.thisBookCuisine[cuisine.cuisine_id]++;//已订菜++
	}
	$scope.reduceBookCuisine = function(cuisine, table_id) {
		table_id = getBookTableId(table_id);
		if(table_id === false) return;
		$scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber--;//已订桌子已订菜--
		if($scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber <= 0) 
			delete $scope.haveBookCuisine[table_id][cuisine.cuisine_id];
		$scope.thisBookCuisine[cuisine.cuisine_id]--;//已订菜--
		if($scope.thisBookCuisine[cuisine.cuisine_id] <= 0) 
			delete $scope.thisBookCuisine[cuisine.cuisine_id];
	}
	$scope.clearBookCuisine = function() {//清除已订菜
		$scope.haveBookCuisine = {};$scope.thisBookCuisine = {};
	}
	$scope.setThisTable = function(table_id) {
		$scope.activeBookAccountsEditTab = 1;
		$scope.thisBookTable = $scope.haveBookTable[table_id];
	}
	//已点菜匹配桌台
	$scope.setHaveBookCuisineTable = function() {
		if(angular.isDefined($scope.haveBookCuisine[0])) {//0 table_id 为没有选择桌前加的菜式
			if(angular.isDefined($scope.thisBookTable.item_id)) {
				var table_id = $scope.thisBookTable.item_id;
				var haveBookCuisine = $scope.haveBookCuisine[0];
				delete $scope.haveBookCuisine[0];
				$scope.haveBookCuisine[table_id] = haveBookCuisine;
			}
		}
	}
	//已选桌台
	$scope.haveBookTable = {};$scope.thisBookTable = {};
	$scope.addBookTable = function(table) {
		$scope.haveBookTable[table.item_id] = table;$scope.thisBookTable = table;
		$scope.setHaveBookCuisineTable();
	}
	function reduceBookTableCuisine(table) {
		delete $scope.haveBookTable[table.item_id];
		var haveBookCuisine = $scope.haveBookCuisine[table.item_id];
		for(var cuisine_id in haveBookCuisine) {
			var bookNumber = haveBookCuisine[cuisine_id].bookNumber
			$scope.thisBookCuisine[cuisine_id] -= bookNumber;
			if($scope.thisBookCuisine[cuisine_id] <= 0) 
				delete $scope.thisBookCuisine[cuisine_id];
		}
		delete $scope.haveBookCuisine[table.item_id];
	}
	$scope.reduceBookTable = function(table) {
		if(table.item_id == $scope.thisBookTable.item_id) {
			$scope.thisBookTable = {};
			//删除已订桌子 删除已订菜式
			reduceBookTableCuisine(table);
			for(var i in $scope.haveBookTable) {
				$scope.thisBookTable = $scope.haveBookTable[i];break;//如果还有桌子重新设置thisBookTable
			}
		} else {
			reduceBookTableCuisine(table);
		}
	}
	$scope.clearBookTable = function() {
		$scope.haveBookTable = {};$scope.thisBookTable = {};
	}
	//计算折扣
	
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
//**SAVE*//*****************************************************************////////////////////////////////////////////////////////////
	$scope.saveRestaurantBook = function() {
		$scope.param.booking_data = resolutionBookingData();
		$scope.param.channel_id = $scope.channel_id;
        $scope.param.channel_father_id = $rootScope.employeeChannel[$scope.thisChannel_id].channel_father_id;
		$scope.loading.show();
		$httpService.header('method', 'saveRestaurantBook');
		$httpService.post('app.do?'+param+'&id='+$scope.id, $scope, function(result){
			$scope.loading.percent();
			if(result.data.success == '0') {//$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
				return;//错误返回
			}
		})
		function resolutionBookingData() {
			var haveBookCuisine = $scope.haveBookCuisine, booking_data = {};
			for(var table_id in haveBookCuisine) {
				var tableBookCuisine = haveBookCuisine[table_id];
				booking_data[table_id] = {};
				for(var cuisine_id in tableBookCuisine) {
					booking_data[table_id][cuisine_id] = {};
					var bookCuisine = tableBookCuisine[cuisine_id];
					booking_data[table_id][cuisine_id].cuisine_id              = bookCuisine.cuisine_id;
					booking_data[table_id][cuisine_id].cuisine_category_id     = bookCuisine.cuisine_category_id;
					booking_data[table_id][cuisine_id].cuisine_name            = bookCuisine.cuisine_name;
					booking_data[table_id][cuisine_id].cuisine_en_name         = bookCuisine.cuisine_en_name;
					booking_data[table_id][cuisine_id].sku_complete_dinner_ids = bookCuisine.sku_complete_dinner_ids;
					booking_data[table_id][cuisine_id].cuisine_price           = bookCuisine.cuisine_price;
					booking_data[table_id][cuisine_id].bookNumber              = bookCuisine.bookNumber;
				}
			}
			return booking_data;
		}
	}
});