<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<style type="text/css">
/*.modal-body{ padding:1px;}
.widget-box{margin-bottom:1px; margin-top:1px;}*/
</style>
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
                        <i class="icon-th-list"></i>
                    </span>
                    <h5><%$arrayLaguage['hotel_attribute_setting']['page_laguage_value']%></h5>
                    <%if $arrayRoleModulesEmployee['role_modules_action_permissions']> 0%>
                    <div class="buttons">
                        <a class="btn btn-primary btn-mini" href="#addHotelAttr" url="<%$add_room_attribute_url%>" id="add_attribute" data-toggle="modal"><i class="am-icon-plus-square"></i>
                        &#12288;<%$arrayLaguage['add_hotel_attribute']['page_laguage_value']%></a>
                    </div>
                    <%/if%>
                </div>
                <div class="widget-content tab-content nopadding">
                <form class="form-horizontal" enctype="multipart/form-data" name="modify_attr_classes" id="modify_attr_classes" novalidate>
                <%section name=attr loop=$arrayAttribute%>
                    <div class="control-group">
                        <label class="control-label">
                        <%if $arrayAttribute[attr].hotel_id==0%><%$arrayAttribute[attr].hotel_attribute_name%><%else%>
                        <input type="text" class="span5" value="<%$arrayAttribute[attr].hotel_attribute_name%>" name="<%$arrayAttribute[attr].hotel_attribute_id%>" />
                        <%/if%> :
                        </label>
                        <div class="controls">
                        <%section name=attr_children loop=$arrayAttribute[attr].children%>
                            <input type="text" class="span1" value="<%$arrayAttribute[attr].children[attr_children].hotel_attribute_name%>"<%if $arrayAttribute[attr].children[attr_children].hotel_id==0%> disabled<%else%> name="<%$arrayAttribute[attr].children[attr_children].hotel_attribute_id%>"<%/if%> />
                        <%/section%>
                        </div>
                    </div>
                <%/section%>
                <div class="controls">
                    <a href="#addHotelAttr" class="btn btn-primary btn-mini" data-toggle="modal"><i class="icon-plus-sign"></i> <%$arrayLaguage['add_customize_attr']['page_laguage_value']%></a> 
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
<div id="addHotelAttr" class="modal hide" style="display: none;" aria-hidden="true">
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
                        <select id="hotel_attribute_id" name="hotel_attribute_id" class="span2">
                            <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                            <option value="0">增加新属性类别</option>
                            <%section name=attr loop=$arrayAttribute%>
                            <option value="<%$arrayAttribute[attr].hotel_attribute_id%>"><%$arrayAttribute[attr].hotel_attribute_name%></option>
                            <%/section%>
                         </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><%$arrayLaguage['attr_name']['page_laguage_value']%> :</label>
                    <div class="controls">
                        <input id="hotel_attribute_name" name="hotel_attribute_name" class="span2" placeholder="" value="" type="text">
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
	$('.addHotelAttr').click(function(e) {
        $('#identifier').modal('show')
    });
	
	$("#add_attr_classes").validate({
		rules:{
			hotel_attribute_id:{
				required:true
			},
			hotel_layout_attribute_name:{
				required:true
			}
		},
		messages: {
			hotel_attribute_id:"请选择属性类别",
			hotel_attribute_name:"请填写属性名称",
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
			   url : "<%$add_hotel_attribute_url%>",
			   type : "post",
			   dataType : "json",
			   data: param,
			   success : function(data) {
			       if(data.success == 1) {
					   $('#addHotelAttr').modal('hide');
					   $('#modal_success').modal('show');
					   $('#modal_success_message').html(data.message);
					   $('#modal_success').on('hide.bs.modal', function () {
							window.location.reload();
					   });
					   $('#hotel_attribute_name').val('');
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
	   url : "<%$delete_hotel_attribute_url%>",
	   type : "post",
	   dataType : "json",
	   data: param,
	   success : function(data) {
		   if(data.success == 1) {
			   $('#addHotelAttr').modal('hide');
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