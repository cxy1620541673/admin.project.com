$(function(){

	TableEditable.init();

	// 编辑按钮
	$('#list_table').on('click', '.edit', function(){
		window.location.href='/Admin/EditAdmin?admin_id='+$(this).data('id');
	});

	// 删除按钮
	$('#list_table').on('click', '.del', function(){
		var $obj = $(this),
			$trobj = $obj.parent().parent();
		if (confirm('确定要删除此记录吗？')) {
			$.post('/Admin/AjaxDelAdmin', {
				admin_id : $obj.data('id')
			}, function(data){
				alert(data.msg);
				if (data.status) {
					TableEditable.refreshTable();
				}
			}, 'json');
		}
	});

	// 查找按钮
	$('.btn-search').click(function(){
		TableEditable.search();
	});

	// 权限分配按钮
	$('#list_table').on('click', '.allot', function(){
		window.location.href='/Admin/AllotAuth?admin_id='+$(this).data('id');
	});

	// 关闭模态框
	$('.sm-modal').click(function(){
		// $('#stack1').modal('hide');
	});

});