<?php

class test extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		set_time_limit(0);
		$this->load->library( 'ActiveRedis' );
		$val	=	$this->activeredis->get( 'name' );
		$ttl	=	$this->activeredis->ttl( 'name' );
		print_r($val);
		print_r($ttl);
		die;
	}

}

?>