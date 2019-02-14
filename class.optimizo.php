<?php
/**
 * Created by PhpStorm.
 * User: minhaz
 * Date: 2/14/19
 * Time: 10:54 AM
 */

function register_activation_hook($file, $function) {
    $file = plugin_basename($file);
    add_action('activate_' . $file, $function);

    echo 'Ongada aaya';
}