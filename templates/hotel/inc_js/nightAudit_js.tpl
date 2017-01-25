<script language="javascript">
$(document).ready(function(){
	// === Prepare peity charts === //
	
    //日历
	$.datetimepicker.setLocale('ch');
	$('#time_begin').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, 
        yearStart: '1980', yearEnd: '<%$nextYear%>', //yearOffset:1,maxDate:'+1970-01-02',
		beforeShowDay: function(date) {
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
            };
            thisModule.init = function() {
                $('#begin_night_audit').click(function(e) {
                    window.location.href="<%$search_url%>?module=<%$module%>&act=night_audit"; 
                });
                $('#check_night_audit').click(function(e) {
                    thisModule.checkNightAudit();
                });
            };
            thisModule.checkErrorNightAudit = function() {
                $('.error_night_audit').each(function(index, element) {
                    
                });
            };
            thisModule.checkNightAudit = function() {
                var nightAudit = {};var room = {};
                $('.nightAudit').each(function(index, element) {
                    var data_id = $(this).attr('data-id');
                    var number = $(this).attr('number');
                    var room_id = $(this).attr('room_id');
                    nightAudit[data_id] = number;
                    room[room_id] = '';
                });
                if(nightAudit == '') {
                    $('#modal_info').modal('show');
                    $('#modal_info_message').html('无数据!');
                    return;
                }
                var param = 'key='+JSON.stringify(nightAudit)+'&room='+JSON.stringify(room);
                var url = '<%$nightAuditUrl%>';
                $.getJSON(url, param, function(result){
                    data = result;
                    if(data.success == 1) {
                        $('#modal_success').modal('show');
                        $('#modal_success_message').html(data.message);
                    } else {
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                })
            };
            return thisModule;
        },

    }
    var thisModule = thisModuleClass.instance();
    thisModule.initParameter();
    thisModule.init();
})//console.log();
</script>