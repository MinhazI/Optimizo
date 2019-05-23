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
$webpCache = $cachePath['$webpCache'];

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
			add_action( 'wp_print_styles', array( $this, 'minifyCSS' ), PHP_INT_MAX );
			if ( ! $this->getWebsiteHTTPResponse( $wpHome ) ) {
				remove_action( 'wp_print_styles', array( $this, 'minifyCSS' ), PHP_INT_MAX );
			}

		} else {
			add_action( 'after_switch_theme', array( $this, 'removeCache' ) );
			add_action( 'save_post', array( $this, 'removeCache' ) );
			add_action( 'post_updated', array( $this, 'removeCache' ) );
		}
	}

	function activation() {
		$this->activate();
	}

	function deactivation() {
		$this->deactivate();
	}

	function uninstall() {
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

	#Code for minifying HTML of the website starts from here

	public function initializeMinifyHTML() {

		ob_start( array( $this, 'minifyHTML' ) );

	}

	# Adding a callback function to get access to the website's source code.
	protected function minifyHTML( $websiteHTMLContent ) {

		$newWebsiteHTMLContent = preg_replace( array( "/[[:blank:]]+/", "/\s+|\n+|\r/"), array(' ', ' '),
			str_replace( array("\t"), null, $websiteHTMLContent ) );

		$websiteHTMLContent = str_replace( array(
			'https://' . $_SERVER['HTTP_HOST'] . '/',
			'http://' . $_SERVER['HTTP_HOST'] . '/',
			'//' . $_SERVER['HTTP_HOST'] . '/'
		), array( '/', '/', '/' ), $newWebsiteHTMLContent );

		$websiteHTMLContent = str_replace( array(
			'</p>',
			'</li>',
			'</img>'
		), null, $websiteHTMLContent );

		if (isset($_SERVER['HTTPS'])){
			$websiteHTMLContent = str_replace('http://', 'https://', $websiteHTMLContent);
		}

		return $websiteHTMLContent;
	}

	#HTML minifying code ends here

	public function minifyHeaderJS() {
		global $cacheDir, $wp_scripts, $wpDomain, $wpHome, $ignore, $cacheBaseURL;

		$headerScripts = wp_clone( $wp_scripts );
		$headerScripts->all_deps( $headerScripts->queue );
		$headerArray = array();

		if ( ! is_object( $wp_scripts ) ) {
			return false;
		}

		$isDone = $headerScripts->done;
		foreach ( $headerScripts->to_do as $headerScriptHandle ) :

			$isFooterScript = 0;
			if ( isset( $wp_scripts->registered[ $headerScriptHandle ]->args ) || isset( $wp_scripts->registered[ $headerScriptHandle ]->extra["group"] ) ) {
				$isFooterScript = 1;
			}

			if ( $isFooterScript == 1 ) {

				$url = $this->returnFullURL( $wp_scripts->registered[ $headerScriptHandle ]->src, $wpDomain, $wpHome );

				if ( empty( $url ) ) {
					wp_enqueue_script( $headerScriptHandle, false );
				} else {
					wp_enqueue_script( $headerScriptHandle, $url, array(), null, true );
				}

			} else {
				$url = $this->returnFullURL( $wp_scripts->registered[ $headerScriptHandle ]->src, $wpDomain, $wpHome );
				if ( empty( $url ) ) {
					continue;
				}

				if ( ( ! $this->minifyInArray( $url, $ignore ) && ! isset( $wp_scripts->registered[ $headerScriptHandle ]->extra["conditional"] ) && $this->checkIfInternalLink( $url, $wpHome ) ) || empty( $url ) ) {

					if ( isset( $header[ count( $headerArray ) - 1 ]['handle'] ) || count( $headerArray ) == 0 ) {
						array_push( $headerArray, array( 'handles' => array() ) );
					}

					array_push( $headerArray[ count( $headerArray ) - 1 ]['handles'], $headerScriptHandle );

				} else {
					array_push( $headerArray, array( 'handle' => $headerScriptHandle ) );
				}

			}
		endforeach;
		$count = 0;
		$x     = count( $headerArray );
		while ( $count < $x ) {
			if ( isset( $headerArray[ $count ]['handle'] ) ) {

				wp_dequeue_script( $headerArray[ $count ]['handle'] );
				wp_enqueue_script( $headerArray[ $count ]['handle'] );

			} else {

				$isDone   = array_merge( $isDone, $headerArray[ $count ]['handles'] );

				$fileHash = 'header-optimizo-' . hash( 'md5', implode( '', $headerArray[ $count ]['handles'] ) );

				$headerScriptFile = $cacheDir . '/' . $fileHash . '.min.js';
				$fileURL          = $this->getWPProtocol( $cacheBaseURL . '/' . $fileHash . '.min.js' );

				clearstatcache();
				if ( ! file_exists( $headerScriptFile ) ) {
					$log          = '';
					$minifiedCode = '';

					foreach ( $headerArray[ $count ]['handles'] as $headerScriptHandle ) :
						if ( empty( $wp_scripts->registered[ $headerScriptHandle ]->src ) ) {

							wp_dequeue_script( $headerScriptHandle );
							wp_enqueue_script( $headerScriptHandle );

						} else {

							$url = $this->returnFullURL( $wp_scripts->registered[ $headerScriptHandle ]->src, $wpDomain, $wpHome );

							if ( empty( $url ) ) {
								continue;
							}
							$jsJson = $this->downloadAndMinify( $url, null, 'js', $headerScriptHandle );

							$decoded = json_decode( $jsJson, true );

							if ( $decoded['status'] != true ) {
								$log .= $decoded['log'];
								continue;
							}

							$minifiedCode .= $decoded['code'];
							$log          .= $decoded['log'];

							if ( ! empty( $wp_scripts->registered[ $headerScriptHandle ]->extra ) ) {
								if ( ! empty( $wp_scripts->registered[ $headerScriptHandle ]->extra['before'] ) ) {
									$minifiedCode .= PHP_EOL . implode( PHP_EOL, $wp_scripts->registered[ $headerScriptHandle ]->extra['before'] );
								}
							}
						}
					endforeach;

					$log = "Header JS files processed on " . date( "F j, Y, g:i a" ) . PHP_EOL . $log . "PROCESSED from " . site_url() . PHP_EOL;

					if ( ! empty( $minifiedCode ) ) {
						$this->addToLog( $log );

						file_put_contents( $headerScriptFile, $minifiedCode );
						$this->fixPermissions( $headerScriptFile );

						file_put_contents( $headerScriptFile . '.gz', gzencode( file_get_contents( $headerScriptFile ), 9 ) );
						$this->fixPermissions( $headerScriptFile . '.gz' );
					}
				}

				wp_register_script( "optimizo-header-$count", $fileURL, array(), null, false );

				$dataArray = array();
				foreach ( $headerArray[ $count ]['handles'] as $headerScriptHandle ) {
					if ( isset( $wp_scripts->registered[ $headerScriptHandle ]->extra['data'] ) ) {
						$dataArray[] = $wp_scripts->registered[ $headerScriptHandle ]->extra['data'];
					}
				}
				if ( count( $dataArray ) > 0 ) {
					$wp_scripts->registered["optimizo-header-$count"]->extra['data'] = implode( PHP_EOL, $dataArray );
				}

				if ( ! file_exists( $headerScriptFile ) && ! ( filesize( $headerScriptFile ) >= 1 || count( $dataArray ) >= 1 ) ) {

					echo "<!-- Well, this is quite embarrassing, but there seems to be an error that is not Optimizo to save your website's cache on - $headerScriptFile -->";
					echo "<!-- Please check if the mentioned path is correct and that it has writing permissions in that directory. -->";
					echo "<!-- If you think it's a bug, please do us a favor and email us at: hello@winauthorityinnovatives.com -->";

				} else {
					wp_enqueue_script( "optimizo-header-$count" );
				}
			}

			$count ++;
		}

		$wp_scripts->done = $isDone;
	}

	public function minifyCSS() {
		global $wp_styles, $wpDomain, $wpHome, $ignore, $cacheDir, $cacheBaseURL;

		if ( ! is_object( $wp_styles ) ) {
			return false;
		}
		$headerStyles = wp_clone( $wp_styles );
		$headerStyles->all_deps( $headerStyles->queue );
		$isDone      = $headerStyles->done;
		$headerArray = array();
		$googleFonts = array();
		$process     = array();
		$inlineCSS   = array();

		$uniqueArray = array();

		foreach ( $headerStyles->to_do as $headerStyleHandle ):

			$cssConditional = null;

			if ( isset( $wp_styles->registered[ $headerStyleHandle ]->extra["conditional"] ) ) {
				$cssConditional = $wp_styles->registered[ $headerStyleHandle ]->extra["conditional"];
			}

			if ( isset( $wp_styles->registered[ $headerStyleHandle ]->args ) ) {
				$currentMediaType = $wp_styles->registered[ $headerStyleHandle ]->args;
			} else {
				$currentMediaType = 'all';
			}

			if ( $currentMediaType == 'screen, print' || $currentMediaType == 'screen' || is_null( $currentMediaType ) || empty( $currentMediaType || $currentMediaType == false ) ) {
				$currentMediaType = 'all';
			}

			$url = $this->returnFullURL( $wp_styles->registered[ $headerStyleHandle ]->src, $wpDomain, $wpHome );

			$mediaType = $currentMediaType;

			if ( empty( $url ) ) {
				continue;
			}

			if ( ! empty( $url ) ) {
				$key = hash( 'md5', $url );
				if ( ! isset( $uniqueArray[ $key ] ) ) {
					$uniqueArray[ $key ] = $headerStyleHandle;
				} else {
					$isDone = array_merge( $isDone, array( $headerStyleHandle ) );
					continue;
				}
			}

			$infoArray = array(
				'handle'      => $headerStyleHandle,
				'url'         => $url,
				'cssConditional' => $cssConditional,
				'mediaType'   => $mediaType
			);

			if ( stripos( $url, 'fonts.googleapis.com' ) !== false ) {
				$googleFonts[ $headerStyleHandle ] = $url;
			}

			$process[ $headerStyleHandle ] = $infoArray;
		endforeach;

		if ( count( $googleFonts ) > 0 ) {
			foreach ( $googleFonts as $f => $x ) {
				$isDone = array_merge( $isDone, array( $f ) );
			}

			$newGoogleFonts   = array();
			$newGoogleFonts[] = $this->getWPProtocol( $this->concatenateGoogleFonts( $googleFonts ) );

			if ( count( $newGoogleFonts ) > 0 ) {
				foreach ( $newGoogleFonts as $googleFontURL ) {
					echo '<link rel="preload" href="' . $googleFontURL . '" as="style" media="all" onload="this.onload=null;this.rel=\'stylesheet\'" />';
					echo '<noscript><link rel="stylesheet" href="' . $googleFontURL . '" media="all" /></noscript>';
					echo '<!--[if IE]><link rel="stylesheet" href="' . $googleFontURL . '" media="all" /><![endif]-->';
				}
			}
		}

		foreach ( $headerStyles->to_do as $headerStyleHandle ) :

			if ( isset( $googleFonts[ $headerStyleHandle ] ) ) {
				continue;
			}
			if ( empty( $wp_styles->registered[ $headerStyleHandle ]->src ) ) {
				continue;
			}
			if ( $this->minifyInArray( $headerStyleHandle, $isDone ) ) {
				continue;
			}
			if ( ! isset( $process[ $headerStyleHandle ] ) ) {
				continue;
			}

			$url         = $process[ $headerStyleHandle ]['url'];
			$cssConditional = $process[ $headerStyleHandle ]['cssConditional'];
			$mediaType   = $process[ $headerStyleHandle ]['mediaType'];

			if ( ( ! $this->minifyInArray( $url, $ignore ) && ! isset( $cssConditional ) && $this->checkIfInternalLink( $url, $wpHome ) )
			     || empty( $url ) ) {

				if ( isset( $wp_styles->registered[ $headerStyleHandle ]->extra['after'] ) && is_array( $wp_styles->registered[ $headerStyleHandle ]->extra['after'] ) ) {
					$inlineCSS[ $headerStyleHandle ]                             = $this->minifyCSSWithPHP( implode( '', $wp_styles->registered[ $headerStyleHandle ]->extra['after'] ) );
					$wp_styles->registered[ $headerStyleHandle ]->extra['after'] = null;
				}

				if ( isset( $headerArray[ count( $headerArray ) - 1 ]['handle'] ) || count( $headerArray ) == 0 || $headerArray[ count( $headerArray ) - 1 ]['media'] != $mediaType ) {
					array_push( $headerArray, array( 'handles' => array(), 'media' => $mediaType ) );
				}

				array_push( $headerArray[ count( $headerArray ) - 1 ]['handles'], $headerStyleHandle );

			} else {

				array_push( $headerArray, array( 'handle' => $headerStyleHandle ) );
			}
		endforeach;

		$count = 0;
		$x     = count( $headerArray );

		while ( $count < $x ) {
			if ( ! isset( $headerArray[ $count ]['handle'] ) ) {
				$inlineCSSGroup = array();
				foreach ( $headerArray[ $count ]['handles'] as $handles ) {
					if ( isset( $inlineCSS[ $handles ] ) && ! empty( $inlineCSS[ $handles ] ) ) {
						$inlineCSSGroup[] = $inlineCSS[ $handles ];
					}
				}

				$inlineCSSHash = md5( implode( '', $inlineCSSGroup ) );

				$isDone   = array_merge( $isDone, $headerArray[ $count ]['handles'] );
				$fileHash = 'css-optimizo-' . hash( 'md5', implode( '', $headerArray[ $count ]['handles'] ) . $inlineCSSHash );
				$headerStyleSheet = $cacheDir . '/' . $fileHash . '.min.css';
				$fileURL          = $this->getWPProtocol( $cacheBaseURL . '/' . $fileHash . '.min.css' );

				clearstatcache();
				if ( ! file_exists( $headerStyleSheet ) ) {

					$log          = '';
					$minifiedCode = '';

					foreach ( $headerArray[ $count ]['handles'] as $headerStyleHandle ) :
						if ( empty( $wp_styles->registered[ $headerStyleHandle ]->src ) ) {

							wp_dequeue_script( $headerStyleHandle );
							wp_enqueue_script( $headerStyleHandle );

						} else {

							$url = $this->returnFullURL( $wp_styles->registered[ $headerStyleHandle ]->src, $wpDomain, $wpHome );
							if ( empty( $url ) ) {
								continue;
							}

							$json = $this->downloadAndMinify( $url, null, 'css', $headerStyleHandle );

							$decoded = json_decode( $json, true );

							if ( $decoded['status'] != true ) {
								$log .= $decoded['log'];
								continue;
							}

							$minifiedCode .= $decoded['code'];
							$log          .= $decoded['log'];

							if ( ! empty( $inlineCSS[ $headerStyleHandle ] ) && isset( $inlineCSS[ $headerStyleHandle ] ) ) {
								$minifiedCode .= $inlineCSS[ $headerStyleHandle ];
							}
						}
					endforeach;

					$log = "CSS files processed on " . date( "F j, Y, g:i a" ) . PHP_EOL . $log . "PROCESSED from " . site_url() . PHP_EOL;

					if ( ! empty( $minifiedCode ) ) {
						$this->addToLog( $log );

						file_put_contents( $headerStyleSheet, $minifiedCode );
						$this->fixPermissions( $headerStyleSheet );

						file_put_contents( $headerStyleSheet . '.gz', gzencode( file_get_contents( $headerStyleSheet ), 9 ) );
						$this->fixPermissions( $headerStyleSheet . '.gz' );
					}
				}

				if ( ! file_exists( $headerStyleSheet ) && filesize( $headerStyleSheet ) == 0 ) {

					echo "<!-- Well, this is quite embarrassing, but there seems to be an error that is not Optimizo to save your website's cache on - $headerStyleSheet -->";
					echo "<!-- Please check if the mentioned path is correct and that it has writing permissions in that directory. -->";
					echo "<!-- If you think it's a bug, please do us a favor and email us at: hello@winauthorityinnovatives.com -->";

				} else {
					if ( filesize( $headerStyleSheet ) < 20000 && $headerArray[ $count ]['media'] != 'all' ) {
						echo '<style id="optimizo-css-' . $count . '" media="' . $headerArray[ $count ]['media'] . '">' . file_get_contents( $headerStyleSheet ) . '</style>';
					} else {
						wp_enqueue_style( "css-optimizo-$count", $fileURL, array(), null, $headerArray[ $count ]['media'] );
					}
				}

			} else {
				wp_dequeue_style( $headerArray[ $count ]['handle'] );
				wp_enqueue_style( $headerArray[ $count ]['handle'] );
			}
			$count ++;
		}

		$wp_styles->done = $isDone;
	}

	public function minifyFooterJS() {
		global $cacheDir, $cacheBaseURL, $wp_scripts, $wpDomain, $wpHome, $ignore;;

		if ( ! is_object( $wp_scripts ) ) {
			return false;
		}
		$footerScripts = wp_clone( $wp_scripts );
		$footerScripts->all_deps( $footerScripts->queue );
		$footer = array();

		$isDone = $footerScripts->done;

		foreach ( $footerScripts->to_do as $footerScriptHandle ) :

			$url = $this->returnFullURL( $wp_scripts->registered[ $footerScriptHandle ]->src, $wpDomain, $wpHome );

			if ( empty( $url ) ) {
				continue;
			}

			if ( ( ! $this->minifyInArray( $url, $ignore ) && ! isset( $wp_scripts->registered[ $footerScriptHandle ]->extra["conditional"] ) && $this->checkIfInternalLink( $url, $wpHome ) ) || empty( $url ) ) {

				if ( isset( $footer[ count( $footer ) - 1 ]['handle'] ) || count( $footer ) == 0 ) {
					array_push( $footer, array( 'handles' => array() ) );
				}

				array_push( $footer[ count( $footer ) - 1 ]['handles'], $footerScriptHandle );

			} else {
				array_push( $footer, array( 'handle' => $footerScriptHandle ) );
			}
		endforeach;

		$count = 0;
		$x     = count( $footer );
		while ( $count < $x ) {
			if ( isset( $footer[ $count ]['handle'] ) ) {

				wp_dequeue_script( $footer[ $count ]['handle'] );
				wp_enqueue_script( $footer[ $count ]['handle'] );

			} else {
				$isDone   = array_merge( $isDone, $footer[ $count ]['handles'] );
				$fileHash = 'footer-optimizo-' . hash( 'md5', implode( '', $footer[ $count ]['handles'] ) );

				$footerScriptFile = $cacheDir . '/' . $fileHash . '.min.js';
				$fileURL          = $this->getWPProtocol( $cacheBaseURL . '/' . $fileHash . '.min.js' );

				clearstatcache();
				if ( ! file_exists( $footerScriptFile ) ) {

					$log          = '';
					$minifiedCode = '';

					foreach ( $footer[ $count ]['handles'] as $footerScriptHandle ) :
						if ( empty( $wp_scripts->registered[ $footerScriptHandle ]->src ) ) {

							wp_dequeue_script( $footerScriptHandle );
							wp_enqueue_script( $footerScriptHandle );

						} else {
							$url = $this->returnFullURL( $wp_scripts->registered[ $footerScriptHandle ]->src, $wpDomain, $wpHome );

							if ( empty( $url ) ) {
								continue;
							}

							$json = $this->downloadAndMinify( $url, null, 'js', $footerScriptHandle );

							$decoded = json_decode( $json, true );

							if ( $decoded['status'] != true ) {
								$log .= $decoded['log'];
								continue;
							}

							$minifiedCode .= $decoded['code'];
							$log          .= $decoded['log'];

							if ( ! empty( $wp_scripts->registered[ $footerScriptHandle ]->extra ) ) {
								if ( ! empty( $wp_scripts->registered[ $footerScriptHandle ]->extra['before'] ) ) {
									$minifiedCode .= PHP_EOL . implode( PHP_EOL, $wp_scripts->registered[ $footerScriptHandle ]->extra['before'] );
								}
							}
						}
					endforeach;

					$log = "Footer JS files processed on " . date( "F j, Y, g:i a" ) . PHP_EOL . $log . "PROCESSED from " . site_url() . PHP_EOL;
					if ( ! empty( $minifiedCode ) ) {
						$this->addToLog( $log );

						file_put_contents( $footerScriptFile, $minifiedCode );
						$this->fixPermissions( $footerScriptFile );

						file_put_contents( $footerScriptFile . '.gz', gzencode( file_get_contents( $footerScriptFile ), 9 ) );
						$this->fixPermissions( $footerScriptFile . '.gz' );

					}
				}

				wp_register_script( "optimizo-footer-$count", $fileURL, array(), null, false );

				$dataArray = array();
				foreach ( $footer[ $count ]['handles'] as $footerScriptHandle ) {
					if ( isset( $wp_scripts->registered[ $footerScriptHandle ]->extra['data'] ) ) {
						$dataArray[] = $wp_scripts->registered[ $footerScriptHandle ]->extra['data'];
					}
				}
				if ( count( $dataArray ) > 0 ) {
					$wp_scripts->registered["optimizo-footer-$count"]->extra['data'] = implode( PHP_EOL, $dataArray );
				}

				if ( file_exists( $footerScriptFile ) && ( filesize( $footerScriptFile ) > 0 || count( $dataArray ) > 0 ) ) {
					wp_enqueue_script( "optimizo-footer-$count" );
				} else {

					echo "<!-- Well, this is quite embarrassing, but there seems to be an error that is not Optimizo to save your website's cache on - $footerScriptFile -->";
					echo "<!-- Please check if the mentioned path is correct and that it has writing permissions in that directory. -->";
					echo "<!-- If you think it's a bug, please do us a favor and email us at: hello@winauthorityinnovatives.com -->";
				}

			}
			$count ++;
		}

		$wp_scripts->done = $isDone;
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