<?php

/**
 * @author		chenxuyi
 * @name		redis缓存类
 * @description	封装redis各种操作
 *              设置主动缓存，需要配合脚本实现主动缓存
 */

class ActiveRedis {

	protected $CI;			// CI对象

	private $redis;			// redis对象
	private $config;		// 系统配置

	// 构造行数	-----------------------------------------------------------------------------------------------
	
	public function __construct( $config = array() ) {
		// 获取配置
		$this->CI	=	&get_instance();
		// 加载系统配置项
		$this->config	=	$this->CI->config->item( 'redis' );
		$host	=	isset( $config['host'] ) ? $config['host'] : $this->config['host'];
		$port	=	isset( $config['port'] ) ? $config['port'] : $this->config['port'];
		$auth	=	isset( $config['auth'] ) ? $config['auth'] : $this->config['auth'];

		// 新建redis对象
		$conn	=	$pass	=	FALSE;
		$this->redis		=	new Redis();
		$conn	=	$this->redis->connect( $host, $port );
		if ( !empty( $auth ) ) {
			$pass	=	$this->redis->auth( $auth );
		} else {
			$pass	=	TRUE;
		}
		if ( $conn && $pass ) return $this->redis;
		return FALSE;
	}

	// 常规操作	-----------------------------------------------------------------------------------------------

	/**
	 * 获取相关key
	 * @param	string	$key	key值
	 * @return 	array
	 */
	public function keys( $key ) {
		return $this->redis->keys( $key );
	}

	/**
	 * 获取相关key的数量
	 * @param	string	$key	key值
	 * @return 	array
	 */
	public function keysNum( $key ) {
		return count( $this->redis->keys( $key ) );
	}

	/**
	 * 获取key的过期时间
	 * @description	ttl=0时，key仍然是存在的
	 * @param	string	$key	[description]
	 * @return	int/boolean
	 */
	public function ttl( $key ) {
		return $this->redis->ttl( $key );
	}

	/**
	 * 设置key的过期时间
	 * @param 	string 	$key
	 * @param  	integer $time 	超时时间，单位秒
	 * @return 	boolean
	 */
	public function expire( $key, $time = -2 ) {
		if ( $time == -2 ) $time	=	$this->config['time'];
		return $this->redis->expire( $key, $time );
	}

	/**
	 * 删除redis
	 * @param 	string 	$key
	 * @return 	boolean
	 */
	public function del( $key ) {
		return $this->redis->del( $key );
	}

	// String类型操作	-----------------------------------------------------------------------------------------------

	/**
	 * 设置string类型redis
	 * @param 	string 	$key
	 * @param 	array 	$data	数据，会自动json_encode
	 * @param 	integer $time 	过期时间
	 */
	public function set( $key, $data, $time = -2 ) {
		$val	=	json_encode( $data );
		return $this->redis->set( $key, $val ) && $this->expire( $key, $time );
	}

	/**
	 * 获取string类型redis
	 * @param 	string 	$key
	 * @return 	array 	返回json数据
	 */
	public function get( $key ) {
		$data	=	$this->redis->get( $key );
		if ( $this->config['auto'] ) {
			$ttl	=	$this->ttl( $key );
			// 如果ttl小于设置，且不是永久保存和未过期，则准备主动缓存
			if ( $ttl < $this->config['remain'] && $ttl > -1 ) {
				// 此处需要脚本配合，因为无论sql是否已经存入队列都会重复存入，脚本每处理一条sql需要删除相同的sql
				$this->rpush( $this->config['autokey'], $key, -1 );
			}
		}
		return $data;
	}

	// List类型操作	-----------------------------------------------------------------------------------------------

	public function rpush( $key, $data, $time = -2 ) {
		$val	=	json_encode( $data );
		return $this->redis->rpush( $key, $val ) && $this->expire( $key, $time );
	}

	public function lpop( $key ) {
		return $this->redis->lpop( $key );
	}

	public function llen( $key ) {
		return $this->redis->llen( $key );
	}

	public function lrange( $key, $start = 0, $end = -1 ) {
		return $this->redis->lrange( $key, $start, $end );
	}

	public function lrem( $key, $data, $count = 0 ) {
		return $this->redis->lrem( $key, $data, $count );
	}

	// Set类型操作	-----------------------------------------------------------------------------------------------
	// 主动缓存操作	-----------------------------------------------------------------------------------------------



}

?>