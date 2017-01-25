<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<script src="<%$__RESOURCE%>js/jquery.validate.js"></script>
</head>
<style type="text/css">
.form-horizontal .control-label {padding-top: 10px;}
</style>
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
                        <i class="icon-th-list"></i>
                    </span>
                    <h5><%$arrayLaguage['attribute_setting']['page_laguage_value']%></h5>
                    <%if $arrayRoleModulesEmployee['role_modules_action_permissions']> 0%>
                    <div class="buttons">
                        <a class="btn btn-primary btn-mini" href="#addLayoutAttr" url="<%$add_room_attribute_url%>" id="add_attribute" data-toggle="modal"><i class="am-icon-plus-square"></i>
                        &#12288;<%$arrayLaguage['add_attribute_setting']['page_laguage_value']%></a>
                    </div>
                    <%/if%>
                </div>
                <div class="widget-content tab-content nopadding">
                <form class="form-horizontal" enctype="multipart/form-data" name="modify_attr_classes" id="modify_attr_classes" novalidate>
                <div class="control-group">
                    <label class="control-label"><%$arrayLaguage['room_setting_type']['page_laguage_value']%> :</label>
                    <div class="controls ">
                        <select id="room_type" name="room_type" class="span1" disabled>
                            <%foreach key=type_key item=item from=$arayRoomType%>
                            <%if $item==1%>
                            <option value="<%$type_key%>"<%if $type_key==$room_type%> selected<%/if%>><%$arrayLaguage[$type_key]['page_laguage_value']%></option>
                            <%/if%>
                            <%/foreach%>
                        </select>
                    </div>
                </div>
                <%section name=attr loop=$arrayAttribute%>
                    <div class="control-group">
                        <label class="control-label">
                        <!--<div class="btn-group">
                            <a class="btn btn-inverse edit_checkbox" href="#view"><i class="am-icon-circle-o"></i> <%$arrayAttribute[attr].room_layout_attribute_name%></a><a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                            <ul class="dropdown-menu" data-id="21" data-name="客房布置" father-id="21" price="-1"><li class="edit_btn"><a href="#edit"><i class="am-icon-pencil am-yellow-FFAA3C"></i> Edit</a></li></ul>
                        </div>-->
                        <%if $arrayAttribute[attr].hotel_id==0%><%$arrayAttribute[attr].room_layout_attribute_name%><%else%>
                        <input type="text" class="span5" value="<%$arrayAttribute[attr].room_layout_attribute_name%>" name="<%$arrayAttribute[attr].room_layout_attribute_id%>" />
                        <%/if%> :
                        </label>
                        <div class="controls">
                        <%section name=attr_children loop=$arrayAttribute[attr].children%>
                            <input type="text" class="span1" value="<%$arrayAttribute[attr].children[attr_children].room_layout_attribute_name%>"<%if $arrayAttribute[attr].children[attr_children].hotel_id==0%> disabled<%else%> name="<%$arrayAttribute[attr].children[attr_children].room_layout_attribute_id%>"<%/if%> />
                        <%/section%>
                        </div>
                    </div>
                <%/section%>
                <div class="controls">
                    <a href="#addLayoutAttr" class="btn btn-primary btn-mini" data-toggle="modal"><i class="icon-plus-sign"></i> <%$arrayLaguage['add_customize_attr']['page_laguage_value']%></a> 
                    <%if $arrayRoleModulesEmployee['role_modules_action_permissions'] > 2%>
                    <a href="#modal_update" class="btn btn-primary btn-mini" data-toggle="modal"><i class="icon-wrench"></i> <%$arrayLaguage['modify_customize_attr']['page_laguage_value']%></a>
                    <%/if%>
                </div>
                <div class="form-actions pagination-centered btn-icon-pg"></div>
               </form>
           	   </div>
            </div>   
        </div>
					
	  </div>
    
    </div>
</div>
</div>
<div id="addLayoutAttr" class="modal hide" style="display: none;" aria-hidden="true">
<form action="" method="post" class="form-horizontal" enctype="multipart/form-data" name="add_attr_classes" id="add_attr_classes" novalidate>
  <div class="modal-header">
    <button data-dismiss="modal" class="close" type="button">×</button>
    <h3><%$arrayLaguage['add_customize_attr']['page_laguage_value']%></h3>
  </div>
  <div class="modal-body">
      <div class="widget-box">
        <div class="widget-content tab-content nopadding">
                <div class="control-group">
                    <label class="control-label"><%$arrayLaguage['attr_classes']['page_laguage_value']%> :</label>
                    <div class="controls">
                        <select id="room_layout_attribute_id" name="room_layout_attribute_id" class="span2">
                            <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                            <option value="0"><%$arrayLaguage['add_attr_classes']['page_laguage_value']%></option>
                            <%section name=attr loop=$arrayAttribute%>
                            <option value="<%$arrayAttribute[attr].room_layout_attribute_id%>"><%$arrayAttribute[attr].room_layout_attribute_name%></option>
                            <%/section%>
                         </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><%$arrayLaguage['attr_name']['page_laguage_value']%> :</label>
                    <div class="controls">
                        <input id="room_layout_attribute_name" name="room_layout_attribute_name" class="span2" placeholder="" value="" type="text">
                    </div>
                </div>
         </div>
      </div>
  </div>
  <div class="modal-footer"> <button type="submit" id="save_info" class="btn btn-success pagination-centered">Save</button> <a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
</form>
</div>
<%include file="hotel/inc/footer.tpl"%>
<%include file="hotel/inc/modal_box.tpl"%>
<script language="javascript">
$(document).ready(function(){	
	$('.addLayoutAttr').click(function(e) {
        $('#identifier').modal('show')
    });
	
	$("#add_attr_classes").validate({
		rules:{
			room_layout_attribute_id:{
				required:true
			},
			room_layout_attribute_name:{
				required:true
			}
		},
		messages: {
			room_layout_attribute_id:"请选择属性类别",
			room_layout_attribute_name:"请填写属性名称",
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
			var param = $("#add_attr_classes").serialize();
			$.ajax({
			   url : "<%$add_room_attribute_url%>",
			   type : "post",
			   dataType : "json",
			   data: param,
			   success : function(data) {
			       if(data.success == 1) {
					   $('#addLayoutAttr').modal('hide');
					   $('#modal_success').modal('show');
					   $('#modal_success_message').html(data.message);
					   $('#modal_success').on('hide.bs.modal', function () {
							window.location.reload();
					   });
					   $('#room_layout_attribute_name').val('');
			       } else {
					   $('#modal_fail').modal('show');
					   $('#modal_fail_message').html(data.message);
			       }
			   }
			 });
		}
	});
});//add_attr_classes

function update_sumbit() {		
	var param = $("#modify_attr_classes").serialize();
	$.ajax({
	   url : "<%$delete_room_attribute_url%>",
	   type : "post",
	   dataType : "json",
	   data: param,
	   success : function(data) {
		   if(data.success == 1) {
			   $('#addLayoutAttr').modal('hide');
			   $('#modal_success').modal('show');
			   $('#modal_success_message').html(data.message);
			   $('#modal_success').on('hide.bs.modal', function () {
					window.location.reload();
			   });
			   $('#room_layout_attribute_name').val('');
		   } else {
			   $('#modal_fail').modal('show');
			   $('#modal_fail_message').html(data.message);
		   }
	   }
	 });
}
</script>

</body>
</html>