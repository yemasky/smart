<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<style type="text/css">
.form-horizontal .control-label{padding-top:10px;}
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
            <div class="widget-box widget-calendar">
							
                <div class="widget-title">
                    <span class="icon"><i class="am-icon-cubes am-yellow-F58A17"></i></span>
                    <h5><%$arrayLaguage['accessorial_service']['page_laguage_value']%></h5>
                    <div class="buttons" id="btn_room_layout">
                        <a class="btn btn-primary btn-mini add_accessorial" href="#add"><i class="am-icon-plus-square"></i> 
                        &#12288;<%$arrayLaguage['add_accessorial_service']['page_laguage_value']%></a>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <form method="post" class="form-horizontal" enctype="multipart/form-data" novalidate>
                    <div class="control-group">
                     <%section name=i loop=$arrayData%>
                        <label class="control-label accessorial_edit">
                        <div class="btn-group">
                            <a class="btn btn-inverse edit_checkbox" href="#view"><i class="am-icon-circle-o"></i> <%$arrayData[i].hotel_service_name%></a><a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                            <ul class="dropdown-menu" data-id="<%$arrayData[i].hotel_service_id%>" data-name="<%$arrayData[i].hotel_service_name%>" father-id="<%$arrayData[i].hotel_service_father_id%>" price="<%$arrayData[i].hotel_service_price%>"><li class="edit_btn"><a href="#edit"><i class="am-icon-pencil am-yellow-FFAA3C"></i> Edit</a></li><%if $arrayData[i].children==''%><li><a href="#delete"><i class="am-icon-trash am-red-FB0000"></i> Delete</a></li><%/if%></ul>
                            
                        </div>
                        </label>
                        <div class="controls accessorial_edit">
                        <%section name=j loop=$arrayData[i].children%>
                            <div class="btn-group"><a class="btn edit_checkbox" href="#view"><i class="am-icon-circle-o"></i> <%$arrayData[i].children[j].hotel_service_name%> <i class="am-icon-rmb am-yellow-F58A17"></i> <%$arrayData[i].children[j].hotel_service_price%></a><a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a><ul class="dropdown-menu" data-id="<%$arrayData[i].children[j].hotel_service_id%>" data-name="<%$arrayData[i].children[j].hotel_service_name%>" father-id="<%$arrayData[i].children[j].hotel_service_father_id%>" price="<%$arrayData[i].children[j].hotel_service_price%>"><li class="edit_btn"><a href="#edit"><i class="am-icon-pencil am-yellow-FFAA3C"></i> Edit</a></li><li><a href="#delete"><i class="am-icon-trash am-red-FB0000"></i> Delete</a></li></ul></div>
                        <%/section%>    
                        </div>
                        <%/section%>
                        <div class="controls">
                            <a class="btn btn-primary btn-mini add_accessorial"><i class="icon-plus-sign"></i> <%$arrayLaguage['add_accessorial_service']['page_laguage_value']%></a>
                        </div>
                     
                    </div>
                    </form>
                </div>
                <div id="edit_accessorial" class="collapse widget-content nopadding">
                    <div class="control-group">
                        <div class="controls">
                            <form method="post" class="form-horizontal" enctype="multipart/form-data" name="edit_accessorial_form" id="edit_accessorial_form" novalidate>
                                <div class="modal-header">
                                    <button data-toggle="collapse" data-target="#edit_accessorial" class="close" type="button">×</button>
                                    <h3><%$arrayLaguage['add_or_edit_accessorial_service']['page_laguage_value']%></h3>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><%$arrayLaguage['select_classes']['page_laguage_value']%> :</label>
                                    <div class="controls">
                                        <select name="hotel_service" id="hotel_service" class="span2">
                                        <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                        <option value="0"><%$arrayLaguage['new_service_classes']['page_laguage_value']%></option>
                                        <%section name=i loop=$arrayData%>
                                            <option value="<%$arrayData[i].hotel_service_id%>"><%$arrayData[i].hotel_service_name%></option>
                                        <%/section%>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><%$arrayLaguage['service_name']['page_laguage_value']%> :</label>
                                    <div class="controls">
                                        <input id="hotel_service_name" name="hotel_service_name" class="span2" placeholder="" value="" type="text">
                                        <input id="hotel_service_id" name="hotel_service_id" value="" type="hidden">
                                    </div>
                                    <label class="control-label"><%$arrayLaguage['price']['page_laguage_value']%> :</label>
                                    <div class="controls">
                                        <input id="hotel_service_price" name="hotel_service_price" class="span2" placeholder="" value="" type="text">(<%$arrayLaguage['0_free']['page_laguage_value']%>)
                                    </div>
                                </div>
                                <div class="control-group"> 
                                    <div class="controls"><button type="submit" id="save_info" class="btn btn-success pagination-centered">Save</button> <a data-toggle="collapse" data-target="#edit_accessorial" class="btn" href="#">Cancel</a> 
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
    var AccessorialServiceClass = {
        instance: function() {
            var accessorialService = {};
            accessorialService.accessorial = function() {
                $('.accessorial_edit .edit_btn').click(function(e) {
                    $('.accessorial_edit .edit_checkbox i').removeClass('am-icon-dot-circle-o');
                    $(this).parent().parent().find('i').first().addClass('am-icon-dot-circle-o');
                    $('#hotel_service').val($(this).parent().attr('father-id'));
                    $('#hotel_service_price').val($(this).parent().attr('price'));
                    $('#hotel_service_name').val($(this).parent().attr('data-name'));
                    $('#hotel_service_id').val($(this).parent().attr('data-id'));
                    $('#edit_accessorial').collapse({toggle: true})
                    $('#edit_accessorial').collapse('show');
                    $('#hotel_service_price').attr('disabled', false);
                    if($(this).parent().attr('price') == -1) {
                       $('#hotel_service_price').attr('disabled', true);
                    }
                });
                $('.add_accessorial').click(function(e) {
                    $('.accessorial_edit .edit_checkbox i').removeClass('am-icon-dot-circle-o');
                    $('#hotel_service').val('');
                    $('#hotel_service_price').val('');
                    $('#hotel_service_name').val('');
                    $('#hotel_service_id').val('');
                    $('#edit_accessorial').collapse({toggle: true})
                    $('#edit_accessorial').collapse('show');
                    $('#hotel_service_price').attr('disabled', false);
                });
                $('#hotel_service').change(function(e) {
                    if($(this).val() == 0) {
                        $('#hotel_service_price').val('-1');
                        $('#hotel_service_price').attr('disabled', true);
                        $('#hotel_service_name').val('');
                        $('#hotel_service_id').val('');
                    } else {
                        if($('#hotel_service_price').val() == -1) {
                            $('#hotel_service_price').val('');
                        }
                        $('#hotel_service_price').attr('disabled', false);
                    }
                });
            };
            return accessorialService;
        },
        
    }
    var accessorialService = AccessorialServiceClass.instance();
    accessorialService.accessorial();
    
    var edit_accessorial_form_validate = $("#edit_accessorial_form").validate({
		rules: {
			hotel_service: {required: true},
            hotel_service_name: {required: true},
            hotel_service_price: {required: true,digits:true},
		},
		messages: {
			hotel_service_name:"",
            hotel_service:"",
            hotel_service_price:"填0和整数",
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
            var param = $("#edit_accessorial_form").serialize();
            var url = '';
            var add = '<%$add_accessorialService_url%>';
            var edit = '<%$edit_accessorialService_url%>';
            url = add;
            if($('#hotel_service').val() > 0) url = edit;
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