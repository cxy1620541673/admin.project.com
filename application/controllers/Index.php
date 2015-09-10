<?php 

/**
 * @description	首页
 * @createtime	2015-08-07 10:00:00
 * @author		chenxyi
 */

class Index extends PW_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function Index() {
		$data['_admin']	=	$this->_admin;
		$this->display( 'index', $data );
	}

	public function ModPass() {
		$this->display( 'ModifyPassword' );
	}

}

?>