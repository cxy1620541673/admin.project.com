<link rel="stylesheet" type="text/css" href="/theme/plugins/select2/select2_metro.css" />
<link rel="stylesheet" href="/theme/plugins/data-tables/DT_bootstrap.css" />
<script type="text/javascript" src="/theme/plugins/select2/select2.min.js"></script>
<script src="/theme/scripts/table-editable.js"></script>
<script src="/theme/scripts/petworld/listauth.js"></script>

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title"> 权限列表 </h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="/index">首页</a> 
			</li>
			<li>
				<i class="icon-angle-right"></i>
				<a href="/Authority/ListAuth">权限管理</a> 
			</li>
			<li>
				<i class="icon-angle-right"></i>
				<a href="/Authority/ListAuth">权限列表</a> 
			</li>
		</ul>
	</div>
</div>

<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption"><i class="icon-edit"></i>列表</div>
	</div>
	<div class="portlet-body">
		<form class="form-inline search-form">
			<div class="form-group">
				<label>权限类型：</label>
				<select class="form-control" name="auth_type">
					<option value="-1">所有</option>
					<option value="0">菜单权限</option>
					<option value="1">操作权限</option>
					<option value="2">页面权限</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			<div class="form-group">
				<label>父级权限：</label>
				<select class="form-control" name="auth_parent">
					<option value="-1">所有</option>
					<option value="0">根权限</option>
					<?php foreach ($parent_auth as $k => $v) { ?>
						<option value="<?php echo $v['auth_id']; ?>"><?php echo $v['auth_name']; ?></option>
					<?php } ?>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			<div class="form-group">
				<label>权限名称：</label>
				<input type="text" class="form-control" name="auth_name">
				&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			<button type="button" class="btn btn-success btn-search">&nbsp;&nbsp;搜索&nbsp;&nbsp;</button>
		</form>
		<br>
		<div class="row">
			<div class="col-md-6 col-sm-12">
				<div id="table_show_record" class="dataTables_length">
					<label>
						<select id="pagesize" size="1" aria-controls="sample_editable_1">
							<option value="5" selected="selected">5</option>
							<option value="10">10</option>
							<option value="15">15</option>
							<option value="20">20</option>
							<option value="-1">所有</option>
						</select>  条 / 页
					</label>
				</div>
			</div>
		</div>
		<table class="table table-hover table-bordered" id="list_table" data-source="/Authority/AjaxGetAuthList" data-select="0" data-key="auth_id">
			<thead>
				<tr>
					<!-- <th><input type="checkbox" class="select-all"/></th> -->
					<th>权限ID</th>
					<th>权限名称</th>
					<th>权限类型</th>
					<th>父级权限</th>
					<th>权限链接</th>
					<th>添加时间</th>
					<th>排序号</th>
					<th>权限描述</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<tr class="table-operate-tr">
					<td class="table-operate">
						<a class="edit" href="javascript:;">编辑</a>
						<a class="del" href="javascript:;">删除</a>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="row table-pager">
			<div class="col-md-5 col-sm-12">
				<div class="dataTables_info" id="show_table_info">
					<span class="from-record">1</span> ~ 
					<span class="to-record">5</span> | 
					共 <span class="total-record">0</span> 条记录
				</div>
			</div>
			<div class="col-md-7 col-sm-12">
				<div class="dataTables_paginate paging_bootstrap">
					<ul class="pagination">
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>