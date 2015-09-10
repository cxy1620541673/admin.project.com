<?php 

class PW_Model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database( 'default' );
	}

	public function GetTableData( $table, $field = '*', $condition = array() ) {
		if ( isset( $condition['where'] ) && !empty( $condition['where'] ) ) {
			$this->db->where( $condition['where'] );
		}
		if ( isset( $condition['or_where'] ) && !empty( $condition['or_where'] ) ) {
			$this->db->or_where( $condition['or_where'] );
		}
		if ( isset( $condition['limit'] ) && !empty( $condition['limit'] ) ) {
			$this->db->limit( $condition['limit']['size'], $condition['limit']['start'] );
		}
		if ( isset( $condition['order_by'] ) && !empty( $condition['order_by'] ) ) {
			$this->db->order_by( $condition['order_by'] );
		}
		$this->db->select( $field );
		$query	=	$this->db->get( $table );
		return $query->result_array();
	}

	public function CountTable( $table, $condition = array() ) {
		if ( isset( $condition['where'] ) ) {
			$this->db->where( $condition['where'] );
		}
		if ( isset( $condition['or_where'] ) ) {
			$this->db->or_where( $condition['or_where'] );
		}
		$this->db->select( 'COUNT(1) AS total' );
		$query	=	$this->db->get( $table );
		return $query->row()->total;
	}

}
