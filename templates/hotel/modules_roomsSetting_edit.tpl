<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<script src="<%$__RESOURCE%>js/jquery.validate.js"></script>
</head>
<body>
<%include file="hotel/inc/top_menu.tpl"%>
<div id="content">
<%include file="hotel/inc/navigation.tpl"%>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon">
                        <i class="icon-align-justify"></i>									
                    </span>
                    <h5><%$arrayLaguage['add_hotel_rooms']['page_laguage_value']%></h5>
                    <div class="buttons">
                    <a class="btn btn-primary btn-mini" href="<%$back_lis_url%>" id="back_list"><i class="am-icon-arrow-circle-left"></i> 
                    &#12288;<%$arrayLaguage['back_list']['page_laguage_value']%></a>	
                    <%if $arrayRoleModulesEmployee['role_modules_action_permissions']> 1%>
						<a class="btn btn-primary btn-mini" id="edit_info"><i class="icon-pencil"></i>
                        　<%$arrayLaguage['edit']['page_laguage_value']%></a>	
                        <a class="btn btn-primary btn-mini" id="cancel_edit_info"><i class="icon-pencil"></i>
                        　<%$arrayLaguage['cancel_edit']['page_laguage_value']%></a>
                    <%/if%>
					</div>                 
                </div>
                <div class="widget-content nopadding">
                    <form action="<%$add_room_url%>" method="post" class="form-horizontal" enctype="multipart/form-data" name="add_room_form" id="add_room_form" novalidate> 
                        <div class="control-group">
                            <label class="control-label"><%$arrayLaguage['room_setting_type']['page_laguage_value']%> :</label>
                            <div class="controls ">
                                <select id="room_type" name="room_type" class="span1">
                                    <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                    <%foreach key=room_type_id item=item from=$arrayRoomType%>
                                    <option value="<%$room_type_id%>"<%if $room_type_id==$arrayDataInfo['room_type']%> selected<%/if%>><%$item.room_type_name%></option>
                                    <%/foreach%>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><%$arrayLaguage['room_name']['page_laguage_value']%> :</label>
                            <div class="controls"><input type="text" class="span3" placeholder="<%$arrayLaguage['room_name']['page_laguage_value']%>" name="room_name" id="room_name" value="<%$arrayDataInfo['room_name']%>" /> </div>
                        </div>
  						<div class="control-group">
                            <label class="control-label"><%$arrayLaguage['room_mansion']['page_laguage_value']%> :</label>
                            <div class="controls"><input type="text" class="span2" placeholder="<%$arrayLaguage['room_mansion']['page_laguage_value']%>" name="room_mansion" id="room_mansion" value="<%$arrayDataInfo['room_mansion']%>" /> </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><%$arrayLaguage['room_floor']['page_laguage_value']%> :</label>
                            <div class="controls"><input type="text" class="span1" placeholder="<%$arrayLaguage['room_floor']['page_laguage_value']%>" name="room_floor" id="room_floor" value="<%$arrayDataInfo['room_floor']%>" /> </div>
                        </div>
  						<div class="control-group">
                            <label class="control-label"><%$arrayLaguage['room_number']['page_laguage_value']%> :</label>
                            <div class="controls"><input type="text" class="span1" placeholder="<%$arrayLaguage['room_number']['page_laguage_value']%>" name="room_number" id="room_number" value="<%$arrayDataInfo['room_number']%>" /> </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">使用面积 :</label>
                            <div class="controls"><input type="text" class="span1" placeholder="<%$arrayLaguage['room_area']['page_laguage_value']%>" name="room_area" id="room_area" value="<%$arrayDataInfo['room_area']%>" /> </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">空间朝向 :</label>
                            <div class="controls">
                                <select name="room_orientations" id="room_orientations" class="span1">
                                    <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                    <%section name=direction loop=$orientations%>
                                        <option value="<%$orientations[direction]%>"<%if $orientations[direction]==$arrayDataInfo['room_orientations']%> selected<%/if%>><%$arrayLaguage[$orientations[direction]]['page_laguage_value']%></option>
                                    <%/section%>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">景观朝向 :</label>
                            <div class="controls"><input type="text" class="span1" placeholder="景观朝向" name="room_landscape" id="room_landscape" value="<%$arrayDataInfo['room_landscape']%>" /> </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><%$arrayLaguage['describe']['page_laguage_value']%></label>
                            <div class="controls">
                                <textarea class="span5" style="height:300px;"  placeholder="<%$arrayLaguage['describe']['page_laguage_value']%>" name="room_describe" value="<%$arrayDataInfo['room_describe']%>" ><%$arrayDataInfo['room_describe']%></textarea>
                            </div>
                        </div>
                        
                        <div class="form-actions pagination-centered">
                            <button type="submit" id="save_info" class="btn btn-success pagination-centered">Save</button>
                        </div>
                    </form>
                </div>
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
	// Form Validation
    $("#add_room_form").validate({
		rules:{
			room_type:{
				required:true
			},
			room_name:{
				required:true,
				maxlength:50
			},
			room_number:{
				required:true,
				"isNumbersAndLetters-":true,
				minlength:1,
				maxlength:20
			},
			room_mansion:{
				"isNumbersAndLetters-":true,
				maxlength:50
			},
			room_floor:{
				required:true,
				"isNumbersAndLetters-":true,
				minlength:1,
				maxlength:20
			},
			room_area:{
				required:true,
				number:true,
				minlength:1,
				maxlength:5
			},
            room_orientations:{required:true,}
		},
		messages: {
			room_type:"请选择房间类型",
			room_number:"请输入房间号，只能包括英文字母、数字和-",
			room_name:"请输入房间名称",
			room_floor:"请输入房间楼层，只能包括英文字母、数字和-",
			room_mansion:"请输入房间楼层，只能包括英文字母、数字和-",
			room_area:"请填写房间面积，只能是是数字",
            room_orientations:""
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
			var param = $("#add_room_form").serialize();
			$.ajax({
			   url : "<%$add_room_url%>",
			   type : "post",
			   dataType : "json",
			   data: param,
			   success : function(data) {
			       if(data.success == 1) {
					   $('#modal_success').modal('show');
					   $('#modal_success_message').html(data.message);
					   if(data.redirect != '') {
							$('#modal_success').on('hide.bs.modal', function () {
								window.location = data.redirect;
							})
					   }
			       } else {
					   $('#modal_fail').modal('show');
					   $('#modal_fail_message').html(data.message);
			       }
			   }
			 });
		}
	});
	
});
<%if $view==1%>
$("form input,textarea,select").prop("readonly", true);
$('#save_info').hide();
$('#cancel_edit_info').hide();
$('#edit_info').click(function(e) {
		$("form input,textarea,select").prop("readonly", false);
		$(this).hide();
		$('#cancel_edit_info').show();
		$('#save_info').show();
});
$('#cancel_edit_info').click(function(e) {
	$("form input,textarea,select").prop("readonly", true);
	$(this).hide();
	$('#edit_info').show();
	$('#save_info').hide();
});
<%else%>
$('#cancel_edit_info').hide();
$('#edit_info').hide();
<%/if%>
</script>
</body>
</html>