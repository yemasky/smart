<!DOCTYPE html>
<html lang="en">
<head>
<%include file="hotel/inc/head.tpl"%>
<script src="<%$__RESOURCE%>js/jquery.dataTables.min.js"></script>
<script language="javascript">
$(document).ready(function(){
	
	$('.data-table').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"sDom": '<""l>t<"F"fp>'
	});	
	
	$("span.icon input:checkbox, th input:checkbox").click(function() {
		var checkedStatus = this.checked;
		var checkbox = $(this).parents('.widget-box').find('tr td:first-child input:checkbox');		
		checkbox.each(function() {
			this.checked = checkedStatus;
			if (checkedStatus == this.checked) {
				$(this).closest('.checker > span').removeClass('checked');
			}
			if (this.checked) {
				$(this).closest('.checker > span').addClass('checked');
			}
		});
	});	
});
</script>
</head>
<body>
<%include file="hotel/inc/top_menu.tpl"%>
<style type="text/css">
select {width: 70px;}
#btn_room_layout{margin: 8px 100px 0 0;}
</style>
<div id="content">
<%include file="hotel/inc/navigation.tpl"%>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
          <div class="widget-title">
             <span class="icon"><i class="icon-th"></i></span> 
            <h5><%$selfNavigation['hotel_modules_name']%></h5>
            <div class="buttons" id="btn_room_layout">
                <a class="btn btn-primary btn-mini" href="<%$add_room_layout_url%>" id="add_room_layout"><i class="am-icon-plus-square"></i> 
                &#12288;<%$arrayLaguage['add_rooms_layout']['page_laguage_value']%></a>
            </div>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th><%$arrayLaguage['room_layout_name']['page_laguage_value']%></th>
                  <th><%$arrayLaguage['room_layout_attr']['page_laguage_value']%></th>
                  <th><%$arrayLaguage['status']['page_laguage_value']%></th>
                  <th><%$arrayLaguage['operate']['page_laguage_value']%></th>
                </tr>
              </thead>
              <tbody>
              <%section name=layout loop=$arrayDataInfo%>
                <tr class="gradeX">
                  <td><i class="am-icon-bed am-yellow-F36419"></i> <%$arrayDataInfo[layout].room_layout_name%></td>
                  <td>
                  	<code><%$arrayLaguage['area']['page_laguage_value']%>:<%$arrayDataInfo[layout].room_layout_area%></code>
                  	<code><%$arrayLaguage['orientations']['page_laguage_value']%>:<%$arrayLaguage[$arrayDataInfo[layout].room_layout_orientations]['page_laguage_value']%></code>
                    
                  </td>
                  <td>
                    <%if $arrayDataInfo[layout].room_layout_valid==1%><i class="icon-ok-circle"></i><%else%><i class="icon-ban-circle"></i><%/if%>
                    <span class="hide"><%$arrayDataInfo[layout].room_layout_valid%></span>
                  </td>
                  <td class="center">
                  	 <div class="fr">
                        <a href="<%$arrayDataInfo[layout].view_url%>" class="btn btn-primary btn-mini"><i class="am-icon-eye"></i> 
                            <%$arrayLaguage['view']['page_laguage_value']%>
                        </a> 
                        <%if $arrayRoleModulesEmployee['role_modules_action_permissions'] > 1%>
                        <a href="<%$arrayDataInfo[layout].edit_url%>" class="btn btn-primary btn-mini"><i class="am-icon-edit"></i> Edit</a> 
                        <%/if%>
                        <%if $arrayRoleModulesEmployee['role_modules_action_permissions'] > 2%>
                        <a href="#modal_delete" url="<%$arrayDataInfo[layout].delete_url%>" class="btn btn-danger btn-mini" data-toggle="modal"><i class="am-icon-trash-o"></i> Delete</a>
                        <%/if%>
                      </div>
                  </td>
                </tr>
              <%/section%>
              </tbody>
            </table>
          </div>
        </div>   
        </div>
					
	  </div>
    
    </div>
</div>
</div>
<%include file="hotel/inc/footer.tpl"%>
<%include file="hotel/inc/modal_box.tpl"%>
</body>
</html>