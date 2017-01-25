<div class="widget-title">
    <ul class="nav nav-tabs">
        <li class="active" id="hotel_setting"><a data-toggle="tab" href="#tab1"><%$arrayLaguage['hotel_setting']['page_laguage_value']%></a></li>
        <li id="hotel_attribute_setting"><a data-toggle="tab" href="#tab2"><%$arrayLaguage['hotel_attribute_setting']['page_laguage_value']%></a></li>
        <li id="hotel_images_upload"><a data-toggle="tab" href="#tab3"><%$arrayLaguage['upload_images']['page_laguage_value']%></a></li>
    </ul>
</div>
<div class="widget-content tab-content nopadding">
<%if $update_success==1%>
<%include file="hotel/inc/success_alert.tpl"%>
<%/if%>
    <div id="tab1" class="tab-pane active">
        <form action="<%$hotel_update_url%>" method="post" class="form-horizontal" enctype="multipart/form-data" name="hotel_form" id="hotel_form" novalidate> 
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['belong_to_company']['page_laguage_value']%> :</label>
                <div class="controls">
                    <select id="company_id" name="company_id" class="span3">
                    	<option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                        <%section name=company loop=$arrayEmployeeCompany%>
                        <option value="<%$arrayEmployeeCompany[company].company_id%>"<%if $arrayEmployeeCompany[company].company_id==$arrayDataInfo['company_id']%> selected="selected"<%/if%>><%$arrayEmployeeCompany[company].company_name%></option>
                        <%/section%>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_name']['page_laguage_value']%> :</label>
                <div class="controls"><input type="text" class="span3" placeholder="<%$arrayLaguage['hotel_name']['page_laguage_value']%>" name="hotel_name" id="hotel_name" value="<%$arrayDataInfo['hotel_name']%>" /> </div>
            </div>
            <div class="control-group">
                <label class="control-label">酒店英文名称 :</label>
                <div class="controls"><input type="text" class="span3" placeholder="酒店英文名称" name="hotel_en_name" id="hotel_en_name" value="<%$arrayDataInfo['hotel_en_name']%>" /> </div>
            </div>
            <div class="control-group">
                <label class="control-label">酒店简称 :</label>
                <div class="controls"><input type="text" class="span3" placeholder="酒店简称" name="hotel_short_name" id="hotel_short_name" value="<%$arrayDataInfo['hotel_short_name']%>" /> </div>
            </div>
            <div class="control-group">
                <label class="control-label">酒店官网 :</label>
                <div class="controls"><input type="text" class="span3" placeholder="酒店官网" name="hotel_web" id="hotel_web" value="<%$arrayDataInfo['hotel_web']%>" /> </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_mobile']['page_laguage_value']%> :</label>
                <div class="controls">
                    <input type="text" class="span3" placeholder="<%$arrayLaguage['hotel_mobile']['page_laguage_value']%>" name="hotel_mobile" id="hotel_mobile" value="<%$arrayDataInfo['hotel_mobile']%>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">酒店总机 :</label>
                <div class="controls">
                    <input type="text" class="span3" placeholder="<%$arrayLaguage['hotel_phone']['page_laguage_value']%>" name="hotel_phone" value="<%$arrayDataInfo['hotel_phone']%>" /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_fax']['page_laguage_value']%> :</label>
                <div class="controls">
                    <input type="text" class="span3" placeholder="<%$arrayLaguage['hotel_fax']['page_laguage_value']%>" name="hotel_fax" value="<%$arrayDataInfo['hotel_fax']%>" /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_email']['page_laguage_value']%> :</label>
                <div class="controls">
                    <input type="text" class="span3" placeholder="<%$arrayLaguage['hotel_email']['page_laguage_value']%>" name="hotel_email" value="<%$arrayDataInfo['hotel_email']%>" /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_location']['page_laguage_value']%> :</label>
                <div class="controls ">
                    <select id="location_province" name="hotel_province" class="span2">
                        <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                    </select>
                    <select id="location_city" name="hotel_city" class="span2">
                        <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                    </select>
                    <select id="location_town" name="hotel_town" class="span2">
                        <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_address']['page_laguage_value']%> :</label>
                <div class="controls">
                    <input type="text" id="address" name="hotel_address" class="span6" placeholder="<%if $arrayDataInfo['hotel_address']==''%><%$arrayLaguage['hotel_address']['page_laguage_value']%><%else%><%$arrayDataInfo['hotel_address']%><%/if%>" value="<%$arrayDataInfo['hotel_address']%>"  /> 
                    <!--<button class="btn btn-primary" type="button" onclick="theLocation()"><%$arrayLaguage['search_map']['page_laguage_value']%></button>-->
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_map']['page_laguage_value']%> :</label>
                <div class="controls">
                    <div id="searchResultPanel" class="span6" style="border:1px solid #C0C0C0;height:auto; display:none;"></div>
                    <div id="allmap" class="span6"></div>
                    </div>
                    <input type="hidden" name="hotel_longitude" id="hotel_longitude" value="<%$arrayDataInfo['hotel_longitude']%>" />
                    <input type="hidden" name="hotel_latitude" id="hotel_latitude" value="<%$arrayDataInfo['hotel_latitude']%>" />
            </div>
            <div class="control-group">
                <label class="control-label">最近路口或标志建筑 :</label>
                <div class="controls">
                    <input type="text" id="hotel_nearest_intersection" name="hotel_nearest_intersection" class="span6" placeholder="最近路口或标志建筑 " value="<%$arrayDataInfo['hotel_nearest_intersection']%>"  /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['brand']['page_laguage_value']%> :</label>
                <div class="controls">
                    <input type="text" id="hotel_brand" name="hotel_brand" class="span3" placeholder="<%if $arrayDataInfo['hotel_brand']==''%><%$arrayLaguage['brand']['page_laguage_value']%><%else%><%$arrayDataInfo['hotel_brand']%><%/if%>" value="<%$arrayDataInfo['hotel_brand']%>"  /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_type']['page_laguage_value']%> :</label>
                <div class="controls">
                    <select id="hotel_type" name="hotel_type" class="span3">
                        <option value="hotel"<%if $arrayDataInfo['hotel_type']=='hotel'%> selected="selected"<%/if%>><%$arrayLaguage['hotel']['page_laguage_value']%></option>
                        <option value="boutique_hotel"<%if $arrayDataInfo['hotel_type']=='boutique_hotel'%> selected="selected"<%/if%>>精品酒店</option>
                        <option value="design_hotel "<%if $arrayDataInfo['hotel_type']=='design_hotel'%> selected="selected"<%/if%>>设计酒店</option>
                        <option value="B&B"<%if $arrayDataInfo['hotel_type']=='B&B'%> selected="selected"<%/if%>>民宿</option>
                        <option value="holiday_village"<%if $arrayDataInfo['hotel_type']=='holiday_village'%> selected="selected"<%/if%>>度假村</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_star']['page_laguage_value']%> :</label>
                <div class="controls">
                    <select id="hotel_star" name="hotel_star" class="span2">
                        <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                        <option value="1"<%if $arrayDataInfo['hotel_star'] == 1%> selected="selected"<%/if%>>★</option>
                        <option value="2"<%if $arrayDataInfo['hotel_star'] == 2%> selected="selected"<%/if%>>★★</option>
                        <option value="3"<%if $arrayDataInfo['hotel_star'] == 3%> selected="selected"<%/if%>>★★★</option>
                        <option value="4"<%if $arrayDataInfo['hotel_star'] == 4%> selected="selected"<%/if%>>★★★★</option>
                        <option value="5"<%if $arrayDataInfo['hotel_star'] == 5%> selected="selected"<%/if%>>★★★★★</option>
                        <option value="6"<%if $arrayDataInfo['hotel_star'] == 6%> selected="selected"<%/if%>>★★★★★★</option>
                        <option value="7"<%if $arrayDataInfo['hotel_star'] == 7%> selected="selected"<%/if%>>★★★★★★★</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">开业年月 :</label>
                <div class="controls">
                    <input type="text" id="hotel_opening_date" name="hotel_opening_date" class="span1" placeholder="开业年月" value="<%$arrayDataInfo['hotel_opening_date']%>"  /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">最新装修年月 :</label>
                <div class="controls">
                    <input type="text" id="hotel_latest_decoration_date" name="hotel_latest_decoration_date" class="span1" placeholder="最新装修年月" value="<%$arrayDataInfo['hotel_latest_decoration_date']%>"  /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">客房总数 :</label>
                <div class="controls">
                    <input type="text" id="hotel_number_of_rooms" name="hotel_number_of_rooms" class="span1" placeholder="客房总数" value="<%$arrayDataInfo['hotel_number_of_rooms']%>"  /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_wifi']['page_laguage_value']%> :</label>
                <div class="controls">
                    <select id="hotel_wifi" name="hotel_wifi" class="span3">
                        <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                        <option value="1"<%if $arrayDataInfo['hotel_wifi'] == 1%> selected="selected"<%/if%>><%$arrayLaguage['have']['page_laguage_value']%></option>
                        <option value="0"<%if $arrayDataInfo['hotel_wifi'] == 0%> selected="selected"<%/if%>><%$arrayLaguage['not_have']['page_laguage_value']%></option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_checkin']['page_laguage_value']%> :</label>
                <div class="controls">
                    <input type="text" id="hotel_checkin" name="hotel_checkin" class="span1" placeholder="<%if $arrayDataInfo['hotel_checkin']==''%><%$arrayLaguage['hotel_checkin']['page_laguage_value']%><%else%><%$arrayDataInfo['hotel_checkin']%><%/if%>" value="<%$arrayDataInfo['hotel_checkin']%>"  /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_checkout']['page_laguage_value']%> :</label>
                <div class="controls">
                    <input type="text" id="hotel_checkout" name="hotel_checkout" class="span1" placeholder="<%if $arrayDataInfo['hotel_checkout']==''%><%$arrayLaguage['hotel_checkout']['page_laguage_value']%><%else%><%$arrayDataInfo['hotel_checkout']%><%/if%>" value="<%$arrayDataInfo['hotel_checkout']%>"  /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_booking_notes']['page_laguage_value']%> :</label>
                <div class="controls">
                    <textarea class="span6" style="height:300px;" id="hotel_booking_notes" name="hotel_booking_notes" placeholder="<%if $arrayDataInfo['hotel_booking_notes']==''%><%$arrayLaguage['hotel_booking_notes']['page_laguage_value']%><%else%><%$arrayDataInfo['hotel_booking_notes']%><%/if%>" value="<%$arrayDataInfo['hotel_booking_notes']%>"  ><%$arrayDataInfo['hotel_booking_notes']%></textarea> 
                </div>
            </div>
            <div class="widget-title">
                    <span class="icon">
                        <i class="icon-align-justify"></i>									
                    </span>
                    <h5>联系方式</h5>
            </div>
            <div class="control-group">
                <label class="control-label">负责人/店长/总经理 :</label>
                <div class="controls">
                    姓名: <input type="text" id="hotel_general_manager" name="hotel_general_manager" class="span1" placeholder="姓名" value="<%$arrayDataInfo['hotel_general_manager']%>"  /> 
                    职务: <input type="text" id="hotel_general_manager_title" name="hotel_general_manager_title" class="span1" placeholder="职务" value="<%$arrayDataInfo['hotel_general_manager_title']%>"  /> 
                    手机: <input type="text" id="hotel_general_manager_mobile" name="hotel_general_manager_mobile" class="span1" placeholder="手机" value="<%$arrayDataInfo['hotel_general_manager_mobile']%>"  /> 
                    电话: <input type="text" id="hotel_general_manager_phone" name="hotel_general_manager_phone" class="span1" placeholder="电话" value="<%$arrayDataInfo['hotel_general_manager_phone']%>"  /> 
                    邮箱: <input type="text" id="hotel_general_manager_email" name="hotel_general_manager_email" class="span1" placeholder="邮箱" value="<%$arrayDataInfo['hotel_general_manager_email']%>"  /> 
                    传真: <input type="text" id="hotel_general_manager_fax" name="hotel_general_manager_fax" class="span1" placeholder="传真" value="<%$arrayDataInfo['hotel_general_manager_fax']%>"  /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">销售部联系人 :</label>
                <div class="controls">
                    姓名: <input type="text" id="hotel_sales_contact" name="hotel_sales_contact" class="span1" placeholder="姓名" value="<%$arrayDataInfo['hotel_sales_contact']%>"  /> 
                    职务: <input type="text" id="hotel_sales_contact_title" name="hotel_sales_contact_title" class="span1" placeholder="职务" value="<%$arrayDataInfo['hotel_sales_contact_title']%>"  /> 
                    手机: <input type="text" id="hotel_sales_contact_mobile" name="hotel_sales_contact_mobile" class="span1" placeholder="手机" value="<%$arrayDataInfo['hotel_sales_contact_mobile']%>"  /> 
                    电话: <input type="text" id="hotel_sales_contact_phone" name="hotel_sales_contact_phone" class="span1" placeholder="电话" value="<%$arrayDataInfo['hotel_sales_contact_phone']%>"  /> 
                    邮箱: <input type="text" id="hotel_sales_contact_email" name="hotel_sales_contact_email" class="span1" placeholder="邮箱" value="<%$arrayDataInfo['hotel_sales_contact_email']%>"  /> 
                    传真: <input type="text" id="hotel_sales_contact_fax" name="hotel_sales_contact_fax" class="span1" placeholder="传真" value="<%$arrayDataInfo['hotel_sales_contact_fax']%>"  /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">预订部联系人 :</label>
                <div class="controls">
                    姓名: <input type="text" id="hotel_reservation_contact" name="hotel_reservation_contact" class="span1" placeholder="姓名" value="<%$arrayDataInfo['hotel_reservation_contact']%>"  /> 
                    职务: <input type="text" id="hotel_reservation_contact_title" name="hotel_reservation_contact_title" class="span1" placeholder="职务" value="<%$arrayDataInfo['hotel_reservation_contact_title']%>"  /> 
                    手机: <input type="text" id="hotel_reservation_contact_mobile" name="hotel_reservation_contact_mobile" class="span1" placeholder="手机" value="<%$arrayDataInfo['hotel_reservation_contact_mobile']%>"  /> 
                    电话: <input type="text" id="hotel_reservation_contact_phone" name="hotel_reservation_contact_phone" class="span1" placeholder="电话" value="<%$arrayDataInfo['hotel_reservation_contact_phone']%>"  /> 
                    邮箱: <input type="text" id="hotel_reservation_contact_email" name="hotel_reservation_contact_email" class="span1" placeholder="邮箱" value="<%$arrayDataInfo['hotel_reservation_contact_email']%>"  /> 
                    传真: <input type="text" id="hotel_reservation_contact_fax" name="hotel_reservation_contact_fax" class="span1" placeholder="传真" value="<%$arrayDataInfo['hotel_reservation_contact_fax']%>"  /> 
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">财务部联系人 :</label>
                <div class="controls">
                    姓名: <input type="text" id="hotel_finance_contact" name="hotel_finance_contact" class="span1" placeholder="姓名" value="<%$arrayDataInfo['hotel_finance_contact']%>"  /> 
                    职务: <input type="text" id="hotel_finance_contact_title" name="hotel_finance_contact_title" class="span1" placeholder="职务" value="<%$arrayDataInfo['hotel_finance_contact_title']%>"  /> 
                    手机: <input type="text" id="hotel_finance_contact_mobile" name="hotel_finance_contact_mobile" class="span1" placeholder="手机" value="<%$arrayDataInfo['hotel_finance_contact_mobile']%>"  /> 
                    电话: <input type="text" id="hotel_finance_contact_phone" name="hotel_finance_contact_phone" class="span1" placeholder="电话" value="<%$arrayDataInfo['hotel_finance_contact_phone']%>"  /> 
                    邮箱: <input type="text" id="hotel_finance_contact_email" name="hotel_finance_contact_email" class="span1" placeholder="邮箱" value="<%$arrayDataInfo['hotel_finance_contact_email']%>"  /> 
                    传真: <input type="text" id="hotel_finance_contact_fax" name="hotel_finance_contact_fax" class="span1" placeholder="传真" value="<%$arrayDataInfo['hotel_finance_contact_fax']%>"  /> 
                </div>
            </div>
            <div class="widget-title">
                    <span class="icon">
                        <i class="icon-align-justify"></i>									
                    </span>
                    <h5>全方位酒店介绍</h5>
            </div>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['hotel_introduce']['page_laguage_value']%>: </label>
                <div class="controls">
                    <textarea class="span6" style="height:300px;"  placeholder="<%$arrayLaguage['hotel_introduce']['page_laguage_value']%>" name="hotel_introduce" value="<%$arrayDataInfo['hotel_introduce']%>" ><%$arrayDataInfo['hotel_introduce']%></textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">设计风格: </label>
                <div class="controls">
                    <textarea class="span6" style="height:300px;"  placeholder="设计风格" name="hotel_design_style" value="<%$arrayDataInfo['hotel_design_style']%>" ><%$arrayDataInfo['hotel_design_style']%></textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">建筑特色: </label>
                <div class="controls">
                    <textarea class="span6" style="height:300px;"  placeholder="建筑特色" name="hotel_architectural_feature" value="<%$arrayDataInfo['hotel_architectural_feature']%>" ><%$arrayDataInfo['hotel_architectural_feature']%></textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">酒店特色: </label>
                <div class="controls">
                    <textarea class="span6" style="height:300px;"  placeholder="酒店特色" name="hotel_features" value="<%$arrayDataInfo['hotel_features']%>" ><%$arrayDataInfo['hotel_features']%></textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">早餐介绍: </label>
                <div class="controls">
                    <textarea class="span6" style="height:300px;"  placeholder="早餐介绍" name="hotel_breakfast_introduction" value="<%$arrayDataInfo['hotel_breakfast_introduction']%>" ><%$arrayDataInfo['hotel_breakfast_introduction']%></textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">餐厅特色: </label>
                <div class="controls">
                    <textarea class="span6" style="height:300px;"  placeholder="餐厅特色" name="hotel_restaurant_features" value="<%$arrayDataInfo['hotel_restaurant_features']%>" ><%$arrayDataInfo['hotel_restaurant_features']%></textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">酒店交通: </label>
                <div class="controls">
                    <textarea class="span6" style="height:300px;"  placeholder="酒店交通" name="hotel_traffic" value="<%$arrayDataInfo['hotel_traffic']%>" ><%$arrayDataInfo['hotel_traffic']%></textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">周边特色: </label>
                <div class="controls">
                    <textarea class="span6" style="height:300px;"  placeholder="周边特色" name="hotel_peripheral_features" value="<%$arrayDataInfo['hotel_peripheral_features']%>" ><%$arrayDataInfo['hotel_peripheral_features']%></textarea>
                </div>
            </div>
            
            
            
            
            <div class="form-actions pagination-centered btn-icon-pg">
            	<!--<ul><li class="btn btn-primary" id="hotel_attribute_setting_btn">  </li></ul>-->
                <button type="submit" id="save_hotel_info" class="btn btn-primary pagination-centered"><%$arrayLaguage['hotel_attribute_setting_next']['page_laguage_value']%></button>
            </div>
         </form>
    </div>
    <div id="tab2" class="tab-pane">
       	  <form action="" method="post" class="form-horizontal" enctype="multipart/form-data" name="hotel_attr_form" id="hotel_attr_form" novalidate> 
       		<%section name=attr loop=$arrayAttribute%>
                <div class="control-group">
                    <label class="control-label"><%$arrayAttribute[attr].hotel_attribute_name%> :</label>
                    <div class="controls">
                    <%section name=attr_childen loop=$arrayAttribute[attr].children%>
                    <label class="control-label"><%$arrayAttribute[attr].children[attr_childen].hotel_attribute_name%> :</label>
                    <div class="controls">
                        <input type="text" class="span2" value=""  />
                        <a href="#add" class="btn btn-primary btn-mini addAttr"><i class="icon-plus-sign"></i> <%$arrayLaguage['add_attribute_value']['page_laguage_value']%></a>
                    </div>
                    <%/section%>
                    </div>
                </div>
            <%/section%>
            <div class="form-actions pagination-centered btn-icon-pg">
                <button type="submit" id="save_hotel_attr_val_info" class="btn btn-primary pagination-centered"><%$arrayLaguage['save_next']['page_laguage_value']%></button>
            </div>
           </form>
    </div>
    <div id="tab3" class="tab-pane">
        <div class="widget-content">
            <ul class="thumbnails">
                <%section name=images loop=$arrayDataImages%>
                <li class="span2">
                    <a class="thumbnail lightbox_trigger" href="<%$__IMGWEB%><%$arrayDataImages[images].room_layout_images_path%>">
                        <img id="room_layout_<%$arrayDataImages[images].hotel_images_id%>" src="<%$__IMGWEB%><%$arrayDataImages[images].hotel_images_path%>" alt="" >
                    </a>
                    <div class="actions">
                        <a title="" href="#"><i class="icon-pencil icon-white"></i></a>
                        <a title="" href="#"><i class="icon-remove icon-white"></i></a>
                    </div>
                </li>
                <%/section%>
            </ul>
        </div>
        <form method="post" class="form-horizontal" enctype="multipart/form-data" novalidate>
            <div class="control-group">
                <label class="control-label"><%$arrayLaguage['upload_holte_images']['page_laguage_value']%> :</label>
                <div class="controls">
                    <p><input type="text" id="upload_images_url" value="" /> <input type="button" id="upload_images" value="选择图片" /></p>
                </div>
            </div>
        </form>
    </div>
</div>
