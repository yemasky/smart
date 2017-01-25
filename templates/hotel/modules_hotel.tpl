<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
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
                    <h5><%$arrayLaguage['list_of_hotel']['page_laguage_value']%></h5>
                    <%if $arrayRoleModulesEmployee['role_modules_action_permissions']> 0%>
                    <div class="buttons">
                        <a class="btn btn-primary btn-mini" href="<%$addHotelUrl%>" id="add_company"><i class="am-icon-plus-square"></i> 
                        &#12288;<%$arrayLaguage['hotel_add']['page_laguage_value']%></a>
                    </div>
                    <%/if%>
                </div>
                <div class="widget-content nopadding">
                    <ul class="recent-posts">
                      <%section name=hotel loop=$arrayHotel%>
                      <li>
                        <div class="user-thumb"> <img width="50" height="50" alt="User" src="<%$__RESOURCE%>img/icons/50/hotel.jpg"> </div>
                        <div class="article-post">
                          <div class="fr">
                          	<a href="<%$arrayHotel[hotel].view_url%>" class="btn btn-primary btn-mini"><i class="am-icon-eye"></i> 
                            	<%$arrayLaguage['view']['page_laguage_value']%>
                            </a>
                            <%if $arrayRoleModulesEmployee['role_modules_action_permissions'] > 1%>
                          	<a href="<%$arrayHotel[hotel].edit_url%>" class="btn btn-primary btn-mini"><i class="am-icon-edit"></i> Edit</a> 
                            <%/if%>
                            <%if $arrayRoleModulesEmployee['role_modules_action_permissions'] > 2%>
                            <a href="#modal_delete" url="<%$arrayHotel[hotel].delete_url%>" class="btn btn-danger btn-mini" data-toggle="modal" ><i class="am-icon-trash-o"></i> Delete</a>
                            <%/if%>
                          </div>
                          <h5><%$arrayHotel[hotel].hotel_name%></h5>
                          <p>
                          	<span class="icon-time" title="添加时间"></span> <%$arrayHotel[hotel].hotel_add_date%> 　
                          	<%if $arrayHotel[hotel].hotel_phone!=''%><span class="am-icon-phone"></span> <%$arrayHotel[hotel].hotel_phone%><%/if%> 　
                          	<%if $arrayHotel[hotel].hotel_mobile!=''%><span class="am-icon-mobile"></span> <%$arrayHotel[hotel].hotel_mobile%><%/if%> 　
                          </p>
                          
                        </div>
                      </li>
                      <%/section%>
                      
                      <li></li>
                    </ul> 
  					<%include file="hotel/inc/page.tpl"%>
                </div>
            </div>

            
        </div>
					
	  </div>
    
    </div>
</div>
</div>
<%include file="hotel/inc/footer.tpl"%>
<%include file="hotel/inc/modal_box.tpl"%>
</body>
</html>