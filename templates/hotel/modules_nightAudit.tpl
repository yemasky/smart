<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<link rel="stylesheet" href="<%$__RESOURCE%>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<%$__RESOURCE%>js/jquery.datetimepicker.full.min.js"></script>
<style type="text/css">
#room_status ul{text-align:left;}
#room_status .stat-boxes2{top:0px;}
.form-horizontal .div-control-label {padding-top: 10px; margin-left:20px;width: 248px;float: left;text-align: right;}
.form-horizontal .controls { margin-left: 268px; padding: 10px 0;}
.form-horizontal .right{min-width:70px; width:auto;}
.room_status_ul .left{height:100px;}
.room_status_ul .dropdown-menu li{ display:list-item;}
.room_status_ul .dropdown-menu li a{ padding: 3px 0;}
.room_status_ul .dropdown-menu li a:hover{ background:none;}
#room_status li{margin:0 10px 0;}
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
                        <i class="am-icon-clock-o am-yellow-E88A26"></i>
                    </span>
                    <h5></h5>
                </div>
                <div class="widget-content nopadding">
                    <form action="<%$search_url%>" method="get" class="form-horizontal ui-formwizard" enctype="multipart/form-data">
                        <input type="hidden" value="<%$module%>" name="module">
                        <div class="control-group">
                            <label class="control-label"><%$arrayLaguage['please_select']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <span class="add-on am-icon-calendar"></span>
                                    <input class="input-small" type="text" id="time_begin" name="time_begin" value="<%$thisDay%>" />
                                    <button class="btn btn-primary"><i class="am-icon-search"></i> <%$arrayLaguage['search']['page_laguage_value']%></button >
                                </div>
                                <a id="begin_night_audit" class="btn btn-primary"><i class="am-icon-server am-yellow-EBC012"></i> <%$arrayLaguage['begin_night_audit']['page_laguage_value']%></a >
                            </div>
                        </div>
                    </form>
                </div>
                <div class="widget-title"><span class="icon"><i class="am-icon-server am-yellow-EBC012"></i></span><h5><%$selfNavigation['hotel_modules_name']%></h5></div>
                <%if $act == 'night_audit'%>
                <div class="widget-content nopadding form-horizontal" id="room_status">
                    <%if ($isArriveTime)%>
                    <div class="widget-content nopadding form-horizontal" id="room_status">
                        <div class="control-group">
                            <label class="control-label"> 
                                <div>
                                    <i class="am-icon-check-square-o am-green-54B51C">正常</i>&#12288;
                                    <i class="am-icon-exclamation-circle am-red-FA0A0A">不正常</i>
                                </div>
                            </label>
                            <div class="controls"></div>
                        </div>
                        <%foreach key=book_order_number item=arrayData from=$arrayDataInfo%>
                        <div class="control-group">
                            <div class="div-control-label">
                                <ul class="stat-boxes stat-boxes2">
                                    <li>
                                        <div class="left peity_line_neutral">
                                        <%$arrayLaguage['order_number']['page_laguage_value']%>
                                        </div>
                                        <div class="right"> <a href="<%$arrayBookEditUrl[$book_order_number].url%>"><%$book_order_number%></a> </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="controls">
                                <ul class="stat-boxes stat-boxes2">
                                <%foreach key=i item=book from=$arrayData%>
                                    <li>
                                        <!--<div class="left peity_line_neutral">
                                        1<%$arrayLaguage['room_number']['page_laguage_value']%>
                                        </div>-->
                                        <div class="left">
                                        <%if $book.book_order_number_status != '0' || $book.book_is_pay == '0'%>
                                            <i class="am-icon-check-square-o am-green-54B51C am-icon-sm"></i>
                                        <%else%>
                                            <i class="am-icon-exclamation-circle am-red-FA0A0A am-icon-sm error_night_audit">
                                            <%if $book.book_order_number_status == '0'%>未入住<%/if%>
                                            <%if $book.book_is_pay == '0'%> 未付款<%/if%>
                                            <%if $book.book_is_pay == '1'%> 已付款<%/if%>
                                            </i>
                                        <%/if%>
                                        </div>
                                        <div class="left peity_line_neutral">
                                        <%$arrayLaguage['room_number']['page_laguage_value']%>
                                        </div>
                                        <div class="right"> <%$arrayRoom[$book.room_id].room_name%>[<%$arrayRoom[$book.room_id].room_number%>] </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="left peity_line_neutral">入住日期</div>
                                        <div class="right"> <%$book.book_check_in%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="left peity_line_neutral">退房日期</div>
                                        <div class="right"> <%$book.book_check_out%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="left peity_line_neutral">联系人</div>
                                        <div class="right"> <%$book.book_contact_name%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="left peity_line_neutral">电话</div>
                                        <div class="right"> <%$book.book_contact_mobile%> </div>
                                    </li>
                                    <%if isset($arrayBookNightAudit[$book.book_order_number]) && isset($arrayBookNightAudit[$book.book_order_number][$book.room_id])%>
                                    <%foreach key=j item=nightAudit from=$arrayBookNightAudit[$book.book_order_number][$book.room_id]%>
                                    <li class="nightAudit" data-id="<%$nightAudit.book_night_audit_id%>" number="<%$book_order_number%>" room_id="<%$book.room_id%>">
                                        <div class="left peity_line_neutral">日期</div>
                                        <div class="right"> <%$nightAudit.book_night_audit_fiscal_day%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="left"><%$arrayLaguage[$nightAudit.book_night_audit_income_type]['page_laguage_value']%></div>
                                        <div class="left peity_line_neutral">收入</div>
                                        <div class="right"> <%$nightAudit.book_night_audit_income%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="left peity_line_neutral">原价</div>
                                        <div class="right"> <%$nightAudit.price%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="left peity_line_neutral">折扣</div>
                                        <div class="right"> <%$nightAudit.book_discount%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="right"><%if $nightAudit.book_night_audit_valid==1%>有效<%else%>无效<%/if%></div>
                                    </li>
                                    <%/foreach%>
                                    <%/if%>
                                <%/foreach%>
                                <%if isset($arrayBookNightAudit[$book_order_number]) && isset($arrayBookNightAudit[$book.book_order_number][0])%>
                                    <%foreach key=room_id item=nightAudit from=$arrayBookNightAudit[$book_order_number][0]%>
                                    <li class="nightAudit" data-id="<%$nightAudit.book_night_audit_id%>" number="<%$book_order_number%>" room_id="0">
                                        <div class="left peity_line_neutral">日期</div>
                                        <div class="right"> <%$nightAudit.book_night_audit_fiscal_day%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="left"><%$arrayLaguage[$nightAudit.book_night_audit_income_type]['page_laguage_value']%></div>
                                        <div class="right"> <%$arrayService[$nightAudit.hotel_service_id].hotel_service_name%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="left peity_line_neutral">收入</div>
                                        <div class="right"> <%$nightAudit.book_night_audit_income%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="left peity_line_neutral">原价</div>
                                        <div class="right"> <%$nightAudit.price%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="left peity_line_neutral">折扣</div>
                                        <div class="right"> <%$nightAudit.book_discount%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="left peity_line_neutral">数量</div>
                                        <div class="right"> <%$nightAudit.hotel_service_num%> </div>
                                        <div class="left peity_line_neutral">&#12288;</div>
                                        <div class="right"><%if $nightAudit.book_night_audit_valid==1%>有效<%else%>无效<%/if%></div>
                                    </li>
                                    <%/foreach%>
                                <%/if%>
                                </ul>
                            </div>
                        </div>
                        <%/foreach%>
                        <div class="control-group">
                            <label class="control-label"></label>
                            <div class="controls">
                                <a id="check_night_audit" class="btn btn-success"><i class="am-icon-check am-yellow-EBC012"></i> 检查完毕，确定夜审</a>
                            </div>
                        </div>
                    </div>
                    <%else%>
                     <div class="control-group">
                        <label class="control-label"></label>
                        <div class="controls">还没到夜审时间！</div>
                    </div>
                    <%/if%>
                </div>
                <%else%>
                <div class="widget-content nopadding form-horizontal" id="room_status">
                    <div class="control-group">
                        <label class="control-label">B </label>
                        <div class="controls"></div>
                    </div>
                    
                </div>
                <%/if%>
            </div>
        </div>
        
    </div>
    
    </div>
</div>
<%include file="hotel/inc/footer.tpl"%>
<%include file="hotel/inc/modal_box.tpl"%>
<%include file="hotel/inc_js/nightAudit_js.tpl"%>
</body>
</html>