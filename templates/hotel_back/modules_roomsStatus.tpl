<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<script src="<%$__RESOURCE%>js/jquery.peity.min.js"></script>
<link rel="stylesheet" href="<%$__RESOURCE%>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<%$__RESOURCE%>js/jquery.datetimepicker.full.min.js"></script>
<style type="text/css">
#room_status ul{text-align:left;}
#room_status .stat-boxes2{top:0px;}
.form-horizontal .div-control-label {padding-top: 10px; margin-left:20px;width: 180px;float: left;text-align: right;}
.form-horizontal .right{width:50px;}
.room_status_ul .left{height:100px;}
.room_status_ul .dropdown-menu li{ display:list-item;}
.room_status_ul .dropdown-menu li a{ padding: 3px 0;}
.room_status_ul .dropdown-menu li a:hover{ background:none;}
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
                        <i class="am-icon-braille am-yellow-E88A26"></i>
                    </span>
                    <h5><%$selfNavigation['hotel_modules_name']%></h5>
                </div>
                <div class="widget-content">
                    <ul class="stat-boxes stat-boxes2">
                      <li>
                        <div class="left peity_bar_better"><span><span style="display: none;">12,12,12,12,12,12,12</span><canvas width="50" height="24"></canvas></span>[info]</div>
                        <div class="right"> <strong>预定</strong> Book </div>
                      </li>
                      <li>
                        <div class="left peity_bar_good"><span><span style="display: none;">12,12,12,12,12,12,12</span><canvas width="50" height="24"></canvas></span>[info]</div>
                        <div class="right"> <strong>入住</strong> Check in </div>
                      </li>
                      <li>
                        <div class="left peity_bar_small"><span><span style="display: none;">12,12,12,12,12,12,12</span><canvas width="50" height="24"></canvas></span>[info]</div>
                        <div class="right"> <strong>预离</strong> Departure </div>
                      </li>
                      <li>
                        <div class="left peity_bar_neutral"><span><span style="display: none;">12,12,12,12,12,12,12,12</span><canvas width="50" height="24"></canvas></span>[info]</div>
                        <div class="right"> <strong>空房</strong> Vacant </div>
                      </li>
                      <li>
                        <div class="left peity_bar_bad"><span><span style="display: none;">12,12,12,12,12,12,12</span><canvas width="50" height="24"></canvas></span>[info]</div>
                        <div class="right"> <strong>脏房</strong> Dirty </div>
                      </li>
                      <li>
                        <div class="left peity_bar_little"><span><span style="display: none;">12,12,12,12,12,12,12</span><canvas width="50" height="24"></canvas></span>[info]</div>
                        <div class="right"> <strong>维修</strong> Servicing </div>
                      </li>
                    </ul>
                </div>
                <div class="widget-title"><span class="icon"><i class="am-icon-clock-o"></i></span><h5></h5></div>
                <div class="widget-content nopadding">
                    <form action="<%$search_url%>" method="get" class="form-horizontal ui-formwizard" enctype="multipart/form-data">
                        <input type="hidden" value="<%$module%>" name="module">
                        <div class="control-group">
                            <label class="control-label"><%$arrayLaguage['please_select']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <span class="add-on am-icon-calendar"></span>
                                    <input class="input-small" type="text" id="time_begin" name="time_begin" value="<%$thisDay%>" />
                                    <span class="add-on am-icon-calendar"></span>
                                    <input class="input-small" type="text" id="time_end" name="time_end" value="<%$toDay%>" />
                                    <button class="btn btn-primary"><i class="am-icon-search"></i> <%$arrayLaguage['search']['page_laguage_value']%></button >
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="widget-title"><span class="icon"><i class="am-icon-bullseye"></i></span><h5><%$selfNavigation['hotel_modules_name']%></h5></div>
                <div class="widget-content nopadding form-horizontal" id="room_status">
                    <div class="control-group">
                        <label class="control-label"> </label>
                        <div class="controls"></div>
                    </div>
                    <%foreach key=room_mansion item=arrayMansion from=$arrayRoom%>
                    <div class="control-group">
                        <div class="div-control-label">
                            <ul class="stat-boxes stat-boxes2">
                                <li>
                                    <div class="left peity_line_neutral">
                                    <%$arrayLaguage['room_mansion']['page_laguage_value']%>
                                    </div>
                                    <div class="right"> <%$room_mansion%> </div>
                                </li>
                            </ul>
                        </div>
                        <div class="controls">
                            <%foreach key=room_floor item=room from=$arrayMansion%>
                            <ul class="stat-boxes stat-boxes2">
                                <li>
                                    <div class="left peity_line_neutral">
                                    <%$arrayLaguage['room_floor']['page_laguage_value']%>
                                    </div>
                                    <div class="right"> <%$room_floor%> </div>
                                </li>
                            </ul>
                            <ul class="stat-boxes stat-boxes2 room_status_ul">
                                <!--<li>
                                    <div class="left peity_line_neutral">
                                    
                                    </div>
                                    <div class="right"> <%$room_floor%> </div>
                                </li>-->
                                <%section name=i loop=$room%>
                                  <li room_id="<%$room[i].room_id%>" status="<%$room[i].room_status%>">
                                    <!--<div class="left peity_bar_better">
                                        <span>
                                            <span style="display: none;">12,12,12,12,12,12,12</span>
                                            <canvas width="50" height="24"></canvas>
                                        </span>[]
                                    </div>-->
                                    <!--<div class="left"> <%$room[i].room_name%>[<%$room[i].room_number%>] </div>-->
                                    <div class="right"> <%$room[i].room_name%>[<%$room[i].room_number%>] </div>
                                  </li>
                                <%/section%>
                            </ul>
                            <%/foreach%>
                        </div>
                    </div>
                    <%/foreach%>
                </div>
            </div>
        </div>
        
    </div>
    
    </div>
</div>
<%include file="hotel/inc/footer.tpl"%>
<%include file="hotel/inc/modal_box.tpl"%>
<%include file="hotel/inc_js/roomsStatus_js.tpl"%>
</body>
</html>