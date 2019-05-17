<?php

global $webpCache;

require __DIR__ . '/webp-on-demand.inc';

use WebPOnDemand\WebPOnDemand;

$imageSource  = $_GET['source'];            // Absolute file path to source file. Comes from the .htaccess
$documentRoot = $_SERVER['DOCUMENT_ROOT'];
if ( ! strstr( $documentRoot, 'htdocs' ) ) {


	$destinationDir = $documentRoot . '/wp-content/optimizoCache/webp-images';

	$fullImageSource = $documentRoot . '/' . strstr( $imageSource, 'wp-content' );

//var_dump($fullImageSource);
//
//var_dump($destinationDir);

	if ( ! is_dir( $destinationDir ) ) {
		@mkdir( $destinationDir, 0755, true );
	}

	$newImageSource = strstr( $imageSource, 'uploads' );

	$imageDestination = $destinationDir . '/' . $newImageSource . '.webp';

	$dirPerm = 0777;

	if ( ! is_dir( $imageDestination ) ) {
		foreach ( $imageDestination as $target ) {
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

} else {
	$destinationDir = __DIR__ . '/wp-content/optimizoCache/webp-images';
	$imageDestination = $destinationDir . '/' . $imageSource . '.webp';
	$dirPerm = 0777;

	if ( ! is_dir( $imageDestination ) ) {
		foreach ( $imageDestination as $target ) {
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

	$currentURL = getURL();


	$fullImageSource = $currentURL .  strstr( $imageSource, 'wp-content' );

	var_dump($fullImageSource);

}

$options = [

	// Tell where to find the webp-convert-and-serve library, which will
	// be dynamically loaded, if need be.
	'require-for-conversion' => __DIR__ . '/webp-convert-and-serve.inc',
	'show-report'            => true,
	'max-quality'            => 80,
	'quality'                => 80,
	'fail'                   => 'throw',     // ('original' | 404' | 'throw' | 'report')
	'fail-when-fail-fails'   => 'throw',        // ('original' | 404' | 'throw' | 'report')
	'preferred-converters'   => [ 'cwebp', 'gd' ]


	// More options available!
];

WebPOnDemand::serve( $fullImageSource, $imageDestination, $options );

function getURL(){
	$url = $_SERVER['REQUEST_URI']; //returns the current URL
	$parts = explode('/',$url);
	$dir = $_SERVER['SERVER_NAME'];
	for ($i = 0; $i < count($parts) - 1; $i++) {
		$dir .= $parts[$i] . "/";
	}
	return $dir;
}