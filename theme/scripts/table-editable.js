var TableEditable = function () {

	var currentpage = 1;
	var tablebody = '';
	var operatenode = '';

	// 初始化选择按钮
	var initShowRecord = function(){
		jQuery('#table_show_record input').addClass("form-control input-medium");
		jQuery('#table_show_record select').addClass("form-control input-small");
		jQuery('#table_show_record select').select2({
			showSearchInput : false
		});
	};

	// 初始化表格
	var initTableData = function(num) {
		if ( tablebody == '' )	tablebody = $('#list_table tbody');
		if ( operatenode == '' )	operatenode = $('#list_table .table-operate-tr .table-operate').html();
		var	url = $('#list_table').data('source');
		var isSelect = $('#list_table').data('select');
		var operatekey = $('#list_table').data('key');
		var	pagenum = num;
		var $tablebody = tablebody;
		var $operatenode = operatenode;

		$('#list_table .table-operate-tr').remove();

		// 查找
		var getSubmitObj = function(page,size) {
			var dataObj = {
				page : parseInt(page),
				size : parseInt(size),
			};
			$('.search-form .form-control').each(function(){
				var key = $(this).attr('name');
				var value = $(this).val();
				dataObj[key] = value;
			});
			return dataObj;
		}

		// 分页
		var getPageInfo = function(totalpage,pagenum) {
			var labeltotal = 5,	// 必须奇数
				medium	=	(labeltotal+1)/2;	// 中间位置
			var $pagination = $('.pagination'),
				$preNode = '<li class="prev"><a href="javascript:;" title="Prev"><i class="icon-angle-left"></i></a></li>',
				$nextNode = '<li class="next"><a href="javascript:;" title="Next"><i class="icon-angle-right"></i></a></li>',
				$pageNode = '';

			$pagination.html('');
			$pagination.append($preNode);
			// 页码标签
			if ( totalpage < (labeltotal+1) ) {
				for (var i = 1; i <= totalpage; i++) {
					if (i==pagenum) {
						$pageNode = '<li class="active"><a href="javascript:;">'+i+'</a></li>';
					} else {
						$pageNode = '<li id="page'+i+'" class="page"><a href="javascript:;">'+i+'</a></li>';
					}
					$pagination.append($pageNode);
					$('#page'+i).data('page', i);
				}
			} else {
				if (pagenum<=medium) {
					for (var i = 1; i <= labeltotal-1; i++) {
						if (i==pagenum) {
							$pageNode = '<li class="active"><a href="javascript:;">'+i+'</a></li>';
						} else {
							$pageNode = '<li id="page'+i+'" class="page"><a href="javascript:;">'+i+'</a></li>';
						}
						$pagination.append($pageNode);
						$('#page'+i).data('page', i);
					}
					$pageNode = '<li class="disabled"><a href="javascript:;">...</a></li>';
					$pagination.append($pageNode);
				} else if (medium < pagenum && pagenum < (totalpage-medium+1)) {
					$pageNode = '<li class="disabled"><a href="javascript:;">...</a></li>';
					$pagination.append($pageNode);
					var offset = (labeltotal-3)/2;
					for (var i = pagenum-offset; i <= pagenum+offset; i++) {
						if (i==pagenum) {
							$pageNode = '<li class="active"><a href="javascript:;">'+i+'</a></li>';
						} else {
							$pageNode = '<li id="page'+i+'" class="page"><a href="javascript:;">'+i+'</a></li>';
						}
						$pagination.append($pageNode);
						$('#page'+i).data('page', i);
					}
					$pageNode = '<li class="disabled"><a href="javascript:;">...</a></li>';
					$pagination.append($pageNode);
				} else {
					$pageNode = '<li class="disabled"><a href="javascript:;">...</a></li>';
					$pagination.append($pageNode);
					for (var i = totalpage-labeltotal+2; i <= totalpage; i++) {
						if (i==pagenum) {
							$pageNode = '<li class="active"><a href="javascript:;">'+i+'</a></li>';
						} else {
							$pageNode = '<li id="page'+i+'" class="page"><a href="javascript:;">'+i+'</a></li>';
						}
						$pagination.append($pageNode);
						$('#page'+i).data('page', i);
					}
				}
			}
			$pagination.append($nextNode);
			// 上一页标签
			if (pagenum==1) $('.pagination .prev').addClass('disabled');
			// 下一页标签
			if (pagenum==totalpage) $('.pagination .next').addClass('disabled');

			// 点击页码
			$('.pagination .page').on('click', function(){
				var topage = $(this).data('page');
				getTableData(topage,$('#pagesize').val());
			});

			// 点击上一页
			$('.pagination .prev').on('click', function(){
				if (pagenum>1) {
					getTableData(pagenum-1,$('#pagesize').val());
				}
			});

			// 点击下一页
			$('.pagination .next').on('click', function(){
				if (pagenum<totalpage) {
					getTableData(pagenum+1,$('#pagesize').val());
				}
			});
		}

		var initCheckboxEvent = function(){
			// 先解绑
			$('#list_table').off('.selectall');
			// 再重新绑定
			$('#list_table').on('mouseover.selectall', 'tbody div.checker', function(){
				$(this).addClass('hover');
			});
			$('#list_table').on('mouseout.selectall', 'tbody div.checker', function(){
				$(this).removeClass('hover');
			});
			$('#list_table').on('focusin.selectall', 'tbody div.checker', function(){
				$(this).addClass('focus');
			});
			$('#list_table').on('focusout.selectall', 'tbody div.checker', function(){
				$(this).removeClass('focus');
			});
			$('#list_table').on('click.selectall', 'tbody div.checker span', function(){
				if ( $(this).hasClass('checked') ) {
					$(this).removeClass('checked');
					$(this).children('input[type=checkbox]').attr('checked', false);
				} else {
					$(this).addClass('checked');
					$(this).children('input[type=checkbox]').attr('checked', true);
				}
			});
		}

		// 获取表格数据
		var getTableData = function(page,size) {
			var submitObj = getSubmitObj(page,size);
			$.post(url, submitObj, function(data){
				if (data.status) {
					$tablebody.html('');
					$('.from-record').text(data.data.start+1);
					if (data.data.start+data.data.size > data.data.total) {
						$('.to-record').text(data.data.total);
					} else {
						$('.to-record').text(data.data.start+data.data.size);
					}
					
					$('.total-record').text(data.data.total);
					for (var i in data.data.rows) {
						var row = '<tr>';
						if (isSelect>0) row += '<td><div class="checker"><span><input class="select-row" type="checkbox" data-id="'+data.data.rows[i][operatekey]+'"></span></div></td>';
						// if (isSelect>0) row += '<td><input class="select-row" type="checkbox" data-id="'+data.data.rows[i][operatekey]+'"></td>';
						for (var j in data.data.rows[i]) {
							row += '<td>'+data.data.rows[i][j]+'</td>';
						};
						if ($operatenode!=undefined && $operatenode!='') row += '<td class="row'+i+'">'+$operatenode+'</td></tr>';
						$tablebody.append(row);
						$('#list_table .row'+i+' a').each(function() {
							$(this).data('id',data.data.rows[i][operatekey]);
						});
					}
					currentpage = data.data.pagenum;
					// 分页标签
					getPageInfo(data.data.pagetotal,data.data.pagenum);
					// checkbox事件注册
					if (isSelect>0) initCheckboxEvent();
				} else {
					alert(data.msg);
				}
			}, 'json');
		}

		$('#pagesize').change(function(){
			getTableData(pagenum,$(this).val());
		});

		if (url!='' && url!=undefined) getTableData(pagenum,$('#pagesize').val());
	}

	return {
		init: function () {
			initShowRecord();
			initTableData(1);
		},
		refreshTable: function() {
			initTableData(currentpage);
		},
		search: function() {
			initTableData(1);
		}
	};

}();