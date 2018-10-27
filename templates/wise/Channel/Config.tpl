<%include file="wise/inc/commonController.tpl"%>
<div class="p-md">
  <div class="box b-a bg-white m-b"  ng-controller="<%$__module%>Controller">
    <div class="col-md-10">
      <div class="panel-heading b-b b-light">{{arrayChannel.channel_name}}
      	<a class="pull-right fa fa-edit" ng-click=""> </a>
      </div>
      <div class="panel-body">
        <p class="m-b-lg text-muted">Many maps of the world, world regions, countries and cities are available for download from <a href="http://jvectormap.com/">http://jvectormap.com/</a>. All of them are made from the data in public domain or data licensed under the free licenses, so you can use them for any purpose without of charge.</p>
        <div class="m-b-lg">叮叮
        </div>
      </div>
    </div>
    <div class="col-md-2 b-l no-border-sm">
      <div class="panel-heading b-b b-light" translate="common.hint.config">配置</div>
      <div class="list-group no-border no-radius">
        <div class="list-group-item" ng-repeat="(key, name) in arrayConfig" ng-init="i=0;">
          <a class="pull-right ti-settings" ng-click="config(key)"></a>
          <i class="fa fa-fw fa-circle text-info"></i>
          {{name}} 
        </div>
      </div>
    </div>
  </div>
</div>
<script language="JavaScript">
    app.controller('<%$__module%>Controller', function($rootScope, $scope, $httpService, $location, $translate) {
		$scope.arrayConfig = eval('(<%$arrayConfig%>)');
		$scope.arrayChannel = eval('(<%$arrayChannel%>)');
		$scope.config = function(key) {
			$httpService.header('method', key);
			$scope.redirect('/app/<%$__module%>/<%$channel_config_url%>&id=<%$channel_id%>&method='+key);
		}
		
	});
	//
	
</script>