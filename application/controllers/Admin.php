<?php

/**
 * @description	管理员管理
 * @createtime	2015-08-07 10:00:00
 * @author		chenxuyi
 */

class Admin extends PW_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'AdminModel', 'amodel' );
	}

	/**
	 * 管理员列表页
	 */
	public function ListAdmin() {
		$data['admin_level_arr']	=	$this->amodel->GetAllLevel();
		$this->display( 'admin/listadmin', $data );
	}

	/**
	 * 添加管理员页
	 */
	public function AddAdmin() {
		$data['level_arr']	=	$this->amodel->GetLevelRange( $this->_admin['al_id'], '<=' );
		$this->display( 'admin/addadmin', $data );
	}

	/**
	 * 编辑管理员页
	 */
	public function EditAdmin() {
		$admin_id	=	GetParams( 'admin_id', 1, 0 );
		$data['admin_info']	=	$this->amodel->GetAdminById( $admin_id );
		$data['al_id_arr']	=	$this->amodel->GetLevelRange( $this->_admin['al_id'], '<=' );
		$this->display( 'admin/editadmin', $data );
	}

	/**
	 * 权限分配页
	 */
	public function AllotAuth() {
		// 获取参数
		$data['admin_id']	=	GetParams( 'admin_id', 1, 0 );

		$this->load->model( 'AuthModel', 'authmodel' );
		$this->load->model( 'LoginModel', 'loginmodel' );

		// 获取管理员信息
		$data['admininfo']	=	$this->loginmodel->GetAdminInfo( array( 'admin_id'=>$data['admin_id'] ) );

		// 获取当前登陆用户的所有权限
		$authinfo	=	$this->authmodel->GetAuthByLevelId( $data['admininfo']['al_id'] );
		$data['allotauth']	=	array();
		foreach ($authinfo as $k => $v) {
			if ( intval( $v['auth_parent'] ) == 0 ) {
				$v['child']	=	array( 'menu'=>array(), 'operate'=>array(), 'page'=>array() );
				$data['allotauth'][$v['auth_id']]	=	$v;
			} else {
				switch ( intval( $v['auth_type'] ) ) {
					case 0:	// 菜单权限
						$data['allotauth'][$v['auth_parent']]['child']['menu'][$v['auth_id']]	=	$v;
						break;
					case 1:	// 操作权限
						$data['allotauth'][$v['auth_parent']]['child']['operate'][$v['auth_id']]	=	$v;
						break;
					case 2:	// 页面权限
						$data['allotauth'][$v['auth_parent']]['child']['page'][$v['auth_id']]	=	$v;
						break;
				}
			}
		}

		// 获取选中用户的所有权限id
		$adminauth	=	$this->model->GetAdminAuth( $data['admin_id'] );
		$data['hasauth']	=	array();
		foreach ($adminauth as $k => $v) {
			$data['hasauth'][]	=	$v['auth_id'];
		}
		$this->display( 'admin/allotauth', $data );
	}

	/**
	 * 获取管理员列表
	 */
	public function AjaxGetAdminList() {
		// 获取参数
		$page	=	GetParams( 'page', 1 );
		$size	=	GetParams( 'size', 1 );

		$admin_status	=	GetParams( 'admin_status', 1, -1 );
		$al_id			=	GetParams( 'al_id', 1, -1 );
		$admin_name		=	GetParams( 'admin_name', 2, '' );

		// 判断参数是否合法
		// MustInt( array( $page, $size ) );
		$start	=	0;
		if ( $page < 1 ) $page	=	1;
		if ( $size < 1 && $size != -1 ) $size	=	10;
		if ( $size != -1 ) $start	=	( $page - 1 ) * $size;

		// 查找条件
		$condition	=	array(
			'where'		=>	array(),
			'or_where'	=>	array(),
			'limit'		=>	array(),
			'order_by'	=>	'',
		);
		$field	=	'admin_id,admin_name,admin_status,al_id,admin_last_login_time';
		$admin_status_arr	=	array( '屏蔽', '正常' );
		$al_id_arr			=	$this->amodel->GetAllLevel();

		if ( $admin_status > -1 )		$condition['where']['admin_status']		=	$admin_status;
		if ( $al_id > -1 )				$condition['where']['al_id']			=	$al_id;
		if ( !empty( $admin_name ) )	$condition['where']['admin_name LIKE']	=	'%'.$admin_name.'%';
		if ( $size != -1 ) {
			$condition['limit']['start']	=	$start;
			$condition['limit']['size']		=	$size;
		}
		$condition['order_by']	=	'admin_id DESC';

		// 获取数据
		$data['total']		=	intval( $this->amodel->CountAdmin( $condition ) );
		$data['pagetotal']	=	$size > 0 ? ceil( $data['total'] / $size ) : 1;
		$data['pagenum']	=	$page < $data['pagetotal'] ? $page : ( $data['pagetotal'] <= 0 ? 1 : $data['pagetotal'] );
		$data['start']		=	$start;
		$data['size']		=	$size < 1 ? $data['total'] : $size;
		$data['rows']		=	$this->amodel->GetAllAdmin( $field, $condition );

		foreach ($data['rows'] as $k => &$v) {
			$v['admin_status']	=	$admin_status_arr[$v['admin_status']];
			$v['al_id']			=	$al_id_arr[$v['al_id']]['al_name'];
			$v['admin_last_login_time']	=	$v['admin_last_login_time'] == 0 ? '' : date( 'Y-m-d H:i:s', $v['admin_last_login_time'] );
		}

		EchoJson( TRUE, SUCCESS, '获取数据成功', $data );
	}

	/**
	 * 添加管理员
	 */
	public function AjaxAddAdmin() {
		// 获取参数
		$data['admin_pass_original']	=	GetParams( 'admin_pass_original', 2 );
		$data['admin_pass']		=	md5( $data['admin_pass_original'] );
		$data['admin_name']		=	GetParams( 'admin_name', 2 );
		$data['admin_status']	=	GetParams( 'admin_status', 1 );
		$data['al_id']			=	GetParams( 'al_id', 1 );
		$data['admin_insert_time']	=	time();

		// 验证参数
		// MustStr( array( $data['admin_name'], $data['admin_pass_original'] ) );
		// MustInt( array( $data['admin_status'], $data['admin_level'] ) );
		// 添加到库
		if ( $this->amodel->InsertAdmin( $data ) ) {
			EchoJson( TRUE, SUCCESS, '添加成功' );
		}
		EchoJson( FALSE, OPERATE_FALSE, '添加失败' );
	}

	/**
	 * 修改管理员
	 */
	public function AjaxEditAdmin() {
		// 获取参数
		$admin_id	=	GetParams( 'admin_id', 1 );
		$level		=	GetParams( 'level', 1 );
		$data['admin_name']		=	GetParams( 'admin_name', 2 );
		$data['al_id']			=	GetParams( 'al_id', 1 );
		$data['admin_status']	=	GetParams( 'admin_status', 1 );
		// 验证参数
		// MustStr( $data['admin_name'] );
		// MustInt( array( $data['admin_status'], $data['al_id'], $admin_id, $level ) );
		// 判断管理员等级，低等级不能修改高等级，同等级可修改
		$al_id_arr	=	$this->amodel->GetLevelRange( $level, '>' );
		if ( !isset( $al_id_arr[$this->_admin['al_id']] ) ) {
			EchoJson( FALSE, Auth_FALSE, '没有权限' );
		}
		// 更新
		if ( $this->amodel->UpdateAdmin( $admin_id, $data ) ) EchoJson( TRUE, SUCCESS, '操作成功' );
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

	/**
	 * 删除管理员
	 */
	public function AjaxDelAdmin() {
		// 获取参数
		$admin_id	=	GetParams( 'admin_id', 1 );
		// MustInt( $admin_id );
		// 删除
		if ( $this->amodel->DelAdmin( $admin_id ) ) EchoJson( TRUE, 1, '操作成功' );
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

	/**
	 * 修改管理员权限关联
	 */
	public function AjaxChangeAdminRelation() {
		// 获取参数
		$admin_id	=	GetParams( 'admin_id', 1 );
		$auth_id	=	GetParams( 'auth_id', 1 );
		$change		=	GetParams( 'change', 1, -1 );

		// 验证参数
		// MustInt( array( $admin_id, $auth_id ) );
		if ( $admin_id < 1 || $auth_id < 1 || $change < 0 ) {
			EchoJson( FALSE, PARAM_FALSE, '参数错误' );
		}

		// 判断管理员等级
		$admin_info	=	$this->amodel->GetAdminById( $admin_id );
		$al_id_arr	=	$this->amodel->GetLevelRange( $admin_info['al_id'], '>' );
		if ( !isset( $al_id_arr[$this->_admin['al_id']] ) && $admin_id != $this->_admin['admin_id'] ) {
			EchoJson( FALSE, Auth_FALSE, '没有权限' );
		}

		$this->load->model( 'AuthModel', 'authmodel' );
		// 判断等级权限是否存在
		if ( !$this->authmodel->IsExistLevelRelation( $admin_info['al_id'], $auth_id ) ) {
			EchoJson( FALSE, Auth_FALSE, '没有权限' );
		}

		// 判断管理员是否绑定此权限
		$isexist	=	$this->authmodel->IsExistAdminRelation( $admin_id, $auth_id );
		if ( $isexist && $change == 0 ) {
			if ( $this->authmodel->UnRelateAdminAuth( $admin_id, $auth_id ) ) {
				EchoJson( TRUE, SUCCESS, '操作成功' );
			}
			EchoJson( TRUE, OPERATE_FALSE, '操作失败' );
		} else if ( !$isexist && $change == 1 ) {
			if ( $this->authmodel->RelateAdminAuth( $admin_id, $auth_id ) ) {
				EchoJson( TRUE, SUCCESS, '操作成功' );
			}
			EchoJson( TRUE, OPERATE_FALSE, '操作失败' );
		}
		EchoJson( TRUE, SUCCESS, '操作成功' );
	}

}

?>