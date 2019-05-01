/////
app.controller('RoomStatusController', function($rootScope, $scope, $httpService, $location, $translate, $aside, $ocLazyLoad, $alert, $filter, $modal) {
    $scope.param = {};
    $ocLazyLoad.load([$scope._resource + "vendor/libs/daterangepicker.css",$scope._resource + "styles/booking.css",
        $scope._resource + "vendor/modules/angular-ui-select/select.min.css"]);
    $ocLazyLoad.load([$scope._resource + "vendor/modules/angular-ui-select/select.min.js"]);
    //初始化数据
    //选择入住房
    var selectLayoutRoom = {};$scope.selectLayoutRoom = {};
    var allLayoutRoom = {};
    var liveLayoutRoom = {};
    //按房型选择房间 全部房型房间
    var layoutRoomList = {};
    $scope.bookAta = '0';//预抵人数
    $scope.dueOut = '0';//预离人数
	//获取数据
	var _channel = $scope.$stateParams.channel;
	var _view = $scope.$stateParams.view,common = '';
	var param = 'channel='+_channel+'&view='+_view;
	var loading = $alert({content: 'Loading... 90%', placement: 'top', type: 'info', templateUrl: '/loading.html', show: true});
	$httpService.post('/app.do?'+param, $scope, function(result){
		loading.hide();
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
        $scope.layoutList        = result.data.item.layoutList;//房型列表
		$scope.layoutRoom        = result.data.item.layoutRoom;//房型房间对应关系
        $scope.consumeList       = result.data.item.consumeList;//消费
        $scope.guestLiveInList   = result.data.item.guestLiveInList;//入住客人
		$scope.bookingDetailRoom = result.data.item.bookingDetailRoom;//预订详情
        $scope.bookRoomStatus    =  {}; $scope.roomDetailList    =  {};$scope.roomLiveIn    =  {};
        if($scope.roomList != '') {
			//按当日计算预抵预离 过期忽略
			var thisDay = $filter('date')($scope._baseDateTime(), 'yyyy-MM-dd');
			var check_inRoom = {},check_outRoom = {},roomDetailList = {};
            var bookAta = 0,dueOut = 0;
			if($scope.bookingDetailRoom != '') {
                var thisDayTimestamp = Date.parse(new Date(thisDay)); 
				for(var i in $scope.bookingDetailRoom) {
					var detail = $scope.bookingDetailRoom[i];
					if(detail.check_in.substr(0, 10) == thisDay) {
						check_inRoom[detail.item_id] = detail; 
                        bookAta++;
					}
					if(detail.check_out.substr(0, 10) == thisDay) {
						check_outRoom[detail.item_id] = detail;
                        dueOut++;
					}
                    var checkOutTimestamp = Date.parse(new Date(detail.check_out)); 
                    if(checkOutTimestamp <= thisDayTimestamp) {
                        check_outRoom[detail.item_id] = detail;
                        dueOut++;
                    }
					if(!angular.isDefined(roomDetailList[detail.booking_number])) {
						roomDetailList[detail.booking_number] = {};
					}
					roomDetailList[detail.booking_number][detail.booking_detail_id] = detail;
				}
                $scope.bookAta = bookAta;
                $scope.dueOut = dueOut;
			}
			//入住客人
            var roomLiveIn = {};
            if($scope.guestLiveInList != '') {
                for(var number in $scope.guestLiveInList) {
                    for(var booking_detail_id in $scope.guestLiveInList[number]) {
                        var roomi = 0;
                        for(var i in $scope.guestLiveInList[number][booking_detail_id]) {
                            var LiveIn = $scope.guestLiveInList[number][booking_detail_id][i];
                            if(angular.isUndefined(roomLiveIn[LiveIn.item_id])) roomLiveIn[LiveIn.item_id] = {};
                            roomLiveIn[LiveIn.item_id][roomi] = LiveIn;roomi++;
                        }
                        //var roomLiveIn = $scope.bookingDetailRoom[number][room_id];
                    }
                }
            }
            $scope.roomLiveIn = roomLiveIn;
            $scope.roomDetailList = roomDetailList;
            //房间状态
			var bookRoomStatus = {};
		    for(var building in $scope.roomList) {
			    for(var floor in $scope.roomList[building]) {
				    var floorRoom = $scope.roomList[building][floor];
					for(var i in floorRoom) {
						var room = floorRoom[i];
						bookRoomStatus[room.item_id] = room;
						//glyphicon-log-in 预抵 glyphicon-log-out 预离 
                        var roomStatus = '<a class="ui-icon glyphicon glyphicon-bed bg-info" title="空净 &#8226; 预订"></a>';//空净
                        if(room.status == 'live_in') {//在住
                            roomStatus = '<a class="ui-icon glyphicon glyphicon-user bg-inverse" title="住净 &#8226; 查看订单"></a>';//住净
                            if(room.clean == 'dirty')
                                roomStatus = '<a class="ui-icon glyphicon glyphicon-user bg-danger" title="住脏 &#8226; 查看订单"></a>';//住脏
                        } else {
                            if(room.clean == 'dirty')
                                roomStatus = '<a class="ui-icon glyphicon glyphicon-bed bg-dark" title="空脏"></i>';//空脏
                        }
                        //附加
				   		if(angular.isDefined(check_outRoom[room.item_id])) {//预离
                            roomStatus = roomStatus + '<a class="fas fa-sign-out-alt text-warning" title="预离 &#8226; 查看订单"></a>';
                        }
						if(angular.isDefined(check_inRoom[room.item_id])) {//预抵
                            roomStatus = '<a class="fas fa-sign-in-alt text-success" title="预抵 &#8226; 查看订单"></a>'+roomStatus;
                        }
                        bookRoomStatus[room.item_id]['roomStatus'] = roomStatus;
                        if(!angular.isDefined(layoutRoomList[$scope.layoutRoom[room.item_id].category_item_id])) {
                            layoutRoomList[$scope.layoutRoom[room.item_id].category_item_id] = {};
                        }
                        layoutRoomList[$scope.layoutRoom[room.item_id].category_item_id][room.item_id] = room;
					}
			   }
		    }
			$scope.bookRoomStatus = bookRoomStatus;
		}
	});
	//预订编辑开始
    $scope.layoutSelectRoom = {};
    $scope.actionEdit = '订单管理';
    $scope.actionConsume = '消费项';
    $scope.actionAccounts = '账务项';
    $scope.actionEditLog = '操作日志';
    $scope.bookDetail = {};$scope.roomDetail = {};
    var asideEditRoomBook = $aside({scope : $scope, title: $scope.action_nav_name, placement:'top',animation:'am-fade-and-slide-top',backdrop:"static",container:'body', templateUrl: '/resource/views/Booking/Room/Edit.html',show: false});
	$scope.editRoomBook = function(detailBookRoom, tab) {
        $scope.layoutSelectRoom = layoutRoomList[detailBookRoom.item_category_id];
		$scope.param["valid"] = "1";
		$scope.activeRoomBookEditTab = tab;
		$scope.roomDetail = $scope.roomDetailList[detailBookRoom.booking_number];//单个订单下面得房间
        $scope.bookDetail = $scope.bookList[detailBookRoom.booking_number];//订单详情
        asideEditRoomBook = $aside({scope : $scope, title: $scope.action_nav_name, placement:'top',animation:'am-fade-and-slide-top',backdrop:"static",container:'body', templateUrl: '/resource/views/Booking/Room/Edit.html',show: false});
		asideEditRoomBook.$promise.then(function() {
			asideEditRoomBook.show();
			$(document).ready(function(){
			});
		});
	};
	$scope.roomStatusBook = function(detailBookRoom) {
        console.log(detailBookRoom);
        $scope.editRoomBook(detailBookRoom, 1);
    }
    //
    var editBookRoomModal = $modal({scope:$scope,templateUrl:'/resource/views/Booking/Room/EditRoom.html',show: false});
    // Show when some event occurs (use $promise property to ensure the template has been loaded)
    $scope.showEditBookRoomModal = function(rDetail, tab) {
        $scope.activeRoomBookTab = tab;
        $scope.roomDetailEdit = rDetail;
        editBookRoomModal = $modal({scope:$scope,templateUrl:'/resource/views/Booking/Room/EditRoom.html',show: false});
        editBookRoomModal.$promise.then(editBookRoomModal.show);
    };
    $scope.saveEditRoomForm = function (roomDetailEdit) {console.log(roomDetailEdit);
        $httpService.header('method', 'editBookRoom');
        $scope.beginLoading =! $scope.beginLoading;
        $scope.param.detail_id = roomDetailEdit.detail_id;
        $httpService.post('/app.do?'+param, $scope, function(result) {
            $scope.beginLoading =! $scope.beginLoading;
            $httpService.deleteHeader('method');
            if (result.data.success == '0') {
                var message = $scope.getErrorByCode(result.data.code);
                //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                return;//错误返回
            }
            roomDetailEdit.item_id = $scope.param.item_room;
            roomDetailEdit.item_name = $scope.param.item_room_name;
            $scope.roomDetail.item_id = $scope.param.item_room;
            $scope.roomDetail.item_name = $scope.param.item_room_name;
            $scope.roomDetailList[roomDetailEdit.booking_number] = $scope.roomDetail;
            var message = $scope.getErrorByCode(result.data.code);
            editBookRoomModal.$promise.then(editBookRoomModal.hide);
            var myAlert = $alert({title: 'Success', content: message, placement: 'top-right', duration: 3, type: 'success', show: true});

        });
    };
    $scope.setEditItemRoomName = function(item_name) {
        $scope.param.item_room_name = item_name;
    }
    //保存入住客人
    var addGuestLiveInModal = $modal({scope:$scope,templateUrl:'/resource/views/Booking/Room/addGuestLiveIn.html',show: false});
    $scope.addGuestLiveIn = function(liveInGuest) {
        addGuestLiveInModal = $modal({scope:$scope,templateUrl:'/resource/views/Booking/Room/addGuestLiveIn.html',show: false});
        addGuestLiveInModal.$promise.then(addGuestLiveInModal.show);
        if(liveInGuest != '') {console.log(liveInGuest);
            $(document).ready(function(){
                for (var key in liveInGuest) {
                    if(key.substr(0,1) == '$') continue;
                    if($('#live_in_'+key)) {
                        $('#live_in_'+key).val(liveInGuest[key]);
                    }
                }
            });
        }
    };
    $scope.saveAddGuestLiveIn = function() {
        $httpService.header('method', 'saveGuestLiveIn');
        $scope.beginLoading =! $scope.beginLoading;
        $scope.param.book_id = $scope.bookDetail.book_id;
        $httpService.post('/app.do?'+param, $scope, function(result) {
            $scope.beginLoading =! $scope.beginLoading;
            $httpService.deleteHeader('method');
            if (result.data.success == '0') {
                var message = $scope.getErrorByCode(result.data.code);
                //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                return;//错误返回
            }
            var message = $scope.getErrorByCode(result.data.code);
            editBookRoomModal.$promise.then(addGuestLiveInModal.hide);
            var myAlert = $alert({title: 'Success', content: message, placement: 'top-right', duration: 3, type: 'success', show: true});
            console.log($scope.roomDetail);
            var booking_detail_id=$scope.param.booking_detail_id;
            var booking_number=$scope.bookDetail.booking_number;
            if(angular.isUndefined($scope.guestLiveInList[booking_number]))
                $scope.guestLiveInList[booking_number] = {};
            if(angular.isUndefined($scope.guestLiveInList[booking_number][booking_detail_id]))
                $scope.guestLiveInList[booking_number][booking_detail_id] = {};
            var length = $scope.guestLiveInList[booking_number][booking_detail_id].length;
            $scope.guestLiveInList[booking_number][booking_detail_id][length] = $scope.param;
        });
    };
    $scope.setLiveInItemName = function() {
        $scope.param.item_name = $('#live_in_item_id').find('option:selected').text();
        $scope.param.detail_id = $('#live_in_item_id').find('option:selected').attr('detail_id');
        $scope.param.booking_detail_id = $('#live_in_item_id').find('option:selected').attr('booking_detail_id');
    }
    //读取身份证
    $scope.readGuestIdCard = function() {

    }
    //begin/////////////////////////////////////////远期房态//////////////////
    
    //end//////////////////////////////////////////远期房态///////////////////
	$httpService.deleteHeader('refresh');
});