<?php
/**
 * Plugin Name: Optimizo
 * Plugin URI:  https://www.optimizo.lk
 * Description: Automatic optimization for your website, this plugin will minify your website's HTML. It will also minify your JavaScript files and combine them as one. Optimizo will also cache your website. All of these optimizations will help your website in reducing the time it takes to load (also known as 'Page Load Time').
 * Version:     1.0.0
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
$cacheDir         = $cachePath['cachedir'];
$cacheDirURL      = $cachePath['cachedirurl'];
$cacheBaseURL     = $cachePath['cachedirurl'];

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

		require_once( 'adminToolBar.php' );
		$toolbar = new optimizoAdminToolbar();
		$toolbar->addToolbar();

		add_action( 'admin_notices', array( $this, 'displayMessageOnActivation' ) );

		if ( ! is_admin() ) {
			add_action( 'init', array( $this, 'initializeMinifyHTML' ), 1 );
			add_action( 'wp_print_scripts', array( $this, 'minifyHeaderJS' ), PHP_INT_MAX );
			add_action( 'wp_print_footer_scripts', array( $this, 'minifyFooterJS' ), 9 );
			add_action( 'wp_print_styles', array( $this, 'minifyCSSInHeader' ), PHP_INT_MAX );
			add_action( 'wp_print_footer_scripts', array( $this, 'minifyCSSinFooter' ), 999999 );
		} else {
			add_action( 'after_switch_theme', array( $this, 'removeCache' ) );
			add_action('save_post', array($this, 'removeCache'));
			add_action('post_updated', array($this, 'removeCache'));
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
				$furl = $this->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wpDomain, $wpHome );
				# inlined scripts without file
				if ( empty( $furl ) ) {
					continue;
				}
				# skip ignore list, scripts with conditionals, external scripts
				if ( ( ! $this->minifyInArray( $furl, $ignore ) && ! isset( $wp_scripts->registered[ $handle ]->extra["conditional"] ) && $this->checkIfInternalLink( $furl, $wpHome ) ) || empty( $furl ) ) {
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
				$furl = $this->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wpDomain, $wpHome );
				# inlined scripts without file
				if ( empty( $furl ) ) {
					wp_enqueue_script( $handle, false );
				} else {
					wp_enqueue_script( $handle, $furl, array(), null, true );
				}
			}
		endforeach;
		# loop through header scripts and merge
		for ( $i = 0, $l = count( $header ); $i < $l; $i ++ ) {
			if ( ! isset( $header[ $i ]['handle'] ) ) {
				# static cache file info + done
				$done     = array_merge( $done, $header[ $i ]['handles'] );
				$fileHash = 'header-optimizo-' . hash( 'md5', implode( '', $header[ $i ]['handles'] ) );
				# create cache files and urls
				$file     = $cacheDir . '/' . $fileHash . '.min.js';
				$file_url = $this->getWPProtocol( $cacheBaseURL . '/' . $fileHash . '.min.js' );
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
							$furl = $this->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wpDomain, $wpHome );
							# inlined scripts without file
							if ( empty( $furl ) ) {
								continue;
							}
							# print url
							$printurl = str_ireplace( array(
								site_url(),
								home_url(),
								'http:',
								'https:'
							), '://', $furl );
							# download, minify, cache
//						$tkey = 'js-' . hash( 'md5', $handle . $furl ) . '.js';
							$json = false;
							if ( $json === false ) {
								$json = $this->downloadAndMinify( $furl, null, 'js', $handle );
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
					$log = "Header JS files processed on " . date( "F j, Y, g:i a" ) . PHP_EOL . $log . "PROCESSED from " . site_url() . PHP_EOL;
					# generate cache, write log
					if ( ! empty( $code ) ) {
						$this->addToLog( $log );
						file_put_contents( $file, $code );
						file_put_contents( $file . '.gz', gzencode( file_get_contents( $file ), 9 ) );
						# permissions
//					$optimizoClass->fixPermissions( $file . '.txt' );
						$this->fixPermissions( $file );
						$this->fixPermissions( $file . '.gz' );
						# brotli static support
						if ( function_exists( 'brotli_compress' ) ) {
							file_put_contents( $file . '.br', brotli_compress( file_get_contents( $file ), 11 ) );
							$this->fixPermissions( $file . '.br' );
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
					echo "<!-- Well, this is quite embarrassing, but there seems to be an error that is not Optimizo to save your website's cache on - $file -->";
					echo "<!-- Please check if the path mentioned is correct and ensure your server has writing permission in that directory. -->";
					echo "<!-- If you think it's a bug, please do us a favor and email us at: hello@winauthorityinnovatives.com -->";
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

	public function minifyFooterJS() {
		global $cacheDir, $cacheBaseURL;

		global $wp_scripts, $wpDomain, $wpHome, $ignore;
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
			$furl = $this->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wpDomain, $wpHome );
			# inlined scripts without file
			if ( empty( $furl ) ) {
				continue;
			}
			# skip ignore list, scripts with conditionals, external scripts
			if ( ( ! $this->minifyInArray( $furl, $ignore ) && ! isset( $wp_scripts->registered[ $handle ]->extra["conditional"] ) && $this->checkIfInternalLink( $furl, $wpHome ) ) || empty( $furl ) ) {
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
				$done     = array_merge( $done, $footer[ $i ]['handles'] );
				$fileHash = 'footer-optimizo-' . hash( 'md5', implode( '', $footer[ $i ]['handles'] ) );
				# create cache files and urls
				$file     = $cacheDir . '/' . $fileHash . '.min.js';
				$file_url = $this->getWPProtocol( $cacheBaseURL . '/' . $fileHash . '.min.js' );
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
							$furl = $this->returnFullURL( $wp_scripts->registered[ $handle ]->src, $wpDomain, $wpHome );
							# inlined scripts without file
							if ( empty( $furl ) ) {
								continue;
							}
							# print url
							$printurl = str_ireplace( array( site_url(), home_url(), 'http:', 'https:' ), '', $furl );
							# download, minify, cache
							$json = $this->downloadAndMinify( $furl, null, 'js', $handle );
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
					$log = "Footer JS files processed on " . date( "F j, Y, g:i a" ) . PHP_EOL . $log . "PROCESSED from " . site_url() . PHP_EOL;
					# generate cache, write log
					if ( ! empty( $code ) ) {
						$this->addToLog( $log );
						file_put_contents( $file, $code );
						file_put_contents( $file . '.gz', gzencode( file_get_contents( $file ), 9 ) );
						# permissions
						$this->fixPermissions( $file );
						$this->fixPermissions( $file . '.gz' );
						# brotli static support
						if ( function_exists( 'brotli_compress' ) ) {
							file_put_contents( $file . '.br', brotli_compress( file_get_contents( $file ), 11 ) );
							$this->fixPermissions( $file . '.br' );
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
					echo "<!-- Well, this is quite embarrassing, but there seems to be an error that is not Optimizo to save your website's cache on - $file -->";
					echo "<!-- Please check if the path mentioned is correct and ensure your server has writing permission in that directory. -->";
					echo "<!-- If you think it's a bug, please do us a favor and email us at: hello@winauthorityinnovatives.com -->";
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

	public function minifyCSSInHeader() {
		global $wp_styles, $wpDomain, $wpHome, $ignore, $cacheDir, $cacheBaseURL;

		if ( ! is_object( $wp_styles ) ) {
			return false;
		}
		$styles = wp_clone( $wp_styles );
		$styles->all_deps( $styles->queue );
		$done         = $styles->done;
		$header       = array();
		$google_fonts = array();
		$process      = array();
		$inline_css   = array();
		$log          = '';
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
			$hurl = $this->returnFullURL( $wp_styles->registered[ $handle ]->src, $wpDomain, $wpHome );
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
			# array of info to save
			$arr = array(
				'handle'      => $handle,
				'url'         => $hurl,
				'conditional' => $conditional,
				'mediatype'   => $mediatype
			);
			# google fonts to the top (collect and skip process array)
			if ( stripos( $hurl, 'fonts.googleapis.com' ) !== false ) {
				$google_fonts[ $handle ] = $hurl;
			}
			# all else
			$process[ $handle ] = $arr;
		endforeach;
# concat google fonts, if enabled
		if ( count( $google_fonts ) > 0 ) {
			foreach ( $google_fonts as $h => $a ) {
				$done = array_merge( $done, array( $h ) );
			} # mark as done
			# merge google fonts if force inlining is enabled?
			$nfonts   = array();
			$nfonts[] = $this->getWPProtocol( $this->concatenateGoogleFonts( $google_fonts ) );
			# foreach google font (will be one if merged is not disabled)
			if ( count( $nfonts ) > 0 ) {
				foreach ( $nfonts as $gfurl ) {
					echo '<link rel="preload" href="' . $gfurl . '" as="style" media="all" onload="this.onload=null;this.rel=\'stylesheet\'" />';
					echo '<noscript><link rel="stylesheet" href="' . $gfurl . '" media="all" /></noscript>';
					echo '<!--[if IE]><link rel="stylesheet" href="' . $gfurl . '" media="all" /><![endif]-->';
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
			if ( $this->minifyInArray( $handle, $done ) ) {
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
			if ( ( ! $this->minifyInArray( $hurl, $ignore ) && ! isset( $conditional ) && $this->checkIfInternalLink( $hurl, $wpHome ) )
			     || empty( $hurl ) ) {
				# colect inline css for this handle
				if ( isset( $wp_styles->registered[ $handle ]->extra['after'] ) && is_array( $wp_styles->registered[ $handle ]->extra['after'] ) ) {
					$inline_css[ $handle ]                            = $this->minifyCSSWithPHP( implode( '', $wp_styles->registered[ $handle ]->extra['after'] ) ); # save
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
				$done     = array_merge( $done, $header[ $i ]['handles'] );
				$fileHash = 'header-optimizo-' . hash( 'md5', implode( '', $header[ $i ]['handles'] ) . $inline_css_hash );
				# create cache files and urls
				$file     = $cacheDir . '/' . $fileHash . '.min.css';
				$file_url = $this->getWPProtocol( $cacheBaseURL . '/' . $fileHash . '.min.css' );
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
							$hurl = $this->returnFullURL( $wp_styles->registered[ $handle ]->src, $wpDomain, $wpHome );
							# inlined scripts without file
							if ( empty( $hurl ) ) {
								continue;
							}
							# print url
							$printurl = str_ireplace( array( site_url(), home_url(), 'http:', 'https:' ), '', $hurl );
							# download, minify, cache
							$tkey = 'css-' . hash( 'adler32', $handle . $hurl ) . '.css';
							$json = false;
							if ( $json === false ) {
								$json = $this->downloadAndMinify( $hurl, null, 'css', $handle );
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
					$log = "Header CSS files processed on " . date( "F j, Y, g:i a" ) . PHP_EOL . $log . "PROCESSED from " . site_url() . PHP_EOL;

					# generate cache, write log
					if ( ! empty( $code ) ) {
						$this->addToLog( $log );
						file_put_contents( $file, $code );
						file_put_contents( $file . '.gz', gzencode( file_get_contents( $file ), 9 ) );
						# permissions
						$this->fixPermissions( $file );
						$this->fixPermissions( $file . '.gz' );
						# brotli static support
						if ( function_exists( 'brotli_compress' ) ) {
							file_put_contents( $file . '.br', brotli_compress( file_get_contents( $file ), 11 ) );
							$this->fixPermissions( $file . '.br' );
						}
					}
				}
				# register and enqueue minified file, consider excluding of mediatype "print" and inline css
				if ( file_exists( $file ) && filesize( $file ) > 0 ) {
					# inline CSS if mediatype is not of type "all" (such as mobile only), if the file is smaller than 20KB
					if ( filesize( $file ) < 20000 && $header[ $i ]['media'] != 'all' ) {
						echo '<style id="optimizo-header-' . $i . '" media="' . $header[ $i ]['media'] . '">' . file_get_contents( $file ) . '</style>';
					} else {
						# enqueue it
						wp_enqueue_style( "optimizo-header-$i", $file_url, array(), null, $header[ $i ]['media'] );
					}
				} else {
					# file could not be generated, output something meaningful
					echo "<!-- Well, this is quite embarrassing, but there seems to be an error that is not Optimizo to save your website's cache on - $file -->";
					echo "<!-- Please check if the path mentioned is correct and ensure your server has writing permission in that directory. -->";
					echo "<!-- If you think it's a bug, please do us a favor and email us at: hello@winauthorityinnovatives.com -->";
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

	public function minifyCSSinFooter() {
		global $wp_styles, $wpDomain, $wpHome, $ignore, $remove_print_mediatypes, $cacheDir;
		$remove_print_mediatypes = false;

		if ( ! is_object( $wp_styles ) ) {
			return false;
		}
		$styles = wp_clone( $wp_styles );
		$styles->all_deps( $styles->queue );
		$done         = $styles->done;
		$footer       = array();
		$google_fonts = array();
		$inline_css   = array();
# google fonts to the top
		foreach ( $styles->to_do as $handle ) :
			# dequeue and get a list of google fonts, or requeue external
			$hurl = $this->returnFullURL( $wp_styles->registered[ $handle ]->src, $wpDomain, $wpHome );
			# inlined scripts without file
			if ( empty( $hurl ) ) {
				continue;
			}
			if ( stripos( $hurl, 'fonts.googleapis.com' ) !== false ) {
				wp_dequeue_style( $handle );
				$google_fonts[ $handle ] = $hurl;
			} else {
				wp_dequeue_style( $handle );
				wp_enqueue_style( $handle ); # failsafe
			}
		endforeach;
# concat google fonts, if enabled
		if ( count( $google_fonts ) > 0 ) {
			foreach ( $google_fonts as $h => $a ) {
				$done = array_merge( $done, array( $h ) );
			} # mark as done
			# merge google fonts if force inlining is enabled?
			$nfonts   = array();
			$nfonts[] = $this->getWPProtocol( $this->concatenateGoogleFonts( $google_fonts ) );
			# foreach google font (will be one if merged is not disabled)
			if ( count( $nfonts ) > 0 ) {
				foreach ( $nfonts as $gfurl ) {
					# download, minify, cache
					$json = false;
					if ( $json === false ) {
						$json = $this->downloadAndMinify( $gfurl, null, 'css', null );
//					$optimizoClass->setTempStore( $tkey, $json );
					}
					# decode
					$res = json_decode( $json, true );
					# inline css or fail
					if ( $res['code'] !== false ) {
						echo '<style type="text/css" media="all">' . $res['code'] . '</style>' . PHP_EOL;
					} else {
						echo "<!-- GOOGLE FONTS REQUEST FAILED for $gfurl -->\n";
					}
				}
			}
		}
# get groups of handles
		$uniq = array();
		foreach ( $styles->to_do as $handle ) :
			# skip already processed google fonts
			if ( isset( $google_fonts[ $handle ] ) ) {
				continue;
			}
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
			# get full url
			$hurl = $this->returnFullURL( $wp_styles->registered[ $handle ]->src, $wpDomain, $wpHome );
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
			# skip ignore list, conditional css, external css, font-awesome merge
			if ( ( ! $this->minifyInArray( $hurl, $ignore ) && ! isset( $conditional ) && $this->checkIfInternalLink( $hurl, $wpHome ) )
			     || empty( $hurl ) ) {
				# colect inline css for this handle
				if ( isset( $wp_styles->registered[ $handle ]->extra['after'] ) && is_array( $wp_styles->registered[ $handle ]->extra['after'] ) ) {
					$inline_css[ $handle ]                            = $this->minifyCSSWithPHP( implode( '', $wp_styles->registered[ $handle ]->extra['after'] ) ); # save
					$wp_styles->registered[ $handle ]->extra['after'] = null; # dequeue
				}
				# process
				if ( isset( $footer[ count( $footer ) - 1 ]['handle'] ) || count( $footer ) == 0 || $footer[ count( $footer ) - 1 ]['media'] != $wp_styles->registered[ $handle ]->args ) {
					array_push( $footer, array( 'handles' => array(), 'media' => $mediatype ) );
				}
				# push it to the array get latest modified time
				array_push( $footer[ count( $footer ) - 1 ]['handles'], $handle );
				# external and ignored css
			} else {
				# normal enqueueing
				array_push( $footer, array( 'handle' => $handle ) );
			}
		endforeach;
# loop through footer css and merge
		for ( $i = 0, $l = count( $footer ); $i < $l; $i ++ ) {
			if ( ! isset( $footer[ $i ]['handle'] ) ) {
				# get has for the inline css in this group
				$inline_css_group = array();
				foreach ( $footer[ $i ]['handles'] as $h ) {
					if ( isset( $inline_css[ $h ] ) && ! empty( $inline_css[ $h ] ) ) {
						$inline_css_group[] = $inline_css[ $h ];
					}
				}
				$inline_css_hash = md5( implode( '', $inline_css_group ) );
				# static cache file info + done
				$done = array_merge( $done, $footer[ $i ]['handles'] );
				$hash = 'footer-optimizo-' . hash( 'md5', implode( '', $footer[ $i ]['handles'] ) . $inline_css_hash );
				# create cache files and urls
				$file     = $cacheDir . '/' . $hash . '.min.css';
				$file_url = $this->getWPProtocol( $cacheDir . '/' . $hash . '.min.css' );
				# generate a new cache file
				clearstatcache();
				if ( ! file_exists( $file ) ) {
					# code and log initialization
					$log  = '';
					$code = '';
					# minify and write to file
					foreach ( $footer[ $i ]['handles'] as $handle ) :
						if ( ! empty( $wp_styles->registered[ $handle ]->src ) ) {
							# get hurl per handle
							$hurl = $this->returnFullURL( $wp_styles->registered[ $handle ]->src, $wpDomain, $wpHome );
							# inlined scripts without file
							if ( empty( $hurl ) ) {
								continue;
							}
							# print url
							$printurl = str_ireplace( array( site_url(), home_url(), 'http:', 'https:' ), '', $hurl );
							# download, minify, cache
							$tkey = 'css-' . hash( 'adler32', $handle . $hurl ) . '.css';
							$json = false;
							if ( $json === false ) {
								$json = $this->downloadAndMinify( $hurl, null, 'css', $handle );
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
					$log = "Footer CSS files processed on " . date( "F j, Y, g:i a" ) . PHP_EOL . $log . "PROCESSED from " . site_url() . PHP_EOL;
					# generate cache, add inline css, write log
					if ( ! empty( $code ) ) {
						$this->addToLog( $log );
						file_put_contents( $file, $code ); # preserve style tags
						file_put_contents( $file . '.gz', gzencode( file_get_contents( $file ), 9 ) );
						# permissions
						$this->fixPermissions( $file . '.txt' );
						$this->fixPermissions( $file );
						$this->fixPermissions( $file . '.gz' );
						# brotli static support
						if ( function_exists( 'brotli_compress' ) ) {
							file_put_contents( $file . '.br', brotli_compress( file_get_contents( $file ), 11 ) );
							$this->fixPermissions( $file . '.br' );
						}
					}
				}
				# register and enqueue minified file, consider excluding of mediatype "print" and inline css
				if ( $remove_print_mediatypes != true ) {
					# the developers tab, takes precedence
					if ( file_exists( $file ) && filesize( $file ) > 0 ) {
						# inline if the file is smaller than 20KB or option has been enabled
						if ( filesize( $file ) < 20000 ) {
							echo '<style id="optimizo-footer-' . $i . '" media="' . $footer[ $i ]['media'] . '">' . file_get_contents( $file ) . '</style>';
						} else {
							# enqueue it
							wp_enqueue_style( "optimizo-footer-$i", $file_url, array(), null, $footer[ $i ]['media'] );
						}
					} else {
						# file could not be generated, output something meaningful
						echo "<!-- Well, this is quite embarrassing, but there seems to be an error that is not Optimizo to save your website's cache on - $file -->";
						echo "<!-- Please check if the path mentioned is correct and ensure your server has writing permission in that directory. -->";
						echo "<!-- If you think it's a bug, please do us a favor and email us at: hello@winauthorityinnovatives.com -->";
					}
				}
			}
# remove from queue
			$wp_styles->done = $done;
		}
	}

	public function displayMessageOnActivation() {
		?>
        <div class="notice notice-info is-dismissible">
            <p><?php _e( "Thank you for installing & activating Optimizo. Your website is in good hands! \n Optimizo has minified your website's HTML and JavaScript and has also started caching your website on your server. " ); ?></p>
        </div>
		<?php
	}
}

if ( class_exists( 'Optimizo' ) ) {
	$optimizo = new Optimizo();
}

register_activation_hook( __FILE__, array( $optimizo, 'activation' ) );
register_deactivation_hook( __FILE__, array( $optimizo, 'deactivation' ) );
register_uninstall_hook( __FILE__, array( $optimizo, 'uninstall' ) );

