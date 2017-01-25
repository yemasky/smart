<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<script src="<%$__RESOURCE%>js/jquery.validate.js"></script>
<script src="<%$__RESOURCE%>js/drag.js"></script>
<link rel="stylesheet" href="<%$__RESOURCE%>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<%$__RESOURCE%>js/jquery.datetimepicker.full.min.js"></script>
<script src="<%$__RESOURCE%>js/jquery.dataTables.min.1.10.12.js"></script>
<!--<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>-->
<link rel="stylesheet" href="<%$__RESOURCE%>css/jquery.dataTables.min.1.10.12.css" />
<style type="text/css">
.custom-date-style{ cursor:pointer; color:#666666 !important;}
.table-bordered th, .table-bordered td:first-child {border-left: 0px solid #ddd !important;}
.dropdown-menu {margin: 2px -40px 0 !important; min-width:110px;}
.dropdown-menu li{padding:0px !important;}
.stat-boxes2{top:0px;right:0px; text-align:left;}
.stat-boxes .right strong{ font-size:14px; font-weight:normal;}
.stat-boxes .left{padding: 2px 5px 5px 1px;margin-right: 1px; text-align:center;}
.stat-boxes .left span{font-size:12px; font-style:italic;}
.stat-boxes .cash_pledge {line-height: 25px; font-size:12px}
.stat-boxes .right{padding:1px 0 0; width: 55px;}
.stat-boxes .price{width: 45px !important;}
.stat-boxes .right span{ line-height: 30px;}
.stat-boxes li{margin:0px 1px 0;padding: 0 3px;line-height: 13px;}
.stat-boxes input{margin-bottom:1px !important;}
#rate_calculation{width:190px; height:248px; top:100%; left:50%;}
#book_nav{margin-bottom:0px;}
#tab1, #tab2, #tab3{margin-top:0px; margin-bottom:0px;}
#calculation-box,#check-out-box{margin-top:0px;}
.tab-content {overflow:visible;}
@media (max-width: 480px){
.stat-boxes2 {margin:auto;}
}
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
                    <span class="icon">
                        <i class="icon-th-list"></i>
                    </span>
                    <h5><%$selfNavigation.hotel_modules_name%> <code>订单号：<%$arrayDataInfo[0].book_order_number%></code> <%$today%></h5>
                    <div class="buttons" id="btn_room_layout">
                        <a class="btn btn-primary btn-mini" href="<%$back_lis_url%>" id="add_room_layout"><i class="am-icon-arrow-circle-left"></i> 
                        &#12288;<%$arrayLaguage['back_list']['page_laguage_value']%></a>
                    </div>
                </div>
                <div class="widget-title">
                    <ul class="nav nav-tabs" id="book_nav">
                        <li class="active" id=""><a data-toggle="tab" href="#book_tab1"><i class="am-icon-first-order am-blue-2F93FF"></i> 预订信息</a></li>
                        <li id=""><a data-toggle="tab" href="#book_tab2"><i class="am-icon-money am-blue-2F93FF"></i> 账务明细</a></li>
                        <li id=""><a data-toggle="tab" href="#book_tab3"><i class="am-icon-credit-card am-blue-2F93FF"></i> 预授权明细</a></li>
                    </ul>
                </div>
                <div class="widget-content">
                    <div class="nopadding tab-content">
                    <div id="book_tab1" class="tab-pane active">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon">
                                    <i class="icon-arrow-right"></i>
                                </span>
                                <h5>来源/折扣</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>信息</th>
                                            <th></th>
                                            <th><!--<a class="btn btn-primary btn-mini fr"><i class="am-icon-edit"></i><%$arrayLaguage['edit']['page_laguage_value']%></a>--></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>来源</td>
                                            <td><%$arrayBookType[$arrayDataInfo[0].book_type_id].book_type_name%></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>折扣</td>
                                            <td><%if $arrayDataInfo[0].book_discount_type==2%><code>协议价</code><%else%><%$arrayDataInfo[0].book_discount%><%/if%></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>外部订单号</td>
                                            <td><%$arrayDataInfo[0].book_order_number_ourter%></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>订单保留时间</td>
                                            <td><%$arrayDataInfo[0].book_order_retention_time%></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><%$arrayLaguage['book_man']['page_laguage_value']%>：</td>
                                            <td><%$arrayDataInfo[0].book_contact_name%></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><%$arrayLaguage['mobile']['page_laguage_value']%>：</td>
                                            <td><%$arrayDataInfo[0].book_contact_mobile%></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <ul class="nav nav-tabs" id="book_nav">
                            <li class="active" id=""><a data-toggle="tab" href="#tab1"><i class="am-icon-bed am-red-FB0000"></i> 客房信息</a></li>
                            <!--<li id=""><a data-toggle="tab" href="#tab2"><i class="am-icon-group am-red-FB0000"></i> 入住信息</a></li>-->
                            <li id=""><a data-toggle="tab" href="#tab3"><i class="am-icon-object-group am-red-FB0000"></i> 附加服务</a></li>
                        </ul>
                        <div class="nopadding tab-content">
                            <div id="tab1" class="widget-box tab-pane active">   
                                <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-arrow-right"></i>
                                    </span>
                                    <h5>客房信息</h5>
                                    <input id="half_price" value="<%$arrayDataInfo[0].book_half_price%>" name="half_price" type="hidden">
                                    <div class="buttons">
                                        <a id="add_room" class="btn btn-primary btn-mini"><i class="am-icon-plus-circle"></i> 
                                        <%$arrayLaguage['add']['page_laguage_value']%></a>
                                    </div>
                                </div>
                               <div class="widget-content nopadding">  
                                    <table class="table table-bordered table-striped">
                                      <thead>
                                        <tr>
                                          <th>房型</th>
                                          <th>价格体系</th>
                                          <th>房间 / 可住人数 / 可住儿童</th>
                                          <th>可加床/已加</th>
                                          <th><%$arrayLaguage['checkin']['page_laguage_value']%></th>
                                          <th><%$arrayLaguage['checkout']['page_laguage_value']%></th>
                                          <th>状态</th>
                                          <th>房费</th>
                                          <th>押金</th>
                                          <th>支付</th>
                                          <th>预付</th>
                                          <th>
                                          <div class="btn-group fr" book_id='ALL' room_id='ALL'>
                                            <a id="all_check_in" class="btn btn-mini btn-warning"><i class="am-icon-slideshare"></i> 入住完成</a>
                                          </div>
                                          </th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      <%section name=i loop=$arrayDataInfo%>
                                        <tr>
                                          <td><%$arraySellLayout[$arrayDataInfo[i].room_sell_layout_id].room_sell_layout_name%></td>
                                          <td><%$arrayPriceSystem[$arrayDataInfo[i].room_layout_price_system_id].room_layout_price_system_name%></td>                   
                                          <td class="room_info_id" room_id="<%$arrayDataInfo[i].room_id%>" max_people="<%$arrayRoomInfo[$arrayDataInfo[i].room_id].temp_max_people%>" max_children="<%$arrayRoomInfo[$arrayDataInfo[i].room_id].temp_max_children%>" extra_bed="<%$arrayDataInfo[i].book_room_extra_bed%>" name="<%$arrayRoomInfo[$arrayDataInfo[i].room_id].room_name%>[<%$arrayRoomInfo[$arrayDataInfo[i].room_id].room_number%>]"><%$arrayRoomInfo[$arrayDataInfo[i].room_id].room_name%>[<%$arrayRoomInfo[$arrayDataInfo[i].room_id].room_number%>] / <%$arrayRoomInfo[$arrayDataInfo[i].room_id].temp_max_people%> / <%$arrayRoomInfo[$arrayDataInfo[i].room_id].temp_max_children%></td>
                                          <td><%$arrayRoomInfo[$arrayDataInfo[i].room_id].temp_extra_bed%> / <%$arrayDataInfo[i].book_room_extra_bed%></td>
                                          <td class="book_check_in"><%$arrayDataInfo[i].book_check_in%></td>
                                          <td class="book_check_out"><%$arrayDataInfo[i].book_check_out%></td>
                                          <td class="all_check_in">
                                          <code class="fr">
                                          <%if $arrayDataInfo[i].book_order_number_status=='0'%><i class="am-icon-circle-thin"></i> 未入住
                                          <%elseif $arrayDataInfo[i].book_order_number_status=='1'%><i class="am-icon-child"></i> 已入住
                                          <%elseif $arrayDataInfo[i].book_order_number_status=='-1'%><i class="am-icon-circle-o-notch"></i> 已退房
                                          <%elseif $arrayDataInfo[i].book_order_number_status=='-99'%><i class="am-icon-credit-card-alt"></i> 已失效
                                          <%else%>
                                          <%/if%>    
                                          <%if $arrayDataInfo[i].book_change=='add_room'%>[新增]<%/if%>
                                          <%if $arrayDataInfo[i].book_change=='change_room'%>[换房]<%/if%>
                                          <%if $arrayDataInfo[i].book_change=='continued_room'%>[续房]<%/if%>
                                          <%if $arrayDataInfo[i].book_change=='have_change_room'%>[已换房]<%/if%>
                                          <%if $arrayDataInfo[i].book_change=='have_continued_room'%>[已续房]<%/if%>
                                          </code>
                                          </td>
                                          <td><%$arrayDataInfo[i].book_room_price%></td>
                                          <td><%$arrayDataInfo[i].book_cash_pledge%></td>
                                          <td><p class="text-center"><%if $arrayDataInfo[i].book_is_pay==1%><i class="am-icon-check-circle am-green-54B51C"></i><%/if%></p></td>
                                          <td><p class="text-center"><%if $arrayDataInfo[i].book_is_prepayment==1%><i class="am-icon-check-circle am-green-54B51C"></i><%/if%></p></td>
                                          <td>
                                          <div class="fr">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-mini" href="#t"><i class="am-icon-circle-o"></i> <%$arrayLaguage['manage']['page_laguage_value']%></a>
                                                <a class="btn btn-primary btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                                <ul class="dropdown-menu" room_id="<%$arrayDataInfo[i].room_id%>" book_id="<%$arrayDataInfo[i].book_id%>">
                                                    <%if $arrayDataInfo[i].book_order_number_status=='0'%>
                                                    <li class="check_in_room"><a data-target="#" href="#t"><i class="am-icon-child"></i> 入住</a></li>
                                                    <%/if%>
                                                    <li class="change_room"><a data-target="#" href="#t"><i class="am-icon-pencil-square-o"></i> 换房</a></li>
                                                    <li class="continued_room"><a data-target="#" href="#t"><i class="am-icon-pencil-square-o"></i> 续住</a></li>
                                                    <li class="check_out_room"><a data-target="#" href="#t"><i class="am-icon-pencil-square-o"></i> 退房</a></li>
                                                    <li class="cancel_room"><a data-target="#" href="#t"><i class="am-icon-close"></i> 取消</a></li>
                                                </ul>
                                            </div>
                                          </div>
                                          </td>
                                        </tr>
                                      <%/section%>
                                        <tr id="add_room_tr" class="hide">
                                          <td><select id="sell_layout" class="input-medium">
                                                <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                            <%foreach key=room_sell_layout_id item=arrayLayout from=$arraySellLayout%>
                                                <option room_layout="<%$arrayLayout.room_layout_id%>" value="<%$room_sell_layout_id%>"><%$arrayLayout.room_sell_layout_name%></option>
                                            <%/foreach%>
                                         </select></td>
                                          <td>
                                            <select id="price_system" class="input-medium">
                                            <%foreach key=system_id item=arraySystem from=$arrayPriceSystem%>
                                                <option sell_id="<%$arraySystem.room_sell_layout_id%>" layout_corp="<%$arraySystem.room_layout_corp_id%>" value="<%$system_id%>"><%$arraySystem.room_layout_price_system_name%></option>
                                            <%/foreach%>
                                            </select>
                                          </td>
                                          <td id="layout_room"></td>
                                          <td id="extra_bed"></td>
                                          <td><input type="text" class="input-medium" id="room_check_in" value="<%$arrayDataInfo[0].book_check_in%>" ></td>
                                          <td><input type="text" class="input-medium" id="room_check_out" value="<%$arrayDataInfo[0].book_check_out%>" ></td>                                      
                                          <td id="book_change"></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td>
                                          <div class="input-prepend input-append fr">
                                          <a id="search_room" class="btn btn-success btn-mini"><i class="am-icon-search"></i><%$arrayLaguage['find_room']['page_laguage_value']%></a>
                                          <a id="cancel_add_room" class="btn btn-warning btn-mini"><i class="am-icon-minus-circle"></i><%$arrayLaguage['cancel']['page_laguage_value']%></a>
                                          </div></td>
                                        </tr>
                                        <tr id="room_layout_data" class="hide">
                                          <td colspan="8"><div class="input-prepend input-append fr"></div></td>
                                        </tr>
                                        <tr id="room_layout_data_price" class="hide">
                                          <td colspan="12" class="nopadding">
                                          <form class="form-horizontal">
                                           <div class="control-group">
                                               <label class="control-label"></label>
                                               <div class="controls">
                                               <span class="input-prepend input-append text-center">
                                               <input type="hidden" value="" name="balance_date" id="balance_date">
                                               <span class="add-on">
                                                    <%if $arrayDataInfo[0].book_discount_type == 0%>
                                                    <%$arrayLaguage['discount']['page_laguage_value']%>
                                                    <%elseif $arrayDataInfo[0].book_discount_type == 1%>
                                                    直减
                                                    <%elseif $arrayDataInfo[0].book_discount_type == 2%>
                                                    协议价
                                                    <%/if%>
                                                    <input type="hidden" value="<%$arrayDataInfo[0].book_discount_type%>" name="book_discount_type" id="book_discount_type">
                                               </span>
                                               <%if $arrayDataInfo[0].book_discount_type == 2%>
                                               <span class="add-on"><%$arrayLayoutCorp[$arrayDataInfo[0].room_layout_corp_id].room_layout_corp_name%></span>
                                               <%/if%>
                                               <input id="discount" class="input-mini" type="<%if $arrayDataInfo[0].book_discount_type == 2%>hidden<%else%>text<%/if%>" value="<%$arrayDataInfo[0].book_discount%>">
                                               <span class="add-on"><%$arrayLaguage['total_room_rate']['page_laguage_value']%></span>
                                               <input value="0" class="input-mini" id="total_room_rate" name="total_room_rate" type="text">
                                               <span class="add-on">加床费</span>
                                               <input id="total_extra_bed_price" class="input-mini" type="text" value="0">
                                               <span class="add-on"><%$arrayLaguage['cash_pledge']['page_laguage_value']%></span>
                                                <input value="" type="text" class="input-mini cash_pledge" id="cash_pledge" name="cash_pledge" />
                                               <span class="add-on"><%$arrayLaguage['book_days_total']['page_laguage_value']%></span>
                                               <input value="1" class="input-mini" id="book_days_total" name="book_days_total" aria-invalid="false" type="text">
                                               <span class="add-on">免费换房</span>
                                               <i class="am-icon-square-o btn" id="free_change_btn"></i>
                                               <input value="0" class="input-mini" id="free_change" name="free_change" type="hidden">
                                               
                                               <a id="room_rate_calculation" class="btn btn-primary"><i class="am-icon-calculator" id="am-icon-calculator"></i> <%$arrayLaguage['room_rate_calculation']['page_laguage_value']%></a>
                                               <a id="save_add_room" class="btn btn-primary"><i class="am-icon-save"></i> <%$arrayLaguage['confirm']['page_laguage_value']%></a>
                                               </span>
                                               </div>
                                           </div>
                                           </form>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                               </div>
                           </div>
                           <div id="tab3" class="widget-box tab-pane">   
                                <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-arrow-right"></i>
                                    </span>
                                    <h5>附加服务</h5>
                                    <div class="buttons">
                                        <a id="add_service" class="btn btn-primary btn-mini"><i class="am-icon-plus-circle"></i> 
                                        <%$arrayLaguage['add']['page_laguage_value']%></a>
                                    </div>
                                </div>
                               <div class="widget-content nopadding">  
                                    <table class="table table-bordered table-striped">
                                      <thead>
                                        <tr>
                                          <th>项目</th>
                                          <th>单价</th>
                                          <th>数量</th>
                                          <th>折扣</th>
                                          <th>总价</th>
                                          <th></th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      <%section name=i loop=$arrayBookHotelService%>
                                        <tr>
                                          <td><%$arrayHotelService[$arrayBookHotelService[i].hotel_service_id].hotel_service_name%></td>
                                          <td><%$arrayBookHotelService[i].hotel_service_price%></td>
                                          <td><%$arrayBookHotelService[i].book_hotel_service_num%></td>
                                          <td><%$arrayBookHotelService[i].book_hotel_service_discount%></td>
                                          <td><%$arrayBookHotelService[i].hotel_service_price * $arrayBookHotelService[i].book_hotel_service_num * $arrayBookHotelService[i].book_hotel_service_discount / 100%></td>
                                          <td>
                                              <div class="fr">
                                                <div class="btn-group">
                                                    <a class="btn btn-primary btn-mini" href="#t"><i class="am-icon-circle-o"></i> 管理</a>
                                                    <a class="btn btn-primary btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                                    <ul class="dropdown-menu" room_id="21" book_id="8">
                                                        <li class="user_room_card"><a data-target="#" href="#t"><i class="am-icon-credit-card"></i> 退订服务</a></li>
                                                    </ul>
                                                </div>
                                              </div>
                                          </td>
                                        </tr>
                                      <%/section%>
                                        <tr id="add_service_tr" class="hide">
                                          <td><select id="serviceItem" class="input-medium">
                                                <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                            <%foreach key=service_id item=arrayService from=$arrayHotelService%>
                                                <%if $arrayService.hotel_service_price != -1%><option price="<%$arrayService.hotel_service_price%>" value="<%$service_id%>">
                                                    <%$arrayService.hotel_service_name%>
                                                </option><%/if%>
                                            <%/foreach%>
                                         </select></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td><div class="input-prepend input-append fr">
                                          <a id="cancel_add_service" class="btn btn-primary btn-mini"><i class="am-icon-edit"></i><%$arrayLaguage['cancel']['page_laguage_value']%></a>
                                          <a id="save_add_service" class="btn btn-primary btn-mini"><i class="am-icon-save"></i><%$arrayLaguage['confirm']['page_laguage_value']%></a>
                                          </div></td>
                                        </tr>
                                      </tbody>
                                    </table>
                               </div>
                           </div>
                           <div id="check-out-box" class="widget-box hide">   
                                <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-arrow-right"></i>
                                    </span>
                                    <h5>退房</h5>
                                </div>
                               <div class="widget-content nopadding">  
                                    <table class="table table-bordered table-striped">
                                      <thead>
                                        <tr>
                                          <th>房间</th>
                                          <th>入住时间</th>
                                          <th>退房日期</th>
                                          <th>已住（天）</th>
                                          <th>已消费</th>
                                          <th>总房价</th>
                                          <th>押金</th>
                                          <th>支付状态</th>
                                          <th>预付状态</th>
                                          <th>操作</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr id="return_room_tr" class="hide">
                                          <td class="return_room_name"></td>
                                          <td class="return_check_in_date"></td>
                                          <td class="return_check_out_date"><%$thisDayTime%></td>
                                          <td class="return_check_in_days"></td>
                                          <td class="return_consume"></td>
                                          <td class="return_price"></td>
                                          <td class="return_cash_pledge"></td>
                                          <td class="return_is_pay"></td>
                                          <td class="return_is_prepayment"></td>
                                          <td><!--<a id="cancel_return_room" class="btn btn-warning btn-mini fr cancel"><i class="am-icon-minus-circle"></i> 取消</a>--></td>
                                        </tr>
                                        <tr>
                                          <td colspan="10" class="nopadding">
                                          <form class="form-horizontal">
                                          <div class="control-group">
                                               <label class="control-label"></label>
                                               <div class="controls">
                                                <span class="input-prepend input-append text-center">
                                                    <span class="add-on">退押金 :</span>
                                                    <input id="return_book_cash_pledge" class="input-mini" value="" name="book_room_rate" type="text" readonly>
                                                    <span class="add-on">退房费 :</span>
                                                    <input id="return_book_room_rate" class="input-mini" value="" name="book_room_rate" type="text" readonly>
                                                    <a id="return_room_calculation" class="btn btn-primary"><i class="am-icon-calculator" id="am-icon-calculator"></i> 计算退房明细</a>
                                              <a id="return_room_money" class="btn btn-warning"><i class="am-icon-credit-card-alt"></i> 确定退房</a>
                                                </span>
                                              </div>
                                           </div>
                                           </form>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                               </div>
                           </div>
                           <div id="calculation-box" class="widget-box hide">   
                                <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-arrow-right"></i>
                                    </span>
                                    <h5>返/补差价</h5>
                                </div>
                               <div class="widget-content nopadding">  
                                    <table class="table table-bordered table-striped">
                                      <thead>
                                        <tr>
                                          <th></th>
                                          <th></th>
                                          <th></th>
                                          <th></th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td colspan="4" class="text-center nopadding">
                                          <form name="re_book" id="re_book" enctype="multipart/form-data" class="form-horizontal">
                                          <div class="control-group">
                                             <label class="control-label"></label>
                                             <div class="controls">
                                             <span class="input-prepend input-append text-center">
                                                <span class="add-on"><%$arrayLaguage['total_room_rate']['page_laguage_value']%></span>
                                                <input value="" type="text" class="input-mini" id="book_room_rate" name="book_room_rate" />
                                                <span class="add-on">加床费</span>
                                                <input id="book_extra_bed_price" class="input-mini" type="text" value="0">
                                                <span class="add-on"><%$arrayLaguage['cash_pledge']['page_laguage_value']%></span>
                                                <input value="" type="text" class="input-mini total_cash_pledge" id="book_total_cash_pledge" name="book_total_cash_pledge" />
                                                <span class="add-on"><%$arrayLaguage['need_service_price']['page_laguage_value']%></span>
                                                <input value="" type="text" class="input-mini book_price" id="need_service_price" name="need_service_price" />
                                                <span class="add-on"><%$arrayLaguage['service_charge']['page_laguage_value']%></span>
                                                <input value="0" type="text" class="input-mini book_price" id="book_service_charge" name="book_service_charge" />
                                                <span class="add-on"><%$arrayLaguage['total_price']['page_laguage_value']%></span>
                                                <input value="" type="text" class="input-mini" id="total_price" name="book_total_price" />
                                                <span class="add-on"><%$arrayLaguage['prepayment_price']['page_laguage_value']%></span>
                                                <input value="" type="text" class="input-mini" id="prepayment" name="book_prepayment_price" />
                                                
                                            </span>
                                            </div>
                                            <div class="controls">
                                            <span class="input-prepend input-append text-center">
                                                <span class="add-on"><%$arrayLaguage['pay']['page_laguage_value']%> :</span>
                                                 <select name="payment" id="payment" class="input-small">
                                                    <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                                    <option value="1"><%$arrayLaguage['prepayment']['page_laguage_value']%></option>
                                                    <option value="2"><%$arrayLaguage['remaining_sum']['page_laguage_value']%></option>
                                                    <option value="3"><%$arrayLaguage['full-payout']['page_laguage_value']%></option>
                                                 </select>
                                                 <span class="add-on"><%$arrayLaguage['payment_type']['page_laguage_value']%> :</span>
                                                 <select name="payment_type_father" id="payment_type_father" class="input-small">
                                                 <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                                    <%foreach key=payment_type_id item=arrayType from=$arrayPaymentType%>
                                                    <option father="<%$arrayType.payment_type_father_id%>" value="<%$payment_type_id%>"><%$arrayType.payment_type_name%></option>
                                                    <%/foreach%>
                                                 </select>
                                                 <select name="payment_type" id="payment_type" class="input-medium"></select>
                                                 <span class="add-on"><%$arrayLaguage['money_has_to_account']['page_laguage_value']%> :</span>
                                                 <select name="is_pay" id="is_pay" class="input-small">
                                                    <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                                    <option value="1"><%$arrayLaguage['already_collection']['page_laguage_value']%></option>
                                                    <option value="2"><%$arrayLaguage['not_receivable']['page_laguage_value']%></option>
                                                 </select>
                                                 <span class="add-on"><%$arrayLaguage['payment_voucher']['page_laguage_value']%> :</span>
                                                 <input value="" type="text" class="input-large" id="book_payment_voucher" name="book_payment_voucher" />
                                                 <a id="save_book_room" class="btn btn-primary"><i class="am-icon-save"></i> 保存</a>
                                             </span>
                                             </div>
                                             </div>
                                             </form>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                               </div>
                           </div>
                       </div>
                      
                       <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon">
                                    <i class="am-icon-group am-red-FB0000"></i>
                                </span>
                                <h5>入住信息</h5>
                                <div class="buttons">
                                    <a id="add_user" class="btn btn-primary btn-mini"><i class="am-icon-user-plus"></i> 
                                    <%$arrayLaguage['add']['page_laguage_value']%></a>
                                </div>
                            </div>
                           <div class="widget-content nopadding">  
                                <table class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th>姓名</th>
                                      <th>性别</th>
                                      <th>身份信息</th>
                                      <th>证件号码</th>
                                      <th>入住房号</th>
                                      <!--<th><%$arrayLaguage['checkin']['page_laguage_value']%></th>
                                      <th><%$arrayLaguage['checkout']['page_laguage_value']%></th>-->
                                      <th>房卡</th>
                                      <th>备注</th>
                                      <th>
                                        <div class="btn-group fr" book_id='ALL' room_id='ALL' book_user_id='ALL'>
                                            <a class="btn btn-mini btn-danger return_user_room_card"><i class="am-icon-sign-out"></i> 已退房卡</a>
                                            <a class="btn btn-mini btn-warning user_room_card"><i class="am-icon-slideshare"></i> 已办房卡</a>
                                          </div>
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  <%section name=i loop=$arrayBookUser%>
                                    <tr>
                                      <td><%$arrayBookUser[i].book_user_name%></td>
                                      <td><%if $arrayBookUser[i].book_user_sex==1%>男<%else%>女<%/if%></td>
                                      <td><%if $arrayBookUser[i].book_user_id_card_type!=''%><%$arrayLaguage[$arrayBookUser[i].book_user_id_card_type]['page_laguage_value']%><%/if%></td>
                                      <td><%$arrayBookUser[i].book_user_id_card%></td>
                                      <td class="check_in_info" room_id="<%$arrayBookUser[i].room_id%>" user_lodger_type="<%$arrayBookUser[i].book_user_lodger_type%>">
                                        <%$arrayRoomInfo[$arrayBookUser[i].room_id].room_name%>[<%$arrayRoomInfo[$arrayBookUser[i].room_id].room_number%>] - 
                                        <%$arrayLaguage[$arrayBookUser[i].book_user_lodger_type]['page_laguage_value']%>
                                      </td>
                                      <!--<td><%$arrayBookUser[i].book_check_in%></td>
                                      <td><%$arrayBookUser[i].book_check_out%></td>-->
                                      <td id="book_card<%$arrayBookUser[i].book_user_id%>" class="book_card">
                                      <%if $arrayBookUser[i].book_user_room_card=='0'%>未领
                                      <%elseif $arrayBookUser[i].book_user_room_card=='1'%>已领
                                      <%elseif $arrayBookUser[i].book_user_room_card=='2'%>已退
                                      <%/if%>
                                      </td>
                                      <td><%$arrayBookUser[i].book_user_comments%></td>
                                      <td>
                                        <div class="fr">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-mini" href="#t"><i class="am-icon-circle-o"></i> <%$arrayLaguage['manage']['page_laguage_value']%></a>
                                                <a class="btn btn-primary btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                                <ul class="dropdown-menu" room_id="<%$arrayBookUser[i].room_id%>" book_id="<%$arrayBookUser[i].book_id%>" book_user_id="<%$arrayBookUser[i].book_user_id%>">
                                                    <li class="user_room_card"><a data-target="#" href="#t"><i class="am-icon-credit-card"></i> 已办房卡</a></li>
                                                    <li class="return_user_room_card"><a data-target="#" href="#t"><i class="am-icon-exchange"></i> 已退房卡</a></li>
                                                    <li class="user_room_edit"><a data-target="#" href="#t"><i class="am-icon-pencil-square-o"></i> 取消入住</a></li>
                                                </ul>
                                            </div>
                                         </div>
                                      </td>
                                    </tr>
                                  <%/section%>
                                  <tr id="add_user_tr" class="hide">
                                  <form enctype="multipart/form-data" name="add_user_form" id="add_user_form" method="post" class="form-horizontal">
                                      <td><input name="room_user_name" id="room_user_name" value="" type="text" class="input-small" placeholder="<%$arrayLaguage['name']['page_laguage_value']%>" /></td>
                                      <td>
                                          <select name="room_user_sex" id="room_user_sex" class="input-small">
                                            <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                            <option value="1"><%$arrayLaguage['male']['page_laguage_value']%></option>
                                            <option value="0"><%$arrayLaguage['female']['page_laguage_value']%></option>
                                          </select>
                                      </td>
                                      <td>
                                      <select name="user_id_card_type" id="user_id_card_type" class="input-small">
                                        <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                        <%section name=card_type loop=$idCardType%>
                                        <option value="<%$idCardType[card_type]%>"><%$arrayLaguage[$idCardType[card_type]]['page_laguage_value']%></option>
                                        <%/section%>
                                      </select>
                                      </td>
                                      <td><input type="text" name="user_id_card" id="user_id_card" class="input-medium" placeholder="<%$arrayLaguage['identification_number']['page_laguage_value']%>"/></td>
                                      <td id="check_in_room_num"></td>
                                      <!--<td><input class="input-medium" type="text" id="user_check_in"></td>
                                      <td><input class="input-medium" type="text" id="user_check_out"></td>-->
                                      <td></td>
                                      <td><input type="text" name="user_comments" id="user_comments" class="input-large" placeholder="<%$arrayLaguage['user_comments']['page_laguage_value']%>"/></td>
                                      <td><div class="input-prepend input-append fr">
                                      <a id="cancel_add_user" class="btn btn-primary btn-mini"><i class="am-icon-edit"></i><%$arrayLaguage['cancel']['page_laguage_value']%></a>
                                      <a id="save_add_user" class="btn btn-primary btn-mini"><i class="am-icon-save"></i><%$arrayLaguage['save']['page_laguage_value']%></a>
                                      </div>
                                      </td>
                                  </form>
                                  </tr>
                                  
                                  </tbody>
                                </table>
                           </div>
                       </div>
                   </div>
                   <div id="book_tab2" class="tab-pane">
                        <div class="span12">
                        <div class="span4">
                            <div class="widget-box">
                                <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-eye-open"></i>
                                    </span><!--A3 单状态 -1 失效 0预定成功 1入住 2退房完成-->
                                    <h5>订单号：<%$arrayDataInfo[0].book_order_number%>  订单状态：<%$arrayLaguage[$orderStatus[$arrayDataInfo[0].book_order_number_main_status]]['page_laguage_value']%></h5>
                                    <input type="hidden" name="main_book_id" id="main_book_id" value="<%$arrayDataInfo[0].book_id%>">
                                    <input type="hidden" name="sell_layout_id" id="sell_layout_id" value="<%$arrayDataInfo[0].room_sell_layout_id%>">
                                    <input type="hidden" name="layout_id" id="layout_id" value="<%$arrayDataInfo[0].room_layout_id%>">
                                </div>
                                <div class="widget-content nopadding">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>价格信息</th>
                                                <th>单位：元</th>
                                                <th>
                                                <div class="btn-group fr">
                                                    <!--<a id="" class="btn btn-mini btn-danger"><i class="am-icon-sign-out"></i> 办理退房</a>-->
                                                    <%if $arrayDataInfo[0].book_total_cash_pledge_returns==1%>
                                                    <code><i class="am-icon-credit-card-alt"></i> 已退押金</code>
                                                    <%else%>
                                                    <!--<a id="return_deposit_money" class="btn btn-mini btn-warning"><i class="am-icon-credit-card-alt"></i> 退押金</a>-->
                                                    <%/if%>
                                                </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>总价</td>
                                                <td><%$arrayDataInfo[0].book_total_price%></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>预付价</td>
                                                <td><%$arrayDataInfo[0].book_prepayment_price%></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>总房价</td>
                                                <td><%$arrayDataInfo[0].book_total_room_rate%></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>附加服务费</td>
                                                <td><%$arrayDataInfo[0].book_need_service_price%></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>服务费</td>
                                                <td><%$arrayDataInfo[0].book_service_charge%></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>总押金</td>
                                                <td><%$arrayDataInfo[0].book_total_cash_pledge%></td>
                                                <td><code><i class="am-icon-credit-card-alt"></i> 
                                                    <%if $arrayDataInfo[0].book_total_cash_pledge_returns==1%>已退
                                                    <%elseif $arrayDataInfo[0].book_total_cash_pledge_returns==2%>部分已退
                                                    <%else%>未退<%/if%>
                                                    </code></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="widget-box">
                                <div class="widget-title">
                                    <span class="icon">
                                        <i class="icon-arrow-right"></i>
                                    </span>
                                    <h5>支付信息</h5>
                                </div>
                                <div class="widget-content nopadding">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>支付方式</th>
                                                <th>状态</th>
                                                <th>
                                                    <input type="hidden" value="<%$arrayDataInfo[0].book_is_pay%>" name="book_is_pay_status" id="book_is_pay_status">
                                                    <%if $arrayDataInfo[0].book_is_pay==1%>
                                                    <code class="fr"><i class="am-icon-credit-card-alt"></i> 已结算</code>
                                                    <%else%>
                                                    <a id="close_an_account" class="btn btn-mini btn-warning fr"><i class="am-icon-credit-card-alt"></i> <%$arrayLaguage['close_an_account']['page_laguage_value']%></a>
                                                    <a id="save_an_account" class="btn btn-mini btn-success fr hide"><i class="am-icon-credit-card-alt"></i> <%$arrayLaguage['save']['page_laguage_value']%></a>
                                                    <%/if%>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>支付状态</td>
                                                <td><%if $arrayDataInfo[0].book_is_pay==1%>已支付<%else%>未支付<%/if%></td>
                                                <td><select name="book_payment" id="book_payment" class="input-small hide">
                                                    <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                                    <option value="1"><%$arrayLaguage['prepayment']['page_laguage_value']%></option>
                                                    <option value="2"><%$arrayLaguage['remaining_sum']['page_laguage_value']%></option>
                                                    <option value="3"><%$arrayLaguage['full-payout']['page_laguage_value']%></option>
                                                 </select></td>
                                            </tr>
                                            <tr>
                                                <td>支付到账</td>
                                                <td><%if $arrayDataInfo[0].book_pay_date ==''%>未到账<%else%>到账时间:<%$arrayDataInfo[0].book_pay_date%><%/if%></td>
                                                <td><select name="book_is_pay" id="book_is_pay" class="input-small hide">
                                                        <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                                        <option value="1"><%$arrayLaguage['already_collection']['page_laguage_value']%></option>
                                                        <option value="2"><%$arrayLaguage['not_receivable']['page_laguage_value']%></option>
                                                     </select>
                                                 </td>
                                            </tr>
                                            <tr>
                                                <td>支付方式</td>
                                                <td><%if $arrayDataInfo[0].payment_type_id > 0%><%$arrayPaymentType[$arrayDataInfo[0].payment_type_id].payment_type_name%><%else%>未支付<%/if%></td>
                                                <td><select name="book_payment_type" id="book_payment_type" class="input-small hide">
                                                    <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                                    <%foreach key=payment_type_id item=arrayType from=$arrayPaymentType%>
                                                    <option value="<%$payment_type_id%>"><%$arrayType.payment_type_name%></option>
                                                    <%/foreach%>
                                                 </select></td>
                                            </tr>
                                            <tr>
                                                <td>预付状态</td>
                                                <td><%if $arrayDataInfo[0].book_is_prepayment==1%>已预付<%else%>未预付<%/if%></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>预付到账</td>
                                                <td><%if $arrayDataInfo[0].book_prepayment_date ==''%>未到账<%else%>到账时间: <%$arrayDataInfo[0].book_prepayment_date%><%/if%></td>
                                                <td><select name="book_is_prpay" id="book_is_prpay" class="input-small hide">
                                                        <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                                        <option value="1"><%$arrayLaguage['already_collection']['page_laguage_value']%></option>
                                                        <option value="2"><%$arrayLaguage['not_receivable']['page_laguage_value']%></option>
                                                     </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>预付方式</td>
                                                <td><%if $arrayDataInfo[0].prepayment_type_id > 0%><%$arrayPaymentType[$arrayDataInfo[0].prepayment_type_id].payment_type_name%><%else%>未支付<%/if%></td>
                                                <td><select name="book_prpayment_type" id="book_prpayment_type" class="input-small hide">
                                                    <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                                    <%foreach key=payment_type_id item=arrayType from=$arrayPaymentType%>
                                                    <option value="<%$payment_type_id%>"><%$arrayType.payment_type_name%></option>
                                                    <%/foreach%>
                                                 </select></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div id="book_tab3" class="tab-pane">
                   
                    </div>
                       
                    <div class="widget-box">   
                        <div class="widget-title">
                            <span class="icon">
                                <i class="icon-arrow-right"></i>
                            </span>
                            <h5>变更历史</h5>
                        </div>
                       <div class="widget-content nopadding">  
                            <table class="table table-bordered table-striped">
                              <thead>
                                <tr>
                                  <th>变更项目</th>
                                  <th>变更内容</th>
                                  <th>涉及价钱</th>
                                  <th>变更时间</th>
                                </tr>
                              </thead>
                              <tbody>
                              <%section name=i loop=$arrayBookChange%>
                                <tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                </tr>
                              <%/section%>
                              </tbody>
                            </table>
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
<%include file="hotel/inc_js/book_edit_js.tpl"%>
<div id="rate_calculation" class="hide">
    <div id="rate_calculation_move" class="text-center"><i class="am-icon-arrows-alt fl"></i> 计算器  <i id="close_calculation" class="am-icon-times-circle fr am-red-FB0000 close"></i></div>
    <iframe src="<%$__RESOURCE%>static/calculator/index.html" frameborder="0" width="190" height="230" marginheight="0" marginwidth="0" scrolling="no"></iframe>
</div>
</body>
</html>