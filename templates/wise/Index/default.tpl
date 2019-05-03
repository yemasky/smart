<!DOCTYPE html>
<html lang="en" ng-app="app">
<head>
<%include file="wise/inc/meta.tpl"%>
</head>
<body>
<div class="app" ng-class="{'app-header-fixed':app.settings.headerFixed, 'app-aside-top':app.settings.asideTop}" ui-view ng-controller="MainController" id="MainController"></div>
<!--<div ui-view="header_menu"></div>-->
<%include file="wise/inc/header.tpl"%>
<%include file="wise/inc/common.tpl"%>
</body>
</html>