<span ng-controller="LoginOverTimeController"></span>
<script language="javascript">
app.controller("LoginOverTimeController",function($rootScope, $scope, $httpService, $alert, $location, $translate){
	$alert({title: 'Error', content: '登陆超时！', templateUrl: '/modal-warning.html', show: true});
});	
</script>