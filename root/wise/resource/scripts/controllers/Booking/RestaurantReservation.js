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
	$scope.param.booking_type = 'meal_dine_in';
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
                $scope.param["channel_id"] = $scope.thisChannel_id;
                $scope.param["channel_father_id"] = $scope.channel_father_id;
                //$scope.defaultHotel = $scope.thisChannel[$scope.thisChannel_id]["channel_name"];
				//设置客源市场  
            	$scope.selectCustomerMarket($scope.marketList[1].children[2], false);
				$('#customer_ul').mouseover(function(e) {$('#customer_ul').next().show();});
				$scope.bookList          = result.data.item.bookList;//预订列表
				$scope.roomList          = result.data.item.roomList;//客房列表
				//时间
                var _thisDay = result.data.item.in_date;
                var _thisTime = $filter('date')($scope._baseDateTime(), 'HH:mm');
                $scope.param["check_in"] = _thisDay;
                $('.check_in').val(_thisDay);
                $scope.param["in_time"] = _thisDay+'T14:00:00.000Z';$scope.param["out_time"] = _thisDay+'T12:00:00.000Z';
				$('.check_date').daterangepicker({singleDatePicker: true, "autoApply": true,"startDate": _thisDay,"locale":{"format" : 'YYYY-MM-DD hh:mm'}
				}, function(start, label) {
					var check_in = start.format('YYYY-MM-DD');
					$scope.param.check_in = check_in;
					$('.check_in').val(check_in);
				});
            });
        });	
    }
	//更换餐厅
	$scope.selectChannel = function(channel_id) {
        $scope.param["channel_father_id"] = $rootScope.employeeChannel[channel_id].channel_father_id;
    };
	//选择客人市场
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