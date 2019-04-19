<?php
/**
 * Created by PhpStorm.
 * User: minhaz
 * Date: 2/28/19
 * Time: 11:39 AM
 */

class optimizoAdminToolbar extends OptimizoFunctions {
	public function addToolbar(){
		if(is_admin()){
			add_action('wp_before_admin_bar_render', array($this, "optimizoToolBar"));
		}
	}

	public function optimizoToolBar(){
		global $wp_admin_bar;

		$wp_admin_bar->add_node(array(
			'id'    => 'optimizo-toolbar-parent',
			'title' => 'Clear Optimizo Cache',
			'href' => wp_nonce_url($this->removeCache())
		));
	}
}