<script language="javascript">
$(document).ready(function(){
    //日历
	$.datetimepicker.setLocale('ch');
	var dateToDisable = new Date('<%$thisDay%>');
	//dateToDisable.setDate(dateToDisable.getDate() - 1);
	$('#user_check_in').datetimepicker({theme:'dark', format: 'Y-m-d H:i:00', formatDate:'Y-m-d H:i:00',
		beforeShowDay: function(date) {
			if (date.getTime() < dateToDisable.getTime()) {
				return [false];
			}
			return [true];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
        onSelectTime:function( ct ){
            var thisDate = new Date(this.getValue());
            var nextDate = new Date(thisDate.setDate(thisDate.getDate() + 1));
            var time_end_date = new Date($('#user_check_out').val());
            if(time_end_date.getTime() < nextDate.getTime()) {
                $('#user_check_out').val(nextDate);
                $('#user_check_out').datetimepicker({value:nextDate});
            }
        }
	});
	$('#user_check_out').datetimepicker({theme:'dark', format: 'Y-m-d H:i:00', formatDate:'Y-m-d H:i:00',
		beforeShowDay: function(date) {
			var dateToDisable = new Date($('#user_check_in').val());
			if (date.getTime() < dateToDisable.getTime()) {
				return [false];
			}
			return [true];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
        onSelectTime:function(date) {
            if(new Date(this.getValue()) <= new Date($('#book_check_in').val())) {
                $('#modal_fail').modal('show');
                $('#modal_fail_message').html("抱歉，这个时间不正确！");
                return false;
            }
        }
	});
    
    $('#room_check_in').datetimepicker({theme:'dark', format: 'Y-m-d H:i:00', formatDate:'Y-m-d H:i:00',
		beforeShowDay: function(date) {
			if (date.getTime() < dateToDisable.getTime()) {
				return [false];
			}
			return [true];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
        onSelectTime:function( ct ){
            var thisDate = new Date(this.getValue());
            var nextDate = new Date(thisDate.setDate(thisDate.getDate() + 1));
            var time_end_date = new Date($('#room_check_out').val());
            if(time_end_date.getTime() < nextDate.getTime()) {
                $('#room_check_out').val(nextDate);
                $('#room_check_out').datetimepicker({value:nextDate});
            }
        }
	});
	$('#room_check_out').datetimepicker({theme:'dark', format: 'Y-m-d H:i:00', formatDate:'Y-m-d H:i:00',
		beforeShowDay: function(date) {
			var dateToDisable = new Date($('#room_check_in').val());
			if (date.getTime() < dateToDisable.getTime()) {
				return [false];
			}
			return [true];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
        onSelectTime:function(date) {
            $('#total_room_rate').val('0');$('#room_layout_data').hide('fast');$('#room_layout_data_price').hide('fast');
            if(new Date(this.getValue()) <= new Date($('#room_check_in').val())) {
                $('#modal_fail').modal('show');
                $('#modal_fail_message').html("抱歉，这个时间不正确！");
                return false;
            }
            if($('#sell_layout').val() > 0) {
                bookEdit.searchBookRoom();
                bookEdit.computeCheckDate();
            }
        }
	});
    
    var BookEditClass = {
        hotel_service: {},book_discount_list: {},bookSelectRoom: {},bookNeed_service:{},lastDate:{},thenRoomPrice:{},tempRoomPrice:{},
        hotelCheckDate: {},roomSellLayout: {},selectBed:{},tempRoomEdit:{},room_info_id:{},room_info :{},tempServicePrice: {},
        returnRoom:{},returnRoomPrice:{},layout_corp: '<%$arrayDataInfo[0].room_layout_corp_id%>',
	    max_man: 0,//最多人数
        BookUser_num: 1,
        priceSystem:{},payment_type:{},
        instance: function() {
            var bookEdit = {};
            bookEdit.initParameter = function() {
                BookEditClass.weekday=new Array(7);
                BookEditClass.weekday[0]="日";BookEditClass.weekday[1]="一";BookEditClass.weekday[2]="二";
                BookEditClass.weekday[3]="三";BookEditClass.weekday[4]="四";BookEditClass.weekday[5]="五";
                BookEditClass.weekday[6]="六";
                BookEditClass.orientations=new Array(7);
                BookEditClass.orientations['east']='东';BookEditClass.orientations['south']='南';
                BookEditClass.orientations['west']='西';BookEditClass.orientations['north']='北';
                BookEditClass.orientations['southeast']='东南';BookEditClass.orientations['northeast']='东北';
                BookEditClass.orientations['southwest']='西南';BookEditClass.orientations['northwest']='西北';
                BookEditClass.orientations['no']='无';
                bookEdit.groupSellLayoutSystem();
                BookEditClass.tempRoomEdit['edit'] = '';
                bookEdit.roomInfo();
            };
            bookEdit.init = function() {
                $('#sell_layout').val('');
                $('#serviceItem').val('');
                $('#add_user').click(function(e) {
                    bookEdit.bookRoomAddUser();
                });
                $('#cancel_add_user').click(function(e) {
                    $('#add_user_tr').hide();
                });
                $('#add_service').click(function(e) {
                    $('#add_service_tr').show('slow');
                    $('#calculation-box').show('slow');
                });
                $('#cancel_add_service').click(function(e) {
                    $('#add_service_tr').hide();
                });
                $('#sell_layout').change(function(e) {
                    var priceSystem = BookEditClass.priceSystem;
                    var sell_id = $(this).val();var sellLayout = {};var select_html = '';
                    if(typeof(priceSystem[sell_id]) != 'undefined') {
                        sellLayout[sell_id] = priceSystem[sell_id];
                    }
                    sellLayout[0] = priceSystem[0];
                    for(sellId in sellLayout) {
                        for(systemID in sellLayout[sellId]) {
                            select_html += '<option value="'+systemID+'">'+sellLayout[sellId][systemID]+'</option>';
                        }
                    }
                    $('#price_system').html(select_html);
                    bookEdit.searchBookRoom();
                    bookEdit.computeCheckDate();
                });
                $('#price_system').change(function(e) {
                    bookEdit.searchBookRoom();
                    bookEdit.computeCheckDate();
                });
                $('#search_room').click(function(e) {
                    if($('#sell_layout').val() == '') {
                        $('#modal_info').modal('show');
                        $('#modal_info_message').html('请选择入住房型！');
                        return;
                    }
                    bookEdit.searchBookRoom();
                    bookEdit.computeCheckDate();
                });
                $('#serviceItem').change(function(e) {
                    var price = $(this).find("option:selected").attr('price');
                    var _next = $(this).parent().next();
                    _next.html('<input price="'+price+'" readonly id="service_price" type="text" value="'+price+'" class="input-small">');
                    _next.next().html('<input id="service_num" type="text" value="1" class="input-small">');
                    _next.next().next().html('<input id="service_discount" type="text" value="100" class="input-small">');
                    _next.next().next().next().html('<input readonly id="service_total_price" type="text" value="'+price+'" class="input-small">');
                    $('#service_num,#service_discount').keyup(function(e) {
                        bookEdit.computeServicePrice(this);
                    });
                });
                $('#save_add_room').click(function(e) {
                    bookEdit.saveAddRoom();
                    bookEdit.computeAllBookPrice();
                });
                $('#add_room').click(function(e) {
                    $('#add_room_tr').show('slow');
                    $('#calculation-box').show('slow');
                    BookEditClass.tempRoomEdit['edit'] = 'add_room';
                    BookEditClass.tempRoomEdit['room_id'] = '';
                    BookEditClass.tempRoomEdit['book_id'] = '';
                });
                $('#cancel_add_room').click(function(e) {
                    $('#add_room_tr').hide();
                    $('#room_layout_data').hide();
                    $('#room_layout_data_price').hide();
                    if(BookEditClass.tempRoomEdit['edit'] == 'add_room') {
                        BookEditClass.tempRoomEdit['edit'] = '';
                    }
                });
                $('.change_room').click(function(e) {
                    bookEdit.changeRoom('change_room', this)
                });
                $('.continued_room').click(function(e) {
                    bookEdit.changeRoom('continued_room', this);
                });
                //退房
                $('.check_out_room').click(function(e) {
                    bookEdit.changeRoom('check_out_room', this);
                    return;
                });
                $('.cancel_room').click(function(e) {
                    var room_id = $(this).parent().attr('room_id');var book_id = $(this).parent().attr('book_id');
                    var select_room_id = BookEditClass.tempRoomEdit['room_id'];
                    if(select_room_id == room_id) {
                        $(this).parent().prev().prev().html('<i class="am-icon-circle-o"></i> 管理');
                        BookEditClass.tempRoomEdit['edit'] = '';
                    }
                    $('#cancel_add_room'+room_id).parent().parent().remove();
                    BookEditClass.room_info_id[room_id] = '';//移除使用房间
                    var thenRoomPrice = BookEditClass.thenRoomPrice;
                    for(room_key in thenRoomPrice) {
                        if(thenRoomPrice[room_key].type_room_id == room_id) {
                            BookEditClass.thenRoomPrice[room_key] = '';
                            $(this).parent().prev().prev().html('<i class="am-icon-circle-o"></i> 管理');
                            $('#cancel_add_room'+room_key).parent().parent().remove();
                            BookEditClass.tempRoomEdit['edit'] = '';
                            break;
                        }
                    }
                    if(typeof(BookEditClass.returnRoom[room_id]) != 'undefined') {
                        $(this).parent().prev().prev().html('<i class="am-icon-circle-o"></i> 管理');
                        BookEditClass.returnRoom[room_id] = '';
                        $('#return_room_'+room_id).remove();
                        if($('#return_room_tr').prev().html() == null) $('#check-out-box').hide('flast');
                        $('#room_layout_data').hide('flast');
                        $('#return_book_cash_pledge').val('');
                        $('#return_book_room_rate').val('');
                    }
                });
                $('#am-icon-calculator').click(function(e) {
                    $('#rate_calculation').show('fast');
                });
                $('#close_calculation').click(function(e) {
                    $('#rate_calculation').hide('fast');
                });
                $('#save_book_room').click(function(e) {
                    bookEdit.saveBookRoom();
                });
                //结算
                $('#close_an_account').click(function(e) {
                    $(this).hide();$('#book_payment').show('fast');
                    $('#book_is_pay').show('fast');$('#book_payment_type').show('fast');$('#save_an_account').show('fast');
                });
                $('#save_an_account').click(function(e) {
                    bookEdit.saveAnAccount();
                });
                $('.check_in_room').click(function(e) {
                    bookEdit.checkInRoom(this);
                });
                $('#all_check_in').click(function(e) {
                    bookEdit.checkInRoom(this);
                });
                $('#save_add_user').click(function(e) {
                    bookEdit.saveAddUser();
                });
                //办房卡
                $('.user_room_card,.return_user_room_card').click(function(e) {
                    var val = 1;
                    if($(this).hasClass('return_user_room_card')) val = 2;
                    bookEdit.setUserRoomCard(this, val);
                });
                //附加服务
                $('#save_add_service').click(function(e) {
                    bookEdit.saveAddService(this);
                });
                //退押金
                $('#return_deposit_money').click(function(e) {
                    bookEdit.returnDepositMoney();
                });
                //退房
                $('#return_room_calculation').click(function () {
                    bookEdit.returnRoomPriceCompute();
                });
                $('#return_room_money').click(function () {
                    bookEdit.returnRoomMoney();

                });
                bookEdit.setPaymentType();
                $('#update_sumbit').click(function () {
                    bookEdit.returnUpdateSumbit()
                });
                $('#free_change_btn').click(function () {
                    var free = $(this).hasClass('am-icon-square-o');
                    if(free) {
                        $(this).removeClass('am-icon-square-o').addClass('am-icon-check-square-o');
                        $('#free_change').val(1);
                    } else {
                        $(this).removeClass('am-icon-check-square-o').addClass('am-icon-square-o');
                        $('#free_change').val(0);
                    }
                });
            };
            bookEdit.returnRoomMoney = function() {
                var return_book_cash_pledge = $('#return_book_cash_pledge').val();
                var return_book_room_rate = $('#return_book_room_rate').val();
                if(return_book_cash_pledge == '' || return_book_room_rate == '') {
                    $('#modal_info').modal('show');
                    $('#modal_info_message').html('请计算退房明细');
                    return;
                }
                $('#modal_update').modal('show');
                $('#modal_update_message').html('退房审核无误，请按 Confirm (确定) 按钮！');
                $('#update_sumbit').attr('update', 'returnRoomMoney')

            };
            bookEdit.updateReturnRoomMoney = function() {
                $('#save_info').modal('show')
                var return_book_cash_pledge = $('#return_book_cash_pledge').val();
                var return_book_room_rate = $('#return_book_room_rate').val();
                var param = '&cash_pledge='+return_book_cash_pledge+'&room_rate='+return_book_room_rate+'&return=' + JSON.stringify(BookEditClass.returnRoomPrice)
                $.getJSON('<%$saveBookInfoUrl%>&act=returnBookPrice&room_id='+JSON.stringify(BookEditClass.returnRoom)+param, function (result) {
                    $('#save_info').modal('hide')
                    data = result;
                    if(data.success == 1) {
                        window.location.reload();
                    } else {
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                })

            }
            bookEdit.returnUpdateSumbit = function () {
                var update = $('#update_sumbit').attr('update')
                if(update == 'returnRoomMoney') bookEdit.updateReturnRoomMoney();
                
            }
            bookEdit.setPaymentType = function () {
                var payment_type = BookEditClass.payment_type;
                var payment_type_select = '<option value=""><%$arrayLaguage["please_select"]["page_laguage_value"]%></option>';
                payment_type[''] = '';
                $('#payment_type_father option').each(function () {
                    var father = $(this).attr('father');
                    if(this.value > 0) {
                        if (typeof(payment_type[father]) == 'undefined') payment_type[father] = '';
                        if(father != this.value) {
                            payment_type[father] += '<option value="'+this.value+'">'+this.text+'</option>'
                        } else {
                            payment_type_select += '<option value="'+this.value+'">'+this.text+'</option>'
                        }
                    }
                })
                $('#payment_type_father').html(payment_type_select);
                $('#payment_type_father').change(function () {
                    $('#payment_type').html(payment_type[this.value]);
                });
            };
            bookEdit.returnRoomPriceCompute = function() {
                $.getJSON('<%$saveBookInfoUrl%>&act=returnBook&room_id='+JSON.stringify(BookEditClass.returnRoom), function (result) {
                    data = result;
                    if(data.success == 1) {
                        var itemData = data.itemData.room;
                        for(room_id in itemData) {
                            $('#return_room_'+room_id).find('.return_check_in_days').html(itemData[room_id].have_lived);
                            $('#return_room_'+room_id).find('.return_cash_pledge').html(itemData[room_id].cash_pledge);
                            $('#return_room_'+room_id).find('.return_price').html(itemData[room_id].room_rate);
                            $('#return_room_'+room_id).find('.return_consume').html(itemData[room_id].consume);
                        }
                        var return_price = data.itemData.return;
                        $('#return_book_cash_pledge').val(return_price.return_cash_pledge);
                        $('#return_book_room_rate').val(return_price.return_room_rate);
                        BookEditClass.returnRoomPrice = data.itemData;
                    } else {
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }

                })
            };
            bookEdit.returnDepositMoney = function () {
                $('#modal_update').modal('show');
                $('#modal_update_message').html('确定退押金，请按 Confirm (确定) 按钮！');
            };
            bookEdit.setUserRoomCard = function(_this, val) {
                $('#modal_save').modal('show');
                var book_user_id = $(_this).parent().attr('book_user_id');
                var url = '<%$saveBookInfoUrl%>&act=setUserRoomCard&id='+book_user_id+'&val='+val;
                var text = '已领';if(val == 2) text = '已退';
                $.getJSON(url, function(result) {
                    $('#modal_save').modal('hide');
                    if(book_user_id == 'ALL') {
                        $('.book_card').html(text);
                    } else {
                        $('#book_card' + book_user_id).html(text);
                    }
                })
            }
            bookEdit.saveAddUser = function() {
                var room_user_name = $.trim($('#room_user_name').val());
                var user_id_card = $.trim($('#user_id_card').val());var room_user_sex = $('#room_user_sex').val();
                var room_user_sex_text = $('#room_user_sex').find('option:selected').text();
                var user_id_card_type = $('#user_id_card_type').val();
                var user_id_card_type_text = $('#user_id_card_type').find('option:selected').text();
                var check_in_room_num = $.trim($('#check_in_room_num select').val());
                var check_in_room_num_text = $('#check_in_room_num').find('option:selected').text();
                var user_lodger_type = $('#check_in_room_num select').find('option:selected').attr('type');
                var error_message = '';
                if(room_user_name == '') error_message += '姓名/';
                if(room_user_sex == '') error_message += '性别/';
                if(user_id_card_type == '') error_message += '身份信息/';
                if(user_id_card == '') error_message += '证件号码/';
                if(check_in_room_num == '') error_message += '入住房号/';
                if(error_message != '') {
                    $('#modal_info').modal('show');
                    $('#modal_info_message').html(error_message + ' 必填！');
                    return;
                }
                var user_comments = $('#user_comments').val();
                $('#modal_save').modal('show');
                var param = $("#add_user_form").serialize();
                param = param+'&user_lodger_type='+user_lodger_type + '&room_num='+$('#check_in_room_num select').val()
                      +'&book_id='+$('#main_book_id').val()+'&sell_layout_id='+$('#sell_layout_id').val()+'&layout_id='+$('#layout_id').val()
                $.ajax({
                    url : '<%$saveBookInfoUrl%>&act=addBookUser',type : "post",dataType : "json",data: param,
                    success : function(result) {
                        $('#modal_save').modal('hide');
                        data = result;
                        if(data.success == 1) {
                            room_layout_id = data.itemData.room_layout_id;
                             $('#modal_success').modal('show');
                             $('#modal_success_message').html(data.message);
                             var html = '<tr><td>'+room_user_name+'</td><td>'+room_user_sex_text+'</td><td>'+user_id_card_type_text+'</td><td>'+user_id_card+'</td>'
                                       +'<td class="check_in_info" room_id="'+check_in_room_num+'" user_lodger_type="'+user_lodger_type+'">'+check_in_room_num_text+'</td>'
                                       +'<td>未领</td><td>'+user_comments+'</td><td>'
                                       +'<div class="fr"><div class="btn-group"><a class="btn btn-primary btn-mini" href="#t">'
                                       +'<i class="am-icon-circle-o"></i> 管理</a>'
                                       +'<a class="btn btn-primary btn-mini dropdown-toggle" data-toggle="dropdown" href="#">'
                                       +'<span class="caret"></span></a>'
                                       +'<ul class="dropdown-menu" room_id="2" book_id="1">'
                                       +'<li class="user_room_card"><a data-target="#" href="#t"><i class="am-icon-credit-card"></i> 已办房卡</a></li>'
                                       +'<li class="return_user_room_card"><a data-target="#" href="#t"><i class="am-icon-exchange"></i> 已退房卡</a></li>'
                                       +'<li class="user_room_edit"><a data-target="#" href="#t"><i class="am-icon-pencil-square-o"></i> 编辑信息</a></li>'
                                       +'</ul></div></div></td></tr>';
                             $('#add_user_tr').before(html);
                             $('#add_user_tr input,#add_user_tr select').val('');
                             $('#add_user_tr').hide();
                        } else {
                            $('#modal_fail').modal('show');
                            $('#modal_fail_message').html(data.message);
                        }
                    }
                });
            };
            bookEdit.checkInRoom = function(_this) {
                var book_is_pay_status = $('#book_is_pay_status').val();
                if(book_is_pay_status != 1) {
                    $('#modal_info').modal('show');
                    $('#modal_info_message').html('请先执行结算操作！');
                    return;
                }
                $('#modal_save').modal('show');
                var book_id = $(_this).parent().attr('book_id');
                var room_id = $(_this).parent().attr('room_id');
                var url = '<%$saveBookInfoUrl%>&act=checkInRoom&book_id='+book_id+'&room_id='+room_id;
                $.getJSON(url, function(result){
                    $('#modal_save').modal('hide');
                    data = result;
                    if(data.success == 1) {
                        var html = '<code class="fr"><i class="am-icon-child"></i> 已入住</code>';
                        if(room_id == 'ALL') {
                            $('.all_check_in').each(function(index, element) {
                                $(this).html(html);
                            });
                        } else {
                            $(_this).parent().parent().parent().parent().prev().html(html);
                        }
                    } else {
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                })
            };
            bookEdit.saveAnAccount = function() {
                var book_payment = $('#book_payment').val();var book_is_pay = $('#book_is_pay').val();var book_payment_type = $('#book_payment_type').val();
                if(book_payment == '') {$('#modal_info').modal('show');$('#modal_info_message').html('请选择支付状态！'); return;}
                if(book_payment == '1') {$('#modal_info').modal('show');$('#modal_info_message').html('必须为全额或者余额支付！'); return;}
                if(book_is_pay == '') {$('#modal_info').modal('show');$('#modal_info_message').html('请选择支付是否到账收款！'); return;}
                if(book_payment_type == '') {$('#modal_info').modal('show');$('#modal_info_message').html('请选择支付方式！'); return;}
                $('#modal_save').modal('show');
                var url = '<%$saveBookInfoUrl%>&act=saveBookPayment&book_payment='+book_payment+'&book_is_pay='+book_is_pay+'&book_payment_type='+book_payment_type
                $.getJSON(url, function(result){
                    $('#modal_save').modal('hide');
                    data = result;
                    if(data.success == 1) {
                        var book_payment = $.trim($('#book_payment').find('option:selected').text());
                        var book_is_pay = $.trim($('#book_is_pay').find('option:selected').text());
                        var book_payment_type = $.trim($('#book_payment_type').find('option:selected').text());
                        $('#book_payment').parent().prev().html(book_payment);$('#book_payment').hide();
                        $('#book_is_pay').parent().prev().html(book_is_pay);$('#book_is_pay').hide();
                        $('#book_payment_type').parent().prev().html(book_payment_type);$('#book_payment_type').hide();
                        $('#book_is_pay_status').val(1);
                    } else {
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                })
            };
            bookEdit.roomInfo = function() {
                var room_info = BookEditClass.room_info;
                $('.room_info_id').each(function(index, element) {
                    var room_id = $(this).attr('room_id');
                    //BookEditClass.room_info_id[room_id] = room_id;
                    room_info[room_id] = {};
                    var max_people = $(this).attr('max_people');var max_children = $(this).attr('max_children');
                    var extra_bed = $(this).attr('extra_bed');var name = $(this).attr('name');
                    room_info[room_id]['name'] = name;room_info[room_id]['extra_bed'] = extra_bed;
                    room_info[room_id]['max_people'] = max_people;room_info[room_id]['max_children'] = max_children;
                });
                BookEditClass.room_info = room_info;
            };
            bookEdit.changeRoom = function(type, _this) {
                var room_id = $(_this).parent().attr('room_id');
                var book_id = $(_this).parent().attr('book_id')
                if(BookEditClass.tempRoomEdit['edit'] != '') {
                    if(BookEditClass.tempRoomEdit['room_id'] != room_id) {
                        $('#modal_info').modal('show');
                        $('#modal_info_message').html('依次操作，请先取消上一次操作！');
                        return;
                    }
                }
                var thenRoomPrice = BookEditClass.thenRoomPrice;
                for(room_key in thenRoomPrice) {
                    if(thenRoomPrice[room_key].type_room_id == room_id) {
                        $('#modal_info').modal('show');
                        $('#modal_info_message').html('请先取消之前的设定！再进行更改。');
                        return;
                    }
                }
                BookEditClass.tempRoomEdit['edit'] = type;
                BookEditClass.tempRoomEdit['room_id'] = room_id;
                BookEditClass.tempRoomEdit['book_id'] = book_id;
                $('#layout_room').attr('type', type);
                if(type != 'check_out_room') {
                    $('#add_room_tr').show('slow');
                    $('#calculation-box').show('slow');
                    $(_this).parent().prev().prev().html('<i class="am-icon-circle am-red-FA0A0A"></i>' + $(_this).children().text());
                    if(typeof(BookEditClass.returnRoom[room_id]) != 'undefined') {
                        BookEditClass.returnRoom[room_id] = '';
                        $('#return_room_'+room_id).remove();
                        if($('#return_room_tr').prev().html() == null) $('#check-out-box').hide('flast');
                    }
                } else {
                    //退房
                    $(_this).parent().prev().prev().html('<i class="am-icon-circle am-yellow-FFAA3C"></i>' + $(_this).children().text());
                    $('#check-out-box').show('slow');
                    $('#add_room_tr').hide();
                    $('#calculation-box').hide('flast')
                    bookEdit.returnRoom(_this);
                    $('#return_book_cash_pledge').val('');
                    $('#return_book_room_rate').val('');
                }
            };
            bookEdit.returnRoom = function (_this) {
                var book_id = $(_this).parent().attr('book_id');
                var room_id = $(_this).parent().attr('room_id');
                var room_info = $(_this).parent().parent().parent().parent().parent();
                if(typeof(BookEditClass.returnRoom[room_id]) != 'undefined' && BookEditClass.returnRoom[room_id] > 0) return;
                $('#return_room_tr .return_room_name').html($.trim(room_info.find('.room_info_id').text()));
                $('#return_room_tr .return_check_in_date').html($.trim(room_info.find('.book_check_in').text()));
                $('#return_room_tr').clone().removeClass('hide').attr('id', 'return_room_'+room_id).insertBefore('#return_room_tr');
                BookEditClass.returnRoom[room_id] = book_id;
                BookEditClass.tempRoomEdit = {};
                BookEditClass.tempRoomEdit['edit'] = '';

            }

            bookEdit.resetchangeRoom = function(room_id) {
                $('.change_room,.continued_room,.check_out_room').each(function(index, element) {
                    if($(this).parent().attr('room_id') != room_id) {
                        $(this).parent().prev().prev().html('<i class="am-icon-circle-o"></i> 管理');
                        return;
                    }
                });
                return true;
            }
            bookEdit.groupSellLayoutSystem = function() {
                var priceSystem = BookEditClass.priceSystem;
                $('#price_system').children('option').each(function(index, element) {
                    var sell_id = $(this).attr('sell_id');
                    if(typeof(priceSystem[sell_id]) == 'undefined') {
                        priceSystem[sell_id] = {};
                        priceSystem[sell_id][$(this).val()] = $(this).text();
                    } else {
                        priceSystem[sell_id][$(this).val()] = $(this).text();
                    }
                    $('#price_system').html('');
                });
            };
            bookEdit.maxCheckOut = function(check_out) {
                var max_check_out = $('#room_check_out').val();
                var checkOutDate = new Date(check_out);var today = checkOutDate.getDate();var thisHours = checkOutDate.getHours();
                var halfPrice = $('#half_price').val().replace(':00', '');
                if(thisHours > halfPrice) {
                    //算半天
                    checkOutDate.setDate(checkOutDate.getDate()+1);
                    var month = checkOutDate.getMonth() - 0 + 1; month = month < 10 ? '0' + month : month;
                    var day = checkOutDate.getDate();day = day < 10 ? '0' + day : day;
                    max_check_out = checkOutDate.getFullYear() + '-' + month + '-' + day;                    
                }  
                return max_check_out;
            };
            bookEdit.searchBookRoom = function() {
                var room_layout_id = $('#sell_layout').find("option:selected").attr('room_layout');
                var sell_id = $('#sell_layout').val();var system_id = $('#price_system').val();
                var check_in = $('#room_check_in').val(); var check_out = $('#room_check_out').val();
                var layout_corp = BookEditClass.layout_corp;
                if(check_in == '' || check_out == ''){
                    //$('#modal_info').modal('show');
                    //$('#modal_info_message').html('请选择入住日期/离店日期！');
                    return;
                }
                if(new Date(check_out) <= new Date(check_in)) {
                    $('#modal_fail').modal('show');
                    $('#modal_fail_message').html("抱歉，这个时间不正确！");
                    return false;
                }
                if(sell_id == '') {
                    return;   
                }
                var max_check_out = bookEdit.maxCheckOut(check_out);
                $.ajax({
                    url : '<%$searchBookInfoUrl%>&search=searchRoomLayout&discount=' + $('#discount').val() 
                        + '&sell_layout_list=' + sell_id + '-' + system_id + '&layout_corp=' + layout_corp,
                    type : "post",
                    data : 'book_check_in=' + check_in + '&book_check_out=' + check_out + '&max_check_out=' + max_check_out,
                    dataType : "json",
                    success : function(result) {
                        $('#modal_loading').hide('show');
                        data = result;
                        if(data.success == 1) {
                           var html = bookEdit.resolverRoomLayoutData(data, check_in, check_out);
                           $('#room_layout_data').html('<td colspan="12"><table>' + html + '</table></td>');
                           $('#room_layout_data').show('fast');
                           $('#room_layout_data_price').show('fast');
                        } else {
                            $('#modal_fail').modal('show');
                            $('#modal_fail_message').html(data.message);
                        }
                    }
                });
                $.getJSON('<%$searchBookInfoUrl%>&search=searchRoom&room_layout_id='+room_layout_id
                                +'&sell_id='+sell_id+'&book_check_in='+check_in+'&book_check_out='+check_out,
                  function(result){
                    data = result;
                    var selectRoomhtml = bookEdit.formatRoomTable(data, system_id, sell_id);
                    $('#layout_room').html(selectRoomhtml);
                    $('#extra_bed').html(BookEditClass.selectBed[$('#select_room').val()]);
                    $('#select_room').change(function(e) {
                        $('#extra_bed').html(BookEditClass.selectBed[this.value]);
                    });
                    $('#modal_loading').hide();
                })   
                //清空价格
                $('#total_room_rate').val('');
            };
            bookEdit.formatRoomTable = function(data, system_id, sell_id) {
                var selectRoomhtml = '';var selectBed = BookEditClass.selectBed;
                if(data.itemData != null && data.itemData != '') {
                   itemData = data.itemData;
                   var selectBedHtml = '';
                   var extra_bed_disable = 'disabled';
                   for(i in data.itemData) {
                        if(BookEditClass.room_info_id[itemData[i].room_id] > 0 ) {continue;}
                        var addBedSelect = '';
                        if(itemData[i].extra_bed > 0) {
                            extra_bed_disable = '';
                        }
                        selectBedHtml = '<select '+extra_bed_disable+' class="input-mini room_extra_bed" room_layout="'+itemData[i].room_layout_id+'" system_id="'+system_id+'" '
                                    +'room="'+itemData[i].room_id+'" sell_id="'+sell_id+'">';
                        for(j = 0; j <= itemData[i].extra_bed; j++) {
                            if($('#addBed_data').data(sell_id+'_'+itemData[i].room_id) == j) {
                                addBedSelect = 'selected';
                            }
                            selectBedHtml += '<option value="'+j+'" '+addBedSelect+'>'+j+'</option>';
                            addBedSelect = '';
                        }
                        selectBedHtml += '</select>';
                        extra_bed_disable = 'disabled';
                        selectBed[itemData[i].room_id] = selectBedHtml;
                        //}
                        //设置是否是已使用的checked
                        var checked_room = '';
                        if(typeof($('#room_data').data(itemData[i].room_id)) != 'undefined') {
                            if($('#room_data').data(itemData[i].room_id) == (sell_id+'-'+ system_id) ) {
                                checked_room = 'checked';
                            } else {
                                checked_room = 'disabled';
                            }
                        }
                        selectRoomhtml += '<option '+checked_room+' value="'+itemData[i].room_id+'" system_id="'+system_id+'" sell_id="'+sell_id+'"'
                             +'room_layout="'+itemData[i].room_layout_id+'" max_people="'+itemData[i].max_people+'" max_children="'+itemData[i].max_children+'"'
                             +' title="'+itemData[i].room_number+'" max_extra_bed="'+itemData[i].extra_bed+'">'
                             +itemData[i].room_name+'['+itemData[i].room_number+']'
                             //+BookEditClass.orientations[itemData[i].room_orientations]
                             //+' '+itemData[i].room_area+'㎡'
                             +' / ' + itemData[i].max_people
                             +' / ' + itemData[i].max_children
                             +'</option>';
                        //selectHtml = '';
                   }
                   BookEditClass.selectBed = selectBed;
                   if(selectRoomhtml == '') selectRoomhtml = '<option value="">无房</option>';
                   selectRoomhtml = '<select id="select_room">'+ selectRoomhtml+'</select>';	  
                } else {
                   selectRoomhtml = '<option value="">无房</option>';
                   selectRoomhtml = '<select id="select_room">'+ selectRoomhtml+'</select>';	  
                }
                
                return selectRoomhtml;
            };
            //分解房型、价格体系数据 
            bookEdit.resolverRoomLayoutData = function(data, check_in, check_out) {
                var data = data.itemData;
                BookEditClass.lastDate = {};
                var html = td1 = td2 = td_bed = option = pledge = '';
                var cash_pledge = {};
                var in_date = new Date(check_in);
                var in_day = in_date.getDate();
                var in_month = in_date.getMonth() - 0 + 1;
                var in_year = in_date.getFullYear();
                var out_date = new Date(check_out);
                var out_day = out_date.getDate();
                var out_month = out_date.getMonth() - 0 + 1;
                var out_year = out_date.getFullYear();
                var layoutPrice = data.layoutPrice;
                
                if(layoutPrice == '' || typeof(layoutPrice) == 'undefined' || layoutPrice[0] == '') {
                    return '-1';//无价格
                }
                var priceSystem = bookEdit.getSellLayoutSystem();
                //var roomSellLayout = data.roomSellLayout;
                var roomSellLayout = bookEdit.getRoomSellLayout();
                var tmpExtraBedPrice = data.extraBedPrice; 
                var extraBedPrice = {};
                if(tmpExtraBedPrice != '') {
                    for(i in tmpExtraBedPrice) {
                        //][][
                        var id = tmpExtraBedPrice[i].sell_layout_id  +'-'+ tmpExtraBedPrice[i].room_layout_price_system_id +'-'
                               + tmpExtraBedPrice[i].this_year +'-'+ tmpExtraBedPrice[i].this_month;
                        extraBedPrice[id] = tmpExtraBedPrice[i];
                    }
                }
                var weekday = BookEditClass.weekday;
                var in_months = BookEditClass.leapYear();
                var same_layout_system = ''; //room_layout_price_system_id
                for(i in layoutPrice) {
                    var sell_layout_id = layoutPrice[i].sell_layout_id;
                    var system_id = layoutPrice[i].room_layout_price_system_id;
                    var extraBedid = sell_layout_id  +'-'+ system_id +'-'+ layoutPrice[i].this_year +'-'+ layoutPrice[i].this_month;
                    if((sell_layout_id + '_' + system_id) == same_layout_system) {
                        //td2 += td2; 与上一个相同的房型和价格体系
                    } else {
                        if(i > 0) {
                            var _sell_layout_id = layoutPrice[i - 1].sell_layout_id;
                            var _system_id = layoutPrice[i - 1].room_layout_price_system_id;
                            var html_id = 'room_layout_id="'+roomSellLayout[_sell_layout_id].room_layout_id+'" sell_layout_id="'+_sell_layout_id+'" system_id="'+_system_id+'"';
                            var pledge = '<ul class="stat-boxes stat-boxes2"><li><div class="left peity_bar_bad cash_pledge"><%$arrayLaguage["cash_pledge"]["page_laguage_value"]%></div>'
                                        +'<div class="right pledge_price"><span><input system_id="'+_system_id+'" sell_layout="'+_sell_layout_id+'" value="'+cash_pledge[_sell_layout_id+'-'+_system_id]+'" class="span12" type="text"></span></div></li></ul>';
                            //cash_pledge[room_layout_id+'-'+system_id] = layoutPrice[i][day+'_day'];
                            html += '<tr '+html_id+'>'+
                                        '<td class="details-control">'+td1+'</td>'+
                                        '<td>'+td2 + td_bed + pledge + '</td>'+
                                        //'<td>'+td_bed+'</td>'+
                                    '</tr>';
                            td1 = td2 = td_bed = option = pledge = '';   
                        }
                        td1 = '' + roomSellLayout[sell_layout_id].room_sell_layout_name + '-' //+ i 
                        //roomSellLayout[sell_layout_id].room_layout_name + 
                             + priceSystem[system_id].room_layout_price_system_name;
                        td1 = td1 +' ';
                    }
                    //td2 begin
                    td2 += '<ul class="stat-boxes stat-boxes2">';
                    td2 += '<li><div class="left peity_bar_bad"><span>'+layoutPrice[i].this_month+'</span>'
                            +layoutPrice[i].this_year+'</div><div class="right price"><span><%$arrayLaguage["room_price"]["page_laguage_value"]%></span></div></li>';
                    if(in_year == out_year) {
                        //相同的年
                        if(in_month == out_month) {
                            //相同的月
                            loop_day = out_day;
                        } else {
                            //不同的月
                            if(in_month < out_month) loop_day = in_months[in_month - 1];
                            if(out_month == layoutPrice[i].this_month) loop_day = out_day;
                            if(in_month < layoutPrice[i].this_month) {
                                in_day = 1;
                            } else {
                                in_day = in_date.getDate();  
                            }
                        }
                    } else {
                        //不同的年
                        loop_day = in_months[layoutPrice[i].this_month - 1];
                        if(out_month == layoutPrice[i].this_month && out_year == layoutPrice[i].this_year) loop_day = out_day;
                        if(i > 0) {
                            if(layoutPrice[i].this_year == in_year && layoutPrice[i].this_month == in_month)  {
                                in_day = in_date.getDate();
                            } else {
                                in_day = 1;
                            }
                        }
                        if(in_year == layoutPrice[i].this_year && in_month == layoutPrice[i].this_month) in_day = in_date.getDate();
                    }
                    var lastYear = layoutPrice[i].this_year;var lastMonth = (layoutPrice[i].this_month - 0) < 10 ? '0' + layoutPrice[i].this_month : layoutPrice[i].this_month;
                    var lastDay = (loop_day - 0) < 10 ? '0' + loop_day : loop_day;
                    BookEditClass.lastDate[sell_layout_id + '-' + system_id] = lastYear + '-' + lastMonth + '-' + lastDay;
                    var month = layoutPrice[i].this_month < 10 ? '0' + layoutPrice[i].this_month : layoutPrice[i].this_month;
                    for(var day_i = in_day; day_i<= loop_day; day_i++) {
                        var day = day_i < 10 ? '0'+day_i : day_i;
                        var this_day = layoutPrice[i].this_year+'-'+month+'-'+day;
                        var week_date = new Date(this_day);
                        var week = week_date.getDay();//room_layout_id="'++'"
                        var div_class = week == 0 || week == 6 ? 'peity_bar_good' : '';
                        td2 += '<li><div class="left '+div_class+'"><span>'+day+'</span>'+weekday[week]+'</div><div class="right">'
                            +'<input value="'+layoutPrice[i][day+'_day']+'" rdate="'+this_day+'" '
                            +'room_layout="'+roomSellLayout[sell_layout_id].room_layout_id+'" system_id="'+system_id+'" sell_layout="'+sell_layout_id+'"'
                            +'class="layout_price span12" type="text" readonly ></div></li>';
                        if(typeof(cash_pledge[sell_layout_id+'-'+system_id]) == 'undefined') {
                            cash_pledge[sell_layout_id+'-'+system_id] = layoutPrice[i][day+'_day'];
                        }
                    }
                    td2 += '</ul>';
                    //td2 end
                    //bed begin
                    if(typeof(extraBedPrice[extraBedid]) != 'undefined') {
                        var bed = extraBedPrice[extraBedid];
                        td_bed += '<ul class="stat-boxes stat-boxes2">';
                        td_bed += '<li><div class="left peity_bar_bad"><span>'+layoutPrice[i].this_month+'</span>'
                            +layoutPrice[i].this_year+'</div><div class="right price"><span><%$arrayLaguage["extra_bed"]["page_laguage_value"]%></span></div></li>';
                        for(var day_i = in_day; day_i<= loop_day; day_i++) {
                            var day = day_i < 10 ? '0'+day_i : day_i;
                            //var month = layoutPrice[i].this_month < 10 ? '0' + layoutPrice[i].this_month : layoutPrice[i].this_month;
                            var this_day = layoutPrice[i].this_year+'-'+month+'-'+day;
                            var week_date = new Date(this_day);
                            var week = week_date.getDay();
                            
                            var div_class = week == 0 || week == 6 ? 'peity_bar_good' : '';
                            td_bed += '<li><div class="left '+div_class+'"><span>'+day+'</span>'+weekday[week]+'</div><div class="right">'
                                +'<input value="'+bed[day+'_day']+'" beddate="'+this_day+'" '
                                +'room_layout="'+roomSellLayout[sell_layout_id].room_layout_id+'" system_id="'+system_id+'" sell_layout="'+sell_layout_id+'"'
                                +'class="span12 extra_bed_price" type="text" readonly >'
                                +'</div></li>';
                        }    
                        td_bed += '</ul>';
                    }
                    //end  bed
                    //td_bed = '';
                    same_layout_system = sell_layout_id + '_' + system_id;
                }
                var pledge = '<ul class="stat-boxes stat-boxes2"><li><div class="left peity_bar_bad cash_pledge"><%$arrayLaguage["cash_pledge"]["page_laguage_value"]%></div>'
                                        +'<div class="right pledge_price"><span>'
                                        +'<input system_id="'+system_id+'" sell_layout="'+sell_layout_id+'" value="'+cash_pledge[sell_layout_id+'-'+system_id]+'" class="span12" type="text"></span></div></li></ul>';//+' max_people="'+roomLayout[_room_layout_id].max_people+'"  max_children="'+roomLayout[_room_layout_id]..max_children+'">'+
                html += '<tr room_layout_id="'+roomSellLayout[sell_layout_id].room_layout_id+'" sell_layout_id="'+sell_layout_id+'" system_id="'+system_id+'">'+
                            '<td class="details-control">'+td1+'</td>'+
                            '<td>'+td2 + td_bed + pledge + '</td>'+
                            //'<td>'+td_bed+'</td>'+
                        '</tr>';
                return html;
            };
            bookEdit.getRoomSellLayout = function() {
                //if(BookEditClass.roomSellLayout != '') return BookEditClass.roomSellLayout;
                var roomSellLayout = BookEditClass.roomSellLayout;
                $('#sell_layout').children('option').each(function(index, element) {
                    var room_layout_id = $(this).attr('room_layout');
                    if(room_layout_id >= 0) {
                        roomSellLayout[this.value] = {};
                        roomSellLayout[this.value]['room_layout_id'] = $(this).attr('room_layout');
                        roomSellLayout[this.value]['sell_id'] = this.value;
                        roomSellLayout[this.value]['room_sell_layout_name'] = $.trim($(this).text());
                    }
                })
                BookEditClass.roomSellLayout = roomSellLayout;
                return roomSellLayout;
            };
            bookEdit.getSellLayoutSystem = function() {
                var priceSystem = BookEditClass.priceSystem;
                var allSellLayoutSystem = {};
                for(sell_id in priceSystem) {
                    for(system_id in priceSystem[sell_id]) {
                        allSellLayoutSystem[system_id] = {};
                        allSellLayoutSystem[system_id]['room_layout_price_system_name'] = priceSystem[sell_id][system_id];
                    }
                }
                return allSellLayoutSystem;
            };
            bookEdit.computeCheckDate = function() {
                var outDate = new Date($('#room_check_out').val());
                var inDate = new Date($('#room_check_in').val());
                var outDateTime =new Date($('#room_check_out').val().substr(0, 10) + ' 00:00:00');
                var inDateTime  =new Date($('#room_check_in').val().substr(0, 10) + ' 00:00:00');
                var days = Math.floor((outDateTime.getTime() - inDateTime.getTime())/(24*3600*1000));
                var halfPrice = $('#half_price').val().substr(0, 2) - 0;
                var checkout = '<%$hotel_checkout%>';
                //标准结算日期
                var  balance_date = new Date($('#room_check_out').val());
                balance_date.setDate(balance_date.getDate() - 1);
                if((outDate.getHours() - 0) > halfPrice) {
                    //算1天
                    days = days - 0 + 1;
                    //加1天的结算日期
                    balance_date.setDate(balance_date.getDate() + 1);
                }
                if((outDate.getHours() - 0) <= halfPrice && (outDate.getHours() - 0) > checkout.substr(0, 2)) {
                    //算0.5天
                    days = days - 0 + 0.5;
                    //加0.5天的结算日期
                    balance_date.setDate(balance_date.getDate() + 1);
                }
                $('#book_days_total').val(days);
                var day = balance_date.getDate();
                day = (day - 0) < 10 ? '0' + day : day;
                var month = balance_date.getMonth() + 1;
                month = month < 10 ? '0' + month : month;
                $('#balance_date').val(balance_date.getFullYear() + '-' + month + '-' + day);
            };
            $('#room_rate_calculation').click(function(e) {
                bookEdit.computeBookPrice();
            });
            //计算价格
            bookEdit.computeBookPrice = function() {
                if(typeof($('.layout_price')) == 'undefined') return;
                //var thenRoomPrice = BookEditClass.thenRoomPrice;
                var tempRoomPrice = BookEditClass.tempRoomPrice;
                tempRoomPrice['room'] = {};
                var balance_date = new Date($('#balance_date').val());//结算日
                var balance_date_time = balance_date.getTime();
                var days = $('#book_days_total').val();//总共住多少天
                var discount = $('#discount').val();
                var book_discount_type = $('#book_discount_type').val();
                var is_half = days.indexOf(".") > 0 ? true : false;
                var select_room = {};
                var room_price = bed_price =  0;//客房价格 押金 需要的服务费
                var room_layout = $('#sell_layout').find('option:selected').attr('room_layout');
                var system_id = $('#price_system').val();
                var sell_id = $('#sell_layout').val();
                var room_key = sell_id + '-' + room_layout + '-' + system_id;
                tempRoomPrice['room']['room_key'] = room_key;
                tempRoomPrice['room']['rdate'] = {};
                $('.layout_price').each(function(index, element) {
                    //房型价格  thenRoomPrice 正在订房当时的价格
                    var rdate = $(this).attr('rdate');
                    var now_date = new Date(rdate);
                    var now_date_time = now_date.getTime();
                    if(now_date_time <= balance_date_time) {
                        var price = $(this).val() - 0;
                        tempRoomPrice['room']['rdate'][rdate] = price;//房费
                        if(now_date_time == balance_date_time && is_half) {
                            price = price * 0.5;
                        }
                        room_price += price;
                    }
                });
                if(book_discount_type == 0) {
                    room_price = room_price * discount / 100;
                } else if(book_discount_type == 1) {
                    room_price = room_price - discount * Math.floor($('#book_days_total').val());
                }
                tempRoomPrice['room']['room_price'] = room_price;//总房价
                tempRoomPrice['room']['cash_pledge'] = $('.pledge_price input').first().val();//押金
                tempRoomPrice['room']['room_id'] = $('#select_room').val();//房号
                tempRoomPrice['room']['total_room_rate'] = room_price;//总房价
                tempRoomPrice['room']['totle_price'] = room_price;//总价
                tempRoomPrice['room']['room_check_in'] = $('#room_check_in').val();
                tempRoomPrice['room']['room_check_out'] = $('#room_check_out').val();
                tempRoomPrice['room']['book_days_total'] = $('#book_days_total').val();
                tempRoomPrice['room']['discount'] = $('#discount').val();
                var val = $('#extra_bed select').val();
                tempRoomPrice['room']['extra_bed'] = val;
                if(val > 0) {
                    tempRoomPrice['bed'] = {};
                    tempRoomPrice['bed']['room_key'] = room_key;
                    var room_id = $('#extra_bed select').attr('room');
                    if(tempRoomPrice['room']['room_id'] == room_id) {
                        tempRoomPrice['bed']['beddate'] = {};
                        $('.extra_bed_price').each(function(index, element) {
                            //房型价格
                            //相同room_layout
                            var beddate = $(this).attr('beddate');
                            var now_date = new Date(beddate);
                            var now_date_time = now_date.getTime();
                            if(now_date_time <= balance_date_time) {
                                var price = $(this).val() - 0;
                                tempRoomPrice['bed']['beddate'][beddate] = price;//加床
                                if(now_date_time == balance_date_time && is_half) {
                                    //price = price * 1; 加床算全价
                                }
                                bed_price = price * val + bed_price;
                            }
                        });
                        tempRoomPrice['bed']['bed_price'] = bed_price;
                        tempRoomPrice['bed']['room_id'] = $('#select_room').val();
                        tempRoomPrice['room']['totle_price'] = tempRoomPrice['room']['totle_price'] + bed_price;
                    }
                }
                $('#total_extra_bed_price').val(bed_price);
                $('#total_room_rate').val(tempRoomPrice['room']['total_room_rate']);
                $('#cash_pledge').val(tempRoomPrice['room']['cash_pledge']);
                BookEditClass.tempRoomPrice = tempRoomPrice;
            };
            bookEdit.saveAddRoom = function() {
                var tempPrice = $('#total_room_rate').val();
                var room_id = $('#select_room').val();
                if(tempPrice == '' || tempPrice == 0) {
                    $('#modal_info').modal('show');
                    $('#modal_info_message').html("抱歉，请计算房费！");
                    return;
                }
                if(room_id == '') {
                    $('#modal_info').modal('show');
                    $('#modal_info_message').html("抱歉，没有选择房间或者无房！");
                    return;
                }
                var check_in = $('#room_check_in').val();var check_out = $('#room_check_out').val();
                var sell_layout_name = $.trim($('#sell_layout').find('option:selected').text());
                var price_system_name = $.trim($('#price_system').find('option:selected').text());
                var room_name = $.trim($('#select_room').find('option:selected').text());
                var extra_bed = $('#select_room').find('option:selected').attr('max_extra_bed') + ' / ' + $.trim($('#extra_bed').find('option:selected').text());
                var max_people = $('#select_room').find('option:selected').attr('max_people');
                var max_children = $('#select_room').find('option:selected').attr('max_children');
                var tempRoomEdit = BookEditClass.tempRoomEdit;
                var code_text = '<%$arrayLaguage["new_add_room"]["page_laguage_value"]%>';
                if(tempRoomEdit['edit'] == 'change_room') code_text = '换房';
                if(tempRoomEdit['edit'] == 'continued_room') code_text = '续住';
                var room_rate = $('#total_room_rate').val();
                var cash_pledge = $('#cash_pledge').val();
                var html = '<tr>';
                html += '<td>'+sell_layout_name+'</td>'
                       +'<td>'+price_system_name+'</td><td>'+room_name+'</td>'
                       +'<td>'+extra_bed+'</td><td>'+check_in+'</td><td>'+check_out+'</td>'
                       +'<td><code class="fr">'+code_text+'</code></td>'
                       +'<td>'+room_rate+'</td>'
                       +'<td>'+cash_pledge+'</td>'
                       +'<td></td><td></td>'
                       +'<td><a id="cancel_add_room'+room_id+'" class="btn btn-warning btn-mini fr">'
                       +'<i class="am-icon-minus-circle"></i><%$arrayLaguage["cancel"]["page_laguage_value"]%></a></td></tr>';
                $('#add_room_tr').before(html);
                $('#sell_layout').val('');$('#room_layout_data').hide('fast');$('#room_layout_data_price').hide('fast');
                $('#cancel_add_room'+room_id).click(function(e) {
                    $(this).parent().parent().remove();
                    BookEditClass.thenRoomPrice[room_id] = '';
                    BookEditClass.room_info_id[room_id] = '';//移除使用房间
                    $('.change_room,.continued_room,.check_out_room').each(function(index, element) {
                        if($(this).parent().attr('room_id') == room_id) {
                            $(this).parent().prev().prev().html('<i class="am-icon-circle-o"></i> 管理');
                            return;
                        }
                    });
                    bookEdit.computeAllBookPrice();
                });
                var tempRoomPrice = BookEditClass.tempRoomPrice;
                var room_id = tempRoomPrice['room']['room_id'];
                BookEditClass.thenRoomPrice[room_id] = {};
                BookEditClass.thenRoomPrice[room_id]['data'] = tempRoomPrice;
                BookEditClass.thenRoomPrice[room_id]['type'] = BookEditClass.tempRoomEdit['edit'];
                BookEditClass.thenRoomPrice[room_id]['type_room_id'] = BookEditClass.tempRoomEdit['room_id'];
                BookEditClass.thenRoomPrice[room_id]['type_book_id'] = BookEditClass.tempRoomEdit['book_id'];
                //
                BookEditClass.tempRoomPrice = {};
                BookEditClass.tempRoomEdit = {};
                BookEditClass.tempRoomEdit['edit'] = '';
                BookEditClass.room_info_id[room_id] = tempRoomPrice['room']['room_id'];//这个房号已经使用
            };
            bookEdit.saveAddService = function(_this) {
                var id = $('#serviceItem').val();
                if(id == '') return;
                var tempServicePrice = BookEditClass.tempServicePrice;
                var service_price = $('#service_price').val();var service_num = $('#service_num').val();
                var discount = $('#service_discount').val();
                var service_total_price = $('#service_total_price').val();
                var key = 'service_' + id + '-' + discount;
                if(typeof(tempServicePrice[key]) == 'undefined' || tempServicePrice[key] == '') {
                    tempServicePrice[key] = {};
                    var html = '<tr id="'+key+'">';
                    html += '<td>'+$.trim($('#serviceItem').find('option:selected').text())+'</td>'
                           +'<td>'+service_price+'</td>'
                           +'<td>'+service_num+'</td><td>'+discount+'</td>'
                           +'<td>'+service_total_price+'</td>'
                           +'<td><code>新增</code><a id="cancel_'+key+'" class="btn btn-warning btn-mini fr" data-id="'+key+'"><i class="am-icon-minus-circle"></i> 取消</a></td>'
                           +'</tr>';
                    $('#add_service_tr').before(html);
                    $('#cancel_'+key).click(function(e) {
                        $('#'+key).remove();
                        BookEditClass.tempServicePrice[key] = '';
                    });
                    tempServicePrice[key]['id'] = id;
                    tempServicePrice[key]['service_price'] = service_price;
                    tempServicePrice[key]['service_num'] = service_num;
                    tempServicePrice[key]['service_discount'] = discount;
                    tempServicePrice[key]['service_total_price'] = service_total_price;
                    BookEditClass.tempServicePrice = tempServicePrice;
                } else {
                    tempServicePrice[key]['service_num'] = (tempServicePrice[key]['service_num'] - 0) + (service_num - 0);
                    tempServicePrice[key]['service_total_price'] = (tempServicePrice[key]['service_total_price'] - 0) + (service_total_price - 0) ;
                    $('#'+key).find('td').eq(2).text(tempServicePrice[key]['service_num']);
                    $('#'+key).find('td').eq(4).text(tempServicePrice[key]['service_total_price']);
                    if(tempServicePrice[key]['service_total_price'] <= 0) {
                        $('#'+key).remove();
                        tempServicePrice[key] = '';
                    }
                    BookEditClass.tempServicePrice = tempServicePrice;
                }
                $('#add_service_tr').hide('fast');
                bookEdit.computeAllBookPrice();                    
            };
            bookEdit.computeServicePrice = function(_this) {
                $('#service_total_price').val($('#service_price').val() * $('#service_num').val()* $('#service_discount').val()/100);
            };
            bookEdit.cancelAddService = function(_this) {
                var id = $(_this).attr('data-id');
                $('#'+id).remove();
                
            };
            bookEdit.computeAllBookPrice = function() {
                var thenRoomPrice = BookEditClass.thenRoomPrice;
                var tempServicePrice = BookEditClass.tempServicePrice;
                var all_totle_price = 0;var all_cash_pledge = 0;var book_extra_bed_price = 0;
                for(var room_id in thenRoomPrice) {
                    if(typeof(thenRoomPrice[room_id]['data']) == 'undefined') continue;
                    all_totle_price += (thenRoomPrice[room_id]['data']['room']['totle_price'] - 0);
                    all_cash_pledge += (thenRoomPrice[room_id]['data']['room']['cash_pledge'] - 0);
                    if(typeof(thenRoomPrice[room_id]['data']['bed']) != 'undefined' 
                        && room_id == thenRoomPrice[room_id]['data']['bed']['room_id']) {
                        book_extra_bed_price += (thenRoomPrice[room_id]['data']['bed']['bed_price'] - 0);
                    }
                }
                $('#book_room_rate').val(all_totle_price);$('#book_total_cash_pledge').val(all_cash_pledge);
                $('#book_extra_bed_price').val(book_extra_bed_price);
                var need_service_price = 0;
                for(var key in tempServicePrice) {
                    //need_service_price
                    need_service_price += (tempServicePrice[key].service_total_price - 0);
                    
                }
                $('#need_service_price').val(need_service_price);
                $('#total_price').val((all_totle_price - 0) +　(all_cash_pledge - 0)　+　(book_extra_bed_price - 0) + (need_service_price - 0));
                $('#prepayment').val((all_cash_pledge - 0) + (need_service_price - 0));//预付金 = 押金+附加服务费
                $('#add_room_tr').hide('fast');
            };
            bookEdit.saveBookRoom = function() {
                if($('#payment_type').val() == '') {
                    $('#modal_info').modal('show');
                    $('#modal_info_message').html('请选择支付方式！');
                    return;
                }
                var thenRoomPrice = BookEditClass.thenRoomPrice;
                var tempServicePrice = BookEditClass.tempServicePrice;
                var data = 'data='+JSON.stringify(thenRoomPrice) + '&' + $("#re_book").serialize() + '&service='+JSON.stringify(tempServicePrice);
                $.post('<%$saveBookInfoUrl%>&act=savebook', data, function(result) {
                    data = result;
                    if(data.success == 1) {
                        $('#modal_success').modal('show');
                        $('#modal_success_message').html(data.message);
                    } else {
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                }, 'json')
            };
            bookEdit.bookRoomAddUser = function() {
                var room_info = BookEditClass.room_info;
                var max_man = 0;var max_num = 0;
                var selectHtml = '<select name="check_in_room_num"><option value=""><%$arrayLaguage["please_select"]["page_laguage_value"]%></option>';
                var have_check_in = {};
                have_check_in['adult'] = {};have_check_in['children'] = {};have_check_in['extra_bed'] = {};
                $('.check_in_info').each(function(index, element) {
                    var room_id = $(this).attr('room_id');
                    var user_lodger_type = $(this).attr('user_lodger_type');
                    if(typeof(have_check_in[user_lodger_type][room_id]) == 'undefined') {
                        have_check_in[user_lodger_type][room_id] = 1;
                    } else {
                        have_check_in[user_lodger_type][room_id]++;
                    }
                });
                for(var room_id in room_info) {
                    var name = $.trim(room_info[room_id].name);
                    if(name == '') continue;
                    max_man = room_info[room_id].max_people - 0;// + (room_info[room_id].extra_bed - 0);
                    for(var i = 0; i < max_man; i++) {
                        //if(have_check_in['adult'][room_id]);
                        if(typeof(have_check_in['adult'][room_id]) == 'undefined') {
                            selectHtml += '<option value="'+room_id+'" type="adult">'+name+'-<%$arrayLaguage["adult"]["page_laguage_value"]%></option>';
                        } else {
                            if(have_check_in['adult'][room_id] <= 0) 
                                selectHtml += '<option value="'+room_id+'" type="adult">'+name+'-<%$arrayLaguage["adult"]["page_laguage_value"]%></option>';
                            have_check_in['adult'][room_id]--;
                        }
                        max_num++;
                    }
                    max_man = (room_info[room_id].max_children - 0);
                    for(var i = 0; i < max_man; i++) {
                        if(typeof(have_check_in['children'][room_id]) == 'undefined') {
                            selectHtml += '<option value="'+room_id+'" type="children">'+name+'-<%$arrayLaguage["children"]["page_laguage_value"]%></option>';
                        } else {
                            if(have_check_in['children'][room_id] <= 0) 
                                selectHtml += '<option value="'+room_id+'" type="children">'+name+'-<%$arrayLaguage["children"]["page_laguage_value"]%></option>';
                            have_check_in['children'][room_id]--;
                        }
                        max_num++;
                    }
                    max_man = room_info[room_id].extra_bed - 0;
                    for(var i = 0; i < max_man; i++) {
                        if(typeof(have_check_in['extra_bed'][room_id]) == 'undefined') {
                            selectHtml += '<option value="'+room_id+'" type="extra_bed">'+name+'-<%$arrayLaguage["extra_bed"]["page_laguage_value"]%></option>';
                        } else {
                            if(have_check_in['extra_bed'][room_id] <= 0) 
                                selectHtml += '<option value="'+room_id+'" type="extra_bed">'+name+'-<%$arrayLaguage["extra_bed"]["page_laguage_value"]%></option>';
                            have_check_in['extra_bed'][room_id]--;
                        }
                        max_num++;
                    }
                    max_man = 0;
                }
                selectHtml += '</select>';
                $('#check_in_room_num').html(selectHtml);
                $('#add_user_tr').show('slow');
            }
            return bookEdit;
        },
        leapYear: function(year){
            var isLeap = year%100==0 ? (year%400==0 ? 1 : 0) : (year%4==0 ? 1 : 0);
            return new Array(31,28+isLeap,31,30,31,30,31,31,30,31,30,31);
        }
    }
    var bookEdit = BookEditClass.instance();
    bookEdit.initParameter();
    bookEdit.init();
});//console.log($('#add_user_tr'));
$(function(){
    $('body #rate_calculation').each(function(){
        $(this).dragging({
            move : 'both',
            randomPosition : false
        });
    });
});
</script>