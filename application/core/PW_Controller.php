<?php

class PW_Controller extends CI_Controller {

	public $_domain		=	'';			// 网站DOMAIN
	public $_uri		=	'';			// URI
	public $_auth		=	array();	// 权限URL
	public $_admin		=	array();	// 管理员信息

	private $menu	=	array();
	private $config_sess	=	'admin_sess';	// 管理员登陆session配置
	private $config_auth	=	'admin_auth';	// 管理员权限session配置

	public function __construct() {
		parent::__construct();
		header( "Content-type: text/html; charset=utf-8" ); 
		// 获取DOMAIN
		$this->_domain	=	$this->GetConfig( 'domain' );
		// 开启SESSION
		session_start();
		// 获取管理员信息
		$this->_admin	=	GetSession( $this->GetConfig( $this->config_sess ) );
		// 判断是否登陆
		if ( empty( $this->_admin ) ) 
			header( 'location:/login' );
		// 处理URI
		$this->_uri	=	strtolower( preg_replace( "/(\/)*\?.*/", "", $_SERVER['REQUEST_URI'] ) );
		// 取权限
		$this->GetAuth();
		// 判断是否有权限
		$this->CheckAuth();
	}

	/**
	 * 获取系统配置
	 * @param string $key [description]
	 */
	public function GetConfig( $key = '' ) {
		$config_item	=	$this->config->item( $key );
		if ( empty( $config_item ) ) {
			return FALSE;
		}
		return $config_item;
	}

	/**
	 * 展示模板
	 * @param	string	$view	模板文件
	 * @param	array	$data	数据
	 */
	public function display( $view = '', $data = array() ) {
		// 获取用户session的键名
		$data['session_key']	=	$this->GetConfig( $this->config_sess );
		// 菜单栏
		$data['menu']	=	$this->menu;
		// 获取活动菜单
		$data['active_menu']	=	isset( $this->_auth[$this->_uri] ) ? $this->_auth[$this->_uri] : array('id'=>0,'pid'=>0);
// print_r($this->_auth);die;
		$this->load->view( 'header', $data );
		if ( !empty( $view ) ) $this->load->view( $view );
		$this->load->view( 'footer' );
	}

	private function GetAuth() {
		// 取数据库数据
		$this->load->model( 'AuthModel', 'model' );
		$authinfo	=	$this->model->GetAdminAuth( $this->_admin['admin_id'] );
		// 记录并存入session
		foreach ($authinfo as $k => $v) {
			// 获取权限id和父级id
			if ( !empty( $v['auth_url'] ) ) {
				$key	=	strtolower( $v['auth_url'] );
				$this->_auth[$key]['id']	=	intval( $v['auth_id'] );
				$this->_auth[$key]['pid']	=	intval( $v['auth_parent'] );
				$this->_auth[$key]['type']	=	intval( $v['auth_type'] );
			}
			// 获取菜单权限
			if ( intval( $v['auth_type'] ) == 0 ) {
				if ( intval( $v['auth_parent'] ) == 0 ) {
					$v['child']	=	array();
					$this->menu[$v['auth_id']]	=	$v;
				} else {
					$this->menu[$v['auth_parent']]['child'][$v['auth_id']]	=	$v;		
				}
			}
		}
		// print_r($authinfo);die;
	}

	private function CheckAuth() {
		// 判断是否ajax请求
		$isAjax	=	FALSE;
		if ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) $isAjax	=	TRUE;
		
		// 判断是否有权限
		if ( !empty( $this->_auth ) && isset( $this->_auth[$this->_uri] ) ) return TRUE;
		if ( $this->_uri != '/index' ) {
			$this->load->model( 'AuthModel', 'model' );
			$auth_type	=	$this->model->GetAuthTypeByUrl( $this->_uri );
			if ( $auth_type === 0 || $auth_type === 2 ) {
				echo '<script>alert("没有权限");window.location.href="'.getenv( "HTTP_REFERER" ).'";</script>';
				exit;
			} else if ( $auth_type === 1 ) {
				EchoJson( FALSE, 10007, '没有权限', '/index' );
			} else {
				if ( $isAjax ) {
					EchoJson( FALSE, 10007, '没有权限', '/index' );
				} else {
					header( 'location:/index' );
				}
				exit;
			}
		}
		return FALSE;
	}

}