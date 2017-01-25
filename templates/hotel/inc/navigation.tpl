<div id="content-header">
    <div id="breadcrumb">
        <a href="index.php" title="Go to <%$arrayLoginEmployeeInfo.hotel_name%>" class="tip-bottom"><i class="icon-home"></i> <%$arrayLoginEmployeeInfo.hotel_name%></a>
        <%section name=nav loop=$arrayNavigation%>
        	<a href="<%$arrayNavigation[nav].url%>" class="<%if $smarty.section.nav.last%>current<%else%>tip-bottom<%/if%>"><%$arrayNavigation[nav].hotel_modules_name%></a>
        <%/section%>
    </div>
    <!--<h1><%$arrayLoginEmployeeInfo.hotel_name%></h1>-->
</div>