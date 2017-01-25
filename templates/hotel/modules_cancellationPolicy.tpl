<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<style type="text/css">
.form-horizontal .btn-group {margin-bottom: 5px;margin-left: 0;margin-right: 5px;}
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
                    <h5><%$selfNavigation.hotel_modules_name%></h5>
                    <div class="buttons" id="btn_room_layout">
                        <a class="btn btn-primary btn-mini add_data" href="#add"><i class="am-icon-plus-square"></i> 
                        &#12288;<%$arrayLaguage['add_category']['page_laguage_value']%></a>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <form method="post" class="form-horizontal" enctype="multipart/form-data" novalidate>
                        <div class="control-group">
                            <label class="control-label _edit"><%$selfNavigation.hotel_modules_name%> : </label>
                            <div class="controls _edit">
                            <%section name=i loop=$arrayData%>
                                <div class="btn-group"><a class="btn edit_checkbox" href="#view"><i class="am-icon-circle-o"></i> <%$arrayData[i].payment_type_name%> <!--<i class="am-icon-rmb am-yellow-F58A17"></i> <%$arrayData[i].type%>--></a><%if $arrayData[i].hotel_id > 0%><a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a><ul class="dropdown-menu" data-id="<%$arrayData[i].payment_type_id%>" data-name="<%$arrayData[i].payment_type_name%>" ><li class="edit_btn"><a href="#edit"><i class="am-icon-edit am-yellow-FFAA3C"></i> Edit</a></li><li><a href="#delete"><i class="am-icon-trash am-red-FB0000"></i> Delete</a></li></ul><%/if%></div>
                            <%/section%>    
                            </div>
                            <div class="controls">
                                <a class="btn btn-primary btn-mini add_data"><i class="am-icon-plus-circle"></i> <%$arrayLaguage['add_category']['page_laguage_value']%></a>
                            </div>
                            <div class="controls">
                            <br>
                            </div>
                         
                        </div>
                    </form>
                    
                </div>
                <div id="edit_data" class="panel-collapse collapse widget-content nopadding">
                    <div class="control-group">
                        <div class="controls">
                            <form method="post" class="form-horizontal" enctype="multipart/form-data" name="edit_form" id="edit_form" novalidate>
                                <div class="modal-header">
                                    <button data-toggle="collapse" data-target="#edit_data" class="close" type="button">×</button>
                                    <h3><%$arrayLaguage['add_or_edit_category']['page_laguage_value']%></h3>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><%$arrayLaguage['apellation']['page_laguage_value']%> :</label>
                                    <div class="controls">
                                        <input id="payment_type_name" name="payment_type_name" class="span2" value="" type="text">
                                        <input id="payment_type_id" name="payment_type_id" value="" type="hidden">
                                    </div>
                                </div>
                                <div class="control-group"> 
                                    <div class="controls"><button type="submit" id="save_info" data-loading-text="Loading..." class="btn btn-success pagination-centered">Save</button> <a data-toggle="collapse" data-target="#edit_data" class="btn" href="#">Cancel</a> 
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
    var edit_form_validate = $("#edit_form").validate({
		rules: {
            payment_type_name: {required: true},
		},
		messages: {
			payment_type_name:"",
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
            var param = $("#edit_form").serialize();
            var url = '';
            var add = '<%$add_url%>';
            var edit = '<%$edit_url%>';
            url = add;
            if($('#payment_type_id').val() > 0) url = edit;
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
    
    var thisActionClass = {
        instance: function() {
            var thisAction = {};
            thisAction.init = function() {
                //$('.collapse').collapse();
                $('._edit .edit_btn').click(function(e) {
                    $('._edit .edit_checkbox i').removeClass('am-icon-dot-circle-o');
                    $(this).parent().parent().find('i').first().addClass('am-icon-dot-circle-o');
                    $('#book_type').val($(this).parent().attr('father-id'));
                    $('#payment_type_name').val($(this).parent().attr('data-name'));
                    $('#payment_type_id').val($(this).parent().attr('data-id'));
                    $('#edit_data').collapse('show');
                });
                $('.add_data').click(function(e) {
                    $('._edit .edit_checkbox i').removeClass('am-icon-dot-circle-o');
                    $('#payment_type_id').val('');
                    $('#payment_type_name').val('');
                    $('#edit_data').collapse('show');
                });
                $('.removeBtn').click(function(e) {
                    //$(this).parent().parent().parent().next().addClass('hide');
                }); 
            };
            
            
            return thisAction;
        },
        
    }
    var thisAction = thisActionClass.instance();
    thisAction.init();

})
$('#edit_data').collapse('hide');$('#discount_data').collapse('hide');$('#more_option').collapse('hide');
</script>
</body>
</html>