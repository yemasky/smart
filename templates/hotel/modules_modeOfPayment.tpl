<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<style type="text/css">
.form-horizontal .btn-group {margin-bottom: 5px;margin-left: 0;margin-right: 5px;}

.form-horizontal .control-label{padding-top:10px;}
.form-horizontal .controls{padding: 5px 0 20px 0;}
.form-horizontal .control-label {padding-top: 5px;}
.table-bordered th, .table-bordered td:first-child {border-left: 0px solid #ddd !important;}
.table-bordered td{font-size:12px;}
.table.in-check tr th:first-child, .table.in-check tr td:first-child {width: 45px;}
.tab-content{overflow:visible;}
.quick-actions{margin-top:5px;}
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
                            <%foreach key=payment_type_id item=payment_type from=$arrayData%>
                            <label class="control-label ">
                            <div class="btn-group">
                            <a class="btn btn-inverse edit_checkbox" href="#view"><%$payment_type.payment_type_name%></a>
                            </div>
                            </label>
                            <div class="controls ">
                            <%section name=j loop=$payment_type.children%>
                                <div class="btn-group"><a class="btn edit_checkbox" href="#view"><i class="am-icon-circle-o"></i> <%$payment_type.children[j].payment_type_name%></a><a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="<%if $payment_type.children[j].hotel_id>0%>caret<%else%>am-icon-genderless<%/if%>"></span></a><%if $payment_type.children[j].hotel_id>0%><ul class="dropdown-menu" data-id="<%$payment_type.children[j].payment_type_id%>" data-name="<%$payment_type.children[j].payment_type_name%>" father-id="<%$payment_type.children[j].payment_type_father_id%>"><li class="edit_btn"><a href="#edit"><i class="am-icon-pencil am-yellow-FFAA3C"></i> Edit</a></li><li><a href="#delete"><i class="am-icon-trash am-red-FB0000"></i> Delete</a></li></ul><%/if%></div>
                            <%sectionelse%>
                            &#12288;
                            <%/section%>    
                            </div>
                            <%/foreach%>
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
                                    <label class="control-label">类别 :</label>
                                    <div class="controls">
                                        <select name="father_id" id="father_id">
                                        <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                        <%foreach key=payment_type_id item=payment_type from=$arrayData%>
                                        <option value="<%$payment_type_id%>"><%$payment_type.payment_type_name%></option>
                                        <%/foreach%>
                                        </select>
                                    </div>
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
                    $('#father_id').val($(this).parent().attr('father-id'));
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