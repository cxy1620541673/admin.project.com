<?php 

class ProjectModel extends PW_Model {

	private $table	=	'project';

	public function __construct() {
		parent::__construct();
	}

	public function CountProject( $condition = array() ) {
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

	public function GetAllProject( $field = '*', $condition = array() ) {
		$result	=	$this->GetTableData( $this->table, $field, $condition );
		return $result;
	}

	public function GetProjectById( $p_ids ) {
		if ( is_array( $p_ids ) ) {
			$this->db->where_in( 'p_id', $p_ids );
		} else {
			$this->db->where( 'p_id', $p_ids );
		}
		$query	=	$this->db->get( $this->table );
		$result	=	is_array( $p_ids ) ? $query->result_array() : $query->row_array();
		return $result;
	}

	public function InsertProject( $data ) {
		if ( $this->db->insert( $this->table, $data ) ) {
			return TRUE;
		}
		return FALSE;
	}

	public function DelProject( $p_id ) {
		if ( $this->db->delete( $this->table, array( 'p_id'=>$p_id ) ) ) {
			return TRUE;
		}
		return FALSE;
	}

	public function UpdateProject( $p_id, $data ) {
		$this->db->where( 'p_id', $p_id );
		if ( $this->db->update( $this->table, $data ) ) return TRUE;
		return FALSE;
	}

}