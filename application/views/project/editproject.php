<div class="row">
	<div class="col-md-12">
		<h3 class="page-title"> 项目修改 </h3>
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
				<a href="/Project/EditProject?p_id=<?php echo $project_info['p_id']; ?>">项目修改</a> 
			</li>
		</ul>
	</div>
</div>

<form class="form-horizontal" id="editproject-form" action="/Project/AjaxEditProject">
	<input class="form-control" type="hidden" name="p_id" value="<?php echo $project_info['p_id']; ?>">
	<div class="form-group">
		<label class="col-sm-3 control-label"><font color="red"> * </font>项目名称</label>
		<div class="col-sm-5 error-tips">
			<input type="text" class="form-control" id="p_name" name="p_name" data-required="必填" value="<?php echo $project_info['p_name']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><font color="red"> * </font>项目路径</label>
		<div class="col-sm-5 error-tips">
			<input type="text" class="form-control" id="p_path" name="p_path" data-required="必填" value="<?php echo $project_info['p_path']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><font color="red"> * </font>域名</label>
		<div class="col-sm-5 error-tips">
			<input type="text" class="form-control" id="p_domain" name="p_domain" data-required="必填" value="<?php echo $project_info['p_domain']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><font color="red"> * </font>项目归属</label>
		<div class="col-sm-5 error-tips">
			<input type="text" class="form-control" id="p_belong" name="p_belong" data-required="必填" value="<?php echo $project_info['p_belong']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">项目描述</label>
		<div class="col-sm-5 error-tips">
			<textarea class="form-control" rows="3" id="p_desc" name="p_desc"><?php echo $project_info['p_desc']; ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-2">
			<a href="/Project/ListProject" class="btn btn-warning">取消</a>
			<button type="button" class="btn btn-success sm-btn" data-formid="editproject-form">修改</button>
		</div>
	</div>
</form>