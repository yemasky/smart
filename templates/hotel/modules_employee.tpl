<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<%include file="hotel/inc_js/ztree.tpl"%>
<script src="<%$__RESOURCE%>js/jquery.validate.js"></script>
<style type="text/css">
.ztree li ul.line{height:auto;}
.ztree li span.button.switch.level0 {visibility:hidden; width:1px;}
.ztree li ul.level0 {padding:0; background:none;}
.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
.stat-boxes{text-align:left !important;}
.stat-boxes .right {padding: 2px 2px 2px 0;}
.stat-boxes .right strong {font-size: 14px;margin-bottom: 3px; margin-top: 6px;}
#employee_add .widget-box{margin-bottom:0px;}
.custom-date-style {background-color:#333333 !important;}
</style>
<link rel="stylesheet" href="<%$__RESOURCE%>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<%$__RESOURCE%>js/jquery.datetimepicker.full.min.js"></script>
<%include file="hotel/inc_js/editor_upload_images.tpl"%>
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
             <span class="icon"><i class="icon-th"></i></span> 
            <h5><%$selfNavigation.hotel_modules_name%></h5>
          </div>
          <div class="widget-title">
            <ul class="nav nav-tabs">
                <li class="active" id="hotel_department_manage"><a data-toggle="tab" href="#tab1">员工管理</a></li>
                <!--<li id="position_manage"><a data-toggle="tab" href="#tab2"><%$arrayLaguage['position_manage']['page_laguage_value']%></a></li>
                <li id="department_role_setting"><a data-toggle="tab" href="#tab3">权限管理</a></li>-->
            </ul>
        </div>
          <div class="widget-content tab-content">
           <div id="tab1" class="tab-pane active">
                <div class="btn-group pagination">
                   <button id="" class="btn btn-primary addTree"><i class="am-icon-plus-circle"></i> 添加员工</button>
                   <!--<button id="" class="btn btn-warning editTree"><i class="am-icon-edit"></i> 编辑员工</button> 
                   <button id="" class="btn btn-danger removeTree"><i class="am-icon-minus-circle"></i> 删除员工</button>-->
                </div>
                <div class="widget-content nopadding form-horizontal">
                   <div class="content_wrap control-group">
                        <div class="control-label">
                            <ul id="zTree" class="ztree"></ul>
                        </div>
                        <div class="controls" id="employee_page">
                            <ul id="employee" class="stat-boxes stat-boxes2">
                            <%section name=i loop=$arrayPageEmployee['list_data']%>
                              <li class="PageEmployee" data-id="<%$arrayPageEmployee['list_data'][i].employee_id%>"><a href="#e">
                                <div class="left peity_bar_good">
                                    <span>
                                        <img src="<%$__IMGWEB%><%$arrayPageEmployee['list_data'][i].employee_photo%>" width="40" height="40" onerror="this.src='data/userimg/user_b.png'" border="0" />
                                    </span>
                                    <%if $arrayPageEmployee['list_data'][i].employee_sex==1%>男<%else%>女<%/if%>
                                </div>
                                <div class="right"> <strong class="employee_name"><%$arrayPageEmployee['list_data'][i].employee_name%></strong><span class="department_name" data-id="<%$arrayPageEmployee['list_data'][i].department_id%>"><%$arrayDepartment[$arrayPageEmployee['list_data'][i].department_id].department_name%></span>-<span class="department_position_name" data-id="<%$arrayPageEmployee['list_data'][i].department_position_id%>"><%$arrayPosition[$arrayPageEmployee['list_data'][i].department_position_id].department_position_name%></span>                        
                                </div></a>
                              </li>
                            <%/section%>
                            </ul>
                            <%include file="hotel/inc/page.tpl"%>
                        </div>
                        <div class="controls hide" id="employee_add">
                            <div class="widget-content nopadding in collapse" style="height: auto;">
                                <div class="widget-box">
                                        <div class="modal-header">
                                            <button class="close" type="button">×</button>
                                            <h3><i class="am-icon-group"></i> 添加/修改员工</h3>
                                        </div>
                                        <div class="widget-title">
                                            <ul class="nav nav-tabs" id="myTab">
                                              <li class="active"><a data-toggle="tab" href="#tab_1">员工基本信息</a></li>
                                              <%if $personnelRole == 1%>
                                              <li class="" id="tab_employee_personnel"><a data-toggle="tab" href="#tab_2">人事信息</a></li>
                                              <%/if%>
                                            </ul>
                                        </div>
                                        <div class="widget-content tab-content nopadding">
                                            <div id="tab_1" class="tab-pane active">
                                            <form method="post" class="form-horizontal" enctype="multipart/form-data" name="add_employee_form" id="add_employee_form" novalidate>  
                                            <input type="hidden" value="" name="employee_id" id="employee_id" >
                                                <div class="control-group">
                                                    <label class="control-label">姓名 :</label>
                                                    <div class="controls">
                                                        <input id="employee_name" name="employee_name" class="span2" value="" type="text">
                                                    </div>
                                                    <label class="control-label">性别 :</label>
                                                    <div class="controls">
                                                        <select name="employee_sex" id="employee_sex" class="input-small">
                                                            <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                                            <option value="1"><%$arrayLaguage['male']['page_laguage_value']%></option>
                                                            <option value="0"><%$arrayLaguage['female']['page_laguage_value']%></option>
                                                        </select>
                                                    </div>
                                                    <label class="control-label">出生日期 :</label>
                                                    <div class="controls">
                                                        <input id="employee_birthday" name="employee_birthday" class="span2" value="<%$yearBegin%>" type="text">
                                                    </div>
                                                    <label class="control-label">移动电话 :</label>
                                                    <div class="controls">
                                                        <input id="employee_mobile" name="employee_mobile" class="span2" value="" type="text">
                                                    </div>
                                                    <label class="control-label">邮箱 :</label>
                                                    <div class="controls">
                                                        <input id="employee_email" name="employee_email" class="span2" value="" type="text">
                                                    </div>
                                                    <label class="control-label">微信号 :</label>
                                                    <div class="controls">
                                                        <input id="employee_weixin" name="employee_weixin" class="span2" value="" type="text">
                                                    </div>
                                                    <label class="control-label">上传头像 :</label>
                                                    <div class="controls">
                                                        <ul class="thumbnails"><li class="span2">
                                                        <a id="employee_images_url" class="thumbnail lightbox_trigger" href="#2"><img /></a>
                                                        
                                                        </li> </ul>
                                                        <input id="upload_images_url" name="upload_images_url" value="" readonly type="hidden">
                                                        <button id="employee_photo" class="btn btn-info btn-mini" type="button"><i class="am-icon-file-image-o"></i> 选择图片</button>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">部门 :</label>
                                                    <div class="controls">
                                                        <input id="department_id" class="span2" value="" readonly type="text">
                                                        <input id="department" name="department_id" value="" type="hidden">
                                                    </div>
                                                    <label class="control-label">职位 :</label>
                                                    <div class="controls">
                                                        <input id="department_position_id" class="span2" value="" readonly type="text">
                                                        <input id="department_position" name="department_position_id" value="" type="hidden">
                                                    </div>
                                                    <label class="control-label">权限 :</label>
                                                    <div class="controls">
                                                        <select id="role_id" name="role_id" class="input-small">
                                                        <%section name=i loop=$arrayRole%>
                                                        <option value="<%$arrayRole[i].role_id%>" position="<%$arrayRole[i].department_position_id%>"><%$arrayRole[i].role_name%></option>
                                                        <%/section%>
                                                        </select>
                                                    </div>
                                                    
                                                </div>
                                                <div class="control-group"> 
                                                    <div class="controls"><button type="submit" id="save_info" class="btn btn-success pagination-centered">保存员工基本信息</button> <a class="btn" href="#close" id="close">Cancel</a> 
                                                    </div>  
                                                </div>
                                                </form>
                                            </div>
                                            <div id="tab_2" class="tab-pane">
                                            <%if $personnelRole == 1%>
                                            <form method="post" class="form-horizontal" enctype="multipart/form-data" name="employee_personnel" id="employee_personnel" novalidate>  
                                                <div class="control-group">
                                                    <label class="control-label">身份证号 :</label>
                                                    <div class="controls">
                                                        <input id="employee_id_card" name="employee_id_card" class="span2" value="" type="text">
                                                    </div>
                                                    <label class="control-label">家庭住址 :</label>
                                                    <div class="controls">
                                                        <input id="employee_address" name="employee_address" class="span2" value="" type="text">
                                                    </div>
                                                    <label class="control-label">现住址 :</label>
                                                    <div class="controls">
                                                        <input id="employee_present_address" name="employee_present_address" class="span2" value="" type="text">
                                                    </div>
                                                    <label class="control-label">身份证正面 :</label>
                                                    <div class="controls">
                                                        <input id="employee_positive_id_card" name="employee_positive_id_card" class="span2" value="" type="hidden">
                                                        <ul class="thumbnails"><li class="span2">
                                                        <a id="positive_id_card" class="thumbnail lightbox_trigger" href="#1"><img /></a>
                                                        
                                                        </li> </ul>
                                                        <button id="positive_id_card_images" class="btn btn-info btn-mini" type="button"><i class="am-icon-file-image-o"></i> 选择图片</button>
                                                    </div>
                                                    <label class="control-label">身份证背面照 :</label>
                                                    <div class="controls">
                                                        <input id="employee_back_id_card" name="employee_back_id_card" class="span2" value="" type="hidden">
                                                        <ul class="thumbnails"><li class="span2">
                                                        <a id="back_id_card" class="thumbnail lightbox_trigger" href="#2"><img /></a>
                                                        
                                                        </li> </ul>
                                                        <button id="back_id_card_images" class="btn btn-info btn-mini" type="button"><i class="am-icon-file-image-o"></i> 选择图片</button>
                                                        
                                                    </div>
                                                    <label class="control-label">入职时间 :</label>
                                                    <div class="controls">
                                                        <input id="employee_entry_date" name="employee_entry_date" class="span2" value="" type="text">
                                                    </div>
                                                    <label class="control-label">试用期结束时间 :</label>
                                                    <div class="controls">
                                                        <input id="employee_probation_date" name="employee_probation_date" class="span2" value="" type="text">
                                                    </div>
                                                    <label class="control-label">员工工号 :</label>
                                                    <div class="controls">
                                                        <input id="employee_number" name="employee_number" class="span2" value="" type="text">
                                                    </div>
                                                    <label class="control-label">劳动合同照片 :</label>
                                                    <div class="controls">
                                                        <input id="employee_photo_labor" name="employee_photo_labor" value="" type="hidden">
                                                        <ul id="labor_list" class="thumbnails"></ul>
                                                        <button id="photo_labor_images" class="btn btn-info btn-mini" type="button"><i class="am-icon-file-image-o"></i> 选择图片</button>
                                                    </div>
                                                    
                                                </div>
                                                <div class="control-group"> 
                                                    <div class="controls"><button type="submit" id="save_info" class="btn btn-success pagination-centered">保存人事信息</button> <a class="btn" href="#close" id="close">Cancel</a> 
                                                    </div>  
                                                </div>
                                            </form>
                                            <%/if%>
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
        </div>   
        </div>
					
	  </div>
    
    </div>
</div>
<%include file="hotel/inc/footer.tpl"%>
<%include file="hotel/inc/modal_box.tpl"%>
<%include file="hotel/inc_js/employee_js.tpl"%>
</body>
</html>