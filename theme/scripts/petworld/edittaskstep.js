$(function(){

	// 初始化编辑器
	var editor;
	KindEditor.ready(function(K){
		editor = K.create('#t_content', {
			width			: '100%',
			height			: '600px',
			resizeType		: 1,
			syncType		: 'auto',
			allowFileManager: false,
			uploadJson		: '/Task/AjaxUploadImage',
			afterChange 	: function(){
				K.sync('#t_content');
			},
		});
		$('.ke-container').hide();
	});
	

	// 初始化编辑按钮
	$('.edit-btn').data('status', 1);

	// 编辑按钮点击
	$('.edit-btn').click(function(){
		var $btn = $(this);
		var $container = $('.pre-content');
		var $content = $('#t_content');
		var status = parseInt( $btn.data('status') );
		if ( status ) {	// 进入编辑状态
			$btn.data('status', 0);
			$btn.text('退出编辑');
			$container.hide();
			$('.ke-container').show();
		} else {		// 退出编辑状态
			$btn.data('status', 1);
			$btn.text('进入编辑');
			$container.html($content.val());
			$('.ke-container').hide();
			$container.show();
		}
	});

	// 自动保存
	$('.portlet-title .tips').hide();
	function autosave() {
		var data = {};
		var $form = $('#edittaskstep-form');
		var url = $form.attr('action');
		var $formdata = $form.find('.form-control');
		var $tips = $('.portlet-title .tips');
		setTimeout(function(){
			$formdata.each(function() {
				data[$(this).attr('name')] = $(this).val();
			});
			$.post( url, data, function(d){
				if (d.status) {
					$tips.text('自动保存成功...');
					$tips.show(1000);
					setTimeout(function(){
						$tips.hide(1000);
						$tips.text('');
					}, 10000);
				};
			}, 'json' );
			autosave();
		}, 300000);
	}
	autosave();

});