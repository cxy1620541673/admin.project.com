<script type="text/javascript" src="/theme/scripts/petworld/addproject.js"></script>

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title"> 项目添加 </h3>
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
				<a href="/Project/AddProject">项目添加</a> 
			</li>
		</ul>
	</div>
</div>

<form class="form-horizontal" id="addproject-form" action="/Project/AjaxAddProject">
	<div class="form-group">
		<label class="col-sm-3 control-label"><font color="red"> * </font>项目名称</label>
		<div class="col-sm-5 error-tips">
			<input type="text" class="form-control" id="p_name" name="p_name" data-required="必填">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><font color="red"> * </font>项目路径</label>
		<div class="col-sm-5 error-tips">
			<input type="text" class="form-control" id="p_path" name="p_path" data-required="必填">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><font color="red"> * </font>域名</label>
		<div class="col-sm-5 error-tips">
			<input type="text" class="form-control" id="p_domain" name="p_domain" data-required="必填">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><font color="red"> * </font>项目归属</label>
		<div class="col-sm-5 error-tips">
			<input type="text" class="form-control" id="p_belong" name="p_belong" data-required="必填">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">项目描述</label>
		<div class="col-sm-5 error-tips">
			<textarea class="form-control" rows="3" id="p_desc" name="p_desc"></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-2">
			<a href="/Project/ListProject"><button type="button" class="btn btn-warning">取消</button></a>
			<button type="button" class="btn btn-success sm-btn" data-formid="addproject-form">添加</button>
		</div>
	</div>
</form>