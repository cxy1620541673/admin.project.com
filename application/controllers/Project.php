<?php

class Project extends PW_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'ProjectModel', 'projectmodel' );
	}

	public function ListProject() {
		$this->display( 'project/listproject' );
	}

	public function AddProject() {
		$this->display( 'project/addproject' );
	}

	public function EditProject() {
		$p_id	=	GetParams( 'p_id', 1, 0 );
		$data['project_info']	=	$this->projectmodel->GetProjectById( $p_id );
		$this->display( 'project/editproject', $data );
	}

	public function AjaxGetProjectList() {
		// 获取参数
		$page	=	GetParams( 'page', 1 );
		$size	=	GetParams( 'size', 1 );

		$p_name		=	GetParams( 'p_name', 2, '' );
		$p_domain	=	GetParams( 'p_domain', 2, '' );

		// 初始化参数
		$condition	=	array('where'=>array(),'or_where'=>array(),'limit'=>array(),'order_by'=>'');
		$start	=	0;
		if ( $page < 1 ) $page	=	1;
		if ( $size < 1 && $size != -1 ) $size	=	10;
		if ( $size != -1 ) $start	=	( $page - 1 ) * $size;

		// 组织查询条件
		if ( $size != -1 ) {
			$condition['limit']['start']	=	$start;
			$condition['limit']['size']		=	$size;
		}
		if ( !empty( $p_name ) )	$condition['where']['p_name LIKE']	=	'%'.$p_name.'%';
		if ( !empty( $p_domain ) )	$condition['where']['p_domain LIKE']	=	'%'.$p_domain.'%';
		$condition['order_by']	=	'p_create_time DESC';

		// 获取数据
		$data['total']	=	intval( $this->projectmodel->CountProject( $condition ) );
		$data['pagetotal']	=	$size > 0 ? ceil( $data['total'] / $size ) : 1;
		$data['pagenum']	=	$page < $data['pagetotal'] ? $page : ( $data['pagetotal'] <= 0 ? 1 : $data['pagetotal'] );
		$data['start']		=	$start;
		$data['size']		=	$size < 1 ? $data['total'] : $size;
		$field				=	'p_id,p_name,p_domain,p_create_user,p_desc,p_path,p_belong,p_create_time';
		$data['rows']		=	$this->projectmodel->GetAllProject( $field, $condition );
		foreach ($data['rows'] as &$v) {
			$v['p_create_time']	=	date( 'Y-m-d H:i:s', $v['p_create_time'] );
		}

		// 输出数据
		EchoJson( TRUE, SUCCESS, '获取数据成功', $data );
	}

	public function AjaxAddProject() {
		// 获取参数
		$data['p_name']		=	GetParams( 'p_name', 2 );
		$data['p_path']		=	GetParams( 'p_path', 2 );
		$data['p_domain']	=	GetParams( 'p_domain', 2 );
		$data['p_belong']	=	GetParams( 'p_belong', 2 );
		$data['p_desc']		=	GetParams( 'p_desc', 2, '' );

		$data['p_create_user_id']	=	$this->_admin['admin_id'];
		$data['p_create_user']		=	$this->_admin['admin_name'];
		$data['p_create_time']		=	time();

		// 添加到库
		if ( $this->projectmodel->InsertProject( $data ) ) {
			EchoJson( TRUE, SUCCESS, '添加成功' );
		}
		EchoJson( FALSE, OPERATE_FALSE, '添加失败' );
	}

	public function AjaxDelProject() {
		// 获取参数
		$p_id	=	GetParams( 'p_id', 1 );

		// 删除
		if ( $this->projectmodel->DelProject( $p_id ) )  EchoJson( TRUE, SUCCESS, '操作成功' );
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

	public function AjaxEditProject() {
		// 获取参数
		$p_id	=	GetParams( 'p_id', 1 );
		$data['p_name']		=	GetParams( 'p_name', 2 );
		$data['p_path']		=	GetParams( 'p_path', 2 );
		$data['p_domain']	=	GetParams( 'p_domain', 2 );
		$data['p_belong']	=	GetParams( 'p_belong', 2 );
		$data['p_desc']		=	GetParams( 'p_desc', 2, '' );

		// 更新数据
		if ( $this->projectmodel->UpdateProject( $p_id, $data ) ) EchoJson( TRUE, SUCCESS, '操作成功' );
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

}

?>