<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<style type="text/css">
.new-update i{margin-top:0px;}
.roomLayoutPrice td{width:37px;}
@media (max-width: 480px) {
    .roomLayoutPrice td{width:37px;}
}

.stat-boxes2{top:0px;right:0px; text-align:left;}
.stat-boxes .right strong{ font-size:14px; font-weight:normal;}
.stat-boxes .left{padding: 1px 5px 6px 1px;margin-right: 1px; text-align:center;}
.stat-boxes .left span{font-size:12px; font-style:italic;}
.stat-boxes .right{padding:5px 0 0; width:auto;}
.stat-boxes li{margin:0px 1px 0;padding: 0 3px;line-height: 12px;}
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
              <div class="widget-title"> <span class="icon"> <i class="icon-refresh"></i> </span>
                <h5><%$arrayLaguage['manager_room_layout_price']['page_laguage_value']%></h5>
                <%if $arrayRoleModulesEmployee['role_modules_action_permissions']> 0%>
                <div class="buttons" id="btn_room_layout">
                    <a class="btn btn-primary btn-mini" href="<%$add_roomLayoutPriceSystem_url%>" id="add_room_layout"><i class="am-icon-plus-square"></i> 
                    &#12288;<%$arrayLaguage['manager_room_layout_price']['page_laguage_value']%></a>
                </div>
                <%/if%>
              </div>
              <div class="widget-content nopadding">
                    <form action="<%$search_url%>" method="post" class="form-horizontal ui-formwizard" enctype="multipart/form-data">
                        <div class="control-group" id="form-wizard-1">
                            <label class="control-label"><%$arrayLaguage['please_select']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <select name="year" id="year" class="span1">
                                    <option value="<%$thisYear%>" ><%$thisYear%></option>
                                    <option value="<%$thisYear + 1%>" ><%$thisYear + 1%></option>
                                </select>
                                
                                <select name="month" id="month" class="span1">
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
                            <button class="btn btn-primary btn-mini"><i class="am-icon-search"></i> <%$arrayLaguage['search']['page_laguage_value']%></button >
                            <%if $arrayRoleModulesEmployee['role_modules_action_permissions']> 0%>
                                <a class="btn btn-primary btn-mini" href="<%$add_roomLayoutPriceSystem_url%>" id="add_room_layout"><i class="am-icon-plus-square"></i> 
                                <%$arrayLaguage['manager_room_layout_price']['page_laguage_value']%></a>
                            <%/if%>
                            </div>
                        </div>
                    </form>
              </div>
              <div class="widget-content nopadding updates">
                <!--<div class="new-update clearfix"><i class="am-icon-caret-right"></i>
                  <div class="update-done"><a href="#" title=""><strong>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</strong></a> <span>dolor sit amet, consectetur adipiscing eli</span> </div>
                  <div class="update-done">ssss</div>
                  <div class="update-date"><span class="update-day">20</span>jan</div>
                </div>-->
                <%section name=layout loop=$arrayRoomLayoutPriceList%>
                <div class="new-update clearfix nopadding"> 
                    <div class="widget-title">
                        <span class="icon"><i class="am-icon-bed am-icon-sm"></i></span>
                        <h5><a><strong><%$arrayRoomLayoutPriceList[layout].room_sell_layout_name%> </strong></a></h5>
                        <div class="buttons">
                        </div>
                    </div>
                    <span class="form-horizontal">
                        <%section name=system loop=$arrayRoomLayoutPriceList[layout].price_system%>
                            <div class="control-group">
                            <label class="control-label"><!--<i class="am-icon-glass am-blue-2F93FF"></i>--><%$arrayRoomLayoutPriceList[layout].price_system[system].room_layout_price_system_name%> : 
                            </label>
                                <div class="controls">
                                <%if $arrayRoomLayoutPriceList[layout].price_system[system].price != ''%>
                                <%foreach key=i item=price from=$arrayRoomLayoutPriceList[layout].price_system[system].price%>
                                    <ul class="stat-boxes stat-boxes2 pull-left">
                                        <li><div class="left"><span class=""><%$thisYear%></span><%$month%></div><div class="right"><%if $price.room_layout_corp_id>0%>协议价格<%else%>正常价格<%/if%></div></li>
                                        <%section name=price loop=$monthT%>
                                        <%if $smarty.section.price.iteration<10%>
                                            <%$day=0|cat:$smarty.section.price.iteration%>
                                        <%else%>
                                            <%$day=$smarty.section.price.iteration%>
                                        <%/if%>
                                        <li>
                                            <div class="left"><span class="month_price"><%$day%></span><%$month%></div>
                                            <%$day=$day|cat:'_day'%>
                                            <div class="right"> <strong><%$price.$day%></strong> </div>
                                        </li>
                                        <%/section%>
                                    </ul>
                                <%/foreach%>
                                <%else%>
                                <code><i class="am-icon-rmb am-red-EA5555"></i><%$year%>-<%$month%> <%$arrayLaguage['no_price']['page_laguage_value']%></code>
                                <%/if%>
                                </div>
                            </div>
                        <%/section%>
                    </span> 
                    <span class="update-date">
                        <span class="update-day"><%$month%></span><%$year%>
                    </span> 
                </div>
                <%/section%>
                
                <!--<div class="new-update clearfix"> <i class="am-icon-caret-right"></i> <span class="update-alert"> <a href="#" title=""><strong>Maruti is a Responsive Admin theme</strong></a> <span>But already everything was solved. It will ...</span> </span> <span class="update-date"><span class="update-day">07</span>Jan</span> </div>
                <div class="new-update clearfix"> <i class="am-icon-caret-right"></i> <span class="update-done"> <a href="#" title=""><strong>Envato approved Maruti Admin template</strong></a> <span>i am very happy to approved by TF</span> </span> <span class="update-date"><span class="update-day">05</span>jan</span> </div>
                <div class="new-update clearfix"> <i class="am-icon-caret-right"></i> <span class="update-notice"> <a href="#" title=""><strong>I am alwayse here if you have any question</strong></a> <span>we glad that you choose our template</span> </span> <span class="update-date"><span class="update-day">01</span>jan</span> </div>-->
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
    var RoomLayoutClass = {
        instance: function() {
            var roomLayout = {};
            roomLayout.thisYear = '<%$thisYear%>';
            roomLayout.year = '<%$year%>';
            roomLayout.nextYear = '<%$nextYear%>';
            roomLayout.thisMonth = '<%$thisMonth%>';
            roomLayout.month = '<%$month%>';
            roomLayout.monthT = '<%$monthT%>';
            roomLayout.weekday=new Array(7);
            roomLayout.initParameter = function() {
                roomLayout.weekday[0]="日";
                roomLayout.weekday[1]="一";
                roomLayout.weekday[2]="二";
                roomLayout.weekday[3]="三";
                roomLayout.weekday[4]="四";
                roomLayout.weekday[5]="五";
                roomLayout.weekday[6]="六";
            };
            roomLayout.init = function(){
            };
            roomLayout.setMonthPrice = function() {
                $('.month_price').each(function(index, element) {
                    var day = $(this).text();
                    var monthDay = new Date('<%$year%>-<%$month%>-'+day);
                    var weekIndex = monthDay.getDay();
                    if(weekIndex == 0 || weekIndex == 6) {
                         $(this).parent().addClass('peity_bar_good');
                    }//
                    var week = roomLayout.weekday[weekIndex];
                    $(this).parent().html('<span class="month_price">'+day+'</span>'+week);
                });
            };
            roomLayout.setSelectYear = function(year) {
                $('#year').val(year);
            };
            roomLayout.setSelectMonth = function(month) {
                $('#month').val(month);
            }
            return roomLayout;
        },
        
    }
    var roomLayout = RoomLayoutClass.instance();
    roomLayout.initParameter();
    roomLayout.setSelectYear(roomLayout.year);
    roomLayout.setSelectMonth(roomLayout.month);
    roomLayout.setMonthPrice();
})
</script>
</body>
</html>