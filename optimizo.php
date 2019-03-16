<?php
/**
 * Plugin Name: Optimizo
 * Plugin URI:  https://www.optimizo.lk
 * Description: Automatic optimization for your website
 * Version:     0.0.6
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
        <p><?php _e( 'Thank you for installing & activating Optimizo. Your website is in good hands!' ); ?></p>
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
add_action( 'init', 'init_minify_html', 1 );

function init_minify_html() {
	$optmizoHTMLMinifier = new optimizoHTMLMinifier();
	$optmizoHTMLMinifier->minifyHTML();
}

function minifyHTML() {
	ob_start( 'minifyHTMLOutput' );
}

// Adding a callback function to get access to the website's source code.
function minifyHTMLOutput( $buffer ) {
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