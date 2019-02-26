<?php
/**
 * Created by PhpStorm.
 * User: minhaz
 * Date: 2/14/19
 * Time: 10:54 AM
 */

class OptimizoClass{
	function addToWPConfig(){

		/*
		 * This is a function which will be used to add anything to WordPress WP-Config file which includes all the configurations for a WordPress installation.
		 * Currently it only includes the function to add the "WP-CACHE" as true in the file.
		 */


		$wp_config_file = @file_get_contents( ABSPATH . "wp-config.php" );

		$wp_config_contents = @file_get_contents( ABSPATH . "wp-config.php" );

		$word = "define('WP_CACHE', true);";

		if(preg_match(".*".$word."*.", $wp_config_contents)){
			$wp_config_file = @file_get_contents( ABSPATH . "wp-config.php" );
			$wp_config_file = str_replace( "define('WP_CACHE', true);", "/** Optimizo's configuration for cache **/ \ndefine('WP_CACHE', false);\n\n", $wp_config_file);
			if (! @file_put_contents( ABSPATH . "wp-config.php", $wp_config_file ) ) {

			}
		} else {
			$wp_config_file = str_replace( "/** MySQL hostname */", "/** Optimizo's configuration for cache **/ \ndefine('WP_CACHE', true);\n\n/** MySQL hostname */", $wp_config_file );

			if (! @file_put_contents( ABSPATH . "wp-config.php", $wp_config_file ) ) {

			}
		}
	}

	function writeToHtaccess(){

		/*
		 * This function will be used to write the rewrite access rules and other rules for caching and G-Zip to the server's .htaccess file
		 */

	}


	function deactivate(){

		/*
		 * This is the function that will be used and called upon the deactivation of the plugin.
		 * Currently this function removes the "WP-CACHE" statement from the WP-Config file if it's available.
		 */

		$wp_config_file = @file_get_contents( ABSPATH . "wp-config.php" );

		$wp_config_file = str_replace( "/** Optimizo's configuration for cache **/ \ndefine('WP_CACHE', true);", null, $wp_config_file );

		if ( ! @file_put_contents( ABSPATH . "wp-config.php", $wp_config_file ) ) {
			add_action( 'admin_notices', 'failed_to_add_wp_config' );
		}
	}
}