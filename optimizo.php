<?php
/**
 * Plugin Name: Optimizo
 * Plugin URI:  https://www.optimizo.lk
 * Description: Automatic optimization for your website
 * Version:     0.0.5
 * Author:      Minhaz Irphan
 * Author URI:  https://minhaz.winauthority.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


if (!defined('ABSPATH')){
    die;
}


class Optimizo {
    function activation(){
    	$path = ABSPATH;
    	//Checking if it's installed in a sub-directory

	    if($this->is_sub_directory_install()){
	    	$path = $this->get_ABSPATH();
	    }

	    if (is_plugin_active($path.'/wp-content/plugins/Optimizo/optimizo.php')){

	    } else {
		    add_action( 'admin_notices', 'display_error_message' );
	    }

	    //Adding the default wp_cache in the wp_config file. This line will be added with a comment which will be easy for a user to identify that it's from this plugin

	    $wp_config_file = @file_get_contents(ABSPATH."wp-config.php");
//
//	    if (!str_replace("define('WP_CACHE', true);", "/** Optimizo's configuration for cache **/ \ndefine('WP_CACHE', true);\n\n", $wp_config_file)){
//
//
//
//        }

	    $wp_config_file = str_replace("/** MySQL hostname */", "/** Optimizo's configuration for cache **/ \ndefine('WP_CACHE', true);\n\n/** MySQL hostname */", $wp_config_file);

	    if(!@file_put_contents(ABSPATH."wp-config.php", $wp_config_file)){
		    add_action('admin_notices', 'failed_to_add_wp_config');
	    }

    }

    function deactivation(){
	    //Adding the default wp_cache in the wp_config file. This line will be added with a comment which will be easy for a user to identify that it's from this plugin

	    $wp_config_file = @file_get_contents(ABSPATH."wp-config.php");

	    $wp_config_file = str_replace("/** Optimizo's configuration for cache **/ \ndefine('WP_CACHE', true);", null, $wp_config_file);

	    if(!@file_put_contents(ABSPATH."wp-config.php", $wp_config_file)){
		    add_action('admin_notices', 'failed_to_add_wp_config');
	    }
    }

    function uninstall(){

    }


	public function is_sub_directory_install(){
		if(strlen(site_url()) > strlen(home_url())){
			return true;
		}
		return false;
	}

	public function get_ABSPATH(){
		$path = ABSPATH;
		$websiteUrl = site_url();
		$websiteHomeUrl = home_url();
		$diff = str_replace($websiteHomeUrl, "", $websiteUrl);
		$diff = trim($diff,"/");

		$pos = strrpos($path, $diff);

		if($pos !== false){
			$path = substr_replace($path, "", $pos, strlen($diff));
			$path = trim($path,"/");
			$path = "/".$path."/";
		}
		return $path;
	}

}

function failed_to_add_wp_config(){
	?>
    <div class="notice notice-info">
        <p><?php _e( 'Optimizo has created wasn\'t able to edit your wp-config file.'); ?></p>
    </div>
	<?php
}

function display_message_on_activation(){
	?>
	<div class="notice notice-info">
		<p><?php _e( 'Thank you for installing & activating Optimizo. Your website is in good hands!'); ?></p>
	</div>
	<?php
}

if (class_exists('Optimizo')){
	add_action( 'admin_notices', 'display_message_on_activation' );
    $optimizo = new Optimizo();
} else {

}

register_activation_hook( __FILE__, array( $optimizo, 'activation' ) );
register_deactivation_hook( __FILE__, array( $optimizo, 'deactivation' ) );