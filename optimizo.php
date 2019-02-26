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


class Optimizo {
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

		require_once( 'class.optimizo.php' );

		$optimizoClass = new OptimizoClass();

		$optimizoClass->addToWPConfig();

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