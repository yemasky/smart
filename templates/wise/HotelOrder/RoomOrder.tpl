<%include file="wise/inc/commonController.tpl"%>
<div class="p-md" ng-controller="<%$__module%>Controller">
  <div class="row">
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading bg-white">
          基本信息<br>
          <small class="text-muted"></small>
        </div>
        <div class="panel-body">
          <form role="form">
          	<div class="form-group">
                <label>类型</label>
                <select class="form-control">
                  <option value="">请选择</option>
                  <option value="Hotel">酒店/旅馆/民宿</option>
                  <option value="Meal">餐馆/Bar</option>
                  <option value="Meeting">商务会议</option>
                  <option value="Sport">健身娱乐</option>
                  <option value="Shop">商城/商店</option>
                  <option value="Service">商务服务</option>
                  <option value="Tour">旅行路线</option>
                </select>
            </div>
            <div class="form-group">
              <label for="channel_name">中文名称</label>
              <input type="text" class="form-control" id="channel_name" placeholder="请输入名称">
            </div>
            <div class="form-group">
              <label for="channel_en_name">英文名称</label>
              <input type="text" class="form-control" id="channel_en_name" placeholder="请输入英文名称">
            </div>
            <div class="form-group">
              <label for="exampleInputFile">File input</label>
              <input type="file" id="exampleInputFile">
              <p class="help-block">Example block-level help text here.</p>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox"> Check me out
              </label>
            </div>
            <button type="submit" class="btn btn-default m-b">Submit</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading bg-white">
          Horizontal form<br>
          <small class="text-muted">Use Bootstrap's predefined grid classes to align labels and groups of form controls in a horizontal layout by adding .form-horizontal to the form. Doing so changes .form-groups to behave as grid rows, so no need for .row.</small>
        </div>
        <div class="panel-body">
          <form class="form-horizontal" role="form">
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                  <label>
                    <input type="checkbox"> Remember me
                  </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Sign in</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading bg-white">
          Column sizing
        </div>
        <div class="panel-body">
          <div class="row row-sm">
            <div class="col-xs-3">
              <input type="text" class="form-control" placeholder=".col-xs-3">
            </div>
            <div class="col-xs-4">
              <input type="text" class="form-control" placeholder=".col-xs-4">
            </div>
            <div class="col-xs-5">
              <input type="text" class="form-control" placeholder=".col-xs-5">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-heading bg-white">
          Inline form<br>
          <small class="text-muted">Add ".form-inline" to your &lt;form> for left-aligned and inline-block controls. This only applies to forms within viewports that are at least 768px wide.</small>
        </div>
        <div class="panel-body">
          <form class="form-inline" role="form">
            <div class="form-group">
              <label class="sr-only" for="exampleInputEmail2">Email address</label>
              <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Enter email">
            </div>
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-addon">@</div>
                <input class="form-control" type="email" placeholder="Enter email">
              </div>
            </div>
            <div class="form-group">
              <label class="sr-only" for="exampleInputPassword2">Password</label>
              <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password">
            </div>
            <div class="checkbox m-l">
              <label class="ui-checks">
                <input type="checkbox"><i></i> Remember me
              </label>
            </div>
            <button type="submit" class="btn btn-default">Sign in</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
<script language="JavaScript">
    app.controller('<%$__module%>Controller', function($rootScope, $scope, $httpService) {
		
      	
})
</script>
