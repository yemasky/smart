<%include file="wise/inc/commonController.tpl"%>
<div class="p-md" ng-controller="<%$__module%>Controller">
  <h3><span translate="module.channel.channel"></span>列表</h3>
  <div class="table-responsive">
    <table class="table table-bordered table-striped bg-white">
      <thead>
        <tr>
          <th translate="module.channel.channel">类型</th>
          <th translate="module.channel.member_of">隶属于</th>
          <th translate="module.channel.channel_name">中文名称</th>
          <th translate="module.channel.channel_en_name">英文名称</th>
          <th translate="module.channel.phone">电话</th>
          <th translate="module.channel.address">地址</th>
          <th translate="common.hint.date">日期</th>
          <th translate="common.hint.manager">管理</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="(i, Channel) in arrayChannelList">
          <td class="text-nowrap">{{channel_type[Channel.channel]}}</td>
          <td><span ng-if="Channel.channel_father_id>0">{{arrayChannelList[Channel.channel_father_id].channel_name}}</span></td>
          <td><a ng-click="config(Channel.id)">{{Channel.channel_name}}</a></td>
          <td>{{Channel.channel_en_name}}</td>
          <td>{{Channel.phone}}</td>
          <td>{{Channel.address}}</td>
          <td>{{Channel.add_datetime}}</td>
          <td>
          <button class="btn btn-sm btn-default" ng-click="edit(Channel.id)"><span translate="common.hint.edit" >编辑</span></button>
          <button class="btn btn-sm btn-default" ng-click="config(Channel.id)"><span translate="common.hint.config" >配置</span></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<script language="JavaScript">
    app.controller('<%$__module%>Controller', function($rootScope, $scope, $httpService, $modal, $translate) {
		$scope.channel_type = eval('(<%$channel_type%>)');
		$scope.arrayChannelList = eval('(<%$arrayChannelList%>)');
		$scope.edit = function(id) {
			$scope.redirect('/app/<%$__module%>/<%$add_url%>&c_id='+id);
		};
		$scope.config = function(id) {
			$scope.redirect('/app/<%$__module%>/<%$config_url%>&c_id='+id);
		};
	});
	//
	
</script>
