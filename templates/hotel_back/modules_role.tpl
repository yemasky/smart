<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<style type="text/css">
.form-horizontal .btn-group {margin-bottom: 5px;margin-left: 0;margin-right: 5px;}
</style>
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
                    <h5><%$selfNavigation.hotel_modules_name%></h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <form method="post" class="form-horizontal" enctype="multipart/form-data" novalidate>
                        <div class="control-group">
                            <label class="control-label _edit"></label>
                            <div class="controls _edit">
                            <%$selfNavigation.hotel_modules_name%> : 在酒店部门管理设置角色。
                            </div>
                            <div class="controls">
                                
                            </div>
                            <div class="controls">
                            <br>
                            </div>
                         
                        </div>
                    </form>
                    
                </div>
                
                <div class="widget-content">
                    
                </div>
            </div>   
        </div>
					
	  </div>
    
    </div>
</div>
<%include file="hotel/inc/footer.tpl"%>
</body>
</html>