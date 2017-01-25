<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<script src="<%$__RESOURCE%>js/jquery.validate.js"></script>
<style type="text/css">
.btn-group .btn {border: 1px solid #8C8585}
</style>
</head>
<body>
<%include file="hotel/inc/top_menu.tpl"%>
<div id="content">
<%include file="hotel/inc/navigation.tpl"%>
	<div class="container-fluid">
      <div class="row-fluid">
        <div class="span12">
            <div class="widget-box widget-calendar">
							
                <div class="widget-title">
                    <span class="icon"><i class="am-icon-cubes am-yellow-F58A17"></i></span>
                    <h5><%$selfNavigation.hotel_modules_name%></h5>
                    <%if $arrayRoleModulesEmployee['role_modules_action_permissions']> 1%>
                    <div class="buttons" id="btn_room_layout">
                        <a class="btn btn-primary btn-mini add_this" href="#add"><i class="am-icon-plus-square"></i> 
                        &#12288;<%$arrayLaguage['add_sell_layout']['page_laguage_value']%></a>
                    </div>
                    <%/if%>
                </div>
                <div class="widget-content nopadding">
                    <form method="post" class="form-horizontal" enctype="multipart/form-data" novalidate>
                    <div class="control-group">
                        <%foreach key=layout_id item=arrayData from=$arrayDataInfo%>
                        <label class="control-label"><%$arrayRoomLayout[$layout_id].room_layout_name%> : &#12288;<i class="am-icon-hotel"></i></label>
                        <div class="controls">
                        <%section name=i loop=$arrayData%>
                         <div class="btn-group this_edit"><a class="btn edit_checkbox" href="#view"><i class="am-icon-circle-o"></i> <%$arrayData[i].room_sell_layout_name%> <%if $arrayData[i].room_sell_layout_valid==1%><i class="am-icon-check-circle am-green-54B51C"></i><%else%><i class="am-icon-ban am-red-F13C3C"></i><%/if%> </a><a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a><ul class="dropdown-menu" data-id="<%$arrayData[i].room_sell_layout_id%>" data-name="<%$arrayData[i].room_sell_layout_name%>" room_layout="<%$arrayData[i].room_layout_id%>" valid="<%$arrayData[i].room_sell_layout_valid%>"><li class="edit_btn"><a href="#edit"><i class="am-icon-pencil am-yellow-FFAA3C"></i> Edit</a></li><li><a href="#delete"><i class="am-icon-trash am-red-FB0000"></i> Delete</a></li></ul></div>
                        <%/section%>
                        </div>
                        <%/foreach%>
                        <div class="controls">
                            <a class="btn btn-primary btn-mini add_this"><i class="icon-plus-sign"></i> <%$arrayLaguage['add_sell_layout']['page_laguage_value']%></a>
                        </div>
                     
                    </div>
                    </form>
                </div>
                <div id="edit_this" class="collapse widget-content nopadding">
                    <div class="control-group">
                        <div class="controls">
                            <form method="post" class="form-horizontal" enctype="multipart/form-data" name="edit_this_form" id="edit_this_form" novalidate>
                                <div class="modal-header">
                                    <button data-toggle="collapse" data-target="#edit_this" class="close" type="button">×</button>
                                    <h3><%$arrayLaguage['add&edit_sell_layout']['page_laguage_value']%></h3>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><%$arrayLaguage['select_base_layout']['page_laguage_value']%> :</label>
                                    <div class="controls">
                                        <select name="room_layout_id" id="room_layout_id" class="span2">
                                        <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                        <%foreach key=layout_id item=arrayLayout from=$arrayRoomLayout%>
                                            <option value="<%$layout_id%>"><%$arrayLayout.room_layout_name%></option>
                                        <%/foreach%>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><%$arrayLaguage['sell_layout_name']['page_laguage_value']%> :</label>
                                    <div class="controls">
                                        <input id="room_sell_layout_name" name="room_sell_layout_name" class="span2" placeholder="" value="" type="text">
                                        <input id="room_sell_layout_id" name="room_sell_layout_id" value="" type="hidden">
                                    </div>
                                    <label class="control-label"><%$arrayLaguage['is_valid']['page_laguage_value']%> :</label>
                                    <div class="controls">
                                        <input type="radio" name="room_sell_layout_valid" checked id="valid_1" value="1">
                                        <%$arrayLaguage['valid']['page_laguage_value']%>&#12288;
                                        <input type="radio" name="room_sell_layout_valid" id="valid_0" value="0">
                                        <%$arrayLaguage['no_avail']['page_laguage_value']%>

                                    </div>
                                </div>
                                <div class="control-group"> 
                                    <div class="controls">
                                    <%if $arrayRoleModulesEmployee['role_modules_action_permissions']> 1%>
                                    <button type="submit" id="save_info" class="btn btn-success pagination-centered">Save</button> <a data-toggle="collapse" data-target="#edit_this" class="btn" href="#">Cancel</a> 
                                    <%/if%>
                                    </div>  
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                
                <div class="widget-content">
                    
                </div>
            </div>   
        </div>
					
	  </div>
    
    </div>
</div>
<%include file="hotel/inc/footer.tpl"%>
<%include file="hotel/inc/modal_box.tpl"%>
<script language="javascript">
$(document).ready(function(){
    var RoomsSellLayoutClass = {
        instance: function() {
            var roomsSellLayout = {};
            roomsSellLayout.initParameter = function() {
            };
            roomsSellLayout.init = function() {
                $('.this_edit .edit_btn').click(function(e) {
                    $('.this_edit .edit_checkbox i').removeClass('am-icon-dot-circle-o');
                    $(this).parent().parent().find('i').first().addClass('am-icon-dot-circle-o');
                    $('#room_layout_id').val($(this).parent().attr('room_layout'));
                    $('#room_sell_layout_name').val($(this).parent().attr('data-name'));
                    $('#room_sell_layout_id').val($(this).parent().attr('data-id'));
                    $valid = $(this).parent().attr('valid');
                    //if(valid == 1) {
                        $('#valid_' + $(this).parent().attr('valid')).attr('checked', true);
                    //}
                    $('#edit_this').collapse({toggle: true})
                    $('#edit_this').collapse('show');
                });
                $('.add_this').click(function(e) {
                    $('.this_edit .edit_checkbox i').removeClass('am-icon-dot-circle-o');
                    $('#room_layout_id').val('');
                    $('#room_sell_layout_name').val('');
                    $('#room_sell_layout_id').val('');
                    $('#valid_1').attr('checked', true);
                    $('#edit_this').collapse({toggle: true})
                    $('#edit_this').collapse('show');
                });
                $('#room_layout_id').change(function(e) {
                    if($(this).val() == 0) {
                        $('#room_sell_layout_name').val('');
                        $('#room_sell_layout_id').val('');
                    } else {
                    }
                });
            };
            return roomsSellLayout;
        },
        
    }
    var roomsSellLayout = RoomsSellLayoutClass.instance();
    roomsSellLayout.init();
    
    var edit_this_form_validate = $("#edit_this_form").validate({
		rules: {
			room_layout_id: {required: true},
            room_sell_layout_name: {required: true},
		},
		messages: {
			hotel_service_name:"",
            room_layout_id:"",
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
            var param = $("#edit_this_form").serialize();
            var url = '';
            var add = '<%$add_url%>';
            var edit = '<%$edit_url%>';
            url = add;
            if($('#room_layout_id').val() > 0) url = edit;
            $.ajax({
                url : url,
                type : "post",dataType : "json",data: param,
                success : function(result) {
                    data = result;
                    if(data.success == 1) {
                        $('#modal_success').modal('show');
                        $('#modal_success_message').html(data.message);
                        
                    } else {
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                }
            });
            return;
		}
	});

})
</script>
</body>
</html>