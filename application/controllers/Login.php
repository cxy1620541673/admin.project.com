<?php 

/**
 * @description	登陆页
 * @createtime	2015-08-07 10:00:00
 * @author		chenxyi
 */

class Login extends CI_Controller {

	private $config_sess	=	'admin_sess';	// 管理员session配置

	public function __construct() {
		parent::__construct();
		// 开启SESSION
		session_start();
	}

	/**
	 * 登陆页面
	 */
	public function Index() {
		$this->load->view( 'login' );
	}

	/**
	 * 登录操作
	 */
	public function CheckLogin() {
		// 获取参数
		$username	=	GetParams( 'username', 2 );
		$password	=	GetParams( 'password', 2 );
		$validate	=	GetParams( 'validate', 2, '' );
		// 判断参数是否合法
		// MustStr( array( $username, $password ) );
		if ( GetSession( $this->config->item( 'admin_login_sess' ) ) != strtoupper( $validate ) || empty( $validate ) ) {
			EchoJson( FALSE, VALIDATE_FALSE, '验证码错误' );
		}
		// 获取数据库数据
		$where	=	array(
			'admin_name'	=>	$username,
			'admin_pass'	=>	md5( $password ),
			// 'admin_status'	=>	1,
		);
		$this->load->model( 'LoginModel', 'lmodel' );
		$userinfo	=	$this->lmodel->GetAdminInfo( $where );
		$sessinfo	=	array();
		if ( !empty( $userinfo ) ) {
			if ( intval( $userinfo['admin_status'] ) == 1 ) {
				// 用户信息写入session
				$sessinfo['admin_id']	=	intval( $userinfo['admin_id'] );
				$sessinfo['al_id']		=	intval( $userinfo['al_id'] );
				$sessinfo['admin_name']	=	$userinfo['admin_name'];
				SetSession( $this->config->item( $this->config_sess ), $sessinfo );
				// 设置最新登录时间
				$this->lmodel->SetLastLoginTime( $userinfo['admin_id'] );
				// 登陆成功跳转
				EchoJson( TRUE, SUCCESS, '登陆成功', $this->config->item( 'domain' ).'/index' );
			} else {
				EchoJson( FALSE, BAN_ACCOUNT, '账号已被封，请联系超级管理员' );
			}
		} else {
			// 登录失败跳转
			EchoJson( FALSE, PASS_WRONG, '用户名或密码错误' );
		}
	}

	/**
	 * 修改密码
	 */
	public function ModifyPassword() {
		// 获取当前账号信息
		$sessinfo	=	GetSession( $this->config->item( $this->config_sess ) );
		// 判断是否登陆
		if ( empty( $sessinfo ) ) header( 'location:/login' );
		// 获取参数
		$username		=	GetParams( 'username', 2 );
		$oldpassword	=	GetParams( 'oldpassword', 2, '' );
		$newpassword	=	GetParams( 'newpassword', 2 );

		// 判断参数合法
		// MustStr( array( $username, $newpassword ) );

		// 获取账号
		$this->load->model( 'LoginModel', 'lmodel' );
		$where		=	array('admin_name'=>$username);
		$userinfo	=	$this->lmodel->GetAdminInfo( $where );
		// 等级高的可无条件等级低的密码，等级低的不能修改等级高的密码，同等级的需要提供原密码
		if ( empty( $userinfo ) ) EchoJson( FALSE, 10002, '管理员不存在' );
		if ( $sessinfo['admin_level'] < $userinfo['admin_level'] ) EchoJson( FALSE, 10003, '权限不够，不能修改' );
		if ( $sessinfo['admin_level'] == $userinfo['admin_level'] && $userinfo['admin_pass'] != md5( $oldpassword ) ) EchoJson( FALSE, 10004, '旧密码错误' );

		// 修改密码
		$moddata	=	array(
			'admin_pass'			=>	md5( $newpassword ),
			'admin_pass_original'	=>	$newpassword,
		);
		if ( $this->lmodel->ModifyAdminInfo( $userinfo['admin_id'], $moddata ) ) EchoJson( TRUE, SUCCESS, '操作成功' );
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

	/**
	 * 登出
	 */
	public function Logout() {
		DelSession( $this->config->item( $this->config_sess ) );
		header( 'location:/login' );
	}

	public function AjaxGetValidateCode() {
		$code	=	Image::CreateValidate( 4, 1 );
		$sessinfo	=	strtoupper( implode( '', $code ) );
		SetSession( $this->config->item( 'admin_login_sess' ), $sessinfo );
	}

}