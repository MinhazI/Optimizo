<?php
/**
 * Plugin Name: Optimizo
 * Plugin URI:  https://www.optimizo.lk
 * Description: Automatic optimization for your website, this plugin will minify your website's HTML. It will also minify your JavaScript files and combine them as one, it will do the same to your CSS as well. Optimizo will also combine all of your website's Google fonts into a single URL. All of these optimizations will help your website in reducing the time it takes to load (also known as 'Page Load Time').
 * Version:     0.0.9
 * Author:      Minhaz Irphan
 * Author URI:  https://minhaz.winauthority.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
require_once( 'class.optimizo.php' );

$optimizoFunction = new OptimizoFunctions();
$cachePath        = $optimizoFunction->createCache();
$cacheDir         = $cachePath['cacheDir'];
$cacheDirURL      = $cachePath['cacheDirURL'];
$cacheBaseURL     = $cachePath['cacheDirURL'];

$wpHome     = site_url();
$wpDomain   = trim( str_ireplace( array( 'http://', 'https://' ), '', trim( $wpHome, '/' ) ) );
$wpHomePath = ABSPATH;
//Checking if it's installed in a sub-directory

//if ( $optimizo->is_sub_directory_install()) {
//	$wpHomePath = $this->get_ABSPATH();
//} else {
//}

class Optimizo extends OptimizoFunctions {
	function __construct() {

		global $wpHome;

		require_once( 'adminToolBar.php' );
		$toolbar = new optimizoAdminToolbar();
		$toolbar->addToolbar();

		add_action( 'admin_notices', array( $this, 'displayMessageOnActivation' ) );

		if ( ! is_admin() ) {
			add_action( 'init', array( $this, 'initializeMinifyHTML' ), 1 );
			if ( ! $this->getWebsiteHTTPResponse( $wpHome ) ) {
				remove_action( 'init', array( $this, 'initializeMinifyHTML' ), 1 );
			}
			add_action( 'wp_print_scripts', array( $this, 'minifyHeaderJS' ), PHP_INT_MAX );
			if ( ! $this->getWebsiteHTTPResponse( $wpHome ) ) {
				remove_action( 'wp_print_scripts', array( $this, 'minifyHeaderJS' ), PHP_INT_MAX );
			}
			add_action( 'wp_print_footer_scripts', array( $this, 'minifyFooterJS' ), 9 );
			if ( ! $this->getWebsiteHTTPResponse( $wpHome ) ) {
				remove_action( 'wp_print_footer_scripts', array( $this, 'minifyFooterJS' ), 9 );
			}
			add_action( 'wp_print_styles', array( $this, 'minifyCSSInHeader' ), PHP_INT_MAX );
			if ( ! $this->getWebsiteHTTPResponse( $wpHome ) ) {
				remove_action( 'wp_print_styles', array( $this, 'minifyCSSInHeader' ), PHP_INT_MAX );
			}
			add_action( 'wp_print_footer_scripts', array( $this, 'minifyCSSinFooter' ), 999999 );
			if ( ! $this->getWebsiteHTTPResponse( $wpHome ) ) {
				remove_action( 'wp_print_footer_scripts', array( $this, 'minifyCSSinFooter' ), 999999 );
			}
		} else {
			add_action( 'after_switch_theme', array( $this, 'removeCache' ) );
			add_action( 'save_post', array( $this, 'removeCache' ) );
			add_action( 'post_updated', array( $this, 'removeCache' ) );
		}
	}

	protected function activation() {

		$this->activate();
	}

	protected function deactivation() {
		$this->deactivate();
	}

	protected function uninstall() {
		$this->uninstallPlugin();
	}

	protected function isInstalledInSubDirectory() {
		if ( strlen( site_url() ) > strlen( home_url() ) ) {
			return true;
		}

		return false;
	}

	protected function getABSPATH() {
		$path           = ABSPATH;
		$websiteUrl     = site_url();
		$websiteHomeUrl = home_url();
		$diff           = str_replace( $websiteHomeUrl, "", $websiteUrl );
		$diff           = trim( $diff, "/" );
		$pos            = strrpos( $path, $diff );
		if ( $pos !== false ) {
			$path = substr_replace( $path, "", $pos, strlen( $diff ) );
			$path = trim( $path, "/" );
			$path = "/" . $path . "/";
		}

		return $path;
	}

	/**
	 * Code for minifying HTML of the website starts from here
	 */
	public function initializeMinifyHTML() {
		ob_start( array( $this, 'minifyHTML' ) );
	}

// Adding a callback function to get access to the website's source code.
	protected function minifyHTML( $buffer ) {
		if ( substr( ltrim( $buffer ), 0, 5 ) == '<?xml' ) {
			return ( $buffer );
		}
		$mod    = '/u';
		$buffer = str_replace( array( chr( 13 ) . chr( 10 ), chr( 9 ) ), array( chr( 10 ), '' ), $buffer );
		$buffer = str_ireplace( array(
			'<script',
			'/script>',
			'<pre',
			'/pre>',
			'<textarea',
			'/textarea>',
			'<style',
			'/style>'
		), array(
			'M1N1FY-ST4RT<script',
			'/script>M1N1FY-3ND',
			'M1N1FY-ST4RT<pre',
			'/pre>M1N1FY-3ND',
			'M1N1FY-ST4RT<textarea',
			'/textarea>M1N1FY-3ND',
			'M1N1FY-ST4RT<style',
			'/style>M1N1FY-3ND'
		), $buffer );
		$split  = explode( 'M1N1FY-3ND', $buffer );
		$buffer = '';
		for ( $i = 0; $i < count( $split ); $i ++ ) {
			$ii = strpos( $split[ $i ], 'M1N1FY-ST4RT' );
			if ( $ii !== false ) {
				$process = substr( $split[ $i ], 0, $ii );
				$asis    = substr( $split[ $i ], $ii + 12 );
				if ( substr( $asis, 0, 7 ) == '<script' ) {
					$split2 = explode( chr( 10 ), $asis );
					$asis   = '';
					for ( $iii = 0; $iii < count( $split2 ); $iii ++ ) {
						if ( $split2[ $iii ] ) {
							$asis .= trim( $split2[ $iii ] ) . chr( 10 );
						}
						if ( $asis ) {
							$asis = substr( $asis, 0, - 1 );
						}
					}
				} else if ( substr( $asis, 0, 6 ) == '<style' ) {
					$asis = preg_replace( array(
						'/\>[^\S ]+' . $mod,
						'/[^\S ]+\<' . $mod,
						'/(\s)+' . $mod
					), array( '>', '<', '\\1' ), $asis );
					$asis = str_replace( array(
						chr( 10 ),
						' {',
						'{ ',
						' }',
						'} ',
						'( ',
						' )',
						' :',
						': ',
						' ;',
						'; ',
						' ,',
						', ',
						';}'
					), array( '', '{', '{', '}', '}', '(', ')', ':', ':', ';', ';', ',', ',', '}' ), $asis );
				}
			} else {
				$process = $split[ $i ];
				$asis    = '';
			}
			$process = preg_replace( array( '/\>[^\S ]+' . $mod, '/[^\S ]+\<' . $mod, '/(\s)+' . $mod ), array(
				'>',
				'<',
				'\\1'
			), $process );
			$buffer  .= $process . $asis;
		}
		$buffer = str_replace( array(
			chr( 10 ) . '<script',
			chr( 10 ) . '<style',
			'*/' . chr( 10 ),
			'M1N1FY-ST4RT'
		), array( '<script', '<style', '*/', '' ), $buffer );
		if ( strtolower( substr( ltrim( $buffer ), 0, 15 ) ) == '<!doctype html>' ) {
			$buffer = str_replace( ' />', '>', $buffer );
		}
		$buffer = str_replace( array(
			'https://' . $_SERVER['HTTP_HOST'] . '/',
			'http://' . $_SERVER['HTTP_HOST'] . '/',
			'//' . $_SERVER['HTTP_HOST'] . '/'
		), array( '/', '/', '/' ), $buffer );

		//$buffer = str_replace( array( 'http://', 'https://' ), '//', $buffer );

		return ( $buffer );
	}

	/**
	 * HTML minifying code ends here
	 */

	public function minifyHeaderJS() {
		global $cacheDir, $wp_scripts, $wpDomain, $wpHome, $ignore, $cacheBaseURL;

		if ( ! is_object( $wp_scripts ) ) {
			return false;
		}
		$scripts = wp_clone( $wp_scripts );
		$scripts->all_deps( $scripts->queue );
		$header = array();

		$done = $scripts->done;

		foreach ( $scripts->to_do as $handle ) :

			$is_footer = 0;
			if ( isset( $wp_scripts->registered[ $handle ]->extra["group"] ) || isset( $wp_scripts->registered[ $handle ]->args ) ) {
				$is_footer = 1;
			}

			if ( $is_footer != 1 ) {

				$url = $this->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wpDomain, $wpHome );

				if ( empty( $url ) ) {
					continue;
				}

				if ( ( ! $this->minifyInArray( $url, $ignore ) && ! isset( $wp_scripts->registered[ $handle ]->extra["conditional"] ) && $this->checkIfInternalLink( $url, $wpHome ) ) || empty( $url ) ) {

					if ( isset( $header[ count( $header ) - 1 ]['handle'] ) || count( $header ) == 0 ) {
						array_push( $header, array( 'handles' => array() ) );
					}

					array_push( $header[ count( $header ) - 1 ]['handles'], $handle );

				} else {
					array_push( $header, array( 'handle' => $handle ) );
				}

			} else {
				$url = $this->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wpDomain, $wpHome );

				if ( empty( $url ) ) {
					wp_enqueue_script( $handle, false );
				} else {
					wp_enqueue_script( $handle, $url, array(), null, true );
				}
			}
		endforeach;

		for ( $i = 0, $l = count( $header ); $i < $l; $i ++ ) {
			if ( ! isset( $header[ $i ]['handle'] ) ) {

				$done     = array_merge( $done, $header[ $i ]['handles'] );
				$fileHash = 'header-optimizo-' . hash( 'md5', implode( '', $header[ $i ]['handles'] ) );

				$file     = $cacheDir . '/' . $fileHash . '.min.js';
				$fileURL = $this->getWPProtocol( $cacheBaseURL . '/' . $fileHash . '.min.js' );

				clearstatcache();
				if ( ! file_exists( $file ) ) {

					$log  = '';
					$code = '';

					foreach ( $header[ $i ]['handles'] as $handle ) :
						if ( ! empty( $wp_scripts->registered[ $handle ]->src ) ) {

							$url = $this->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wpDomain, $wpHome );

							if ( empty( $url ) ) {
								continue;
							}
							$json = $this->downloadAndMinify( $url, null, 'js', $handle );

							$res = json_decode( $json, true );
							# response has failed
							if ( $res['status'] != true ) {
								$log .= $res['log'];
								continue;
							}

							$code .= $res['code'];
							$log  .= $res['log'];

							if ( ! empty( $wp_scripts->registered[ $handle ]->extra ) ) {
								if ( ! empty( $wp_scripts->registered[ $handle ]->extra['before'] ) ) {
									$code .= PHP_EOL . implode( PHP_EOL, $wp_scripts->registered[ $handle ]->extra['before'] );
								}
							}

						} else {
							wp_dequeue_script( $handle );
							wp_enqueue_script( $handle );
						}
					endforeach;

					$log = "Header JS files processed on " . date( "F j, Y, g:i a" ) . PHP_EOL . $log . "PROCESSED from " . site_url() . PHP_EOL;

					if ( ! empty( $code ) ) {
						$this->addToLog( $log );
						file_put_contents( $file, $code );
						file_put_contents( $file . '.gz', gzencode( file_get_contents( $file ), 9 ) );

						$this->fixPermissions( $file );
						$this->fixPermissions( $file . '.gz' );

						if ( function_exists( 'brotli_compress' ) ) {
							file_put_contents( $file . '.br', brotli_compress( file_get_contents( $file ), 11 ) );
							$this->fixPermissions( $file . '.br' );
						}
					}
				}

				wp_register_script( "optimizo-header-$i", $fileURL, array(), null, false );

				$data = array();
				foreach ( $header[ $i ]['handles'] as $handle ) {
					if ( isset( $wp_scripts->registered[ $handle ]->extra['data'] ) ) {
						$data[] = $wp_scripts->registered[ $handle ]->extra['data'];
					}
				}
				if ( count( $data ) > 0 ) {
					$wp_scripts->registered["optimizo-header-$i"]->extra['data'] = implode( PHP_EOL, $data );
				}

				if ( file_exists( $file ) && ( filesize( $file ) > 0 || count( $data ) > 0 ) ) {
					wp_enqueue_script( "optimizo-header-$i" );
				} else {

					echo "<!-- Well, this is quite embarrassing, but there seems to be an error that is not Optimizo to save your website's cache on - $file -->";
					echo "<!-- Please check if the path mentioned is correct and ensure your server has writing permission in that directory. -->";
					echo "<!-- If you think it's a bug, please do us a favor and email us at: hello@winauthorityinnovatives.com -->";
				}

			} else {
				wp_dequeue_script( $header[ $i ]['handle'] );
				wp_enqueue_script( $header[ $i ]['handle'] );
			}
		}

		$wp_scripts->done = $done;
	}

	public function minifyFooterJS() {
		global $cacheDir, $cacheBaseURL;

		global $wp_scripts, $wpDomain, $wpHome, $ignore;
		if ( ! is_object( $wp_scripts ) ) {
			return false;
		}
		$scripts = wp_clone( $wp_scripts );
		$scripts->all_deps( $scripts->queue );
		$footer = array();

		$done = $scripts->done;

		foreach ( $scripts->to_do as $handle ) :
			# get full url
			$url = $this->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wpDomain, $wpHome );

			if ( empty( $url ) ) {
				continue;
			}

			if ( ( ! $this->minifyInArray( $url, $ignore ) && ! isset( $wp_scripts->registered[ $handle ]->extra["conditional"] ) && $this->checkIfInternalLink( $url, $wpHome ) ) || empty( $url ) ) {

				if ( isset( $footer[ count( $footer ) - 1 ]['handle'] ) || count( $footer ) == 0 ) {
					array_push( $footer, array( 'handles' => array() ) );
				}

				array_push( $footer[ count( $footer ) - 1 ]['handles'], $handle );

			} else {
				array_push( $footer, array( 'handle' => $handle ) );
			}
		endforeach;

		for ( $i = 0, $l = count( $footer ); $i < $l; $i ++ ) {
			if ( ! isset( $footer[ $i ]['handle'] ) ) {

				$done     = array_merge( $done, $footer[ $i ]['handles'] );
				$fileHash = 'footer-optimizo-' . hash( 'md5', implode( '', $footer[ $i ]['handles'] ) );

				$file     = $cacheDir . '/' . $fileHash . '.min.js';
				$fileURL = $this->getWPProtocol( $cacheBaseURL . '/' . $fileHash . '.min.js' );

				clearstatcache();
				if ( ! file_exists( $file ) ) {

					$log  = '';
					$code = '';

					foreach ( $footer[ $i ]['handles'] as $handle ) :
						if ( ! empty( $wp_scripts->registered[ $handle ]->src ) ) {

							$url = $this->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wpDomain, $wpHome );

							if ( empty( $url ) ) {
								continue;
							}

							$json = $this->downloadAndMinify( $url, null, 'js', $handle );

							$res = json_decode( $json, true );

							if ( $res['status'] != true ) {
								$log .= $res['log'];
								continue;
							}

							$code .= $res['code'];
							$log  .= $res['log'];

							if ( ! empty( $wp_scripts->registered[ $handle ]->extra ) ) {
								if ( ! empty( $wp_scripts->registered[ $handle ]->extra['before'] ) ) {
									$code .= PHP_EOL . implode( PHP_EOL, $wp_scripts->registered[ $handle ]->extra['before'] );
								}
							}

						} else {
							wp_dequeue_script( $handle );
							wp_enqueue_script( $handle );
						}
					endforeach;
					$log = "Footer JS files processed on " . date( "F j, Y, g:i a" ) . PHP_EOL . $log . "PROCESSED from " . site_url() . PHP_EOL;
					if ( ! empty( $code ) ) {
						$this->addToLog( $log );
						file_put_contents( $file, $code );
						file_put_contents( $file . '.gz', gzencode( file_get_contents( $file ), 9 ) );
						$this->fixPermissions( $file );
						$this->fixPermissions( $file . '.gz' );

						if ( function_exists( 'brotli_compress' ) ) {
							file_put_contents( $file . '.br', brotli_compress( file_get_contents( $file ), 11 ) );
							$this->fixPermissions( $file . '.br' );
						}
					}
				}

				wp_register_script( "optimizo-footer-$i", $fileURL, array(), null, false );

				$data = array();
				foreach ( $footer[ $i ]['handles'] as $handle ) {
					if ( isset( $wp_scripts->registered[ $handle ]->extra['data'] ) ) {
						$data[] = $wp_scripts->registered[ $handle ]->extra['data'];
					}
				}
				if ( count( $data ) > 0 ) {
					$wp_scripts->registered["optimizo-footer-$i"]->extra['data'] = implode( PHP_EOL, $data );
				}

				if ( file_exists( $file ) && ( filesize( $file ) > 0 || count( $data ) > 0 ) ) {
					wp_enqueue_script( "optimizo-footer-$i" );
				} else {

					echo "<!-- Well, this is quite embarrassing, but there seems to be an error that is not Optimizo to save your website's cache on - $file -->";
					echo "<!-- Please check if the path mentioned is correct and ensure your server has writing permission in that directory. -->";
					echo "<!-- If you think it's a bug, please do us a favor and email us at: hello@winauthorityinnovatives.com -->";
				}

			} else {
				wp_dequeue_script( $footer[ $i ]['handle'] );
				wp_enqueue_script( $footer[ $i ]['handle'] );
			}
		}

		$wp_scripts->done = $done;
	}

	public function minifyCSSInHeader() {
		global $wp_styles, $wpDomain, $wpHome, $ignore, $cacheDir, $cacheBaseURL;

		if ( ! is_object( $wp_styles ) ) {
			return false;
		}
		$styles = wp_clone( $wp_styles );
		$styles->all_deps( $styles->queue );
		$done        = $styles->done;
		$header      = array();
		$googleFonts = array();
		$process     = array();
		$inlineCSS   = array();
		$log         = '';

		$uniqueArray = array();

		foreach ( $styles->to_do as $handle ):

			$conditional = null;
			if ( isset( $wp_styles->registered[ $handle ]->extra["conditional"] ) ) {
				$conditional = $wp_styles->registered[ $handle ]->extra["conditional"];
			}

			$currentMediaType = isset( $wp_styles->registered[ $handle ]->args ) ? $wp_styles->registered[ $handle ]->args : 'all';
			if ( $currentMediaType == 'screen' || $currentMediaType == 'screen, print' || empty( $currentMediaType ) || is_null( $currentMediaType ) || $currentMediaType == false ) {
				$currentMediaType = 'all';
			}
			$mediaType = $currentMediaType;
			$url       = $this->returnFullURL( $wp_styles->registered[ $handle ]->src, $wpDomain, $wpHome );

			if ( empty( $url ) ) {
				continue;
			}

			if ( ! empty( $url ) ) {
				$key = hash( 'adler32', $url );
				if ( isset( $uniqueArray[ $key ] ) ) {
					$done = array_merge( $done, array( $handle ) );
					continue;
				} else {
					$uniqueArray[ $key ] = $handle;
				}
			}

			$arr = array(
				'handle'      => $handle,
				'url'         => $url,
				'conditional' => $conditional,
				'mediatype'   => $mediaType
			);

			if ( stripos( $url, 'fonts.googleapis.com' ) !== false ) {
				$googleFonts[ $handle ] = $url;
			}

			$process[ $handle ] = $arr;
		endforeach;

		if ( count( $googleFonts ) > 0 ) {
			foreach ( $googleFonts as $h => $a ) {
				$done = array_merge( $done, array( $h ) );
			}

			$newGoogleFonts   = array();
			$newGoogleFonts[] = $this->getWPProtocol( $this->concatenateGoogleFonts( $googleFonts ) );

			if ( count( $newGoogleFonts ) > 0 ) {
				foreach ( $newGoogleFonts as $gfurl ) {
					echo '<link rel="preload" href="' . $gfurl . '" as="style" media="all" onload="this.onload=null;this.rel=\'stylesheet\'" />';
					echo '<noscript><link rel="stylesheet" href="' . $gfurl . '" media="all" /></noscript>';
					echo '<!--[if IE]><link rel="stylesheet" href="' . $gfurl . '" media="all" /><![endif]-->';
				}
			}
		}

		foreach ( $styles->to_do as $handle ) :

			if ( isset( $googleFonts[ $handle ] ) ) {
				continue;
			}
			if ( empty( $wp_styles->registered[ $handle ]->src ) ) {
				continue;
			}
			if ( $this->minifyInArray( $handle, $done ) ) {
				continue;
			}
			if ( ! isset( $process[ $handle ] ) ) {
				continue;
			}

			$url         = $process[ $handle ]['url'];
			$conditional = $process[ $handle ]['conditional'];
			$mediaType   = $process[ $handle ]['mediatype'];

			if ( ( ! $this->minifyInArray( $url, $ignore ) && ! isset( $conditional ) && $this->checkIfInternalLink( $url, $wpHome ) )
			     || empty( $url ) ) {

				if ( isset( $wp_styles->registered[ $handle ]->extra['after'] ) && is_array( $wp_styles->registered[ $handle ]->extra['after'] ) ) {
					$inlineCSS[ $handle ]                             = $this->minifyCSSWithPHP( implode( '', $wp_styles->registered[ $handle ]->extra['after'] ) ); # save
					$wp_styles->registered[ $handle ]->extra['after'] = null; # dequeue
				}

				if ( isset( $header[ count( $header ) - 1 ]['handle'] ) || count( $header ) == 0 || $header[ count( $header ) - 1 ]['media'] != $mediaType ) {
					array_push( $header, array( 'handles' => array(), 'media' => $mediaType ) );
				}

				array_push( $header[ count( $header ) - 1 ]['handles'], $handle );

			} else {

				array_push( $header, array( 'handle' => $handle ) );
			}
		endforeach;

		for ( $i = 0, $l = count( $header ); $i < $l; $i ++ ) {
			if ( ! isset( $header[ $i ]['handle'] ) ) {
				# get has for the inline css in this group
				$inlineCSSGroup = array();
				foreach ( $header[ $i ]['handles'] as $h ) {
					if ( isset( $inlineCSS[ $h ] ) && ! empty( $inlineCSS[ $h ] ) ) {
						$inlineCSSGroup[] = $inlineCSS[ $h ];
					}
				}
				$inlineCSS_hash = md5( implode( '', $inlineCSSGroup ) );

				$done     = array_merge( $done, $header[ $i ]['handles'] );
				$fileHash = 'header-optimizo-' . hash( 'md5', implode( '', $header[ $i ]['handles'] ) . $inlineCSS_hash );

				$file     = $cacheDir . '/' . $fileHash . '.min.css';
				$fileURL = $this->getWPProtocol( $cacheBaseURL . '/' . $fileHash . '.min.css' );
				
				clearstatcache();
				if ( ! file_exists( $file ) ) {

					$log  = '';
					$code = '';

					foreach ( $header[ $i ]['handles'] as $handle ) :
						if ( ! empty( $wp_styles->registered[ $handle ]->src ) ) {

							$url = $this->returnFullURL( $wp_styles->registered[ $handle ]->src, $wpDomain, $wpHome );
							if ( empty( $url ) ) {
								continue;
							}

							$json = $this->downloadAndMinify( $url, null, 'css', $handle );

							$res = json_decode( $json, true );

							if ( $res['status'] != true ) {
								$log .= $res['log'];
								continue;
							}

							$code .= $res['code'];
							$log  .= $res['log'];

							if ( isset( $inlineCSS[ $handle ] ) && ! empty( $inlineCSS[ $handle ] ) ) {
								$code .= $inlineCSS[ $handle ];
							}

						} else {
							wp_dequeue_script( $handle );
							wp_enqueue_script( $handle );
						}
					endforeach;

					$log = "Header CSS files processed on " . date( "F j, Y, g:i a" ) . PHP_EOL . $log . "PROCESSED from " . site_url() . PHP_EOL;

					if ( ! empty( $code ) ) {
						$this->addToLog( $log );
						file_put_contents( $file, $code );
						file_put_contents( $file . '.gz', gzencode( file_get_contents( $file ), 9 ) );

						$this->fixPermissions( $file );
						$this->fixPermissions( $file . '.gz' );

						if ( function_exists( 'brotli_compress' ) ) {
							file_put_contents( $file . '.br', brotli_compress( file_get_contents( $file ), 11 ) );
							$this->fixPermissions( $file . '.br' );
						}
					}
				}

				if ( file_exists( $file ) && filesize( $file ) > 0 ) {

					if ( filesize( $file ) < 20000 && $header[ $i ]['media'] != 'all' ) {
						echo '<style id="optimizo-header-' . $i . '" media="' . $header[ $i ]['media'] . '">' . file_get_contents( $file ) . '</style>';
					} else {

						wp_enqueue_style( "optimizo-header-$i", $fileURL, array(), null, $header[ $i ]['media'] );
					}
				} else {

					echo "<!-- Well, this is quite embarrassing, but there seems to be an error that is not Optimizo to save your website's cache on - $file -->";
					echo "<!-- Please check if the path mentioned is correct and ensure your server has writing permission in that directory. -->";
					echo "<!-- If you think it's a bug, please do us a favor and email us at: hello@winauthorityinnovatives.com -->";
				}

			} else {
				wp_dequeue_style( $header[ $i ]['handle'] );
				wp_enqueue_style( $header[ $i ]['handle'] );
			}
		}

		$wp_styles->done = $done;
	}

	public function minifyCSSinFooter() {
		global $wp_styles, $wpDomain, $wpHome, $cacheDir;
		$removePrintMediatypes = false;
		$ignore                = false;

		if ( ! is_object( $wp_styles ) ) {
			return false;
		}
		$styles = wp_clone( $wp_styles );
		$styles->all_deps( $styles->queue );
		$done        = $styles->done;
		$footer      = array();
		$googleFonts = array();
		$inlineCSS   = array();

		foreach ( $styles->to_do as $handle ) :

			$url = $this->returnFullURL( $wp_styles->registered[ $handle ]->src, $wpDomain, $wpHome );

			if ( empty( $url ) ) {
				continue;
			}
			if ( stripos( $url, 'fonts.googleapis.com' ) !== false ) {
				wp_dequeue_style( $handle );
				$googleFonts[ $handle ] = $url;
			} else {
				wp_dequeue_style( $handle );
				wp_enqueue_style( $handle );
			}
		endforeach;

		if ( count( $googleFonts ) > 0 ) {
			foreach ( $googleFonts as $h => $a ) {
				$done = array_merge( $done, array( $h ) );
			}

			$newGoogleFonts   = array();
			$newGoogleFonts[] = $this->getWPProtocol( $this->concatenateGoogleFonts( $googleFonts ) );

			if ( count( $newGoogleFonts ) > 0 ) {
				foreach ( $newGoogleFonts as $googleFontURL ) {
					$json = false;
					if ( $json === false ) {
						$json = $this->downloadAndMinify( $googleFontURL, null, 'css', null );
					}
					$res = json_decode( $json, true );
					if ( $res['code'] !== false ) {
						echo '<style type="text/css" media="all">' . $res['code'] . '</style>' . PHP_EOL;
					} else {
						echo "<!-- GOOGLE FONTS REQUEST FAILED for $googleFontURL -->\n";
					}
				}
			}
		}

		$uniqueArray = array();
		foreach ( $styles->to_do as $handle ) :

			if ( isset( $googleFonts[ $handle ] ) ) {
				continue;
			}

			$conditional = null;
			if ( isset( $wp_styles->registered[ $handle ]->extra["conditional"] ) ) {
				$conditional = $wp_styles->registered[ $handle ]->extra["conditional"];
			}

			$mediaType = isset( $wp_styles->registered[ $handle ]->args ) ? $wp_styles->registered[ $handle ]->args : 'all';
			if ( $mediaType == 'screen' || $mediaType == 'screen, print' || empty( $mediaTypes ) || is_null( $mediaType ) || $mediaType == false ) {
				$mediaType = 'all';
			}
			$mediaType = $mediaType;

			$url = $this->returnFullURL( $wp_styles->registered[ $handle ]->src, $wpDomain, $wpHome );

			if ( empty( $url ) ) {
				continue;
			}

			if ( ! empty( $url ) ) {
				$key = hash( 'md5', $url );
				if ( isset( $uniqueArray[ $key ] ) ) {
					$done = array_merge( $done, array( $handle ) );
					continue;
				} else {
					$uniqueArray[ $key ] = $handle;
				}
			}

			if ( ( ! $this->minifyInArray( $url, $ignore ) && ! isset( $conditional ) && $this->checkIfInternalLink( $url, $wpHome ) )
			     || empty( $url ) ) {

				if ( isset( $wp_styles->registered[ $handle ]->extra['after'] ) && is_array( $wp_styles->registered[ $handle ]->extra['after'] ) ) {
					$inlineCSS[ $handle ]                             = $this->minifyCSSWithPHP( implode( '', $wp_styles->registered[ $handle ]->extra['after'] ) );
					$wp_styles->registered[ $handle ]->extra['after'] = null;
				}

				if ( isset( $footer[ count( $footer ) - 1 ]['handle'] ) || count( $footer ) == 0 || $footer[ count( $footer ) - 1 ]['media'] != $wp_styles->registered[ $handle ]->args ) {
					array_push( $footer, array( 'handles' => array(), 'media' => $mediaType ) );
				}

				array_push( $footer[ count( $footer ) - 1 ]['handles'], $handle );
			} else {

				array_push( $footer, array( 'handle' => $handle ) );
			}
		endforeach;

		for ( $count = 0, $x = count( $footer ); $count < $x; $count ++ ) {
			if ( ! isset( $footer[ $count ]['handle'] ) ) {

				$inlineCSSGroup = array();
				foreach ( $footer[ $count ]['handles'] as $footerHandle ) {
					if ( isset( $inlineCSS[ $footerHandle ] ) && ! empty( $inlineCSS[ $footerHandle ] ) ) {
						$inlineCSSGroup[] = $inlineCSS[ $footerHandle ];
					}
				}
				$inlineCSSHash = md5( implode( '', $inlineCSSGroup ) );

				$done = array_merge( $done, $footer[ $count ]['handles'] );
				$hash = 'footer-optimizo-' . hash( 'md5', implode( '', $footer[ $count ]['handles'] ) . $inlineCSSHash );

				$footerCSSFile = $cacheDir . '/' . $hash . '.min.css';
				$fileURL       = $this->getWPProtocol( $cacheDir . '/' . $hash . '.min.css' );

				clearstatcache();
				if ( ! file_exists( $footerCSSFile ) ) {

					$log  = '';
					$code = '';

					foreach ( $footer[ $count ]['handles'] as $handle ) :
						if ( ! empty( $wp_styles->registered[ $handle ]->src ) ) {

							$url = $this->returnFullURL( $wp_styles->registered[ $handle ]->src, $wpDomain, $wpHome );

							if ( empty( $url ) ) {
								continue;
							}

							$json = false;
							if ( $json === false ) {
								$json = $this->downloadAndMinify( $url, null, 'css', $handle );
							}

							$res = json_decode( $json, true );

							if ( $res['status'] != true ) {
								$log .= $res['log'];
								continue;
							}

							$code .= $res['code'];
							$log  .= $res['log'];

							if ( isset( $inlineCSS[ $handle ] ) && ! empty( $inlineCSS[ $handle ] ) ) {
								$code .= $inlineCSS[ $handle ];
							}

						} else {
							wp_dequeue_script( $handle );
							wp_enqueue_script( $handle );
						}
					endforeach;

					$log = "Footer CSS files processed on " . date( "F j, Y, g:i a" ) . PHP_EOL . $log . "PROCESSED from " . site_url() . PHP_EOL;

					if ( ! empty( $code ) ) {
						$this->addToLog( $log );
						file_put_contents( $footerCSSFile, $code );
						file_put_contents( $footerCSSFile . '.gz', gzencode( file_get_contents( $footerCSSFile ), 9 ) );

						$this->fixPermissions( $footerCSSFile . '.txt' );
						$this->fixPermissions( $footerCSSFile );
						$this->fixPermissions( $footerCSSFile . '.gz' );

						if ( function_exists( 'brotli_compress' ) ) {
							file_put_contents( $footerCSSFile . '.br', brotli_compress( file_get_contents( $footerCSSFile ), 11 ) );
							$this->fixPermissions( $footerCSSFile . '.br' );
						}
					}
				}

				if ( $removePrintMediatypes != true ) {

					if ( file_exists( $footerCSSFile ) && filesize( $footerCSSFile ) > 0 ) {

						if ( filesize( $footerCSSFile ) < 20000 ) {
							echo '<style id="optimizo-footer-' . $count . '" media="' . $footer[ $count ]['media'] . '">' . file_get_contents( $footerCSSFile ) . '</style>';
						} else {

							wp_enqueue_style( "optimizo-footer-$count", $fileURL, array(), null, $footer[ $count ]['media'] );
						}
					} else {
						echo "<!-- Well, this is quite embarrassing, but there seems to be an error that is not Optimizo to save your website's cache on - $footerCSSFile -->";
						echo "<!-- Please check if the path mentioned is correct and ensure your server has writing permission in that directory. -->";
						echo "<!-- If you think it's a bug, please do us a favor and email us at: hello@winauthorityinnovatives.com -->";
					}
				}
			}

			$wp_styles->done = $done;
		}
	}

	public function displayMessageOnActivation() {
		?>
        <div class="notice notice-info is-dismissible">
            <p><?php _e( "Thank you for installing & activating Optimizo. Your website is in good hands! \n Optimizo has minified your website's HTML, CSS and JavaScript and it has combined your website's Google fonts if there are more than one. It has also combined all your CSS file and JS files." ); ?></p>
        </div>
		<?php
	}
}

if ( class_exists( 'Optimizo' ) ) {
	$optimizo = new Optimizo();
} else {
	die;
}

register_activation_hook( __FILE__, array( $optimizo, 'activation' ) );
register_deactivation_hook( __FILE__, array( $optimizo, 'deactivation' ) );
register_uninstall_hook( __FILE__, array( $optimizo, 'uninstall' ) );

