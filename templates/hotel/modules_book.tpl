<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<link rel="stylesheet" href="<%$__RESOURCE%>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<%$__RESOURCE%>js/jquery.datetimepicker.full.min.js"></script>
<style type="text/css">
.custom-date-style{ cursor:pointer; color:#666666 !important;}
.dropdown-menu {margin: 2px -65px 0 !important;}
.dropdown-menu li{padding:0px !important;}
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
                    <h5><%$arrayLaguage['book_info']['page_laguage_value']%> <%$today%></h5>
                    <%if $arrayRoleModulesEmployee['role_modules_action_permissions']> 0%>
                    <div class="buttons">
                        <a class="btn btn-primary btn-mini" href="<%$add_book_url%>"><i class="am-icon-plus-square"></i>
                        &#12288;<%$arrayLaguage['add_book']['page_laguage_value']%></a>
                    </div>
                    <%/if%>
                </div>
                <div class="widget-content nopadding">
                    <form action="<%$search_url%>" method="post" class="form-horizontal ui-formwizard" enctype="multipart/form-data">
                        <div class="control-group" id="form-wizard-1">
                            <label class="control-label"><%$arrayLaguage['please_select']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <span class="add-on am-icon-calendar"></span>
                                    <input class="input-small" type="text" id="time_begin" name="time_begin" value="<%$thisDay%>" />
                                    <span class="add-on am-icon-calendar"></span>
                                    <input class="input-small" type="text" id="time_end" name="time_end" value="<%$toDay%>" />
                                    <span class="add-on am-icon-user"></span>
                                    <input class="input-small" type="text" id="user_name" name="user_name" value="<%$user_name%>" />
                                    <button class="btn btn-primary"><i class="am-icon-search"></i> <%$arrayLaguage['search']['page_laguage_value']%></button >
                                </div>
                            <%if $arrayRoleModulesEmployee['role_modules_action_permissions']> 0%>
                                <a class="btn btn-link" href="<%$add_book_url%>"><i class="am-icon-user-plus"></i>
                                <%$arrayLaguage['add_book']['page_laguage_value']%></a>
                            <%/if%>
                            </div>
                        </div>
                    </form>
               </div>
                <div class="widget-content nopadding">
                    <ul class="recent-posts">
                    <%section name=book loop=$arrayBookList%>
                      <li>
                        <div class="article-post"> 
                        <i class="am-icon-user am-green-54B51C"></i>
                        	<div class="fr">
                                <div class="btn-group">
                                    <a class="btn btn-mini btn-primary" data-target="#" href="<%$arrayBookList[book].number_main.edit_url%>"><i class="am-icon-sun-o"></i> 
                                    <%if $arrayBookList[book].number_main.book_order_number_main_status == '0'%>办理入住
                                    <%elseif  $arrayBookList[book].number_main.book_order_number_main_status == '1'%>入住完成
                                    <%elseif  $arrayBookList[book].number_main.book_order_number_main_status == '-1'%>退房完成
                                    <%else%><%$arrayLaguage['manage']['page_laguage_value']%>
                                    <%/if%>
                                    </a>
                                    <a class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a data-target="#" href="<%$arrayBookList[book].number_main.edit_url%>"><i class="am-icon-pencil-square-o"></i> <%$arrayLaguage['manage']['page_laguage_value']%></a></li>
                                        <li><a data-target="#" href="#"><i class="am-icon-trash-o"></i> Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                            <a href="#collapse<%$arrayBookList[book].number_main.book_order_number%>" data-toggle="collapse">
                            <strong><%$arrayBookList[book].number_main.book_contact_name%></strong>
                            <span class="user-info"> 
                                <i class="am-icon-bed am-blue-17C6EA"></i> : 
                                <%$arrayBookList[book].room_num%> room <span> </span>
                                <i class="am-icon-clock-o am-blue-17C6EA"></i> : 
                                <%$arrayBookList[book].number_main.book_add_date%> <%$arrayBookList[book].number_main.book_add_time%> 
                                <i class="am-icon-mobile am-blue-17C6EA"></i> : 
                                <%$arrayBookList[book].number_main.book_contact_mobile%> 
                                <i class="am-icon-commenting-o"></i> : 
                                <%$arrayBookList[book].number_main.book_comments%>
                            </span>
                          </a>
                          <p></p>
                          <!--<p><a href="#collapse<%$arrayBookList[book].number_main.book_order_number%>" data-toggle="collapse"><i class="am-icon-commenting-o"></i>
                          <%$arrayBookList[book].number_main.book_comments%></a> </p>-->
                          
                          </div>
                          <%section name=lodger loop=$arrayBookList[book].child%>
                          <div class="collapse" id="collapse<%$arrayBookList[book].number_main.book_order_number%>">
                                <div class="new-update clearfix"><i class="icon-ok-sign"></i>
                                  <div class="update-done">
                                    <%$arrayBookList[book].child[lodger].book_contact_name%> <span><%$arrayBookList[book].child[lodger].book_comments%></span> 
                                  </div>
                                  <div class="update-date"><span class="update-day">20</span>jan</div>
                                </div>
                          </div>
                          <%/section%>
                      </li>
                     <%/section%>
                      
                      <!--<li>
                        <div class="article-post"> 
                        <i class="icon-user"></i>
                        	<div class="fr">
                                <div class="btn-group">
                                    <a class="btn btn-primary" href="#"><i class="icon-user icon-white"></i> User</a>
                                    <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#"><i class="icon-pencil"></i> Edit</a></li>
                                        <li><a href="#"><i class="icon-trash"></i> Delete</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#"><i class="i"></i> Make admin</a></li>
                                    </ul>
                                </div>
                            </div>
                          <a href="#collapse2" data-toggle="collapse">
                            <strong>Themeforest</strong>Approved My college theme <strong>1 user</strong> <span>2 hours ago</span>
                            <span class="user-info"> By: john Deo / Date: 2 Aug 2012 / Time:09:27 AM </span>
                          </a>
                          <p><a href="#">This is a much longer one that will go on for a few lines.It has multiple paragraphs and is full of waffle to pad out the comment.</a> </p>
                          
                          </div>
                          <div class="collapse" id="collapse2">
                                    <div class="new-update clearfix"><i class="icon-ok-sign"></i>
                                      <div class="update-done"><a href="#" title=""><strong>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</strong></a> <span>dolor sit amet, consectetur adipiscing eli</span> </div>
                                      <div class="update-date"><span class="update-day">20</span>jan</div>
                                    </div>
                            </div>
                      </li>-->
                    </ul>
                    
                    
                    <!--<ul class="activity-list">
                    	<li>
                           
                        	<a href="#collapseOne" data-toggle="collapse">
                            <i class="icon-user"></i>
                            <strong>Themeforest</strong>Approved My college theme <strong>1 user</strong> <span>2 hours ago</span>
                        	</a>
                            
                            
                        </li>
                        <li>
                        	<a href="#collapse2" data-toggle="collapse">
                            <i class="icon-user"></i>
                            <strong>Themeforest</strong>Approved My college theme <strong>1 user</strong> <span>2 hours ago</span>
                        	</a>
                            <div class="collapse" id="collapse2">
                            	<div class="widget-content">
                                This box is opened by default
                                </div>
                            </div>
                        </li>
                     </ul>-->
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
    //日历
	$.datetimepicker.setLocale('ch');
	var dateToDisable =  new Date('<%$thisDay%>'); 
	$('#time_begin').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, 
        yearStart: '1980', yearEnd: '<%$nextYear%>', //yearOffset:1,maxDate:'+1970-01-02',
		beforeShowDay: function(date) {
            return [true];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
        onSelectDate:function(date) {//console.log(date + '-====-' + nextWeekDateToDisable + '----'+ week_differ + '-----<%$thisDay%>');
            var thisDate = new Date(this.getValue());
            var nextDate = new Date(thisDate.setDate(thisDate.getDate() + 1));
            var time_end_date = new Date($('#time_end').val());
            if(time_end_date.getTime() < nextDate.getTime()) {
                $('#time_end').val(nextDate);
                $('#time_end').datetimepicker({value:nextDate});
            }
        },
		onShow:function(date) {
			//xdsoft_calendar data-date="31" data-month="9" data-year="2016"
		}
       
	});
	$('#time_end').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, yearEnd: '<%$nextYear%>',
		beforeShowDay: function(date) {//new Date($('#book_check_in').val()).getDate()
			var dateToDisable = new Date($('#time_begin').val());
            dateToDisable.setDate(dateToDisable.getDate() + 6);
			if (date.getTime() < dateToDisable.getTime()) {
                //alert((date.getTime() + '----' + (dateToDisable.getTime() - 0 + 36000 * 24 * 6)));
				return [false];
			}
            var dateToDisableNext = new Date($('#time_begin').val());
            dateToDisableNext.setDate(dateToDisableNext.getDate() + 180);
            if (date.getTime() > dateToDisableNext.getTime()) {
                //alert((date.getTime() + '----' + (dateToDisable.getTime() - 0 + 36000 * 24 * 6)));
				return [false];
			}
			return [true, ""];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
	});
    
    var BookClass = {
        instance: function() {
            var book = {};
            book.thisYear = '<%$thisYear%>';
            book.thisMonth = '<%$thisMonth%>';
            book.time_begin = '<%$thisDay%>';
            book.time_end = '<%$toDay%>';
            return book;
        },

    }
    var book = BookClass.instance();

})
</script>
</body>
</html>