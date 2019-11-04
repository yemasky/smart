//单个channel_id 的数据 并无多个，如需切换，则重新获取数据
app.controller('RestaurantInventoryController', function($rootScope, $scope, $httpService, $location, $translate, $aside, $ocLazyLoad, $alert, $filter, $modal) {
    $scope.param = {};
    $ocLazyLoad.load([$scope._resource + "vendor/modules/angular-ui-select/select.min.css",
                      $scope._resource + "vendor/modules/angular-ui-select/select.min.js",
                      $scope._resource + "vendor/print/css/print-preview.css",
                      $scope._resource + "vendor/print/jquery.print-preview.js"]);
    //初始化数据
    //选择入住房
    var selectLayoutRoom = {},arrayRoom = {},liveLayoutRoom = {},billAccount={};
    $scope.selectLayoutRoom = {};
    var layoutRoomList = {};//按房型选择房间 全部房型房间
    $scope.bookAta = '0';//预抵人数
    $scope.dueOut = '0';//预离人数
	//选择客源市场
    $scope.market_name = '散客步入';$scope.market_id = '2';$scope.customer_name = '预订人';
	//获取数据
	var _channel = $scope.$stateParams.channel;
	var _view = $scope.$stateParams.view,common = '';
	var param = 'channel='+_channel+'&view='+_view;
    beginRoomStatus();//开始执行
    function beginRoomStatus() {
        $scope.loading.show();
        $httpService.post('app.do?'+param, $scope, function(result){
            $scope.loading.percent();
            if(result.data.success == '0') {
                var message = $scope.getErrorByCode(result.data.code);
                //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                return;//错误返回
            }
            common = result.data.common;
            $scope.setCommonSetting(common);
            $scope.setThisChannel('Hotel');//酒店频道
            $(document).ready(function(){
                $scope.param["channel_id"] = $rootScope.defaultChannel["channel_id"];
                $scope.param["channel_father_id"] = $rootScope.defaultChannel["channel_father_id"];
                $scope.defaultHotel = $rootScope.defaultChannel["channel_name"];
            });
            $scope.bookList          = result.data.item.bookList;//预订列表
            $scope.roomList          = result.data.item.roomList;//客房列表
            
            //时间
            $(document).ready(function(){
                var _thisDay = result.data.item.in_date;
                var _thisTime = $filter('date')($scope._baseDateTime(), 'HH:mm');
                var _nextDay = result.data.item.out_date;
                $scope.param["check_in"] = _thisDay;$scope.param["check_out"] = _nextDay;
                $('.check_in').val(_thisDay);$('.check_out').val(_nextDay);
                $scope.param["in_time"] = _thisDay+'T14:00:00.000Z';$scope.param["out_time"] = _thisDay+'T12:00:00.000Z';
                //
            });
        });	
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
    
});