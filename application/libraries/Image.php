<?php

include_once( APPPATH.'libraries/phpqrcode/phpqrcode.php' );

class Image {

	private static $FontSrc			=	'/theme/fonts/validate.ttf';

	/**
	 * 生成验证码图片
	 * @param array $code 文字数组
	 */
	public static function CreateValidate( $len = 4, $mode = 0, $string = '' ) {
		if ( empty( $string ) ) {
			switch ( $mode ) {
				default:$string='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';break;
				case 0:$string='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';break;
				case 1:$string='0123456789';break;
				case 2:$string='abcdefghijklmnopqrstuvwxyz';break;
				case 3:$string='ABCDEFGHIJKLMNOPQRSTUVWXYZ';break;
				case 4:$string='0123456789abcdefghijklmnopqrstuvwxyz';break;
				case 5:$string='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';break;
				case 4:$string='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';break;
			}
		}
		
		$code	=	array();
		for ($i=0; $i < $len; $i++) { 
			$code[]	=	substr( $string, rand( 0, strlen( $string ) - 1 ), 1 );
		}

		// 生成图片
		$imgW	=	$len * 25;
		$imgH	=	34;
		$img	=	imagecreatetruecolor( $imgW, $imgH );
		// 背景颜色
		$bg		=	imagecolorallocate( $img, 250, 250, 250 );
		// 文字颜色
		$text	=	imagecolorallocate( $img, 0, 0, 0 );
		// 噪点颜色
		$point	=	imagecolorallocate( $img, rand( 0, 255 ), rand( 0, 255 ), rand( 0, 255 ) );
		// 填充背景颜色 
		imagefill( $img, 0, 0, $bg );
		// 添加文字
		foreach ($code as $k => $v) {
			imagettftext( $img, 20, rand( -45, 45 ), $k*22+10 , 25, $text, FCPATH.self::$FontSrc, strval( $v ) );
		}
		// 画线
		// for($i = 0; $i < 3; $i++) {
		// 	imageline( $img, 0, rand( 0, 20 ), rand(70, 100 ), rand( 0, 20 ), $text );      
		// }
		// 添加噪点
		for($i = 0; $i < 150; $i++) {  
			imagesetpixel( $img, rand( 1, $imgW ), rand( 1, $imgH ), $point );  
		}
		// 输出图片
		header( "Content-type: image/jpeg" );
		imagejpeg( $img );
		imagedestroy( $img );
		return $code;
	}

	/**
	 * 按百分比创建缩略图
	 * @param [type]  $src     原图路径
	 * @param string  $name    生成名称（包括路径）
	 * @param integer $percent 大小百分比
	 * @param integer $quality 质量百分比
	 */
	public static function CreateThumb( $src, $name = '', $percent = 100, $quality = 100 ) {
		// 获取图片信息
		$imageinfo	=	self::GetSrcInfo( $src );
		// 计算新图长宽
		$newW	=	$imageinfo['width'] * $percent / 100;
		$newH	=	$imageinfo['height'] * $percent / 100;
		// 生成新图
		$newObj	=	imagecreatetruecolor( $newW, $newH );
		imagecopyresampled( $newObj, $imageinfo['srcObj'], 0, 0, 0, 0, $newW, $newH, $imageinfo['width'], $imageinfo['height'] );
		if ( empty( $name ) ) {
			header( 'Content-Type:'.$imageinfo['mime'] );
			$imageinfo['imagefunc']( $newObj, NULL, $quality );
		} else {
			$imageinfo['imagefunc']( $newObj, $name, $quality );
		}
		imagedestroy( $newObj );
		imagedestroy( $imageinfo['srcObj'] );
		unset( $imageinfo );
	}

	/**
	 * 按百分比创建缩略图
	 * @param [type]  $src     原图路径
	 * @param string  $name    生成名称（包括路径）
	 * @param integer $width   目标宽度
	 * @param integer $quality 质量百分比
	 */
	public static function CreateThumbByWidth( $src, $name = '', $width = 100, $quality = 100 ) {
		// 获取图片信息
		$imageinfo	=	self::GetSrcInfo( $src );
		// 计算新图长宽
		$newW		=	$width;
		$percent	=	$imageinfo['width'] / $imageinfo['height'];
		$newH		=	$newW / $percent;
		// 生成新图
		$newObj	=	imagecreatetruecolor( $newW, $newH );
		imagecopyresampled( $newObj, $imageinfo['srcObj'], 0, 0, 0, 0, $newW, $newH, $imageinfo['width'], $imageinfo['height'] );
		if ( empty( $name ) ) {
			header( 'Content-Type:'.$imageinfo['mime'] );
			$imageinfo['imagefunc']( $newObj, NULL, $quality );
		} else {
			$imageinfo['imagefunc']( $newObj, $name, $quality );
		}
		imagedestroy( $newObj );
		imagedestroy( $imageinfo['srcObj'] );
		unset( $imageinfo );
	}

	/**
	 * 生成截图
	 * @param [type]  $src     原图路径
	 * @param string  $name    生成名称（包括路径）
	 * @param integer $newW    新图宽度
	 * @param integer $newH    新图高度
	 * @param integer $quality 新图质量
	 * @param integer $posX    原图横坐标起点
	 * @param integer $posY    原图纵坐标起点
	 */
	public static function CreateShotcut( $src, $name = '', $newW = 100, $newH = 100, $quality = 100, $posX = 0, $posY = 0 ) {
		// 获取图片信息
		$imageinfo	=	self::GetSrcInfo( $src );
		// 生成新图
		$newObj	=	imagecreatetruecolor( $newW, $newH );
		imagecopyresampled( $newObj, $imageinfo['srcObj'], 0, 0, $posX, $posY, $newW, $newH, $newW, $newH );
		if ( empty( $name ) ) {
			header( 'Content-Type:'.$imageinfo['mime'] );
			$imageinfo['imagefunc']( $newObj, NULL, $quality );
		} else {
			$imageinfo['imagefunc']( $newObj, $name, $quality );
		}
		imagedestroy( $newObj );
		imagedestroy( $imageinfo['srcObj'] );
		unset( $imageinfo );
	}

	/**
	 * 获取原图信息，生成对象
	 * @param string $src 图片地址
	 */
	private static function GetSrcInfo( $src ) {
		$imageinfo	=	getimagesize( $src );
		$data['width']		=	$imageinfo[0];
		$data['height']		=	$imageinfo[1];
		$data['type']		=	$imageinfo[2];
		$data['mime']		=	$imageinfo['mime'];
		$data['pathinfo']	=	pathinfo( $src );

		$data['srcObj']		=	'';
		$data['imagefunc']	=	'';
		switch ( $data['type'] ) {
			case 1:$data['srcObj']=imagecreatefromgif($src);$data['imagefunc']='imagegif';break;
			case 2:$data['srcObj']=imagecreatefromjpeg($src);$data['imagefunc']='imagejpeg';break;
			case 3:$data['srcObj']=imagecreatefrompng($src);$data['imagefunc']='imagepng';break;
			default:throw new Exception("Error Image Type In Image.php", 1);break;
		}
		return $data;
	}

	/**
	 * 创建二维码
	 * @param string  $text   二维码内容
	 * @param integer $size   二维码大小
	 * @param string  $logo   二维码logo，没有则NUll或者空
	 * @param string  $level  容错等级
	 * @param integer $margin 边距
	 */
	public static function CreateQRCode( $text = '', $size = 10, $logo = '', $level = 'H', $margin = 1 ) {
		if ( is_null( $logo ) || empty( $logo ) ) {
			QRcode::png( $text, FALSE, $level, $size, $margin );
		} else {
			$QRcodeName	=	APPPATH.'cache/qrcache/'.strval(microtime()*1000000).rand().rand().rand().'.png';
			QRcode::png( $text, $QRcodeName, $level, $size, $margin );
			$qrImg	=	imagecreatefromstring( file_get_contents( $QRcodeName ) );
			$logo	=	imagecreatefromstring( file_get_contents( $logo ) );
			$qrW	=	imagesx( $qrImg );
			$qrH	=	imagesy( $qrImg );
			$logoW	=	imagesx( $logo );
			$logoH	=	imagesy( $logo );
			$qrlogoW	=	$qrW / 5;
			$qrlogoH	=	$qrlogoW / $logoW * $logoH;
			$posX	=	( $qrW - $qrlogoW ) / 2;
			$posY	=	( $qrH - $qrlogoH ) / 2;
			imagecopyresampled( $qrImg, $logo, $posX, $posY, 0, 0, $qrlogoW, $qrlogoH, $logoW, $logoH );
			header( 'Content-Type:image/png' );
			imagepng( $qrImg );
			imagedestroy( $qrImg );
			imagedestroy( $logo );
			unlink( $QRcodeName );
		}
	}

}

?>