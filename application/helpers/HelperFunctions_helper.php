<?php 

/**
 * 自动转换数字为整型
 * @param	array	&$data	需要装换的数据
 */
function AutoToInt( &$data ) {
	if ( is_array( $data ) ) {
		foreach ($data as $k => &$v) {
			if ( preg_match( "/^[0-9]+$/", $v ) ) {
				$v	=	intval( $v );
			}
		}
	} else {
		if ( preg_match( "/^[0-9]+$/", $data ) ) {
			$data	=	intval( $data );
		}
	}
}

/**
 * 获取url参数，POST和GET
 * @param	string	$key		键名
 * @param	integer	$type		类型，0:自动变换, 1:int, 2:string, 其他:报错
 * @param	integer	$isdefault	默认值
 */
function GetParams( $key = '', $type = 0, $default = NULL ) {
	$data	=	array();

	// 组合POST和GET的数据
	foreach ($_POST as $k => $v) {
		$data[$k]	=	$v;
		unset( $k, $v );
	}
	foreach ($_GET as $k => $v) {
		if ( !isset( $data[$k] ) ) {
			$data[$k]	=	$v;
			unset( $k, $v );
		}
	}

	// 返回数据
	$res	=	'';
	if ( empty( $key ) ) return $data;
	if ( !isset( $data[$key] ) ) {
		if ( $default !== NULL ) return $default;
		EchoJson( FALSE, PARAM_FALSE, $key.'错误', NULL );
	}
	if ( $type === 0 ) {
		AutoToInt( $data );
		$res	=	$data[$key];
		if ( $default === NULL )	{
			if ( is_int( $res ) ) {
				MustInt( $res, $key.'错误' );
			} else if ( is_string( $res ) ) {
				MustStr( $res, $key.'错误' );
			}
		}
	} else if ( $type === 1 ) {
		if ( preg_match( "/^(\-)?[0-9]+$/", $data[$key] ) ) {
			return intval( $data[$key] );
		}
		$res	=	'';
		if ( $default === NULL )	MustInt( $res, $key.'错误' );
	} else if ( $type === 2 ) {
		$res	=	$data[$key];
		if ( $default === NULL )	MustStr( $res, $key.'错误' );
	} else {
		throw new Exception("错误的数据类型请求", 1);
	}
	return $default !== NULL && empty( $res ) ? $default : $res;
}

/**
 * 输出JSON
 * @param boolean $status 	状态
 * @param integer $code   	状态码
 * @param string  $msg    	提示信息
 * @param string  $data   	数据
 * @param booelan $exitflag 是否退出程序
 */
function EchoJson( $status = FALSE, $code = 0, $msg = '', $data = '', $exitflag = TRUE ) {
	$data	=	array(
		'status'	=>	$status,
		'code'		=>	$code,
		'msg'		=>	$msg,
		'data'		=>	$data,
	);
	if ( $exitflag ) exit( json_encode( $data ) );
	echo json_encode( $data );
}

function EchoArr( $status = FALSE, $code = 0, $msg = '', $data = '' ) {
	$data	=	array(
		'status'	=>	$status,
		'code'		=>	$code,
		'msg'		=>	$msg,
		'data'		=>	$data,
	);
	print_r( $data );
	exit();
}

/**
 * 必须是INT值
 * @param array/int $data [description]
 */
function MustInt( $data, $msg = '参数错误' ) {
	if ( !is_array( $data ) ) {
		if ( !is_integer( $data ) ) {
			EchoJson( FALSE, PARAM_FALSE, $msg, NULL );
		}
	} else {
		foreach ($data as $v) {
			if ( !is_integer( $v ) ) {
				EchoJson( FALSE, PARAM_FALSE, $msg, NULL );
			}
		}
	}
	return $data;
}

/**
 * 必须是String值
 * @param array/string $data [description]
 */
function MustStr( $data, $msg = '参数错误' ) {
	if ( !is_array( $data ) ) {
		if ( empty( $data ) && $data != '0' ) {
			EchoJson( FALSE, PARAM_FALSE, $msg, NULL );
		}
	} else {
		foreach ($data as $v) {
			if ( empty( $v ) && $v != '0' ) {
				EchoJson( FALSE, PARAM_FALSE, $msg, NULL );
			}
		}
	}
	return $data;
}

/**
 * 设置SESSION
 * @param string $key  键名
 * @param array  $data 键值
 */
function SetSession( $key, $data ) {
	if ( !empty( $data ) ) {
		$_SESSION[$key]	=	$data;
		return TRUE;
	}
	return FALSE;
}

/**
 * 获取SESSION
 * @param string $key 键名
 */
function GetSession( $key ) {
	if ( isset( $_SESSION[$key] ) ) return $_SESSION[$key];
	return array();
}

/**
 * 删除session
 * @param string $key 键名
 */
function DelSession( $key ) {
	if ( isset( $_SESSION[$key] ) ) {
		unset( $_SESSION[$key] );
		return TRUE;
	}
	return FALSE;
}

?>