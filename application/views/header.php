<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> 
<html lang="en" class="no-js"> 
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>Project</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<meta name="MobileOptimized" content="320">
	<link href="/theme/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="/theme/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="/theme/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link href="/theme/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="/theme/css/style-responsive.css" rel="stylesheet" type="text/css"/>
	<link href="/theme/css/plugins.css" rel="stylesheet" type="text/css"/>
	<link href="/theme/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="/theme/css/style-metronic.css" rel="stylesheet" type="text/css"/>
	<link href="/theme/css/custom.css" rel="stylesheet" type="text/css"/>
	<link rel="shortcut icon" href="/theme/img/favicon.ico" />
	<!--[if lt IE 9]>
	<script src="/theme/plugins/respond.min.js"></script>
	<script src="/theme/plugins/excanvas.min.js"></script> 
	<![endif]-->   
	<script src="/theme/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="/theme/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body class="page-header-fixed">
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<div class="header-inner">
			<a class="navbar-brand" href="/Index">   
				<img src="/theme/img/logo.png" alt="logo" class="img-responsive" />
			</a>
			<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<img src="/theme/img/menu-toggler.png" alt="" />
			</a> 
			<ul class="nav navbar-nav pull-right">
				<li class="dropdown user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<img alt="" src="/theme/img/avatar1_small.jpg"/>
						<span class="username"><?php echo $_SESSION[$session_key]['admin_name']; ?></span>
						<i class="icon-angle-down"></i>
					</a>
					<ul class="dropdown-menu">
						<li><a href="/Index/ModPass"><i class="icon-user"></i> 修改密码</a></li>
						<li class="divider"></li>
						<li><a href="/Login/Logout"><i class="icon-key"></i> 退出</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="page-container">
		<div class="page-sidebar navbar-collapse collapse">
			<ul class="page-sidebar-menu">
				<li><div class="sidebar-toggler hidden-phone"></div><li>
				<li class="start <?php if ( strtolower( $_SERVER['REQUEST_URI'] ) == '/index' ) echo "active"; ?>">
					<a href="/Index">
						<i class="icon-home"></i> 
						<span class="title">首页</span>
						<span class="selected"></span>
					</a>
				</li>
				<?php foreach ($menu as $k => $v) { if ( isset($v['auth_id']) ) { ?>
				<li class="<?php if ( $k == $active_menu['id'] || $k == $active_menu['pid'] ) echo "active"; ?>">
					<a href="<?php if ( isset( $v['child'] ) || $v['auth_url'] == '' ) { echo 'javascript:;'; } else { echo $v['auth_url']; } ?>">
						<i class="<?php echo $v['auth_icon']; ?>"></i> 
						<span class="title"><?php echo $v['auth_name']; ?></span>
						<span class="selected"></span>
					</a>
					<?php if ( isset( $v['child'] ) ) { ?>
						<ul class="sub-menu">
							<?php foreach ($v['child'] as $ck => $cv) { ?>
							<li class="<?php if ( $ck == $active_menu['id'] || $ck == $active_menu['pid'] ) echo "active"; ?>">
								<i class=""></i>
								<a href="<?php echo $cv['auth_url']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cv['auth_name']; ?></a>
							</li>
							<?php } ?>
						</ul>
					<?php } ?>
				</li>
				<? } } ?>
			</ul>
		</div>
		<div class="page-content">
			<div class="theme-panel hidden-xs hidden-sm">
				<div class="toggler"></div>
				<div class="toggler-close"></div>
				<div class="theme-options">
					<div class="theme-option theme-colors clearfix">
						<span>THEME COLOR</span>
						<ul>
							<li class="color-black current color-default" data-style="default"></li>
							<li class="color-blue" data-style="blue"></li>
							<li class="color-brown" data-style="brown"></li>
							<li class="color-purple" data-style="purple"></li>
							<li class="color-grey" data-style="grey"></li>
							<li class="color-white color-light" data-style="light"></li>
						</ul>
					</div>
					<div class="theme-option">
						<span>Layout</span>
						<select class="layout-option form-control input-small">
							<option value="fluid" selected="selected">Fluid</option>
							<option value="boxed">Boxed</option>
						</select>
					</div>
					<div class="theme-option">
						<span>Header</span>
						<select class="header-option form-control input-small">
							<option value="fixed" selected="selected">Fixed</option>
							<option value="default">Default</option>
						</select>
					</div>
					<div class="theme-option">
						<span>Sidebar</span>
						<select class="sidebar-option form-control input-small">
							<option value="fixed">Fixed</option>
							<option value="default" selected="selected">Default</option>
						</select>
					</div>
					<div class="theme-option">
						<span>Footer</span>
						<select class="footer-option form-control input-small">
							<option value="fixed">Fixed</option>
							<option value="default" selected="selected">Default</option>
						</select>
					</div>
				</div>
			</div>
			<!-- <div class="row">
				<div class="col-md-12">
					<h3 class="page-title"> 首页 </h3>
					<ul class="page-breadcrumb breadcrumb">
						<li>
							<i class="icon-home"></i>
							<a href="index.html">首页</a> 
							<i class="icon-angle-right"></i>
						</li>
						<li>
							<a href="#">Dashboard</a>
							<i class="icon-angle-right"></i>
						</li>
					</ul>
				</div>
			</div>
			<div class="row"> -->