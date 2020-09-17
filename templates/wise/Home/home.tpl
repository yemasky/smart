<div class="p-md" ng-controller="HomeController">
  
  
  <div class="row">
    <div class="col-sm-6">
      <h5 class="no-margin m-b">新加会员</h5>
      <ul class="list-group list-group-md">
        <%foreach key=i item=member from=$newMember%>
          <li class="list-group-item">
          <a href class="pull-left w-thumb m-r b-b b-b-2x b-success"><img src="<%$member.wx_avatar%>" onerror="this.src='data/images/userimg/user_h.png'" class="img-responsive"></a>
          <div class="clear">
            <a href class="font-bold block"><%$member.member_name%></a>
            注册时间:<%$member.add_datetime%>
          </div>
        </li>
        <%/foreach%>
      </ul>
    </div>
    <div class="col-sm-6">
      <h5 class="no-margin m-b">最新留言</h5>
      <div class="list-group list-group-gap">
        <a href class="list-group-item b-l-inverse">
          暂无留言
        </a>
        <!--<a href class="list-group-item b-l-success">
          Morbi id neque quam. Aliquam sollicitudin venenatis ipsum ac feugia
        </a>
        <a href class="list-group-item b-l-dark">
          Vestibulum ullamcorper sodales nisi nec condimentum
        </a>
        <a href class="list-group-item b-l-warning">
          Sollicitudin venenatis ipsum ac
        </a>
        <a href class="list-group-item b-l-info">
          Donec eleifend condimentum nisl eu consectetur. Integer eleifend
        </a>
        <a href class="list-group-item b-l-primary">
          Lectus arcu malesuada sem
        </a>-->
      </div>
    </div>
  </div>
  
</div>
<script src="<%$__RESOURCE%>vendor/jquery/easypiechart/jquery.easy-pie-chart.js"></script>
<script src="<%$__RESOURCE%>vendor/jquery/flot/jquery.flot.min.js"></script>
<script src="<%$__RESOURCE%>vendor/jquery/flot/jquery.flot.resize.js"></script>
<script src="<%$__RESOURCE%>vendor/jquery/flot/jquery.flot.tooltip.min.js"></script>
<script src="<%$__RESOURCE%>vendor/jquery/flot/jquery.flot.spline.js"></script>
<script src="<%$__RESOURCE%>vendor/jquery/flot/jquery.flot.orderBars.js"></script>
<script src="<%$__RESOURCE%>vendor/jquery/flot/jquery.flot.pie.min.js"></script>
<script src="<%$__RESOURCE%>vendor/jquery/sparkline/jquery.sparkline.min.js"></script>
<script src="<%$__RESOURCE%>vendor/jquery/jvectormap/jquery-jvectormap.min.js"></script>
<script src="<%$__RESOURCE%>vendor/jquery/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<%$__RESOURCE%>vendor/jquery/jvectormap/jquery-jvectormap-us-aea-en.js"></script>
<script language="JavaScript">
angular.module("ui.jq", ["oc.lazyLoad", "ui.load"]).value("uiJqConfig", {}).directive("uiJq", ["uiJqConfig", "MODULE_CONFIG", "$ocLazyLoad", "uiLoad", "$timeout", function(a, b, c, d, e) {
    return {
        restrict: "A",
        compile: function(c, f) {
            var g = a && a[f.uiJq];
            return function(a, c, f) {
                function h() {
                    var b = [];
                    return f.uiOptions ? (b = a.$eval("[" + f.uiOptions + "]"), angular.isObject(g) && angular.isObject(b[0]) && (b[0] = angular.extend({}, g, b[0]))) : g && (b = [g]), b
                }

                function i() {
                    e(function() {
                        c[f.uiJq].apply(c, h())
                    }, 0, !1)
                }

                function j() {
                    f.uiRefresh && a.$watch(f.uiRefresh, function() {
                        i()
                    })
                }
                f.ngModel && c.is("select,input,textarea") && c.bind("change", function() {
                    c.trigger("input")
                });
                var k = !1;
                angular.forEach(b, function(a) {
                    a.name == f.uiJq && (k = a.files)
                }), k ? d.load(k).then(function() {
                    i(), j()
                }).catch(function() {}) : (i(), j())
            }
        }
    }
}]);
 app.controller('HomeController', function($rootScope, $scope, $httpService, $location, $translate, $aside, $ocLazyLoad, $alert, $filter, $modal) {
    $scope.param = {};
    $ocLazyLoad.load([$scope._resource + "styles/booking.css"]);
    //初始化数据
    $scope.plot_pie = [];
    var series = Math.floor(Math.random() * 4) + 3;

    for (var i = 0; i < series; i++) {
    $scope.plot_pie[i] = {
      label: "Series" + (i + 1),
      data: Math.floor(Math.random() * 100) + 1
    }
    }

    $scope.plot_line = [[1, 7.5], [2, 7.5], [3, 5.7], [4, 8.9], [5, 10], [6, 7], [7, 9], [8, 13], [9, 7], [10, 6]];
    $scope.plot_line_1 = [[1, 9.5], [2, 9.4], [3, 9.5], [4, 9.5], [5, 9.7], [6, 9.6], [7, 9.3], [8, 9.5], [9, 9], [10, 9.9]];
    $scope.plot_line_2 = [[1, 4.5], [2, 4.2], [3, 4.5], [4, 4.3], [5, 4.5], [6, 4.7], [7, 4.6], [8, 4.8], [9, 4.6], [10, 4.5]];
    $scope.plot_line_3 = [[1, 14], [2, 5.7], [3, 9.6], [4, 7.8], [5, 6.6], [6, 10.5]];
});

</script>

