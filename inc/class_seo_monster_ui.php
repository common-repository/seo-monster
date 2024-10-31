<?php
/**
 * Package Seo_monster
 */

if( ! defined( 'ABSPATH' ) ) exit;

class seo_monster_wp_ui
{
	public static function get_custom_post_types(){
		// Get All Post Types as List

		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);
		ob_start();


		foreach ( get_post_types( $args, 'names' ) as $post_type ) {
			echo '<label for="f'.$post_type.'"><input type="checkbox" name="f'.$post_type.'" id="select'.$post_type.'" value="select'.$post_type.'" class="custom" /> '.$post_type.'</label>';
		}
		$data = ob_get_contents();
		ob_end_clean();
		return $data;
	}
	public static function display_table_headers($post_type){
		$postcount = wp_count_posts($post_type)->publish;
		echo '<input type="hidden" id="'.$post_type.'total" value="'.$postcount.'" />';
		?>
		<input type="hidden" id="pagetotal" value="'.$postcount.'" />
		<div class="seo-table custom-type <?php echo strtolower($post_type); ?> hidden">
		<div class="runaction"><input type="submit" class="quitsave page" value="Quit & Save" /></div>
			<textarea id="all<?php echo strtolower($post_type); ?>listapi"></textarea>
			<div class="local_spinner"><div class="loader"></div></div>
			<input type="checkbox" name="check<?php echo strtolower($post_type); ?>" id="check<?php echo strtolower($post_type); ?>" class="checkall" >
			<table class="table table-bordered <?php echo strtolower($post_type); ?>list" id="<?php echo strtolower($post_type); ?>list" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th><span class="th sorting_asc"><?php echo $post_type; ?> Urls</span></th>
						<th style="width:165px;">Crawl Frequency</th>
						<th style="width:160px;">Last Modified</th>
						<th style="width:165px;">Last Google Crawl</th>
						<th style="width:140px;" class="ingoogle">Is In Google <i class="far fa-question-circle"></i>
						<div class="scaleserp">This feature is per request to protect Your Scaleserp credits. Can be change in settings (Top-Right).</div>
						</th>
					</tr>
				</thead>
				<tbody>			
				</tbody>
			</table>
		</div>
		<?php
	}
	public static function display_pages_other_post_types(){
		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);
		$post_types = get_post_types( $args, 'names' );
		ob_start();
		foreach ( $post_types as $post_type ) {
		seo_monster_wp_ui::display_table_headers($post_type);
		}
		$data = ob_get_contents();
		ob_end_clean();
		return $data;
	}
	function check_integration($display = false,$api = false){

		if($display == false){
		if(isset($_POST['omega_api'])){
		  update_option( 'seomonster_api_omega', sanitize_key($_POST['omega_api']));
		}
		if(isset($_POST['speedlinks_api'])){
		  update_option( 'seomonster_api_speedlinks', sanitize_key($_POST['speedlinks_api']));
		}
		if(isset($_POST['scaleserp_api'])){
		  update_option( 'seomonster_api_scaleserp', sanitize_key($_POST['scaleserp_api']));
		}
		if(isset($_POST['theseomonster_api'])){
		  update_option( 'theseomonster_api', sanitize_key($_POST['theseomonster_api']));
		}
		}else {
		  if($api == 'omega'){
		  if(get_option('seomonster_api_omega')){
		  echo get_option('seomonster_api_omega');
		  }
		  }else if($api == 'scaleserp'){
		  echo get_option('seomonster_api_scaleserp');
		  }else if($api == 'speedlinks'){
		  echo get_option('seomonster_api_speedlinks');
		  }else if($api == 'themonster'){
		  echo get_option('theseomonster_api');
		  }


		}
	}


}
