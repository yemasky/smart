<script type="text/javascript">
$(document).ready(function(){
    <!--
    var employee_edit_validate = $("#add_employee_form").validate({
		rules: {
			employee_name: {required: true},
            employee_sex: {required: true},
            employee_mobile: {required: true,isMobile: true},
            upload_images_url: {required: true},
		},
		messages: {
			employee_name:"请填写姓名",
            employee_sex:"",
            employee_mobile:"请填写正确的手机号码！",
            upload_images_url:"请上传或选择图片！",
		},
		errorClass: "text-error",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('success');
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		},
		submitHandler: function() {
            var param = $("#add_employee_form").serialize();
            $('#modal_save').show('fast');
            var url = '<%$add_url%>';
            $.ajax({
                url : url,type : "post",dataType : "json",data: param,
                success : function(result) {
                    data = result;
                    $('#modal_save').hide('fast');
                    if(data.success == 1) {
                        $('#modal_success').modal('show');
                        $('#modal_success_message').html(data.message);
                        EmployeeClass.employee_id = data.itemData;
                        EmployeeClass.employee_list[EmployeeClass.employee_id] = '';
                    } else {
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                }
            });
		}
	});
    
    var employee_personnel_validate = $("#employee_personnel").validate({
		rules: {
		},
		messages: {
		},
		errorClass: "text-error",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('success');
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		},
		submitHandler: function() {
            var param = $("#employee_personnel").serialize() +　'&employee_id='+EmployeeClass.employee_id;
            $('#modal_save').show('fast');
            var url = '<%$personnelFile_url%>';
            $.ajax({
                url : url,type : "post",dataType : "json",data: param,
                success : function(result) {
                    data = result;
                    $('#modal_save').hide('fast');
                    if(data.success == 1) {
                        EmployeeClass.employee_personnel_list[EmployeeClass.employee_id] = '';
                        $('#modal_success').modal('show');
                        $('#modal_success_message').html(data.message);
                    } else {
                        $('#modal_fail').modal('show');
                        $('#modal_fail_message').html(data.message);
                    }
                }
            });
		}
	});
    //日历
	$.datetimepicker.setLocale('ch');
	$('#employee_birthday').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, 
        yearStart: '1930', yearEnd: '<%$yearEnd%>', //yearOffset:1,maxDate:'+1970-01-02',
		beforeShowDay: function(date) {
            if (date.getFullYear() > '<%$yearEnd%>') {
				return [false];
			}
            return [true];
		},
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
       
	});
    $('#employee_entry_date').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, 
        yearStart: '1930', yearEnd: '2050', //yearOffset:1,maxDate:'+1970-01-02',
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
       
	});
    $('#employee_probation_date').datetimepicker({theme:'dark', format: 'Y-m-d', formatDate:'Y-m-d',timepicker:false, 
        yearStart: '1930', yearEnd: '2050', //yearOffset:1,maxDate:'+1970-01-02',
        onGenerate:function( ct ){
            $(this).find('.xdsoft_other_month').removeClass('xdsoft_other_month').addClass('custom-date-style');
        },
       
	});
    var EmployeeClass = {
		setting: {},role: {},uploadJsonUrl:'',fileManagerJsonUrl:'',employee_id: '',
        ZTreeObj: {},employee_list: {},employee_personnel_list:{},
        zNodes:{},
        instance: function() {
            var employee = {};
            employee.initParameter = function() {
                //var setting = EmployeeClass.setting;
                EmployeeClass.setting = {
                    data: {keep: {
                            parent:true,
                            leaf:true
                        },
                        simpleData: {
                            enable: true
                        }
                    },
                    callback: {beforeClick: employee.beforeClick,onClick: employee.onClick}
                };
                EmployeeClass.ZTreeObj['id'] = 'zTree';
                //var zNodes = EmployeeClass.zNodes;
                EmployeeClass.zNodes['zTree'] = [
                    { id:0, pId:0, name:"<%$arrayLoginEmployeeInfo.hotel_name%>", open:true, isParent:true},
                    <%foreach key=position_id item=position from=$arrayPosition%>
                    {id:'P<%$position_id%>',pId:'<%$position.department_id%>', name:"<%$position.department_position_name%>", isParent:false},
                    <%/foreach%>
                    <%foreach key=department_id item=department from=$arrayDepartment%>
                    {id:'<%$department_id%>',pId:'<%$department.department_father_id%>', name:"<%$department.department_name%>", open:true,isParent:true},
                    <%/foreach%>
                ];
            };
            employee.init = function() {
                $.fn.zTree.init($("#zTree"), EmployeeClass.setting, EmployeeClass.zNodes['zTree']);
                $(".addTree").bind("click", employee.addEmployee);
                $('#close,.close').click(function(e) {
                    $('#employee_page').show('fast');
                    $('#employee_add').hide('fast');
                });
                var role = EmployeeClass.role;
                $('#role_id option').each(function(index, element) {
                    var role_id = this.value;
                    var position_id = $(this).attr('position');
                    if(typeof(role[position_id]) == 'undefined') {
                        role[position_id] = {};
                    }
                    role[position_id][role_id] = $.trim(this.text);
                });
                EmployeeClass.role = role;
                $('.PageEmployee').click(function(e) {
                    employee.editEmployee(this);
                });
                $('#save_info').click(function(e) {
                    $('#add_employee_form').submit();
                });
                $('#tab_employee_personnel').click(function() {
                    var employee_id = EmployeeClass.employee_id;
                    if (employee_edit_validate.form() && employee_id > 0) {
                        employee.getEmployeePersonnel(employee_id);
                    } else {
                        $('#modal_info_message').html('请先保存员工基本信息！');
                        $('#modal_info').modal('show');
                        return false;
                    }
                });
            };
            
            employee.getEmployeePersonnel = function(employee_id) {
                if(typeof(EmployeeClass.employee_personnel_list[employee_id]) == 'undefined' || 
                            EmployeeClass.employee_personnel_list[employee_id] == '') {
                    $('#modal_loading').modal('show');
                    $.getJSON('<%$personnelFile_url%>&employee_id=' + employee_id,function(result) {
                        data = result;
                        $('#modal_loading').modal('hide');
                        if(data.success == 1) {
                            var personnel = data.itemData;
                            EmployeeClass.employee_personnel_list[employee_id] = personnel;
                            employee.setEmployeePersonnel(personnel);
                        } else {
                            $('#modal_fail').modal('show');
                            $('#modal_fail_message').html(data.message);
                        }     
                    })
                } else{
                    var personnel = EmployeeClass.employee_personnel_list[employee_id];
                    employee.setEmployeePersonnel(personnel);
                }
            };
            employee.setEmployeePersonnel = function(personnel) {
                for(key in personnel) {
                    $('#' + key).val(personnel[key]);
                    if(key == 'employee_photo_labor') {
                        var labor = personnel[key].split('|');
                        for(var i in labor) {
                            if(labor[i] != '') {
                                var li = '<li class="span2"><a id="photo_labor" class="thumbnail lightbox_trigger" href="'
                                         +labor[i]+'"><img src="'+labor[i]+'" /></a></li>';
                                $('#labor_list').append(li);
                            }
                        }
                    }
                    if(key == 'employee_positive_id_card') { 
                        $('#positive_id_card').attr('href', personnel[key]);
                        $('#positive_id_card img').attr('src', personnel[key]);
                    }
                    if(key == 'employee_back_id_card') {
                        $('#back_id_card').attr('href', personnel[key]);
                        $('#back_id_card img').attr('src', personnel[key]);
                    }
                }
            }
            employee.addEmployee = function() {
                var zTree = $.fn.zTree.getZTreeObj(EmployeeClass.ZTreeObj['id']),
                nodes = zTree.getSelectedNodes(),
                treeNode = nodes[0];
                if (nodes.length == 0) {
                    //alert("请先选择一个节点");
                    $('#modal_info_message').html('请先选择要添加员工的职位！');
                    $('#modal_info').modal('show');
                    return;
                }
				var parentNode = treeNode.getParentNode();
				if(parentNode == null) {
                    $('#modal_info_message').html('无法在此添加员工');
                    $('#modal_info').modal('show');
                    return;
                }
                if(!isNaN(treeNode.id)) {
                    $('#modal_info_message').html('无法在此添加员工！此节点不是职位');
                    $('#modal_info').modal('show');
                    return;
                }
                $('#employee_page').hide('fast');
                $('#employee_add').show('fast');
                editor.uploadJson = uploadJsonUrl + '&images_type=avatar';
                editor.fileManagerJson = fileManagerJsonUrl + '&images_type=avatar';
                $('#add_employee_form input,#add_employee_form select').val('');
                $('#department').val(treeNode.pId);
                $('#department_id').val(parentNode.name);
                $('#department_position_id').val(treeNode.name);
                var position = treeNode.id.replace('P', '');
                $('#department_position').val(position);
                EmployeeClass.employee_id = '';
                $('#employee_images_url img').attr('src', '');
            };
            employee.beforeClick = function(treeId, treeNode, clickFlag) {
                return (treeNode.click != false);
            };
            employee.onClick = function(event, treeId, treeNode, clickFlag) {
                var parentNode = treeNode.getParentNode();
                var position = treeNode.id.replace('P', '');
                $('#department').val(treeNode.pId);
                $('#department_id').val(parentNode.name);
                $('#department_position_id').val(treeNode.name);
                $('#department_position').val(position);
                employee.setRole(position);
            };
            employee.setRole = function(position) {
                var option = '<option value="0"><%$arrayLaguage["please_select"]["page_laguage_value"]%></option>';
                var role = EmployeeClass.role;
                if(typeof(role[position]) == 'undefined') {
                } else {
                    for(var role_id in role[position]) {
                        option +='<option value="'+role_id+'">'+role[position][role_id]+'</option>';
                    }
                }
                $('#role_id').html(option);
            };
            employee.editEmployee = function(_this) {
                var employee_id = $(_this).attr('data-id');
                EmployeeClass.employee_id = employee_id;
                if(EmployeeClass.uploadJsonUrl == '') EmployeeClass.uploadJsonUrl = uploadJsonUrl;
                if(EmployeeClass.fileManagerJsonUrl == '') EmployeeClass.fileManagerJsonUrl = fileManagerJsonUrl;
                editor.fileManagerJson = EmployeeClass.fileManagerJsonUrl + '&employee_id='+employee_id;
                editor.uploadJson = EmployeeClass.uploadJsonUrl + '&employee_id='+employee_id;
                $('#myTab a:first').tab('show');
                if(typeof(EmployeeClass.employee_list[employee_id]) == 'undefined' || EmployeeClass.employee_list[employee_id] == '') {
                    $('#modal_loading').modal('show');
                    $.getJSON('<%$view_url%>&employee_id='+employee_id, function(result) {
                        data = result;
                        $('#modal_loading').modal('hide');
                        if(data.success == 1) {
                             employee.setEmployeeInfo(data.itemData[0], _this, employee_id);
                        } else {
                            $('#modal_fail').modal('show');
                            $('#modal_fail_message').html(data.message);
                        }
                    })
                } else {
                    employee.setEmployeeInfo(EmployeeClass.employee_list[employee_id], _this, employee_id);
                }
            };
            employee.setEmployeeInfo = function(employeeInfo, _this, employee_id) {
                $('#add_employee_form input,#add_employee_form select').val('');
                $('#employee_page').hide('fast');
                $('#employee_add').show('fast');
                $('#employee_name').val(employeeInfo.employee_name);
                $('#employee_sex').val(employeeInfo.employee_sex);
                $('#employee_birthday').val(employeeInfo.employee_birthday);
                $('#employee_mobile').val(employeeInfo.employee_mobile);
                $('#upload_images_url').val(employeeInfo.employee_photo);
                $('#employee_images_url').attr('href', '<%$__IMGWEB%>'+ employeeInfo.employee_photo);
                $('#employee_images_url img').attr('src', '<%$__IMGWEB%>' + employeeInfo.employee_photo);
                $('#department_id').val($.trim($(_this).find('.department_name').text()));
                $('#department').val($(_this).find('.department_name').attr('data-id'));
                $('#department_position_id').val($.trim($(_this).find('.department_position_name').text()));
                var position = $(_this).find('.department_position_name').attr('data-id');
                $('#department_position').val(position);
                $('#employee_id').val(employeeInfo.employee_id);
                employee.setRole(position);
                $('#role_id').val(employeeInfo.role_id);
                $('#employee_email').val(employeeInfo.employee_email);
                $('#employee_weixin').val(employeeInfo.employee_weixin);
                EmployeeClass.employee_list[employee_id] = employeeInfo;
            };
            employee.setEmployeeLabor = function(src) {
                var li = '<li class="span2"><a id="photo_labor" class="thumbnail lightbox_trigger" href="'+src+'"><img src="'+src+'" /></a></li>';
                $('#labor_list').append(li);//employee_photo_labor
                var labor = $('#employee_photo_labor').val().split('|');
                var labor_list = {};
                for(var i in labor) {
                    labor_list[labor[i]] = labor[i];
                }
                var labor_val = '';
                for(var url in labor_list) {
                    labor_val += url + '|';
                }
                labor_val = labor_val + src;
                $('#employee_photo_labor').val(labor_val);
            }
            return employee;		
        }
    }
    var employee = EmployeeClass.instance();
    employee.initParameter();
    employee.init();


	KindEditor.ready(function(K) {
		editor = K.editor({
			uploadJson : uploadJsonUrl,
			fileManagerJson : fileManagerJsonUrl,
			allowFileManager : true
		});
		K('#positive_id_card_images').click(function() {
            var employee_id = $.getUrlParam(editor.fileManagerJson, 'employee_id');
            editor.fileManagerJson =  fileManagerJsonUrl + '&employee_id=' + employee_id + '&images_type=id_card';
            editor.uploadJson = uploadJsonUrl + '&employee_id=' + employee_id + '&images_type=id_card';
			editor.loadPlugin('image', function() {
				editor.plugin.imageDialog({
					imageUrl : K('#positive_id_card_images').val(),
					clickFn : function(url, title, width, height, border, align) {
                        $('#positive_id_card').attr('href', url);
                        $('#positive_id_card img').attr('src', url);
                        $('#employee_positive_id_card').val(url);
						editor.hideDialog();
					}
				});
			});
		});
        K('#back_id_card_images').click(function() {
            var employee_id = $.getUrlParam(editor.fileManagerJson, 'employee_id');
            editor.fileManagerJson = fileManagerJsonUrl + '&employee_id=' + employee_id + '&images_type=id_card';
            editor.uploadJson = uploadJsonUrl + '&employee_id=' + employee_id + '&images_type=id_card';
			editor.loadPlugin('image', function() {
				editor.plugin.imageDialog({
					imageUrl : K('#back_id_card_images').val(),
					clickFn : function(url, title, width, height, border, align) {
						$('#back_id_card').attr('href', url);
                        $('#back_id_card img').attr('src', url);
                        $('#employee_back_id_card').val(url);
						editor.hideDialog();
					}
				});
			});
		});
        K('#photo_labor_images').click(function() {
            var employee_id = $.getUrlParam(editor.fileManagerJson, 'employee_id');
            editor.fileManagerJson = fileManagerJsonUrl + '&employee_id=' + employee_id + '&images_type=labor';
            editor.uploadJson = uploadJsonUrl + '&employee_id=' + employee_id + '&images_type=labor';
			editor.loadPlugin('image', function() {
				editor.plugin.imageDialog({
					imageUrl : K('#photo_labor_images').val(),
					clickFn : function(url, title, width, height, border, align) {
						employee.setEmployeeLabor(url);
						editor.hideDialog();
					}
				});
			});
		});
        K('#employee_photo').click(function() {
            var employee_id = $.getUrlParam(editor.fileManagerJson, 'employee_id');
            editor.fileManagerJson = fileManagerJsonUrl + '&employee_id=' + employee_id + '&images_type=avatar';
            editor.uploadJson = uploadJsonUrl + '&employee_id=' + employee_id + '&images_type=avatar';
			editor.loadPlugin('image', function() {
				editor.plugin.imageDialog({
					imageUrl : K('#photo_labor_images').val(),
					clickFn : function(url, title, width, height, border, align) {
                        $('#employee_images_url').attr('href', url);
                        $('#employee_images_url img').attr('src', url);
						$('#upload_images_url').val(url);
						editor.hideDialog();
					}
				});
			});
		});
        
	});
});//console.log($('#add_user_tr'));

function uploadSuccess(url, title) {
    //$('#upload_images_url').val(url);
}
//-->
</script>