<script language="javascript">
var room_layout_id = '<%$room_layout_id%>';
var url = '<%$add_room_layout_url%>';
$(document).ready(function(){
	// Form Validation
    var v = $("#add_room_layout_form").validate({
		rules:{
			room_layout_name: {required:true,minlength:2,maxlength:50},
			room_layout_valid:{required:true},
			//room_layout_area:{required:true,digits:true},
			//room_layout_orientations:{required:true},
			//room_layout_max_people:{required:true,number:true,minlength:1,maxlength:5},
			//room_layout_max_children:{required:true,number:true},
			//room_layout_extra_bed:{required:true,number:true},
            room_layout_type_id:{required:true}
		},
		messages: {
			room_layout_name:"请输入房型名称，2~50个字符",
			//room_layout_area:"必须是整数",
			//room_layout_orientations:"请选择朝向",
			//room_layout_max_people:"请输最多住几人，只能是整数",
			//room_layout_max_children:"请输最多住几个小孩，必须是0和整数",
			//room_layout_extra_bed:"请输可加床数，必须是0和整数",
            room_layout_type_id:""
		},
		errorClass: "help-inline",
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
			$('#room_layout_attr a').tab('show');
		}
	});
	$('#room_layout_attr').click(function() {
		if (v.form()) {
			if(room_layout_id == '') return false;
		} else {
			return false;
		}
	});
	$('#room_layout_images').click(function() {
		if (v.form()) {
			if(room_layout_id == '') return false;
		} else {
			return false;
		}
	});
	$('#set_room').click(function() {
		if (v.form()) {
			if(room_layout_id == '') return false;
		} else {
			return false;
		}
	});
	$('#room_layout_price_setting').click(function() {
		if (v.form()) {
			if(room_layout_id == '') return false;
		} else {
			return false;
		}
	});
	var v_server = $('#add_room_layout_attr_form').validate({
		submitHandler: function() {
			var param = $("#add_room_layout_form").serialize();
			$.ajax({
			   url : url,
			   type : "post",
			   dataType : "json",
			   data: param,
			   success : function(data) {
			       if(data.success == 1) {
					   room_layout_id = data.itemData.room_layout_id;
					   saveRoomLayoutAttrValue();
					   /*$('#modal_fail').modal('hide');
					   $('#modal_success').modal('show');
					   $('#modal_success_message').html(data.message);
					   $('#modal_success').on('hidden.bs.modal', function () {
							
					   })*/
			       } else {
					   $('#modal_fail').modal('show');
					   $('#modal_fail_message').html(data.message);
			       }
			   }
			 });
			 
			
		}
	});
	function saveRoomLayoutAttrValue() {
		var param = $("#add_room_layout_attr_form").serialize();
        var view = '<%$view%>';
		$.ajax({
		   url : '<%$add_room_layout_attr_url%>&room_layout_id=' + room_layout_id,
		   type : "post",
		   dataType : "json",
		   data: param,
		   success : function(data) {
			   if(data.success == 1) {
				   //$('#modal_fail').modal('hide');
				   $('#modal_success').modal('show');
				   $('#modal_success_message').html(data.message)
				   $('#modal_success').on('hidden.bs.modal', function () {
					    if(view == 'add') {
                            if(data.redirect != '') {
                               window.location = data.redirect;
                            }
                        } else {
							$('#set_room a').tab('show');
                        }
				   })
			   } else {
				   //$('#modal_success').modal('hide');
				   $('#modal_fail').modal('show');
				   $('#modal_fail_message').html(data.message);
			   }
		   }
		 });
	}
    var step = '<%$step%>';
	if(step == 'upload_images') {$('#room_layout_images a').tab('show');}
	$('.addAttr').click(function(e) {
		$(this).before(" ").prev().clone().insertBefore(this).after(" ");
    });
    $('#room_next').click(function(e) {
        $('#room_layout_images a').tab('show');
    });
    $('#room_bed_type_num').change(function(e) {
        console.log($('.bed').attr('checked'));
        if($('.bed').attr('checked') == 'checked') {
            setBedNum();
        }
    });
    $('.bed').click(function(e) {
        setBedNum();        
    });
    $('.special_bed').click(function(e) {
        $('#bed_extra_div').hide('fast');
    });
    function setBedNum() {
        var num = $('#room_bed_type_num').val();//bed_extra_demo
        var data = JSON.parse($('#bed_extra').attr('data'));
        var html = setBedSelectHtml('');
        var bed_extra_html = '';
        var continue_num = 0;
        if(data != '') {
            for(var i in data) {
                if(i >= num) {
                    continue_num = num;
                    break;
                }
                var html = setBedSelectHtml(data[i]);
                bed_extra_html += '<span class="add-on">'+ (i - 0 + 1) +'.床宽</span>' + html;
                continue_num = i;
            }
        }
        continue_num++;
        for(var i = continue_num; i < num; i++) {
            bed_extra_html += '<span class="add-on">'+ (i - 0 + 1) +'.床宽</span>' + html;
        }
        $('#bed_extra').html(bed_extra_html);
        $('#bed_extra_div').show('fast');
    }
    function beginSetBedNum() {
        var data = JSON.parse($('#bed_extra').attr('data'));
        var bed_extra_html = '';
        if(data != '') {
            for(var i in data) {
                var html = setBedSelectHtml(data[i]);
                bed_extra_html += '<span class="add-on">'+ (i - 0 + 1) +'.床宽</span>' + html;
            }
            $('#bed_extra').html(bed_extra_html);
            $('#bed_extra_div').show('fast');
        }
    }
    if($('.bed').attr('checked') == 'checked') {
        beginSetBedNum();
    }
    function setBedSelectHtml(value) {
        var option = JSON.parse('<%$layoutHouseConfig%>');
        var html = '<select name="room_bed_type_wide[]" class="input-small" >';
        var selected = '';
        var option_html = '';
        for(var val in option) {
            if(val == value) selected = 'selected';
            option_html += '<option value="'+val+'"' + selected +'>'+option[val]+'</option>';
            selected = '';
        }
        html = html + option_html + '</select>';
        return html;
    }
});
</script>
<%if $view!='1'%>
<script language="javascript">
    $('#rooms .selectRoom').click(function(e) {
        $('.alert.alert-success.alert-block').hide();
        var thisVal = $(this).attr('value');
        var checked = $(this).attr('check') - 0 > 0 ? 'false' : 'true';
        console.log(this);
        var extra_bed = $('#extra_bed_' + thisVal).val();
        var max_people = $('#max_people_' + thisVal).val();
        var max_children = $('#max_children_' + thisVal).val();
        if(typeof(extra_bed) == 'undefined') extra_bed = 0;
        if(typeof(max_people) == 'undefined') max_people = 0;
        if(typeof(max_children) == 'undefined') max_children = 0;
        if(max_people == 0) {
            $('#modal_info').modal('show');
		    $('#modal_info_message').html("请先填写最多住几人！人数大于或等于1");
            return;
        }
        $('#modal_save').show('slow');
        var url = '<%$add_room_layout_url%>&act=setRoomLayoutRoom&checked=' + checked + '&room_id=' + thisVal 
                + '&extra_bed=' + extra_bed+ '&max_people=' + max_people+ '&max_children=' + max_children;
        var _this = this;
        $.getJSON(url, function(result) {
            data = result;
            $('#modal_save').hide();
            $('.alert.alert-success.alert-block').show("slow");
            setTimeout(function(){$(".alert.alert-success.alert-block").hide("slow");}, 1000);
            if(checked == 'false') {
                $(_this).addClass('am-icon-square-o').removeClass('am-icon-check-square');
                $(_this).attr('check', 0);
            } else {
                $(_this).addClass('am-icon-check-square').removeClass('am-icon-square-o');
                $(_this).attr('check', 1);
            }
        })
    });
    $('#rooms :text').keyup(function(e) {
        var key = e.which;
        if((key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {
            $('.alert.alert-success.alert-block').hide();
            var data_id = $(this).attr('data-id');
            var checked = $('#'+data_id).attr('check') - 0;
            if(checked > 0) {
                $('#modal_save').show();
                var extra_bed = $('#extra_bed_' + data_id).val();
                var max_people = $('#max_people_' + data_id).val();
                var max_children = $('#max_children_' + data_id).val();
                if(typeof(extra_bed) == 'undefined') extra_bed = 0;
                if(typeof(max_people) == 'undefined') max_people = 0;
                if(typeof(max_children) == 'undefined') max_children = 0;
                var url = '<%$add_room_layout_url%>&act=setRoomLayoutRoom&checked=true&room_id=' + $(this).attr('data-id') 
                        + '&extra_bed=' + extra_bed+ '&max_people=' + max_people+ '&max_children=' + max_children;
                $.getJSON(url, function(result) {
                    data = result;
                    $('#modal_save').hide();
                    $('.alert.alert-success.alert-block').show("slow");
                    setTimeout(function(){$(".alert.alert-success.alert-block").hide("slow");}, 1000);
                })
            }
        }
    });
</script>    
<%/if%>
<%if $view=='1'%>
<script language="javascript">
$("form input,textarea,select").prop("readonly", true);
$('.save_info').hide();
$('#upload_images').hide();
</script>
<%/if%>
<script language="javascript">
function uploadSuccess(img_url, id) {
	if(id == '') {
		img_url = img_url.replace('/data/images/', '');//<%$upload_images_url%>
		$.getJSON(url + '&act=updateLayoutImages&room_layout_id=' 
				  + room_layout_id + '&url=' + img_url, function(data){
			if(data.success == 1) {
			   id = data.itemData.room_layout_images_id;
			   addLayoutImages(img_url, id);
			} else {
			   $('#modal_success').modal('hide');
			   $('#modal_fail').modal('show');
			   $('#modal_fail_message').html(data.message);
			}
		});
	} else {
		addLayoutImages(img_url, id);
	}
}

function addLayoutImages(img_url, id) {
	var html = '<li class="span2"><a class="thumbnail lightbox_trigger" href="'+img_url
	          +'"><img id="room_layout_'+id+'" src="<%$__IMGWEB%>'+img_url+'" alt="" ></a>'
              +'<div class="actions">'
              +'<a title="" href="#"><i class="icon-pencil icon-white"></i></a>'
              +'<a title="" href="#"><i class="icon-remove icon-white"></i></a>'
              +'</div></li>';
	$('.thumbnails').append(html);
}
</script>