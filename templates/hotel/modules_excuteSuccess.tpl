<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<link rel="stylesheet" href="<%$__RESOURCE%>css/fullcalendar.css" />
</head>
<body>
<%include file="hotel/inc/top_menu.tpl"%>
<div id="content">
<%include file="hotel/inc/navigation.tpl"%>
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
  <div  class="quick-actions_homepage">
    <ul class="quick-actions">
          <li> <a href="#"> <i class="icon-dashboard"></i> My Dashboard </a> </li>
          <li> <a href="#"> <i class="icon-shopping-bag"></i> Shopping Cart</a> </li>
          <li> <a href="#"> <i class="icon-web"></i> Web Marketing </a> </li>
          <li> <a href="#"> <i class="icon-people"></i> Manage Users </a> </li>
          <li> <a href="#"> <i class="icon-calendar"></i> Manage Events </a> </li>
        </ul>
  </div>
  <div class="container-fluid">    
    <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-hand-right"></i> </span>
            <h5><%$arrayLaguage['reminder']['page_laguage_value']%></h5>
          </div>
          <div class="widget-content">
            <div class="alert alert-success alert-block">  
              <h4 class="alert-heading"><%$arrayLaguage['excute_success']['page_laguage_value']%></h4>
             You're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. 
            </div>
          </div>
        </div>    
  </div>
</div>
<%include file="hotel/inc/footer.tpl"%>
</body>
</html>