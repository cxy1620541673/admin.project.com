<link rel="stylesheet" type="text/css" href="/theme/plugins/select2/select2_metro.css"/>
<link rel="stylesheet" type="text/css" href="/theme/plugins/data-tables/DT_bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="/theme/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css"/>
<link rel="stylesheet" type="text/css" href="/theme/plugins/bootstrap-modal/css/bootstrap-modal.css"/>
<script type="text/javascript" src="/theme/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/theme/plugins/bootstrap-modal/js/bootstrap-modalmanager.js"></script>
<script type="text/javascript" src="/theme/plugins/bootstrap-modal/js/bootstrap-modal.js"></script>
<script type="text/javascript" src="/theme/scripts/table-editable.js"></script>
<script type="text/javascript" src="/theme/scripts/petworld/listtask.js"></script>

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title"> 任务列表 </h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="/index">首页</a> 
			</li>
			<li>
				<i class="icon-angle-right"></i>
				<a href="/Task/ListTask">项目管理</a> 
			</li>
			<li>
				<i class="icon-angle-right"></i>
				<a href="/Task/ListTask">任务列表</a> 
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
				<a href="/Task/AddTask"><span class="btn btn-info add-project"><i class="icon-plus"></i> 添加任务 </span></a>
				&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			<div class="form-group">
				<label>执行者：</label>
				<select class="form-control" id="t_executor_id" name="t_executor_id">
					<option value="0">请选择</option>
					<?php foreach ($executor as $v) { ?>
					<option value="<?php echo $v['admin_id'] ?>"><?php echo $v['admin_name'] ?></option>
					<?php } ?>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;
			</div>
			<div class="form-group">
				<label>任务名称：</label>
				<input type="text" class="form-control" name="t_title">
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
		<table class="table table-hover table-bordered" id="list_table" data-source="/Task/AjaxGetTaskList" data-select="0" data-key="t_id">
			<thead>
				<tr>
					<!-- <th><input type="checkbox" class="select-all"/></th> -->
					<th>任务ID</th>
					<th>任务标题</th>
					<th>执行者</th>
					<th>开始时间</th>
					<th>结束时间</th>
					<th>状态</th>
					<th>创建时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<tr class="table-operate-tr">
					<td>
						<i class="icon-hand-right pointer"></i>
					</td>
					<td>
						<span>未指派</span>
						[ <a href="#">开始</a> | <a href="#">完成</a> | <a href="#">放弃</a> ]
					</td>
					<td class="table-operate">
						<a class="step" href="javascript:;">详细步骤</a>
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

<div id="stack1" class="modal fade" tabindex="-1" data-focus-on="input:first">
	<form class="form-horizontal" id="appoint-form" action="/Task/AjaxUpdateTaskStatus">
		<input type="hidden" class="form-control" id="modal_t_status" name="t_status" value="1">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title">指派任务执行者</h4>
		</div>
		<div class="modal-body">
				<div class="form-group">
					<label class="col-sm-3 control-label"><font color="red"> * </font>任务标题</label>
					<div class="col-sm-7 error-tips">
						<input type="hidden" class="form-control" id="modal_t_id" name="t_id">
						<input type="text" class="form-control" id="modal_t_title" name="t_title" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><font color="red"> * </font>执行者</label>
					<div class="col-sm-7 error-tips">
						<select class="form-control" id="modal_t_executor_id" name="t_executor_id" data-required="必选">
							<option value="">请选择</option>
							<?php foreach ($executor as $v) { ?>
							<option value="<?php echo $v['admin_id'] ?>"><?php echo $v['admin_name'] ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
		</div>
		<div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn btn-default">关闭</button>
			<button type="button" class="btn btn-primary sm-modal sm-btn" data-formid="appoint-form">提交</button>
		</div>
	</form>
</div>