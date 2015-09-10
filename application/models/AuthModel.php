<?php

/**
 * @createtime	2015-08-07 10:00:00
 * @author		chenxyi
 */

class AuthModel extends PW_Model {

	private $authority	=	'authority';
	private $admin_auth	=	'admin_authority';
	private $admin		=	'admin_user';
	private $lvl		=	'admin_level';
	private $lvl_auth	=	'level_authority';

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 获取管理员显示的权限菜单
	 * @param 	int	$admin_id 	管理员id
	 */
	public function GetAdminAuth( $admin_id ) {
		// 获取管理员拥有的权限
		$this->db->join( $this->authority, $this->admin_auth.'.auth_id = '.$this->authority.'.auth_id', 'left' );
		$this->db->where( $this->admin_auth.'.admin_id', $admin_id );
		$this->db->order_by( 'auth_parent ASC,auth_sort ASC' );
		$query	=	$this->db->get( $this->admin_auth );
		$authinfo	=	$query->result_array();
		return $authinfo;
	}

	/**
	 * 计算所有权限的数量
	 * @param array $condition [description]
	 */
	public function CountAuth( $condition ) {
		if ( isset( $condition['where'] ) ) {
			$this->db->where( $condition['where'] );
		}
		if ( isset( $condition['or_where'] ) ) {
			$this->db->where( $condition['or_where'], NULL, FALSE );
		}
		$this->db->select( 'COUNT(1) AS total' );
		$query	=	$this->db->get( $this->authority.' AS a ' );
		return $query->row()->total;
	}

	/**
	 * 获取所有的权限
	 * @param string  $field 选择的字段
	 * @param integer $start 开始位置
	 * @param integer $size  数量
	 */
	public function GetAllAuth( $field = '*', $condition = array() ) {
		if ( isset( $condition['where'] ) && !empty( $condition['where'] ) ) {
			$this->db->where( $condition['where'] );
		}
		if ( isset( $condition['or_where'] ) && !empty( $condition['or_where'] ) ) {
			$this->db->where( $condition['or_where'], NULL, FALSE );
		}
		if ( isset( $condition['limit'] ) && !empty( $condition['limit'] ) ) {
			$this->db->limit( $condition['limit']['size'], $condition['limit']['start'] );
		}
		if ( isset( $condition['order_by'] ) && !empty( $condition['order_by'] ) ) {
			$this->db->order_by( $condition['order_by'] );
		}
		$this->db->join( $this->authority.' AS b', 'b.auth_id = a.auth_parent', 'left' );
		$this->db->select( $field );
		$query	=	$this->db->get( $this->authority.' AS a' );
		// print_r($this->db->last_query());die;
		return $query->result_array();
	}

	/**
	 * 获取所有菜单权限
	 */
	public function GetRootAuth() {
		$this->db->order_by( 'auth_sort ASC' );
		$query	=	$this->db->get_where( $this->authority, array( 'auth_parent'=>0, 'auth_type'=>0 ) );
		return $query->result_array();
	}

	/**
	 * 根据id获取权限
	 * @param [type] $auth_ids [description]
	 */
	public function GetAuthById( $auth_ids ) {
		if ( is_array( $auth_ids ) ) {
			$this->db->where_in( 'auth_id', $auth_ids );
		} else {
			$this->db->where( 'auth_id', $auth_ids );
		}
		$query	=	$this->db->get( $this->authority );
		$result	=	is_array( $auth_ids ) ? $query->result_array() : $query->row_array();
		return $result;
	}

	/**
	 * 根据URL获取权限的类型
	 * @param string $url URL
	 */
	public function GetAuthTypeByUrl( $url ) {
		$this->db->select( 'auth_type' );
		$query	=	$this->db->get_where( $this->authority, array( 'auth_url'=>$url ) );
		$result	=	$query->row_array();
		if ( !empty( $result ) ) {
			return intval( $result['auth_type'] );
		}
		return FALSE;
	}

	/**
	 * 新增权限, 新增后自动关联所有超级管理员, 其他管理员不进行关联
	 * @param	array	$data 插入的数据
	 */
	public function InsertAuth( $data ) {
		if ( $this->db->insert( $this->authority, $data ) ) {
			$auth_id	=	$this->db->insert_id();
			$admin_ids	=	$this->GetAdminUserByLevel( 1, 1 );
			$this->RelateLevelAuth( 1, $auth_id );
			foreach ($admin_ids as $v) {
				$this->RelateAdminAuth( $v, $auth_id );
			}
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 更新权限
	 * @param int 	$auth_id 权限id
	 * @param array $data    更新的数据
	 */
	public function UpdateAuth( $auth_id, $data ) {
		$this->db->where( 'auth_id', $auth_id );
		if ( $this->db->update( $this->authority, $data ) ) return TRUE;
		return FALSE;
	}

	/**
	 * 删除权限
	 * @param int $auth_id 权限id
	 */
	public function DelAuth( $auth_id ) {
		if ( $this->db->delete( $this->authority, array( 'auth_id'=>$auth_id ) ) && $this->db->delete( $this->admin_auth, array( 'auth_id'=>$auth_id ) ) ) 
			return TRUE;
		return FALSE;
	}

	/**
	 * 关联管理员和权限
	 * @param	int	$admin_id 管理员id
	 * @param 	int $auth_id  权限id
	 */
	public function RelateAdminAuth( $admin_id, $auth_id ) {
		if ( $this->db->insert( $this->admin_auth, array( 'admin_id'=>$admin_id, 'auth_id'=>$auth_id ) ) ) {
			return $this->db->insert_id();
		}
		return FALSE;
	}

	/**
	 * 关联等级权限
	 * @param [type] $al_id   [description]
	 * @param [type] $auth_id [description]
	 */
	public function RelateLevelAuth( $al_id, $auth_id ) {
		if ( $this->db->insert( $this->lvl_auth, array( 'al_id'=>$al_id, 'auth_id'=>$auth_id ) ) ) {
			return $this->db->insert_id();
		}
		return FALSE;
	}

	/**
	 * 解除关联管理员和权限
	 * @param	int	$admin_id 管理员id
	 * @param 	int $auth_id  权限id
	 */
	public function UnRelateAdminAuth( $admin_id, $auth_id ) {
		if ( $this->db->delete( $this->admin_auth, array( 'admin_id'=>$admin_id, 'auth_id'=>$auth_id ) ) ) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 解除等级权限关联
	 * @param [type] $al_id   [description]
	 * @param [type] $auth_id [description]
	 */
	public function UnRelateLevelAuth( $al_id, $auth_id ) {
		if ( $this->db->delete( $this->lvl_auth, array( 'al_id'=>$al_id, 'auth_id'=>$auth_id ) ) ) {
			$admininfo	=	$this->GetTableData( $this->admin, 'admin_id', array( 'where'=>array( 'al_id'=>$al_id ) ) );
			$admin_ids	=	array();
			foreach ($admininfo as $v) {
				$this->UnRelateAdminAuth( $v['admin_id'], $auth_id );
			}
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 根据等级获取管理员
	 * @param	int	$level 管理员等级，1:超级管理员
	 * @param	int $mark  符号标志，0:<, 1:=, 2:>
	 */
	public function GetAdminUserByLevel( $al_id, $mark = 1 ) {
		switch ($mark) {
			case 0:$this->db->where( 'al_id <', $al_id );break;
			case 1:$this->db->where( 'al_id', $al_id );break;
			case 2:$this->db->where( 'al_id >', $al_id );break;
			default:return array();
		}
		$this->db->select( 'admin_id' );
		$query	=	$this->db->get( $this->admin );
		$data	=	$query->result_array();
		$result	=	array();
		foreach ($data as $k => $v) {
			$result[]	=	$v['admin_id'];
		}
		return $result;
	}

	/**
	 * 判断是否存在此关联
	 * @param [type] $admin_id [description]
	 * @param [type] $auth_id  [description]
	 */
	public function IsExistAdminRelation( $admin_id, $auth_id ) {
		$this->db->select( 'COUNT(*) AS total' );
		$query	=	$this->db->get_where( $this->admin_auth, array( 'admin_id'=>$admin_id, 'auth_id'=>$auth_id ) );
		if ( $query->row()->total > 0 ) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 判断是否存在等级权限关联
	 * @param [type] $al_id   [description]
	 * @param [type] $auth_id [description]
	 */
	public function IsExistLevelRelation( $al_id, $auth_id ) {
		$this->db->select( 'COUNT(*) AS total' );
		$query	=	$this->db->get_where( $this->lvl_auth, array( 'al_id'=>$al_id, 'auth_id'=>$auth_id ) );
		if ( $query->row()->total > 0 ) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 根据等级id获取权限信息
	 * @param array $al_ids [description]
	 */
	public function GetAuthByLevelId( $al_ids = array() ) {
		if ( !is_array( $al_ids ) ) $al_ids	=	array( $al_ids );
		$this->db->select( 'a.al_id,b.*' );
		$this->db->from( $this->lvl_auth.' AS a' );
		$this->db->join( $this->authority.' AS b', 'a.auth_id = b.auth_id', 'inner' );
		$this->db->where_in( 'a.al_id', $al_ids );
		$this->db->order_by( 'a.al_id ASC,b.auth_parent ASC, b.auth_sort ASC' );
		$query	=	$this->db->get();
		$result	=	$query->result_array();
		$auth	=	array();
		return $result;
	}

	/**
	 * 删除等级，关联删除此等级的所有权限和此等级的所有管理员
	 * @param int $al_id [description]
	 */
	public function DelLevel( $al_id ) {
		// 获取该等级的所有管理员id
		$admininfo	=	$this->GetTableData( $this->admin, 'admin_id', array( 'where'=>array( 'al_id'=>$al_id ) ) );
		$admin_ids	=	array();
		foreach ($admininfo as $v) {
			$admin_ids[]	=	$v['admin_id'];
		}

		$flag1	=	$this->db->delete( $this->lvl, array( 'al_id'=>$al_id ) );
		$flag2	=	$this->db->delete( $this->lvl_auth, array( 'al_id'=>$al_id ) );
		$flag3	=	$this->db->delete( $this->admin, array( 'al_id'=>$al_id ) );
		$this->db->where_in( 'admin_id', $admin_ids );
		$flag4	=	empty( $admin_ids ) ? TRUE : $this->db->delete( $this->admin_auth );
		if ( $flag1 && $flag2 && $flag3 && $flag4 ) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 添加等级
	 * @param 	string	$al_name  等级名称
	 * @param 	int		$al_level 等级
	 */
	public function AddLevel( $al_name, $al_level ) {
		$data['al_name']	=	$al_name;
		$data['al_level']	=	$al_level;
		if ( $this->db->insert( $this->lvl, $data ) ) return TRUE;
		return FALSE;
	}

}

?>
