<span class="hide" ng-controller="NoLoginController"></span>
<script language="JavaScript">
    app.controller('NoLoginController', function($rootScope, $scope, $httpService, $alert, $translate) {
		$httpService.header('refresh', '0');
        $(document).ready(function(){
            var message = $scope.reconvertChinese($translate.instant("error.code.000011"));
		  $alert({title: 'Error', content: message, templateUrl: '/modal-warning.html', show: true});
        });
    });
</script>