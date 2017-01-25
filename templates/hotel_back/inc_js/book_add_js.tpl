<script language="javascript">
$(document).ready(function(){
	//日历
	$.datetimepicker.setLocale('ch');
	var dateToDisable = new Date();
	dateToDisable.setDate(dateToDisable.getDate() - 1);
	$('#book_check_in').datetimepicker({theme:'dark', format: 'Y-m-d H:i:s', formatDate:'Y-m-d H:i:s',
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
            var time_end_date = new Date($('#book_check_out').val());
            if(time_end_date.getTime() < nextDate.getTime()) {
                $('#book_check_out').val(nextDate);
                $('#book_check_out').datetimepicker({value:nextDate});
            }
            computeCheckDate($('#book_check_out').val());
            bookEdit.computeBookPrice(false);
        }
	});
	$('#book_check_out').datetimepicker({theme:'dark', format: 'Y-m-d H:i:s', formatDate:'Y-m-d H:i:s',
		beforeShowDay: function(date) {
			//var dateToDisable = new Date($('#book_check_in').val());
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
            computeCheckDate(this.getValue());
            bookEdit.computeBookPrice(false);
        }
	});
	//日历 时间控制
	$('#book_order_retention_time').datetimepicker({
		theme:'dark',format:'Y-m-d H:i:00',step:30
	});
    $('#half_price').datetimepicker({
		datepicker:false,format:'H:i',step:30
	});
    
    function computeCheckDate(computeDate) {
        var outDate = new Date(computeDate);
        var inDate = new Date($('#book_check_in').val());
        var outDateTime =new Date(outDate.getFullYear() + '-' + (outDate.getMonth() - 0 + 1) + '-' + outDate.getDate() + ' 00:00:00');
        var itDateTime =new Date(inDate.getFullYear() + '-' + (inDate.getMonth() - 0 + 1) + '-' + inDate.getDate() + ' 00:00:00');
        var days = Math.floor((outDateTime.getTime() - itDateTime.getTime())/(24*3600*1000));
        var halfPrice = $('#half_price').val().substr(0, 2) - 0;
        var checkout = '<%$hotel_checkout%>';
        //标准结算日期
        var  balance_date = new Date(computeDate);
        balance_date.setDate(balance_date.getDate() - 1);
        if((outDate.getHours() - 0) > halfPrice) {
            //算1天
            outDateTime.setDate(outDateTime.getDate() + 1);
            days = days - 0 + 1;
            //加1天的结算日期
            balance_date.setDate(balance_date.getDate() + 1);
        }
        if((outDate.getHours() - 0) <= halfPrice && (outDate.getHours() - 0) > checkout.substr(0, 2)) {
            //算0.5天
            outDateTime.setDate(outDateTime.getDate() + 1);
            days = days - 0 + 0.5;
            //加0.5天的结算日期
            balance_date.setDate(balance_date.getDate() + 1);
        }
        $('#book_days_total').val(days);
        
        var day = outDateTime.getDate();
        day = (day - 0) < 10 ? '0' + day : day;
        var month = outDateTime.getMonth() + 1;
        month = month < 10 ? '0' + month : month;
        $('#max_date').val(outDateTime.getFullYear() + '-' + month + '-' + day);
        
        var day = balance_date.getDate();
        day = (day - 0) < 10 ? '0' + day : day;
        var month = balance_date.getMonth() + 1;
        month = month < 10 ? '0' + month : month;
        $('#balance_date').val(balance_date.getFullYear() + '-' + month + '-' + day);
        bookEdit.computeBookPrice(true);
    }
	
//});//add_attr_classes
//$(document).ready(function(){

    var contact_validate = $("#contact_form").validate({
		rules: {
			contact_name: {required: true},
			contact_mobile: {isMobile: true},
            contact_email: {email: true},
		},
		messages: {
			contact_name:"请填写联系人",
			contact_mobile:"请填写正确的移动电话号码",
            contact_email:"请填写正确的email"
		},
		errorClass: "text-error",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('success');
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		},
		submitHandler: function() {

		}
	});
	//插件
	jQuery.validator.addMethod("pre-authorized", function(value, element) {
		var father_id = $('#payment_type_father').val();//4
		var amount = $('#book_credit_authorized_amount').val();var number = $('#book_credit_authorized_number').val();
		var days = $('#book_credit_authorized_days').val();var card_number = $('#book_credit_card_number').val();
		var pre_authorized = true;
		if(father_id == 4) {
			if(amount == '' || number == '' || days == '' || card_number == '') pre_authorized = false;
		}
		return this.optional(element) || pre_authorized;
	}, "请正确填写预授权相关选项！");
	var book_validate = $("#book_form").validate({
		rules:{
			book_type_id:{required:true},book_discount:{required:true},
			book_check_in:{required:true},book_check_out:{required:true},
			book_total_price:{required:true},payment:{required:true},
			payment_type:{required:true,"pre-authorized":true},
			is_pay:{required:true}
		},
		messages: {
			book_type_id:"请选择来源",
			book_discount:"请填写折扣",
			book_total_price:"",
			payment:"",
			//payment_type:"",
			is_pay:"",
		},
		errorClass: "text-error",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('success');
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		},
		submitHandler: function() {
            if(new Date($('#book_check_out').val()) <= new Date($('#book_check_in').val())) {
                $('#modal_fail').modal('show');
                $('#modal_fail_message').html("抱歉，这个时间不正确！");
                return false;
            }
			if(contact_validate.form()) {
				$('#book_contact_mobile').val($('#contact_mobile').val());
				$('#book_contact_name').val($('#contact_name').val());
                $('#book_contact_email').val($('#contact_email').val());
                var have_room  = false;
                $('#room_layout_html input').each(function(index, element) {
                    if($(this).attr('layout') == 'room'){
                        have_room = true;
                        return;
                    }
                });
                if(!have_room) {
                    $('#modal_info_message').html("没有选择客房！");
                    $('#modal_info').modal('show');
                    return;
                }
                var user_lodger = {};
                var i = 0;
                $('.bookSelectRoom').each(function(index, element) {
                    //user_lodger[i] = {};
                    var type = $(this).find('option:selected').attr('type');//var room_id = $(this).val();
                    user_lodger[i] = type;
                    i++;
                });
				var param = $("#book_form").serialize();
				$.ajax({
					url : '<%$book_url%>',type : "post",dataType : "json",
                    data: param+'&thenRoomPrice='+JSON.stringify(BookEditClass.thenRoomPrice)+'&user_lodger='+JSON.stringify(user_lodger) + '&layout_corp=' + BookEditClass.layout_corp,
					success : function(result) {
                        data = result;
						if(data.success == 1) {
							room_layout_id = data.itemData.room_layout_id;
							 $('#modal_success').modal('show');
							 $('#modal_success_message').html(data.message);
							 
						} else {
							$('#modal_fail').modal('show');
							$('#modal_fail_message').html(data.message);
						}
					}
				});
			}
		}
	});
	//$('.book_form_step1 .book_form_step2').hide();
	
	//搜索房型
	table = $('#room_layout').DataTable({
		paging: false
	});
    
    var BookEditClass = {
        hotel_service: {},book_discount_list: {},bookSelectRoom: {},bookNeed_service:{},lastDate:{},thenRoomPrice:{},sell_layout_list:{},
        priceSystem: {},roomSellLayout: {},payment_type: {},layout_corp: 0,
        hotelCheckDate: {'hotel_checkin':'<%$hotel_checkin%>', 'hotel_checkout':'<%$hotel_checkout%>'},
	    max_man: 0,//最多人数
        BookUser_num: 1,
        instance: function() {
            var bookEdit = {};
            bookEdit.initParameter = function() {
                BookEditClass.hotel_service[-1] = 1;
                BookEditClass.weekday=new Array(7);
                BookEditClass.weekday[0]="日";BookEditClass.weekday[1]="一";BookEditClass.weekday[2]="二"
                BookEditClass.weekday[3]="三";BookEditClass.weekday[4]="四";BookEditClass.weekday[5]="五"
                BookEditClass.weekday[6]="六";
                BookEditClass.orientations=new Array(7);
                BookEditClass.orientations['east']='东';BookEditClass.orientations['south']='南';BookEditClass.orientations['west']='西';
                BookEditClass.orientations['north']='北';
                BookEditClass.orientations['southeast']='东南';BookEditClass.orientations['northeast']='东北';
                BookEditClass.orientations['southwest']='西南';BookEditClass.orientations['northwest']='西北';
                BookEditClass.orientations['no']='无';
                //
                bookEdit.groupSellLayoutSystem();
            },
            bookEdit.init = function() {
                $('.edit_checkbox').click(function(e) {
                    hotel_server_id = $(this).attr('data-id');
                    var hotel_service = BookEditClass.hotel_service;
                    var length = 0;
                    for(i in hotel_service) {if(hotel_service[i] == 1) length++;}
                    if(typeof(hotel_service[hotel_server_id]) == 'undefined' || hotel_service[hotel_server_id] == '') {
                        $(this).find('.edit_btn').addClass('am-icon-check-square-o');
                        $(this).find('.edit_btn').removeClass('am-icon-square-o');
                        hotel_service[hotel_server_id] = 1;
                    } else {
                        if(length <= 1) {
                            $('#modal_info').modal('show');$('#modal_info_message').html('不能全部取消包含服务，必须包含一项服务！');
                            return;
                        }
                        $(this).find('.edit_btn').removeClass('am-icon-check-square-o');
                        $(this).find('.edit_btn').addClass('am-icon-square-o');
                        hotel_service[hotel_server_id] = '';
                    }
                });
                $('#server_-1').click(function(e) {
                    var hotel_service = BookEditClass.hotel_service;
                    var length = 0;
                    for(i in hotel_service) {
                        if(hotel_service[i] == 1) length++;
                    }
                    if(length <= 1) {
                        $('#modal_info').modal('show');$('#modal_info_message').html('不能全部取消包含服务，必须包含一项服务！');
                        return;
                    }
                    BookEditClass.hotel_service[-1] = 0;
                    $(this).parent().remove();
                });
                $('#service_type').change(function(e) {
                    hotel_server_id = this.value;
                    var hotel_service = BookEditClass.hotel_service;
                    if(typeof(hotel_service[hotel_server_id]) == 'undefined' || hotel_service[hotel_server_id] == 0) {
                        var html = ' <li><i class="am-icon-check-square"></i>'+$(this).find("option:selected").text()
                                  +'<i id="server_'+hotel_server_id+'" class="am-icon-trash-o am-red-E43737 service_type_del"></i></li>';
                        $('#service_type').after(html);
                        hotel_service[hotel_server_id] = 1;
                        $('#server_'+hotel_server_id).click(function(e) {
                            var hotel_service = BookEditClass.hotel_service;
                            var length = 0;
                            for(i in hotel_service) {
                                if(hotel_service[i] == 1) length++;
                            }
                            if(length <= 1) {
                                $('#modal_info').modal('show');
                                $('#modal_info_message').html('不能全部取消包含服务，必须包含一项服务！');
                                return;
                            }
                            //var hotel_server_id = $(this).attr('server_id');
                            //hotel_service[hotel_server_id] = 0;
                            BookEditClass.hotel_service[hotel_server_id] = 0;
                            $(this).parent().remove();
                        });
                    }
                });
                //联系信息事件
                //$('#contact_mobile,#contact_name,#contact_email').bind("keyup") = 
                $('#begin_book').bind("click", function(e) {
                    if($('#contact_mobile').val().length == 11 || $('#contact_email').val() != '') {
                        $('#modal_loading').show();
                        $.ajax({url : "<%$searchBookInfoUrl%>&search=searchUserMemberLevel",type : "post",
                           dataType : "json",
                           data: "book_contact_mobile=" + $('#contact_mobile').val() + "&book_contact_email=" + $('#contact_email').val(),
                           success : function(result) {
                               $('#modal_loading').hide();$('.book_form_step1').show();
                               data = result;BookEditClass.layout_corp = 0;
                               if(data.success == 1) {
                                   if(data.itemData != null && data.itemData != '' && data.itemData != 'null') {
                                       $('#book_discount_id,.book_discount_id').remove();
                                       $('#book_type_id').val(data.itemData.book_type_id);
                                       $('#discount').val(data.itemData.book_discount);
                                       if(data.itemData.book_discount_type == 0) {$('#discount_type').text('折扣');$('#book_discount_type').val('0');}
                                       if(data.itemData.book_discount_type == 1) {$('#discount_type').text('直减'); $('#book_discount_type').val('1');}
                                       if(data.itemData.book_discount_type == 2) {
                                           $('#discount_type').html('<code>协议价</code>'); $('#book_discount_type').val(2);$('#discount').hide();
                                           BookEditClass.layout_corp = data.itemData.layout_corp;
                                       }
                                       if(data.itemData.agreement_company_name != '') {
                                           var book_discount_id = ' <input readonly id="book_discount_id" value="'
                                                + data.itemData.book_discount_name + data.itemData.agreement_company_name+'" type="text" class="input-large"/> '
                                                +' <input name="book_discount_id" value="'
                                                + data.itemData.book_discount_id+'" type="hidden" class="book_discount_id" /> ';
                                           $('#book_type_id').after(book_discount_id);
                                       } else {
                                            var book_discount_id = ' <input readonly id="book_discount_id" value="'
                                                + data.itemData.book_discount_name+'" type="text" class="input-large"/> '
                                                +' <input name="book_discount_id" value="'
                                                + data.itemData.book_discount_id+'" type="hidden" class="book_discount_id" /> ';
                                            $('#book_type_id').after(book_discount_id);
                                       }
                                   } else {
                                       $('#book_discount_id,.book_discount_id').remove();$('#discount_type').text('折扣');
                                       $('#book_type_id').val('');$('#discount').val(100);$('#discount').show();
                                   }
                                   //计算价格
                                   bookEdit.computeBookPrice(true);
                               } else {
                                   $('#modal_fail').modal('show');
                                   $('#modal_fail_message').html(data.message);
                               }
                           }
                         });
                    }
                });
                //协议公司
                var book_discount_list = BookEditClass.book_discount_list;
                $('#book_type_id').change(function(e) {
                    $('#book_discount_id,.book_discount_id,#order_number_ourter').remove();
                    var book_type_id = $(this).val();
                    var booktype = $(this).find('option:selected').attr('booktype')
                    if(typeof(book_discount_list[book_type_id]) == 'undefined') {
                        $.getJSON('<%$searchBookInfoUrl%>&search=discount&book_type_id='+book_type_id, function(result) {
                            data = result;
                            if(data.itemData != null && data.itemData != '') {
                                var discount_html = '<select name="book_discount_id" id="book_discount_id" class="span2 book_discount_id select_discount">';
                                var option = '';
                                for(i in data.itemData) {
                                    option += '<option value="'+data.itemData[i].book_discount_id+'" type="'+data.itemData[i].book_discount_type+'" '
                                           +'layout_corp="'+data.itemData[i].layout_corp+'">'
                                           +data.itemData[i].book_discount_name + data.itemData[i].agreement_company_name +'</option>';
                                    book_discount_list[data.itemData[i].book_discount_id + '_0'] = data.itemData[i].book_discount;
                                    if(i == 0) {
                                        $('#discount').val(data.itemData[i].book_discount);
                                        book_discount_list[book_type_id + '_'] = data.itemData[i].book_discount;
                                        BookEditClass.layout_corp = data.itemData[i].layout_corp;
                                    }
                                }
                                discount_html += option + '</section>';
                                book_discount_list[book_type_id] = discount_html;
                                //$('#book_type_id_div').after(discount_html);
                                $('#book_discount_id_div').html(discount_html);
                                $('.select_discount').change(function(e) {
                                    $('#discount').val(book_discount_list[$(this).val() + '_0']);
                                    bookEdit.setDiscountHtml(this);
                                    BookEditClass.layout_corp = $(this).find('option:selected').attr('layout_corp');
                                    bookEdit.computeBookPrice(true);
                                })
                                //var type = $('.select_discount').find('option:selected').attr('type');
                                bookEdit.setDiscountHtml(document.getElementById('book_discount_id'));
                            } else {
                                book_discount_list[book_type_id] = '';
                                book_discount_list[book_type_id + '_'] = 100;
                                $('#discount').val(100);
                                $('#discount_type').text('折扣');$('#book_discount_type').val('0');$('#discount').show();
                                BookEditClass.layout_corp = 0;
                            }
                            if(booktype == 'OTA') 
                                $('#book_type_id').after('<span id="order_number_ourter"> <%$arrayLaguage["order_number_ourter"]["page_laguage_value"]%> : <input  name="book_order_number_ourter" value="" class="input-medium" type="text"></span>');
                            bookEdit.computeBookPrice(true);
                            $('#book_discount_id').select2();
                        })
                    } else {
                        //$('#book_type_id').after(book_discount_list[book_type_id]);
                        $('#book_discount_id_div').html(book_discount_list[book_type_id]);
                        $('.select_discount').change(function(e) {
                            $('#discount').val(book_discount_list[$(this).val() + '_0']);
                            bookEdit.setDiscountHtml(this);
                            BookEditClass.layout_corp = $(this).find('option:selected').attr('layout_corp');
                            bookEdit.computeBookPrice(true);
                        })
                        $('#discount').val(book_discount_list[book_type_id + '_']);
                        bookEdit.setDiscountHtml(document.getElementById('book_discount_id'));
                        BookEditClass.layout_corp = $('.select_discount').find('option:selected').attr('layout_corp');
                        if(booktype == 'OTA') 
                            $('#book_type_id').after('<span id="order_number_ourter"> <%$arrayLaguage["order_number_ourter"]["page_laguage_value"]%> : <input name="book_order_number_ourter" value="" class="input-medium" type="text"></span>');
                        bookEdit.computeBookPrice(true);
                        $('#book_discount_id').select2();
                    }
                });
                //搜索客房价格
                $('#search_room_layout').click(function(e) {
                    var i = 0;
                    var sell_layout_list = BookEditClass.sell_layout_list;
                    for(key in sell_layout_list) {
                        if(sell_layout_list[key] != '') i++;
                    }
                    if(i == 0) {
                        $('#modal_info').modal('show');
					    $('#modal_info_message').html('请选择房型！');
                        return;
                    }
                    $('#contact_form').submit();
                    if(contact_validate.form()) {
                        bookEdit.ajaxGetRoomLayout();
                    }
                });
                //book_user_room
                //table点击
                $('#room_layout tbody').on('click', 'td.details-control', function () {
                    var _this = this;
                    var system_id = $(_this).parent().attr('system_id');
                    var sell_id = $(this).parent().attr('sell_layout_id');
                    var lastDate = new Date(BookEditClass.lastDate[sell_id+'-'+system_id]);
                    var outDate = new Date($('#book_check_out').val().substr(0,10));
                    if(lastDate.getTime() < outDate.getTime()) {
                        $('#modal_info_message').html('设置的价格日期和离店日期不符合，请设置相符的日期价格!');
                        $('#modal_info').modal('show');
                        return;
                    }
                    var bookSelectRoom = BookEditClass.bookSelectRoom;
                    var tr = $(this).closest('tr');
                    var row = table.row( tr );
                    if ( row.child.isShown() ) {
                        // This row is already open - close it
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        // Open this row 	row.data()
                        $('#modal_loading').show();
                        $.getJSON('<%$searchBookInfoUrl%>&search=searchRoom&room_layout_id='+$(this).parent().attr('room_layout_id')
                                +'&sell_id='+sell_id+'&book_check_in='+$('#book_check_in').val()+'&book_check_out='+$('#book_check_out').val(),
                          function(result){
                            $('#modal_loading').hide();
                            row.child(bookEdit.formatRoomTable(result, system_id, sell_id)).show();
                            row.child().children().addClass('nopadding');
                            row.child().children().attr('id', 'noBodyLeft');
                            tr.addClass('shown');
                            $(_this).parent().next().find('th input:checkbox').click(function() {
                                var checkedStatus = this.checked;
                                var checkbox = $(this).parent().parent().parent().next().find('tr td:first-child input:checkbox');
                                checkbox.each(function() {
                                    if(this.disabled) {
                                    } else {
                                        this.checked = checkedStatus;
                                        var room_id = $(this).val();
                                        var room_layout_id = $(this).attr('room_layout');var room_name = $(this).attr('title');
                                        var max_people = $(this).attr('max_people');var max_children = $(this).attr('max_children');
                                        if (checkedStatus == this.checked) {
                                            $('#room_'+sell_id+'_'+room_id).remove();//room移除
                                            $('#extra_bed_'+sell_id+'_'+room_id).remove();//bed移除
                                            //$('#select_room_'+sell_id+'_'+room_id).remove();
                                            $("#room_data").removeData(room_id);  //移除
                                            //$(this).closest('.checker > span').removeClass('checked');
                                        }
                                        if (this.checked && typeof(room_layout_id) != 'undefined') {
                                            var room_lauout_input = '<input type="hidden" id="room_'+sell_id+'_'+room_id+'" '
                                                          +'name="room_layout_id['+sell_id +'-'+ room_layout_id+'-'+system_id+'][]" value="'+room_id+'" '
                                                          +'layout="room" room_layout="'+room_layout_id+'" system_id="'+system_id+'" sell_id="'+sell_id+'" '
                                                          +'max_people="'+max_people+'" max_children="'+max_children+'" />';
                                            $('#room_layout_html').append(room_lauout_input);
                                            $('#room_data').data(room_id, sell_id+'-'+ system_id);
                                            /*var select_rooms = '<label class="span2 text-left role_modules" id="select_room_'+sell_id+'_'+room_id+'">'
                                                             +$(this).attr('room_name')+' <i class="am-icon-check-circle"></i> </label>';
                                            $('#select_rooms').append(select_rooms);*/
                                            //ExtraBed
                                            bookEdit.setExtraBed(sell_id, system_id, $(this).parent().parent().find('.room_extra_bed'));
                                            bookSelectRoom[room_id] = room_name;
                                            //$(this).closest('.checker > span').addClass('checked');
                                        }
                                        //设置disable
                                        bookEdit.resetRoomStatus(room_id, sell_id, system_id, this.checked);
                                    }
                                });
                                //计算价格
                                bookEdit.computeBookPrice(true);
                            });	
                            
                            $(_this).parent().next().find('td input:checkbox').click(function(e) {
                                var room_id = $(this).val();
                                var room_layout_id = $(this).attr('room_layout');var room_name = $(this).attr('title');
                                var max_people = $(this).attr('max_people');var max_children = $(this).attr('max_children');
                                if(room_id == 'on') return;
                                if (this.checked) {
                                    //选中状态
                                    var room_lauout_input = '<input type="hidden" id="room_'+sell_id+'_'+room_id+'" '
                                                         +'name="room_layout_id['+sell_id +'-'+ room_layout_id+'-'+system_id+'][]" value="'+room_id+'" '
                                                         +'layout="room" room_layout="'+room_layout_id+'" system_id="'+system_id+'" sell_id="'+sell_id+'" '
                                                         +'max_people="'+max_people+'" max_children="'+max_children+'" />';
                                    $('#room_layout_html').append(room_lauout_input);
                                    $('#room_data').data(room_id, sell_id+'-'+ system_id);
                                    /*var select_rooms = '<label class="span2 text-left role_modules" id="select_room_'+sell_id+'_'+room_id+'">'
                                                             +$(this).attr('room_name')+' <i class="am-icon-check-circle"></i> </label>';
                                    $('#select_rooms').append(select_rooms);*/
                                    //ExtraBed
                                    bookEdit.setExtraBed(sell_id, system_id, $(this).parent().parent().find('.room_extra_bed'));
                                    bookSelectRoom[room_id] = room_name;
                                    //设置disable
                                    bookEdit.resetRoomStatus(room_id, sell_id, system_id, this.checked);
                                } else {
                                    $('#room_'+sell_id+'_'+room_id).remove();
                                    $('#extra_bed_'+sell_id+'_'+room_id).remove();
                                    //$('#select_room_'+sell_id+'_'+room_id).remove();
                                    $("#room_data").removeData(room_id);  //移除
                                    //设置disable
                                    bookEdit.resetRoomStatus(room_id, sell_id, system_id, this.checked);
                                }
                                //计算价格
                                bookEdit.computeBookPrice(true);
                            });
                            $('.room_extra_bed').change(function(e) {
                                bookEdit.setExtraBed(sell_id, system_id, this);
                                //计算价格
                                bookEdit.computeBookPrice(true);
                            });
                            $('.book_price').keyup(function(e) {
                                bookEdit.computeBookPrice(true);
                            });
                            //计算价格
                        })
                    }
                });
                $('#need_service').change(function(e) {
                    var thisVal = $(this).val();
                    if(BookEditClass.bookNeed_service[thisVal] == 1 || thisVal == '') return;
                    //bookNeed_service
                    var need_service_id = $('#need_service_id').attr('id');
                    if(typeof(need_service_id) == 'undefined') {
                        $('#need_service_info').append('<ul id="need_service_id"></ul>');
                    }
                    var html = '<li><i class="am-icon-check-square"></i>'+$(this).find("option:selected").attr('title')
                              +' ￥ :  <input class="input-mini service_price" service_id="'+thisVal+'" value="'+$(this).find("option:selected").attr('price')+'" type="text">   '
                              +'数量 : <input class="input-mini" value="1" type="text" id="service_num'+thisVal+'"> 折扣 : <input class="input-mini" value="100" type="text" id="service_discount'+thisVal+'"> '
                              +'<i class="am-icon-trash-o am-red-E43737" id="service_del'+thisVal+'"></i></li>';
                    $('#need_service_id').append(html);
                    $('#service_del'+thisVal).click(function(e) {
                        $(this).parent().remove();
                        BookEditClass.bookNeed_service[thisVal] = 0;
                        bookEdit.computeBookPrice(false);
                    });
                    $('#service_num'+thisVal+',#service_discount'+thisVal).keyup(function(e) {
                        bookEdit.computeBookPrice(false);
                    });
                    BookEditClass.bookNeed_service[thisVal] = 1;
                    bookEdit.computeBookPrice(false);            
                });
                $('#sell_layout').change(function(e) {
                    if(this.value == '') {
                        BookEditClass.sell_layout_list = {};
                        $('.sell_layout_del').parent().remove();
                        return;
                    }
                    var layout_corp = BookEditClass.layout_corp;
                    var sellLayout = {};var select_html = '';
                    var sell_id = $(this).val();
                    var sell_name = $.trim($(this).find("option:selected").text());
                    var priceSystem = BookEditClass.priceSystem;
                    //sellLayout[0] = priceSystem[0];
                    //默认
                    sellLayout[0] = priceSystem[0][0];
                    if(typeof(priceSystem[sell_id]) != 'undefined' && typeof(priceSystem[sell_id][layout_corp]) != 'undefined') {
                        sellLayout[sell_id] = priceSystem[sell_id][layout_corp];
                    }
                    for(sellId in sellLayout) {
                        for(systemID in sellLayout[sellId]) {
                            select_html += '<option sell_id="'+sellId+'" sell_name="'+sell_name+'" value="'+systemID+'">'+sellLayout[sellId][systemID]+'</option>';
                        }
                    }
                    $('#price_system').html(select_html);
                    
                    var sell_del_id = this.value+'-'+1;
                    
                    var text = BookEditClass.sell_layout_list[sell_del_id];
                    if(typeof(text) != 'undefined' && text != '') {
                        return;
                    }
                    //text = 
                    BookEditClass.sell_layout_list[sell_del_id] = sell_name;
                    var html = ' <li data-id="'+this.value+'" data-text="'+sell_name+'"><i class="am-icon-check-circle"></i> '
                              +sell_name+'-'+sellLayout[0][1]
                              +' <i class="am-icon-trash-o am-red-E43737 sell_layout_del" id="sell_del_'+sell_del_id+'"></i></li>';
                    $('#search_room_layout').before(html);
                    $('#sell_del_'+sell_del_id).click(function(e) {
                        BookEditClass.sell_layout_list[sell_del_id] = '';
                        $(this).parent().remove();
                        if($('#select_sell_layout li').size() == 0) {
                            $('#sell_layout').val('');$('#price_system').html('');
                        }
                    });
                    //$('.sell_layout_del').each(function(index, element) {
                        //$(this).click(function(e) {
                            
                        //});
                    //});
                    
                });
                $('#price_system').change(function(e) {
                    var sell_del_id = $(this).find("option:selected").attr('sell_id') + '-' + this.value;
                    var text = BookEditClass.sell_layout_list[sell_del_id];
                    if(typeof(text) != 'undefined' && text != '') {
                        return;
                    }
                    var sell_name = $(this).find("option:selected").attr('sell_name');
                    var price_system_name = $.trim($(this).find("option:selected").text());
                    var html = ' <li><i class="am-icon-check-circle"></i> '
                          +sell_name+'-'+price_system_name
                          +' <i class="am-icon-trash-o am-red-E43737 sell_layout_del" id="sell_del_'+sell_del_id+'"></i></li>';
                    BookEditClass.sell_layout_list[sell_del_id] = sell_name + '-' + price_system_name;
                    $('#search_room_layout').before(html);
                    $('#sell_del_'+sell_del_id).click(function(e) {
                        BookEditClass.sell_layout_list[sell_del_id] = '';
                        $(this).parent().remove();
                        if($('#select_sell_layout li').size() == 0) {
                            $('#sell_layout').val('');$('#price_system').html('');
                        }
                    });
                });
                //增加减少人数
                $('#addBookUser').click(function(e) {
                    var max_man = BookEditClass.max_man;
                    if(BookEditClass.BookUser_num >= max_man) return;
                    $(this).parent().prev().clone().insertBefore($(this).parent());
                    BookEditClass.BookUser_num++;
                });
                $('#reduceBookUser').click(function(e) {
                    var max_man = BookEditClass.max_man;
                    if(BookEditClass.BookUser_num == 1) return;
                    $(this).parent().prev().remove();
                    BookEditClass.BookUser_num--;
                });
                $('#computed_value').click(function(e) {
                    bookEdit.computeBookPrice(true);  
                });
                bookEdit.setPaymentType();
            };
            bookEdit.setExtraBed = function(sell_id, system_id, _this) {
                var extra_bed_val = $(_this).val();
                var room_id = $(_this).attr('room');
                var room_layout_id = $(_this).attr('room_layout');
                $('#extra_bed_'+sell_id+'_'+room_id).remove();
                $('#addBed_data').removeData(sell_id+'_'+room_id);
                if(extra_bed_val == 0) {						
                } else {
                    var extra_bed_input = '<input type="hidden" id="extra_bed_'+sell_id+'_'+room_id+'" '
                                         +'name="extra_bed['+sell_id +'-'+ room_layout_id+'-'+system_id+']['+room_id+']" value="'+extra_bed_val+'" '
                                         +'layout="bed" room_layout="'+room_layout_id+'" system_id="'+system_id+'" room="'+room_id+'" sell_id="'+sell_id+'" />';
                    $('#room_layout_html').append(extra_bed_input);
                    $('#addBed_data').data(sell_id+'_'+room_id, extra_bed_val);
                }
            };
            bookEdit.setDiscountHtml = function (_this) {
                var type = $(_this).find('option:selected').attr('type');
                if(type == 0) {
                    $('#discount_type').text('折扣');$('#book_discount_type').val('0');$('#discount').show();
                }
                if(type == 1) {
                    $('#discount_type').text('直减');$('#book_discount_type').val('1');$('#discount').show();
                }
                if(type == 2) {
                    $('#discount_type').html('<code>协议价</code>');$('#book_discount_type').val('2');$('#discount').hide();
                }
            };
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
                    $('#pre_licensing').hide('falst')
                    if(this.value == 4) {
                        $('#pre_licensing').show('falst')
                    }
                });
            };
            //搜索RoomLayout
            bookEdit.ajaxGetRoomLayout = function() {
                $('#room_layout_data').html('<tr class="gradeX odd" role="row"><td class="sorting_1"></td><td></td></tr>');
                $('#modal_loading').show();
                var hotel_service = '';//JSON.stringify(BookEditClass.hotel_service, false, 4)
                for(i in BookEditClass.hotel_service) {
                    if(BookEditClass.hotel_service[i] == 1) hotel_service += i + ',';
                }
                var sell_layout_list = '';
                for(i in BookEditClass.sell_layout_list) {
                    if(BookEditClass.sell_layout_list[i] != '') sell_layout_list += i + ',';
                }
                var check_in = $('#book_check_in').val();var check_out = $('#book_check_out').val();var max_check_out = $('#book_check_out').val();
                var checkOutDate = new Date(check_out);var today = checkOutDate.getDate();var thisHours = checkOutDate.getHours();
                var halfPrice = $('#half_price').val().replace(':00', '');
                if(thisHours > halfPrice) {
                    //算半天
                    checkOutDate.setDate(checkOutDate.getDate()+1);
                    var month = checkOutDate.getMonth() - 0 + 1; month = month < 10 ? '0' + month : month;
                    var day = checkOutDate.getDate();day = day < 10 ? '0' + day : day;
                    max_check_out = checkOutDate.getFullYear() + '-' + month + '-' + day;                    
                }
                //$('#book_form div').hide();
                $.ajax({
                    url : '<%$searchBookInfoUrl%>&search=searchRoomLayout&discount=' + $('#discount').val() 
                        + '&sell_layout_list=' + sell_layout_list + '&layout_corp='+BookEditClass.layout_corp,
                    type : "post",
                    data : 'book_check_in=' + check_in + '&book_check_out=' + check_out + '&max_check_out=' + max_check_out,
                    dataType : "json",
                    success : function(result) {
                        $('#modal_loading').hide('show');
                        $('#book_form .book_form_step2').show('show');
                        data = result;
                        if(data.success == 1) {
                            $('#room_layout_table').show();
                            table.destroy();
                            $('#room_layout_data').html(bookEdit.resolverRoomLayoutData(data.itemData,check_in,max_check_out));
                            table = $('#room_layout').DataTable({
                                "pagingType":   "numbers"
                            })
                            $('#room_layout_length').hide();
                            $('#room_layout_filter input').addClass('input-small');
                            $('#room_layout th').first().attr('style', 'width: 18%;');
                        } else {
                            $('#modal_fail').modal('show');
                            $('#modal_fail_message').html(data.message);
                        }
                        bookEdit.computeBookPrice(false);//计算房费
                    }
                });
            };
            //分解房型、价格体系数据 
            bookEdit.resolverRoomLayoutData = function(data, check_in, check_out) {
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
                if(layoutPrice == '') {
                    $('#room_layout_data').html('<tr class="gradeX odd" role="row"><td class="sorting_1">无房</td><td></td></tr>');
                    return;
                }
                var room = data.room;
                var priceSystem = bookEdit.getSellLayoutSystem();//data.priceSystem;
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
                                        +'<div class="right pledge_price"><span><input system_id="'+_system_id+'" sell_layout="'+_sell_layout_id+'" value="'+cash_pledge[_sell_layout_id+'-'+_system_id]+'" class="span12" type="text" /></span></div></li></ul>';
                            //cash_pledge[room_layout_id+'-'+system_id] = layoutPrice[i][day+'_day'];
                            html += '<tr '+html_id+'>'+
                                        '<td class="details-control">'+td1+'</td>'+
                                        '<td>'+td2 + td_bed + pledge + '</td>'+
                                        //'<td>'+td_bed+'</td>'+
                                    '</tr>';
                            td1 = td2 = td_bed = option = pledge = '';   
                        }
                        td1 = '<a href="#room" class="select_room">' + roomSellLayout[sell_layout_id].room_sell_layout_name + '-' //+ i 
                        //roomSellLayout[sell_layout_id].room_layout_name + 
                             + priceSystem[system_id].room_layout_price_system_name;
                        td1 = td1 +' <i class="am-icon-search am-blue-16A2EF"></i></a>';
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
                            //不通的月
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
                            +'class="layout_price span12" type="text" ></div></li>';
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
                                +'class="span12 extra_bed_price" type="text" >'
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
            },
            bookEdit.formatRoomTable = function(data, system_id, sell_id) {
                var html = '';
                if(data.success == 1) {
                   if(data.itemData != null && data.itemData != '') {
                       itemData = data.itemData;
                       var selectHtml = '';
                       var extra_bed_disable = 'disabled';
                       for(i in data.itemData) {
                            var addBedSelect = '';
                            if(itemData[i].extra_bed > 0) {
                                extra_bed_disable = '';
                            }
                            selectHtml = '<select '+extra_bed_disable+' class="input-mini room_extra_bed" room_layout="'+itemData[i].room_layout_id+'" system_id="'+system_id+'" '
                                        +'room="'+itemData[i].room_id+'" sell_id="'+sell_id+'">';
                            for(j = 0; j <= itemData[i].extra_bed; j++) {
                                if($('#addBed_data').data(sell_id+'_'+itemData[i].room_id) == j) {
                                    addBedSelect = 'selected';
                                }
                                selectHtml += '<option value="'+j+'" '+addBedSelect+'>'+j+'</option>';
                                addBedSelect = '';
                            }
                            selectHtml += '</select>';
                            extra_bed_disable = 'disabled';
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
                            html += '<tr>'
                                 +'<td>'
                                 +'<input '+checked_room+' type="checkbox" value="'+itemData[i].room_id+'" system_id="'+system_id+'" sell_id="'+sell_id+'"'
                                 +'room_layout="'+itemData[i].room_layout_id+'" max_people="'+itemData[i].max_people+'" max_children="'+itemData[i].max_children+'"'
                                 +' title="'+itemData[i].room_number+'" room_name="'+itemData[i].room_name+'['+itemData[i].room_number+']" />'
                                 +'</td>'
                                 +'<td>'+itemData[i].room_name+'['+itemData[i].room_number+']<i class="am-icon-location-arrow am-blue-3F91DD"></i>'
                                 +BookEditClass.orientations[itemData[i].room_orientations]
                                 +' '+itemData[i].room_area+'㎡</td>'
                                 +'<td>'+itemData[i].max_people+'</td>'
                                 +'<td>'+itemData[i].max_children+'</td>'
                                 /*+'<td>'+itemData[i].room_mansion+'</td>'
                                 +'<td>'+itemData[i].room_floor+'</td>'*/
                                 +'<td>'+selectHtml+'</td>'
                                 +'</tr>';
                            selectHtml = '';
                       }
                       html = '<table class="table table-bordered table-striped with-check">'
                              +'<thead>'
                              +'<tr>'
                              +'<th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" /></th>'
                              +'<th><%$arrayLaguage["room_name"]["page_laguage_value"]%>[<%$arrayLaguage["room_number"]["page_laguage_value"]%>]</th>'
                              +'<th><%$arrayLaguage["max_people"]["page_laguage_value"]%></th>'
                              +'<th><%$arrayLaguage["children"]["page_laguage_value"]%></th>'
                              /*+'<th><%$arrayLaguage["room_mansion"]["page_laguage_value"]%></th>'
                              +'<th><%$arrayLaguage["room_floor"]["page_laguage_value"]%></th>'*/
                              +'<th><%$arrayLaguage["extra_bed"]["page_laguage_value"]%></th>'
                              +'</tr>'
                              +'</thead>'
                              +'<tbody>'
                              + html
                              +'</tbody>'
                              +'</table>';	  
                   } else {
                       html = '<table class="table table-bordered table-striped with-check"><thead><tr><th></th></tr></thead><tbody><tr><td>无房</td></tr></tbody></table>';
                   }
                   return html;
               } else {
                   $('#modal_fail').modal('show');
                   $('#modal_fail_message').html(data.message);
                   return html;
               }
            };
            bookEdit.resetRoomStatus = function(room_id, sell_id, system_id, status) {
                var checkbox = $('#room_layout_data td input:checkbox');
                checkbox.each(function() {
                    if($(this).val() == room_id) {
                        if($(this).attr('sell_id') == sell_id && $(this).attr('system_id') == system_id)  {
                        } else {
                            if(status) this.disabled = true;
                            if(!status) this.disabled = false;
                        }
                    }
                })
            };
            //计算价格
            bookEdit.computeBookPrice = function(checkDate) {
                if(checkDate == false) {
                } else {
                    if(new Date($('#book_check_out').val()) <= new Date($('#book_check_in').val())) {
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html("抱歉，这个时间不正确！");
                        return false;
                    }
                }
                var thenRoomPrice = BookEditClass.thenRoomPrice = {};var bookSelectRoom = BookEditClass.bookSelectRoom;
                thenRoomPrice['room'] = {};thenRoomPrice['bed'] = {}; thenRoomPrice['pledge'] = {};thenRoomPrice['half'] = {};
                thenRoomPrice['room_id'] = {};thenRoomPrice['bed_room_id'] = {};thenRoomPrice['service'] = {}; var check_price = {};
                var room_price = 0; var pledge_price = 0; var service_price = 0;//客房价格 押金 需要的服务费
                var select_html = ' <select class="input-small bookSelectRoom" name="user_room[]">';//用户选择房间号
                var option = '';//选项
                var days = $('#book_days_total').val();//总共住多少天
                var is_half = days.indexOf(".") > 0 ? true : false;
                var in_date = $('#book_check_in').val().substr(0, 10);var out_date = $('#book_check_out').val().substr(0, 10);
                var balance_date = new Date($('#balance_date').val());//结算日
                var balance_date_time = balance_date.getTime();
                var book_discount_type = $('#book_discount_type').val();
                BookEditClass.max_man = 0;//人数
                var bed_all_price = 0;var room_all_price = 0;
                $("#room_layout_html input").each(function (i) {
                    var val = $(this).val() - 0; //获取单个value 如果大于0 表示有客房{客房数量 加床数量}
                    var select_room = {};
                    if(val > 0) {
                        var layout = $(this).attr('layout');//room or bed
                        var room_layout = $(this).attr('room_layout');//room_layout id
                        var system_id = $(this).attr('system_id');
                        var sell_id = $(this).attr('sell_id');
                        select_room[val] = 0;//是否已经选择room
                        select_room[val + '_bed'] = 0;//是否有加床
                        var room_key = sell_id + '-' + room_layout + '-' + system_id;
                        if(typeof(thenRoomPrice['room_id'][room_key]) == 'undefined') {
                            thenRoomPrice['room_id'][room_key] = {};
                        }
                        if(typeof(thenRoomPrice['bed_room_id'][room_key]) == 'undefined') {
                            thenRoomPrice['bed_room_id'][room_key] = {};
                        }
                        if(layout == 'room') {
                            thenRoomPrice['room_id'][room_key][val] = val;
                            var max_people = $(this).attr('max_people');var max_children = $(this).attr('max_children');
                            thenRoomPrice['room'][room_key] = {};thenRoomPrice['room'][room_key]['room_price'] = 0;
                            check_price[val] = {};
                            $('.layout_price').each(function(index, element) {
                                //房型价格  thenRoomPrice 正在订房当时的价格
                                if($(this).attr('sell_layout') == sell_id && $(this).attr('system_id') == system_id) {
                                    //房型和systemID对应上
                                    var rdate = $(this).attr('rdate');
                                    var now_date = new Date(rdate);
                                    var now_date_time = now_date.getTime();
                                    if(now_date_time <= balance_date_time) {
                                        var price = $(this).val() - 0;
                                        thenRoomPrice['room'][room_key][rdate] = {};
                                        thenRoomPrice['room'][room_key][rdate]['price'] = price;//原始房费
                                        var is_half_price = false;
                                        if(now_date_time == balance_date_time && is_half) {
                                            price = price * 0.5;is_half_price = true;
                                            thenRoomPrice['half'][rdate] = price;//半天价格
                                        }
                                        room_price += price;
                                        room_all_price += price;
                                        thenRoomPrice['room'][room_key]['room_price'] += price;
                                        //成交价格
                                        thenRoomPrice['room'][room_key][rdate]['discount_price'] = price;
                                        if(book_discount_type == '0') {
                                            thenRoomPrice['room'][room_key][rdate]['discount_price'] = price * ($('#discount').val() - 0) / 100;
                                        }
                                        if(!is_half_price) {
                                            if(book_discount_type == '1') {
                                                thenRoomPrice['room'][room_key][rdate]['discount_price'] = price - $('#discount').val();
                                            }
                                        }
                                        check_price[val][rdate] = thenRoomPrice['room'][room_key][rdate]['discount_price'];
                                        if(select_room[val] == 0) {
                                            var num_prople = (max_people - 0);
                                            for(p_i = 0; p_i < num_prople; p_i++) {
                                                option += '<option value="'+val+'" type="adult">'+bookSelectRoom[val]
                                                       +'-<%$arrayLaguage["adult"]["page_laguage_value"]%></option>';
                                                BookEditClass.max_man++;
                                            }
                                            var num_prople = (max_children - 0);
                                            for(p_i = 0; p_i < num_prople; p_i++) {
                                                option += '<option value="'+val+'" type="children">'+bookSelectRoom[val]
                                                       +'-<%$arrayLaguage["children"]["page_laguage_value"]%></option>';
                                                BookEditClass.max_man++;
                                            }
                                            
                                            select_room[val] = 1;
                                        }
                                    }
                                }
                            });
                            $('.pledge_price input').each(function(index, element) {
                                //押金
                                if($(this).attr('sell_layout') == sell_id && $(this).attr('system_id') == system_id) {
                                    //房型和systemID对应上
                                    var price = $(this).val() - 0;
                                    pledge_price += price;
                                    thenRoomPrice['pledge'][room_key] = price;//押金
                                }
                            });
                        }
                        if(layout == 'bed') {
                            thenRoomPrice['bed'][room_key] = {};
                            thenRoomPrice['bed'][room_key]['bed_price'] = 0;
                            var room_id = $(this).attr('room');
                            if(typeof($('#room_data').data(room_id)) == 'undefined') return;
                            var room_layout_system = $('#room_data').data(room_id);// +'-'+ system_id
                            var room_layout_system = room_layout_system.split('-');
                            room_layout_id = room_layout_system[0];
                            system_id = room_layout_system[1];
                            $('.extra_bed_price').each(function(index, element) {
                                //房型价格
                                if($(this).attr('sell_layout') == sell_id && $(this).attr('system_id') == system_id) {
                                    //相同room_layout
                                    thenRoomPrice['bed_room_id'][room_key][room_id] = room_id;
                                    var beddate = $(this).attr('beddate');
                                    var now_date = new Date(beddate);
                                    var now_date_time = now_date.getTime();
                                    if(now_date_time <= balance_date_time) {
                                        var price = $(this).val() - 0;
                                        thenRoomPrice['bed'][room_key][beddate] = price;//加床
                                        if(now_date_time == balance_date_time && is_half) {
                                            //price = price * 0.5; 加床没半价
                                        }
                                        room_price = price * val + room_price; bed_all_price += price * val;
                                        thenRoomPrice['bed'][room_key]['bed_price'] += price * val;
                                        if(select_room[val + '_bed'] == 0) {
                                            for(i = 1; i <= val; i++) {
                                                option += '<option value="'+room_id+'" type="extra_bed">'+bookSelectRoom[room_id]
                                                       +'-<%$arrayLaguage["extra_bed"]["page_laguage_value"]%></option>';	
                                                BookEditClass.max_man++;
                                            }
                                            select_room[val + '_bed'] = 1;
                                        }
                                    }
                                }
                            });
                        }
                    }
                });
                select_html += option + '</select>';//用户房间结束
                //房费
                if(book_discount_type == '0') {
                    room_price = room_price * ($('#discount').val() - 0) / 100; 
                    room_all_price = room_all_price  * ($('#discount').val() - 0) / 100; 
                } else if(book_discount_type == '1') {
                    room_price = room_price - $('#discount').val() * Math.floor($('#book_days_total').val());
                    room_all_price = room_all_price - $('#discount').val() * Math.floor($('#book_days_total').val());
                }
                //核对价格
                var check_price_total = 0;
                for(var key in check_price) {
                    for(var date in check_price[key]) {
                        check_price_total = accAdd(check_price_total, check_price[key][date]);
                    }
                }
                console.log(check_price_total + '===' + room_price + '=====' + room_all_price);
                //押金
                pledge_price = pledge_price * ($('#discount').val() - 0) / 100; 
                $('.service_price').each(function(index, element) {
                    //需要的服务费
                    var price = $(this).val() - 0;
                    var service_id = $(this).attr('service_id');
                    var service_num = $(this).next().val();
                    var service_discount = $(this).next().next().val();
                    service_price += (price * service_num * service_discount) / 100;
                    thenRoomPrice['service'][service_id] = price + '-' + service_num + '-' + service_discount;
                });//附加服务费
                
                $('#total_room_rate').val(room_price);//总房费 
                $('#room_all_price').text(room_all_price);//房费
                $('#bed_all_price').text(bed_all_price);//加床
                $('#book_total_cash_pledge').val(pledge_price);//押金
                $('#need_service_price').val(service_price);
                //room_price = days * room_price * ($('#discount').val() - 0) / 100;
                var book_service_charge = $('#book_service_charge').val() - 0;//服务费
                var total_price = accAdd(accAdd(accAdd(book_service_charge, room_price), service_price), pledge_price);//总价=服务费+房费+附加服务费+押金
                /////////
                $('#total_price').val(total_price);
                $('#prepayment').val(pledge_price + service_price);//预付金 = 押金+附加服务费
                //$('#room_layout_html').html(room_layout_html);	
                $('.bookSelectRoom').remove();
                $('.book_user_info').append(select_html);
            };
            bookEdit.groupSellLayoutSystem = function() {
                var priceSystem = BookEditClass.priceSystem;
                $('#price_system').children('option').each(function(index, element) {
                    var sell_id = $(this).attr('sell_id');
                    var layout_corp = $(this).attr('layout_corp');
                    if(typeof(priceSystem[sell_id]) == 'undefined') {
                        priceSystem[sell_id] = {};
                        priceSystem[sell_id][layout_corp] = {};
                        priceSystem[sell_id][layout_corp][$(this).val()] = $.trim($(this).text());
                    } else {
                        priceSystem[sell_id][layout_corp][$(this).val()] = $.trim($(this).text());
                    }
                });
                $('#price_system').html('');
                BookEditClass.priceSystem = priceSystem;
            };
            bookEdit.getRoomSellLayout = function() {
                //if(BookEditClass.roomSellLayout != '') return BookEditClass.roomSellLayout;
                var roomSellLayout = BookEditClass.roomSellLayout;
                $('#sell_layout').children('option').each(function(index, element) {
                    roomSellLayout[this.value] = {};
                    roomSellLayout[this.value]['room_layout_id'] = $(this).attr('room_layout');
                    roomSellLayout[this.value]['sell_id'] = this.value;
                    roomSellLayout[this.value]['room_sell_layout_name'] = $.trim($(this).text());
                })
                BookEditClass.roomSellLayout = roomSellLayout;
                return roomSellLayout;
            };
            bookEdit.getSellLayoutSystem = function() {
                var layout_corp = BookEditClass.layout_corp;
                var priceSystem = BookEditClass.priceSystem;
                var allSellLayoutSystem = {};
                for(var sell_id in priceSystem) {
                    for(var layout_corp in priceSystem[sell_id]) {
                        for(system_id in priceSystem[sell_id][layout_corp]) {
                            allSellLayoutSystem[system_id] = {};
                            allSellLayoutSystem[system_id]['room_layout_price_system_name'] = priceSystem[sell_id][layout_corp][system_id];
                        }
                    }
                }console.log(priceSystem);console.log(allSellLayoutSystem);
                return allSellLayoutSystem;
            };
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
});//console.log(roomSellLayout);
</script>