$(function(){

	var adminid = $('#auth_table').data('adminid');
	var myid = $('.header-inner .username').data('adminid');
	var $selectall = $('#auth_table .selectall');
	var $selectrow = $('#auth_table .selectrow');
	var $selectone = $('#auth_table .selectone');

	$selectall.click(function() {
		if ($(this).is(':checked')) {
			$selectrow.each(function(){
				if (!$(this).is(':checked')) {
					$(this).click();
				}
			});
		} else {
			$selectrow.each(function(){
				if ($(this).is(':checked')) {
					$(this).click();
				}
			});
		};
	});

	$selectrow.click(function(){
		if ($(this).is(':checked')) {
			$(this).parents('#auth_table tr').find('.selectone').each(function() {
				if (!$(this).is(':checked')) {
					$(this).click();
				}
			});
		} else{
			$(this).parents('#auth_table tr').find('.selectone').each(function() {
				if ($(this).is(':checked')) {
					$(this).click();
				}
			});
		};
	});

	$selectone.click(function(){
		var change = -1;
		var value = $(this).val();

		$('.scrolllabel'+value).append('<i class="icon-spinner icon-spin"></i>');
		if ($(this).is(':checked')) {
			change = 1;
		} else {
			change = 0;
		}
		$.post('/Admin/AjaxChangeAdminRelation', {
			change:change,
			auth_id:value,
			admin_id:adminid,
		}, function(data){
			setTimeout(function(){$('.scrolllabel'+value+' i').remove();}, 500);
			if (!data.status) {
				popfunc(data.msg);
			};
		}, 'json');
	});

	// 延迟响应
	var time = '';
	var popfunc = function(msg) {
		clearTimeout(time);
		time = setTimeout(function(){
			alert(msg);
			window.location.reload();
		}, 500);
	}

});