<?php
/**
 * Plugin Name: Optimizo
 * Plugin URI:  https://www.optimizo.lk
 * Description: Automatic optimization for your website
 * Version:     0.0.2
 * Author:      Minhaz Irphan
 * Author URI:  https://minhaz.winauthority.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


register_activation_hook( __FILE__, array( 'Optimizo', 'plugin_activation' ) );