<link rel="stylesheet" type="text/css" href="/theme/plugins/kindeditor/themes/default/default.css" />
<script type="text/javascript" src="/theme/plugins/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/theme/plugins/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="/theme/scripts/petworld/edittaskstep.js"></script>

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title"> 任务步骤 </h3>
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
				<a href="/Task/EditTaskStep?t_id=<?php echo $t_id; ?>">任务步骤</a> 
			</li>
		</ul>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption"><i class="icon-reorder"></i><font>任务: <?php echo $task_info['t_title']; ?></font> <font color="red" class="tips"></font></div>
			</div>
			<div class="portlet-body form">
				<form class="form-horizontal form-bordered" id="edittaskstep-form" action="/Task/AjaxSaveStep">
					<input class="form-control" type="hidden" name="t_id" value="<?php echo $t_id; ?>">
					<div class="form-body">
						<div class="form-group">
							<div class="error-tips">
								<div class="pre-content"><?php echo $task_info['t_content']; ?></div>
								<textarea class="form-control hidden" id="t_content" name="t_content"><?php echo $task_info['t_content']; ?></textarea>
							</div>
						</div>
						<!-- <div class="">
							<div class="col-sm-offset-5 col-sm-3">
								<a href="/Task/ListTask"><button type="button" class="btn btn-warning">返回列表</button></a>
								<button type="button" class="btn btn-primary edit-btn">进入编辑</button>
								<button type="button" class="btn btn-success sm-btn" data-formid="edittaskstep-form">保存修改</button>
							</div>
						</div> -->
					</div>
					<div class="form-operate">
						<div class="row">
							<div class="col-md-12">
								<div class="col-xs-offset-5 col-md-7">
									<a href="/Task/ListTask" class="btn btn-warning">返回列表</a>
									<button type="button" class="btn btn-primary edit-btn">进入编辑</button>
									<button type="button" class="btn btn-success sm-btn" data-formid="edittaskstep-form">保存修改</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>