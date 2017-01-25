<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<style type="text/css">
.form-horizontal .control-label{padding-top:10px;}
.form-horizontal .controls{padding: 5px 0 5px 0;}
.form-horizontal .control-label {padding-top: 5px;}
.table-bordered th, .table-bordered td:first-child {border-left: 0px solid #ddd !important;}
.table-bordered td{font-size:12px;}
.table.in-check tr th:first-child, .table.in-check tr td:first-child {width: 45px;}
.tab-content{overflow:visible;}
.quick-actions{margin-top:5px;}
.quick-actions li{font-size:14px; font-weight:bold; padding:10px;}
.e_parent{ width:108px;}
</style>
<script src="<%$__RESOURCE%>js/jquery.validate.js"></script>
<link rel="stylesheet" href="<%$__RESOURCE%>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<%$__RESOURCE%>js/jquery.datetimepicker.full.min.js"></script>
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
                        &#12288;添加来源</a>
                    </div>
                </div>
                <div class="widget-title">
                    <ul class="nav nav-tabs">
                        <li class="active" id=""><a data-toggle="tab" href="#tab1">客人来源</a></li>
                        <li id="discount_tab"><a data-toggle="tab" href="#tab2"><i class="am-icon-puzzle-piece am-red-FB0000"></i> 折扣管理</a></li>
                        <li id="agreement_tab"><a data-toggle="tab" href="#tab3"><i class="am-icon-briefcase am-blue-2F93FF"></i> 协议公司管理</a></li>
                    </ul>
                </div>
                <div class="widget-content nopadding tab-content">
                    <div id="tab1" class="tab-pane active">
                        <form method="post" class="form-horizontal" enctype="multipart/form-data" novalidate>
                        <div class="control-group">
                         <%foreach key=book_sales_type_id item=arrayData from=$arrayDataInfo%>
                            <div class="control-group">
                               <label class="control-label"><ul class="quick-actions"><li><%$arrayBookSalesType[$book_sales_type_id].book_sales_type_name%></li></ul></label>
                               <%foreach key=book_type_id item=BookType from=$arrayData%>
                               <div class="controls"> 
                                    <label class="control-label _edit">
                                    <div class="btn-group">
                                        <a class="btn btn-inverse edit_checkbox e_parent" href="#view"><i class="am-icon-circle-o"></i> <%$BookType.book_type_name%></a><%if $BookType.hotel_id > 0%><a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                        <ul class="dropdown-menu" data-id="<%$BookType.book_type_id%>" data-name="<%$BookType.book_type_name%>" father-id="<%$BookType.book_type_father_id%>" dtype="<%$BookType.type%>" sales_type="<%$BookType.book_sales_type_id%>"><li class="edit_btn"><a href="#edit"><i class="am-icon-pencil am-yellow-FFAA3C"></i> Edit</a></li><%if $BookType.children==''%><li><a href="#delete"><i class="am-icon-trash am-red-FB0000"></i> Delete</a></li><%/if%></ul><%else%><a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#"><span class="am-icon-genderless"></span></a><%/if%>
                                    </div>
                                    </label>
                                    <div class="controls _edit">
                                    <%section name=j loop=$BookType.children%>
                                        <div class="btn-group"><a class="btn edit_checkbox" href="#view"><i class="am-icon-circle-o"></i> <%$BookType.children[j].book_type_name%> </a><a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a><ul class="dropdown-menu" data-id="<%$BookType.children[j].book_type_id%>" data-name="<%$BookType.children[j].book_type_name%>" father-id="<%$BookType.children[j].book_type_father_id%>" dtype="<%$BookType.children[j].type%>" sales_type="<%$BookType.children[j].book_sales_type_id%>"><li class="edit_btn"><a href="#edit"><i class="am-icon-edit am-yellow-FFAA3C"></i> Edit</a></li><li class="discount_btn"><a href="#discount"><i class="am-icon-puzzle-piece am-red-FB0000"></i> <%$arrayLaguage['add_discount']['page_laguage_value']%></a></li><li><a href="#delete"><i class="am-icon-trash am-red-FB0000"></i> Delete</a></li></ul></div>
                                    <%sectionelse%>
                                    &#12288;
                                    <%/section%>    
                                    </div>
                                 </div>
                                 <%/foreach%>
                            </div>
                         <%/foreach%>
                         <div class="controls">
                            <a class="btn btn-primary btn-mini add_data"><i class="am-icon-plus-circle"></i> 添加来源</a>
                         </div>
                         
                        </div>
                        </form>
                    </div>
                    <div id="tab2" class="tab-pane">
                          <div class="widget-content nopadding">
                            <table class="table table-bordered table-striped with-check">
                              <tbody>
                                <tr>
                                  <td><i></i></td>
                                  <td><%$arrayLaguage['apellation']['page_laguage_value']%></td>
                                  <td>折扣名称</td>
                                  <td>折扣方式</td>
                                  <td></td>
                                  <td>单位</td>
                                  <td>公司名称</td>
                                  <td>开始时间</td>
                                  <td>结束时间</td>
                                  <td></td>
                                </tr>
                              </tbody>
                              <tbody>
                              <%section name=i loop=$arrayDiscount%>
                              <%if $arrayDiscount[i].book_type_father_id!='5'%>
                                <tr class="discount_tr" id="discount_tr<%$arrayDiscount[i].book_discount_id%>">
                                  <td class="discount_td"><div class="checker" id="uniform-undefined"><span><%$smarty.section.i.index+1%></span></div></td>
                                  <td class="discount_td" btype="book_type_name"><%$arrayType[$arrayDiscount[i].book_type_id].book_type_name%></td>
                                  <td class="discount_td" btype="book_discount_name"><%$arrayDiscount[i].book_discount_name%></td>
                                  <td class="discount_td"><%if $arrayDiscount[i].book_discount_type==1%>直减<%else%>折扣<%/if%></td>
                                  <td class="discount_td" btype="book_discount"><%$arrayDiscount[i].book_discount%></td>
                                  <td class="discount_td"><%if $arrayDiscount[i].book_discount_type==1%>元<%else%>%<%/if%></td>
                                  <td class="discount_td" btype="agreement_company_name"><%$arrayDiscount[i].agreement_company_name%></td>
                                  <td class="discount_td" btype="agreement_active_time_begin"><%$arrayDiscount[i].agreement_active_time_begin%></td>
                                  <td class="discount_td" btype="agreement_active_time_end"><%$arrayDiscount[i].agreement_active_time_end%></td>
                                  <td>
                                    <div class="btn-group">
                                       <button data-id="<%$arrayDiscount[i].book_discount_id%>" type="<%$arrayDiscount[i].book_discount_type%>"  
                                       layout_corp="<%$arrayDiscount[i].room_layout_corp_id%>"
                                       class="btn btn-mini btn-warning editBtn"><i class="am-icon-edit"></i> 编辑</button> 
                                       <button data-id="<%$arrayDiscount[i].book_discount_id%>" class="btn btn-mini btn-danger removeBtn"><i class="am-icon-minus-circle"></i> 删除</button>
                                    </div>
                                  </td>
                                </tr>
                                <tr class="hide">
                                  <td colspan="10">
                                      <table class="table table-bordered table-striped with-check in-check">
                                          <tbody>
                                            <tr>
                                              <th></th>
                                              <th><%$arrayLaguage['contacts']['page_laguage_value']%></th>
                                              <th><%$arrayLaguage['phone']['page_laguage_value']%></th>
                                              <th><%$arrayLaguage['mobile']['page_laguage_value']%></th>
                                              <th>Email</th>
                                            </tr>
                                            <tr>
                                              <th></th>
                                              <td btype="agreement_company_contacts"><%$arrayDiscount[i].agreement_company_contacts%></td>
                                              <td btype="agreement_company_phone"><%$arrayDiscount[i].agreement_company_phone%></td>
                                              <td btype="agreement_company_mobile"><%$arrayDiscount[i].agreement_company_mobile%></td>
                                              <td btype="agreement_company_email"><%$arrayDiscount[i].agreement_company_email%></td>
                                            </tr>
                                            <tr>
                                              <th>有效时间</th>
                                              <td colspan="5" btype="agreement_active_time">
                                                <%$arrayDiscount[i].agreement_active_time_begin%> <%$arrayDiscount[i].agreement_active_time_end%>
                                              </td>
                                            </tr>
                                            <tr>
                                              <th>介绍</th>
                                              <td colspan="5" btype="agreement_company_introduction"><%$arrayDiscount[i].agreement_company_introduction%></td>
                                            </tr>
                                            <tr>
                                              <th>协议</th>
                                              <td colspan="5" btype="agreement_content"><%$arrayDiscount[i].agreement_content%></td>
                                            </tr>
                                            <tr>
                                              <th><%$arrayLaguage['address']['page_laguage_value']%></th>
                                              <td colspan="5" btype="agreement_company_address"><%$arrayDiscount[i].agreement_company_address%></td>
                                            </tr>
                                          </tbody>
                                      </table>
                                  </td>
                                </tr>
                              <%/if%>
                              <%/section%>  
                              </tbody>
                            </table>
                          </div>
                    </div>
                    <div id="tab3" class="tab-pane">
                          <div class="widget-content nopadding">
                            <table class="table table-bordered table-striped with-check">
                              <tbody>
                                <tr>
                                  <td><i></i></td>
                                  <td><%$arrayLaguage['apellation']['page_laguage_value']%></td>
                                  <td>折扣名称</td>
                                  <td>类别</td>
                                  <td></td>
                                  <td>单位</td>
                                  <td>公司名称</td>
                                  <td>开始时间</td>
                                  <td>结束时间</td>
                                  <td></td>
                                </tr>
                              </tbody>
                              <tbody>
                              <%section name=i loop=$arrayDiscount%>
                              <%if $arrayDiscount[i].book_type_father_id=='5'%>
                                <tr class="discount_tr" id="discount_tr<%$arrayDiscount[i].book_discount_id%>">
                                  <td class="discount_td"><div class="checker" id="uniform-undefined"><span><%$smarty.section.i.index+1%></span></div></td>
                                  <td class="discount_td" btype="book_type_name"><%$arrayType[$arrayDiscount[i].book_type_id].book_type_name%></td>
                                  <td class="discount_td" btype="book_discount_name"><%$arrayDiscount[i].book_discount_name%></td>
                                  <td class="discount_td"><%if $arrayDiscount[i].book_discount_type==1%>直减<%elseif $arrayDiscount[i].book_discount_type==0%>折扣<%else%>协议价<%/if%></td>
                                  <td class="discount_td" btype="book_discount"><%if $arrayDiscount[i].book_discount_type==2%><%$arrayRoomLayoutCorp[$arrayDiscount[i].room_layout_corp_id].room_layout_corp_name%><%else%><%$arrayDiscount[i].book_discount%><%/if%></td>
                                  <td class="discount_td">
                                  <%if $arrayDiscount[i].book_discount_type==2%>
                                  
                                  <%else%>
                                    <%if $arrayDiscount[i].book_discount_type==1%>元<%else%>%<%/if%>
                                  <%/if%>
                                    
                                  </td>
                                  <td class="discount_td" btype="agreement_company_name"><%$arrayDiscount[i].agreement_company_name%></td>
                                  <td class="discount_td" btype="agreement_active_time_begin"><%$arrayDiscount[i].agreement_active_time_begin%></td>
                                  <td class="discount_td" btype="agreement_active_time_end"><%$arrayDiscount[i].agreement_active_time_end%></td>
                                  <td>
                                    <div class="btn-group">
                                       <button data-id="<%$arrayDiscount[i].book_discount_id%>" type="<%$arrayDiscount[i].book_discount_type%>"  
                                       layout_corp="<%$arrayDiscount[i].room_layout_corp_id%>"
                                       class="btn btn-mini btn-warning editBtn"><i class="am-icon-edit"></i> 编辑</button> 
                                       <button data-id="<%$arrayDiscount[i].book_discount_id%>" class="btn btn-mini btn-danger removeBtn"><i class="am-icon-minus-circle"></i> 删除</button>
                                    </div>
                                  </td>
                                </tr>
                                <tr class="hide">
                                  <td colspan="10">
                                      <table class="table table-bordered table-striped with-check in-check">
                                          <tbody>
                                            <tr>
                                              <th></th>
                                              <th><%$arrayLaguage['contacts']['page_laguage_value']%></th>
                                              <th><%$arrayLaguage['phone']['page_laguage_value']%></th>
                                              <th><%$arrayLaguage['mobile']['page_laguage_value']%></th>
                                              <th>Email</th>
                                            </tr>
                                            <tr>
                                              <th></th>
                                              <td btype="agreement_company_contacts"><%$arrayDiscount[i].agreement_company_contacts%></td>
                                              <td btype="agreement_company_phone"><%$arrayDiscount[i].agreement_company_phone%></td>
                                              <td btype="agreement_company_mobile"><%$arrayDiscount[i].agreement_company_mobile%></td>
                                              <td btype="agreement_company_email"><%$arrayDiscount[i].agreement_company_email%></td>
                                            </tr>
                                            <tr>
                                              <th>有效时间</th>
                                              <td colspan="5" btype="agreement_active_time">
                                                <%$arrayDiscount[i].agreement_active_time_begin%> <%$arrayDiscount[i].agreement_active_time_end%>
                                              </td>
                                            </tr>
                                            <tr>
                                              <th>介绍</th>
                                              <td colspan="5" btype="agreement_company_introduction"><%$arrayDiscount[i].agreement_company_introduction%></td>
                                            </tr>
                                            <tr>
                                              <th>协议</th>
                                              <td colspan="5" btype="agreement_content"><%$arrayDiscount[i].agreement_content%></td>
                                            </tr>
                                            <tr>
                                              <th><%$arrayLaguage['address']['page_laguage_value']%></th>
                                              <td colspan="5" btype="agreement_company_address"><%$arrayDiscount[i].agreement_company_address%></td>
                                            </tr>
                                          </tbody>
                                      </table>
                                  </td>
                                </tr>
                              <%/if%>
                              <%/section%>  
                              </tbody>
                            </table>
                          </div>
                    </div>
                </div>
                <div id="edit_data" class="panel-collapse collapse widget-content nopadding">
                    <div class="control-group">
                        <div class="controls">
                            <form method="post" class="form-horizontal" enctype="multipart/form-data" name="edit_form" id="edit_form" novalidate>
                                <div class="modal-header">
                                    <button data-toggle="collapse" data-target="#edit_data" class="close" type="button">×</button>
                                    <h3>添加/修改来源</h3>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">客人来源 :</label>
                                    <div class="controls">
                                        <label class="inline">
                                            <span id="type_1" class="set_type" value="1" type="member"><i class="am-icon-circle-thin"></i> 本店直销</span>
                                            <span id="type_2" class="set_type" value="2" type="OTA"><i class="am-icon-circle-thin"></i> 分销渠道</span>
                                            <span id="type_3" class="set_type" value="3" type="agreement"><i class="am-icon-circle-thin"></i> 集团预定</span>
                                            <input type="hidden" value="" name="book_sales_type_id" id="book_sales_type_id" >
                                        </label>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">属于 :</label>
                                    <div class="controls">
                                        <select id="book_type_select" class="span2">
                                        <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                        <option value="0"><%$arrayLaguage['new_category']['page_laguage_value']%></option>
                                        <%foreach key=book_sales_type_id item=arrayData from=$arrayDataInfo%>
                                            <%foreach key=book_type_id item=Data from=$arrayData%>
                                                <option value="<%$Data.book_type_id%>"><%$Data.book_type_name%></option>
                                            <%/foreach%>
                                        <%/foreach%>    
                                        </select>
                                        <input type="hidden" name="type" id="type" value="">
                                        <input type="hidden" name="book_type" id="book_type" value="">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><%$arrayLaguage['apellation']['page_laguage_value']%> :</label>
                                    <div class="controls">
                                        <input id="book_type_name" name="book_type_name" class="span2" value="" type="text">
                                        <input id="book_type_id" name="book_type_id" value="" type="hidden">
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
                <div id="discount_data" class="panel-collapse collapse widget-content nopadding">
                <form method="post" class="form-horizontal" enctype="multipart/form-data" name="discount_form" id="discount_form" novalidate>
                    <input type="hidden" value="" name="book_discount_id" id="book_discount_id">
                    <input type="hidden" value="" name="discount_book_type_id" id="discount_book_type_id">
                    <input id="book_type_father_id" name="book_type_father_id" value="" type="hidden">
                    <div class="control-group">
                        <label class="control-label"><span id="discount_name"></span><%$arrayLaguage['discount']['page_laguage_value']%></label>
                        <div class="controls"></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">折扣名称：</label>
                        <div class="controls">
                            <input id="book_discount_name" name="book_discount_name" class="input-medium" value="" type="text">
                            <select name="book_discount_type" id="book_discount_type" class="input-small">
                                <option value="0">打折</option>
                                <option value="1">直减</option>
                                <option value="2">协议价</option>
                            </select>
                            <span id="discount_span">
                            折扣：
                            <div class="input-append">
                            <input id="book_discount" name="book_discount" class="input-small" value="" type="text">
                            <span class="add-on" id="book_discount_add_on">%</span>
                            </div>
                            </span>
                            <a data-toggle="collapse" data-target="#more_option" class="btn btn-primary btn-mini"><i class="am-icon-chevron-circle-down"></i> 更多选项</a>
                        </div>
                    </div>
                    <div class="control-group hide" id="layout_corp_div">
                        <label class="control-label">协议价种类：</label>
                        <div class="controls"><label class="inline">
                        <%foreach key=i item=LayoutCorp from=$arrayRoomLayoutCorp%>
                            <span data-id="<%$LayoutCorp.room_layout_corp_id%>" class="layout_corp_class"><i class="am-icon-circle-thin"></i> <%$LayoutCorp.room_layout_corp_name%></span>
                        <%/foreach%></label>
                        <input value="0" name="room_layout_corp_id" id="room_layout_corp_id" type="hidden">
                        </div>
                    </div>
                    <div class="control-group">
                        <div id="more_option" class="panel-collapse collapse">
                            <label class="control-label">公司\团体名称：</label>
                            <div class="controls">
                            <input id="agreement_company_name" name="agreement_company_name" class="input-large" value="" type="text">
                            </div>
                            <label class="control-label"><%$arrayLaguage['address']['page_laguage_value']%> ： </label>
                            <div class="controls"><input id="" name="" class="span5" value="" type="text"></div>
                            <label class="control-label"><%$arrayLaguage['contacts']['page_laguage_value']%> ： </label>
                            <div class="controls">
                                <input id="agreement_company_contacts" name="agreement_company_contacts" class="input-small" value="" type="text">
                                <%$arrayLaguage['phone']['page_laguage_value']%> ： 
                                <input id="agreement_company_phone" name="agreement_company_phone" class="input-small" value="" type="text">
                                <%$arrayLaguage['mobile']['page_laguage_value']%> ： 
                                <input id="agreement_company_mobile" name="agreement_company_mobile" class="input-small" value="" type="text">
                                Email ： 
                                <input id="agreement_company_email" name="agreement_company_email" class="input-small" value="" type="text">
                            </div>
                            <label class="control-label">公司\团体介绍 ： </label>
                            <div class="controls"><textarea name="agreement_company_introduction" id="agreement_company_introduction" class="span5"></textarea></div>
                            <label class="control-label">协议内容 ： </label>
                            <div class="controls"><textarea name="agreement_content" id="agreement_content" class="span5"></textarea></div>
                            <label class="control-label">有效时间 <i class="am-icon-calendar"></i> ： </label>
                            <div class="controls">
                                <input id="agreement_active_time_begin" name="agreement_active_time_begin" class="input-small" value="" type="text"> - 
                                <input id="agreement_active_time_end" name="agreement_active_time_end" class="input-small" value="" type="text">
                            </div>
                        </div>
                        <div class="controls"><button type="submit" data-loading-text="Loading..." class="btn btn-success pagination-centered">Save</button> <a data-toggle="collapse" data-target="#discount_data" class="btn" href="#">Cancel</a> 
                        </div>  
                    </div>
                </form>    
                </div>
                
                <div class="widget-content">
                    <br>
                </div>
                
            </div>   
        </div>
					
	  </div>
    
    </div>
</div>
<%include file="hotel/inc/footer.tpl"%>
<%include file="hotel/inc/modal_box.tpl"%>
<%include file="hotel/inc_js/memberSetting_js.tpl"%>
</body>
</html>