<?php 

class File {

	public function __construct() {}

	public static function Del( $dir ) {
		$dir	=	rtrim( $dir, '/' );
		// 判断是路径还是文件
		if ( !is_dir( $dir ) ) {	// 文件类型
			if ( is_file( $dir ) ) {
				unlink( $dir );	// 删除文件
			}
		} else {	// 文件夹类型
			$files	=	self::scan( $dir );
			foreach ($files as $val) {
				$path	=	$dir.'/'.$val;
				if ( is_dir( $path ) ) {
					self::Del( $path );
				} else {
					unlink( $path );	// 删除文件
				}
			}
			rmdir( $dir );	// 删除文件夹
		}
		clearstatcache();	// 清除缓存，is_file的结果会自动缓存
	}

	public static function scan( $dir ) {
		$except	=	array( '.', '..', '$RECYCLE.BIN' );
		$files	=	scandir( $dir );
		return array_diff( $files, $except );
	}

}

?>