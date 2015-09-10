$(function(){
	$('.del').click(function(){
		if (confirm('此等级的管理员也会一并删除，确定要删除此等级吗？')) {
			$.post('/Authority/AjaxDelLevel', {
				al_id : $(this).data('alid')
			}, function(data){
				alert(data.msg);
				if (data.status) {
					window.location.reload();
				};
			}, 'json');
		}
	});

	$('#level_auth_table tbody tr').mouseover(function(){
		var classname = $(this).attr('class');
		$('.'+classname).css( 'background-color', '#f5f5f5' );
	}).mouseout(function(){
		var classname = $(this).attr('class');
		$('.'+classname).css( 'background-color', '#ffffff' );
	});
});