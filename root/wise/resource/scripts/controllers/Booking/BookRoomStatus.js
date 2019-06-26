
app.directive("showBookroomPrice", function($document){
  return{
    restrict: "A",
	require: 'ngModel',
    link: function($scope, $element, $attr, $ngModel){
        var show = $attr.showBookroomPrice;
        $element.on('mouseout', function(){
            if(show == 'close') {
                $element.find('a').removeClass('fa fa-angle-double-down');
            } else {
                $element.find('a').removeClass('fa fa-angle-up');
            }
        });
        $element.on('mouseover', function(){
            if(show == 'close') {
                $element.find('a').addClass('fa fa-angle-double-down');
            } else {
                $element.find('a').addClass('fa fa-angle-up');
            }
        });
        $element.find('a').on("click",function(){
            if(show == 'close') {
            	var priceTable = '<table class="table no-margin table-condensed"><tbody>';
                var priceTr = priceDate = '<tr><td></td><td></td>';
            	for(var i in $ngModel.$modelValue) {
            		var price = $ngModel.$modelValue[i];
					priceDate += '<td class="w-xs">'+price.business_day + '</td>';
                    priceTr += '<td>' + price.consume_price+'</td>';
				}
				priceTable += priceDate + '<td></td></tr>'+priceTr+'<td></td></tr></tbody></table>';
                $element.parent().after('<tr><td colspan="9" class="no-padding panel">'+priceTable+'</td></tr>');
                $attr.showBookroomPrice = show = 'on';
                $element.find('a').removeClass('fa fa-angle-double-down');
                $element.find('a').addClass('fa fa-angle-up');
            } else {
                $element.parent().next().remove();
                $attr.showBookroomPrice = show = 'close';
                $element.find('a').removeClass('fa fa-angle-up');
                $element.find('a').addClass('fa fa-angle-double-down');
            }
        })
    }
  }
});
app.controller('RoomStatusController', function($rootScope, $scope, $httpService, $location, $translate, $aside, $ocLazyLoad, $alert, $filter, $modal) {
    $scope.param = {};
    $ocLazyLoad.load([$scope._resource + "styles/booking.css"]);
    //初始化数据
    //选择入住房
    var selectLayoutRoom = {};$scope.selectLayoutRoom = {};
    var arrayRoom = {};
    var liveLayoutRoom = {};
    //按房型选择房间 全部房型房间
    var layoutRoomList = {};
    $scope.bookAta = '0';//预抵人数
    $scope.dueOut = '0';//预离人数
	//选择客源市场
    $scope.market_name = '散客步入';$scope.market_id = '2';$scope.customer_name = '预订人';
	//获取数据
	var _channel = $scope.$stateParams.channel;
	var _view = $scope.$stateParams.view,common = '';
	var param = 'channel='+_channel+'&view='+_view;
	$scope.loading.show();
	$httpService.post('/app.do?'+param, $scope, function(result){
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
        $scope.layoutList        = result.data.item.layoutList;//房型列表
		$scope.layoutRoom        = result.data.item.layoutRoom;//房型房间对应关系
        $scope.consumeList       = result.data.item.consumeList;//消费
        $scope.accountsList      = result.data.item.accountsList;//账户信息
        $scope.guestLiveInList   = result.data.item.guestLiveInList;//入住客人
        $scope.paymentTypeList   = result.data.item.paymentTypeList;//支付方式
		$scope.bookingDetailRoom = result.data.item.bookingDetailRoom;//预订详情
        $scope.bookingSearchList = result.data.item.bookingDetailRoom;//所有订单
		$scope.marketList        = result.data.item.marketList;
        var channelBorrowing     = result.data.item.channelBorrowing;
        var channelConsume       = result.data.item.channelConsumeList;
        $scope.bookRoomStatus    =  {}; $scope.roomDetailList = {};$scope.roomLiveIn = {};$scope.check_outRoom = {};
        if($scope.roomList != '') {
			//按当日计算预抵预离 过期忽略
			var thisDay = $scope.getDay();
			var check_inRoom = {},check_outRoom = {}, live_inRoom = {},roomDetailList = {};
            var bookAta = 0,dueOut = 0;
			if($scope.bookingDetailRoom != '') {
                var thisDayTimestamp = Date.parse(new Date(thisDay)); 
				for(var detail_id in $scope.bookingDetailRoom) {
					var detail = $scope.bookingDetailRoom[detail_id];
                    live_inRoom[detail.item_id] = detail;
                    if(detail.check_in == thisDay && detail.booking_detail_status == '0') {
						check_inRoom[detail.item_id] = detail; 
                        bookAta++;//预抵人数
					}
					/*if(detail.check_out.substr(0, 10) == thisDay) {
						check_outRoom[detail.item_id] = detail;
                        dueOut++;
					}*/
                    var checkOutTimestamp = Date.parse(new Date(detail.check_out)); 
                    if(checkOutTimestamp <= thisDayTimestamp) {
                        check_outRoom[detail.item_id] = detail;
                        dueOut++;//预离人数
                    }
					if(!angular.isDefined(roomDetailList[detail.booking_number])) {
						roomDetailList[detail.booking_number] = {};
					}
					roomDetailList[detail.booking_number][detail.booking_detail_id] = detail;
				}
                $scope.bookAta = bookAta;
                $scope.dueOut = dueOut;
                $scope.check_outRoom = check_outRoom;$scope.check_inRoom = check_inRoom;
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
                    }
                }
            }
            $scope.roomLiveIn = roomLiveIn;
            $scope.roomDetailList = roomDetailList;
            //消费、账务计算
			var bookConsume = {}, bookAccount = {}, bookBalance = {}, bookConsumePrice = {};
			if($scope.consumeList != '') {//消费
				for(var booking_number in $scope.consumeList) {
                    bookConsume[booking_number] = 0; bookAccount[booking_number] = 0; bookConsumePrice[booking_number] = {};
					for(var detail_id in $scope.consumeList[booking_number]) {
                        bookConsumePrice[booking_number][detail_id] = 0;
						for(var i in $scope.consumeList[booking_number][detail_id]) {
							var consume = $scope.consumeList[booking_number][detail_id][i];
							bookConsume[booking_number] = $scope.arithmetic(consume.consume_price_total, '+', bookConsume[booking_number]);
                            bookConsumePrice[booking_number][detail_id] = $scope.arithmetic(consume.consume_price_total, '+',bookConsumePrice[booking_number][detail_id]);
						}
					}
				}				
			}
            $scope.bookConsumePrice = bookConsumePrice;
			if($scope.accountsList != '') {//账务
				for(var booking_number in $scope.accountsList) {
					for(var i in $scope.accountsList[booking_number]) {
						var account = $scope.accountsList[booking_number][i];
						var symbol = '+';
                        if(account.accounts_type == 'refund') symbol = '-';
                        if(account.accounts_type == 'hanging') continue;//挂账不算金钱
						bookAccount[booking_number] = $scope.arithmetic(bookAccount[booking_number], symbol, account.money);
					}
				}				
			}
            //房间状态
			var bookRoomStatus = {};
		    for(var building in $scope.roomList) {
			    for(var floor in $scope.roomList[building]) {
				    var floorRoom = $scope.roomList[building][floor];
					for(var i in floorRoom) {
						var room = floorRoom[i];
                        arrayRoom[room.item_id] = room;
                        room['detail_id'] = 0, room.roomAccount = 0;
						if(angular.isDefined(live_inRoom[room.item_id])){
                            room['detail_id'] = live_inRoom[room.item_id]['booking_detail_id'];
                            if(angular.isDefined(bookConsume[live_inRoom[room.item_id].booking_number])) {
                                var booking_number = live_inRoom[room.item_id].booking_number;
                                room.roomAccount = $scope.arithmetic(bookAccount[booking_number], '-', bookConsume[booking_number], 2);
                            }
                        }
                        bookRoomStatus[room.item_id] = room;
                        bookRoomStatus[room.item_id].check_in = '',bookRoomStatus[room.item_id].check_out = '';
                        if(angular.isDefined(live_inRoom[room.item_id])) {
                            bookRoomStatus[room.item_id].check_in = live_inRoom[room.item_id].check_in;
                            bookRoomStatus[room.item_id].check_out = live_inRoom[room.item_id].check_out;
                        }
						//glyphicon-log-in 预抵 glyphicon-log-out 预离 
                        var roomStatus = '<a class="ui-icon glyphicon glyphicon-bed bg-info" title="空净 &#8226; 预订"></a>';//空净
                        if(room.status == 'live_in') {//在住
                            roomStatus = '<a class="ui-icon glyphicon glyphicon-user bg-inverse" title="住净 &#8226; 查看订单"></a>';//住净
                            if(room.clean == 'dirty') roomStatus = '<a class="ui-icon glyphicon glyphicon-user bg-danger" title="住脏 &#8226; 查看订单"></a>';//住脏
                        } else {
                            if(room.clean == 'dirty') roomStatus = '<a class="ui-icon glyphicon glyphicon-bed bg-dark" title="空脏"></i>';//空脏
                        }
                        //锁房 维修房
                        if(room.lock == 'lock') roomStatus ='<a class="fas fa-lock text-warning" title="锁房"></a> ';//锁房
						if(room.lock == 'repair') roomStatus = '<a class="fas fa-tools text-warning" title="维修房"></a>';//维修房
                        //附加
				   		if(angular.isDefined(check_outRoom[room.item_id])) {//预离
                            roomStatus = roomStatus + '<a class="fas fa-sign-out-alt text-warning" title="预离 &#8226; 查看订单"></a>';
                        }
						if(angular.isDefined(check_inRoom[room.item_id]) && room.status != 'live_in') {//预抵
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
		//借物
        var thisChannelBorrowing = {};
        if(channelBorrowing != '') {
           for(var i in channelBorrowing) {
               var borrowing = channelBorrowing[i], tag = channelBorrowing[i].borrowing_tag;
               var borrowing_id = borrowing.borrowing_id;
               if(angular.isUndefined(thisChannelBorrowing[tag])) {
                   thisChannelBorrowing[tag] = {};
                   thisChannelBorrowing[tag]['borrowing_tag'] = tag;
                   thisChannelBorrowing[tag]['children'] = {};
               }
               thisChannelBorrowing[tag]['children'][borrowing_id] = borrowing;
           }
        }
        $scope.thisChannelBorrowing = thisChannelBorrowing;
        //时间
		$(document).ready(function(){
			var _thisDay = result.data.item.in_date;
			var _thisTime = $filter('date')($scope._baseDateTime(), 'HH:mm');
			var _nextDay = result.data.item.out_date;
			$scope.param["check_in"] = _thisDay;$scope.param["check_out"] = _nextDay;
			$('.check_in').val(_thisDay);$('.check_out').val(_nextDay);
			$scope.param["in_time"] = _thisDay+'T14:00:00.000Z';$scope.param["out_time"] = _thisDay+'T12:00:00.000Z';
		});
	});	
	//单个预订编辑开始
    $scope.layoutSelectRoom = {};
    $scope.actionEdit = '客房项';
    $scope.actionConsume = '消费项';
    $scope.actionAccounts = '账务项';
    $scope.actionEditLog = '日志项';
    $scope.bookDetail = {};$scope.roomDetail = {};
    var asideEditRoomBook = '';
	$scope.editRoomBook = function(detail, tab) {
		$scope.param["valid"] = "1";
		$scope.activeRoomBookEditTab = tab;
        var booking_number = detail.booking_number;
        if(angular.isUndefined($scope.consumeList[booking_number])) {
            $httpService.header('method', 'getEditRoomBookInfo');
			$scope.param.booking_number = booking_number;
            $httpService.post('/app.do?'+param, $scope, function(result) {
                $httpService.deleteHeader('method');
                if (result.data.success == '0') {
                    var message = $scope.getErrorByCode(result.data.code);
                    //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                    return;//错误返回
                } else {
                    $scope.bookingDetailRoom[booking_number] = result.data.item.detailRoom;//单个订单下面的所有房间
                    if(angular.isUndefined($scope.roomDetailList[booking_number])) {$scope.roomDetailList[booking_number] = {};}
					$scope.roomDetailList[booking_number][detail.booking_detail_id] = detail;
                    $scope.bookList[booking_number] = result.data.item.book[booking_number];//订单详情
                    if(angular.isUndefined($scope.consumeList[booking_number])) {$scope.consumeList[booking_number] = {};}
                    console.log(result.data.item.consume[booking_number]);
                    $scope.consumeList[booking_number] = angular.copy(result.data.item.consume[booking_number]);//消费详情
                    console.log($scope.consumeList[booking_number]);
                    $scope.accountsList[booking_number] = result.data.item.accounts;//付款详情
                    //消费、账务计算
                    if($scope.consumeList[booking_number] != '') {//消费
                        var bookConsume = {}, bookAccount = {}, bookBalance = {}, bookConsumePrice = {};
                        bookConsume[booking_number] = 0; bookAccount[booking_number] = 0; bookConsumePrice[booking_number] = {};
                        for(var detail_id in $scope.consumeList[booking_number]) {
                            bookConsumePrice[booking_number][detail_id] = 0;
                            for(var i in $scope.consumeList[booking_number][detail_id]) {
                                var consume = $scope.consumeList[booking_number][detail_id][i];
                                bookConsume[booking_number] = $scope.arithmetic(consume.consume_price_total, '+', bookConsume[booking_number]);
                                bookConsumePrice[booking_number][detail_id] = $scope.arithmetic(consume.consume_price_total, '+',bookConsumePrice[booking_number][detail_id]);
                            }
                        }
                        $scope.bookConsumePrice[booking_number] = bookConsumePrice[booking_number];
                    }
                    $scope.guestLiveInList[booking_number] = result.data.item.guestLiveIn;//
                    showEditRoomBook();
                }
            })
        } else {showEditRoomBook();}
        function showEditRoomBook() {
            $scope.roomDetail = $scope.roomDetailList[booking_number];//单个订单下面的所有房间
            $scope.bookDetail = $scope.bookList[booking_number];//订单详情
            $scope.consumeDetail = $scope.consumeList[booking_number];//消费详情
            $scope.accountDetail = $scope.accountsList[booking_number];//付款详情

            asideEditRoomBook = $aside({scope : $scope, title: $scope.action_nav_name, placement:'top',animation:'am-fade-and-slide-top',backdrop:"static",container:'body', templateUrl: '/resource/views/Booking/Room/Edit.html',show: false});
            asideEditRoomBook.$promise.then(function() {
                asideEditRoomBook.show();
                $(document).ready(function(){
                });
            });
        }
	};
	var asideBookRoom = '';
	$scope.closeAsideBookRoom = function(bookDetail) {if(asideBookRoom != '') {asideBookRoom.hide();};}
	$scope.roomStatusBook = function(detail_id, room) {
	    if(detail_id == 0) {//预定
			room.item_father_id = $scope.layoutRoom[room.item_id].category_item_id;
            $scope.bookRoom = room;$scope.bookInfo = '';
            var title = '预定 : '+$scope.layoutList[$scope.layoutRoom[room.item_id].category_item_id].item_name +'-'+room.item_name;
			asideBookRoom = $aside({scope : $scope, title: title, placement:'top',animation:'am-fade-and-slide-top',backdrop:"static",container:'#MainController', templateUrl: '/resource/views/Booking/Room/book.html',show: false});
			asideBookRoom.$promise.then(function() {
				asideBookRoom.show();
				$(document).ready(function(){
					
				});
            });
			return;
		}
        $scope.editRoomBook($scope.bookingDetailRoom[detail_id], 1);
    }
    var editBookRoomAside = '';
    // Show when some event occurs (use $promise property to ensure the template has been loaded)
    $scope.showEditBookRoomAside = function(rDetail, tab) {
        $scope.layoutSelectRoom = layoutRoomList[rDetail.item_category_id];
        $scope.activeRoomBookTab = tab;
        $scope.roomDetailEdit = rDetail;
        editBookRoomAside = $aside({scope:$scope,templateUrl:'/resource/views/Booking/Room/EditRoom.html',placement:'left',show: false});
        editBookRoomAside.$promise.then(editBookRoomAside.show);
    };
    $scope.saveEditRoomForm = function (roomDetailEdit) {//单个roomDetail
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
            if($scope.param.item_room_name != '') roomDetailEdit.item_name = $scope.param.item_room_name;
            //$scope.roomDetail.item_id = $scope.param.item_room;
            //if($scope.param.item_room_name != '') $scope.roomDetail.item_name = $scope.param.item_room_name;
            //$scope.roomDetailList[roomDetailEdit.booking_number] = $scope.roomDetail;
            editBookRoomAside.$promise.then(editBookRoomAside.hide);
            $scope.successAlert.startProgressBar();

        });
    };
    $scope.setEditItemRoomName = function(item_name) {
        if(item_name != '') $scope.param.item_room_name = item_name;
    };
	//设置入住客房
	$scope.liveInRoom = function(liveIn){
        $scope.beginLoading =! $scope.beginLoading;
		$httpService.header('method', 'liveInRoom');
		$scope.param.book_id = $scope.bookDetail.book_id;
		$scope.param.liveInType = liveIn.type;
		if(angular.isDefined(liveIn.detail_id)) $scope.param.detail_id = liveIn.detail_id;
        $httpService.post('/app.do?'+param, $scope, function(result) {
            $scope.beginLoading =! $scope.beginLoading;
            $httpService.deleteHeader('method');
            if (result.data.success == '0') {
                var message = $scope.getErrorByCode(result.data.code);
                //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                return;//错误返回
            } else {$scope.successAlert.startProgressBar();}
        });
	};
    //添加入住客人
    var addGuestLiveInAside = $aside({scope:$scope,templateUrl:'/resource/views/Booking/Room/addGuestLiveIn.html',placement:'left',show: false});;
    $scope.addGuestLiveIn = function(liveInGuest, ObjectLiveIn) {
		if(liveInGuest == 'AddBookRoom') {
			$scope.bookInfo = $scope.bookDetail;$scope.bookRoom = '';
			var title = '添加客房 订单号: '+$scope.bookDetail.booking_number;
			asideBookRoom = $aside({scope : $scope, title: title, placement:'top',animation:'am-fade-and-slide-top',backdrop:"static",container:'#MainController', templateUrl: '/resource/views/Booking/Room/book.html',show: false});
			asideBookRoom.$promise.then(function() {
				asideBookRoom.show();
				$(document).ready(function(){

				});
			});
			return;
		}
		if(liveInGuest=='HaveLiveIn'){$scope.confirm({'content':'确定要设置客房全部入住状态吗？','callback':$scope.liveInRoom,'param':{'type':'all'}});return;}//入住全部房间
        if(liveInGuest == 'LiveInOne') {$scope.confirm({'content':'确定要设置客房入住状态吗？','callback':$scope.liveInRoom,'param':{'type':'one','detail_id':ObjectLiveIn.detail_id}});return;}//入住一个房间
        addGuestLiveInAside = $aside({scope:$scope,templateUrl:'/resource/views/Booking/Room/addGuestLiveIn.html',placement:'left',show: false});
        addGuestLiveInAside.$promise.then(addGuestLiveInAside.show);
        $(document).ready(function(){
            $('#saveAddGuestLiveInForm input').val('');
            if(liveInGuest != '') {
				if(liveInGuest == 'EditLiveIn') {
					for (var key in ObjectLiveIn) {
						if(key.substr(0,1) == '$') continue;
						if(key == 'item_id') continue;
						if($('#live_in_'+key)) {$('#live_in_'+key).val(ObjectLiveIn[key]);}
                        $scope.param[key] = ObjectLiveIn[key];
					}
                    $('#live_in_edit_id').val(ObjectLiveIn.l_in_id);
					$scope.param.booking_detail_id = ObjectLiveIn.booking_detail_id;
                    $scope.param.live_in_edit_id = ObjectLiveIn.l_in_id;
				} else if(liveInGuest == 'AddLiveIn') {
					$scope.param.booking_detail_id = ObjectLiveIn.booking_detail_id;
                    //$('#live_in_item_id').val(ObjectLiveIn.item_id);
					$scope.roomDetailEdit = ObjectLiveIn;
				}
            }
        });
    };
    //保存入住客人
    $scope.saveAddGuestLiveIn = function() {
        if(!$scope.setLiveInItemName()){return;};
        $httpService.header('method', 'saveGuestLiveIn');
		var formParam = $.serializeFormat('#saveAddGuestLiveInForm'),item_id = $scope.param.item_id;
        $scope.param = angular.merge(formParam, $scope.param);
        $scope.param.item_id = item_id;
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
            var live_in_id = result.data.item.live_in_id, l_in_id = result.data.item.l_in_id;
            var message = $scope.getErrorByCode(result.data.code);
            addGuestLiveInAside.$promise.then(addGuestLiveInAside.hide);
            $scope.successAlert.startProgressBar();
            var booking_detail_id=$scope.param.booking_detail_id;
            var booking_number=$scope.bookDetail.booking_number;
            if(angular.isUndefined($scope.guestLiveInList[booking_number]))
                $scope.guestLiveInList[booking_number] = {};
            if(angular.isUndefined($scope.guestLiveInList[booking_number][booking_detail_id]))
                $scope.guestLiveInList[booking_number][booking_detail_id] = {};
            var length = $scope.guestLiveInList[booking_number][booking_detail_id].length;
            if(angular.isDefined($scope.param.live_in_edit_id) && $scope.param.live_in_edit_id != '') {
                $scope.param.live_in_edit_id = '';$('#live_in_edit_id').val('');
                $scope.guestLiveInList[booking_number][booking_detail_id][live_in_id] = angular.copy($scope.param);
                $scope.guestLiveInList[booking_number][booking_detail_id][live_in_id].l_in_id = l_in_id;
            } else {
                $scope.guestLiveInList[booking_number][booking_detail_id][live_in_id] = angular.copy($scope.param);
                $scope.guestLiveInList[booking_number][booking_detail_id][live_in_id].l_in_id = l_in_id;
            }
        });
    };
    $scope.setLiveInItemName = function() {
        var rDetail = null;
        for(var detail_id in $scope.roomDetail) {
            rDetail = $scope.roomDetail[detail_id];
            if($scope.param.booking_detail_id == rDetail.booking_detail_id) break; 
            rDetail == null
        }
        if(rDetail == null) {$alert({title: 'Notice', content: '找不到客房！', templateUrl: '/modal-warning.html', show: true, type : 'warning'});return false;};
        $scope.param.item_name = rDetail.item_name;//$('#live_in_item_id').find('option:selected').text();
        $scope.param.item_id = angular.copy(rDetail.item_id);
        $scope.param.detail_id = angular.copy(rDetail.detail_id);//$('#live_in_item_id').find('option:selected').attr('detail_id');
        $scope.param.booking_detail_id = angular.copy(rDetail.booking_detail_id);
        //$('#live_in_item_id').find('option:selected').attr('booking_detail_id');
        return true;
    };
    $scope.selectRoomItem = function($event) {
        $scope.param.item_name = $event.target.text;//$('#').find('option:selected').text();
        $scope.param.item_id = $event.target.value;
        $scope.param.detail_id = $event.target.attributes.detail_id.nodeValue;//$('#').find('option:selected').attr('detail_id');
        $scope.param.booking_detail_id = $event.target.attributes.booking_detail_id.nodeValue;
        return true;
    }
    //读取身份证
    $scope.readGuestIdCard = function() {
        
    };
    //设置room的各种状态
    $scope.statusRoom = {};
    $scope.setRoomStatus = function(room) {
        $scope.statusRoom = room;
    };
    //lock unlock dirty clean repair room_card
    $scope.param.editType = '';var myOtherAside = '';
    $scope.editRoomStatus = function(editType) {
        $scope.param.editType = editType;
        if(editType == 'lock' || editType == 'repair') {
            var bookRoomStatus = $scope.bookRoomStatus[$scope.statusRoom.item_id];
            if(bookRoomStatus.status == 'live_in') {
                $alert({title: 'Notice', content: '在住房不能设置锁房，也不能设置维修。', templateUrl: '/modal-warning.html', show: true});
                return;//在住房不能设置锁房 也不能设置维修
            }
            var title = '锁房';
            if(editType == 'repair') title = '维修房';
            myOtherAside = $aside({scope : $scope, title: title, placement:'left',animation:'am-fade-and-slide-top',backdrop:"static",container:'body', templateUrl: '/resource/views/Booking/Room/EditRoomStatus.html',show: false});
            // Show when some event occurs (use $promise property to ensure the template has been loaded)
            myOtherAside.$promise.then(function() {
                myOtherAside.show();
            })
        } else if(editType != 'room_card') {
			if(editType == 'unlock') {$scope.confirm({'content':'您确定要解锁此房间吗？','callback':$scope.saveRoomStatusEdit});
		    } else if(editType == 'repair_ok') { $scope.confirm({'content':'您确定已修好此房间吗？','callback':$scope.saveRoomStatusEdit});
			} else if(editType == 'empty_room') { $scope.confirm({'content':'您确定要设置此房间空房吗？','callback':$scope.saveRoomStatusEdit});
			} else {$scope.saveRoomStatusEdit();}
        } else {//发放房卡 room_card
            
        }
    };
    $scope.saveRoomStatusEdit = function() {//维修 锁房等房间状态设置
        $scope.beginLoading =! $scope.beginLoading;
        $scope.param.r_id = $scope.layoutRoom[$scope.statusRoom.item_id].r_id;
        $scope.param['begin_datetime'] = $filter("date")( $scope.param['begin_datetime'], "yyyy-MM-dd");
		$scope.param['end_datetime'] = $filter("date")($scope.param['end_datetime'], "yyyy-MM-dd");
        $httpService.header('method', 'saveRoomStatusEdit');
        $httpService.post('/app.do?'+param, $scope, function(result) {
            $scope.beginLoading =! $scope.beginLoading;
            $httpService.deleteHeader('method');
            if (result.data.success == '0') {
                var message = $scope.getErrorByCode(result.data.code);
                //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                return;//错误返回
            }
            $scope.successAlert.startProgressBar();
            if(myOtherAside != '') myOtherAside.hide();
            var editType = $scope.param.editType, bookRoomStatus = $scope.bookRoomStatus[$scope.statusRoom.item_id],roomStatus = bookRoomStatus.roomStatus;
            if(editType == 'lock') {if(bookRoomStatus.status == 'live_in') return;//在住房不能设置锁房 也不能设置维修
                roomStatus ='<a class="fas fa-lock text-warning" title="锁房"></a> ';//锁房
            }
            if(editType == 'repair') {if(bookRoomStatus.status == 'live_in') return;//在住房不能设置锁房 也不能设置维修
                roomStatus = '<a class="fas fa-tools text-warning" title="维修房"></a>';//维修房
            }
            if(editType == 'unlock' || editType == 'repair_ok' || editType == 'empty_room') {
				if(bookRoomStatus.status == 'live_in') return;//在住房不能设置锁房 也不能设置维修
                roomStatus ='<a class="ui-icon glyphicon glyphicon-bed bg-info" title="空净 &#8226; 预订"></a>';//解锁 空净 修好 空净  
            }
            //
            if(editType == 'dirty') {
                if(bookRoomStatus.status == 'live_in') {
                    roomStatus = '<a class="ui-icon glyphicon glyphicon-user bg-danger" title="住脏 &#8226; 查看订单"></a>';//住脏
                } else {roomStatus = '<a class="ui-icon glyphicon glyphicon-bed bg-dark" title="空脏"></i>';//空脏
                }
            }
            if(editType == 'clean') {
                if(bookRoomStatus.status == 'live_in') {
                    roomStatus = '<a class="ui-icon glyphicon glyphicon-user bg-inverse" title="住净 &#8226; 查看订单"></a>';//住净
                } else {roomStatus = '<a class="ui-icon glyphicon glyphicon-bed bg-info" title="空净 &#8226; 预订"></a>';//空净
                }
            }
            //附加
            if(angular.isDefined($scope.check_outRoom[bookRoomStatus.item_id])) {//预离
                roomStatus = roomStatus + '<a class="fas fa-sign-out-alt text-warning" title="预离 &#8226; 查看订单"></a>';
            }
            if(angular.isDefined($scope.check_inRoom[bookRoomStatus.item_id]) && bookRoomStatus.status != 'live_in') {//预抵
                roomStatus = '<a class="fas fa-sign-in-alt text-success" title="预抵 &#8226; 查看订单"></a>'+roomStatus;
            }
            $scope.bookRoomStatus[$scope.statusRoom.item_id].roomStatus = roomStatus;
        });
    };
    //收款
    $scope.payment_name = '选择支付方式';
    var asideAccounts = '';
    $scope.bookingAccounts = function(account, type) {
        var title = '收款', accounts_type = 'receipts';
        if(type == 'refund') {title = '退款';accounts_type = 'refund';};
        if(type == 'hanging') {title = '挂账';accounts_type = 'hanging';};
        if(type == 'edit') title = '修改账款';
        $scope.param.accounts_type = accounts_type;$scope.param.ba_id = '';
        asideAccounts = $aside({scope : $scope, title: title, placement:'left',animation:'am-fade-and-slide-left',backdrop:"static",container:'#MainController', templateUrl: '/resource/views/Booking/Room/Accounts.html',show: false});
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
        $httpService.post('/app.do?'+param, $scope, function(result) {
            $scope.beginLoading =! $scope.beginLoading;
            $scope.param.ba_id = '';//设置编辑为空
            $httpService.deleteHeader('method');
            if (result.data.success == '0') {
                var message = $scope.getErrorByCode(result.data.code);
                //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
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
    //账务编辑
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
        asideAccounts = $aside({scope : $scope, title: title+'-'+type, placement:'left',animation:'am-fade-and-slide-left',backdrop:"static",container:'#MainController', templateUrl: '/resource/views/Booking/Room/Accounts.html',show: false});
        asideAccounts.$promise.then(function() {
			asideAccounts.show();
			$(document).ready(function(){
			});
		});
	};
    //消费编辑
    $scope.editConsume = function(consume) {
        console.log(consume);
        var title = '编辑消费';
        $scope.param = consume;
        $scope.param.item_id = consume.item_id+'';
        $scope.payment_name = consume.payment_name;
        asideAccounts = $aside({scope : $scope, title: title, placement:'left',animation:'am-fade-and-slide-left',backdrop:"static",container:'#MainController', templateUrl: '/resource/views/Booking/Room/Accounts.html',show: false});
        asideAccounts.$promise.then(function() {
			asideAccounts.show();
			$(document).ready(function(){
			});
		});
	};
	//消费
	$scope.bookingConsume = function(consume) {
        var title = '消费';
        var templateUrl = '/resource/views/Booking/Room/Consume.html';
        if(consume == 'borrowing') {
            title = "借物";
            templateUrl = '/resource/views/Booking/Room/Borrowing.html';
        }
		asideConsume = $aside({scope : $scope, title: title, placement:'left',animation:'am-fade-and-slide-left',backdrop:"static",container:'#MainController', templateUrl: templateUrl,show: false});
        asideConsume.$promise.then(function() {
			asideConsume.show();
			$(document).ready(function(){
			});
		});
	};
    $scope.consume_title = '选择消费类别';
    $scope.selectConsume = function(consume) {
        if(angular.isDefined(consume)) {
            $scope.consume_title = consume.consume_title;
            $scope.channel_consume_id = consume.channel_consume_id;
            $scope.channel_consume_father_id =  consume.channel_consume_father_id;
            $('#payment_ul').next().hide();
        }
    }
    //借物
    $scope.borrowing_name = '选择借物';
    $scope.selectBorrowing = function(borrowing) {
        if(angular.isDefined(borrowing)) {
            $scope.borrowing_name = borrowing.borrowing_name;
            $scope.borrowing_id = borrowing.borrowing_id;
            $('#borrowing_ul').next().hide();
        }
    };
    $scope.showBorrowingUL = function() {
        $('#borrowing_ul').next().show();
    }
	//结账退房
	$scope.bookingClose = function(bookDetail, closeType) {
        if(closeType == 'refund' || closeType == 'hanging') {
            var title = '退房';$scope.closeThisBooking = close;
            if(closeType == 'hanging') {title = '挂账退房';};
            var asideBookingClose = $aside({scope : $scope, title: title, placement:'top',animation:'am-fade-and-slide-left',backdrop:"static",container:'#MainController', templateUrl: '/resource/views/Booking/Room/bookClose.html',show: false});
            asideBookingClose.$promise.then(function() {
                asideBookingClose.show();
                $(document).ready(function(){
                });
            });
            return;
        }
        if(closeType == 'escape') {$scope.confirm({'content':'您确定要走结退房？','callback': close});
        } else if(closeType == 'cancel') { $scope.confirm({'content':'您确定要取消此订单吗？','callback':close});}
        function close() {
            $scope.beginLoading =! $scope.beginLoading;
            $httpService.header('method', 'bookingClose');
            $scope.param['booking_number'] = bookDetail.booking_number;
            $scope.param['book_id'] = bookDetail.book_id;
            $scope.param['closeType'] = closeType;
            $httpService.post('/app.do?'+param, $scope, function(result) {
                $scope.beginLoading =! $scope.beginLoading;
                $httpService.deleteHeader('method');
                if (result.data.success == '0') {
                    var message = $scope.getErrorByCode(result.data.code);
                    //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                    return;//错误返回
                } else {
                    $scope.successAlert.startProgressBar();
                }
            });
        }
	};
	////////////////////////////////////////////////night auditor
	$scope.nightAuditorList = '';
	$scope.nightAuditor = function() {
		$httpService.header('method', 'nightAuditor');
		$scope.loading.start();
		$httpService.post('/app.do?'+param, $scope, function(result) {
            $scope.loading.percent();
            $httpService.deleteHeader('method');
            if (result.data.success == '0') {
                var message = $scope.getErrorByCode(result.data.code);
                //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                return;//错误返回
            } else {
				$scope.nightAuditorList = result.data.item.nightAuditorList;
			}
        });
	}
	///////////////////////////////////////////////end night auditor
    $scope.searchBooking = function(condition) {
        if((angular.isDefined($scope.param.condition_date) && angular.isDefined($scope.param.search_date)) || 
           (angular.isDefined($scope.param.condition_key) && angular.isDefined($scope.param.search_value))) {
        } else {
            $alert({title: 'Notice', content: '搜索条件不正确！', templateUrl: '/modal-warning.html', show: true});
            return;//错误返回
        }
        if(angular.isDefined($scope.param.search_date)) $scope.param.search_date = $filter('date')($scope.param.search_date, 'yyyy-MM-dd');
        $httpService.header('method', 'searchBooking');
		$scope.loading.start();
		$httpService.post('/app.do?'+param, $scope, function(result) {
            $scope.loading.percent()
            $httpService.deleteHeader('method');
            if (result.data.success == '0') {
                var message = $scope.getErrorByCode(result.data.code);
                //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                return;//错误返回
            } else {
				$scope.bookingSearchList = result.data.item.bookingSearchList;
			}
        });
    }
    //begin/////////////////////////////////////////远期房态//////////////////
	$scope.roomForwardList = '';$scope.param.eta_date = $scope.getDay('yyyy-MM-dd');
    $scope.roomForcasting =function(getRoomForward) {
		if($scope.roomForwardList == '' || getRoomForward == true) {
            $scope.setForwardCalendar('2019-05-01', '2019-07-30');
			$scope.loading.start();
			$httpService.header('method', 'roomForcasting');
            $scope.param.eta_date = $filter("date")($scope.param.eta_date, 'yyyy-MM-dd');
			$httpService.post('/app.do?'+param, $scope, function(result) {
				$scope.loading.percent()
				$httpService.deleteHeader('method');
				if (result.data.success == '0') {
					var message = $scope.getErrorByCode(result.data.code);
					//$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
					return;//错误返回
				} else {
					$scope.roomForwardList = result.data.item.roomForwardList;
				}
			});
		}
		console.log('Room Forcasting');
	}
    $scope.forwardCalendar = {};$scope.forwardColspan = 4;
    $scope.setForwardCalendar = function(in_date, out_date) {//设置日期
        var check_in = new Date(in_date.replace(/-/g, '/'));
        var check_in_time = check_in.getTime(); 
        var check_out = new Date(out_date.replace(/-/g, '/'));
        var check_out_time = check_out.getTime();
        var forwardCalendar = {}, forwardColspan = 4;
        for(var i = check_in_time; i < check_out_time; i += 86400000) {
            var thisDate = new Date(i);var year = thisDate.getFullYear();
            var month = thisDate.getMonth() - 0 + 1; if(month < 10) month = '0'+month;
            var day = thisDate.getDate() - 0; if(day < 10) day = '0'+day;
            //date_key = 年-月-日 2018-01-01
            var date_key = year+'-'+month+'-'+day;var week = $scope.weekday[thisDate.getDay()];
            if(typeof(forwardCalendar[date_key]) == 'undefined') {
                forwardCalendar[date_key] = {};
            }
            forwardCalendar[date_key]['day'] = day;
            forwardCalendar[date_key]['week'] = week;
            forwardCalendar[date_key]['month'] = month;
            forwardCalendar[date_key]['year'] = year;
            forwardColspan++;
        }
        $scope.forwardCalendar = forwardCalendar;$scope.forwardColspan = forwardColspan;
    };
    $scope.setForwardCalendar('2019-05-01', '2019-07-30');
    //end//////////////////////////////////////////远期房态///////////////////
	$httpService.deleteHeader('refresh');
});