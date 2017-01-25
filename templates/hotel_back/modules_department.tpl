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
.btn-icon-pg ul li{min-width: auto;}
#role_power label{margin-left:0px;}
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
             <span class="icon"><i class="icon-th"></i></span> 
            <h5><%$arrayLaguage['hotel_department_manage']['page_laguage_value']%></h5>
          </div>
          <div class="widget-title">
            <ul class="nav nav-tabs">
                <li class="active" id="hotel_department_manage"><a data-toggle="tab" href="#tab1"><%$arrayLaguage['hotel_department_manage']['page_laguage_value']%></a></li>
                <li id="position_manage"><a data-toggle="tab" href="#tab2"><%$arrayLaguage['position_manage']['page_laguage_value']%></a></li>
                <li id="department_role_setting"><a data-toggle="tab" href="#tab3"><%$arrayLaguage['department_role_setting']['page_laguage_value']%></a></li>
            </ul>
          </div>
          <div class="widget-content tab-content">
           <div id="tab1" class="tab-pane active">
               <div class="btn-group pagination">
               <button id="" class="btn btn-primary addParentTree"><i class="am-icon-plus-circle"></i> 添加部门</button>
               <button id="" class="btn btn-warning editTree"><i class="am-icon-edit"></i> 编辑部门</button> 
               <button id="" class="btn btn-danger removeTree"><i class="am-icon-minus-circle"></i> 删除部门</button>
               </div>
               <div class="content_wrap">
                <div>
                    <ul id="department_tree" class="ztree"></ul>
                </div>
                <div class="right">
                    <ul id="log" class="log"></ul>
                </div>
                </div>
           </div>
    	   <div id="tab2" class="tab-pane">
               <div class="btn-group pagination">
               <button id="" class="btn btn-primary addParentTree"><i class="am-icon-plus-circle"></i> 添加职位</button>
               <button id="" class="btn btn-warning editTree"><i class="am-icon-edit"></i> 编辑职位</button> 
               <button id="" class="btn btn-danger removeTree"><i class="am-icon-minus-circle"></i> 删除职位</button>
               </div>
               <div class="content_wrap">
                <div>
                    <ul id="position_tree" class="ztree"></ul>
                </div>
                <div class="right">
                    <ul id="" class="log"></ul>
                </div>
                </div>
           </div>
           <div id="tab3" class="tab-pane">
               <div class="btn-group pagination">
               <button id="" class="btn btn-primary addParentTree"><i class="am-icon-plus-circle"></i> 添加角色</button>
               <button id="" class="btn btn-warning editTree"><i class="am-icon-edit"></i> 编辑角色</button> 
               <button id="" class="btn btn-danger removeTree"><i class="am-icon-minus-circle"></i> 删除角色</button>
               </div>
               <div class="content_wrap">
                <div class="span2 fl">
                    <ul id="role_tree" class="ztree"></ul>
                </div>
                <div class="span10 fl">
                    <div class="widget-box">
                        <div class="widget-title">
                            <span class="icon"><i class="icon-th-list"></i></span><h5>权限</h5>
                        </div>
                        <div class="widget-content nopadding form-horizontal">
                            <div class="control-group">
                                <label class="control-label">菜单</label>
                                <div class="controls"></div>
                            </div>
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
</div>
<div id="edit_department" class="modal hide in" aria-hidden="false">
<form action="" method="post" class="form-horizontal" enctype="multipart/form-data" name="edit_department_form" id="edit_department_form" novalidate>
  <div class="modal-header">
    <button data-dismiss="modal" class="close" type="button">×</button>
    <h3>添加/编辑</h3>
  </div>
  <div class="modal-body">
      <div class="widget-box">
        <div class="widget-content tab-content nopadding">
                <div class="control-group">
                    <label class="control-label">属于 :</label>
                    <div class="controls">
                        <input id="department_parent_name" class="span2" readonly value="" type="text">
                        <input id="department_parent_id" name="department_parent_id" type="hidden" value="" >
                        <input id="department_self_id" name="department_self_id" type="hidden" value="" >
                        <input id="department_position" name="department_position" type="hidden" value="0" >
                        <input id="role_department_id" name="role_department_id" type="hidden" value="" >
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">名称 :</label>
                    <div class="controls">
                        <input id="department_self_name" name="department_self_name" class="span2" placeholder="" value="" type="text">
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
<%include file="hotel/inc_js/department_js.tpl"%>
</body>
</html>