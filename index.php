<?php
/**
 * Plugin Name: Seo Monster
 * Plugin URI: #
 * Description: Monitor Google Activity & Manage your links.
 * Version: 3.3.3
 * Author: Marc Moeller
 * Author URI: https://moellerseo.com/
 */

if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'Seo_Monster ' ) ) {
	define( 'SEMO_PLUGIN_PATH', plugin_dir_url( __FILE__ ) );
	class Seo_Monster {
		function register(){
			add_action( 'admin_enqueue_scripts', array($this, 'enqueue') );
			add_action( 'admin_menu', array( $this,'add_admin_pages' ) );
			add_action( 'wp_ajax_my_seo_posts', array( $this, 'get_postlist_rest' ) );
			add_action( 'wp_ajax_my_seo_category', array( $this, 'get_catlist' ) );
			add_action( 'wp_ajax_my_seo_tag', array( $this, 'get_taglist' ) );
			add_action( 'wp_ajax_my_seo_attachment', array( $this, 'get_attachlist' ) );
			add_action( 'wp_ajax_my_seo_author', array( $this, 'get_authlist' ) );
			add_action( 'wp_ajax_my_seo_omega', array( $this, 'post_omega_indexer' ) );
			add_action( 'wp_ajax_my_seo_scaleserp', array( $this, 'get_scaleserp' ) );
			add_action( 'wp_ajax_my_seo_speedlinks', array( $this, 'post_speedlinks_indexer' ) );
			add_action( 'wp_ajax_my_seo_crawlfreq', array( $this, 'get_crawl_frequency' ) );
			add_action( 'wp_ajax_my_seo_genxml', array( $this, 'generateXmlDocument' ) );
			add_action( 'wp_ajax_my_seo_add410', array( $this, 'post_410_pages' ) );
			add_action( 'wp_ajax_my_seo_color_switch', array( $this, 'update_color_scheme' ) );
			add_action( 'rest_customer_query', 'customer_override_per_page' );
			add_action( 'admin_head', array( $this, 'seo_monster_head' ) );
			require_once( __DIR__ ).'/inc/410_deletepages_class.php';
			$urlgone = new seomonster_410();
			if( is_admin() ){}
      else {
			add_action( 'template_redirect', array( $urlgone, 'check_for_410' ) 	);
			add_action('wp_head', array( $this, 'check_useragent' ) );
			}
		}

		function check_useragent() {
			if(empty($_SERVER['HTTP_USER_AGENT'])) {
				return array(
					'name' => 'unrecognized',
					'version' => 'unknown',
					'platform' => 'unrecognized',
					'userAgent' => ''
				);
			}

		   if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "googlebot"))
		   {

		   global $wp;
		   $current_url = home_url(add_query_arg(array($_GET), $wp->request));
		   $date = current_time( "Y/M/d h:i:sa", $gmt = 0 );
		   $url = trailingslashit($current_url);
		   $option = get_option('googlebot_last_visit');
		   $existing = $option[$url];
			 $lastDateFromDB = $existing;
		   if(is_array($existing)){
		   array_push($existing, $date);
		   $array[$url] = array_filter($existing);
		   }else {
				 $array[$url] = [$existing,$date];
		   }

		   if(!get_option('googlebot_last_visit')){
		    update_option( 'googlebot_last_visit', $array );
		   }else {

		    $array = array_merge($option,$array);
				//print_r($array);

		    //update_option( 'googlebot_last_visit', $array );
				$date1 = substr(end($lastDateFromDB), 0, -2);
				$date1 = str_replace('/','-',$date1);

				$explode = explode(' ',$date1);

				$date1_time = date("H:i:s", strtotime($explode[1]." ".substr(strtoupper(end($existing)), -2)));

				$dateFromdb = $explode[0]." ".$date1_time;

				$dateTimestamp1 = strtotime($dateFromdb);

				$CurrentDateLessMinute = get_date_from_gmt(date('Y-m-d h:i:s', strtotime('-1 minutes')));

				$dateTimestamp2 = strtotime($CurrentDateLessMinute);


				if($dateTimestamp1 < $dateTimestamp2 || $lastDateFromDB=="") {
					update_option( 'googlebot_last_visit', $array );
				}else {
				}

		   }

		   }
		}
		function seo_monster_head() {
			if(isset($_POST['omega_api'])){
			  update_option( 'seomonster_api_omega', sanitize_text_field($_POST['omega_api']));
			}
			if(isset($_POST['speedlinks_api'])){
			  update_option( 'seomonster_api_speedlinks', sanitize_text_field($_POST['speedlinks_api']));
			}
			if(isset($_POST['scaleserp_api'])){
			  update_option( 'seomonster_api_scaleserp', sanitize_text_field($_POST['scaleserp_api']));
			}
			if(isset($_POST['theseomonster_api'])){
			  update_option( 'theseomonster_api', sanitize_text_field($_POST['theseomonster_api']));
			}
		}
		function add_admin_pages(){
			add_menu_page('Marc Moeller Seo Monster', 'Seo Monster', 'manage_options', 'seo-monster', array($this, 'seo_monster_admin_page') ,plugins_url( 'seo-monster/images/icon2.png' ), 6);
			add_submenu_page('seo-monster', 'Integration | Seo Monster', 'Integration', 'manage_options', 'seo-monster-integration', array($this, 'my_seo_bots_admin_page_2' ) );
			add_submenu_page('seo-monster', '410 Pages | Seo Monster', '410 Tool', 'manage_options', 'seo-monster-410', array($this, 'my_seo_bots_admin_page_3' ) );
			add_submenu_page('seo-monster', 'XML Sitemaps | Seo Monster', 'XML Sitemaps', 'manage_options', 'seo-monster-sitemaps', array($this, 'my_seo_bots_admin_page_4' ) );
		}

		function seo_monster_admin_page() {
			require_once( __DIR__ ).'/inc/class_seo_monster_ui.php';
			include 'tpl/disp.php';
		}
		function my_seo_bots_admin_page_2() {
			require_once( __DIR__ ).'/inc/class_seo_monster_ui.php';
			include 'tpl/integration.php';
		}
		function my_seo_bots_admin_page_3() {
			include 'tpl/410pages.php';
		}
		function my_seo_bots_admin_page_4() {
			include 'tpl/xmlsitemaps.php';
		}
		function enqueue(){
			if(!empty($_GET['page'])){
            $explode = explode('-',$_GET['page']);
			if(count($explode)>3){$path = $explode[0].'-'.$explode[1].'-'.$explode[2];}else $path = $_GET['page'];
			if($path == "seo-monster" || $path == "seo-monster-integration" || $path == "seo-monster-410" || $path == "seo-monster-sitemaps"){
			wp_enqueue_style( 'seo-bot-css', plugins_url( 'tpl/css/seo_bot_style.css', __FILE__ ) );
			wp_enqueue_style( 'datatable-css', plugins_url( 'tpl/css/datatables.min.css', __FILE__ ) );
			wp_enqueue_style( 'seo-bot-font', 'https://fonts.googleapis.com/css2?family=Oswald&display=swap' );
			wp_enqueue_script('data-table', plugins_url( 'js/datatables.min.js', __FILE__ ) );
			wp_enqueue_script('seo-ajax', plugins_url( 'js/myscript.js', __FILE__ ) );
			wp_localize_script('seo-ajax', 'object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script('seo-ajax');
			wp_enqueue_script('font-awesome-js', plugins_url( 'js/all.min.js', __FILE__ ) );
			wp_enqueue_script('validate-js', plugins_url( 'js/jquery.validate.js', __FILE__ ) );
			wp_enqueue_script('bootstrap-bundle', plugins_url( 'js/bootstrap.bundle.min.js', __FILE__ ) );
			}
			if($path == "seo-monster-integration"){
			wp_enqueue_style( 'integration-css', plugins_url( 'tpl/css/integration.css', __FILE__ ) );
			}
			}
		}

		function myplugin_activate(){
			require_once( __DIR__ ).'/inc/seo_monster_activate.php';
			Seo_Monster_activate::activate();
		}
		function myplugin_deactivate(){
			require_once( __DIR__ ).'/inc/seo_monster_deactivate.php';
			Seo_Monster_deactivate::deactivate();
		}


		function customer_override_per_page( $params ) {
			if ( isset( $params ) AND isset( $params[ 'posts_per_page' ] ) ) {
				$params[ 'posts_per_page' ] = PHP_INT_MAX;
			}

			return $params;
		}

		function get_date_format($datestring){
                        if(!isset($datestring)){return '';}
			$format = 'Y/M/d h:i:sa';
			return date($format, $datestring);
		}
		function get_scaleserp_action(){
			$monster = strtolower(explode('|',get_option('monsterstatus'))[0]);
			return '<input type="button" class="button action" value="Activate Seo Monster" onclick="location.href = \'admin.php?page=seo-monster-integration\';">';
		}


		function check_last_visit($url,$freq=false,$print=false){
		$option = get_option('googlebot_last_visit');
		$date = array_filter($option[$url]);

	  if(is_array($date) && $date[0]==''){
		$count = count($date);
		$freq = '<span>'.$count.'</span>';
		//$freq = $count;
	  }else if($date!=""){
			$freq = '';
		}

		if(is_array($date)){
		$date = end($date);
		}
		return [$date,$freq];
		}

		function get_postlist_rest(){
			$type = $_POST['type'];
			$countposts = wp_count_posts($type)->publish;
			$chunks = $countposts/100;

			$monster = strtolower(explode('|',get_option('monsterstatus'))[0]);
			if($monster!='active'){
				$status = "License Required";
				$class = "theseomonster_license";
			}else $status = '<div class="loader"></div>';

			for($i=0;$i<$chunks;$i++){
				$posts = wp_remote_get( rest_url().'wp/v2/'.$type.'s?per_page=100&offset='.($i*100).'&_fields=link,modified', array( 'body' => '', 'timeout'=> '120', 'httpversion' => '1.0','blocking'=> true, ) );
				$posts = json_decode(wp_remote_retrieve_body( $posts ));
				//$results = $wpdb->get_results( "SELECT guid FROM {$wpdb->prefix}post WHERE post_type = page", OBJECT );
				$array[$i] = $posts;
			}
			$merged = call_user_func_array('array_merge', $array);

			$count=0;
			foreach($merged as $arr){
				$date = date_create($arr->modified);
				$post_modified = date_format($date,"Y/M/d h:i:sa");

				ob_start();

				if($_POST['saved']=="true"){
					if($saved[$arr] != ''){
					$status = $saved[$arr];
					$explode = explode(' ',$status);
					$class = ($explode[0]=='True') ? 'good' : 'bad';
					$checked = 'checked';
				}else {
						$checked = '';
						$status = '<a href="#" class="continue '.$type.'d">Continue</a>';
						$class = $continue = 'continue';

					}
				}
				/*Get Last Visit*/
				$lastvisit = Seo_Monster::check_last_visit($arr->link);
				$freq = $lastvisit[0];
				$lvisit = $lastvisit[1];
				$d1 = strtotime( str_replace('/','-',$post_modified) );
				$d2 = strtotime( str_replace('/','-',$freq) );							  
				?>
				<tr class="<?php echo $class; ?>" >
				 <td class="checkurl <?php echo $type." ".$type.$count; ?>"><input type="checkbox" name="<?php echo $type.$count; ?>" id="<?php echo $type.$count; ?>" class="selecturl" value="<?php echo $arr->link; ?>" /><a href="<?php echo $arr->link; ?>"><?php echo $arr->link; ?></a></td>
				 <td class="lvisitfreq<?php echo $count; ?>"><?php echo $lvisit; ?></td>
				 <td><?php echo $post_modified; ?> </td>
				 <td class="lvisit<?php echo $count; ?> <?php if($d2>$d1){echo 'recently_visited';}?>"><?php echo $freq; ?></td>
				 <td><a href="#"  class="check_scaleserp <?php echo $type.$count; ?>">Click To Check</a></td>
				</tr>
				<?php
				$count++;
			}

			$data = ob_get_contents();

			ob_end_clean();
			echo $data;
		}
		function get_postlist(){
			if(isset($_POST['type'])){$type = sanitize_text_field($_POST['type']);}
			$custom = sanitize_text_field($_POST['custom']);
			$num = 0;


			if($custom=='false'){
			$posts = wp_remote_get( rest_url().'wp/v2/'.$type.'s?per_page=100', array( 'body' => '', 'timeout'=> '120', 'httpversion' => '1.0','blocking'=> true, ) );
			$posts = json_decode(wp_remote_retrieve_body( $posts ));
			}else{
			$posts = get_posts(array(
				'post_type' => $type,
				'posts_per_page'  => -1
			));
			}

			foreach($posts as $p){
			$urls[$num] = ($custom=='false') ? $p->link : get_permalink($p);
      if($custom=='false'){
			$date = date_create($p->modified);
      }else{ $date = date_create($p->post_modified); }
			$post_modified[$num] = date_format($date,"Y/M/d h:i:sa");
			$num++;
			}

			$countposts = wp_count_posts($type)->publish;
			$chunk = 100;
			$divide = $countposts/$chunk;
			if($divide>1 && ($type=='post' || $type=='page')){
			$whole = floor($divide);
			$decimal = ($divide - $whole)*100;
			for($i=0;$i<$whole;$i++){
				$offsetposts = wp_remote_get( rest_url().'wp/v2/'.$type.'s?per_page=100&offset='.$chunk, array( 'body' => '', 'timeout'=> '120', 'httpversion' => '1.0','blocking'=> true, ) );
				$offsetposts = json_decode(wp_remote_retrieve_body( $offsetposts ));
				foreach($offsetposts as $p){
				$urls[$num+i] = $p->link;
				$date = date_create($p->modified);
				$post_modified[$num+i] = date_format($date,"Y/M/d h:i:sa");
				$num++;
				}
				$chunk += 100;
			}
			}
			if($_POST['saved']=="true"){
				$saved = get_option('seo_monster_result');

				$savedResults = get_option('seo_monster_result');
				$savedResults = explode('|',$savedResults[$type]);
				foreach(array_filter($savedResults) as $rec){
					$explode = explode('=>',$rec);
					$arr[str_replace(' ','',$explode[0])] = $explode[1];
				}
			}
			$indexable_count=0;

			foreach($urls as $url){
			ob_start();
			$continue = '';

      $modified = $post_modified[$indexable_count];
			$lastvisit = Seo_Monster::check_last_visit($url);
			?>
			 <tr class="<?php echo $class; ?>" >
				<td class="checkurl <?php echo $type." ".$type.$indexable_count." ".$checked; ?>"><input type="checkbox" name="<?php echo $type.$indexable_count; ?>" id="<?php echo $type.$indexable_count; ?>" class="selecturl" value="<?php echo $url; ?>" > <a href="<?php echo $url; ?>"><?php echo $url; ?></a></td>
				<td><?php echo Seo_Monster::check_last_visit($url,true); ?></td>
				<td><?php echo $modified; ?></td>
				<td class="<?php if($lastvisit>$modified){echo 'recently_visited';}?>"><?php echo $lastvisit; ?></td>
				<td><a href="#" class="check_scaleserp <?php echo $type.$indexable_count; ?>">Click To Check</a></td>
			 </tr>
			<?php
			if($_POST['saved']=="true"){

			}
			$indexable_count++;
			}
			$data = ob_get_contents();
			ob_end_clean();
			echo $data;
		}

		function get_catlist(){
			$pages = wp_remote_get( rest_url().'wp/v2/categories', array( 'body' => '', 'timeout'=> '120', 'httpversion' => '1.0','blocking'=> true, ) );
			$obj = json_decode(wp_remote_retrieve_body( $pages ));

			$class = '';
			$num = 0;
			foreach($obj as $p){
			$urls[$num] = $p->link;
			$num++;
			}

			$indexable_count=0;


			foreach($urls as $cat){
			ob_start();
			$continue = '';
			/*Get Last Visit*/
			$lastvisit = Seo_Monster::check_last_visit($cat);
			$freq = $lastvisit[1];
			$lvisit = $lastvisit[0];
			?>
			<tr class="<?php echo $class; ?>" >
				<td class="checkurl category category<?php echo $indexable_count; ?>"><input type="checkbox" name="category<?php echo $indexable_count; ?>" id="category<?php echo $indexable_count; ?>" class="selecturl" value="<?php echo $cat; ?>" > <a href="<?php echo $cat; ?>"><?php echo $cat; ?></a></td>
				<td><?php echo $freq; ?></td>
				<td></td>
				<td><?php echo $lvisit; ?></td>
				<td><a href="#" class="check_scaleserp category<?php echo $indexable_count; ?>" >Click To Check</a></td>
			 </tr>
			<?php
			$indexable_count++;
			}
			$data = ob_get_contents();
			ob_end_clean();
			echo $data;
		}

		function get_taglist(){
			$indexable_count=0;
			$pages = wp_remote_get( rest_url().'wp/v2/tags', array( 'body' => '', 'timeout'=> '120', 'httpversion' => '1.0','blocking'=> true, ) );
			$obj = json_decode(wp_remote_retrieve_body( $pages ));
			$num = 0;
			foreach($obj as $p){
			$urls[$num] = $p->link;
			$num++;
			}
			foreach($urls as $link){
			ob_start();
			$continue = '';
			/*Get Last Visit*/
			$lastvisit = Seo_Monster::check_last_visit($link);
			$freq = $lastvisit[1];
			$lvisit = $lastvisit[0];
			?>
			 <tr class="<?php echo $class; ?>" >
				<td class="checkurl tag tag<?php echo $indexable_count; ?>"><input type="checkbox" name="tag<?php echo $indexable_count; ?>" id="tag<?php echo $indexable_count; ?>" class="selecturl" value="<?php echo $link; ?>" > <a href="<?php echo $link; ?>"><?php echo $link; ?></a></td>
				<td><?php echo $freq; ?></td>
				<td></td>
				<td><?php echo $lvisit; ?></td>
				<td><a href="#" class="check_scaleserp tag<?php echo $indexable_count; ?>" >Click To Check</a></td>
			 </tr>
			<?php
				$indexable_count++;
			}
			$data = ob_get_contents();
			ob_end_clean();
			echo $data;
		}
		function get_attachlist($total=0){
			if(isset($_POST['type'])){$type = sanitize_text_field($_POST['type'] );}
			$pages = wp_remote_get( rest_url().'wp/v2/media', array( 'body' => '', 'timeout'=> '120', 'httpversion' => '1.0','blocking'=> true, ) );
			$obj = json_decode(wp_remote_retrieve_body( $pages ));
			$indexable_count=0;
			$num = 0;
			foreach($obj as $p){
			$urls[$num] = $p->link;
			$post_modified[$num] = $p->modified;
			$num++;
			}

			if(isset($_POST['saved'])){
			if($_POST['saved']=="true"){
				$savedResults = get_option('seo_monster_result')[$type];
				$savedResults = explode('|',$savedResults);
				foreach($savedResults as $rec){
					$explode = explode('=',$rec);
					$arr[str_replace(' ','',$explode[0])] = $explode[1];
				}
			}
			}

			if($total==1){return count($urls);}
			   foreach($urls as $link){
			   ob_start();
					$continue = '';
				$modified = $post_modified[$indexable_count];

					/*Get Last Visit*/
					$lastvisit = Seo_Monster::check_last_visit($link);
					$freq = $lastvisit[1];
					$lvisit = $lastvisit[0];
					$post_mod = Seo_Monster::get_date_format(strtotime($modified));
					$d1 = strtotime( str_replace('/','-',$post_mod) );
					$d2 = strtotime( str_replace('/','-',$lvisit) );							 
			   ?>
			 <tr class="<?php echo $class; ?>" >
				<td class="checkurl sm_attachment attachment<?php echo $indexable_count; ?>"><input type="checkbox" name="attachment<?php echo $indexable_count; ?>" id="attachment<?php echo $indexable_count; ?>" class="selecturl" value="<?php echo $link; ?>" > <a href="<?php echo $link; ?>"><?php echo $link; ?></a></td>
				<td><?php echo $freq; ?></td>
				<td><?php echo $post_mod; ?></td>
				<td class="<?php if($d2>$d1){echo 'recently_visited';}?>"><?php echo $lvisit; ?></td>
				<td><a href="#" class="check_scaleserp attachment<?php echo $indexable_count; ?>" >Click To Check</a></td>
			 </tr>
				<?php
				$indexable_count++;
			   }

			$data = ob_get_contents();
			ob_end_clean();
			echo $data;
		}

		function get_authlist($total=0){
			if(isset($_POST['type'])){$type = sanitize_text_field($_POST['type']);}

			$indexable_count=0;
			$pages = wp_remote_get( rest_url().'wp/v2/users', array( 'body' => '', 'timeout'=> '120', 'httpversion' => '1.0','blocking'=> true, ) );
			$obj = json_decode(wp_remote_retrieve_body( $pages ));

			$num = 0;
			foreach($obj as $p){
			$urls[$num] = $p->link;
			$num++;
			}

			if(isset($_POST['saved'])){
			if($_POST['saved']=="true"){
				$savedResults = get_option('seo_monster_result')[$type];
				$savedResults = explode('|',$savedResults);
				foreach($savedResults as $rec){
					$explode = explode('=',$rec);
					$arr[str_replace(' ','',$explode[0])] = $explode[1];
				}
			}
			}

			if($total==1){return count($urls);}
			foreach ($urls as $link) {
					ob_start();
					$continue = '';
					/*Get Last Visit*/
					$lastvisit = Seo_Monster::check_last_visit($link);
					$freq = $lastvisit[1];
					$lvisit = $lastvisit[0];
					?>
			<tr class="<?php echo $class; ?>" >
				<td class="checkurl author author<?php echo $indexable_count; ?>"><input type="checkbox" name="author<?php echo $indexable_count; ?>" id="author<?php echo $indexable_count; ?>" class="selecturl" value="<?php echo $link; ?>" > <a href="<?php echo $link; ?>"><?php echo $link; ?></a></td>
				<td><?php echo $freq; ?></td>
				<td></td>
				<td><?php echo $lvisit; ?></td>
				<td><a href="#" class="check_scaleserp author<?php echo $indexable_count; ?>" >Click To Check</a></td>
			 </tr>

			<?php
			if($_POST['saved']=="true"){

			}

			$indexable_count++;
			}
			$data = ob_get_contents();
			ob_end_clean();
			echo $data;
		}

		function httpPost($url, $data){
		$args = array(
		    'body'        => $data,
		    'timeout'     => '5',
		    'httpversion' => '1.0',
		    'blocking'    => true,
		);
			$response = wp_remote_post( $url, $args );


			return json_encode($response);
		}

		function post_omega_indexer(){
		$cname = sanitize_text_field($_POST['campaign_name']);
		$dripfeed = sanitize_text_field($_POST['drip_feed']);
		$urls = sanitize_text_field($_POST['urls']);
		$apikey = get_option('seomonster_api_omega');


		$response = Seo_Monster::httpPost("https://www.omegaindexer.com/amember/dashboard/api",
			array("apikey"=>$apikey,"campaignname"=>$cname,"dripfeed"=>$dripfeed,"urls"=>$urls)
			);
		echo $response;
		}

		function post_speedlinks_indexer(){
		$cname = sanitize_text_field($_POST['campaign_name']);
		$dripfeed = sanitize_text_field($_POST['drip_feed']);
		$urls = sanitize_text_field($_POST['urls']);

		$apikey = get_option('seomonster_api_speedlinks');
		$campaign = sanitize_text_field($_POST['campaign_name']);
		$qstring = 'apikey=' . $apikey . '&cmd=submit&campaign=' . $campaign . '&urls=' . urlencode($urls).'&reporturl=1';

		$args = array(
		    'body'        => $qstring,
		    'timeout'     => '5',
		    'httpversion' => '1.0',
		    'blocking'    => true,
		);
		$response = wp_remote_post( 'http://speed-links.net/api.php', $args );
		echo json_encode($response);
		}

		function get_scaleserp(){
			$apikey = get_option('seomonster_api_scaleserp');

				$queryString = [
				  'api_key' => $apikey,
				  'q' => 'site:'.str_replace(' ','',sanitize_text_field($_POST['url'])),
				  'gl' => 'au',
				  'hl' => 'en',
				  'google_domain' => 'google.com.au'
				];

	      $args = array(
			    'body'        => $queryString,
			    'timeout'     => '120',
			    'httpversion' => '1.0',
			    'blocking'    => true,
			);
	$response = wp_remote_get( 'https://api.scaleserp.com/search', $args );
	$responseBody = wp_remote_retrieve_body( $response );
	$arr = json_decode( $responseBody );

			//$arr = json_decode($api_result);

			$success = $arr->request_info->success;
			$message = $arr->request_info->message;

			$cred = $arr->request_info->credits_used;
			$rem  = $arr->request_info->credits_remaining;

			if($arr->organic_results){
			 $val = 'true';
			 }else $val = 'false';
			$return = ['success'=>$success,'credits'=>$cred,'remaining'=>$rem,'result'=>$val,'message'=>$message];
			echo json_encode($return);
		}
		function generateXmlDocument(){
		$list = sanitize_text_field($_POST['urlstring']);
		$name = sanitize_text_field($_POST['filename']);
		$date = sanitize_text_field($_POST['datemodified']);
		if(substr($name, -4)!=".xml"){
			$name = $name.".xml";
		}
		$list = explode(',',$list);
		$date = explode(',',$date);
		$now = current_time( "c" );
		$xmlString = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$num = 0;
		foreach($list as $url){
		$itemdate = date_create(str_replace('/','-',$date[$num]),timezone_open('+'.get_option('gmt_offset')));
		$mod = date_format($itemdate, 'c');
		if($date[$num] == ""){$mod = $now;}
		$xmlString .= '<url><loc>'.$url.'</loc><lastmod>'.$mod.'</lastmod></url>';
		$num++;
		}
		$xmlString .= '</urlset>';
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		$dom->loadXML($xmlString);
		$return = get_home_url().'/'.$name;
		if(get_option('seo_monster_xmlsitemaps')){
		$option = get_option('seo_monster_xmlsitemaps')."|".$return;
		update_option('seo_monster_xmlsitemaps',$option);
		}else update_option('seo_monster_xmlsitemaps',$return);
		$dom->save($_SERVER['DOCUMENT_ROOT'].'/'.$name);
		echo $return;
		}

		static function post_410_pages(){
			require_once( __DIR__ ).'/inc/410_deletepages_class.php';
			$urlgone = new seomonster_410();
			$failed_to_add = array();
				try {
					if( !empty( $_POST['links_to_add'] ) ) {
						foreach( preg_split( '/,/', sanitize_text_field($_POST['links_to_add']), -1, PREG_SPLIT_NO_EMPTY ) as $link ) {
							$link = stripslashes( $link );
							if( $urlgone->is_valid_url( $link ) ) {
								$urlgone->add_link( $link );
							}
							else {}
						}
					}


				}catch(Exception $e){
				echo $e;
				}
		}
		function update_color_scheme(){
			$color = sanitize_text_field($_POST['color']);
			update_option('seo_monster_color',$color);
		}
	}
	$seomonster = new Seo_Monster();
	$seomonster->register();
	register_activation_hook(__FILE__, array($seomonster, 'myplugin_activate'));
	register_deactivation_hook(__FILE__, array($seomonster, 'myplugin_deactivate'));
}

if( is_admin() ) {
add_action( 'publish_post', 'save_410_whitelist', 10, 2 );
add_action( 'publish_page', 'save_410_whitelist', 10, 2 );

function save_410_whitelist( $ID, $post ){
	$permalink = get_permalink( $ID );
	if(get_option('whitelist410')!=''){
		$whitelist410 = get_option('whitelist410');
		if(count($whitelist410)>0){
			if($post->post_type=="page"){
				if(get_option('410whitelistpages')==1){
					array_push($whitelist410,$permalink);
					update_option('whitelist410',$whitelist410);
				}
			}else if($post->post_type=="post"){
				if(get_option('410whitelistposts')==1){
						array_push($whitelist410,$permalink);
						update_option('whitelist410',$whitelist410);
				}
			}
		}
	}
}
}