<?php

class TaskModel extends PW_Model {

	private $table	=	'task';

	public function __construct() {
		parent::__construct();
	}

	public function CountTask( $condition = array() ) {
		if ( isset( $condition['where'] ) ) {
			$this->db->where( $condition['where'] );
		}
		if ( isset( $condition['or_where'] ) ) {
			$this->db->where( $condition['or_where'], NULL, FALSE );
		}
		$this->db->select( 'COUNT(1) AS total' );
		$query	=	$this->db->get( $this->table );
		return $query->row()->total;
	}

	public function GetAllTask( $field = '*', $condition = array() ) {
		$result	=	$this->GetTableData( $this->table, $field, $condition );
		return $result;
	}

	public function GetTaskById( $t_ids ) {
		if ( is_array( $t_ids ) ) {
			$this->db->where_in( 't_id', $t_ids );
		} else {
			$this->db->where( 't_id', $t_ids );
		}
		$query	=	$this->db->get( $this->table );
		$result	=	is_array( $t_ids ) ? $query->result_array() : $query->row_array();
		return $result;
	}

	public function InsertTask( $data ) {
		if ( $this->db->insert( $this->table, $data ) )	return TRUE;
		return FALSE;
	}

	public function UpdateTask( $t_id, $data ) {
		$this->db->where( 't_id', $t_id );
		if ( $this->db->update( $this->table, $data ) ) return TRUE;
		return FALSE;
	}

	public function DelTask( $t_id ) {
		if ( $this->db->delete( $this->table, array( 't_id'=>$t_id ) ) ) {
			return TRUE;
		}
		return FALSE;
	}

}

?>