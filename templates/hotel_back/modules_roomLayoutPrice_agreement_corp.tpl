<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<script src="<%$__RESOURCE%>js/jquery.validate.js"></script>
<style type="text/css">
.this_crop{margin-bottom:10px; margin-left:0px !important;}
</style>
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
                    <span class="icon"><i class="am-icon-briefcase am-blue-2F93FF"></i></span>
                    <h5><%$selfNavigation.hotel_modules_name%></h5>
                    <div class="buttons">
                        <a class="btn btn-primary btn-mini add" href="#addLayoutAttr" url="index.php?module=Dj1TYA==" id="add_attribute" data-toggle="modal"><i class="am-icon-plus-square"></i>
                        &#12288;添加价格种类</a>
                    </div>
                </div>
                <div class="widget-content nopadding">
                   <div class="widget-content nopadding  hide" id="add">
                        <form class="form-horizontal" method="post"  name="layout_corp" id="layout_corp" novalidate>
                            <div class="control-group">
                                <label class="control-label">价格种类名称:</label>
                                <div class="controls">
                                    <input name="room_layout_corp_name" id="room_layout_corp_name" type="text">
                                    <input name="room_layout_corp_id" id="room_layout_corp_id" type="hidden" value="0">                                    
                                </div>
                                
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <div class="btn-group">
                                        <input value="确定" class="btn btn-mini btn-warning" type="submit">
                                        <input value="取消" class="btn btn-mini btn-primary " type="button" id="cancel">
                                    </div>
                                </div>
                            </div>
                        </form>
                   </div>
                   <div class="row-fluid">
                        <div class="span2">
                            <div class="widget-box">
                                <div class="widget-title">
                                    <span class="icon"><i class="icon-th-list"></i></span><h5></h5>
                                </div>
                                <div class="widget-content text-center">
                                    <a class="btn btn-primary add" href="#addLayoutAttr" url="index.php?module=Dj1TYA==" id="add_attribute" data-toggle="modal"><i class="am-icon-plus-square"></i> 添加价格种类</a>
                                </div>
                             </div>
                        </div>
                        <div class="span10">
                            <div class="widget-box">
                                <div class="widget-title">
                                    <span class="icon"><i class="icon-th-list"></i></span><h5>价格种类</h5>
                                </div>
                                <div class="widget-content form-horizontal">
                                    <%section name=i loop=$arrayDataInfo%>
                                    <div class="btn-group this_crop">
                                        <a class="btn edit_checkbox" href="#view"><i class="am-icon-circle-o"></i> <%$arrayDataInfo[i].room_layout_corp_name%> </a>
                                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                        <ul class="dropdown-menu" data-id="<%$arrayDataInfo[i].room_layout_corp_id%>" data-name="<%$arrayDataInfo[i].room_layout_corp_name%>" valid="<%$arrayDataInfo[i].room_layout_corp_valid%>">
                                            <li class="edit_btn"><a href="#edit"><i class="am-icon-pencil am-yellow-FFAA3C"></i> Edit</a></li>
                                            <li><a href="<%$arrayDataInfo[i].layout_corp_url%>"><i class="am-icon-pencil am-yellow-FFAA3C"></i> 添加协议价</a></li>
                                            <li><a href="#delete"><i class="am-icon-trash am-red-FB0000"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                    <%/section%>
                                </div>
                                <div class="widget-content nopadding form-horizontal" id="role_power">
                                
                                </div>
                            </div>
                        </div>
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
    var layout_corp_validate = $("#layout_corp").validate({
		rules: {
			room_layout_corp_name: {required: true},
		},
		messages: {
			room_layout_corp_name:"请填写价格种类名称",
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
            var param = $("#layout_corp").serialize();
            $('#modal_save').modal('show');
            var url = '<%$edit_corp_url%>';
            $.ajax({
                url : url,type : "post",dataType : "json",data: param,
                success : function(result) {
                    data = result;
                    $('#modal_save').modal('hide');
                    if(data.success == 1) {
                        $('#modal_success').modal('show');
                        $('#modal_success_message').html(data.message);
                        $('#modal_success').on('hidden.bs.modal', function () {
                            window.location.reload();
                        })
                    } else {
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                }
            });
		}
	});
    var thisModuleClass = {
        instance: function() {
            var thisModule = {};
            thisModule.initParameter  = function() {
                thisModule.thisYear   = '';
            };
            thisModule.init = function() {
                $('.add').click(function(e) {
                    $('#add').show('fast');
                    $('#room_layout_corp_id').val(0);
                    $('#room_layout_corp_name').val('');
                });
                $('#cancel').click(function(e) {
                    $('#add').hide('fast');
                });
                $('.edit_btn').click(function(e) {
                    thisModule.edit(this);
                });
                $('.this_crop').click(function(e) {
                    thisModule.thisCrop(this);
                });
            };
            thisModule.checkError = function() {
                
            };
            thisModule.edit = function(_this) {
                $('#add').show('fast');
                $('#room_layout_corp_name').val($(_this).parent().attr('data-name'));
                $('#room_layout_corp_id').val($(_this).parent().attr('data-id'));
            };
            thisModule.thisCrop = function(_this) {
                
            };
            return thisModule;
        },
    
    }
    var thisModule = thisModuleClass.instance();
    thisModule.initParameter();
    thisModule.init();
})
</script>
</body>
</html>