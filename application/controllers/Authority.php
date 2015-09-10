<?php

/**
 * @description	权限管理
 * @createtime	2015-08-07 10:00:00
 * @author		chenxyi
 */

class Authority extends PW_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'AuthModel', 'amodel' );
	}

	/**
	 * 权限列表页
	 */
	public function ListAuth() {
		$data['parent_auth']	=	$this->amodel->GetRootAuth();
		// print_r($data);die;
		$this->display( 'authority/listauth', $data );
	}

	/**
	 * 添加权限页
	 */
	public function AddAuth() {
		// 获取所有根菜单
		$data['parent_auth']	=	$this->amodel->GetRootAuth();
		$this->display( 'authority/addauth', $data );
	}

	/**
	 * 编辑权限页
	 */
	public function EditAuth() {
		$auth_id	=	GetParams( 'auth_id', 1, 0 );
		$data['auth_info']	=	$this->amodel->GetAuthById( $auth_id );
		$this->display( 'authority/editauth', $data );
	}

	/**
	 * 等级权限列表页
	 */
	public function ListLevelAuth() {
		$this->load->model( 'AdminModel', 'adminmodel' );
		// 获取所有管理员等级
		$data['alllevel']	=	$this->adminmodel->GetAllLevel();
		$al_ids		=	array();
		foreach ($data['alllevel'] as $k => $v) {
			$al_ids[]	=	$k;
		}
		// 根据等级获取权限
		$auth	=	$this->amodel->GetAuthByLevelId( $al_ids );
		// 组织格式
		$data['auth']	=	array();
		if ( !empty( $auth ) ) {
			foreach ($auth as $k => $v) {
				if ( $v['auth_parent'] == 0 ) {
					$v['child']	=	array();
					$data['auth'][$v['al_id']][$v['auth_id']]	=	$v;
				} else {
					switch ( intval( $v['auth_type'] ) ) {
						case 0:$data['auth'][$v['al_id']][$v['auth_parent']]['child']['menu'][$v['auth_id']]=$v;break;
						case 1:$data['auth'][$v['al_id']][$v['auth_parent']]['child']['operate'][$v['auth_id']]=$v;break;
						case 2:$data['auth'][$v['al_id']][$v['auth_parent']]['child']['page'][$v['auth_id']]=$v;break;
					}
				}
			}
		}
		$this->display( 'authority/listlevelauth', $data );
	}

	/**
	 * 编辑等级权限页
	 */
	public function EditLevelAuth() {
		// 获取参数
		$al_id	=	GetParams( 'al_id', 1, 0 );

		// 判断是否有权限修改
		$this->load->model( 'AdminModel', 'adminmodel' );
		$level	=	$this->adminmodel->GetLevelRange( $al_id, '>' );
		// 超级管理员能够修改自己的，其他管理员不行
		if ( !isset( $level[$this->_admin['al_id']] ) ) {
			if ( $this->_admin['al_id'] == 1 ) {
				// exit( '<script>alert("超级管理员等级权限不能修改");window.location.href="/Authority/ListLevelAuth"</script>' );
			} else {
				exit( '<script>alert("没有权限");window.location.href="/Authority/ListLevelAuth"</script>' );
			}
		}
		
		$data['al_id']	=	$al_id;
		// 获取所有权限
		$myauth		=	$this->_admin['al_id'] == 1 ? $this->amodel->GetAllAuth( 'a.*', array( 'order_by'=>'auth_parent ASC,auth_sort ASC' ) ) : $this->amodel->GetAuthByLevelId( $this->_admin['al_id'] );
		$existauth	=	$this->amodel->GetAuthByLevelId( $al_id );
		// 组织格式
		$data['myauth']		=	array();
		$data['existauth']	=	array();
		if ( !empty( $myauth ) ) {
			foreach ($myauth as $k => $v) {
				if ( $v['auth_parent'] == 0 ) {
					$v['child']	=	array();
					$data['myauth'][$v['auth_id']]	=	$v;
				} else {
					switch ( intval( $v['auth_type'] ) ) {
						case 0:$data['myauth'][$v['auth_parent']]['child']['menu'][$v['auth_id']]=$v;break;
						case 1:$data['myauth'][$v['auth_parent']]['child']['operate'][$v['auth_id']]=$v;break;
						case 2:$data['myauth'][$v['auth_parent']]['child']['page'][$v['auth_id']]=$v;break;
					}
				}
			}
		}
		if ( !empty( $existauth ) ) {
			foreach ($existauth as $k => $v) {
				$data['existauth'][]	=	$v['auth_id'];
			}
		}
		$this->display( 'authority/editlevelauth', $data );
	}

	/**
	 * 获取权限列表
	 */
	public function AjaxGetAuthList() {
		// 获取参数
		$page	=	GetParams( 'page', 1 );
		$size	=	GetParams( 'size', 1 );

		$auth_name		=	GetParams( 'auth_name', 2, '' );
		$auth_type		=	GetParams( 'auth_type', 1, 0 );
		$auth_parent	=	GetParams( 'auth_parent', 1, 0 );

		$start	=	0;
		if ( $page < 1 ) $page	=	1;
		if ( $size < 1 && $size != -1 ) $size	=	10;
		if ( $size != -1 ) $start	=	( $page - 1 ) * $size;

		$condition	=	array(
			'where'		=>	array(),
			'or_where'	=>	array(),
			'limit'		=>	array(),
			'order_by'	=>	'',
		);
		if ( $auth_type > -1 )		$condition['where']['a.auth_type']	=	$auth_type;
		if ( $auth_parent > -1 ) {
			$condition['or_where']	=	" ( a.auth_parent = $auth_parent OR a.auth_id = $auth_parent ) ";
		}
		if ( !empty( $auth_name ) )	$condition['where']['a.auth_name LIKE']	=	'%'.$auth_name.'%';
		if ( $size != -1 ) {
			$condition['limit']['start']	=	$start;
			$condition['limit']['size']		=	$size;
		}
		$condition['order_by']	=	'a.auth_id DESC';

		// 获取数据
		$data['total']		=	intval( $this->amodel->CountAuth( $condition ) );
		$data['pagetotal']	=	$size > 0 ? ceil( $data['total'] / $size ) : 1;
		$data['pagenum']	=	$page < $data['pagetotal'] ? $page : ( $data['pagetotal'] <= 0 ? 1 : $data['pagetotal'] );
		$data['start']		=	$start;
		$data['size']		=	$size < 1 ? $data['total'] : $size;
		$field		=	'a.auth_id,a.auth_name,a.auth_type,b.auth_name as auth_parent,a.auth_url,a.auth_insert_time,a.auth_sort,a.auth_desc';
		$auth_type_arr	=	array( '菜单权限', '操作权限', '页面权限' );

		$data['rows']		=	$this->amodel->GetAllAuth( $field, $condition );

		foreach ($data['rows'] as $k => &$v) {
			$v['auth_type']		=	$auth_type_arr[$v['auth_type']];
			$v['auth_parent']	=	empty( $v['auth_parent'] ) ? '' : $v['auth_parent'];
			$v['auth_insert_time']	=	$v['auth_insert_time'] == 0 ? '' : date( 'Y-m-d H:i:s', $v['auth_insert_time'] );
		}

		EchoJson( TRUE, SUCCESS, '获取数据成功', $data );
	}

	/**
	 * 添加权限
	 */
	public function AjaxAddAuth() {
		// 获取参数
		$data['auth_name']	=	GetParams( 'auth_name', 2 );
		$data['auth_parent']=	GetParams( 'auth_parent', 1 );
		$data['auth_type']	=	GetParams( 'auth_type', 1 );
		$data['auth_url']	=	GetParams( 'auth_url', 2, '' );
		$data['auth_desc']	=	GetParams( 'auth_desc', 2, '' );
		$data['auth_icon']	=	GetParams( 'auth_icon', 2, '' );
		$data['auth_sort']	=	GetParams( 'auth_sort', 1, 0 );
		$data['auth_insert_time']	=	time();

		// 只有超级管理员可以添加
		// if ( $this->_admin['al_id'] != 1 ) EchoJson( FALSE, 1000, '只有超级管理员可以添加' );
		// 添加到库
		if ( $this->amodel->InsertAuth( $data ) ) {
			EchoJson( TRUE, SUCCESS, '添加成功' );
		}
		EchoJson( FALSE, OPERATE_FALSE, '添加失败' );
	}

	/**
	 * 编辑权限
	 */
	public function AjaxEditAuth() {
		// 获取参数
		$auth_id	=	GetParams( 'auth_id', 1 );
		$data['auth_name']	=	GetParams( 'auth_name', 2 );
		$data['auth_parent']=	GetParams( 'auth_parent', 1 );
		$data['auth_sort']	=	GetParams( 'auth_sort', 1, 0 );
		$data['auth_type']	=	GetParams( 'auth_type', 1, 0 );
		$data['auth_url']	=	GetParams( 'auth_url', 2, '' );
		$data['auth_desc']	=	GetParams( 'auth_desc', 2, '' );
		$data['auth_icon']	=	GetParams( 'auth_icon', 2, '' );
		// 验证参数
		// MustStr( $data['auth_name'] );
		// MustInt( array( $data['auth_parent'], $auth_id ) );
		// 更新
		if ( $this->amodel->UpdateAuth( $auth_id, $data ) ) EchoJson( TRUE, SUCCESS, '操作成功' );
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

	/**
	 * 删除权限
	 */
	public function AjaxDelAuth() {
		// 获取参数
		$auth_id	=	GetParams( 'auth_id', 1 );
		// 只有超级管理员可以删除
		// if ( $this->_admin['al_id'] != 1 ) EchoJson( FALSE, 1000, '只有超级管理员可以删除' );
		// 删除
		if ( $this->amodel->DelAuth( $auth_id ) ) EchoJson( TRUE, SUCCESS, '操作成功' );
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

	/**
	 * 修改等级权限关联
	 */
	public function AjaxChangeLevelRelation() {
		// 获取参数
		$al_id		=	GetParams( 'al_id', 1 );
		$auth_id	=	GetParams( 'auth_id', 1 );
		$change		=	GetParams( 'change', 1, -1 );

		// 验证参数
		// MustInt( array( $al_id, $auth_id ) );
		if ( $al_id < 1 || $auth_id < 1 || $change < 0 ) {
			EchoJson( FALSE, PARAM_FALSE, '参数错误' );
		}

		// 判断是否有权限修改
		$this->load->model( 'AdminModel', 'adminmodel' );
		$level	=	$this->adminmodel->GetLevelRange( $al_id, '>' );
		// 超级管理员能够修改自己的
		if ( !isset( $level[$this->_admin['al_id']] ) ) {
			if ( $this->_admin['al_id'] == 1 ) {
				EchoJson( FALSE, FORBIDDEN, '超级管理员等级权限不能删除' );
			} else {
				EchoJson( FALSE, Auth_FALSE, '没有权限' );
			}
		}

		// 判断等级是否绑定此权限
		$this->load->model( 'AuthModel', 'authmodel' );
		$isexist	=	$this->authmodel->IsExistLevelRelation( $al_id, $auth_id );
		if ( $isexist && $change == 0 ) {
			if ( $this->authmodel->UnRelateLevelAuth( $al_id, $auth_id ) ) {
				EchoJson( TRUE, SUCCESS, '操作成功' );
			}
			EchoJson( TRUE, OPERATE_FALSE, '操作失败' );
		} else if ( !$isexist && $change == 1 ) {
			if ( $this->authmodel->RelateLevelAuth( $al_id, $auth_id ) ) {
				EchoJson( TRUE, SUCCESS, '操作成功' );
			}
			EchoJson( TRUE, OPERATE_FALSE, '操作失败' );
		}
		EchoJson( TRUE, SUCCESS, '操作成功' );
	}

	public function AjaxDelLevel() {
		// 获取参数
		$al_id	=	GetParams( 'al_id', 1, 0 );

		// 只有超级管理员可以删除
		// if ( $this->_admin['al_id'] != 1 ) EchoJson( FALSE, 1000, '只有超级管理员可以删除' );

		if ( $al_id == 1 ) EchoJson( FALSE, FORBIDDEN, '不能删除超级管理员等级' );
		// 删除
		if ( $this->amodel->DelLevel( $al_id ) ) EchoJson( TRUE, SUCCESS , '操作成功' );
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

	public function AjaxAddLevel() {
		// 获取参数
		$al_name	=	GetParams( 'al_name', 2 );
		$al_level	=	GetParams( 'al_level', 1 );
		// 判断参数
		// MustInt( $al_level );
		// MustStr( $al_name );
		if ( $al_level < 0 ) EchoJson( FALSE, PARAM_FALSE, '参数错误' );
		// 只有超级管理员可以添加
		// if ( $this->_admin['al_id'] != 1 ) EchoJson( FALSE, 1000, '只有超级管理员可以添加' );
		// 添加
		if ( $this->amodel->AddLevel( $al_name, $al_level ) ) {
			EchoJson( TRUE, SUCCESS, '操作成功' );
		}
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

}

?>