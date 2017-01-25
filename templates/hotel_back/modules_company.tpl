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
                    <h5><%$arrayLaguage['list_of_companies']['page_laguage_value']%></h5>
                    <%if $arrayRoleModulesEmployee['role_modules_action_permissions']> 0%>
                    <div class="buttons">
                        <a class="btn btn-primary btn-mini" href="<%$addCompanyUrl%>" id="add_company"><i class="am-icon-plus-square"></i>
                        &#12288;<%$arrayLaguage['company_add']['page_laguage_value']%></a>
                    </div>
                    <%/if%>
                </div>
                <div class="widget-content nopadding">
                    <ul class="recent-posts">
                      <%section name=company loop=$arrayCompany%>
                      <li>
                        <div class="user-thumb"> <a href="<%$arrayCompany[company].view_url%>"><img width="50" height="50" alt="User" src="<%$__RESOURCE%>img/icons/50/company.jpg"></a> </div>
                        <div class="article-post">
                          <div class="fr">
                          	<a href="<%$arrayCompany[company].view_url%>" class="btn btn-primary btn-mini"><i class="am-icon-eye"></i> 
                            	<%$arrayLaguage['view']['page_laguage_value']%>
                            </a> 
                            <%if $arrayRoleModulesEmployee['role_modules_action_permissions'] > 1%>
                          	<a href="<%$arrayCompany[company].edit_url%>" class="btn btn-primary btn-mini"><i class="am-icon-edit"></i> Edit</a> 
                            <%/if%>
                            <%if $arrayRoleModulesEmployee['role_modules_action_permissions'] > 2%>
                            <a href="#modal_delete" url="<%$arrayCompany[company].delete_url%>" class="btn btn-danger btn-mini" data-toggle="modal"><i class="am-icon-trash-o"></i> Delete</a>
                            <%/if%>
                          </div>
                          <h5><a href="<%$arrayCompany[company].view_url%>"><%$arrayCompany[company].company_name%></a></h5>
                          <p>
                          	<span class="icon-time" title="添加时间"></span> <%$arrayCompany[company].company_add_date%> 　
                          	<%if $arrayCompany[company].company_phone!=''%><span class="am-icon-phone"></span> <%$arrayCompany[company].company_phone%><%/if%> 　
                          	<%if $arrayCompany[company].company_mobile!=''%><span class="am-icon-mobile"></span> <%$arrayCompany[company].company_mobile%><%/if%> 　
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