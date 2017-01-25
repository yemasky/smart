<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<%include file="hotel/inc_js/editor_upload_images.tpl"%>
<script src="<%$__RESOURCE%>js/jquery.validate.js"></script>
<style type="text/css">
.quick-actions li {margin: 2px !important; padding:0 3px 0 3px; width:245px;}
.quick-actions{text-align:left;}
#add_room_layout_attr_form .radio input,#add_room_layout_form .radio input{margin-left:0px !important;}
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
             <span class="icon"><i class="icon-th"></i></span> 
            <h5><%$arrayLaguage['add_rooms_layout']['page_laguage_value']%></h5>
            <div class="buttons" id="btn_room_layout">
                <a class="btn btn-primary btn-mini" href="<%$back_lis_url%>" id="add_room_layout"><i class="am-icon-arrow-circle-left"></i> 
                &#12288;<%$arrayLaguage['back_list']['page_laguage_value']%></a>
            </div>
          </div>
          <div class="widget-title">
            <ul class="nav nav-tabs">
                <li class="active" id="rooms_layout_setting"><a data-toggle="tab" href="#tab1"><%$arrayLaguage['rooms_layout_setting']['page_laguage_value']%></a></li>
                <li id="room_layout_attr"><a data-toggle="tab" href="#tab2"><%$arrayLaguage['room_layout_attr']['page_laguage_value']%></a></li>
                <li id="set_room"><a data-toggle="tab" href="#tab3"><%$arrayLaguage['set_room']['page_laguage_value']%></a></li>
                <li id="room_layout_images"><a data-toggle="tab" href="#tab4"><%$arrayLaguage['upload_images']['page_laguage_value']%></a></li>
                <!--<li id="room_layout_price_setting"><a href="#tab3"><%$arrayLaguage['room_layout_price_setting']['page_laguage_value']%></a></li>-->
            </ul>
          </div>
          <div class="widget-content tab-content nopadding">
           <div id="tab1" class="tab-pane active">
            <form action="<%$add_room_layout_url%>" method="post" class="form-horizontal" enctype="multipart/form-data" name="add_room_layout_form" id="add_room_layout_form" novalidate> 
                <div class="control-group">
                    <label class="control-label"><%$arrayLaguage['room_layout_name']['page_laguage_value']%> :</label>
                    <div class="controls"><input type="text" class="span3" placeholder="<%$arrayLaguage['room_layout_name']['page_laguage_value']%>" name="room_layout_name" id="room_layout_name" value="<%$arrayDataInfo['room_layout_name']%>" /> </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><%$arrayLaguage['room_layout_type']['page_laguage_value']%> :</label>
                    <div class="controls">
                        <select name="room_layout_type_id" id="room_layout_type_id" class="span1">
                        	<option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                            <%foreach key=room_layout_type_id item=arrayLayoutType from=$arrayRoomLayoutType%>
                            	<option value="<%$room_layout_type_id%>"<%if $room_layout_type_id==$arrayDataInfo['room_layout_type_id']%> selected<%/if%>><%$arrayLayoutType.room_layout_type_name%></option>
                            <%/foreach%>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">房型激活 :</label>
                    <div class="controls">
                    <label>
                    	<div class="radio" id="room_layout_valid-1"><span><input value="1" name="room_layout_valid" type="radio"<%if $arrayDataInfo['room_layout_valid']=='1'%> checked<%/if%>></span>激活</div> 
                    
                    	<div class="radio" id="room_layout_valid-0"><span><input value="0" name="room_layout_valid" type="radio"<%if $arrayDataInfo['room_layout_valid']=='0'%> checked<%/if%>></span>休眠</div> 
                    </label>
                    </div>
                </div>
               <!-- <div class="control-group">
                    <label class="control-label"><%$arrayLaguage['area']['page_laguage_value']%> :</label>
                    <div class="controls"><input type="text" class="span1" placeholder="<%$arrayLaguage['area']['page_laguage_value']%>" name="room_layout_area" id="room_layout_area" value="<%$arrayDataInfo['room_layout_area']%>" /> </div>
                </div>-->
                <!--<div class="control-group">
                    <label class="control-label">容纳人数 :</label>
                    <div class="controls"><input type="text" class="span1" placeholder="<%$arrayLaguage['room_layout_max_people']['page_laguage_value']%>" name="room_layout_max_people" id="room_layout_max_people" value="<%$arrayDataInfo['room_layout_max_people']%>" /> </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><%$arrayLaguage['room_layout_max_children']['page_laguage_value']%> :</label>
                    <div class="controls"><input type="text" class="span1" placeholder="<%$arrayLaguage['room_layout_max_children']['page_laguage_value']%>" name="room_layout_max_children" id="room_layout_max_children" value="<%$arrayDataInfo['room_layout_max_children']%>" /> </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><%$arrayLaguage['room_layout_extra_bed']['page_laguage_value']%> :</label>
                    <div class="controls"><input type="text" class="span1" placeholder="<%$arrayLaguage['room_layout_extra_bed']['page_laguage_value']%>" name="room_layout_extra_bed" id="room_layout_extra_bed" value="<%$arrayDataInfo['room_layout_extra_bed']%>" /> (0表示不可加床，阿拉伯数字表示加床数量)</div>
                </div>-->
                <div class="control-group">
                    <label class="control-label">床型 :</label>
                    <div class="controls">
                    <span class="input-prepend input-append text-center">
                        <span class="add-on">数量</span>
                        <select class="input-mini valid" name="room_bed_type_num" id="room_bed_type_num" aria-invalid="false">
                        <%section name=i loop=10%>
                         <option value="<%$smarty.section.i.index+1%>"<%if ($smarty.section.i.index+1)==$arrayDataInfo['room_bed_type_num']%>  selected<%/if%>><%$smarty.section.i.index+1%></option>
                        <%/section%>
                        </select>
                        <span class="add-on">标准床型</span>
                        <span class="add-on radio">
                        <input name="room_bed_type" id="room_bed_type1" value="standard" class="bed" type="radio"<%if 'standard'==$arrayDataInfo['room_bed_type']%> checked<%/if%>> 
                        </span>
                        <span class="add-on">榻榻米</span>
                        <span class="add-on radio">
                        <input name="room_bed_type" id="room_bed_type2" value="tatami" class="special_bed" type="radio"<%if 'tatami'==$arrayDataInfo['room_bed_type']%> checked<%/if%>> 
                        </span>
                        <span class="add-on">圆床</span>
                        <span class="add-on radio">
                        <input name="room_bed_type" id="room_bed_type3" value="round_bed" class="special_bed" type="radio"<%if 'round_bed'==$arrayDataInfo['room_bed_type']%> checked<%/if%>> 
                        </span>
                        <span class="add-on">非标准床</span>
                        <span class="add-on radio">
                        <input name="room_bed_type" id="room_bed_type4" value="non_standard" class="special_bed" type="radio"<%if 'non_standard'==$arrayDataInfo['room_bed_type']%> checked<%/if%>> 
                        </span>
                    </span>
                    </div>
                    <div class="controls hide" id="bed_extra_div">
                        <span class="input-prepend input-append text-center" id="bed_extra" data='<%$arrayDataInfo['room_bed_type_wide']%>'></span>
                    </div>
                </div>
                
                <!--<div class="control-group">
                    <label class="control-label"><%$arrayLaguage['orientations']['page_laguage_value']%> :</label>
                    <div class="controls">
                    	<select name="room_layout_orientations" id="room_layout_orientations" class="span1">
                        	<option value=""><%$arrayLaguage['please_select']['page_laguage_value']%></option>
                            <%section name=direction loop=$orientations%>
                            	<option value="<%$orientations[direction]%>"<%if $orientations[direction]==$arrayDataInfo['room_layout_orientations']%> selected<%/if%>><%$arrayLaguage[$orientations[direction]]['page_laguage_value']%></option>
                            <%/section%>
                        </select>
                    </div>
                </div>-->
                <div class="form-actions pagination-centered btn-icon-pg">
            	<!--<ul><li class="btn btn-primary" id="hotel_attribute_setting_btn">  </li></ul>-->
                    <button type="submit" id="save_info" class="btn btn-primary pagination-centered save_info"><%$arrayLaguage['next_rooms_attribute_setting']['page_laguage_value']%></button>
                </div>
            </form>
           </div>
    	   <div id="tab2" class="tab-pane">
              <form action="<%$add_room_layout_attr_url%>" method="post" class="form-horizontal" enctype="multipart/form-data" name="add_room_layout_attr_form" id="add_room_layout_attr_form" novalidate> 
           		<%section name=attr loop=$arrayAttribute%>
                    <div class="control-group">
                        <label class="control-label"><%$arrayAttribute[attr].room_layout_attribute_name%> :</label>
                        <div class="controls">
                        	<%section name=attr_children loop=$arrayAttribute[attr].children%>
                        	 <label class="control-label"><%$arrayAttribute[attr].children[attr_children].room_layout_attribute_name%> :</label>
                             <div class="controls">
                                <%if $arrayAttribute[attr].children[attr_children].room_layout_attribute_is_appoint=='0'%>
                                    <%section name=attrValue loop=$arrayAttribute[attr].children[attr_children].values%>
                                    <input type="text" name="<%$arrayAttribute[attr].room_layout_attribute_id%>[<%$arrayAttribute[attr].children[attr_children].room_layout_attribute_id%>][]" class="span2" 
                                        value="<%$arrayAttribute[attr].children[attr_children].values[attrValue].room_layout_attribute_value%>">
                                    <%/section%>
                                    <input type="text" name="<%$arrayAttribute[attr].room_layout_attribute_id%>[<%$arrayAttribute[attr].children[attr_children].room_layout_attribute_id%>][]" class="span2" value=""> 
                                    <a href="#addAttr" class="btn btn-primary btn-mini addAttr"><i class="icon-plus-sign"></i> <%$arrayLaguage['add_attribute_value']['page_laguage_value']%></a>
                                <%else%>
                                <%if $arrayAttribute[attr].children[attr_children].room_layout_attribute_value_type=='radio'%>
                                    <span class="input-prepend input-append text-center">
                                    <%section name=attrValue loop=$arrayAttribute[attr].children[attr_children].values%>
                                    <span class="add-on"><%$arrayAttribute[attr].children[attr_children].values[attrValue].name%></span>
                                    <span class="add-on radio">
                                    <input type="radio" <%if $arrayAttribute[attr].children[attr_children].values[attrValue].check==1%>checked<%/if%>
                                    name="<%$arrayAttribute[attr].room_layout_attribute_id%>[<%$arrayAttribute[attr].children[attr_children].room_layout_attribute_id%>][]" 
                                    value="<%$arrayAttribute[attr].children[attr_children].values[attrValue].name%>"
                                    > 
                                    </span>
                                    <%/section%>
                                    </span>
                                <%/if%>
                                <%/if%>
                             </div>
                        	<%/section%>
                        	<!--<label class="control-label"><span><a href="#add" class="btn btn-primary btn-mini"><i class="icon-plus-sign"></i> <%$arrayLaguage['add_customize_attr']['page_laguage_value']%></a></span></label>-->
                            
                        </div>
                    </div>
                <%/section%>
                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controls">
                        <label class="control-label"><span><a href="<%$room_attribute_url%>" target="_blank" class="btn btn-primary btn-link btn-mini"><i class="icon-plus-sign"></i> <%$arrayLaguage['add_customize_attr']['page_laguage_value']%></a></span></label>
                        
                    </div>
                </div>
                <div class="form-actions pagination-centered btn-icon-pg">
                    <button type="submit" class="btn btn-primary pagination-centered save_info"><%$arrayLaguage['save_next']['page_laguage_value']%></button>
                </div>
              </form>
           </div>
           <div id="tab3" class="tab-pane">
               <div class="widget-content">
                <div class="hide fade in alert alert-success alert-block">  
                  <a class="close" data-dismiss="alert" href="#close"> </a>
                  <h4 class="alert-heading"><%$arrayLaguage['excute_update_success']['page_laguage_value']%></h4>
                </div>
                <ul class="quick-actions" id="rooms"><%section name=room loop=$arrayRoom%><%if $view=='1' && $arrayRoom[room].checked=='0'%><%else%><li> 
                    <a href="#select_room"> <!--<i class="icon-home"></i>--> <span class="am-icon-home am-icon-sm"></span> 
                    <span id="<%$arrayRoom[room].room_id%>" data-id="<%$arrayRoom[room].room_id%>" value="<%$arrayRoom[room].room_id%>" class="<%if $arrayRoom[room].checked!='0'%>am-icon-check-square<%else%>am-icon-square-o<%/if%> selectRoom am-blue-2F93FF" check="<%$arrayRoom[room].checked%>"></span>
                    <%$arrayRoom[room].room_name%>[<%$arrayRoom[room].room_number%>]
                    </a>
                    <!--<%$arrayLaguage['orientations']['page_laguage_value']%><i class="am-icon-location-arrow"></i> <%$arrayLaguage[$arrayRoom[room].room_orientations]['page_laguage_value']%>
                    <%$arrayLaguage['room_area']['page_laguage_value']%>:<%$arrayRoom[room].room_area%>-->
                    <table>
                    <tr>
                        <td align="right"><%$arrayLaguage['room_layout_max_people']['page_laguage_value']%> :</td>
                        <td align="left"><input type="text" class="input-mini" id="max_people_<%$arrayRoom[room].room_id%>" data-id="<%$arrayRoom[room].room_id%>" value="<%$arrayRoom[room].room_layout_room_max_people%>"></td>
                    </tr>
                    <tr>
                        <td align="right"><%$arrayLaguage['room_layout_max_children']['page_laguage_value']%> :</td>
                        <td align="left"><input type="text" class="input-mini" id="max_children_<%$arrayRoom[room].room_id%>" data-id="<%$arrayRoom[room].room_id%>" value="<%$arrayRoom[room].room_layout_room_max_children%>">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><%$arrayLaguage['room_layout_extra_bed']['page_laguage_value']%>: </td>
                        <td align="left"><input type="text" class="input-mini" id="extra_bed_<%$arrayRoom[room].room_id%>" data-id="<%$arrayRoom[room].room_id%>" value="<%$arrayRoom[room].room_layout_room_extra_bed%>">
                        </td>
                    </tr>
                    </table></li><%/if%><%/section%>
                </ul>
                </div>
           		<div class="control-group">
                    <div class="controls form-actions pagination-centered btn-icon-pg">
            	<!--<ul><li class="btn btn-primary" id="hotel_attribute_setting_btn">  </li></ul>-->
                    <button type="submit" id="room_next" class="btn btn-primary pagination-centered"><%$arrayLaguage['next']['page_laguage_value']%></button>
                    </div>
                </div>
            </div>
           <div id="tab4" class="tab-pane">
               <div class="widget-content">
                <ul class="thumbnails">
                	<%section name=images loop=$arrayDataImages%>
                    <li class="span2">
                        <a class="thumbnail lightbox_trigger" href="<%$__IMGWEB%><%$arrayDataImages[images].room_layout_images_path%>">
                            <img id="room_layout_<%$arrayDataImages[images].room_layout_images_id%>" src="<%$__IMGWEB%><%$arrayDataImages[images].room_layout_images_path%>" alt="" >
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
                	<label class="control-label"><%$arrayLaguage['upload_room_layout_images']['page_laguage_value']%> :</label>
                    <div class="controls">
           			<p><input type="text" id="upload_images_url" value="" /> <input type="button" id="upload_images" value="选择图片" /></p>
                    </div>
                </div>
                </form>
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
<%include file="hotel/inc_js/roomsLayout_js.tpl"%>
</body>
</html>