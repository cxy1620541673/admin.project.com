<link rel="stylesheet" type="text/css" href="/theme/plugins/select2/select2_metro.css" />
<script type="text/javascript" src="/theme/plugins/select2/select2.min.js"></script>
<script src="/theme/scripts/table-editable.js"></script>
<script src="/theme/scripts/petworld/allotauth.js"></script>

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title"> 管理员列表 </h3>
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
				<a href="/Admin/AllotAuth?admin_id=<?php echo $admin_id; ?>"><?php echo $admininfo['admin_name']; ?></a> 
			</li>
		</ul>
	</div>
</div>

<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption"><i class="icon-edit"></i><?php echo $admininfo['admin_name']; ?> 的权限列表</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="auth_table" data-adminid="<?php echo $admin_id; ?>">
			<thead>
				<tr>
					<th width="20px"><input class="selectall" type="checkbox"></th>
					<th>根权限</th>
					<th>菜单权限</th>
					<th>页面权限</th>
					<th>操作权限</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($allotauth as $k => $v) { ?>
				<tr data-test="test<?php echo $k; ?>">
					<td><input class="selectrow" type="checkbox"></td>
					<td>
					<?php if ( isset( $v['auth_id'] ) ) { ?>
						<label>
							<input class="selectone" type="checkbox" <?php if (in_array($v['auth_id'], $hasauth)) echo 'checked'; ?> value="<?php echo $v['auth_id']; ?>">
							<span> <?php echo $v['auth_name']; ?></span>
							<label class="scrolllabel<?php echo $v['auth_id']; ?>" style="width:20px;margin:0px;"></label>
						</label>
					<?php } ?>
					</td>
					<td>
					<?php if ( !empty( $v['child']['menu'] ) ) foreach ($v['child']['menu'] as $mk => $mv) { ?>
						<label>
							<input class="selectone" type="checkbox" <?php if (in_array($mv['auth_id'], $hasauth)) echo 'checked'; ?> value="<?php echo $mv['auth_id']; ?>">
							<span> <?php echo $mv['auth_name']; ?></span>
							<label class="scrolllabel<?php echo $mv['auth_id']; ?>" style="width:20px;margin:0px;"></label>
						</label>
					<?php } ?>
					</td>
					<td>
					<?php if ( !empty( $v['child']['page'] ) ) foreach ($v['child']['page'] as $pk => $pv) { ?>
						<label>
							<input class="selectone" type="checkbox" <?php if (in_array($pv['auth_id'], $hasauth)) echo 'checked'; ?> value="<?php echo $pv['auth_id']; ?>">
							<span> <?php echo $pv['auth_name']; ?></span>
							<label class="scrolllabel<?php echo $pv['auth_id']; ?>" style="width:20px;margin:0px;"></label>
						</label>
					<?php } ?>
					</td>
					<td>
					<?php if ( !empty( $v['child']['operate'] ) ) foreach ($v['child']['operate'] as $ok => $ov) { ?>
						<label>
							<input class="selectone" type="checkbox" <?php if (in_array($ov['auth_id'], $hasauth)) echo 'checked'; ?> value="<?php echo $ov['auth_id']; ?>">
							<span> <?php echo $ov['auth_name']; ?></span>
							<label class="scrolllabel<?php echo $ov['auth_id']; ?>" style="width:20px;margin:0px;"></label>
						</label>
					<?php } ?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<!-- <div class="row center-block">
			<button type="button" class="btn btn-success col-md-12">提交保存</button>
		</div> -->
	</div>
</div>