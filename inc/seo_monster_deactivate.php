<?php
/**
 * Package Seo_monster
 */

if( ! defined( 'ABSPATH' ) ) exit;

class Seo_Monster_deactivate
{
	public static function deactivate(){
		flush_rewrite_rules();
	}
}
