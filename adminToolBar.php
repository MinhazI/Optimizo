<?php
/**
 * Created by PhpStorm.
 * User: minhaz
 * Date: 2/28/19
 * Time: 11:39 AM
 */

class optimizoAdminToolbar{
	function addToolbar(){
		if(is_admin()){
			add_action('wp_before_admin_bar_render', array($this, "tweakedToolbar"));
		}
	}

	function tweakedToolbar(){
		global $wp_admin_bar;

		$wp_admin_bar->add_node(array(
			'id'    => 'optimizo-toolbar-parent',
			'title' => 'Clear Optimizo Cache'
		));

		$wp_admin_bar->add_menu( array(
			'id'    => 'optimizo-toolbar-parent-delete-cache',
			'title' => 'Delete all Cache',
			'parent'=> 'optimizo-toolbar-parent',
			'meta' => array("class" => "optimizo-toolbar-child")
		));

		$wp_admin_bar->add_menu( array(
			'id'    => 'optimizo-toolbar-parent-delete-cache-and-minified',
			'title' => 'Delete both Cache and Minified CSS/JS',
			'parent'=> 'optimizo-toolbar-parent',
			'meta' => array("class" => "optimizo-toolbar-child")
		));
	}
}