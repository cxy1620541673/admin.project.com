<div class="row">
	<div class="col-md-12">
		<h3 class="page-title"> 修改密码 </h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="/index">首页</a> 
				<i class="icon-angle-right"></i>
			</li>
			<li>
				<a href="/index/modpass">修改密码</a>
			</li>
		</ul>
	</div>
</div>
<div class="row">
	<form class="form-horizontal col-sm-9">
		<div class="form-group">
			<label class="col-sm-6 control-label">用户名</label>
			<div class="col-sm-3">
				<input type="text" class="form-control" id="username" name="username">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-6 control-label">旧密码</label>
			<div class="col-sm-3">
				<input type="password" class="form-control" id="oldpassword" name="oldpassword">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-6 control-label">新密码</label>
			<div class="col-sm-3">
				<input type="password" class="form-control" id="newpassword" name="newpassword">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-6 col-sm-9">
				<a href="/Index"><button type="button" class="btn btn-warning">放弃修改</button></a>
				<button type="button" id="submit-btn" class="btn btn-success">修改密码</button>
			</div>
		</div>
	</form>
</div>


<script type="text/javascript">
$(function(){
	$('#submit-btn').click(function(){
		$.post('/login/ModifyPassword', {
			username : $('#username').val(),
			oldpassword : $('#oldpassword').val(),
			newpassword : $('#newpassword').val(),
		}, function(data){
			alert(data.msg);
			if (data.status) {
				document.location.reload();
			};
		}, 'json');
	});
});
</script>