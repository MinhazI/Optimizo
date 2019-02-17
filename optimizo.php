<?php
/**
 * Plugin Name: Optimizo
 * Plugin URI:  https://www.optimizo.lk
 * Description: Automatic optimization for your website
 * Version:     0.0.3
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
        echo "This plugin is activated fam!";
    }

    function deactivation(){

    }

    function uninstall(){

    }
}

if (class_exists('Optimizo')){
    $optimizo = new Optimizo();
}

register_activation_hook( __FILE__, array( $optimizo, 'activation' ) );

//define( 'OPTIMIZO__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

//require_once (OPTIMIZO__PLUGIN_DIR . 'class.optimizo.php');