<script language="javascript">
$(document).ready(function(){
	// === Prepare peity charts === //
	
    //日历
	$.datetimepicker.setLocale('ch');
	var dateToDisable =  new Date('<%$nowDay%>'); 
	$('#time_begin').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, 
        yearStart: '1980', //yearEnd: '<%$nextYear%>', yearOffset:1,maxDate:'+1970-01-02',
		beforeShowDay: function(date) {
            if (date.getTime() < dateToDisable.getTime()) {
                //alert((date.getTime() + '----' + (dateToDisable.getTime() - 0 + 36000 * 24 * 6)));
				return [false];
			}
            return [true];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
        onSelectDate:function(date) {//console.log(date + '-====-' + nextWeekDateToDisable + '----'+ week_differ + '-----<%$thisDay%>');
            var thisDate = new Date(this.getValue());
            var time_end_date = new Date($('#time_end').val());
            if(time_end_date.getTime() < thisDate.getTime()) {
                $('#time_end').val(thisDate);
                $('#time_end').datetimepicker({value:thisDate});
            }
        },
	});
	$('#time_end').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, //yearEnd: '<%$nextYear%>',
		beforeShowDay: function(date) {//new Date($('#book_check_in').val()).getDate()
			var dateToDisable = new Date($('#time_begin').val());
			if (date.getTime() < dateToDisable.getTime()) {
                //alert((date.getTime() + '----' + (dateToDisable.getTime() - 0 + 36000 * 24 * 6)));
				return [false];
			}
			return [true];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
	});
    
    var thisModuleClass = {
        instance: function() {
            var thisModule = {};
            thisModule.initParameter  = function() {
                thisModule.thisYear   = '<%$thisYear%>';
                thisModule.thisMonth  = '<%$thisMonth%>';
                thisModule.time_begin = '<%$thisDay%>';
                thisModule.time_end   = '<%$toDay%>';
                thisModule.roomStatus = $.parseJSON('<%$arrayRoomStatus%>');
            };
            thisModule.init = function() {
                thisModule.setRoomStatus();
                thisModule.turnRoomStatus();
            };
            thisModule.computeCheckDate = function() {
                var inDate = new Date($('#time_begin').val());
                var outDate = new Date($('#time_end').val());
                var days = Math.floor((outDate.getTime() - inDate.getTime())/(24*3600*1000));
                return days + 1;
            };
            thisModule.computeRoomStatusDiv = function(className, thisDay, id) {//peity_bar_better
                var button = '<div class="btn-group">'
                            +'<a class="btn btn-mini btn-primary" href="#"><i class="am-icon-sun-o"></i> <%$arrayLaguage["manage"]["page_laguage_value"]%></a>'
                            +'<a class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><div class="caret"></div></a>'
                            +'<ul class="dropdown-menu" data-id="'+id+'">'
                            +'<li><a data-target="#" href="#Book"><i class="am-icon-toggle-off"></i> 转为可预定[干净/好房]</a></li>'
                            +'<li><a data-target="#" href="#Dirty"><i class="am-icon-toggle-off"></i> 转为脏房</a></li>'
                            +'<li><a data-target="#" href="#Servicing"><i class="am-icon-toggle-off"></i> 转为维修房</a></li>'
                            +'</ul></div>';
                var html_fl = '<div class="left '+className+'"><span><span style="display: none;">12,12,12,12,12,12,12</span>'
                           +'<canvas width="70" height="70"></canvas></span>'+button+'</div>';
                var html_fr = '<div class="left">'+thisDay+'<span></span><span></span> </div>';
                return html_fl + html_fr;
            };
            thisModule.computeRoomStatusDate = function(newThisDay, roomStatus) {
                var thisDayDate = new Date(newThisDay);var thisTime = thisDayDate.getTime();
                var className = 'peity_bar_neutral';
                for(var j in roomStatus) {
                    var check_in = roomStatus[j].check_in;var check_out = roomStatus[j].check_out;
                    var inDate = new Date(check_in);var inDateTime = inDate.getTime();
                    var outDate = new Date(check_out);var outDateTime = outDate.getTime();
                    if(thisTime >= inDateTime && thisTime <= outDateTime) {
                        if(roomStatus[j]['status'] == '0') className = 'peity_bar_better';
                        if(roomStatus[j]['status'] == '1') className = 'peity_bar_good';
                        break;
                    }
                }
                return className;
            };
            thisModule.setRoomStatus = function() {
                var days = thisModule.computeCheckDate();
                var roomStatus = thisModule.roomStatus;
                $('#room_status li').each(function(index, element) {
                    var room_id = $(this).attr('room_id');
                    var status = $(this).attr('status');
                    var className = 'peity_bar_neutral';
                    var beginDate = new Date($('#time_begin').val());
                    if(typeof(room_id) != 'undefined') {// && typeof(roomStatus[room_id]) != 'undefined'
                        var k = 0;
                        for(var i = 0; i < days; i++) {
                            if(i > 0) k = 1;
                            var thisDay = new Date(beginDate.setDate(beginDate.getDate() + k));
                            var newThisDay = thisDay.getFullYear() + '-' + (thisDay.getMonth() - 0 + 1) + '-' + thisDay.getDate();
                            if(typeof(roomStatus[room_id]) != 'undefined') {
                                className = thisModule.computeRoomStatusDate(newThisDay, roomStatus[room_id]);
                            }
                            if(status == 4) className = 'peity_bar_bad';
                            if(status == 5) className = 'peity_bar_little';
                            if(status == 3) className = 'peity_bar_small';
                            $(this).find('div').last().before(thisModule.computeRoomStatusDiv(className, newThisDay, room_id));
                            className = 'peity_bar_neutral';
                        }
                    }
                });
                maruti.peity();
            };
            thisModule.turnRoomStatus = function() {
                $('.room_status_ul .dropdown-menu a').click(function(e) {
                    var id = $(this).parent().parent().attr('data-id');
                    var status = $(this).attr('href').replace('#','');
                    $.getJSON('<%$search_url%>?module=<%$module%>&act=turn'+'&id='+id+'&status='+status, function(result) {
                        data = result;
						if(data.success == 1) {
							$('#modal_success').modal('show');
							$('#modal_success_message').html(data.message);
						} else {
							$('#modal_fail').modal('show');
							$('#modal_fail_message').html(data.message);
						}
                    })
                });
            };
            return thisModule;
        },

    }
    var thisModule = thisModuleClass.instance();
    thisModule.initParameter();
    thisModule.init();
})//console.log();
maruti = {
		// === Peity charts === //
		peity: function(){		
			$.fn.peity.defaults.line = {
				strokeWidth: 1,
				delimeter: ",",
				height: 70,
				max: null,
				min: 0,
				width: 69
			};
			$.fn.peity.defaults.bar = {
				delimeter: ",",
				height: 70,
				max: null,
				min: 0,
				width: 69
			};
			$(".peity_line_good span").peity("line", {
				colour: "#57a532",
				strokeColour: "#459D1C"
			});
            $(".peity_line_better span").peity("line", {
				colour: "#f311c8",
				strokeColour: "#f311c8"
			});
            $(".peity_line_small span").peity("line", {
				colour: "#bcb7ff",
				strokeColour: "#bcb7ff"
			});
			$(".peity_line_bad span").peity("line", {
				colour: "#FFC4C7",
				strokeColour: "#BA1E20"
			});	
            $(".peity_line_little span").peity("line", {
				colour: "#ff9900",
				strokeColour: "#ff9900"
			});	
			$(".peity_line_neutral span").peity("line", {
				colour: "#CCCCCC",
				strokeColour: "#757575"
			});
			$(".peity_bar_good span").peity("bar", {
				colour: "#459D1C"
			});
            $(".peity_bar_better span").peity("bar", {
				colour: "#f311c8"
			});
            $(".peity_bar_small span").peity("bar", {
				colour: "#bcb7ff"
			});
			$(".peity_bar_bad span").peity("bar", {
				colour: "#BA1E20"
			});	
            $(".peity_bar_little span").peity("bar", {
				colour: "#ff9900"
			});
			$(".peity_bar_neutral span").peity("bar", {
				colour: "#4fb9f0"
			});
		},

		// === Tooltip for flot charts === //
		flot_tooltip: function(x, y, contents) {
			$('<div id="tooltip">' + contents + '</div>').css( {
				top: y + 5,
				left: x + 5
			}).appendTo("body").fadeIn(200);
		}
}
</script>