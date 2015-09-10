var FormValidation = function () {

	// 获取表单规则和提示信息
	var getformrequired = function(form) {

		var rules = {};		// 表单规则，判断是否必须
		var messages = {};	// 表单提示信息，不符合规则时提示

		// 检查表单是否有该属性
		var checkvalue = function(value) {
			if (value!=undefined && value!='' && value!=null) {
				return true;
			}
			return false;
		} 
		var $inputs = $(form).find('.form-control');	// 获取表单所有要提交的字段
		var name = [];
		var data = [];
		// 获取表单required信息
		$inputs.each(function(){
			var dval = $(this).data('required');
			var nval = $(this).attr('name');
			if (checkvalue(dval)) {
				name[name.length] = nval;
				data[data.length] = dval;
			}
		});
		// 设置表单规则
		for (var i = 0; i < name.length; i++) {
			rules[name[i]] = {required:true};
			messages[name[i]] = {required:data[i]};
		};
		return {rules:rules,messages:messages};
	};

	var getformdata = function(form){
		var $inputs = $(form).find('.form-control');	// 获取表单所有要提交的字段
		var values = {};
		// 获取表单data
		$inputs.each(function(){
			if ($(this).attr('type')=='checkbox') {
				if ($(this).is(':checked')) {
					values[$(this).attr('name')] = 1;
				} else {
					values[$(this).attr('name')] = 0;
				}
			} else {
				values[$(this).attr('name')] = $(this).val();
			}
		});
		// console.dir(values);
		return values;
	}

	// 表单验证
	var validateform = function() {
		var $form = $('form');
		var $error = $('.alert-danger', $form);
		var $success = $('.alert-success', $form);

		$form.each(function(){
			var $dealform = $(this);
			var formdata = getformrequired($dealform);
			// console.dir(formdata);
			$(this).validate({
				errorElement: 'span',
				errorClass: 'help-block',
				focusInvalid: false,
				rules : formdata.rules,
				messages : formdata.messages,
				ignore : '',
				invalidHandler: function (event, validator) { 
					$('.alert-error', $dealform).show();
				},
				highlight: function (element) {
					$(element)
						.closest('.form-group').addClass('has-error');
					},
				success: function (label) {
					label.closest('.form-group').removeClass('has-error');
					label.remove();
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.closest('.error-tips'));
				},
				submitHandler: function (form) {
					// dealform.submit();	// 提交表单
					var url = $dealform.attr('action'),
						formdata = getformdata($dealform);
					// console.dir(formdata);
					$.post(url, formdata, function(data){
						alert(data.msg);
						if (data.status) {
							document.location.reload();
						}
					}, 'json');
					// console.dir(formdata.data);
				}
			});

			$(this).find('.sm-btn').click(function(){
				if ($dealform.validate().form()) {
					$dealform.submit();
				}
			});
		});
	};

	return {
		init : function() {
			validateform();
		}
	}
}();