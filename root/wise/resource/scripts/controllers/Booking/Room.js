/////
app.controller('RoomOrderController', function($rootScope, $scope, $httpService, $location, $translate, $aside, $ocLazyLoad, $alert, $filter) {
    var loading = $alert({content: 'Loading... 80%', placement: 'top', type: 'info', templateUrl: '/loading.html', show: true});
    //日历部分
    $ocLazyLoad.load([$scope._resource + "vendor/libs/daterangepicker.css",$scope._resource + "styles/booking.css",
                      $scope._resource + "vendor/modules/angular-ui-select/select.min.css"]);
    $ocLazyLoad.load([$scope._resource + "vendor/modules/angular-ui-select/select.min.js"]);
    $scope.param = {};$scope.booking_room = {};$scope.system_price = {};var priceLayout = {};
    //选择客源市场
    $scope.market_name = '散客步入';$scope.market_id = '2';$scope.customer_name = '预订人';
    var _channel = $scope.$stateParams.channel;$scope.param.mobile_email = '';
    var _view = $scope.$stateParams.view;
    //获取数据
    var param = 'channel='+_channel+'&view='+_view+'&market_id='+$scope.market_id;
    $httpService.post('/app.do?'+param, $scope, function(result){
        loading.hide();
        if(result.data.success == '0') {
            var message = $scope.getErrorByCode(result.data.code);
            $alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
        }
        var common = result.data.common;
        $scope.setCommonSetting(common);
        $scope.setThisChannel('Hotel');
        $(document).ready(function(){
            var channelItemList = result.data.item.arrayChannelItem;
            var layoutRoom = result.data.item.layoutRoom;
            if(angular.isDefined(channelItemList)) $scope.setLayoutList(channelItemList, layoutRoom);
            $scope.marketList = result.data.item.marketList;
            //
            var resultPriceLayout = result.data.item.priceLayout;
            $scope.setPriceLayout(resultPriceLayout);//计算价格类型
            //按时间计算空房
            $scope.layoutRoom = result.data.item.layoutRoom;
            //所有价格类型
            $scope.priceSystemHash = result.data.item.priceSystemHash;
            //设置价格体系市场
            //$scope.setPriceSystemMarket();
            var _thisDay = result.data.item.in_date;
            var _thisTime = $filter('date')($scope._baseDateTime(), 'HH:mm');
            var _nextDay = result.data.item.out_date;
            $scope.param["check_in"] = _thisDay;$scope.param["check_out"] = _nextDay;
            $scope.setBookingCalendar(_thisDay, _nextDay);
            $('.check_in').val(_thisDay);$('.check_out').val(_nextDay);
            $('.check_date').daterangepicker({
                "autoApply": true,"startDate": _thisDay,"endDate": _nextDay,"locale":{"format" : 'YYYY-MM-DD hh:mm'}
            }, function(start, end, label) {
              //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                var check_in = start.format('YYYY-MM-DD'), check_out = end.format('YYYY-MM-DD');
                $scope.param["check_in"] = check_in;$scope.param["check_out"] = check_out;
                $scope.setBookingCalendar(check_in, check_out);
                $('.check_in').val(check_in);$('.check_out').val(check_out);$scope.checkOrderData();
            });
            $('#customer_ul').mouseover(function(e) {$('#customer_ul').next().show();});
            $scope.param["in_time"] = _thisDay+'T14:00:00.000Z';$scope.param["out_time"] = _thisDay+'T12:00:00.000Z';
            //var channel_id = result.data.item.defaultChannel_id;
            $scope.param["channel_id"] = $rootScope.defaultChannel["channel_id"];
            $scope.param["channel_father_id"] = $rootScope.defaultChannel["channel_father_id"];
            $scope.defaultHotel = $rootScope.defaultChannel["channel_name"];
            //设置客源市场 
            $scope.selectCustomerMarket($scope.marketList[1].children[2], false);
        });
    });
    //选择客人市场
    $scope.selectCustomerMarket = function(market, ajax) {
        $scope.marketSystemLayout = {};
        if(angular.isDefined(market)) {
            $scope.market_name = market.market_name;
            $scope.market_id = market.market_id;
            $scope.market_father_id =  market.market_father_id;
            $('#customer_ul').next().hide();
            $scope.customer_name = '预订人';
            if(market.market_father_id == '4') {//判断会员是否正确
                $scope.customer_name = market.market_name;
            }
            $scope.setPriceSystemMarket();
            if(ajax == true) $scope.checkOrderData();
        }
    };
    //设置房型价格
    $scope.setPriceLayout = function (resultPriceLayout) {
        if(resultPriceLayout != '') {
            for(var i in resultPriceLayout) {
                var channel_id = resultPriceLayout[i].channel_id;
                if(typeof(priceLayout[channel_id]) == 'undefined') priceLayout[channel_id] = {};
                //
                var item_id = resultPriceLayout[i].item_id;
                if(typeof(priceLayout[channel_id][item_id]) == 'undefined') priceLayout[channel_id][item_id] = {};
                //
                var system_id = resultPriceLayout[i].price_system_id;
                if(typeof(priceLayout[channel_id][item_id][system_id]) == 'undefined') priceLayout[channel_id][item_id][system_id] = {};
                //
                var price_date = resultPriceLayout[i].layout_price_date;
                priceLayout[channel_id][item_id][system_id][price_date] = resultPriceLayout[i];
            }
        }
    };
    $scope.setLayoutList = function (channelItemList, layoutRoom) {
        var layoutList = {};//房型
        var roomList = {},room_data = {};//房间
        for(var channel_id in channelItemList) {
            var thisItemList = channelItemList[channel_id], thisLayoutRoom = {};
            if(typeof(layoutList[channel_id]) == 'undefined') {layoutList[channel_id] = {};room_data[channel_id] = {};}
            if(typeof(layoutRoom[channel_id]) != 'undefined') thisLayoutRoom = layoutRoom[channel_id];
            for(var i in thisItemList) {
                if(thisItemList[i].channel_config == 'room') {
                    roomList[thisItemList[i]['item_id']] = thisItemList[i];
                }
                if(thisItemList[i].channel_config == "layout") {
                    layoutList[channel_id][i] = thisItemList[i];
                    var num = 0;
                    if(typeof(thisLayoutRoom[thisItemList[i]['item_id']]) != 'undefined') {
                        for(var j in thisLayoutRoom[thisItemList[i]['item_id']]) {
                            num++;
                        }
                        //room_data[channel_id][thisItemList[i]['item_id']] = thisItemList[i]['item_id'];
                    }
                    layoutList[channel_id][i]['room_num'] = num;
                    room_data[channel_id][thisItemList[i]['item_id']] = 0;
                    var select_room_num = [];
                    for(var j = 0; j <= num; j++) {
                        select_room_num[j] = {};select_room_num[j]['room_info'] = j;select_room_num[j]['value'] = j;
                    }
                    layoutList[channel_id][i]['select_room_num'] = select_room_num;
                }
            }
        }
        $scope.layoutList = layoutList;$scope.roomList = roomList;//$scope.booking_room = room_data;
    }
    //显示layout
    $scope.showLayout = function(layout_item_id, show) {
        $scope.layoutShow[layout_item_id] = show;
    }
    //选择市场价格类别
    $scope.setPriceSystemMarket = function() {
        //marketSystemLayout：市场价格类别[市场有什么价格类别按layout排序]
        var priceSystemHash = $scope.priceSystemHash, marketSystemLayout = {}, k = {}, layoutShow = {}, layoutSystemMore = {};
        for(var system_id in priceSystemHash) {//价格类型
            var channel_ids = angular.fromJson(priceSystemHash[system_id].channel_ids);	//价格类型包含的channel
            var layout_item = angular.fromJson(priceSystemHash[system_id].layout_item);	//价格类型包含的item
            var market_ids = angular.fromJson(priceSystemHash[system_id].market_ids);//价格类型包含的market
            for(var market_id in market_ids) {//历遍market
                if(typeof(marketSystemLayout[market_id]) == 'undefined') marketSystemLayout[market_id] = {};
                for(var channel_id in layout_item) {//历遍layout
                    if(typeof(marketSystemLayout[market_id][channel_id]) == 'undefined') marketSystemLayout[market_id][channel_id] = {};
                    for(var layout_item_id in layout_item[channel_id]) {//历遍layout
                        layoutShow[layout_item_id] = 0;
                        var key = market_id + '-' + channel_id + '-' + layout_item_id;
                        var systemKey = system_id + '-' + layout_item_id;
                        if(!angular.isDefined(marketSystemLayout[market_id][channel_id][layout_item_id])) { 
                            marketSystemLayout[market_id][channel_id][layout_item_id] = [];
                            k[key] = 0;
                            layoutSystemMore[layout_item_id] = 0;
                        } else {
                            k[key]++;
                            layoutSystemMore[layout_item_id] = 1;
                        }
                        marketSystemLayout[market_id][channel_id][layout_item_id][k[key]] = priceSystemHash[system_id];
                        //此市场的价格
                    }
                }
            }
        }
        $scope.selectMarketLayoutPrice();//channel_id, layout_item_id, system_id
        $scope.marketSystemLayout = marketSystemLayout;
        $scope.layoutShow = layoutShow;$scope.layoutSystemMore = layoutSystemMore;
    };
    //取出客源市场价格及远期房态
    $scope.checkOrderData = function() {
        if($scope.market_id == '0') {
            $alert({title: 'Error', content: "请选择客源市场！", templateUrl: '/modal-warning.html', show: true});
            return;
        }
        $scope.param["in_time"] = $scope.param["in_time"].substr(11,5);
        $scope.param["out_time"] = $scope.param["out_time"].substr(11,5);
        loading.show();
        var market_id = $scope.market_id;
        var param = 'channel='+_channel+'&market_id='+market_id;
        $httpService.header('method', 'checkOrderData');
        $httpService.post('/app.do?'+param, $scope, function(result){
            loading.hide();$httpService.deleteHeader('checkOrderData');
            if(result.data.success == '0') {
                var message = $scope.getErrorByCode(result.data.code);
                $alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
            } else {
                var resultPriceLayout = result.data.item.priceLayout;
                //$scope.marketChannelLayoutPrice = {};
                $scope.setPriceLayout(resultPriceLayout);
            }
        })
    }
    
    $scope.setBookingCalendar = function(in_date, out_date) {//设置日期
        var check_in = new Date(in_date.replace(/-/g, '/'));
        var check_in_time = check_in.getTime(); 
        var check_out = new Date(out_date.replace(/-/g, '/'));
        var check_out_time = check_out.getTime();
        var bookingCalendar = {}, colspan = 4;
        for(var i = check_in_time; i < check_out_time; i += 86400000) {
            var thisDate = new Date(i);var year = thisDate.getFullYear();
            var month = thisDate.getMonth() - 0 + 1; if(month < 10) month = '0'+month;
            var day = thisDate.getDate() - 0; if(day < 10) day = '0'+day;
            //date_key = 年-月-日 2018-01-01
            var date_key = year+'-'+month+'-'+day;var week = $scope.weekday[thisDate.getDay()];
            if(typeof(bookingCalendar[date_key]) == 'undefined') {
                bookingCalendar[date_key] = {};
            }
            bookingCalendar[date_key]['day'] = day;    bookingCalendar[date_key]['week'] = week;
            bookingCalendar[date_key]['month'] = month;bookingCalendar[date_key]['year'] = year;
            colspan++;
        }
        $scope.bookingCalendar = bookingCalendar;$scope.colspan = colspan;
    };		
    $scope.marketChannelLayoutPrice = {};
    //选择价格体系 
    var thisMarketPrice = {};
    $scope.selectMarketLayoutPrice = function() {//channel_id, layout_item_id, _system_id
        //if(_system_id == '0' || typeof(_system_id) == 'undefined') return;//为0时不做任何操作
        var market_id = $scope.market_id;
        if(typeof(thisMarketPrice[market_id]) == 'undefined') thisMarketPrice[market_id] = {};
        
        var priceSystemHash = $scope.priceSystemHash;
        //var channel_id = 1,layout_item_id = 1;
        for(var _system_id in priceSystemHash) {
            var priceSystem = priceSystemHash[_system_id];
            var channel_ids = priceSystem.channel_ids;
            var layout_item = priceSystem.layout_item;
            if(channel_ids == '' || layout_item == '') continue;
            channel_ids = angular.fromJson(channel_ids);
            layout_item = angular.fromJson(layout_item);
            var channel_formula = priceSystem['formula'] != '' ? angular.fromJson(priceSystem['formula']) : '';
            //if(typeof(priceSystemHash[_system_id]) != 'undefined') {
            var system_type = priceSystem['price_system_type'];
            if(system_type == 'formula') {//公式
                for(var channel_id in channel_ids) {
                    if(typeof(layout_item[channel_id]) == 'undefined') continue;
                    if(typeof(thisMarketPrice[market_id][channel_id]) == 'undefined') thisMarketPrice[market_id][channel_id] = {};
                    for(var layout_item_id in layout_item[channel_id]) {
                        var key = channel_id + '-' + layout_item_id;
                        var system_father_id = priceSystem['price_system_father_id'];
                        var layout_formula = channel_formula[key];
                        if(typeof(thisMarketPrice[market_id][channel_id][layout_item_id]) == 'undefined') thisMarketPrice[market_id][channel_id][layout_item_id] = {};
                        if(typeof(thisMarketPrice[market_id][channel_id][layout_item_id][_system_id]) == 'undefined') {
                            thisMarketPrice[market_id][channel_id][layout_item_id][_system_id] = {};
                        }
                        //如果价格体系在这个公司有设置
                        if(typeof(priceLayout[channel_id]) != 'undefined' && typeof(layout_formula) != 'undefined' && 
                           typeof(priceLayout[channel_id][layout_item_id]) != 'undefined') {//根据公式算价格
                            //重新统计价格
                            var thisPriceLayout = priceLayout[channel_id][layout_item_id][system_father_id];
                            //thisMarketPrice[market_id][channel_id][layout_item_id][_system_id] = thisPriceLayout;[死循环]
                            for(var date in thisPriceLayout) {
                                var thisPrice = thisPriceLayout[date];
                                var year_month = date.substr(0,8);
                                for(var i = 1; i <= 31; i++) {
                                    var day = i < 10 ? '0'+i : i;//date_key = 年-月-日 2018-01-01
                                    var thisDayPrice = isNaN(thisPrice['day_'+day]) ? 0 : thisPrice['day_'+day];
                                    var price = thisDayPrice - 0;
                                    if(typeof(price) != 'undefined' && price > 0) {
                                        if(layout_formula['formula_value'] - 0 > 0)
                                            price = $scope.arithmetic(price, layout_formula['formula'], layout_formula['formula_value'], 2);
                                        if(layout_formula['formula_second_value'] - 0 > 0)
                                            price = $scope.arithmetic(price, layout_formula['formula_second'], layout_formula['formula_second_value'], 2);
                                    } else {
                                        price = '';//未设置价格
                                    }
                                    thisMarketPrice[market_id][channel_id][layout_item_id][_system_id][year_month+day] = price;
                                }
                            }
                        }
                    }
                }
            } else {
                for(var channel_id in channel_ids) {
                    if(typeof(layout_item[channel_id]) == 'undefined') continue;
                    if(typeof(thisMarketPrice[market_id][channel_id]) == 'undefined') thisMarketPrice[market_id][channel_id] = {};
                    for(var layout_item_id in layout_item[channel_id]) {
                        if(typeof(thisMarketPrice[market_id][channel_id][layout_item_id]) == 'undefined') thisMarketPrice[market_id][channel_id][layout_item_id] = {};
                        if(typeof(thisMarketPrice[market_id][channel_id][layout_item_id][_system_id]) == 'undefined') {
                            thisMarketPrice[market_id][channel_id][layout_item_id][_system_id] = {};
                        }
                        if(typeof(priceLayout[channel_id]) != 'undefined' && typeof(priceLayout[channel_id][layout_item_id]) != 'undefined') {
                            var thisPriceLayout = priceLayout[channel_id][layout_item_id][_system_id];
                            for(var date in thisPriceLayout) {
                                var thisPrice = thisPriceLayout[date];
                                var year_month = date.substr(0,8);
                                for(var i = 1; i <= 31; i++) {
                                    var day = i < 10 ? '0'+i : i;//date_key = 年-月-日 2018-01-01
                                    var price = thisPrice['day_'+day];
                                    thisMarketPrice[market_id][channel_id][layout_item_id][_system_id][year_month+day] = price;
                                }
                            }
                        }
                    }
                }
            }
        }
        console.log(thisMarketPrice);
        $scope.marketChannelLayoutPrice = thisMarketPrice;
    }
    $scope.selectThisLayout = function($event) {}
    $scope.selectAllLayout = function($event) {
    };
    $scope.selectChannel = function(channel_id) {
        $scope.param["channel_father_id"] = $rootScope.employeeChannel[channel_id].channel_father_id;
    };
    //开始预订
    $scope.beginBooking = function() {
        if($scope.market_father_id == '4') {//判断会员是否正确
            var mobile_email = $scope.param.mobile_email, member_mobile = '', member_email = '';
            if(mobile_email.indexOf("@") != -1) {
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
            loading.show();
            $httpService.header('method', 'checkMember');
            $checkMember = {};
            $checkMember.param = {};
            $checkMember.param['member_email']      = member_email;
            $checkMember.param['member_mobile']     = member_mobile;
            $checkMember.param['channel_father_id'] = $scope.param.channel_father_id;
            $httpService.post('/app.do?'+param, $checkMember, function(result){
                loading.hide();$httpService.deleteHeader('checkMember');
                if(result.data.success == '0') {
                    var message = $scope.getErrorByCode(result.data.code);
                    var message_ext = '.没有找到"'+mobile_email+'"的会员记录！';
                    $alert({title: 'Notice', content: message+message_ext, templateUrl: '/modal-warning.html', show: true});
                } else {
                    $scope.market_id = result.data.item.market_id;
                    $scope.param.member_id = result.data.item.member_id;
                    booking();
                }
            });
        } else {
            booking();
        }
        function booking() {
            loading.show();
            $scope.param.market_name = $scope.market_name;
            $scope.param.market_id = $scope.market_id;
            $scope.param.market_father_id = $scope.market_father_id; 
            $scope.param['booking_data'] = {};
            var data_key = $scope.param.check_in + '|' + $scope.param.check_out;
            $scope.param['booking_data'][data_key] = {};
            $scope.param['booking_data'][data_key]['system_price'] = $scope.system_price;
            $scope.param['booking_data'][data_key]['booking_room'] = $scope.booking_room;
            $httpService.header('method', 'bookingRoom');
            $scope.param['id'] = $rootScope.employeeChannel[$scope.param.channel_id].id;
            $httpService.post('/app.do?'+param, $scope, function(result){
                loading.hide();$httpService.deleteHeader('checkOrderData');
                if(result.data.success == '0') {
                    //var message = $scope.getErrorByCode(result.data.code);
                    //$alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
                } else {
                    
                }
            })
        }
    }
    $httpService.deleteHeader('refresh');
});