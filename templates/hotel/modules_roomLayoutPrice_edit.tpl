<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<link rel="stylesheet" href="<%$__RESOURCE%>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<%$__RESOURCE%>js/jquery.datetimepicker.full.min.js"></script>

<style type="text/css">
.quick-actions li a { padding: 10px 5px 5px;}
.pagination-left { text-align:left }
.stat-boxes, .quick-actions, .quick-actions-horizontal, .stats-plain { margin:0px;}
.stat-boxes li, .quick-actions li, .quick-actions-horizontal li,#kalendar_week li{margin:0px 5px 5px 0;}
.quick-actions li{max-width:210px; min-width:210px; width:210px;}
#room_layout_price_kalendar li,#kalendar_week li, #different_price_month li{max-width:100px; min-width:100px; width:100px;}
.custom-date-style{ cursor:pointer; color:#666666 !important;}
#hotel_service input{margin:0px 2px 0px 8px;}
#hotel_service label{display: inline-block;}
.dropdown-menu li,.dropdown-menu li a{word-break:break-all;word-wrap:break-word;white-space:normal !important;}
.btn-group .btn {border: 1px solid #8C8585}
.btn-group.btn-group {margin-right: 5px; margin-left:0px;}
.none{border:none !important; padding-left:2px !important;}
.stat-boxes2{top:0px;right:0px; text-align:left;}
.stat-boxes .right strong{ font-size:14px; font-weight:normal;}
.stat-boxes .left{padding: 1px 5px 6px 1px;margin-right: 1px; text-align:center;}
.stat-boxes .left span{font-size:12px; font-style:italic;}
.stat-boxes .right{padding:5px 0 0; width:auto;}
.stat-boxes li{margin:0px 1px 0;padding: 0 3px;line-height: 12px;}
</style>
<script src="<%$__RESOURCE%>js/select2.min.js"></script>
<link rel="stylesheet" href="<%$__RESOURCE%>css/select2.css" />
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" />-->
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
                    <h5><%$selfNavigation['hotel_modules_name']%></h5>
                    <div class="buttons" id="btn_room_layout">
                        <a class="btn btn-primary btn-mini" href="<%$back_lis_url%>" id="back"><i class="am-icon-plus-square"></i> 
                        &#12288;<%$arrayLaguage['back_list']['page_laguage_value']%></a>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <form method="post" class="form-horizontal" enctype="multipart/form-data" novalidate>
                    <%if $arrayLayoutCorp!=''%>
                    <div class="control-group">
                        <label class="control-label">协议价种类 :</label>
                        <div class="controls"><input class="input-large" value="<%$arrayLayoutCorp.0.room_layout_corp_name%>" type="text" readonly></div>
                    </div>
                    <%/if%>
                    <div class="control-group">
                        <label class="control-label"><%$arrayLaguage['sale_room']['page_laguage_value']%> :</label>
                        <div class="controls">
                            <select name="sell_layout" id="sell_layout" class="span2">
                                <option value="0" extra_bed=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                            <%section name=layout loop=$arrayRoomSellLayout%>
                                <option value="<%$arrayRoomSellLayout[layout].room_sell_layout_id%>" extra_bed="<%$arrayRoomLayout[$arrayRoomSellLayout[layout].room_layout_id].room_layout_extra_bed%>" layout_id="<%$arrayRoomSellLayout[layout].room_layout_id%>"><%$arrayRoomSellLayout[layout].room_sell_layout_name%></option>
                            <%/section%>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><%$arrayLaguage['room_layout_price_system']['page_laguage_value']%> :</label>
                        <div class="controls" id="system_prices_html">
                            <div class="btn-group"><a class="btn"><i class="am-icon-circle-o"></i> <%$arrayLaguage['room_layout_price_system']['page_laguage_value']%></a>
                            </div>
                            <!--<%section name=system loop=$arrayRoomLayoutPriceSystem%>
                                <div class="btn-group system_prices" data-id="<%$arrayRoomLayoutPriceSystem[system].room_layout_price_system_id%>">
                                    <a class="btn" href="#system_prices"><i class="am-icon-circle-o"></i> <%$arrayRoomLayoutPriceSystem[system].room_layout_price_system_name%></a>
                                    <%if $arrayRoomLayoutPriceSystem[system].room_layout_price_system_id > 1%>
                                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                    <ul class="dropdown-menu" data-id="<%$arrayRoomLayoutPriceSystem[system].room_layout_price_system_id%>" layout-id="<%$arrayRoomLayoutPriceSystem[system].room_layout_id%>" data-name="<%$arrayRoomLayoutPriceSystem[system].room_layout_price_system_name%>">
                                        <li><a href="#" class="system_prices_edit"><i class="am-icon-pencil am-yellow-FFAA3C"></i> Edit</a></li>
                                        <li><a href="#" class="system_prices_delete"><i class="am-icon-trash am-red-FB0000"></i> Delete</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#"><i class="i"></i>
                                        <%section name=service loop=$arrayRoomLayoutPriceSystem[system].hotel_service_id%>
                                        <i class="am-icon-check-square-o" data-id="<%$arrayRoomLayoutPriceSystem[system].hotel_service_id[service]%>"></i><%$arrayRoomLayoutPriceSystem[system].hotel_service_name[service]%>
                                        <%/section%></a>
                                        </li>
                                    </ul>
                                    <%/if%>
                                </div>
                            <%/section%>-->
                            
                        </div>
                        <div class="controls">
                            <a id="add_edit_system" class="btn btn-primary btn-mini"><i class="icon-plus-sign"></i> <%$arrayLaguage['add_room_layout_price_system']['page_laguage_value']%></a>
                        </div>
                    </div>
                    </form>
                </div>
                <div id="addSystemPrice" class="collapse widget-content nopadding">
                    <div class="control-group">
                        <div class="controls">
                            <form method="post" class="form-horizontal" enctype="multipart/form-data" name="room_layout_price_system" id="room_layout_price_system" novalidate>
                                <div class="modal-header">
                                    <button data-toggle="collapse" data-target="#addSystemPrice" class="close" type="button">×</button>
                                    <h3><%$arrayLaguage['add_room_layout_price_system']['page_laguage_value']%></h3>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><%$arrayLaguage['sale_room']['page_laguage_value']%> :</label>
                                    <div class="controls">
                                        <select name="sell_layout_id" id="sell_layout_id" class="span2">
                                        <option value="0"><%$arrayLaguage['common_room_layout']['page_laguage_value']%></option>
                                        <%section name=layout loop=$arrayRoomSellLayout%>
                                            <option value="<%$arrayRoomSellLayout[layout].room_sell_layout_id%>"><%$arrayRoomSellLayout[layout].room_sell_layout_name%></option>
                                        <%/section%>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><%$arrayLaguage['system_price_name']['page_laguage_value']%> :</label>
                                    <div class="controls">
                                        <input id="price_system_name" name="price_system_name" class="span2" placeholder="" value="" type="text">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><%$arrayLaguage['select_additional_services']['page_laguage_value']%> :</label>
                                    <div class="controls" id="hotel_service">
                                        
                                    </div>
                                </div>

                              <div class="control-group"> 
                                <div class="controls"><button type="submit" id="save_info" class="btn btn-success pagination-centered">Save</button> <a data-toggle="collapse" data-target="#addSystemPrice" class="btn" href="#">Cancel</a> 
                                </div>  
                              </div>
                              <input type="hidden" name="update_system_id" id="update_system_id" value="" />
                            </form>
                        </div>
                    </div>
                </div>
                <div class="widget-title hide" id="title_price">
                    <ul class="nav nav-tabs">
                        <li id="prices_on_a_week"><a data-toggle="tab" href="#tab1"><%$arrayLaguage['set_prices_on_a_week']['page_laguage_value']%></a></li>
                        <li class="active" id="prices_on_a_monthly"><a data-toggle="tab" href="#tab2"><%$arrayLaguage['set_prices_on_a_monthly']['page_laguage_value']%></a></li>
                        <li id="history_prices"><a data-toggle="tab" href="#tab3"><%$arrayLaguage['history_prices']['page_laguage_value']%></a></li>

                    </ul>
                </div>
                <div class="widget-content tab-content nopadding hide" id="title_content">
                    <div id="tab1" class="tab-pane">
                    	<form method="post" class="form-horizontal" enctype="multipart/form-data" name="prices_week" id="prices_week" novalidate>
                        <div class="control-group">
                            <label class="control-label"><%$arrayLaguage['please_select']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <input type="text" class="span1" id="time_begin" name="time_begin" value="<%$thisDay%>" /> - 
                                <input type="text" class="span1" id="time_end" name="time_end" value="<%$toDay%>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><%$arrayLaguage['set_price']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <ul class="quick-actions pagination-left" id="room_layout_price_week">
                                    <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周一</i>
                                        <input id="week_1" name="week_1" class="span8" type="text" /></a> 
                                    </li>
                                    <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周二</i>
                                        <input id="week_2" name="week_2" class="span8" type="text" /></a> 
                                    </li>
                                    <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周三</i>
                                        <input id="week_3" name="week_3" class="span8" type="text" /></a> 
                                    </li>
                                    <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周四</i>
                                        <input id="week_4" name="week_4" class="span8" type="text" /></a> 
                                    </li>
                                    <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周五</i>
                                        <input id="week_5" name="week_5" class="span8" type="text" /></a> 
                                    </li>
                                    <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周六</i>
                                        <input id="week_6" name="week_6" class="span8" type="text" /></a> 
                                    </li>
                                    <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周日</i>
                                        <input id="week_7" name="week_7" class="span8" type="text" /></a> 
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="control-group hide extra_bed">
                            <label class="control-label"><%$arrayLaguage['extra_bed_price']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <div class="btn-group">
                                    <a class="btn select_extra_bed_week" id='same_week' href="#select">
                                        <i class="am-icon-circle-o am-icon-dot-circle-o"></i> <%$arrayLaguage['uniform_price']['page_laguage_value']%>
                                    </a> 
                                    <a class="btn select_extra_bed_week" id='different_week' href="#select">
                                        <i class="am-icon-circle-o"></i> <%$arrayLaguage['week_price']['page_laguage_value']%>
                                    </a>
                                </div>
                            </div>
                            <label class="control-label"><%$arrayLaguage['set_price']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <div id="same_price_week"><input class="span1" type="text" name="extra_bed_price" id="extra_bed_price_week" value="" /></div>
                                <div id="different_price_week" class="hide">
                                    <ul class="quick-actions pagination-left" id="room_layout_same_price_week">
                                        <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周一</i>
                                            <input id="extra_bed_week_1" name="extra_bed[week_1]" class="span8" type="text" /></a> 
                                        </li>
                                        <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周二</i>
                                            <input id="extra_bed_week_2" name="extra_bed[week_2]" class="span8" type="text" /></a> 
                                        </li>
                                        <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周三</i>
                                            <input id="extra_bed_week_3" name="extra_bed[week_3]" class="span8" type="text" /></a> 
                                        </li>
                                        <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周四</i>
                                            <input id="extra_bed_week_4" name="extra_bed[week_4]" class="span8" type="text" /></a> 
                                        </li>
                                        <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周五</i>
                                            <input id="extra_bed_week_5" name="extra_bed[week_5]" class="span8" type="text" /></a> 
                                        </li>
                                        <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周六</i>
                                            <input id="extra_bed_week_6" name="extra_bed[week_6]" class="span8" type="text" /></a> 
                                        </li>
                                        <li> <a> <i class="am-icon-sm am-icon-calendar-minus-o "> 周日</i>
                                            <input id="extra_bed_week_7" name="extra_bed[week_7]" class="span8" type="text" /></a> 
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pagination-centered">
                            <button type="submit" class="btn btn-primary pagination-centered save_info"><%$arrayLaguage['save']['page_laguage_value']%></button>
                        </div>
                        </form>
                    </div>
                	<div id="tab2" class="tab-pane active">
                    	<form method="post" class="form-horizontal" enctype="multipart/form-data" name="prices_month" id="prices_month" novalidate>
                        <div class="control-group">
                            <label class="control-label"><%$arrayLaguage['please_select']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <select name="room_layout_date_year" id="room_layout_date_year" class="span1">
                                    <option value="<%$thisYear%>" ><%$thisYear%></option>
                                    <option value="<%$thisYear + 1%>" ><%$thisYear + 1%></option>
                                </select>
                                
                                <select name="room_layout_date_month" id="room_layout_date_month" class="span1">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><%$arrayLaguage['set_price']['page_laguage_value']%> :</label>
                            <div class="controls">
                                 <ul class="quick-actions pagination-left" id="kalendar_week">
                                    <li><a><i class="am-icon-sm am-icon-calendar-minus-o "> 周一</i></a></li><li><a><i class="am-icon-sm am-icon-calendar-minus-o "> 周二</i></a></li><li><a><i class="am-icon-sm am-icon-calendar-minus-o "> 周三</i></a></li><li><a><i class="am-icon-sm am-icon-calendar-minus-o "> 周四</i></a></li><li><a><i class="am-icon-sm am-icon-calendar-minus-o "> 周五</i></a></li><li><a><i class="am-icon-sm am-icon-calendar-minus-o "> 周六</i></a></li><li><a><i class="am-icon-sm am-icon-calendar-minus-o "> 周日</i></a></li>
                                </ul><br>
                                <ul class="quick-actions pagination-left" id="room_layout_price_kalendar"></ul>
                            </div>
                        </div>
                        <div class="control-group hide extra_bed">
                            <label class="control-label"><%$arrayLaguage['extra_bed_price']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <div class="btn-group">
                                    <a class="btn select_extra_bed_month" id="same_month" href="#select">
                                        <i class="am-icon-circle-o"></i> <%$arrayLaguage['uniform_price']['page_laguage_value']%>
                                    </a> 
                                    <a class="btn select_extra_bed_month" id="different_month" href="#select">
                                        <i class="am-icon-circle-o am-icon-dot-circle-o"></i> <%$arrayLaguage['month_price']['page_laguage_value']%>
                                    </a>
                                </div>
                            </div>
                            <div class="controls">
                                <div id="same_price_month" class="hide"><input class="span1" type="text" name="extra_bed_price" id="extra_bed_price_month" value="" /></div>
                                <div id="different_price_month"></div>
                            </div>
                        </div>
                        <div class="form-actions pagination-centered">
                            <button type="submit" class="btn btn-primary pagination-centered save_info"><%$arrayLaguage['save']['page_laguage_value']%></button>
                        </div>
                        </form>
                    </div>
                    <div id="tab3" class="tab-pane">
                    	<form action="" method="post" class="form-horizontal" enctype="multipart/form-data" name="search_history" id="search_history" novalidate>
                        <div class="control-group">
                            <label class="control-label"><%$arrayLaguage['please_select']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <input type="text" id="history_begin" value="<%$thisDay%>" class="span1" /> - 
                                <input type="text" id="history_end" value="<%$toDay%>" class="span1" />
                                <button type="submit" class="btn btn-primary pagination-centered save_info am-icon-search" id="history_search_btn">
                                    <%$arrayLaguage['search']['page_laguage_value']%>
                                </button>
                            </div>
                            
                        </div>
                        <div class="control-group">
                        <label class="control-label"><%$arrayLaguage['price']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <ul class="stat-boxes stat-boxes2" id="history_price_list_html">
                                  <li>
                                    <div class="left peity_bar_good">
                                        <span></span></div>
                                    <div class="right"> <strong></strong> </div>
                                  </li>
                                </ul>
                            </div>
                        </div>
                        </form>
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
<%include file="hotel/inc_js/roomLayoutPrice_edit_js.tpl"%>
</body>
</html>