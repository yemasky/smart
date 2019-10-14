<!--CommonController-->
<span class="hide" ng-controller="CommonController"></span>
<script language="JavaScript">
    app.controller('CommonController', function($rootScope, $scope, $httpService, $modal) {
		$httpService.header('refresh', '0');
		var common = '<%$__commonResponse%>';
		if(common != '') {
			var common = eval('('+common+')');
			if(typeof(common.error) != 'undefined') {
				var message = $scope.getErrorByCode(common.error);
				$modal({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
			}
			$scope.setCommonSetting(common);
		}
    });
</script>
<div class="p-h-md p-xs bg-white box-shadow pos-rlt"><i class="fa fa-building-o"> </i> <span class="no-margin" ng-bind-html="action_nav_name"></span><%$nav%> <span class="badge pull-right">{{_baseDateTime() | date:"yyyy-MM-dd"}}</span></div>