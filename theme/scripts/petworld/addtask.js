$(function(){

	// 初始化编辑器
	var editor;
	KindEditor.ready(function(K){
		editor = K.create('#t_desc', {
			width			: '100%',
			height			: '300px',
			resizeType		: 1,
			syncType		: 'auto',
			allowFileManager: false,
			uploadJson		: '/Task/AjaxUploadImage',
			afterChange 	: function(){
				K.sync('#t_desc');
			},
		});
	});

	// 初始化多选框
	$('#p_id').select2({
		placeholder	: "选择项目",
		allowClear	: true,
	});

	// var $select = $("#p_id").data("select2");
	// $select.addSelectedChoice({id : '1',text:'adf'});

	// 初始化日期选择器
	$(".from_datetime").datetimepicker({
		autoclose		: true,
		language		: 'zh-CN',
		isRTL			: App.isRTL(),
		format			: "yyyy-mm-dd hh:ii:ss",
		pickerPosition	: (App.isRTL() ? "bottom-right" : "bottom-left")
	});

	// 初始化日期选择器
	$(".to_datetime").datetimepicker({
		autoclose		: true,
		isRTL			: App.isRTL(),
		format			: "yyyy-mm-dd hh:ii:ss",
		pickerPosition	: (App.isRTL() ? "bottom-right" : "bottom-left")
	});

});