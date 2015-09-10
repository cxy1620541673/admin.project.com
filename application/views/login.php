<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> 
<html lang="en" class="no-js"> 
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>Metronic | Admin Dashboard Template</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<meta name="MobileOptimized" content="320">
	<link href="/theme/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="/theme/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="/theme/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link href="/theme/css/style-metronic.css" rel="stylesheet" type="text/css"/>
	<link href="/theme/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="/theme/css/pages/login.css" rel="stylesheet" type="text/css"/>
	<link rel="shortcut icon" href="/theme/img/favicon.ico" />
</head>
<body class="login">
	<div class="logo">
		<!-- <img src="/theme/img/logo-big.png" alt="" />  -->
	</div>
	<div class="content">
		<form class="login-form" action="javascript:;" method="post">
			<h3 class="form-title">登陆管理后台</h3>
			<div class="alert alert-error hide">
				<button class="close" data-dismiss="alert"></button>
				<span>输入用户名和密码</span>
			</div>
			<div class="form-group col-md-12">
				<label class="control-label visible-ie8 visible-ie9">用户名</label>
				<div class="input-icon">
					<i class="icon-user"></i>
					<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="用户名" id="username" name="username"/>
				</div>
			</div>
			<div class="form-group col-md-12">
				<label class="control-label visible-ie8 visible-ie9">密码</label>
				<div class="input-icon">
					<i class="icon-lock"></i>
					<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="密码" id="password" name="password"/>
				</div>
			</div>
			<div class="form-group col-md-7">
				<label class="control-label visible-ie8 visible-ie9">验证码</label>
				<div class="input-icon">
					<i class="icon-lock"></i>
					<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="验证码" id="validate" name="validate"/>
				</div>
			</div>
			<a class="validateimg col-md-2" href="javascript:;"><img src="/Login/AjaxGetValidateCode"></a>
			<div class="form-group col-md-12"><font color="red" size="2" id="login-tips"></font></div>
			<div class="form-actions">
				<div class="col-md-12">
					<label class="checkbox">
						<input type="checkbox" name="remember" value="1"/> 记住登陆状态
					</label>
					<button type="button" id="submit-login" class="btn green pull-right"> 登陆 <i class="m-icon-swapright m-icon-white"></i></button>            
				</div>
			</div>
		</form>
	</div>
	<div class="copyright">
		2015 &copy; Pet. Admin Dashboard Template.
	</div>
	<!--[if lt IE 9]>
	<script src="/theme/plugins/respond.min.js"></script>
	<script src="/theme/plugins/excanvas.min.js"></script> 
	<![endif]-->   
	<script src="/theme/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="/theme/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="/theme/plugins/jquery.cookie.min.js" type="text/javascript"></script>
	<script src="/theme/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
	<script src="/theme/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>	
	<script src="/theme/scripts/app.js" type="text/javascript"></script>
	<script src="/theme/scripts/login.js" type="text/javascript"></script> 
	<script>
		jQuery(document).ready(function() {     
			App.init();
			Login.init();

			var loginsubmit = function(){
				var username = $('#username').val(),
					password = $('#password').val(),
					validate = $('#validate').val();
				if (username!='' && password!='' && validate!='') {
					$.post('/login/CheckLogin', {
						username : username,
						password : password,
						validate : validate,
					}, function(data){
						if (!data.status) {
							$('#login-tips').text(data.msg);
						} else {
							window.location.href = data.data;
						}
					}, 'json');
				} else {
					$('#login-tips').text('请填写登录信息');
				}
			}
			$('#submit-login').click(function(){
				loginsubmit();
			});
			$("body,input").keypress(function(event){
				if (event.which==13) {
					loginsubmit();
				};
			});
			$('.validateimg').click(function(){
				$(this).children('img').attr('src','/Login/AjaxGetValidateCode');
			});
		});
	</script>
</body>
</html>