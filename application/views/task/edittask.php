<link rel="stylesheet" type="text/css" href="/theme/plugins/kindeditor/themes/default/default.css" />
<link rel="stylesheet" type="text/css" href="/theme/plugins/select2/select2_metro.css" />
<link rel="stylesheet" type="text/css" href="/theme/plugins/bootstrap-datetimepicker/css/datetimepicker.css" />
<script type="text/javascript" src="/theme/plugins/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/theme/plugins/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="/theme/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/theme/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/theme/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript" src="/theme/scripts/petworld/addtask.js"></script>

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title"> 任务修改 </h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="/index">首页</a> 
			</li>
			<li>
				<i class="icon-angle-right"></i>
				<a href="/Task/ListTask">任务列表</a> 
			</li>
			<li>
				<i class="icon-angle-right"></i>
				<a href="/Task/EditTask?t_id=<?php echo $task_info['t_id']; ?>">任务修改</a> 
			</li>
		</ul>
	</div>
</div>

<form class="form-horizontal" id="edittask-form" action="/Task/AjaxEditTask">
	<input class="form-control" type="hidden" name="t_id" value="<?php echo $task_info['t_id']; ?>">
	<input class="form-control" type="hidden" name="t_status" value="<?php echo $task_info['t_status']; ?>">
	<div class="form-group">
		<label class="col-sm-1 control-label"><font color="red"> * </font>任务标题</label>
		<div class="col-sm-5 error-tips">
			<input type="text" class="form-control" id="t_title" name="t_title" data-required="必填" value="<?php echo $task_info['t_title']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-1 control-label">关联项目</label>
		<div class="col-sm-5 error-tips">
			<select class="form-control select2" id="p_id" name="p_id" multiple>
			<?php foreach ($project as $v) { ?>
				<option value="<?php echo $v['p_id'] ?>" <?php if ( in_array( $v['p_id'], $task_info['p_id'] ) ) echo "selected"; ?> ><?php echo $v['p_name'] ?></option>
			<?php } ?>
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label class="col-sm-4 control-label">执行者</label>
				<div class="col-sm-6 error-tips">
					<select class="form-control" id="t_executor_id" name="t_executor_id">
						<option value="0">请选择</option>
						<?php foreach ($executor as $v) { ?>
						<option value="<?php echo $v['admin_id'] ?>" <?php if ( $v['admin_id'] == $task_info['t_executor_id'] ) echo "selected"; ?>><?php echo $v['admin_name'] ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="col-sm-4 control-label">开始时间</label>
				<div class="col-sm-8 error-tips">
					<div class="input-group date from_datetime">
						<input type="text" class="form-control" id="t_start_time" name="t_start_time" size="16" readonly value="<?php if ( !empty( $task_info['t_start_time'] ) ) echo date( 'Y-m-d H:i:s', $task_info['t_start_time'] ); ?>">
						<span class="input-group-btn">
							<button class="btn default date-reset" type="button"><i class="icon-remove"></i></button>
						</span>
						<span class="input-group-btn">
							<button class="btn default date-set" type="button"><i class="icon-time"></i></button>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="col-sm-4 control-label">结束时间</label>
				<div class="col-sm-8 error-tips">
					<div class="input-group date to_datetime">
						<input type="text" class="form-control" id="t_end_time" name="t_end_time" size="16" readonly value="<?php if ( !empty( $task_info['t_end_time'] ) ) echo date( 'Y-m-d H:i:s', $task_info['t_end_time'] ); ?>">
						<span class="input-group-btn">
							<button class="btn default date-reset" type="button"><i class="icon-remove"></i></button>
						</span>
						<span class="input-group-btn">
							<button class="btn default date-set" type="button"><i class="icon-time"></i></button>
						</span>
					</div>
				</div>
			</div>
		</div>
		<!-- <div class="col-md-3">
			<div class="form-group">
				<label class="col-sm-4 control-label"></label>
				<span class="btn btn-primary">查看解决方案</span>
			</div>
		</div> -->
	</div>


	<div class="form-group">
		<label class="col-sm-1 control-label"><font color="red"> * </font>任务描述</label>
		<div class="col-sm-10 error-tips">
			<textarea class="form-control" id="t_desc" name="t_desc" data-required="必填"><?php echo $task_info['t_desc']; ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-2">
			<a href="/Task/ListTask" class="btn btn-warning">取消</a>
			<button type="button" class="btn btn-success sm-btn" data-formid="edittask-form">修改</button>
			<!-- <button type="button" class="btn btn-primary solution-btn">查看解决方案</button> -->
		</div>
	</div>
</form>