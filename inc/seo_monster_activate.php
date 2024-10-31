<?php
/**
 * Package Seo_monster
 */

if( ! defined( 'ABSPATH' ) ) exit;

class Seo_Monster_activate
{
	public static function activate(){
		if (!get_transient('_my_seobots_welcome_screen')) {
			return;
		}
		delete_transient('_my_seobots_welcome_screen');
		if (is_network_admin() || isset($_GET['activate-multi'])) {
			return;
		}
		wp_safe_redirect(add_query_arg(array('page' => 'seo-monster'), admin_url('index.php')));
		flush_rewrite_rules();
	}
}
