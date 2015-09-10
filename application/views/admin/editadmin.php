<div class="row">
	<div class="col-md-12">
		<h3 class="page-title"> 管理员修改 </h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="/index">首页</a> 
			</li>
			<li>
				<i class="icon-angle-right"></i>
				<a href="/Admin/ListAdmin">管理员管理</a> 
			</li>
			<li>
				<i class="icon-angle-right"></i>
				<a href="/Admin/EditAdmin?admin_id=<?php echo $admin_info['admin_id'] ?>">管理员添加</a> 
			</li>
		</ul>
	</div>
</div>

<form class="form-horizontal" id="editadmin-form" action="/Admin/AjaxEditAdmin">
	<input class="form-control" type="hidden" name="level" value="<?php echo $admin_info['al_id']; ?>">
	<input class="form-control" type="hidden" name="admin_id" value="<?php echo $admin_info['admin_id']; ?>">
	<div class="form-group">
		<label class="col-sm-4 control-label"><font color="red"> * </font>管理员名称</label>
		<div class="col-sm-2 error-tips">
			<input type="text" class="form-control" id="admin_name" name="admin_name" data-required="必填" value="<?php echo $admin_info['admin_name']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label"><font color="red"> * </font>管理员等级</label>
		<div class="col-sm-2 error-tips">
			<select class="form-control" id="al_id" name="al_id" data-required="必选">
				<option value="">请选择</option>
				<?php foreach ($al_id_arr as $v) { ?>
				<option value="<?php echo $v['al_id']; ?>" <?php if ($admin_info['al_id']==$v['al_id']) echo 'selected'; ?>><?php echo $v['al_name']; ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label"><font color="red"> * </font>管理员状态</label>
		<div class="col-sm-2 error-tips">
			<select class="form-control" id="admin_status" name="admin_status" data-required="必选">
				<option value="">请选择</option>
				<option value="0" <?php if ($admin_info['admin_status']==0) echo 'selected'; ?> >屏蔽</option>
				<option value="1" <?php if ($admin_info['admin_status']==1) echo 'selected'; ?> >正常</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-2">
			<a href="/Admin/ListAdmin"><button type="button" class="btn btn-warning">取消</button></a>
			<button type="button" class="btn btn-success sm-btn" data-formid="editadmin-form">修改</button>
		</div>
	</div>
</form>
