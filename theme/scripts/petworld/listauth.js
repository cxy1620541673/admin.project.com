$(function(){

	TableEditable.init();

	// 编辑按钮
	$('#list_table').on('click', '.edit', function(){
		window.location.href='/Authority/EditAuth?auth_id='+$(this).data('id');
	});

	// 删除按钮
	$('#list_table').on('click', '.del', function(){
		var $obj = $(this),
			$trobj = $obj.parent().parent();
		if (confirm('确定要删除此记录吗？')) {
			$.post('/Authority/AjaxDelAuth', {
				auth_id : $obj.data('id')
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

	// 全选按钮
	$('#list_table th input.select-all').click(function(){
		$(this).parents("#list_table").find('tbody input.select-row').click();
	});

});