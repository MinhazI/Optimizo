<?php

global $webpCache;

error_reporting(E_ALL);
ini_set("display_errors", 1);

use WebPConvert\WebPConvert;

require __DIR__ . '/inc/webp-converter/webp-on-demand-1.inc';

function autoloader($class) {
	if (strpos($class, 'WebPConvert\\') === 0) {
		require_once __DIR__ . '/inc/webp-converter/webp-on-demand-2.inc';
	}
}
spl_autoload_register('autoloader', true, true);

$imageSource  = $_GET['source'];
$documentRoot = $_SERVER['DOCUMENT_ROOT'];

$fullImageSource = $documentRoot . '/' . strstr( $imageSource, 'wp-content' );

if ( ! strstr( $documentRoot, 'htdocs' ) ) {

	$imageDestination = $documentRoot . '/wp-content/optimizoCache/webp-images';

	if ( ! strstr( $imageSource, 'themes' ) ) {
		if ( ! strstr( $imageSource, 'images' ) ) {
			if ( ! strstr( $imageSource, 'uploads' ) ) {
				exit;
			} else {
				$imageDestination = $imageDestination . '/' . strstr( $fullImageSource, 'uploads' );
			}
		} else {
//			$fullImageSource  = $documentRoot . '/' . strstr( $imageSource, 'images' );
			$imageDestination = $imageDestination . '/' . strstr( $fullImageSource, 'images' );

		}
	} else {
//		$fullImageSource  = $documentRoot . '/' . strstr( $imageSource, 'themes' );
		$imageDestination = $imageDestination . '/' . strstr( $fullImageSource, 'themes' );
	}

//var_dump($fullImageSource);

//var_dump($destinationDir);

	createDirectory( $imageDestination );

	$newImageSource = strstr( $imageSource, 'uploads' );

	$imageDestination = $imageDestination . '.webp';

	createDirectory( $imageDestination );


} else {
//	$destinationDir   = __DIR__ . '/wp-content/optimizoCache/webp-images';
//	$imageDestination = $destinationDir . '/' . $imageSource . '.webp';

	$imageDestination = $documentRoot . '/fyp/wp-content/optimizoCache/webp-images/';

	createDirectory( $imageDestination );

	$currentURL = getURL();

	$fullImageSource = $documentRoot . '/' . strstr( $imageSource, 'wp-content' );

	if ( ! strstr( $imageSource, 'themes' ) ) {
		if ( ! strstr( $imageSource, 'images' ) ) {
			if ( ! strstr( $imageSource, 'wp-content' ) ) {
				exit;
			} else {
				$imageDestination = $imageDestination . '/' . strstr( $fullImageSource, 'wp-content' );
			}
		} else {
//			$fullImageSource  = $documentRoot . '/' . strstr( $imageSource, 'images' );
			$imageDestination = $imageDestination . '/' . strstr( $fullImageSource, 'images' );
			var_dump( 'it comes here' );

		}
	} else {
//		$fullImageSource  = $documentRoot . '/' . strstr( $imageSource, 'themes' );
		$imageDestination = $imageDestination . '/' . strstr( $fullImageSource, 'themes' );
		var_dump( 'it comes here' );
	}


	var_dump( $fullImageSource );

}

$options = [
	'show-report'            => true,
	'max-quality'            => 80,
	'quality'                => 80,
	'fail'                   => 'throw',     // ('original' | 404' | 'throw' | 'report')
	'fail-when-fail-fails'   => 'throw',        // ('original' | 404' | 'throw' | 'report')
	'preferred-converters'   => [ 'cwebp', 'gd' ]

];

WebPConvert::convertAndServe( $fullImageSource, $imageDestination, $options );

function getURL() {
	$url   = $_SERVER['REQUEST_URI']; //returns the current URL
	$parts = explode( '/', $url );
	$dir   = $_SERVER['SERVER_NAME'];
	for ( $i = 0; $i < count( $parts ) - 1; $i ++ ) {
		$dir .= $parts[ $i ] . "/";
	}

	return $dir;
}

function createDirectory( $directory ) {

	$dirPerm = 0777;

	if ( ! is_dir( $directory ) ) {
		foreach ( $directory as $target ) {
			if ( ! is_dir( $target ) ) {
				if ( @mkdir( $target, $dirPerm, true ) ) {
					if ( $dirPerm != ( $dirPerm & ~umask() ) ) {
						$folderParts = explode( '/', substr( $target, strlen( dirname( $target ) ) + 1 ) );
						for ( $i = 1, $c = count( $folderParts ); $i <= $c; $i ++ ) {
							@chmod( dirname( $target ) . '/' . implode( '/', array_slice( $folderParts, 0, $i ) ), $dirPerm );
						}
					}
				} else {
					wp_mkdir_p( $target );
				}
			}
		}
	}
}