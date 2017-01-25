<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<script src="<%$__RESOURCE%>js/jquery.validate.js"></script>
<link rel="stylesheet" href="<%$__RESOURCE%>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<%$__RESOURCE%>js/jquery.datetimepicker.full.min.js"></script>
<script src="<%$__RESOURCE%>js/jquery.dataTables.min.1.10.12.js"></script>
<!--<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>-->
<link rel="stylesheet" href="<%$__RESOURCE%>css/jquery.dataTables.min.1.10.12.css" />
<script src="<%$__RESOURCE%>js/select2.min.js"></script>
<link rel="stylesheet" href="<%$__RESOURCE%>css/select2.css" />
<style type="text/css">
/*.modal-body{ padding:1px;}*/
/*.widget-box{margin-bottom:1px; margin-top:1px;}*/
#room_layout_paginate a{border:1px solid #BFBDBD;}
.dataTables_wrapper .dataTables_paginate .paginate_button {border-radius: 0;margin-left: 0;min-width: 0;padding: 0.1em 0.5em;}
.details-control{cursor:pointer;}
#noBodyLeft{}
#noBodyLeft th,#noBodyLeft td{padding:5px;}
#noBodyLeft input,#noBodyLeft select{margin-bottom:0px;}
.custom-date-style {background-color:#333333 !important;}
.btn-group .btn {border: 1px solid #8C8585}
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
.custom-date-style{ cursor:pointer; color:#666666 !important;}
#need_service_id li{margin-left:0px;}
#controls_service .btn-group{ margin-bottom:5px; margin-left:0px; margin-right:5px;}
#controls_sell_layout li{font-size:inherit !important; text-align:center; margin: 5px 0 0 0;}
#controls_sell_layout select{ margin-top:-2px;}
.btn-icon-pg li{min-width:auto !important;}
#select_sell_layout li{margin:0 5px 5px 0;padding: 5px; cursor:pointer;}
.quick-actions{margin:0px !important; padding:0px; text-align:left !important;}
input, select {border-radius: 0 !important;}
/*.table-bordered th, .table-bordered td {border-left: 0px solid #ddd !important;}*/
@media (max-width: 480px){
.stat-boxes2 {margin:auto;}
}
</style>
<script src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/localization/messages_zh.js"></script>
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
                    <div class="buttons" id="btn_room_layout">
                        <a class="btn btn-primary btn-mini" href="<%$back_lis_url%>" id="add_room_layout"><i class="am-icon-arrow-circle-left"></i> 
                        &#12288;<%$arrayLaguage['back_list']['page_laguage_value']%></a>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <form action="#" method="post" class="form-horizontal ui-formwizard" enctype="multipart/form-data" name="contact_form" id="contact_form">
                    	<div class="control-group" id="form-wizard-1">
                            <label class="control-label">预订人 
                            <i class="am-icon-user am-green-54B51C"></i> :</label>
                            <div class="controls">
                            <input type="text" id="contact_name" name="contact_name" class="input-medium" placeholder="<%$arrayLaguage['name']['page_laguage_value']%>"  />
                            <%$arrayLaguage['mobile']['page_laguage_value']%> <i class="am-icon-mobile am-green-54B51C"></i> : 
                            <input type="text" id="contact_mobile" name="contact_mobile" class="input-medium" placeholder="<%$arrayLaguage['mobile']['page_laguage_value']%>"  />
                            <%$arrayLaguage['email']['page_laguage_value']%> : 
                            <input type="text" id="contact_email" name="contact_email" class="span2" placeholder="<%$arrayLaguage['email']['page_laguage_value']%>"  />
                            <a href="#begin_book" id="begin_book" class="btn btn-primary btn-mini"><i class="am-icon-plus-circle"></i> <%$arrayLaguage['begin_book']['page_laguage_value']%></a>
                            </div>
                        </div>
					</form>
					<form action="#" method="post" class="form-horizontal ui-formwizard" enctype="multipart/form-data" name="book_form" id="book_form">
						<input type="hidden" value="" name="book_contact_name" id="book_contact_name">
						<input type="hidden" value="" name="book_contact_mobile" id="book_contact_mobile">
                        <input type="hidden" value="" name="book_contact_email" id="book_contact_email">
						<div class="control-group hide book_form_step1">
							<label class="control-label"><%$arrayLaguage['book_type']['page_laguage_value']%> :</label>
							<div class="controls">
                                <div id="book_type_id_div" class="fl">
                                 <select name="book_type_id" id="book_type_id" class="input-medium">
                                 <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                 <%section name=type loop=$arrayBookType%>
                                    <%if $arrayBookType[type].book_type_father_id!=$arrayBookType[type].book_type_id%>
                                    <option value="<%$arrayBookType[type].book_type_id%>" bookType="<%$arrayBookType[type].type%>"><%$arrayBookType[type].book_type_name%></option>
                                    <%/if%>
                                 <%/section%>
                                </select>
                                 &nbsp;</div> 
                                 <div id="book_discount_id_div"></div>
							</div>
						</div>
						<div class="control-group hide book_form_step1">
							<label class="control-label"><%$arrayLaguage['discount']['page_laguage_value']%> :</label>
							<div class="controls">
                                 <span id="discount_type">折扣</span>
                                 <input type="hidden" name="book_discount_type" id="book_discount_type" value="0">
								 <input type="text" id="discount" name="book_discount" class="input-mini book_price" placeholder="<%$arrayLaguage['discount']['page_laguage_value']%>" value="100"  />
								 <%$arrayLaguage['discount_describe']['page_laguage_value']%> :
								 <input type="text" id="book_discount_describe" name="book_discount_describe" class="input-large" placeholder="<%$arrayLaguage['discount_describe']['page_laguage_value']%>"  />
                                 <!--<div class="input-prepend input-append">
                                     <span class="add-on">半天不打折</span>
                                     <span class="add-on btn"><input type="checkbox" value="1" name="" id="half_discount" checked> </span>
                                 </div>-->
							</div>
						</div>
						<div class="control-group hide book_form_step1">
                            <div class="controls">
                                <input id="half_price" class="input-mini" value="<%$hotel_overtime%>" name="half_price" style="" aria-invalid="false" type="text">
                                <%$arrayLaguage['before_half_of_the_rate']['page_laguage_value']%>
                                <code>[<%$arrayLaguage['hotel_checkin']['page_laguage_value']%>: <%$hotel_checkin%> - 
                                <%$arrayLaguage['hotel_checkout']['page_laguage_value']%>: <%$hotel_checkout%>]</code>
                            </div>
							<label class="control-label"><%$arrayLaguage['checkin']['page_laguage_value']%> <i class="am-icon-calculator am-green-2BBAB0"></i> :</label>
							<div class="controls">
								<input type="text" class="input-medium" id="book_check_in" name="book_check_in" value="<%$book_check_in%>"/>
								<%$arrayLaguage['checkout']['page_laguage_value']%> :
								<input type="text" class="input-medium" id="book_check_out" name="book_check_out" value="<%$book_check_out%>"/>
                                <input id="max_date" value="" type="hidden"><input id="balance_date" value="<%$thisDay%>" type="hidden">
								<!--<%$arrayLaguage['number_of_people']['page_laguage_value']%> :
								<input type="text" class="span1" id="room_layout_max_people" name="room_layout_max_people" placeholder="<%$arrayLaguage['number_of_people']['page_laguage_value']%>"  />-->
                                <%$arrayLaguage['book_order_retention_time']['page_laguage_value']%> :
                                <input value="<%$thisDay%> 18:00" type="text" class="input-medium" id="book_order_retention_time" name="book_order_retention_time" />
                                <%$arrayLaguage['book_days_total']['page_laguage_value']%> :
                                <input value="1" type="text" class="input-mini" id="book_days_total" name="book_days_total" readonly />
                                <!--<a href="#searchRoom" id="search_room_hour_layout" class="btn btn-primary btn-mini"><i class="am-icon-hourglass-2"></i> <%$arrayLaguage['find_hour_room']['page_laguage_value']%></a>-->
							</div>
                            <!--房型-->
                            <label class="control-label"><%$arrayLaguage['room_layout']['page_laguage_value']%> :</label>
                            <div class="controls" id="controls_sell_layout">
                                 <select id="sell_layout" class="input-medium">
                                    <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                    <%section name=i loop=$arraySellLayout%>
                                        <option room_layout="<%$arraySellLayout[i].room_layout_id%>" value="<%$arraySellLayout[i].room_sell_layout_id%>">
                                            <%$arraySellLayout[i].room_sell_layout_name%>
                                        </option>
                                    <%/section%>
                                 </select> 
                                 <select id="price_system" class="input-medium">
                                    <%section name=i loop=$arrayPriceSystem%>
                                        <option sell_id="<%$arrayPriceSystem[i].room_sell_layout_id%>" layout_corp="<%$arrayPriceSystem[i].room_layout_corp_id%>" value="<%$arrayPriceSystem[i].room_layout_price_system_id%>">
                                            <%$arrayPriceSystem[i].room_layout_price_system_name%>
                                        </option>
                                    <%/section%>
                                 </select> 
                            </div>
                            <div class="controls">
                                <ul id="select_sell_layout" class="quick-actions"><a href="#searchRoom" id="search_room_layout" class="btn btn-primary btn-mini"><i class="am-icon-search"></i> <%$arrayLaguage['find_room']['page_laguage_value']%></a></ul>
                            </div>
                            <!--<label class="control-label"><%$arrayLaguage['include_service']['page_laguage_value']%> :</label>
                            <div class="controls" id="controls_service">
                                <div class="btn-group"><a class="btn edit_checkbox" data-id="-1"><i class="am-icon-check-square-o edit_btn"></i> <%$arrayLaguage['base_room_price']['page_laguage_value']%></a></div><%section name=i loop=$arrayHotelService%><div class="btn-group"><a class="btn edit_checkbox" data-id="<%$arrayHotelService[i].hotel_service_id%>" href="#view"><i class="am-icon-square-o edit_btn"></i> <%$arrayHotelService[i].hotel_service_name%></a></div><%/section%>
                                
                            </div>
                            <div class="controls">
                                <div class="btn-icon-pg">
                                <ul>
                                 <select id="service_type" class="input-medium">
                                    <option value="-1"><%$arrayLaguage['base_room_price']['page_laguage_value']%></option>
                                    <%section name=i loop=$arrayHotelService%>
                                        <option value="<%$arrayHotelService[i].hotel_service_id%>">
                                            <%$arrayHotelService[i].hotel_service_name%>
                                        </option>
                                    <%/section%>
                                 </select> 
                                 <li><i class="am-icon-check-square"></i><%$arrayLaguage['base_room_price']['page_laguage_value']%><i id="server_-1" class="am-icon-trash-o am-red-E43737 service_type_del"></i></li><a href="#searchRoom" id="search_room_layout" class="btn btn-primary btn-mini"><i class="am-icon-search"></i> <%$arrayLaguage['find_room']['page_laguage_value']%></a>
                                 </ul>
                                 </div>
                            </div>-->
                            
						</div>
						<div class="control-group hide book_form_step2" id="room_layout_table">
							<div class="controls">
							 <table class="table table-bordered data-table" id="room_layout">
							  <thead>
								<tr>
								  <th><!--<%$arrayLaguage['room_layout_name']['page_laguage_value']%><%$arrayLaguage['book']['page_laguage_value']%>--></th>
								  <th><%$arrayLaguage['room_layout_name']['page_laguage_value']%><%$arrayLaguage['book']['page_laguage_value']%>--<%$arrayLaguage['price']['page_laguage_value']%> -- <%$today%></th>
								</tr>
							  </thead>
							  <tbody id='room_layout_data'>
								<tr class="gradeX">
								  <td></td>
								  <td></td>
								</tr>
							  </tbody>
							</table>
                            <div id="room_layout_html"></div>
                            <div id="room_data"></div>
                            <div id="addBed_data"></div>
						  </div>
                          <!--<label class="control-label hide">已选房间 :</label>
                          <div class="controls hide" id="select_rooms">
                          </div>-->
						</div>
                        <div class="control-group hide book_form_step2">
							<label class="control-label"><%$arrayLaguage['need_service']['page_laguage_value']%> :</label>
							<div class="controls">
                             <select name="need_service" id="need_service" class="input-large">
                                <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                                <%section name=i loop=$arrayHotelService%>
                                    <option price="<%$arrayHotelService[i].hotel_service_price%>" title="<%$arrayHotelService[i].hotel_service_name%>" value="<%$arrayHotelService[i].hotel_service_id%>">
                                        <%$arrayHotelService[i].hotel_service_name%>  ￥<%$arrayHotelService[i].hotel_service_price%>
                                    </option>
                                <%/section%>
							 </select>
                             <div class="btn-icon-pg" id="need_service_info"></div>
                            </div>
                        </div>
                        <div class="control-group hide book_form_step2">
							<label class="control-label"><%$arrayLaguage['check_in_information']['page_laguage_value']%> <i class="am-icon-users am-yellow-E88A26"></i>:</label>
							<div class="controls book_user_info">
								<input name="user_name[]" value="" type="text" class="input-small" placeholder="<%$arrayLaguage['name']['page_laguage_value']%>" />
								<%$arrayLaguage['sex']['page_laguage_value']%> :
								<select name="user_sex[]" class="input-small">
									<option value="1"><%$arrayLaguage['male']['page_laguage_value']%></option>
									<option value="0"><%$arrayLaguage['female']['page_laguage_value']%></option>
								</select>
								<%$arrayLaguage['identity_information']['page_laguage_value']%> :
								<select name="user_id_card_type[]" class="input-small">
									<option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
									<%section name=card_type loop=$idCardType%>
									<option value="<%$idCardType[card_type]%>"><%$arrayLaguage[$idCardType[card_type]]['page_laguage_value']%></option>
									<%/section%>
								</select>
								<input type="text" name="user_id_card[]" class="input-medium" placeholder="<%$arrayLaguage['identification_number']['page_laguage_value']%>"/>
                                <%$arrayLaguage['user_comments']['page_laguage_value']%> :
                                <input type="text" name="user_comments[]" class="input-large" placeholder="<%$arrayLaguage['user_comments']['page_laguage_value']%>"/>
                                <!--<%$arrayLaguage['cash_pledge']['page_laguage_value']%> :
                                <input type="text" name="book_cash_pledge[]" class="span1" placeholder="<%$arrayLaguage['cash_pledge']['page_laguage_value']%>"/>// 押金-->
							</div>
							<div class="controls">
							<a href="#addBookUser" id="addBookUser" class="btn btn-primary btn-mini"><i class="am-icon-user-plus"></i> <%$arrayLaguage['add_number_of_people']['page_laguage_value']%></a>
							<a href="#reduceBookUser" id="reduceBookUser" class="btn btn-warning btn-mini"><i class="am-icon-user-times"></i> <%$arrayLaguage['reduce_number_of_people']['page_laguage_value']%></a>
							</div>
						</div>
                        <div class="control-group hide book_form_step2">
                            <label class="control-label"><%$arrayLaguage['user_comments']['page_laguage_value']%> :</label>
                            <div class="controls">
                             <textarea name="comments" class="span8" placeholder="<%$arrayLaguage['user_comments']['page_laguage_value']%>"></textarea>
                            </div>
                        </div>
                        <div class="control-group hide book_form_step2">
							<label class="control-label"><%$arrayLaguage['pay']['page_laguage_value']%> :</label>
							<div class="controls">
							 <select name="payment" id="payment" class="input-small">
								<option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
								<option value="1"><%$arrayLaguage['prepayment']['page_laguage_value']%></option>
								<option value="2"><%$arrayLaguage['remaining_sum']['page_laguage_value']%></option>
								<option value="3"><%$arrayLaguage['full-payout']['page_laguage_value']%></option>
							 </select>
							 <%$arrayLaguage['payment_type']['page_laguage_value']%> :
							 <select id="payment_type_father" class="input-medium">
								<option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
								<%section name=type loop=$arrayPaymentType%>
								<option father="<%$arrayPaymentType[type].payment_type_father_id%>" value="<%$arrayPaymentType[type].payment_type_id%>"><%$arrayPaymentType[type].payment_type_name%></option>
								<%/section%>
							 </select>
                             <select name="payment_type" id="payment_type" class="input-medium"></select>
							 <%$arrayLaguage['money_has_to_account']['page_laguage_value']%> :
							 <select name="is_pay" id="is_pay" class="input-small">
								<option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
								<option value="1"><%$arrayLaguage['already_collection']['page_laguage_value']%></option>
								<option value="2"><%$arrayLaguage['not_receivable']['page_laguage_value']%></option>
							 </select>
							 <%$arrayLaguage['payment_voucher']['page_laguage_value']%> :
							 <input value="" type="text" class="input-large" id="book_payment_voucher" name="book_payment_voucher" />
						  </div>
                          <div class="controls hide" id="pre_licensing">
                          预授权金额：<input value="" type="text" class="input-mini" id="book_credit_authorized_amount" name="book_credit_authorized_amount" />
                          预授权单号： <input value="" type="text" class="input-medium" id="book_credit_authorized_number" name="book_credit_authorized_number" />
                          预授权天数： <input value="" type="text" class="input-mini" id="book_credit_authorized_days" name="book_credit_authorized_days" />
                          预授权卡号：<input value="" type="text" class="input-large" id="book_credit_card_number" name="book_credit_card_number" />
                          </div>
						</div>
                        <div class="control-group hide book_form_step2">
                            <label class="control-label"><%$arrayLaguage['total_room_rate']['page_laguage_value']%> :</label>
							<div class="controls">
                                <input value="" type="text" class="input-mini" id="total_room_rate" name="total_room_rate" />
                                含（房费 :
                                <span id="room_all_price"></span>
                                加床费用 :
                                <span id="bed_all_price"></span>）
                                <%$arrayLaguage['cash_pledge']['page_laguage_value']%> :
                                <input value="" type="text" class="input-mini total_cash_pledge" id="book_total_cash_pledge" name="book_total_cash_pledge" />
                                <%$arrayLaguage['need_service_price']['page_laguage_value']%> :
                                <input value="" type="text" class="input-mini book_price" id="need_service_price" name="need_service_price" />
                                <%$arrayLaguage['service_charge']['page_laguage_value']%> :
                                <input value="0" type="text" class="input-mini book_price" id="book_service_charge" name="book_service_charge" />
						    </div>
                            <label class="control-label"><%$arrayLaguage['prepayment_price']['page_laguage_value']%> :</label>
                            <div class="controls">
                                <input value="" type="text" class="input-mini" id="prepayment" name="book_prepayment_price" />
                            </div>
							<label class="control-label"><%$arrayLaguage['total_price']['page_laguage_value']%> :</label>
							<div class="controls">
                                <input value="" type="text" class="input-mini" id="total_price" name="book_total_price" />
						    </div>
						</div>
                        <div class="form-actions pagination-centered hide book_form_step2">
                            <div class="btn-group">
                                <button type="button" class="btn btn-warning" id="computed_value" ><i class="am-icon-calculator"></i> 计算核对价格</button>
                                <button type="submit" class="btn btn-primary pagination-centered save_info"><i class="am-icon-save"></i> <%$arrayLaguage['save_next']['page_laguage_value']%></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>   
        </div>
					
	  </div>
    
    </div>
</div>
<%include file="hotel/inc/footer.tpl"%>
<%include file="hotel/inc/modal_box.tpl"%>
<%include file="hotel/inc_js/book_add_js.tpl"%>
</body>
</html>