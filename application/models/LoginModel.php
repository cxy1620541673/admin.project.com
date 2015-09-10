<?php

/**
 * @createtime	2015-08-07 10:00:00
 * @author		chenxyi
 */

class LoginModel extends PW_Model {

	private $_user	=	'admin_user';	// 用户表

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 获取管理员信息
	 * @param 	array	$where	选择条件
	 */
	public function GetAdminInfo( $where ) {
		$query	=	$this->db->get_where( $this->_user, $where );
		$result	=	$query->row_array();
		return $result;
	}

	/**
	 * 设置管理员最近登录时间
	 * @param 	int	$admin_id 	管理员id
	 */
	public function SetLastLoginTime( $admin_id ) {
		$this->db->where( 'admin_id', $admin_id );
		if ( $this->db->update( $this->_user, array( 'admin_last_login_time'=>time() ) ) ) return TRUE;
		return FALSE;
	}

	/**
	 * 修改用户信息
	 * @param [type] $admin_id [description]
	 * @param [type] $data     [description]
	 */
	public function ModifyAdminInfo( $admin_id, $data ) {
		if ( empty( $data ) ) return FALSE;
		$this->db->where( 'admin_id', $admin_id );
		if ( $this->db->update( $this->_user, $data ) ) return TRUE;
		return FALSE;
	}

}

?>