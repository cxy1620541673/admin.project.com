$(function(){

	// 初始化列表
	TableEditable.init();

	// 查找按钮
	$('.btn-search').click(function(){
		TableEditable.search();
	});

	// 编辑按钮
	$('#list_table').on('click', '.edit', function(){
		window.location.href='/Project/EditProject?p_id='+$(this).data('id');
	});

	// 删除按钮
	$('#list_table').on('click', '.del', function(){
		var $obj = $(this),
			$trobj = $obj.parent().parent();
		if (confirm('确定要删除此记录吗？')) {
			$.post('/Project/AjaxDelProject', {
				p_id : $obj.data('id')
			}, function(data){
				alert(data.msg);
				if (data.status) {
					TableEditable.refreshTable();
				}
			}, 'json');
		}
	});

});