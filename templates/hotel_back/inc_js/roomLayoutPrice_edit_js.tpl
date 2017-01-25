<script language="javascript">
$(document).ready(function(){
    //
    $('#room_layout_date_month').val('<%$thisMonth%>');
    function leapYear(year){
        var isLeap = year%100==0 ? (year%400==0 ? 1 : 0) : (year%4==0 ? 1 : 0);
        return new Array(31,28+isLeap,31,30,31,30,31,31,30,31,30,31);
    }
    function setKalendar(select_month) {
        var year = $('#room_layout_date_year').val();
        var arrayMonth = leapYear(year);
        var month_option = '';
        var thisMonth = '<%$thisMonth%>';
        var thisYear = '<%$thisYear%>';
        var iMonth = year > thisYear ? 1 : thisMonth;
        var toDay = '<%$thisToday%>';
        //room_layout_date_month <option value="1">1</option>
        var selected = '';
        
        for(var i = iMonth; i <= 12; i++) {
            if(i == select_month) selected = "selected";
            month_option += '<option value="'+i+'" '+selected+'>'+i+'</option>';
            selected = '';
        }
        $('#room_layout_date_month').html(month_option);     
        //
        var month = $('#room_layout_date_month').val();
        var days = arrayMonth[month - 1];
        var iDay = (year == thisYear && month == thisMonth) ? toDay : 1;
        var disabled = '';
        var kalendar_html = '';
        var nowDate = new Date(year + '-' +  month + '-01');
        var firstWeek = nowDate.getDay();
        firstWeek = firstWeek == 0 ? firstWeek = 7 : firstWeek;
        var num = days + firstWeek;
        for(var i = 1; i < num; i++ ) {
            var l = i;// - firstWeek;
            if(l < firstWeek){
                l = 00;
            } else {
                l = l - firstWeek + 1;
            }
            if(l < iDay) disabled = 'disabled';
            if(l < 10) l = '0' + l;
            if(l == 00) {
                kalendar_html += '<li class="none"></li>';
            } else {
                kalendar_html += '<li> <a> <i class=""> '+l+' </i> '
                        +'<input '+disabled+' id="'+l+'_day" name="'+l+'_day" class="span7" type="text" /></a> </li>';
            }
            disabled = '';
            if(i % 7 == 0) {
                kalendar_html += '<br>';
            }
        }
        $('#room_layout_price_kalendar').html(kalendar_html);   
    }
    function setKalendarWeek() {
        var kalendar_html = '';
        for(var i = 1; i <= 7; i++ ) {
            var l = i;
            if(i < 10) l = '0' + i;
            kalendar_html += '<li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> '+l+'</i> '
                    +'<input id="week_'+l+'" name="week_'+l+'" class="span9" type="text" /></a> </li>';
        }
        $('#room_layout_price_week').html(kalendar_html);
    }
    setKalendar('');
    $('#room_layout_date_month,#room_layout_date_year').change(function(e) {
        var month = $(this).val();
        if(month > 12) month = 1;
        setKalendar(month);
        setDifferentExtraBedMonth();
        setHistoryPrice();
    });
    var historyPrice = {};
    function setHistoryPrice() {
        if(sell_layout > 0 && system_id > 0) {
            $('#room_layout_price_kalendar input').val('');
            $('#different_price_month_1 input').val('');
            var year = $('#room_layout_date_year').val();
            var month = $('#room_layout_date_month').val();
            var key = sell_layout+'：'+system_id + '：' + year + '：'+month;
            if(typeof(historyPrice[key]) == 'undefined' || historyPrice[key] == '') {
                var url = '<%$add_roomLayoutPriceSystem_url%>&search=historyPrice&sell_layout='+sell_layout
                         +'&system_id='+system_id + '&year=' + year + '&month='+month;
                $.getJSON(url, function(result) {
                    data = result;
                    if(data.success == 1) {
                        itemData = data.itemData;
                        //console.log(itemData);
                        if(itemData != '' && typeof(itemData['room_price'][0]) != 'undefined') {
                            var _roomPrice = itemData['room_price'][0];
                            //缓存
                            historyPrice[key] = _roomPrice;
                            for(i in _roomPrice) {
                                $('#'+i).val(_roomPrice[i]);
                            }
                            if(typeof(itemData['extra_bed_price'][0]) != 'undefined') {
                                //缓存
                                historyPrice[key+'_extra_bed_price'] = itemData['extra_bed_price'][0];
                                var _extraBedPrice = itemData['extra_bed_price'][0];
                                for(i in _extraBedPrice) {
                                    $('#'+i+'_extra_bed').val(_extraBedPrice[i]);
                                }
                            } else {
                                historyPrice[key+'_extra_bed_price'] = '';
                            }
                        }
                    } else {
                        $('#modal_success').modal('hide');
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                })
            }else {
                var _roomPrice = historyPrice[key];
                for(i in _roomPrice) {
                    $('#'+i).val(_roomPrice[i]);
                    //console.log(i);
                }
                if(typeof(historyPrice[key+'_extra_bed_price']) != 'undefined') {
                    var _extraBedPrice = historyPrice[key+'_extra_bed_price'];
                    console.log(_extraBedPrice);
                    for(i in _extraBedPrice) {
                        console.log(i +'===>'+ $('#'+i+'_extra_bed').val());
                        $('#'+i+'_extra_bed').val(_extraBedPrice[i]);
                    }
                }
            }
        }
    }
    
    //日历
	$.datetimepicker.setLocale('ch');
	var dateToDisable =  new Date('<%$thisDay%>'); 
	var weekToDisable =  new Date('<%$thisDay%>');
	var week_differ  = 1 - weekToDisable.getDay();
	week_differ = week_differ < 0 ? week_differ + 7 : week_differ;
    var nextWeekDateToDisable = new Date(weekToDisable.setDate(weekToDisable.getDate() + week_differ));
	//dateToDisable.setDate(dateToDisable.getDate() - 1);
	$('#time_begin').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, 
        yearStart: '<%$thisYear%>', yearEnd: '<%$nextYear%>', //yearOffset:1,maxDate:'+1970-01-02',
		beforeShowDay: function(date) {
			if (date.getTime() < dateToDisable.getTime()) {
				return [false];
			}
            if(date.getTime() > nextWeekDateToDisable.getTime() && date.getDay() != 1) {
                //return [false];
            }
            return [true];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
        onSelectDate:function(date) {//console.log(date + '-====-' + nextWeekDateToDisable + '----'+ week_differ + '-----<%$thisDay%>');
            var thisDate = new Date(this.getValue());
            var nextDate = new Date(thisDate.setDate(thisDate.getDate() + 6));
            var time_end_date = new Date($('#time_end').val());
            if(time_end_date.getTime() < nextDate.getTime()) {
                $('#time_end').val(nextDate);
                $('#time_end').datetimepicker({value:nextDate});
            }
        },
		onShow:function(date) {
			//xdsoft_calendar data-date="31" data-month="9" data-year="2016"
			console.log(this[0]);
		}
       
	});
	$('#time_end').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, yearStart: '<%$thisYear%>', yearEnd: '<%$nextYear%>',
		beforeShowDay: function(date) {//new Date($('#book_check_in').val()).getDate()
			var dateToDisable = new Date($('#time_begin').val());
            dateToDisable.setDate(dateToDisable.getDate() + 6);
			if (date.getTime() < dateToDisable.getTime()) {
                //alert((date.getTime() + '----' + (dateToDisable.getTime() - 0 + 36000 * 24 * 6)));
				return [false];
			}
            var dateToDisableNext = new Date($('#time_begin').val());
            dateToDisableNext.setDate(dateToDisableNext.getDate() + 180);
            if (date.getTime() > dateToDisableNext.getTime()) {
                //alert((date.getTime() + '----' + (dateToDisable.getTime() - 0 + 36000 * 24 * 6)));
				return [false];
			}
			return [true, ""];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
	});
    $('#history_begin').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, yearEnd: '<%$nextYear%>',
		beforeShowDay: function(date) {
            var dateToDisable = new Date('<%$thisDay%>');
			if (date.getDate() > 1) {
				return [false];
			}
            return [true];
			//return [true, ""];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        }
	});
    $('#history_end').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, yearEnd: '<%$nextYear%>',
		beforeShowDay: function(date) {//new Date($('#book_check_in').val()).getDate()
            var dateToDisable = new Date('<%$thisDay%>');
			var dateToBeginDisable = new Date($('#history_begin').val());
            var month = date.getMonth();
            var year = date.getFullYear();
            var arrayMonth = leapYear(year);
            var bigDay = arrayMonth[month]
			if (date.getTime() < dateToBeginDisable.getTime()) {//date.getTime() > dateToDisable.getTime() || 
				return [false];
			}
            if (date.getDate() != bigDay) {//date.getTime() > dateToDisable.getTime() || 
				return [false];
			}
			return [true];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        }
	});
    var weekday=new Array(7)
        weekday[0]="日"
        weekday[1]="一"
        weekday[2]="二"
        weekday[3]="三"
        weekday[4]="四"
        weekday[5]="五"
        weekday[6]="六"
    $('#history_search_btn').click(function(e) {
        var history_begin = $('#history_begin').val();
        var history_end = $('#history_end').val();
        var url = '<%$add_roomLayoutPriceSystem_url%>&search=history&history_begin='+history_begin
                 +'&history_end='+history_end+'&system_id='+system_id+'&sell_layout='+sell_layout;
        $.getJSON(url, function(result) {
            data = result;
            if(data.success == 1) {
                var html = '';
                if(data.itemData.room_price != '') {
                    var priceItem = data.itemData.room_price;
                    for(i in priceItem) {
                        var year = priceItem[i].room_layout_date_year;
                        var month = priceItem[i].room_layout_date_month;
                        var arrayMonth = leapYear(year);
                        html += '<li><div class="left peity_bar_good"><span>'+month+'</span>'+year+'</div><div class="right"> <strong></strong> </div></li>';
                        for(var j = 1; j < arrayMonth[month]; j++) {
                            var day = j + '_day';
                            if(j < 10) {
                                day = '0' + j + '_day';
                            }
                            var now_date = new Date(year + '-' + month + '-' + j);
                            var week = now_date.getDay();
                            html += '<li><div class="left peity_bar_good"><span>'+j+'</span>'+weekday[week]+'</div>'
                                                  +'<div class="right"> <strong>'+priceItem[i][day]+'</strong> </div></li>';
                        }
                        html += '<br>';
                    }
                }
                $('#history_price_list_html').html(html);
            } else {
                $('#modal_success').modal('hide');
                $('#modal_fail').modal('show');
                $('#modal_fail_message').html(data.message);
            }
        });
    });
	//select2
    $('#sell_layout,#sell_layout_id').select2();
    //选择对应的房型和价格体系
    var room_layout_data = {};
    var sell_layout = 0;
    $('#sell_layout').val(sell_layout);
    var is_extra_bed = false;
	$('.extra_bed input').attr('disabled', true);
    $('#sell_layout').change(function(e) {
        $('#title_price').hide();
        $('#title_content').hide();
        $('#system_prices_html').html('');
        system_id = 0;
        sell_layout = $(this).val();//3. $("#select_id option[text='jQuery']").attr("selected", true); 
        if(sell_layout < 1) return;
        var extra_bed = $("#sell_layout option[value='"+sell_layout+"']").attr("extra_bed");
        $('.extra_bed').hide();
		$('.extra_bed input').attr('disabled', true);
        is_extra_bed = false;
        if(extra_bed > 0) {
            $('.extra_bed').show();
            is_extra_bed = true;
			$('.extra_bed input').attr('disabled', false);
            setDifferentExtraBedMonth();
        }
        var layout_id = $(this).find('option[value="'+sell_layout+'"]').attr("layout_id")
        if(typeof(room_layout_data[sell_layout]) == 'undefined') {
            $.getJSON('<%$add_roomLayoutPriceSystem_url%>&search=systemPrices&sell_layout_id='+sell_layout+'&layout_id='+layout_id, function(result) {
                data = result;
                if(data.success == 1) {
                    room_layout_data[sell_layout] = data.itemData;
                    $('#system_prices_html').html(createSystemPriceHtml(data.itemData));
                    $('.system_prices .view_price').click(function(e) {
                        systemPriceClick(this);
                    });
                    $('.system_prices_edit').click(function(e) {
                        systemPricesEditClick(this);
                    });
                } else {
                    $('#modal_success').modal('hide');
                    $('#modal_fail').modal('show');
                    $('#modal_fail_message').html(data.message);
                }
            })
        } else {
            $('#system_prices_html').html(createSystemPriceHtml(room_layout_data[sell_layout]));
            $('.system_prices .view_price').click(function(e) {
                systemPriceClick(this);
            });
            $('.system_prices_edit').click(function(e) {
                systemPricesEditClick(this);
            });
        }
    });
    function createSystemPriceHtml(htmlData) {
        var html = '<div class="btn-group system_prices" data-id="1"><a class="btn view_price" href="#system_prices"><i class="am-icon-circle-o"></i> <%$arrayLaguage["base_room_price"]["page_laguage_value"]%></a></div> ';
        for(i in htmlData) {
            var id = htmlData[i].room_layout_price_system_id;
            var name = htmlData[i].room_layout_price_system_name;
            html += '<div class="btn-group system_prices" data-id="'+id+'">'
                   +'<a class="btn view_price" href="#system_prices"><i class="am-icon-circle-o"></i> '+name+'</a>'
                   +'<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>'
                   +'<ul class="dropdown-menu" data-id="'+id+'" layout-id="'+htmlData[i].room_sell_layout_id+'" data-name="'+name+'">'
                   +'<li><a href="#" class="system_prices_edit"><i class="am-icon-pencil am-yellow-FFAA3C"></i> Edit</a></li>'
                   +'<li><a href="#" class="system_prices_delete"><i class="am-icon-trash am-red-FB0000"></i> Delete</a></li>'
                   +'<li class="divider"></li>'
                   +'<li><a href="#"><i class="i"></i>';
                    for(j in htmlData[i].hotel_service_id) {
                        html += '<i class="am-icon-check-square-o" data-id="'+htmlData[i].hotel_service_id[j]+'"></i>' + htmlData[i].hotel_service_name[j];
                    }
             html += '</a></li></ul></div> ';
        }
        return html;
    }
    //增加价格体系
    var system_id = 0;
    $("[data-toggle='popover']").popover({'trigger':'hover'});
    $('.system_prices .view_price').click(function(e) {
        systemPriceClick(this);
    });
    function systemPriceClick(_this) {
        system_id = $(_this).parent().attr('data-id');
        $('.system_prices .btn i').removeClass('am-icon-dot-circle-o');
        $('.system_prices .btn i').addClass('am-icon-circle-o');
        $(_this).parent().find('.btn i').removeClass('am-icon-circle-o');
        $(_this).parent().find('.btn i').addClass('am-icon-dot-circle-o');
        setHistoryPrice();
        $('#title_price').show();
        $('#title_content').show();
    }
    var layout_id = 0;
    $('.system_prices_edit').click(function(e) {
        systemPricesEditClick(this);
    });
    function systemPricesEditClick(_this){
        $('.system_prices .btn i').removeClass('am-icon-dot-circle-o');
        $('.system_prices .btn i').addClass('am-icon-circle-o');
        $(_this).parent().parent().parent().find('.btn i').removeClass('am-icon-circle-o');
        $(_this).parent().parent().parent().find('.btn i').addClass('am-icon-dot-circle-o');
        
        $('#addSystemPrice').collapse({toggle: true})
        $('#addSystemPrice').collapse('show');
        layout_id = $(_this).parent().parent().attr('layout-id');
        $('#sell_layout_id').val(layout_id);
        $('#update_system_id').val($(_this).parent().parent().attr('data-id'));
        $('#price_system_name').val($(_this).parent().parent().attr('data-name'));
        var layout_name = $('#sell_layout option[value="'+layout_id+'"]').text();
        layout_name = layout_name == '' ? "<%$arrayLaguage['common_room_layout']['page_laguage_value']%>" : layout_name;
        $('#s2id_room_layout_id span').text(layout_name);
        $('#sell_layout_id').attr("disabled",true); 
        $('#sell_layout_id').select2();
        getHotelService();
        //var _this = this;
        var hotel_serviceInterval = setInterval(function(){
            var hotel_service_html = $('#hotel_service').html();
            if(hotel_service_html != '') {
                $(":checkbox").attr('checked', false);
                $(_this).parent().parent().find('.am-icon-check-square-o').each(function(index, element) {
                    var data_id = $(this).attr('data-id');
                    $("#hotel_service :checkbox").each(function(index, element) {
                        if($(this).val() == data_id) {
                            $(this).attr('checked', true);
                        }
                    });
                });
                clearInterval(hotel_serviceInterval);
                //console.log('1');
            }
        },500);
    }
    $('#add_edit_system').click(function(e) {
        getHotelService();
        $('#addSystemPrice').collapse({toggle: true})
        $('#addSystemPrice').collapse('show');
        $('#sell_layout_id').attr("disabled",false); 
        $('#sell_layout_id').val(0);
        $('#price_system_name').val('');
        $('#s2id_room_layout_id span').text("<%$arrayLaguage['common_room_layout']['page_laguage_value']%>");
        $('#sell_layout_id').select2();
        $('#update_system_id').val('');//
    });
    $('.system_prices_delete').click(function(e) {
        //alert($(this).parent().parent().attr('data-id'));        
    });
    $('#addSystemPrice').on('show.bs.collapse', function () {
        //getHotelService();
    })
    var hotel_service_values = {};
    function getHotelService() {
        if(typeof(hotel_service_values['html']) == 'undefined') {
            $.getJSON('<%$add_roomLayoutPriceSystem_url%>&search=hotel_service', function(result) {
                data = result;
                if(data.success == 1) {
                    var hotel_service_html = '';
                    for(i in data.itemData) {
                        if(data.itemData[i].hotel_service_id != data.itemData[i].hotel_service_father_id) {
                            hotel_service_html += '<input value="'+data.itemData[i].hotel_service_id+'" name="hotel_service_id[]" type="checkbox"> ' 
                                               + '<label>'+ data.itemData[i].hotel_service_name + '</label>'
                                               + '<label> ￥' + data.itemData[i].hotel_service_price + '</label>';
                        }
                    }
                    hotel_service_values['html'] = hotel_service_html;
                    $('#hotel_service').html(hotel_service_html);
                } else {
                    $('#modal_success').modal('hide');
                    $('#modal_fail').modal('show');
                    $('#modal_fail_message').html(data.message);
                }
            });
        } else {
            $('#hotel_service').html(hotel_service_values['html']);
        }
    }
    
    var room_layout_price_system_validate = $("#room_layout_price_system").validate({
		rules: {
			price_system_name: {required: true},
		},
		messages: {
			price_system_name:"",
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
            var param = $("#room_layout_price_system").serialize();
            $.ajax({
                url : '<%$editSystem_url%>',type : "post",dataType : "json",data: param,
                success : function(data) {
                    if(data.success == 1) {
                        //$('#modal_fail').modal('hide');
                        $('#modal_success').modal('show');
                        $('#modal_success_message').html(data.message);
                        $('#addSystemPrice').collapse('hide');
                        var update_system_id = $('#update_system_id').val();
                        if(update_system_id > 0) {//update
                            $('#update_system_id').val('');//system_id
                            $('.system_prices').each(function(index, element) {
                                if($(this).attr('data-id') == update_system_id) {
                                    $(this).children().first().html('<i class="am-icon-dot-circle-o"></i> ' + $('#price_system_name').val());
                                    var haveHotelService = '<i class="i"></i>';
                                    $("#hotel_service :checkbox").each(function(index, element) {
                                        if($(this).attr('checked')) {
                                            haveHotelService += '<i class="am-icon-check-square-o" data-id="'+$(this).val()+'"></i>'
                                                             + $(this).next().text() + ' ';
                                        }
                                    });
                                    $(this).find('.i').parent().html(haveHotelService);
                                    $(this).find('ul').attr('data-name', $('#price_system_name').val());
                                    return false;
                                }
                            });
                           
                        } else {//add 
                            var add_room_layout = $('#sell_layout_id').val();
                            var name = $('#price_system_name').val();
                            var html = '';
                            if(add_room_layout == 0 || add_room_layout == sell_layout){
                                html += '<div class="btn-group system_prices" data-id="'+data.itemData+'">'
                                       +'<a class="btn" href="#system_prices"><i class="am-icon-circle-o"></i> '+name+'</a>'
                                       +'<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>'
                                       +'<ul class="dropdown-menu" data-id="'+data.itemData+'" layout-id="'+add_room_layout+'" data-name="'+name+'">'
                                       +'<li><a href="#" class="system_prices_edit"><i class="am-icon-pencil am-yellow-FFAA3C"></i> Edit</a></li>'
                                       +'<li><a href="#" class="system_prices_delete"><i class="am-icon-trash am-red-FB0000"></i> Delete</a></li>'
                                       +'<li class="divider"></li>'
                                       +'<li><a href="#"><i class="i"></i>';
                                       $("#hotel_service :checkbox").each(function(index, element) {
                                            if($(this).attr('checked')) {
                                                html += '<i class="am-icon-check-square-o" data-id="'+$(this).val()+'"></i>' + $(this).next().text();
                                            }
                                        });
                                 html += '</a></li></ul></div> ';
                            };
                            if(html != '') {
                                $('#system_prices_html').append(html);
                                $('.system_prices .view_price').click(function(e) {
                                    systemPriceClick(this);
                                });
                                $('.system_prices_edit').click(function(e) {
                                    systemPricesEditClick(this);
                                });
                            }
                        }
                        $('#price_system_name').val('');
                        $("#hotel_service :checkbox").attr('checked', false);
                        room_layout_data = {};
                    } else {
                        //$('#modal_success').modal('hide');
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                }
            });
		}
	});
    //
    $('.select_extra_bed_week').click(function(e) {
        $('.select_extra_bed_week i').removeClass('am-icon-dot-circle-o');
        $(this).find('i').addClass('am-icon-dot-circle-o');
        if($(this).attr('id') == 'same_week') {
            $('#same_price_week').show();
            $('#same_price_week input').attr('disabled', false);
            $('#different_price_week').hide();
            $('#different_price_week input').attr('disabled', true);
        } else {
            $('#same_price_week').hide();
            $('#same_price_week input').attr('disabled', true);
            $('#different_price_week').show();
            $('#different_price_week input').attr('disabled', false);
        }
    });
    $('.select_extra_bed_month').click(function(e) {
        $('.select_extra_bed_month i').removeClass('am-icon-dot-circle-o');
        $(this).find('i').addClass('am-icon-dot-circle-o');
        if($(this).attr('id') == 'same_month') {
            $('#same_price_month').show();
            $('#same_price_month input').attr('disabled', false);
            $('#different_price_month').hide();
            $('#different_price_month ul').remove();
        } else {
            $('#same_price_month').hide();
            $('#same_price_month input').attr('disabled', true);
            $('#different_price_month').show();
            setDifferentExtraBedMonth();
        }
    });
    function setDifferentExtraBedMonth() {
        $('#different_price_month ul').remove();
        $('#different_price_month').html('');
        $('#kalendar_week').clone().appendTo($('#different_price_month'));
        $('#different_price_month').append('<br>');
        $('#room_layout_price_kalendar').clone().appendTo($('#different_price_month'));
        $('#different_price_month ul').each(function(index, element) {
            $(this).attr('id', 'different_price_month_' + index);
        });
        $('#different_price_month_1 input').each(function(index, element) {
            $(this).attr('id', $(this).attr('id') + '_extra_bed');
            $(this).attr('name', 'extra_bed[' + $(this).attr('name') + ']');
        });
        $('#different_price_month_1 input').val('');
        var key = sell_layout+'：'+system_id + '：' + $('#room_layout_date_year').val() + '：'+$('#room_layout_date_month').val();
        if(typeof(historyPrice[key+'_extra_bed_price']) != 'undefined') {
            var _extraBedPrice = historyPrice[key+'_extra_bed_price'];
            console.log(_extraBedPrice);
            for(i in _extraBedPrice) {
                $('#'+i+'_extra_bed').val(_extraBedPrice[i]);
            }
        }
    }
    
    var prices_week_validate = $("#prices_week").validate({
		rules: {
			
		},
		messages: {
			
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
            if(sell_layout == 0) {
                $('#modal_success').modal('hide');
                $('#modal_fail').modal('show');
                $('#modal_fail_message').html('请选择售卖房型！');
                return;
            }
            if(system_id == 0) {
                $('#modal_success').modal('hide');
                $('#modal_fail').modal('show');
                $('#modal_fail_message').html('请选择价格体系！');
                return;
            }
            //var week_begin = 
            var param = $("#prices_week").serialize();
            $('#modal_save').show('slow');
            $.ajax({
                url : '<%$add_roomLayoutPriceSystem_url%>&search=prices_week&sell_layout='+sell_layout+'&system_id='+system_id,
                type : "post",dataType : "json",data: param,
                success : function(result) {
                    $('#modal_save').hide('slow');
                    data = result;
                    if(data.success == 1) {
                        //$('#modal_fail').modal('hide');
                        $('#modal_success').modal('show');
                        $('#modal_success_message').html(data.message);
                        historyPrice = {};
                        setHistoryPrice();
                    } else {
                        //$('#modal_success').modal('hide');
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                }
            });
		}
	});
    
    var prices_month_validate = $("#prices_month").validate({
		rules: {
			price_system_name: {required: true},
		},
		messages: {
			price_system_name:"",
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
            if(sell_layout == 0) {
                $('#modal_success').modal('hide');
                $('#modal_fail').modal('show');
                $('#modal_fail_message').html('请选择售卖房型！');
                return;
            }
            if(system_id == 0) {
                $('#modal_success').modal('hide');
                $('#modal_fail').modal('show');
                $('#modal_fail_message').html('请选择价格体系！');
                return;
            }
            var param = $("#prices_month").serialize();
            $.ajax({
                url : '<%$add_roomLayoutPriceSystem_url%>&search=prices_month&sell_layout='+sell_layout+'&system_id='+system_id,
                type : "post",dataType : "json",data: param,
                success : function(result) {
                    data = result;
                    if(data.success == 1) {
                        //$('#modal_fail').modal('hide');
                        $('#modal_success').modal('show');
                        $('#modal_success_message').html(data.message);
                        var key = sell_layout+'：'+system_id + '：' + $('#room_layout_date_year').val() + '：'+$('#room_layout_date_month').val();
                        historyPrice[key] = '';
                        historyPrice[key+'_extra_bed_price'] = '';
                    } else {
                        //$('#modal_success').modal('hide');
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                }
            });
		}
	});
    
    var search_history_validate = $("#search_history").validate({
		rules: {
			price_system_name: {required: true},
		},
		messages: {
			price_system_name:"",
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
    $('#sell_layout').val(sell_layout);
    $('#s2id_sell_layout span').text("<%$arrayLaguage['please_select']['page_laguage_value']%>");
})
</script>