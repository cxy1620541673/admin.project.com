<?php 

/**
 * @createtime	2015-08-07 10:00:00
 * @author		chenxyi
 */

class AdminModel extends PW_Model {

	private $table		=	'admin_user';
	private $admin_auth	=	'admin_authority';
	private $lvl		=	'admin_level';
	private $lvl_auth	=	'level_authority';

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 计算管理员数量
	 * @param array $condition [description]
	 */
	public function CountAdmin( $condition = array() ) {
		$total	=	$this->CountTable( $this->table, $condition );
		return $total;
	}

	/**
	 * 获取所有管理员的信息
	 * @param string $field     [description]
	 * @param array  $condition [description]
	 */
	public function GetAllAdmin( $field = '*', $condition = array() ) {
		$result	=	$this->GetTableData( $this->table, $field, $condition );
		return $result;
	}

	/**
	 * 新增管理员
	 * @param [type] $data [description]
	 */
	public function InsertAdmin( $data ) {
		if ( $this->db->insert( $this->table, $data ) ) return TRUE;
		return FALSE;
	}

	/**
	 * 根据id获取管理员信息
	 * @param [type] $admin_ids [description]
	 */
	public function GetAdminById( $admin_ids ) {
		if ( is_array( $admin_ids ) ) {
			$this->db->where_in( 'admin_id', $admin_ids );
		} else {
			$this->db->where( 'admin_id', $admin_ids );
		}
		$query	=	$this->db->get( $this->table );
		$result	=	is_array( $admin_ids ) ? $query->result_array() : $query->row_array();
		return $result;
	}

	/**
	 * 更新管理员信息
	 * @param [type] $admin_id [description]
	 * @param [type] $data     [description]
	 */
	public function UpdateAdmin( $admin_id, $data ) {
		$this->db->where( 'admin_id', $admin_id );
		if ( $this->db->update( $this->table, $data ) ) return TRUE;
		return FALSE;
	}

	/**
	 * 删除管理员
	 * @param [type] $admin_id [description]
	 */
	public function DelAdmin( $admin_id ) {
		if ( $this->db->delete( $this->table, array( 'admin_id'=>$admin_id ) ) && $this->db->delete( $this->admin_auth, array( 'admin_id'=>$admin_id ) ) ) 
			return TRUE;
		return FALSE;
	}

	/**
	 * 获取所有管理员等级
	 */
	public function GetAllLevel() {
		$this->db->order_by( 'al_level ASC' );
		$query	=	$this->db->get( $this->lvl );
		$result	=	$query->result_array();
		$level	=	array();
		if ( !empty( $result ) ) {
			foreach ($result as $k => $v) {
				$level[$v['al_id']]	=	$v;
			}
		}
		return $level;
	}

	/**
	 * 通过比较获取等级的一个范围，也可获取自己的等级
	 * @param	int		$al_id 等级权限id
	 * @param	boolean	$mark  符号，>:权限更大, <:权限更小...
	 */
	public function GetLevelRange( $al_id, $mark = FALSE ) {
		$on	=	'a.al_level = b.al_level';
		switch ( $mark ) {
			case '=':$on='a.al_level = b.al_level';break;
			case '<':$on='a.al_level < b.al_level';break;
			case '>':$on='a.al_level > b.al_level';break;
			case '<=':$on='a.al_level <= b.al_level';break;
			case '>=':$on='a.al_level >= b.al_level';break;
			default:break;
		}
		$this->db->select( 'b.*' );
		$this->db->from( $this->lvl.' AS a' );
		$this->db->join( $this->lvl.' AS b', $on, 'inner' );
		$this->db->where( 'a.al_id', $al_id );
		$query	=	$this->db->get();
		$result	=	$query->result_array();
		$level	=	array();
		if ( !empty( $result ) ) {
			foreach ($result as $k => $v) {
				$level[$v['al_id']]	=	$v;
			}
		}
		return $level;
	}

}