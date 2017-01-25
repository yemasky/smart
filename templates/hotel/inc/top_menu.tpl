<!--Header-part-->
<div id="header">
  <h1><a href="<%$__WEB%>">Smart Hotel</a></h1>
</div>
<!--close-Header-part--> 

<!--top-Header-messaages-->
<div class="btn-group rightzero"> <a class="top_message tip-left" title="Manage Files"><i class="icon-file"></i></a> <a class="top_message tip-bottom" title="Manage Users"><i class="icon-user"></i></a> <a class="top_message tip-bottom" title="Manage Comments"><i class="icon-comment"></i><span class="label label-important">5</span></a> <a class="top_message tip-bottom" title="Manage Orders"><i class="icon-shopping-cart"></i></a> </div>
<!--close-top-Header-messaages--> 

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li class="" ><a title="" href="#"><i class="icon icon-user"></i> <span class="text">Profile</span></a></li>
    <li class=" dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Messages</span> <span class="label label-important">5</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sAdd" title="" href="#">new message</a></li>
        <li><a class="sInbox" title="" href="#">inbox</a></li>
        <li><a class="sOutbox" title="" href="#">outbox</a></li>
        <li><a class="sTrash" title="" href="#">trash</a></li>
      </ul>
    </li>
    <li class=""><a title="" href="#"><i class="icon icon-cog"></i> <span class="text">Settings</span></a></li>
    <li class=""><a title="" href="index.php?action=logout"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
  </ul>
</div>
<!--<div id="search">
  <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-left" title="Search"><i class="icon-search icon-white"></i></button>
</div>-->
<!--close-top-Header-menu-->

<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> 智能酒店管理菜单</a>
  <ul>
    <%section name=modules loop=$arrayEmployeeModules%>
    <%if $arrayEmployeeModules[modules].modules_id==$arrayEmployeeModules[modules].hotel_modules_father_id%> 
    <li class="<%if $navigation==$arrayEmployeeModules[modules].hotel_modules_navigation%>active<%/if%><%if !$smarty.section.modules.last &&  $arrayEmployeeModules[modules].modules_id==$arrayEmployeeModules[modules.index_next].hotel_modules_father_id%> submenu<%/if%>">
        <a href="<%$arrayEmployeeModules[modules].url%>"><i class="icon <%$arrayEmployeeModules[modules].hotel_modules_ico%>"></i> <span><%$arrayEmployeeModules[modules].hotel_modules_name%></span><%if !$smarty.section.modules.last && $arrayEmployeeModules[modules].modules_id==$arrayEmployeeModules[modules.index_next].hotel_modules_father_id%><span class="am-icon-chevron-circle-down"></span><%/if%></a>
    	<%if !$smarty.section.modules.last && $arrayEmployeeModules[modules].modules_id==$arrayEmployeeModules[modules.index_next].hotel_modules_father_id%>        
        <ul>
        <%/if%>
    <%else%>
    	<li><a href="<%$arrayEmployeeModules[modules].url%>"><span class="<%if $arrayEmployeeModules[modules].hotel_modules_ico!=''%><%$arrayEmployeeModules[modules].hotel_modules_ico%><%else%>am-icon-gear<%/if%>"></span><%$arrayEmployeeModules[modules].hotel_modules_name%></a></li>
        <%if $smarty.section.modules.last || $arrayEmployeeModules[modules].hotel_modules_father_id!=$arrayEmployeeModules[modules.index_next].hotel_modules_father_id%>
        </ul>
        <%/if%>
    <%/if%>
    <%if $smarty.section.modules.last || $arrayEmployeeModules[modules].hotel_modules_father_id!=$arrayEmployeeModules[modules.index_next].hotel_modules_father_id%>
    </li>
    <%/if%>
    <%/section%>
<!--
    <li class="active"><a href="index.html"><i class="icon icon-home"></i> <span>管理首页</span></a></li>
    <li> <a href="charts.html"><i class="icon icon-signal"></i> <span>Charts &amp; graphs</span></a> </li>
    <li> <a href="widgets.html"><i class="icon icon-inbox"></i> <span>Widgets</span></a> </li>
    <li><a href="tables.html"><i class="icon icon-th"></i> <span>Tables</span></a></li>
    <li><a href="grid.html"><i class="icon icon-fullscreen"></i> <span>Full width</span></a></li>
    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Forms</span> <span class="icon-circle-arrow-down"></span></a>
      <ul>
        <li><a href="form-common.html">Basic Form</a></li>
        <li><a href="form-validation.html">Form with Validation</a></li>
        <li><a href="form-wizard.html">Form with Wizard</a></li>
      </ul>
    </li>
    <li><a href="buttons.html"><i class="icon icon-tint"></i> <span>Buttons &amp; icons</span></a></li>
    <li><a href="interface.html"><i class="icon icon-pencil"></i> <span>Eelements</span></a></li>

    <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>Addons</span> <span class="icon-circle-arrow-down"></span></a>
      <ul>
        <li><a href="gallery.html">Gallery</a></li>
        <li><a href="calendar.html">Calendar</a></li>
        <li><a href="chat.html">Chat option</a></li>
      </ul>
    </li>
-->
  </ul>
</div>