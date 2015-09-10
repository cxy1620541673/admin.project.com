<link rel="stylesheet" type="text/css" href="/theme/plugins/select2/select2_metro.css" />
<link rel="stylesheet" href="/theme/plugins/data-tables/DT_bootstrap.css" />
<script type="text/javascript" src="/theme/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/theme/scripts/table-editable.js"></script>
<script type="text/javascript" src="/theme/scripts/petworld/listproject.js"></script>

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title"> 项目列表 </h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="/index">首页</a> 
			</li>
			<li>
				<i class="icon-angle-right"></i>
				<a href="/Project/ListProject">项目管理</a> 
			</li>
			<li>
				<i class="icon-angle-right"></i>
				<a href="/Project/ListProject">项目列表</a> 
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
				<a href="/Project/AddProject"><span class="btn btn-info add-project"><i class="icon-plus"></i> 添加项目 </span></a>
				&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			<div class="form-group">
				<label>项目名称：</label>
				<input type="text" class="form-control" name="p_name">
				&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			<div class="form-group">
				<label>域名：</label>
				<input type="text" class="form-control" name="p_domain">
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
		<table class="table table-hover table-bordered" id="list_table" data-source="/Project/AjaxGetProjectList" data-select="0" data-key="p_id">
			<thead>
				<tr>
					<!-- <th><input type="checkbox" class="select-all"/></th> -->
					<th>项目ID</th>
					<th>项目名称</th>
					<th>域名</th>
					<th>创建者</th>
					<th>描述</th>
					<th>项目路径</th>
					<th>项目归属</th>
					<th>创建时间</th>
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