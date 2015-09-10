<div class="row">
	<div class="col-md-12">
		<h3 class="page-title"> 权限添加 </h3>
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
				<a href="/Authority/AddAuth">权限添加</a> 
			</li>
		</ul>
	</div>
</div>

<form class="form-horizontal" id="addauth-form" action="/Authority/AjaxAddAuth">
	<div class="form-group">
		<label class="col-sm-3 control-label"><font color="red"> * </font>权限名称</label>
		<div class="col-sm-5 error-tips">
			<input type="text" class="form-control" id="auth_name" name="auth_name" data-required="必填">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">权限URL</label>
		<div class="col-sm-5 error-tips">
			<input type="text" class="form-control" id="auth_url" name="auth_url">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><font color="red"> * </font>权限类型</label>
		<div class="col-sm-2 error-tips">
			<select class="form-control" id="auth_type" name="auth_type" data-required="必选">
				<option value="">请选择</option>
				<option value="0">菜单权限</option>
				<option value="1">操作权限</option>
				<option value="2">页面权限</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><font color="red"> * </font>父级权限</label>
		<div class="col-sm-2 error-tips">
			<select class="form-control" id="auth_parent" name="auth_parent" data-required="必选">
				<option value="">请选择</option>
				<option value="0">根权限</option>
				<?php foreach ($parent_auth as $k => $v) { ?>
					<option value="<?php echo($v['auth_id']); ?>"><?php echo($v['auth_name']); ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">权限图标</label>
		<div class="col-sm-2 error-tips">
			<input type="text" class="form-control" id="auth_icon" name="auth_icon">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">权限排序</label>
		<div class="col-sm-2 error-tips">
			<input type="text" class="form-control" id="auth_sort" name="auth_sort">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">权限描述</label>
		<div class="col-sm-5 error-tips">
			<textarea class="form-control" rows="3" id="auth_desc" name="auth_desc"></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-2">
			<a href="/Authority/ListAuth"><button type="button" class="btn btn-warning">取消</button></a>
			<button type="button" class="btn btn-success sm-btn" data-formid="addauth-form">添加</button>
		</div>
	</div>
</form>