<?php
/**
 * Plugin Name: Optimizo
 * Plugin URI:  https://www.optimizo.lk
 * Description: Automatic optimization for your website, this plugin will minify your website's HTML. It will also minify your JavaScript files and combine them as one. Optimizo will also cache your website. All of these optimizations will help your website in reducing the time it takes to load (also known as 'Page Load Time').
 * Version:     0.0.8
 * Author:      Minhaz Irphan
 * Author URI:  https://minhaz.winauthority.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

require_once( 'class.optimizo.php' );

class Optimizo {

	function __construct() {
		$path = ABSPATH;
		//Checking if it's installed in a sub-directory

		if ( $this->is_sub_directory_install() ) {
			 $path = $this->get_ABSPATH();
		}

		require_once( 'adminToolBar.php' );

		$toolbar = new optimizoAdminToolbar();

		$toolbar->addToolbar();

		echo "<!-- This website has been optimized by Optimizo. Web: https://www.optimizo.lk -->";

		add_action( 'init', 'init_minify_html', 1 );
		add_action( 'wp_print_scripts', 'minifyHeaderJS', PHP_INT_MAX );
		add_action( 'wp_print_footer_scripts', 'minifyFooterJS', 9.999999 );
		add_action( 'wp_print_styles', 'minifyCSSInHeader', PHP_INT_MAX );

	}

	function activation() {
		$path = ABSPATH;
		//Checking if it's installed in a sub-directory

		if ( $this->is_sub_directory_install() ) {
			$path = $this->get_ABSPATH();
		}

		if ( is_plugin_active( $path . '/wp-content/plugins/Optimizo/optimizo.php' ) ) {

		} else {
			add_action( 'admin_notices', 'display_error_message' );
		}


		$optimizoClass = new OptimizoClass();

		$optimizoClass->activate();

	}

	function deactivation() {
		require_once( 'class.optimizo.php' );

		$optimizoClass = new OptimizoClass();

		$optimizoClass->deactivate();
	}

	function uninstall() {

	}


	function is_sub_directory_install() {
		if ( strlen( site_url() ) > strlen( home_url() ) ) {
			return true;
		}

		return false;
	}

	function get_ABSPATH() {
		$path           = ABSPATH;
		$websiteUrl     = site_url();
		$websiteHomeUrl = home_url();
		$diff           = str_replace( $websiteHomeUrl, "", $websiteUrl );
		$diff           = trim( $diff, "/" );

		$pos = strrpos( $path, $diff );

		if ( $pos !== false ) {
			$path = substr_replace( $path, "", $pos, strlen( $diff ) );
			$path = trim( $path, "/" );
			$path = "/" . $path . "/";
		}

		return $path;
	}

}

function display_message_on_activation() {
	?>
    <div class="notice notice-info">
        <p><?php _e( "Thank you for installing & activating Optimizo. Your website is in good hands! \n Optimizo has minified your website's HTML and JavaScript and has also started caching your website on your server. " ); ?></p>
    </div>
	<?php
}

if ( class_exists( 'Optimizo' ) ) {
	add_action( 'admin_notices', 'display_message_on_activation' );

	$optimizo = new Optimizo();
}

register_activation_hook( __FILE__, array( $optimizo, 'activation' ) );
register_deactivation_hook( __FILE__, array( $optimizo, 'deactivation' ) );

/**
 * Code for minifying HTML of the website starts from here
 */

function init_minify_html() {
	ob_start( 'minifyHTML' );
}

// Adding a callback function to get access to the website's source code.
function minifyHTML( $buffer ) {
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
	$buffer = str_replace( array( 'http://', 'https://' ), '//', $buffer );

	return ( $buffer );
}

/**
 * HTML minifying code ends here
 */


function minifyHeaderJS() {
	$optimizoClass = new OptimizoClass();

	global $wp_scripts, $wp_domain, $wp_home, $ignore;
	if ( ! is_object( $wp_scripts ) ) {
		return false;
	}
	$scripts = wp_clone( $wp_scripts );
	$scripts->all_deps( $scripts->queue );
	$header = array();

	# mark as done (as we go)
	$done = $scripts->done;

	# get groups of handles
	foreach ( $scripts->to_do as $handle ) :

		# is it a footer script?
		$is_footer = 0;
		if ( isset( $wp_scripts->registered[ $handle ]->extra["group"] ) || isset( $wp_scripts->registered[ $handle ]->args ) ) {
			$is_footer = 1;
		}

		# skip footer scripts for now
		if ( $is_footer != 1 ) {

			# get full url
			$furl = $optimizoClass->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wp_domain, $wp_home );

			# inlined scripts without file
			if ( empty( $furl ) ) {
				continue;
			}

			# skip ignore list, scripts with conditionals, external scripts
			if ( ( ! $optimizoClass->minifyInArray( $furl, $ignore ) && ! isset( $wp_scripts->registered[ $handle ]->extra["conditional"] ) && $optimizoClass->checkIfInternalLink( $furl, $wp_home ) ) || empty( $furl ) ) {

				# process
				if ( isset( $header[ count( $header ) - 1 ]['handle'] ) || count( $header ) == 0 ) {
					array_push( $header, array( 'handles' => array() ) );
				}

				# push it to the array
				array_push( $header[ count( $header ) - 1 ]['handles'], $handle );

				# external and ignored scripts
			} else {
				array_push( $header, array( 'handle' => $handle ) );
			}

			# make sure that the scripts skipped here, show up in the footer
		} else {
			$furl = $optimizoClass->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wp_domain, $wp_home );

			# inlined scripts without file
			if ( empty( $furl ) ) {
				wp_enqueue_script( $handle, false );
			} else {
				wp_enqueue_script( $handle, $furl, array(), null, true );
			}
		}
	endforeach;

//	$cachepath = $optimizoClass->createCache();
//	$cacheDir  = $cachepath['cachedir'];

	$cacheDir = WP_CONTENT_DIR . '/optimizoCache';

	# loop through header scripts and merge
	for ( $i = 0, $l = count( $header ); $i < $l; $i ++ ) {
		if ( ! isset( $header[ $i ]['handle'] ) ) {

			# static cache file info + done
			$done = array_merge( $done, $header[ $i ]['handles'] );
			$hash = 'header-' . hash( 'adler32', implode( '', $header[ $i ]['handles'] ) );

			# create cache files and urls
			$file     = $cacheDir . '/' . $hash . '.min.js';
			$file_url = $optimizoClass->getWPProtocol( $cacheDir . '/' . $hash . '.min.js' );


			# generate a new cache file
			clearstatcache();
			if ( ! file_exists( $file ) ) {

				# code and log initialization
				$log  = '';
				$code = '';

				# minify and write to file
				foreach ( $header[ $i ]['handles'] as $handle ) :
					if ( ! empty( $wp_scripts->registered[ $handle ]->src ) ) {

						# get furl per handle
						$furl = $optimizoClass->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wp_domain, $wp_home );

						# inlined scripts without file
						if ( empty( $furl ) ) {
							continue;
						}

						# print url
						$printurl = str_ireplace( array( site_url(), home_url(), 'http:', 'https:' ), '://', $furl );

						# download, minify, cache
						$tkey = 'js-' . hash( 'adler32', $handle . $furl ) . '.js';
						$json = false;
						$json = $optimizoClass->getTempStore( $tkey );
						if ( $json === false ) {
							$json = $optimizoClass->downloadAndMinify( $furl, null, 'js', $handle );
							$optimizoClass->setTempStore( $tkey, $json );
						}

						# decode
						$res = json_decode( $json, true );

						# response has failed
						if ( $res['status'] != true ) {
							$log .= $res['log'];
							continue;
						}

						# append code to merged file
						$code .= $res['code'];
						$log  .= $res['log'];

						# Add extra data from wp_add_inline_script before
						if ( ! empty( $wp_scripts->registered[ $handle ]->extra ) ) {
							if ( ! empty( $wp_scripts->registered[ $handle ]->extra['before'] ) ) {
								$code .= PHP_EOL . implode( PHP_EOL, $wp_scripts->registered[ $handle ]->extra['before'] );
							}
						}

						# consider dependencies on handles with an empty src
					} else {
						wp_dequeue_script( $handle );
						wp_enqueue_script( $handle );
					}
				endforeach;

				# prepare log
				$log = "PROCESSED on " . date( 'r' ) . PHP_EOL . $log . "PROCESSED from " . home_url( add_query_arg( null, null ) ) . PHP_EOL;

				# generate cache, write log
				if ( ! empty( $code ) ) {
					file_put_contents( $file . '.txt', $log );
					file_put_contents( $file, $code );
					file_put_contents( $file . '.gz', gzencode( file_get_contents( $file ), 9 ) );

					# permissions
					$optimizoClass->fixPermissions( $file . '.txt' );
					$optimizoClass->fixPermissions( $file );
					$optimizoClass->fixPermissions( $file . '.gz' );

					# brotli static support
					if ( function_exists( 'brotli_compress' ) ) {
						file_put_contents( $file . '.br', brotli_compress( file_get_contents( $file ), 11 ) );
						$optimizoClass->fixPermissions( $file . '.br' );
					}
				}
			}

			# register minified file
			wp_register_script( "optimizo-header-$i", $file_url, array(), null, false );

			# add all extra data from wp_localize_script
			$data = array();
			foreach ( $header[ $i ]['handles'] as $handle ) {
				if ( isset( $wp_scripts->registered[ $handle ]->extra['data'] ) ) {
					$data[] = $wp_scripts->registered[ $handle ]->extra['data'];
				}
			}
			if ( count( $data ) > 0 ) {
				$wp_scripts->registered["optimizo-header-$i"]->extra['data'] = implode( PHP_EOL, $data );
			}

			# enqueue file, if not empty
			if ( file_exists( $file ) && ( filesize( $file ) > 0 || count( $data ) > 0 ) ) {
				wp_enqueue_script( "optimizo-header-$i" );
			} else {
				# file could not be generated, output something meaningful
				echo "<!-- ERROR: Optimizo was not allowed to save it's cache on - $file -->";
				echo "<!-- Please check if the path above is correct and ensure your server has writing permission there! -->";
				echo "<!-- If you found a bug, please email us at hello@winauthorityinnovatives.com -->";
			}

			# other scripts need to be requeued for the order of files to be kept
		} else {
			wp_dequeue_script( $header[ $i ]['handle'] );
			wp_enqueue_script( $header[ $i ]['handle'] );
		}
	}

	# remove from queue
	$wp_scripts->done = $done;
}

function minifyFooterJS() {
	$optimizoClass = new OptimizoClass();

	global $wp_scripts, $wp_domain, $wp_home, $ignore;

//	$cachepath = $optimizoClass->createCache();
//	$cachedir  = $cachepath['cachedir'];
//	$cachedirurl = $cachepath['$cachedirurl'];

	$cacheDir = WP_CONTENT_DIR . '/optimizoCache';

	if ( ! is_object( $wp_scripts ) ) {
		return false;
	}
	$scripts = wp_clone( $wp_scripts );
	$scripts->all_deps( $scripts->queue );
	$footer = array();

# mark as done (as we go)
	$done = $scripts->done;

# get groups of handles
	foreach ( $scripts->to_do as $handle ) :

		# get full url
		$furl = $optimizoClass->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wp_domain, $wp_home );

		# inlined scripts without file
		if ( empty( $furl ) ) {
			continue;
		}

		# skip ignore list, scripts with conditionals, external scripts
		if ( ( ! $optimizoClass->minifyInArray( $furl, $ignore ) && ! isset( $wp_scripts->registered[ $handle ]->extra["conditional"] ) && $optimizoClass->checkIfInternalLink( $furl, $wp_home ) ) || empty( $furl ) ) {

			# process
			if ( isset( $footer[ count( $footer ) - 1 ]['handle'] ) || count( $footer ) == 0 ) {
				array_push( $footer, array( 'handles' => array() ) );
			}

			# push it to the array
			array_push( $footer[ count( $footer ) - 1 ]['handles'], $handle );

			# external and ignored scripts
		} else {
			array_push( $footer, array( 'handle' => $handle ) );
		}
	endforeach;

# loop through footer scripts and merge
	for ( $i = 0, $l = count( $footer ); $i < $l; $i ++ ) {
		if ( ! isset( $footer[ $i ]['handle'] ) ) {

			# static cache file info + done
			$done = array_merge( $done, $footer[ $i ]['handles'] );
			$hash = 'footer-' . hash( 'adler32', implode( '', $footer[ $i ]['handles'] ) );

			# create cache files and urls
			$file     = $cacheDir . '/' . $hash . '.min.js';
			$file_url = $optimizoClass->getWPProtocol( $cacheDir . '/' . $hash . '.min.js' );

			# generate a new cache file
			clearstatcache();
			if ( ! file_exists( $file ) ) {

				# code and log initialization
				$log  = '';
				$code = '';

				# minify and write to file
				foreach ( $footer[ $i ]['handles'] as $handle ) :
					if ( ! empty( $wp_scripts->registered[ $handle ]->src ) ) {

						# get hurl per handle
						$furl = $optimizoClass->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wp_domain, $wp_home );

						# inlined scripts without file
						if ( empty( $furl ) ) {
							continue;
						}

						# print url
						$printurl = str_ireplace( array( site_url(), home_url(), 'http:', 'https:' ), '', $furl );


						# download, minify, cache
						$tkey = 'js-' . hash( 'adler32', $handle . $furl ) . '.js';
						$json = false;
						$json = $optimizoClass->getTempStore( $tkey );
						if ( $json === false ) {
							$json = $optimizoClass->downloadAndMinify( $furl, null, 'js', $handle );
							$optimizoClass->setTempStore( $tkey, $json );
						}

						# decode
						$res = json_decode( $json, true );

						# response has failed
						if ( $res['status'] != true ) {
							$log .= $res['log'];
							continue;
						}

						# append code to merged file
						$code .= $res['code'];
						$log  .= $res['log'];

						# Add extra data from wp_add_inline_script before
						if ( ! empty( $wp_scripts->registered[ $handle ]->extra ) ) {
							if ( ! empty( $wp_scripts->registered[ $handle ]->extra['before'] ) ) {
								$code .= PHP_EOL . implode( PHP_EOL, $wp_scripts->registered[ $handle ]->extra['before'] );
							}
						}

						# consider dependencies on handles with an empty src
					} else {
						wp_dequeue_script( $handle );
						wp_enqueue_script( $handle );
					}
				endforeach;

				# prepare log
				$log = "PROCESSED on " . date( 'r' ) . PHP_EOL . $log . "PROCESSED from " . home_url( add_query_arg( null, null ) ) . PHP_EOL;

				# generate cache, write log
				if ( ! empty( $code ) ) {
					file_put_contents( $file . '.txt', $log );
					file_put_contents( $file, $code );
					file_put_contents( $file . '.gz', gzencode( file_get_contents( $file ), 9 ) );

					# permissions
					$optimizoClass->fixPermissions( $file . '.txt' );
					$optimizoClass->fixPermissions( $file );
					$optimizoClass->fixPermissions( $file . '.gz' );

					# brotli static support
					if ( function_exists( 'brotli_compress' ) ) {
						file_put_contents( $file . '.br', brotli_compress( file_get_contents( $file ), 11 ) );
						$optimizoClass->fixPermissions( $file . '.br' );
					}
				}
			}

			# register minified file
			wp_register_script( "optimizo-footer-$i", $file_url, array(), null, false );

			# add all extra data from wp_localize_script
			$data = array();
			foreach ( $footer[ $i ]['handles'] as $handle ) {
				if ( isset( $wp_scripts->registered[ $handle ]->extra['data'] ) ) {
					$data[] = $wp_scripts->registered[ $handle ]->extra['data'];
				}
			}
			if ( count( $data ) > 0 ) {
				$wp_scripts->registered["optimizo-footer-$i"]->extra['data'] = implode( PHP_EOL, $data );
			}

			# enqueue file, if not empty
			if ( file_exists( $file ) && ( filesize( $file ) > 0 || count( $data ) > 0 ) ) {
				wp_enqueue_script( "optimizo-footer-$i" );
			} else {
				# file could not be generated, output something meaningful
				echo "<!-- ERROR: Optimizo was not allowed to save it's cache on - $file -->";
				echo "<!-- Please check if the path above is correct and ensure your server has writing permission there! -->";
				echo "<!-- If you found a bug, please email us at hello@winauthorityinnovatives.com -->";
			}

			# other scripts need to be requeued for the order of files to be kept
		} else {
			wp_dequeue_script( $footer[ $i ]['handle'] );
			wp_enqueue_script( $footer[ $i ]['handle'] );
		}
	}

# remove from queue
	$wp_scripts->done = $done;
}

function minifyCSSInHeader(){
	global $wp_styles, $wp_domain, $wp_home, $wp_home_path, $ignore, $skip_google_fonts, $skip_cssorder, $remove_print_mediatypes, $force_inline_googlefonts, $css_hide_googlefonts, $min_async_googlefonts, $remove_googlefonts, $fvmloadcss, $fvm_remove_css, $fvmualist, $fvm_min_excludecsslist, $fvm_debug, $fvm_min_excludecsslist, $fvm_fawesome_method;
	$skip_google_fonts = false;
	$min_async_googlefonts = true;
	$force_inline_googlefonts = true;

	$cachedir =  WP_CONTENT_DIR . '/optimizoCache';
	$cachedirurl =  WP_CONTENT_DIR . '/optimizoCache';

	$optimizoClass = new OptimizoClass();

	if ( ! is_object( $wp_styles ) ) {
		return false;
	}
	$ctime  = '0';
	$styles = wp_clone( $wp_styles );
	$styles->all_deps( $styles->queue );
	$done         = $styles->done;
	$header       = array();
	$google_fonts = array();
	$process      = array();
	$inline_css   = array();
	$log          = '';

# dequeue all styles
	if ( $fvm_remove_css != false ) {
		foreach ( $styles->to_do as $handle ) :
			$done = array_merge( $done, array( $handle ) );
		endforeach;

		# remove from queue
		$wp_styles->done = $done;

		return false;
	}

# get list of handles to process, dequeue duplicate css urls and keep empty source handles (for dependencies)
	$uniq   = array();
	$gfonts = array();
	foreach ( $styles->to_do as $handle ):

		# conditionals
		$conditional = null;
		if ( isset( $wp_styles->registered[ $handle ]->extra["conditional"] ) ) {
			$conditional = $wp_styles->registered[ $handle ]->extra["conditional"]; # such as ie7, ie8, ie9, etc
		}

		# mediatype
		$mt = isset( $wp_styles->registered[ $handle ]->args ) ? $wp_styles->registered[ $handle ]->args : 'all';
		if ( $mt == 'screen' || $mt == 'screen, print' || empty( $mt ) || is_null( $mt ) || $mt == false ) {
			$mt = 'all';
		}
		$mediatype = $mt;

		# full url or empty
		$hurl = $optimizoClass->returnFullURL( $wp_styles->registered[ $handle ]->src, $wp_domain, $wp_home );

		# inlined scripts without file
		if ( empty( $hurl ) ) {
			continue;
		}

		# mark duplicates as done and remove from the queue
		if ( ! empty( $hurl ) ) {
			$key = hash( 'adler32', $hurl );
			if ( isset( $uniq[ $key ] ) ) {
				$done = array_merge( $done, array( $handle ) );
				continue;
			} else {
				$uniq[ $key ] = $handle;
			}
		}
//
//		# Exclude specific CSS files from PSI?
//		if ( $fvm_min_excludecsslist != false && is_array( $fvm_min_excludecsslist ) && fastvelocity_min_in_arrayi( $hurl, $fvm_min_excludecsslist ) ) {
//			$cssguid = 'fvm' . hash( 'adler32', $hurl );
//			echo '<script type="text/javascript">if(!navigator.userAgent.match(/' . implode( '|', $fvmualist ) . '/i)){';
//			echo 'var ' . $cssguid . '=document.createElement("link");' . $cssguid . '.rel="stylesheet",' . $cssguid . '.type="text/css",' . $cssguid . '.media="async",' . $cssguid . '.href="' . $hurl . '",' . $cssguid . '.onload=function(){' . $cssguid . '.media="' . $mediatype . '"},document.getElementsByTagName("head")[0].appendChild(' . $cssguid . ');';
//			echo '}</script>';
//			$done = array_merge( $done, array( $handle ) );
//			continue;
//		}

//		# font awesome processing, async css
//		if ( $fvm_fawesome_method == 2 && stripos( $hurl, 'font-awesome' ) !== false ) {
//			echo '<link rel="preload" href="' . $hurl . '" as="style" media="' . $mediatype . '" onload="this.onload=null;this.rel=\'stylesheet\'" />';
//			echo '<noscript><link rel="stylesheet" href="' . $hurl . '" media="' . $mediatype . '" /></noscript>';
//			echo '<!--[if IE]><link rel="stylesheet" href="' . $hurl . '" media="' . $mediatype . '" /><![endif]-->';
//			$done = array_merge( $done, array( $handle ) );
//			continue;
//		}
//
//		# font awesome processing, async and exclude from PSI
//		if ( $fvm_fawesome_method == 3 && stripos( $hurl, 'font-awesome' ) !== false ) {
//			$cssguid = 'fvm' . hash( 'adler32', $hurl );
//			echo '<script type="text/javascript">if(!navigator.userAgent.match(/' . implode( '|', $fvmualist ) . '/i)){';
//			echo 'var ' . $cssguid . '=document.createElement("link");' . $cssguid . '.rel="stylesheet",' . $cssguid . '.type="text/css",' . $cssguid . '.media="async",' . $cssguid . '.href="' . $hurl . '",' . $cssguid . '.onload=function(){' . $cssguid . '.media="' . $mediatype . '"},document.getElementsByTagName("head")[0].appendChild(' . $cssguid . ');';
//			echo '}</script>';
//			$done = array_merge( $done, array( $handle ) );
//			continue;
//		}

		# array of info to save
		$arr = array( 'handle' => $handle, 'url' => $hurl, 'conditional' => $conditional, 'mediatype' => $mediatype );

		# google fonts to the top (collect and skip process array)
		if ( stripos( $hurl, 'fonts.googleapis.com' ) !== false ) {

			if ( $skip_google_fonts != true || $force_inline_googlefonts != false ) {
				$google_fonts[ $handle ] = $hurl;

			} else {
				wp_enqueue_style( $handle ); # skip google fonts optimization?
			}
			continue;
		}

		$google_fonts[ $handle ] = $hurl;

		# all else
		$process[ $handle ] = $arr;

	endforeach;


# concat google fonts, if enabled
	if (count( $google_fonts ) > 0) {
		foreach ( $google_fonts as $h => $a ) {
			$done = array_merge( $done, array( $h ) );
		} # mark as done

		# merge google fonts if force inlining is enabled?
		$nfonts = array();
		if ( $skip_google_fonts != true ) {
			$nfonts[] = $optimizoClass->getWPProtocol( $optimizoClass->concatenateGoogleFonts( $google_fonts ) );
		} else {
			foreach ( $google_fonts as $a ) {
				if ( ! empty( $a ) ) {
					$nfonts[] = $a;
				}
			}
		}

		# foreach google font (will be one if merged is not disabled)
		if ( count( $nfonts ) > 0 ) {
			foreach ( $nfonts as $gfurl ) {

				if ( $min_async_googlefonts == true ) {
					echo '<link rel="preload" href="' . $gfurl . '" as="style" media="all" onload="this.onload=null;this.rel=\'stylesheet\'" />';
					echo '<noscript><link rel="stylesheet" href="' . $gfurl . '" media="all" /></noscript>';
					echo '<!--[if IE]><link rel="stylesheet" href="' . $gfurl . '" media="all" /><![endif]-->';

					# inline css
				} else {
					wp_enqueue_style( 'header-fvm-fonts', $gfurl, array(), null, 'all' );
				}

			}
		}
	}


# get groups of handles
	foreach ( $styles->to_do as $handle ) :

# skip already processed google fonts and empty dependencies
		if ( isset( $google_fonts[ $handle ] ) ) {
			continue;
		}                     # skip google fonts
		if ( empty( $wp_styles->registered[ $handle ]->src ) ) {
			continue;
		}        # skip empty src
		if ( $optimizoClass->minifyInArray( $handle, $done ) ) {
			continue;
		}       # skip if marked as done before
		if ( ! isset( $process[ $handle ] ) ) {
			continue;
		}                        # skip if not on our unique process list

# get full url
		$hurl        = $process[ $handle ]['url'];
		$conditional = $process[ $handle ]['conditional'];
		$mediatype   = $process[ $handle ]['mediatype'];


		# skip ignore list, conditional css, external css, font-awesome merge
		if ( ( ! $optimizoClass->minifyInArray( $hurl, $ignore ) && ! isset( $conditional ) && $optimizoClass->checkIfInternalLink( $hurl, $wp_home ) )
		     || empty( $hurl )
		     || ( $fvm_fawesome_method == 1 && stripos( $hurl, 'font-awesome' ) !== false ) ) {

			# colect inline css for this handle
			if ( isset( $wp_styles->registered[ $handle ]->extra['after'] ) && is_array( $wp_styles->registered[ $handle ]->extra['after'] ) ) {
				$inline_css[ $handle ]                            = fastvelocity_min_minify_css_string( implode( '', $wp_styles->registered[ $handle ]->extra['after'] ) ); # save
				$wp_styles->registered[ $handle ]->extra['after'] = null; # dequeue
			}

			# process
			if ( isset( $header[ count( $header ) - 1 ]['handle'] ) || count( $header ) == 0 || $header[ count( $header ) - 1 ]['media'] != $mediatype ) {
				array_push( $header, array( 'handles' => array(), 'media' => $mediatype ) );
			}

			# push it to the array
			array_push( $header[ count( $header ) - 1 ]['handles'], $handle );

			# external and ignored css
		} else {

			# normal enqueuing
			array_push( $header, array( 'handle' => $handle ) );
		}
	endforeach;

# reorder CSS by mediatypes
	if ( ! $skip_cssorder ) {
		if ( count( $header ) > 0 ) {

			# get unique mediatypes
			$allmedia = array();
			foreach ( $header as $array ) {
				if ( isset( $array['media'] ) ) {
					$allmedia[ $array['media'] ] = '';
				}
			}

			# extract handles by mediatype
			$grouphandles = array();
			foreach ( $allmedia as $md => $var ) {
				foreach ( $header as $array ) {
					if ( isset( $array['media'] ) && $array['media'] === $md ) {
						foreach ( $array['handles'] as $h ) {
							$grouphandles[ $md ][] = $h;
						}
					}
				}
			}

			# reset and reorder header by mediatypes
			$newheader = array();
			foreach ( $allmedia as $md => $var ) {
				$newheader[] = array( 'handles' => $grouphandles[ $md ], 'media' => $md );
			}
			if ( count( $newheader ) > 0 ) {
				$header = $newheader;
			}
		}
	}

# loop through header css and merge
	for ( $i = 0, $l = count( $header ); $i < $l; $i ++ ) {
		if ( ! isset( $header[ $i ]['handle'] ) ) {

			# get has for the inline css in this group
			$inline_css_group = array();
			foreach ( $header[ $i ]['handles'] as $h ) {
				if ( isset( $inline_css[ $h ] ) && ! empty( $inline_css[ $h ] ) ) {
					$inline_css_group[] = $inline_css[ $h ];
				}
			}
			$inline_css_hash = md5( implode( '', $inline_css_group ) );

			# static cache file info + done
			$done = array_merge( $done, $header[ $i ]['handles'] );
			$hash = 'header-' . hash( 'adler32', implode( '', $header[ $i ]['handles'] ) . $inline_css_hash );

			# create cache files and urls
			$file     = $cachedir . '/' . $hash . '.min.css';
			$file_url = $optimizoClass->getWPProtocol( $cachedirurl . '/' . $hash . '.min.css' );

			# generate a new cache file
			clearstatcache();
			if ( ! file_exists( $file ) ) {

				# code and log initialization
				$log  = '';
				$code = '';

				# minify and write to file
				foreach ( $header[ $i ]['handles'] as $handle ) :
					if ( ! empty( $wp_styles->registered[ $handle ]->src ) ) {

						# get hurl per handle
						$hurl = $optimizoClass->returnFullURL( $wp_styles->registered[ $handle ]->src, $wp_domain, $wp_home );

						# inlined scripts without file
						if ( empty( $hurl ) ) {
							continue;
						}

						# print url
						$printurl = str_ireplace( array( site_url(), home_url(), 'http:', 'https:' ), '', $hurl );

						# download, minify, cache
						$tkey = 'css-' . hash( 'adler32', $handle . $hurl ) . '.css';
						$json = false;
						$json = $optimizoClass->getTempStore( $tkey );
						if ( $json === false ) {
							$json = $optimizoClass->downloadAndMinify( $hurl, null, 'css', $handle );
							if ( $fvm_debug == true ) {
								echo "<!-- FVM DEBUG: Uncached file processing now for $handle / $hurl -->" . PHP_EOL;
							}
							$optimizoClass->setTempStore( $tkey, $json );
						}

						# decode
						$res = json_decode( $json, true );

						# response has failed
						if ( $res['status'] != true ) {
							$log .= $res['log'];
							continue;
						}

						# append code to merged file
						$code .= $res['code'];
						$log  .= $res['log'];

						# append inlined styles
						if ( isset( $inline_css[ $handle ] ) && ! empty( $inline_css[ $handle ] ) ) {
							$code .= $inline_css[ $handle ];
						}

						# consider dependencies on handles with an empty src
					} else {
						wp_dequeue_script( $handle );
						wp_enqueue_script( $handle );
					}
				endforeach;

				# prepare log
				$log = "PROCESSED on " . date( 'r' ) . PHP_EOL . $log . "PROCESSED from " . home_url( add_query_arg( null, null ) ) . PHP_EOL;

				# generate cache, write log
				if ( ! empty( $code ) ) {
					file_put_contents( $file . '.txt', $log );
					file_put_contents( $file, $code );
					file_put_contents( $file . '.gz', gzencode( file_get_contents( $file ), 9 ) );

					# permissions
					$optimizoClass->fixPermissions( $file . '.txt' );
					$optimizoClass->fixPermissions( $file );
					$optimizoClass->fixPermissions( $file . '.gz' );

					# brotli static support
					if ( function_exists( 'brotli_compress' ) ) {
						file_put_contents( $file . '.br', brotli_compress( file_get_contents( $file ), 11 ) );
						$optimizoClass->fixPermissions( $file . '.br' );
					}
				}
			}

			# register and enqueue minified file, consider excluding of mediatype "print" and inline css
			if ( $remove_print_mediatypes != true || ( $remove_print_mediatypes == true && $header[ $i ]['media'] != 'print' ) ) {

				# the developers tab, takes precedence

				# Async CSS with loadCSS ?
				if ( $fvmloadcss != false && $fvm_remove_css != true ) {
					$mt = $header[ $i ]['media'];
					echo '<link rel="preload" href="' . $file_url . '" as="style" media="' . $mt . '" onload="this.onload=null;this.rel=\'stylesheet\'" />';
					echo '<noscript><link rel="stylesheet" href="' . $file_url . '" media="' . $mt . '" /></noscript>';
					echo '<!--[if IE]><link rel="stylesheet" href="' . $file_url . '" media="' . $mt . '" /><![endif]-->';

					# enqueue file, if not empty
				} else {
					if ( file_exists( $file ) && filesize( $file ) > 0 ) {

						# inline CSS if mediatype is not of type "all" (such as mobile only), if the file is smaller than 20KB
						if ( filesize( $file ) < 20000 && $header[ $i ]['media'] != 'all' ) {
							echo '<style id="fvm-header-' . $i . '" media="' . $header[ $i ]['media'] . '">' . file_get_contents( $file ) . '</style>';
						} else {
							# enqueue it
							wp_enqueue_style( "fvm-header-$i", $file_url, array(), null, $header[ $i ]['media'] );
						}
					} else {
						# file could not be generated, output something meaningful
						echo "<!-- ERROR: FVM was not allowed to save it's cache on - $file -->";
						echo "<!-- Please check if the path above is correct and ensure your server has writting permission there! -->";
						echo "<!-- If you found a bug, please report this on https://wordpress.org/support/plugin/fast-velocity-minify/ -->";
					}
				}
			}

			# other css need to be requeued for the order of files to be kept
		} else {
			wp_dequeue_style( $header[ $i ]['handle'] );
			wp_enqueue_style( $header[ $i ]['handle'] );
		}
	}

# remove from queue
	$wp_styles->done = $done;

}
