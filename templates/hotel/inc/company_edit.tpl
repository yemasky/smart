<div class="widget-content nopadding">
<%if $update_success==1%>
<%include file="hotel/inc/success_alert.tpl"%>
<%/if%>
    <form action="<%$company_update_url%>" method="post" class="form-horizontal" enctype="multipart/form-data" name="company_form" id="company_form" novalidate> 
        <div class="control-group">
            <label class="control-label"><%$arrayLaguage['company_name']['page_laguage_value']%> :</label>
            <div class="controls"><input type="text" class="span3" placeholder="<%$arrayLaguage['company_name']['page_laguage_value']%>" name="company_name" id="company_name" value="<%$arrayCompany['company_name']%>" /> </div>
        </div>
        <div class="control-group">
            <label class="control-label">联系电话 :</label>
            <div class="controls">
                <input type="text" class="input-medium" placeholder="<%$arrayLaguage['company_phone']['page_laguage_value']%>" name="company_phone" id="company_phone" value="<%$arrayCompany['company_phone']%>" />[010-88888888]
                
                <input type="text" class="input-medium" placeholder="<%$arrayLaguage['company_email']['page_laguage_value']%>" name="company_email" value="<%$arrayCompany['company_email']%>" /> 
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">联系人手机 :</label>
            <div class="controls">
                 <input type="text" class="input-medium" placeholder="<%$arrayLaguage['company_mobile']['page_laguage_value']%>" name="company_mobile" id="company_mobile" value="<%$arrayCompany['company_mobile']%>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">财务负责人座机 :</label>
            <div class="controls">
                <input type="text" class="input-medium" placeholder="财务负责人座机" name="company_finance_phone" id="company_finance_phone" value="<%$arrayCompany['company_finance_phone']%>" />[010-88888888]
                
                <input type="text" class="input-medium" placeholder="联系email" name="company_finance_email" id="company_finance_email" value="<%$arrayCompany['company_finance_email']%>" /> 
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">销售负责人座机 :</label>
            <div class="controls">
                <input type="text" class="input-medium" placeholder="销售负责人座机" name="company_sales_phone" id="company_sales_phone" value="<%$arrayCompany['company_sales_phone']%>" />[010-88888888]
                
                <input type="text" class="input-medium" placeholder="联系email" name="company_sales_email" id="company_sales_email" value="" /> 
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">信息负责人座机 :</label>
            <div class="controls">
                <input type="text" class="input-medium" placeholder="信息负责人座机" name="company_information_phone" id="company_information_phone" value="<%$arrayCompany['company_information_phone']%>" />[010-88888888]
                
                <input type="text" class="input-medium" placeholder="联系email" name="company_information_email" id="company_information_email" value="<%$arrayCompany['company_information_email']%>" /> 
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><%$arrayLaguage['company_fax']['page_laguage_value']%> :</label>
            <div class="controls">
                <input type="text" class="span3" placeholder="<%$arrayLaguage['company_fax']['page_laguage_value']%>" name="company_fax" value="<%$arrayCompany['company_fax']%>" /> 
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">公司网址 :</label>
            <div class="controls">
                <input type="text" class="span3" placeholder="公司网址" name="company_web" id="company_web" value="<%$arrayCompany['company_web']%>" /> 
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><%$arrayLaguage['company_location']['page_laguage_value']%> :</label>
            <div class="controls ">
                <select id="location_province" name="company_province" class="input-medium">
                    <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                </select>
                <select id="location_city" name="company_city" class="span1">
                    <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                </select>
                <select id="location_town" name="company_town" class="span1">
                    <option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><%$arrayLaguage['company_address']['page_laguage_value']%> :</label>
            <div class="controls">
                <input type="text" id="company_address" name="company_address" class="span6" placeholder="<%if $arrayCompany['company_address']==''%><%$arrayLaguage['company_address']['page_laguage_value']%><%else%><%$arrayCompany['company_address']%><%/if%>" value="<%$arrayCompany['company_address']%>"  /> 
                <!--<button class="btn btn-primary" type="button" onclick="theLocation()"><%$arrayLaguage['search_map']['page_laguage_value']%></button>-->
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><%$arrayLaguage['company_map']['page_laguage_value']%> :</label>
            <div class="controls">
                <div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
                <div id="allmap" class="span6"></div>
                </div>
                <input type="hidden" name="company_longitude" id="company_longitude" value="<%$arrayCompany['company_longitude']%>" />
                <input type="hidden" name="company_latitude" id="company_latitude" value="<%$arrayCompany['company_latitude']%>" />
        </div>
        <div class="control-group">
            <label class="control-label"><%$arrayLaguage['company_introduction']['page_laguage_value']%></label>
            <div class="controls">
                <textarea class="span6" style="height:300px;"  placeholder="<%$arrayLaguage['company_introduction']['page_laguage_value']%>" name="company_introduction" value="<%$arrayCompany['company_introduction']%>" ><%$arrayCompany['company_introduction']%></textarea>
            </div>
        </div>
       

        
        <div class="form-actions pagination-centered">
            <button type="submit" id="save_company_info" class="btn btn-success pagination-centered">Save</button>
        </div>
    </form>
</div>