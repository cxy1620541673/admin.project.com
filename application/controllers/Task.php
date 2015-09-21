<?php 

class Task extends PW_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model( 'ProjectModel', 'projectmodel' );
		$this->load->model( 'TaskModel', 'taskmodel' );
		$this->load->model( 'AdminModel', 'adminmodel' );
	}

	public function ListTask() {
		$data['executor']	=	$this->adminmodel->GetAllAdmin();
		$this->display( 'task/listtask', $data );
	}

	public function AddTask() {
		$data['project']	=	$this->projectmodel->GetAllProject();
		$data['executor']	=	$this->adminmodel->GetAllAdmin();
		$this->display( 'task/addtask', $data );
	}

	public function EditTask() {
		$t_id	=	GetParams( 't_id', 1, 0 );
		$data['task_info']	=	$this->taskmodel->GetTaskById( $t_id );
		$data['project']	=	$this->projectmodel->GetAllProject();
		$data['executor']	=	$this->adminmodel->GetAllAdmin();
		$data['task_info']['p_id']	=	explode( ',', $data['task_info']['p_id'] );
		$this->display( 'task/edittask', $data );
	}

	public function EditTaskStep() {
		$data['t_id']		=	GetParams( 't_id', 1, 0 );
		$data['task_info']	=	$this->taskmodel->GetTaskById( $data['t_id'] );
		$this->display( 'task/edittaskstep', $data );
	}

	public function AjaxGetTaskList() {
		// 获取参数
		$page	=	GetParams( 'page', 1 );
		$size	=	GetParams( 'size', 1 );

		$t_title		=	GetParams( 't_title', 2, '' );
		$t_executor_id	=	GetParams( 't_executor_id', 1, 0 );

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
		if ( !empty( $t_title ) )		$condition['where']['t_title LIKE']		=	'%'.$t_title.'%';
		if ( !empty( $t_executor_id ) )	$condition['where']['t_executor_id']	=	$t_executor_id;
		$condition['order_by']	=	't_create_time DESC';

		// 获取数据
		$status	=	array( '未指派','已指派未开始','正在进行','代码完成','测试','上线','放弃任务' );
		$executor	=	$this->adminmodel->GetAllAdmin();
		$executorArr	=	array();
		foreach ($executor as $val) {
			$executorArr[$val['admin_id']]	=	$val['admin_name'];
		}
		// 获取所有项目
		$project	=	$this->projectmodel->GetAllProject();
		$projectArr	=	array();
		foreach ($project as $v) {
			$projectArr[$v['p_id']]	=	$v['p_name'];
		}

		$data['total']		=	intval( $this->taskmodel->CountTask( $condition ) );
		$data['pagetotal']	=	$size > 0 ? ceil( $data['total'] / $size ) : 1;
		$data['pagenum']	=	$page < $data['pagetotal'] ? $page : ( $data['pagetotal'] <= 0 ? 1 : $data['pagetotal'] );
		$data['start']		=	$start;
		$data['size']		=	$size < 1 ? $data['total'] : $size;
		$field				=	't_id,t_title,p_id,t_executor_id,t_start_time,t_end_time,t_online_time,t_status,t_create_time';
		$data['rows']		=	$this->taskmodel->GetAllTask( $field, $condition );
		foreach ($data['rows'] as &$v) {
			$v['t_status']		=	$status[$v['t_status']].' [ <span data-id="'.$v['t_id'].'"><a class="edit-status" href="javascript:;" data-val="2">开始</a> | <a class="edit-status" href="javascript:;" data-val="3">完成</a> | <a class="edit-status" href="javascript:;" data-val="4">测试</a> | <a class="edit-status" href="javascript:;" data-val="5">上线</a> | <a class="edit-status" href="javascript:;" data-val="6">放弃</a></span> ] ';
			$v['t_start_time']	=	empty( $v['t_start_time'] ) ? '' : date( 'Y/m/d H:i:s', $v['t_start_time'] );
			$v['t_end_time']	=	empty( $v['t_end_time'] ) ? '' : date( 'Y/m/d H:i:s', $v['t_end_time'] );
			$v['t_online_time']	=	empty( $v['t_online_time'] ) ? '未上线' : date( 'Y/m/d H:i:s', $v['t_online_time'] );
			$v['t_create_time']	=	date( 'Y/m/d H:i:s', $v['t_create_time'] );
			$v['t_executor_id']	=	empty( $v['t_executor_id'] ) ? '<span class="appoint" data-id="'.$v['t_id'].'" data-title="'.$v['t_title'].'" data-target="#stack1" data-toggle="modal"><i class="icon-hand-right pointer"></i></span>' : $executorArr[$v['t_executor_id']];
			$p_id	=	explode( ',', $v['p_id'] );
			$p_name	=	array();
			foreach ($p_id as $val) {
				$p_name[$val]	=	$projectArr[$val];
			}
			$v['p_id']	=	implode( ',', $p_name );
		}

		// 输出数据
		EchoJson( TRUE, SUCCESS, '获取数据成功', $data );
	}

	public function AjaxUploadImage() {
		// 文件名信息
		$pathinfo	=	pathinfo( $_FILES['imgFile']['name'] );
		$filename	=	date( 'YmdHis' ).'_'.md5( $pathinfo['filename'] ).'.'.$pathinfo['extension'];

		// 存放的路径
		$filepath	=	$this->GetConfig( 'image_path' );

		$result	=	array();
		if ( move_uploaded_file( $_FILES['imgFile']['tmp_name'], $filepath.$filename ) ) {
			$result['error']	=	0;
			$result['url']		=	$this->GetConfig( 'image_url' ).$filename;
			$result['msg']		=	'上传成功';
		} else {
			$result['error']	=	1;
			$result['url']		=	'';
			$result['msg']		=	'上传失败';
		}
		exit( json_encode( $result ) );
	}

	public function AjaxAddTask() {
		// 获取参数
		$data['t_title']		=	GetParams( 't_title', 2 );
		$data['t_executor_id']	=	GetParams( 't_executor_id', 1, 0 );
		$data['t_desc']			=	GetParams( 't_desc', 2, '' );
		$data['p_id']			=	is_array( $_POST['p_id'] ) ? implode( ',', $_POST['p_id'] ) : '';
		$data['t_create_user_id']	=	$this->_admin['admin_id'];
		$data['t_create_user']		=	$this->_admin['admin_name'];
		$data['t_create_time']		=	time();

		// 数据处理
		if ( !empty( $data['t_executor_id'] ) ) {
			$data['t_status']	=	1;
		}

		// 添加进库
		if ( $this->taskmodel->InsertTask( $data ) ) {
			EchoJson( TRUE, SUCCESS, '操作成功' );
		}
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

	public function AjaxEditTask() {
		// 获取参数
		$t_id	=	GetParams( 't_id', 1 );
		$data['t_title']		=	GetParams( 't_title', 2 );
		$data['t_status']		=	GetParams( 't_status', 1, 0 );
		$data['t_executor_id']	=	GetParams( 't_executor_id', 1, 0 );
		// $data['t_start_time']	=	GetParams( 't_start_time', 2, '' );
		// $data['t_end_time']		=	GetParams( 't_end_time', 2, '' );
		$data['t_desc']			=	GetParams( 't_desc', 2, '' );
		$data['p_id']			=	is_array( $_POST['p_id'] ) ? implode( ',', $_POST['p_id'] ) : '';

		// 数据处理
		// $data['t_start_time']	=	empty( $data['t_start_time'] ) ? 0 : strtotime( $data['t_start_time'] );
		// $data['t_end_time']		=	empty( $data['t_end_time'] ) ? 0 : strtotime( $data['t_end_time'] );
		if ( !empty( $data['t_executor_id'] ) && empty( $data['t_status'] ) ) {
			$data['t_status']	=	1;
		}

		// 更新数据
		if ( $this->taskmodel->UpdateTask( $t_id, $data ) ) EchoJson( TRUE, SUCCESS, '操作成功' );
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

	public function AjaxDelTask() {
		// 获取参数
		$t_id	=	GetParams( 't_id', 1 );

		// 删除
		if ( $this->taskmodel->DelTask( $t_id ) )  EchoJson( TRUE, SUCCESS, '操作成功' );
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

	public function AjaxUpdateTaskStatus() {
		// 获取参数
		$t_id	=	GetParams( 't_id', 1 );
		$data['t_status']	=	GetParams( 't_status', 1, 0 );

		$task_info	=	$this->taskmodel->GetTaskById( $t_id );

		if ( $task_info['t_status'] >= $data['t_status'] ) {	// 阶段不能回退
			EchoJson( FALSE, OPERATE_FALSE, '状态未发生改变' );
		} else if ( $data['t_status'] == 6 && $task_info['t_status'] > 3 ) {	// 不能放弃
			EchoJson( FALSE, OPERATE_FALSE, '此阶段不能放弃任务' );
		}

		switch ( $data['t_status'] ) {
			case 1:	// 指派
				$data['t_executor_id']	=	GetParams( 't_executor_id', 1, 0 );
				$data['t_start_time']	=	0;
				$data['t_end_time']		=	0;
				$data['t_online_time']	=	0;
				break;
			case 2:	// 开始
				$data['t_start_time']	=	time();
				$data['t_end_time']		=	0;
				$data['t_online_time']	=	0;
				break;
			case 3:	// 完成
				if ( empty( $task_info['t_start_time'] ) )	$data['t_start_time']	=	time();
				$data['t_end_time']		=	time();
				$data['t_online_time']	=	0;
				break;
			case 4:	// 测试
				$data['t_start_time']	=	time();
				$data['t_end_time']		=	time();
				$data['t_online_time']	=	0;
				break;
			case 5:	// 上线
				if ( empty( $task_info['t_start_time'] ) )	$data['t_start_time']	=	time();
				if ( empty( $task_info['t_end_time'] ) )	$data['t_end_time']		=	time();
				$data['t_online_time']	=	time();
				break;
			case 6:	// 放弃
				break;
			default:
				EchoJson( FALSE, OPERATE_FALSE, '状态未发生改变' );
				break;
		}

		// 更新数据
		if ( $this->taskmodel->UpdateTask( $t_id, $data ) ) EchoJson( TRUE, SUCCESS, '操作成功' );
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

	public function AjaxSaveStep() {
		// 获取参数
		$t_id	=	GetParams( 't_id', 1 );
		$data['t_content']		=	GetParams( 't_content', 2, '' );

		// 更新数据
		if ( $this->taskmodel->UpdateTask( $t_id, $data ) ) EchoJson( TRUE, SUCCESS, '操作成功' );
		EchoJson( FALSE, OPERATE_FALSE, '操作失败' );
	}

}

?>