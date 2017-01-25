/**
 * Unicorn Admin Template
 * Diablo9983 -> diablo9983@gmail.com
**/
$(document).ready(function(){
	
	$("#hotel_form").formwizard({ 
		formPluginEnabled: true,
		validationEnabled: true,
		focusFirstInput : true,
		disableUIStyles : true,
	
		formOptions :{
			success: function(data){$("#status").fadeTo(500,1,function(){ $(this).html("<span>Form was submitted!</span>").fadeTo(5000, 0); })},
			beforeSubmit: function(data){$("#submitted").html("<span>Form was submitted with ajax. Data sent to the server: " + $.param(data) + "</span>");},
			dataType: 'json',
			resetForm: true
		},
		validationOptions : {
			rules: {
				hotel_name:{
					required:true
				},
				hotel_province:{
					required:true
				},
				hotel_mobile:{
					required:true,
					number:true,
					isMobile:true
				},
				address:{
					required:true,
					minlength:5,
				}
			},
			messages: {
				hotel_name:"请输入酒店名称",
				hotel_province:"",
				hotel_mobile:"请输入正确移动电话号码",
				address:"请输入酒店地址"
			},
			errorClass: "help-inline",
			errorElement: "span",
			highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.control-group').removeClass('error');
			}
		}
	});	
});
