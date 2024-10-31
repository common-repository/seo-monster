<?php
class seomonster_410 {
	const db_version = 5;
	private $permalinks;
	private $table;

	function __construct() {
		//seomonster_410::permalinks = (bool) get_option('permalink_structure');
		//seomonster_410::table = $GLOBALS['wpdb']->prefix . 'seomonster_410';
		$this->table = $GLOBALS['wpdb']->prefix . 'seomonster_410';

		add_action( 'plugins_loaded',	array( $this, 'upgrade_check' ));

		// these could theoretically happen both with/without is_admin()
		add_action('wp_insert_post', 	array( $this, 'note_inserted_post' ));
	}

	private function install_table() {
		// remember, two spaces after PRIMARY KEY otherwise WP borks
		$sql = "CREATE TABLE $this->table (
			gone_id MEDIUMINT unsigned NOT NULL AUTO_INCREMENT,
			gone_key VARCHAR(512) NOT NULL,
			gone_regex VARCHAR(512) NOT NULL,
			PRIMARY KEY  (gone_id)
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	public function get_links(){
		global $wpdb;
		$table = $wpdb->prefix. 'seomonster_410';
		return $wpdb->get_results( "SELECT gone_key, gone_regex FROM $table", OBJECT_K );	// indexed by gone_key
	}

	public static function add_link( $key, $is_404 = false ){	// just supply the link
		global $wpdb;
		$table = $wpdb->prefix. 'seomonster_410';
		// build regex
		$parts = preg_split( '/(\*)/', $key, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
		foreach( $parts as &$part ) if( '*' != $part )
			$part = preg_quote( $part, '|' );
		$parts = str_replace( '*', '.*', $parts );
		$regex = '|^' . implode( '', $parts ) . '$|i';

		// avoid duplicates - messy but MySQL doesn't allow url-length unique keys
		if( $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `$table` WHERE `gone_key` = %s", $key ) ) )
			return 0;

		$wpdb->insert( $table, array( 'gone_key' => $key, 'gone_regex' => $regex ) );
	}

	public function remove_link( $key ){
		global $wpdb;
		$table = $wpdb->prefix. 'seomonster_410';
		return $wpdb->query( $wpdb->prepare( "DELETE FROM $table WHERE gone_key = %s", array( $key ) ) );
	}

	function upgrade_check() {
		$options_version = get_option( 'seomonster_410_options_version', 0 );

		if( $options_version == self::db_version )	// nothing to do
			return;

		// last db change was in version 5
		if( $options_version < 5 )
			seomonster_410::install_table();

		if( $options_version < 3 ) {
			$old_links = get_option( 'seomonster_410_links_list', array() );
			$new_links = array();	 // just a simple array of links

			if( 0 == $options_version )	// links were stored just as links
				$new_links = array_map( 'rawurldecode', $old_links );
			elseif( 1 == $options_version ) // links were stored as array( link => regex ), We only need the link
				$new_links = array_map( 'rawurldecode', array_keys( $old_links ) );
			else // moved to using the database in db_version 3
				$new_links = array_keys( $old_links );

			foreach( $new_links as $link )
				seomonster_410::add_link( $link );

			delete_option( 'seomonster_410_links_list' );	// remove old option
		}

		update_option( 'seomonster_410_options_version', self::db_version );
	}


	public static function is_valid_url ( $link ) {
		// Determine whether WP will handle a request for this URL
		$wp_path = parse_url( home_url( '/' ), PHP_URL_PATH );
		$link_path = parse_url( $link, PHP_URL_PATH );

		if( strpos( $link_path, $wp_path ) !== 0 )
			return false;

		if( !get_option('permalink_structure') ) {
			$req = preg_replace( '|' . preg_quote( $wp_path, '|' ) . '/?|' , '', $link_path );
			if( strlen( $req ) && $req[0] != '?' )	// this is a pretty permalink, but pretty permalinks are disabled
				return false;
		}

		return true;
	}

	function note_inserted_post( $id ) {
		$post = get_post( $id );

		if( 'revision' == $post->post_type || 'draft' == $post->post_status )
			return;

		// Check our list of URLs against the new/updated post's permalink, and if they match, scratch it from our list
		$created_links = array();

		$created_links[] = rawurldecode( get_permalink( $id ) );
		$created_links[] = get_post_comments_feed_link( $id );	// back compat

		if( $this->permalinks )
			$created_links[] .= $created_links[0] . '*';

		foreach( $created_links as $link )
			seomonster_410::remove_link( $link );
	}
	
	function save_410_whitelist($page = false, $post = false, $custom = ''){
		$whitelist410 = array();
		
		if($page == true){
			$countposts = wp_count_posts('page')->publish;
			$chunks = $countposts/100;

			for($i=0;$i<$chunks;$i++){
				$posts = wp_remote_get( rest_url().'wp/v2/pages?per_page=100&offset='.($i*100).'&_fields=link' );
				$posts = json_decode(wp_remote_retrieve_body( $posts ));
				$array[$i] = $posts;
			}
			$pages = call_user_func_array('array_merge', $array);

			foreach($pages as $page){
				array_push($whitelist410, $page->link);
			}
		}
		if($post == true){
			$countposts = wp_count_posts('post')->publish;
			$chunks = $countposts/100;

			for($i=0;$i<$chunks;$i++){
				$posts = wp_remote_get( rest_url().'wp/v2/posts?per_page=100&offset='.($i*100).'&_fields=link' );
				$posts = json_decode(wp_remote_retrieve_body( $posts ));
				$array[$i] = $posts;
			}
			$posts = call_user_func_array('array_merge', $array);

			foreach($posts as $post){
				array_push($whitelist410, $post->link);
			}
		}
		if($custom != ''){
			foreach( preg_split( '/(\r?\n)+/', $custom, -1, PREG_SPLIT_NO_EMPTY ) as $link ) {
				array_push($whitelist410, $link);
			}
		}
		update_option('whitelist410',$whitelist410);
	}
	
	function check_for_410() {
		$links = seomonster_410::get_links();
		$req  = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$req = rawurldecode( $req );
		$_htm = substr($req, -4);
		if($_htm == "_htm"){ $req = str_replace('_htm','.htm',$req); }

		if( get_option('whitelist410')!='' && count(get_option('whitelist410'))>0 ){
			$whitelist410 = get_option('whitelist410');
			if( !in_array($req,$whitelist410) ){
				if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "googlebot"))
				{
				define( 'DONOTCACHEPAGE', true );	// WP Super Cache and W3 Total Cache recognise this
				status_header( 410 );
				do_action( 'seomonster_410_response' );	// you can use this to customise the response message
				if( ! locate_template( '410.php', true ) )
					echo 'Sorry, the page you requested has been permanently removed.';
					exit;
				}
			}

		}

		foreach( $links as $link ) {
			if( @preg_match( $link->gone_regex, $req ) ) {
				define( 'DONOTCACHEPAGE', true );	// WP Super Cache and W3 Total Cache recognise this
				status_header( 410 );
				do_action( 'seomonster_410_response' );	// you can use this to customise the response message

				if( ! locate_template( '410.php', true ) )
					echo 'Sorry, the page you requested has been permanently removed.';

				exit;
			}
		}
	}
}