<div class="p-md" ng-controller="HomeController">
  <div class="row">
    <div class="col-sm-4">
      <div class="panel b-light">
        <div class="panel-heading p-v-xs p-h-sm">
          <span class="pull-right">80%</span>
          今日运营数据
        </div>
        <div class="progress progress-xxxs no-margin no-radius bg-white">
          <div class="progress-bar bg-light" style="width:80%"></div>
        </div>
        <div class="panel-body p-sm">
          <div class="pull-left pull-none-sm p-h text-center m-r-xs">
            <div class="inline">
              <div ui-jp="easyPieChart" ui-options="{percent:80,lineWidth: 5,trackColor: '#fff',barColor: '{{app.color.light}}',scaleColor: '#fff',
                  size: 65,lineCap: 'butt',color: '{{app.color.success}}',animate: 3000}">
                <div class="text-white"> 80% </div>
              </div>
            </div>
          </div>
          <div class="clear">
            <div class="text-2x font-bold">432,000</div>
            <small class="text-muted">Caculated in 19:30 Thu</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="panel b-light">
        <div class="panel-heading p-v-xs p-h-sm">
          <span class="pull-right">20%</span> 新会员
        </div>
        <div class="progress progress-xxxs no-margin no-radius bg-white">
          <div class="progress-bar bg-light" style="width:20%"></div>
        </div>
        <div class="panel-body p-sm">
          <div class="pull-left pull-none-sm p-h text-center m-r-xs">
            <div ui-jp="easyPieChart" ui-options="{percent:20,lineWidth: 5,trackColor: '{{app.color.light}}',
                barColor: '{{app.color.info}}',scaleColor: '#fff',size: 65,lineCap: 'butt',color: '{{app.color.inverse}}',animate: 3000 }">
              <div class="text-white"> 20%</div>
            </div>
          </div>
          <div class="clear">
            <div class="text-2x font-bold">386,000</div>
            <small class="text-muted">Peaked at 14:30 Mon</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="panel b-light">
        <div class="panel-heading p-v-xs p-h-sm">
          <span class="pull-right">50%</span>
          今日流水
        </div>
        <div class="progress progress-xxxs no-margin no-radius bg-white">
          <div class="progress-bar bg-light no-radius" style="width:50%"></div>
        </div>
        <div class="panel-body p-sm">
          <div class="pull-left pull-none-sm p-h text-center m-r-xs">
            <div ui-jp="easyPieChart" ui-options="{
              percent: 50,
              lineWidth: 28,
              trackColor: '#fff',
              barColor: '{{app.color.info}}',
              scaleColor: '#fff',
              size: 65,
              lineCap: 'butt',
              rotate: 90,
              animate: 5000
            }">
            <div class="text-black">
              50%
            </div>
          </div>
          </div>
          <div class="clear">
            <div class="text-2x font-bold">96,000</div>
            <small class="text-dk">Questions scheduled</small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-8">
      <div class="panel b-light">
        <div class="panel-heading">
          <label class="ui-switch bg-inverse pull-right" ng-init="showData=true">
            <input type="checkbox" ng-model="showData">
            <i></i>
          </label>
          <span>今日进店</span> <i class="fa fa-caret-up text-success"></i><span class="text-xs text-muted m-l-xs">1.5%</span>
        </div>
        <div class="panel-body">
          <div ui-jp="plot" ui-refresh="showData" ui-options="
            [
              {
                data: {{plot_line}}, 
                points: { show: true, radius: 4, lineWidth: 3, fillColor: 'rgba(18,147,204,0.5)'}, 
                lines:  { show: true, lineWidth: 0, fill: 0.5, fillColor: 'rgba(18,147,204,0.5)' }, 
                color:'#fff'
              },
              {
                data: {{plot_line_3}}, 
                points: { show: true, radius: 4, lineWidth: 3, fillColor: 'rgba(166,107,238,0.5)'}, 
                lines:  { show: true, lineWidth: 0, fill: 0.5, fillColor: 'rgba(166,107,238,0.5)' }, 
                color:'#fff'
              }
            ],
            {
              series: { shadowSize: 0 },
              xaxis: { show: true, font: { color: '#ccc' }, position: 'bottom' },
              yaxis:{ show: true, font: { color: '#ccc' }},
              grid: { hoverable: true, clickable: true, borderWidth: 0, color: '#ccc' },
              tooltip: true,
              tooltipOpts: { content: '%x.0 is %y.4',  defaultTheme: false, shifts: { x: 0, y: -40 } }
            }
          " style="height:240px" >
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel b-light">
        <div class="panel-heading">
          <a href=""></a>
          本月总收入
        </div>
        <div style="margin: 0 -2px">
          <div ui-jp="plot" ui-options="
            [
              { data: {{plot_line_1}}, points: { show: true, radius: 0}, splines: { show: true, tension: 0.45, lineWidth: 1, fill: 0.2 } },
              { data: {{plot_line_2}}, points: { show: true, radius: 0}, splines: { show: true, tension: 0.45, lineWidth: 1, fill: 1 } }
            ], 
            {
              colors: ['{{app.color.success}}', '{{app.color.inverse}}'],
              series: { shadowSize: 3 },
              xaxis: { show: false, font: { color: '#ccc' }, position: 'bottom' },
              yaxis:{ show: false, font: { color: '#ccc' }},
              grid: { hoverable: true, clickable: true, borderWidth: 0, color: '#ccc' },
              tooltip: true,
              tooltipOpts: { content: '%x.0 is %y.4',  defaultTheme: false, shifts: { x: 0, y: -40 } }
            }
          " style="height:175px" >
          </div>
        </div>
        <div class="panel-footer bg-inverse no-b-t">
          <div class="box">
            <div class="box-col p-md">
              <span class="text-xl text-lt">$30,343 <i class="fa fa-caret-up text-muted"></i></span>
            </div>
            <div class="box-col text-right p-md w-xs">
              <div ng-init="data1=[60,40]" ui-jp="sparkline" ui-options="{{data1}}, {type:'pie', height:35, sliceColors:['{{app.color.dark}}','#fff']}" class="sparkline inline"></div>
            </div>
          </div>
        </div>
      </div>
    </div>    
  </div>
  <!--
  <div class="box b-a bg-white m-b">
    <div class="col-md-8">
      <div class="panel-heading b-b b-light">World Market</div>
      <div class="panel-body">
        <p class="m-b-lg text-muted">Many maps of the world, world regions, countries and cities are available for download from <a href="http://jvectormap.com/">http://jvectormap.com/</a>. All of them are made from the data in public domain or data licensed under the free licenses, so you can use them for any purpose without of charge.</p>
        <div class="m-b-lg" style="height:240px;" ui-jp="vectorMap" ui-options="{            
          map: 'world_mill_en',
          markers: {{world_markers}},
          normalizeFunction: 'polynomial',
          backgroundColor: '#fff',
          regionsSelectable: true,
          markersSelectable: true,
          regionStyle: {
            initial: {
              fill: '{{app.color.light}}'
            },
            hover: {
              fill: '{{app.color.info}}',
              stroke: '#fff'
            },
          },
          markerStyle: {
            initial: {
              fill: '{{app.color.info}}',
              stroke: '#fff'
            },
            hover: {
              fill: '{{app.color.primary}}',
              stroke: '#fff'
            }
          },
          series: {
            markers: [{
              attribute: 'fill',
              scale: ['{{app.color.primary}}','{{app.color.inverse}}', '{{app.color.success}}'],
              values: {{cityAreaData}}
            },{
              attribute: 'r',
              scale: [5, 20],
              values: {{cityAreaData}}
            }]
          }
        }" >
        </div>
      </div>
    </div>
    <div class="col-md-4 b-l no-border-sm">
      <div class="panel-heading b-b b-light">Infomation</div>
      <div class="list-group no-border no-radius">
        <div class="list-group-item">
          <span class="pull-right">293,200</span>
          <i class="fa fa-fw fa-circle text-info"></i>
          Vatican City
        </div>
        <div class="list-group-item">
          <span class="pull-right">203,000</span>
          <i class="fa fa-fw fa-circle text-success"></i>
          San Marino
        </div>
        <div class="list-group-item">
          <span class="pull-right">180,230</span>
          <i class="fa fa-fw fa-circle text-inverse"></i>
          Marshall Islands
        </div>
        <div class="list-group-item">
          <span class="pull-right">130,100</span>
          <i class="fa fa-fw fa-circle text-inverse-lt"></i>
          Maldives
        </div>
        <div class="list-group-item">
          <span class="pull-right">98,000</span>
          <i class="fa fa-fw fa-circle text-primary"></i>
          Palau
        </div>
      </div>
    </div>
  </div>
  -->
  <div class="row">
    <div class="col-sm-6">
      <h5 class="no-margin m-b">新加会员</h5>
      <ul class="list-group list-group-md">
        <li class="list-group-item">
          <a href class="pull-left w-thumb m-r b-b b-b-2x b-success"><img src="pages/images/a1.jpg" class="img-responsive"></a>
          <div class="clear">
            <a href class="font-bold block">Jonathan Doe</a>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit
          </div>
        </li>
        <li class="list-group-item">
          <a href class="pull-left w-thumb m-r b-b b-b-2x b-success"><img src="pages/images/a2.jpg" class="img-responsive"></a>
          <div class="clear">
            <a href class="font-bold block">Jack Michale</a>
            Sectetur adipiscing elit
          </div>
        </li>
        <li class="list-group-item">
          <a href class="pull-left w-thumb m-r b-b b-b-2x b-warning"><img src="pages/images/a3.jpg" class="img-responsive"></a>
          <div class="clear">
            <a href class="font-bold block">Jessi</a>
            Sectetur adipiscing elit
          </div>
        </li>
        <li class="list-group-item">
          <a href class="pull-left w-thumb m-r b-b b-b-2x"><img src="pages/images/a4.jpg" class="img-responsive"></a>
          <div class="clear">
            <a href class="font-bold block">Sodake</a>
            Vestibulum ullamcorper sodales nisi nec condimentum
          </div>
        </li>
      </ul>
    </div>
    <div class="col-sm-6">
      <h5 class="no-margin m-b">最新留言</h5>
      <div class="list-group list-group-gap">
        <a href class="list-group-item b-l-inverse">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit
        </a>
        <a href class="list-group-item b-l-success">
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
        </a>
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
angular.module("ui.jp", ["oc.lazyLoad", "ui.load"]).value("uiJpConfig", {}).directive("uiJp", ["uiJpConfig", "MODULE_CONFIG", "$ocLazyLoad", "uiLoad", "$timeout", function(a, b, c, d, e) {
    return {
        restrict: "A",
        compile: function(c, f) {
            var g = a && a[f.uiJp];
            return function(a, c, f) {
                function h() {
                    var b = [];
                    return f.uiOptions ? (b = a.$eval("[" + f.uiOptions + "]"), angular.isObject(g) && angular.isObject(b[0]) && (b[0] = angular.extend({}, g, b[0]))) : g && (b = [g]), b
                }

                function i() {
                    e(function() {
                        c[f.uiJp].apply(c, h())
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
                    a.name == f.uiJp && (k = a.files)
                }), k ? d.load(k).then(function() {
                    i(), j()
                }).catch(function() {}) : (i(), j())
            }
        }
    }
}]);
 app.controller('HomeController', function($rootScope, $scope, $httpService, $location, $translate, $aside, $ocLazyLoad, $alert, $filter, $modal) {
    $scope.param = {};
    $ocLazyLoad.load([$scope._resource + "vendor/libs/daterangepicker.css",$scope._resource + "styles/booking.css"]);
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

