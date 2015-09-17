<link rel="stylesheet" type="text/css" href="/theme/plugins/select2/select2_metro.css" />
<link href="/theme/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
<link href="/theme/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="/theme/plugins/select2/select2.min.js"></script>
<script src="/theme/scripts/table-editable.js"></script>
<script src="/theme/scripts/petworld/listlevelauth.js"></script>
<script src="/theme/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript" ></script>
<script src="/theme/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript" ></script>

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title"> 等级权限列表 </h3>
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
				<a href="/Authority/ListLevelAuth">等级权限列表</a> 
			</li>
		</ul>
	</div>
</div>

<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption"><i class="icon-edit"></i> 列表</div>
	</div>
	<div class="portlet-body">
		<span class="btn btn-info add-level" data-target="#stack1" data-toggle="modal"><i class="icon-plus"></i> 添加管理员等级 </span>
		<br><br>
		<table class="table table-bordered" id="level_auth_table">
			<thead>
				<tr>
					<th width="100px">等级名称</th>
					<th width="46px">等级</th>
					<th width="150px">根权限</th>
					<th>菜单权限</th>
					<th>页面权限</th>
					<th>操作权限</th>
					<th width="80px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($alllevel as $lk => $lv) { if ( isset( $auth[$lk] ) ) { foreach ($auth[$lk] as $ak => $av) { ?>
				<tr class="row<?php echo $lk; ?>">
					<?php if ( array_slice( $auth[$lk], 0, 1 ) == array( $av ) ) { ?>
					<td rowspan="<?php echo count($auth[$lk]) ?>"><?php echo $lv['al_name']; ?></td>
					<td rowspan="<?php echo count($auth[$lk]) ?>"><?php echo $lv['al_level']; ?></td>
					<?php } ?>
					<td>
					<?php if ( isset( $av['auth_id'] ) ) { ?>
						<label>
							<input class="selectone" type="checkbox" checked disabled />
							<span> <?php echo $av['auth_name']; ?></span>
						</label>
					<?php } ?>
					</td>
					<td>
					<?php if ( !empty( $av['child']['menu'] ) ) foreach ($av['child']['menu'] as $mk => $mv) { ?>
						<label>
							<input class="selectone" type="checkbox" checked disabled />
							<span> <?php echo $mv['auth_name']; ?></span>
						</label>
					<?php } ?>
					</td>
					<td>
					<?php if ( !empty( $av['child']['page'] ) ) foreach ($av['child']['page'] as $pk => $pv) { ?>
						<label>
							<input class="selectone" type="checkbox" checked disabled />
							<span> <?php echo $pv['auth_name']; ?></span>
						</label>
					<?php } ?>
					</td>
					<td>
					<?php if ( !empty( $av['child']['operate'] ) ) foreach ($av['child']['operate'] as $ok => $ov) { ?>
						<label>
							<input class="selectone" type="checkbox" checked disabled />
							<span> <?php echo $ov['auth_name']; ?></span>
						</label>
					<?php } ?>
					</td>
					<?php if ( array_slice( $auth[$lk], 0, 1 ) == array( $av ) ) { ?>
					<td rowspan="<?php echo count($auth[$lk]) ?>">
						<a class="edit" href="/Authority/EditLevelAuth?al_id=<?php echo $lk; ?>">编辑</a>
						<a class="del" href="javascript:;" data-alid="<?php echo $lk; ?>">删除</a>
					</td>
					<?php } ?>
				</tr>
			<?php } } else { ?>
				<tr>
					<td><?php echo $lv['al_name']; ?></td>
					<td><?php echo $lv['al_level']; ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<a class="edit" href="/Authority/EditLevelAuth?al_id=<?php echo $lk; ?>">编辑</a>
						<a class="del" href="javascript:;" data-alid="<?php echo $lk; ?>">删除</a>
					</td>
				</tr>
			<?php } } ?>
			</tbody>
		</table>
	</div>
</div>

<div id="stack1" class="modal fade" tabindex="-1" data-focus-on="input:first">
	<form class="form-horizontal" id="addlevel-form" action="/Authority/AjaxAddLevel">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title">添加管理员等级</h4>
		</div>
		<div class="modal-body">
				<div class="form-group">
					<label class="col-sm-3 control-label"><font color="red"> * </font>等级名称</label>
					<div class="col-sm-7 error-tips">
						<input type="text" class="form-control" id="al_name" name="al_name" data-required="必填">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><font color="red"> * </font>等级序号</label>
					<div class="col-sm-7 error-tips">
						<input type="text" class="form-control" id="al_level" name="al_level" data-required="必填">
					</div>
				</div>
		</div>
		<div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn btn-default">关闭</button>
			<button type="button" class="btn btn-primary sm-modal sm-btn" data-formid="addlevel-form">提交</button>
		</div>
	</form>
</div>