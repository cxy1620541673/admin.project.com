$(function(){

	// 初始化列表
	TableEditable.init();

	// 查找按钮
	$('.btn-search').click(function(){
		TableEditable.search();
	});

	// 编辑按钮
	$('#list_table').on('click', '.edit', function(){
		window.location.href='/Task/EditTask?t_id='+$(this).data('id');
	});

	// 详细步骤按钮
	$('#list_table').on('click', '.step', function(){
		window.location.href='/Task/EditTaskStep?t_id='+$(this).data('id');
	});

	// 删除按钮
	$('#list_table').on('click', '.del', function(){
		var $obj = $(this),
			$trobj = $obj.parent().parent();
		if (confirm('确定要删除此记录吗？')) {
			$.post('/Task/AjaxDelTask', {
				t_id : $obj.data('id')
			}, function(data){
				alert(data.msg);
				if (data.status) {
					TableEditable.refreshTable();
				}
			}, 'json');
		}
	});

	// 修改状态按钮
	$('#list_table').on('click', '.edit-status', function(){
		var val = $(this).data('val'),
			id = $(this).parent().data('id');
		if (confirm('确定要更改此状态吗？')) {
			$.post('/Task/AjaxUpdateTaskStatus', {
				t_id : id,
				t_status : val,
			}, function(data){
				alert(data.msg);
				if (data.status) {
					TableEditable.refreshTable();
				}
			}, 'json');
		}
	});

	// 指派执行者
	$('#list_table').on('click', '.appoint', function(){
		$('#modal_t_id').val($(this).data('id'));
		$('#modal_t_title').val($(this).data('title'));
	});

});