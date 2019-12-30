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
	$scope.bookingTypeHash = {'booking_type':'堂食','meal_reserve':'预订','meal_take_out':'外卖'};
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
				$scope.channel_father_id = $rootScope.employeeChannel[$scope.thisChannel_id].channel_father_id;
				$scope.id = $rootScope.employeeChannel[$scope.thisChannel_id].id;
                //$scope.defaultHotel = $scope.thisChannel[$scope.thisChannel_id]["channel_name"];
				//设置客源市场  
            	$scope.selectCustomerMarket($scope.marketList[1].children[2], false);
				$scope.roomList          = result.data.item.roomList;//客房列表
				$scope.rowRoomList       = result.data.item.roomList;//客房列表
				//时间
                var _thisDay = result.data.item.in_date;
                var _thisTime = $filter('date')($scope._baseDateTime(), 'HH:mm');
                $scope.param["check_in"] = _thisDay;
                $('.check_in').val(_thisDay);
                $scope.param["in_time"] = _thisDay+'T14:00:00.000Z';
				//
				showBookDetail(result.data.item);
				/*$('.check_date').daterangepicker({singleDatePicker: true, "autoApply": true,"startDate": _thisDay,"locale":{"format" : 'YYYY-MM-DD hh:mm'}
				}, function(start, label) {
					var check_in = start.format('YYYY-MM-DD');
					$scope.param.check_in = check_in;
					$('.check_in').val(check_in);
				});*/
            });
        });	
    }
	$scope.bookRoomStatus = {};$scope.bookList = {};
	function showBookDetail(bookData) {
		$scope.bookList = bookData.bookList;
		var bookRoomStatus = {}, bookDetail = bookData.bookDetailRoom;
		//,bookingReserveStatus = {},bookingTakeOutStatus = {};//，预订，外卖
		if(bookDetail != '') $scope.getDiningCuisine();
		for(var detail_id in bookDetail) {
			if(bookDetail[detail_id].booking_type == 'meal_dine_in') {//堂食
				var table_id = bookDetail[detail_id].item_id;
				if(angular.isUndefined(bookRoomStatus[table_id])) {
					bookRoomStatus[table_id] = {};
				}
				bookRoomStatus[table_id][detail_id] = bookDetail[detail_id];
			}
		}
		$scope.bookRoomStatus = bookRoomStatus;//桌态
		$scope.bookConsumeList = bookData.bookConsumeList;//消费列表
		$scope.bookCuisineList = bookData.bookCuisineList;//预订菜式列表
		//hashTable
		var hashTable = {};
		for(var building in $scope.roomList) {
			for(var floor in $scope.roomList[building]) {
				for(var i in $scope.roomList[building][floor]) {
					var table = $scope.roomList[building][floor][i];
					hashTable[table.item_id] = table;
				}
			}
		}
		$scope.hashTable = hashTable;
		var channelConsume = bookData.channelConsumeList;
		var channelConsumeList = {};
		if(channelConsume!='') {
			for(var i in channelConsume) {
				var consume = channelConsume[i];
				var consume_id = consume.channel_consume_id;
				var consume_father_id = consume.channel_consume_father_id;
				if(consume_id == consume_father_id) {
					var children = {}
					if(angular.isDefined(channelConsumeList[consume_father_id])) {
						children = channelConsumeList[consume_father_id].children;
					}
					consume['children'] = children;
					channelConsumeList[consume_father_id] = consume;
				} else {
					if(angular.isUndefined(channelConsumeList[consume_father_id])) {
						channelConsumeList[consume_father_id] = {};
						channelConsumeList[consume_father_id]['children'] = {};
					}
					channelConsumeList[consume_father_id]['children'][consume_id] = consume;
				}             
			}
		}
		$scope.channelConsumeList = channelConsumeList;
		$scope.paymentTypeList    = bookData.paymentTypeList;//支付方式
	}
	//更换餐厅
	$scope.changeChannel = function(channel_id) {
		$scope.id = $rootScope.employeeChannel[channel_id].id;
		$scope.channel_id = $rootScope.employeeChannel[channel_id].channel_id;
		$scope.channel_father_id = $rootScope.employeeChannel[channel_id].channel_father_id;
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
		$('#customer_ul').next().show();
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
		$scope.diningSaveButton = '下单';
		$scope.activeBookAccountsEditTab=1;
		if(diningType == 'open') {//开台$scope.param.number_of_people = 1;
			diningTypeName = '堂食';
			$scope.clearBookTable();$scope.clearBookCuisine();
			table.detail_id = 0;//新加table
			$scope.addBookTable(table);
			$scope.param.booking_type = 'meal_dine_in';
		}
		if(diningType == 'book') {diningTypeName = '预订';$scope.param.booking_type = 'meal_reserve';}
		if(diningType == 'cuisine') diningTypeName = '加减菜';
		if(diningType == 'account') diningTypeName = '结账';
		if(diningType == 'editBook') $scope.diningSaveButton = '保存';
		$scope.diningTypeName = diningTypeName;$scope.diningType = diningType;
		var asideRestaurantBook = $aside({scope : $scope, title: $scope.action_nav_name, placement:'top',animation:'am-fade-and-slide-top',backdrop:"static",container:'#MainController', templateUrl: 'resource/views/Booking/Restaurant/tableBook.html?'+__VERSION,show: false});
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
								var discount_id = discount.discount_id;
								hashDiscountItem[discount_item_list[j]][discount_id] = discount;//菜式有几个折扣
								hashDiscountItem[discount_item_list[j]][discount_id]['week'] = week;
								hashDiscountItem[discount_item_list[j]][discount_id]['market_ids'] = market_ids;
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
	//searchMember
	$scope.searchMember = function(search) {
        if($scope.market_father_id == '4' || search == 'member') {//判断会员是否正确
            var mobile_email = $scope.param.mobile_email;var member_mobile = '', member_email = '';
            if(angular.isUndefined(mobile_email) || mobile_email.indexOf("@") != -1) {
                var emailRegexp = /^([0-9a-zA-Z\.\-\_]+)@([0-9a-zA-Z\.\-\_]+)\.([a-zA-Z]{2,5})$/i;
                if(!emailRegexp.test(mobile_email)) {
                    $alert({title: 'Error', content: '填写的Email不对！', templateUrl: '/modal-warning.html', show: true});
                    return false;
                } 
                member_email = mobile_email;
            } else {
               var mobileReg = /^(13|14|15|16|17|18|19)+\d{9}$/;
                if(!mobileReg.test(mobile_email)) {
                    $alert({title: 'Error', content: '填写的手机号不对！', templateUrl: '/modal-warning.html', show: true});
                    return false;
                } 
                member_mobile = mobile_email;
            }
            $scope.loading.start();
            $httpService.header('method', 'checkMember');
            var $checkMember = {};
            $checkMember.param = {};
            $checkMember.param['member_email']      = member_email;
            $checkMember.param['member_mobile']     = member_mobile;
            $checkMember.param['channel_father_id'] = $scope.channel_father_id;
            $httpService.post('app.do?'+param, $checkMember, function(result){
                $scope.loading.percent();$httpService.deleteHeader('method');
                if(result.data.success == '0') {
                    var message = $scope.getErrorByCode(result.data.code);
                    var message_ext = '.没有找到"'+mobile_email+'"的会员记录！';
                    $alert({title: 'Notice', content: message+message_ext, templateUrl: '/modal-warning.html', show: true});
                } else {
                    $scope.market_id = result.data.item.market_id;
                    $scope.param.member_id = result.data.item.member_id;
                    if(search == 'member') {
                        //找出markey
                        var market = '';
                        for(var mi in $scope.marketList) {
                            for(var mii in $scope.marketList[mi]['children']) {
                                if($scope.market_id == $scope.marketList[mi]['children'][mii].market_id) {
                                    market = $scope.marketList[mi]['children'][mii];
                                    break;
                                }
                            }
                        }
                        if(market != '') {
                            $scope.selectCustomerMarket(market, true);
                        } else {
                            var message_ext = '没有找到"'+mobile_email+'"的会员记录！';
                            $alert({title: 'Notice', content: message_ext, templateUrl: '/modal-warning.html', show: true});
                        }
                    } 
                }
            });
        } 
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
	function countDiscount(cuisine) {//计算折扣
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
									cuisine.is_discount       = true;
									cuisine.discount_price    = discount_price;
									cuisine.discount_id       = discount.discount_id;
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
	$scope.addBookCuisine = function(cuisine, table_id) {//新加菜品
		table_id = getBookTableId(table_id);//先计算桌子
		//计算折扣 
		cuisine = countDiscount(cuisine);
		if(angular.isUndefined(cuisine.detail_id)) {
			cuisine.detail_id = 0;//未下单
		}
		console.log(cuisine.detail_id);console.log(table_id);console.log($scope.thisBookTable);
		if(table_id > 0) {
			cuisine.detail_id = $scope.thisBookTable.detail_id;
			cuisine.ec_detail_id = $scope.thisBookTable.ec_detail_id;
			cuisine.item_id = $scope.thisBookTable.item_id;
			cuisine.item_name = $scope.thisBookTable.item_name;
		}
		console.log(cuisine.detail_id);
		if(table_id === false) return;
		if($scope.diningType == 'editBook') {
			var editCuisine = {};editCuisine.param = {};
			editCuisine.param = cuisine;
			editCuisine.param.type = 'Add';
			var message = cuisine.cuisine_name+'加菜成功！';
			editThisBookCuisine(editCuisine, addCuisine, message);
		} else {addCuisine();}
		function addCuisine() {
			if(angular.isUndefined($scope.haveBookCuisine[table_id])) $scope.haveBookCuisine[table_id] = {};
			if(angular.isUndefined($scope.haveBookCuisine[table_id][cuisine.cuisine_id])) {//第一次加菜
				$scope.haveBookCuisine[table_id][cuisine.cuisine_id] = angular.copy(cuisine);
				$scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber = 0;//
			}
			$scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber++;//已订桌子已订菜++
			if(angular.isUndefined($scope.thisBookCuisine[cuisine.cuisine_id])) {$scope.thisBookCuisine[cuisine.cuisine_id] = 0;}
			$scope.thisBookCuisine[cuisine.cuisine_id]++;//已订菜++
		}
	}
	$scope.reduceBookCuisine = function(cuisine, table_id) {//删除新加菜品
		table_id = getBookTableId(table_id);
		if(table_id === false) return;
		if($scope.diningType == 'editBook') {
			var editCuisine = {};editCuisine.param = {};
			editCuisine.param = cuisine;
			editCuisine.param.type = 'Reduce';
			var message = cuisine.cuisine_name+'减菜成功！';
			editThisBookCuisine(editCuisine, reduceCuisine, message);
		} else {reduceCuisine();};
		function reduceCuisine() {
			$scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber--;//已订桌子已订菜--
			if($scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber <= 0) {
				$scope.haveBookCuisine[table_id][cuisine.cuisine_id].bookNumber = 0;
				//if($scope.haveBookCuisine[table_id][cuisine.cuisine_id].detail_id == 0) {
					delete $scope.haveBookCuisine[table_id][cuisine.cuisine_id];
				//} 
			}
			if(angular.isUndefined($scope.thisBookCuisine[cuisine.cuisine_id])) return;
			$scope.thisBookCuisine[cuisine.cuisine_id]--;//已订菜--
			if($scope.thisBookCuisine[cuisine.cuisine_id] <= 0) 
				delete $scope.thisBookCuisine[cuisine.cuisine_id];
		}
	}
	function editThisBookCuisine(editParam, callback, message) {
		editParam.param.book_id = $scope.thisBook.book_id;
		$httpService.header('method', 'editBookEditCuisine');
		$scope.loading.start();
		$httpService.post('app.do?'+param+'&id='+$scope.id, editParam, function(result){
			$scope.loading.percent();
			if(result.data.success == '0') {
				return;//错误返回
			}
			$scope.successAlert.startProgressBar(message);
			callback();
		})
	}
	$scope.clearBookCuisine = function() {//清除已订菜
		$scope.haveBookCuisine = {};$scope.thisBookCuisine = {};
	}
	$scope.setThisTable = function(table_id) {
		$scope.activeBookAccountsEditTab = 1;
		$scope.thisBookTable = $scope.haveBookTable[table_id];
	}
	$scope.reduceBookEditCuisine = function(cuisine, table_id) {
		var message = cuisine.cuisine_name+'退菜成功！';
		var editCuisine = {};editCuisine.param = {};
		editCuisine.param.cuisine_id = cuisine.cuisine_id;
		editCuisine.param.detail_id = cuisine.detail_id;
		editCuisine.param.type = 'ReduceBook';
		editThisBookCuisine(editCuisine, reduceBookCuisine, message);		
		function reduceBookCuisine() {
			//$scope.bookEditCuisine[table_id][cuisine.cuisine_id].detail_id = bookCuisine.booking_detail_id;
			$scope.bookEditCuisine[table_id][cuisine.cuisine_id].cuisine_number--;//
			//$scope.bookEditCuisine[table_id][cuisine.cuisine_id].cuisine_number_over = bookCuisine.cuisine_number_over;//
			//$scope.bookEditCuisine[table_id][cuisine.cuisine_id].cuisine_number_return = bookCuisine.cuisine_number_return;//
		}
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
		if(angular.isUndefined(table.detail_id)) {table.detail_id = 0,table.ec_detail_id = '';};
		$scope.haveBookTable[table.item_id] = table;$scope.thisBookTable = table;
		$scope.setHaveBookCuisineTable();
	}
	function reduceBookTableCuisine(table) {//删除桌台菜式
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
	$scope.reduceBookTable = function(table) {//删除桌台
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
	}//
	$scope.activeBookAccountsEdit = function(active) {
		$scope.activeBookAccountsEditTab=active;
	}
	//编辑 结算 结账 取消订单操作
	$scope.bookEditCuisine = {};//本次已订的菜品数据
	$scope.setEdit = function(is_edit) {$scope.isEdit = is_edit;}
	$scope.thisBook = {};
	$scope.editDiningTable = function(bookingDetail) {
		var bookCuisine = $scope.bookCuisineList, thisBook = $scope.bookList[bookingDetail.booking_number];
		$scope.thisBook = thisBook;$scope.param = thisBook; $scope.bookingDetail = bookingDetail;$scope.isEdit = 0;
		$scope.market_name = bookingDetail.market_name;$scope.market_id = bookingDetail.market_id;
        $scope.market_father_id =  bookingDetail.market_father_id;
		var bookRoomStatus = $scope.bookRoomStatus;//计算已订菜式 已订餐桌
		$scope.haveBookTable = {};$scope.thisBookTable = {};//初始化已订餐桌和本次餐桌thisBookTable
		for(var table_id in bookRoomStatus) {
			for(var detail_id in bookRoomStatus[table_id]) {
				if(bookingDetail.booking_number == bookRoomStatus[table_id][detail_id].booking_number) {
					$scope.hashTable[table_id].detail_id = detail_id;
					$scope.hashTable[table_id].ec_detail_id = bookRoomStatus[table_id][detail_id].ec_detail_id;
					$scope.addBookTable($scope.hashTable[table_id]);//已订餐桌
				}
			}
		}
		$scope.haveBookCuisine = {};//清空上次的新增菜数据
		$scope.thisBookCuisine = {};$scope.bookEditCuisine = {};//本次已订的菜品数据
		for(var detail_id in bookCuisine) {//取得已订菜
			if(detail_id == bookingDetail.booking_detail_id) {//取出这次的订菜数据
				for(var cuisine_id in bookCuisine[detail_id]) {
					editBookCuisine(bookCuisine[detail_id][cuisine_id], bookCuisine[detail_id][cuisine_id].item_id);
				}
			}
		}//console.log($scope.haveBookCuisine);
		function editBookCuisine (bookCuisine, table_id) {
			var cuisine = $scope.hashCuisineSKU[bookCuisine.cuisine_id];
			if(angular.isUndefined($scope.bookEditCuisine[table_id])) $scope.bookEditCuisine[table_id] = {};
			$scope.bookEditCuisine[table_id][cuisine.cuisine_id] = angular.copy(cuisine);
			$scope.bookEditCuisine[table_id][cuisine.cuisine_id].is_discount = bookCuisine.is_discount;
			$scope.bookEditCuisine[table_id][cuisine.cuisine_id].bookNumber = 0;
			$scope.bookEditCuisine[table_id][cuisine.cuisine_id].detail_id = bookCuisine.booking_detail_id;
			$scope.bookEditCuisine[table_id][cuisine.cuisine_id].cuisine_number = bookCuisine.cuisine_number;//
			$scope.bookEditCuisine[table_id][cuisine.cuisine_id].cuisine_number_over = bookCuisine.cuisine_number_over;//
			$scope.bookEditCuisine[table_id][cuisine.cuisine_id].cuisine_number_return = bookCuisine.cuisine_number_return;//
			$scope.bookEditCuisine[table_id][cuisine.cuisine_id].discount_price = bookCuisine.cuisine_sell_price;//
		}
		$scope.diningTable('editBook', '');
	}
//**SAVE*//*****************************************************************///////////////////////////////////////////////////////
	$scope.saveRestaurantBook = function() {
		$scope.param.booking_data = resolutionBookingData();
		$scope.param.channel_id = $scope.channel_id;
        $scope.param.channel_father_id = $rootScope.employeeChannel[$scope.thisChannel_id].channel_father_id;
		$scope.param.market_id = $scope.market_id;$scope.param.market_father_id = $scope.market_father_id;
		$scope.param.market_name = $scope.market_name;
		if(angular.isDefined($scope.param.in_time) && $scope.param['in_time'].length > 8) $scope.param['in_time'] = $filter('limitTo')($scope.param['in_time'], 8, 11);
		if(angular.isUndefined($scope.param.member_name) || $scope.param.member_name == '') $scope.param.member_name = '散客';
		$scope.loading.show();
		$httpService.header('method', 'saveRestaurantBook');
		if($scope.diningType == 'editBook') $httpService.header('method', 'editRestaurantBook');
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
					booking_data[table_id][cuisine_id].is_discount             = bookCuisine.is_discount;
					booking_data[table_id][cuisine_id].discount_price          = bookCuisine.discount_price;
					if(bookCuisine.is_discount) {
						var discount_id                                        = bookCuisine.discount_id;
						booking_data[table_id][cuisine_id].discount_id         = discount_id;
						booking_data[table_id][cuisine_id].discount_type       = bookCuisine.discount[discount_id].discount_type;
						booking_data[table_id][cuisine_id].discount_category   = bookCuisine.discount[discount_id].discount_category;
						booking_data[table_id][cuisine_id].discount            = bookCuisine.discount[discount_id].discount;
					}
				}
			}
			return booking_data;
		}
	}
//***************////收款//////////////////////////////////////////////////////////
    $scope.payment_name = '选择支付方式';
    var asideAccounts = '';
    $scope.bookingAccounts = function(account, type) {
		$scope.bookExtend = 'accounts';
        var title = '收款', accounts_type = 'receipts';
        if(type == 'refund') {title = '退款';accounts_type = 'refund';};
        if(type == 'hanging') {title = '挂账';accounts_type = 'hanging';};
        if(type == 'edit') title = '修改账款';
        $scope.param.credit_authorized_days = $scope.getDay('yyyy-MM-dd HH:mm:ss');
        $scope.param.accounts_type = accounts_type;$scope.param.ba_id = '';
        asideAccounts = $aside({scope : $scope, title: title, placement:'left',animation:'am-fade-and-slide-left',backdrop:"static",container:'#MainController', templateUrl: 'resource/views/Booking/Restaurant/tableBookExtend.html?'+__VERSION,show: false});
		asideAccounts.$promise.then(function() {
			asideAccounts.show();
			$(document).ready(function(){
			});
		});
    };
    $scope.payment_id = '';
    $scope.selectPaymentType = function(payment) {
        if(angular.isDefined(payment)) {
            $scope.payment_name = payment.payment_name;
            $scope.payment_id = payment.payment_id;
            $scope.payment_father_id =  payment.payment_father_id;
            $('#payment_ul').next().hide();
        }
	};
    //收款
    $scope.showPaymentUL = function() {$('#payment_ul').next().show();};
    $scope.saveAccounts = function() {
        $scope.beginLoading =! $scope.beginLoading;
        $httpService.header('method', 'saveAccounts');
        if(angular.isDefined($scope.roomDetail[$scope.param.booking_detail_id])) {
            var bookRoomDetail = $scope.roomDetail[$scope.param.booking_detail_id];
            $scope.param['booking_number'] = bookRoomDetail.booking_number;
            $scope.param['booking_number_ext'] = bookRoomDetail.booking_number_ext;
            $scope.param['channel'] = bookRoomDetail.channel;
            $scope.param['booking_type'] = bookRoomDetail.booking_type;
            $scope.param['member_id'] = bookRoomDetail.member_id;
        }
        $scope.param['payment_name'] = $scope.payment_name;
        $scope.param['payment_id'] = $scope.payment_id;
        $scope.param['payment_father_id'] = $scope.payment_father_id;
        if($scope.param['payment_id'] == '') {
            console.log($scope.param);
            return;
        }
        $httpService.post('app.do?'+param, $scope, function(result) {
            $scope.beginLoading =! $scope.beginLoading;
            $scope.param.ba_id = '';//设置编辑为空
            $httpService.deleteHeader('method');
            if (result.data.success == '0') {
                var message = $scope.getErrorByCode(result.data.code);
                return;//错误返回
            }
            $scope.successAlert.startProgressBar();
            if(asideAccounts != '') asideAccounts.hide();
            var accounts_id = result.data.item.accounts_id, booking_number = angular.copy($scope.param['booking_number']);
            if(angular.isDefined($scope.accountDetail[accounts_id])) {
                $scope.accountDetail[accounts_id] = angular.merge($scope.accountDetail[accounts_id], $scope.param);
            } else {$scope.accountDetail[accounts_id] = angular.copy($scope.param);}
            $scope.accountDetail[accounts_id].add_datetime = $scope.getDay('yyyy-MM-dd HH:mm:ss');
            $scope.accountDetail[accounts_id].accounts_id = accounts_id;
            $scope.accountDetail[accounts_id].ba_id = result.data.item.ba_id;
            if(result.data.item.business_day != '') $scope.accountDetail[accounts_id].business_day = result.data.item.business_day;
            $scope.accountsList[booking_number] = $scope.accountDetail;
        });
    };
    ////账务编辑////////////////////////////////
    $scope.param.ba_id = '';
    $scope.editAccounts = function(accounts) {
        var title = '账务编辑';
        var type = '收款';
        var param = angular.copy(accounts);
        $scope.param.item_id = param.item_id+'';
        $scope.param.ba_id   = param.ba_id;
        $scope.param.money   = param.money;
        $scope.payment_id    = param.payment_id;$scope.payment_name  = param.payment_name;
        $scope.payment_father_id = param.payment_father_id;
        if($scope.payment_id == '11') {
            $scope.param.credit_authorized_number = param.credit_authorized_number;
            $scope.param.credit_card_number  = param.credit_card_number;
            $scope.param.credit_authorized_days = param.credit_authorized_days;
            console.log($scope.param);
        }
        
        if(accounts.accounts_type == 'refund') type = '退款';
        if(accounts.accounts_type == 'pre-authorization') type = '预授权';
        asideAccounts = $aside({scope : $scope, title: title+'-'+type, placement:'left',animation:'am-fade-and-slide-left',backdrop:"static",container:'#MainController', templateUrl: 'resource/views/Booking/Restaurant/tableBookExtend.html?'+__VERSION,show: false});
        asideAccounts.$promise.then(function() {
			asideAccounts.show();
			$(document).ready(function(){
			});
		});
	};
	////消费  消费编辑///////////////////////////////////
	$scope.consume_title = '选择消费类别';
    $scope.selectConsume = function(consume) {
        if(angular.isDefined(consume)) {
            $scope.thisConsume = consume;
            $scope.consume_code = consume.consume_code;
            $scope.consume_title = consume.consume_title;
            $scope.channel_consume_id = consume.channel_consume_id;
            $scope.channel_consume_father_id =  consume.channel_consume_father_id;
            $('#payment_ul').next().hide();
        }
    }
    //$scope.asideConsume = '';
    $scope.restaurantConsume = function(consume) {
		$scope.bookExtend = 'consume';
        $scope.thisConsume = angular.copy(consume);
        var title = '消费';
        $scope.asideConsume = $aside({scope : $scope, title: title, placement:'left',animation:'am-fade-and-slide-left',backdrop:"static",container:'#MainController', templateUrl: 'resource/views/Booking/Restaurant/tableBookExtend.html?'+__VERSION,show: false});
        $scope.asideConsume.$promise.then(function() {
			$scope.asideConsume.show();
			$(document).ready(function(){
			});
		});
	};
    $scope.revokesOperations = function(revokesParam, type) {//撤销消费
        var message = '';$scope.param = {};
        if(type == 'consume') {
            message = '您确定要撤销消费记录？';
            $scope.param.c_id = revokesParam.c_id;
            $scope.param.consume_title = revokesParam.consume_title;
        }
        if(type == 'account') {
            message = '您确定要撤销消费记录？';
            $scope.param.ba_id = revokesParam.ba_id;
            $scope.param.payment_name = revokesParam.payment_name;
            $scope.param.accounts_type = revokesParam.accounts_type;
        }
        if(type == 'borrow') {
            message = '您确定要操作归还物品并交还押金吗？';
            $scope.param.bb_id = revokesParam.bb_id;
            $scope.param.borrowing_name = revokesParam.borrowing_name;
            $scope.param.borrowing_num = revokesParam.borrowing_num;
        }
        $scope.param.revokes = type;
        $scope.param.item_name = revokesParam.item_name;
        $scope.param.business_day = revokesParam.business_day;
        $scope.param.booking_number = revokesParam.booking_number;
        $scope.confirm({'content':message,'callback': close});
        function close(consume) {
            $scope.beginLoading =! $scope.beginLoading;
            $httpService.header('method', 'revokesOperations');
            $httpService.post('app.do?'+param, $scope, function(result) {
                $scope.beginLoading =! $scope.beginLoading;
                $httpService.deleteHeader('method');
                if (result.data.success == '0') {
                    var message = $scope.getErrorByCode(result.data.code);
                    //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                    return;//错误返回
                } else {
                    $scope.successAlert.startProgressBar();
                    if(type == 'borrow') {revokesParam.borrowing_return = 1;
                    } else {revokesParam.valid = '0';}
                    $scope.param = {};
                }
            });
        }
	};
	$scope.changeBookTable = function(table, detail_id) {
		$scope.bookExtend = 'changeTable';$scope.bulidingTable = '';
        var title = '换桌';
        $scope.asideConsume = $aside({scope : $scope, title: title, placement:'left',animation:'am-fade-and-slide-left',backdrop:"static",container:'#MainController', templateUrl: 'resource/views/Booking/Restaurant/tableBookExtend.html?'+__VERSION,show: false});
        $scope.asideConsume.$promise.then(function() {
			$scope.asideConsume.show();
			$(document).ready(function(){
			});
		});
	}
//************//打印
    $scope.printBill = function(print) {
        
    };
});