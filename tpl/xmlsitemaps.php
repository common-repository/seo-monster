<?php
include 'css/style.php';
?>
<script>
jQuery(window).on('load',function(){
jQuery('#indexing').change(function(){

 if(jQuery(this).val()=="omega"){
  jQuery('.omega_form').addClass('display');
  jQuery('.speedlinks_form, .scaleserp_form').removeClass('display');
 }else if(jQuery(this).val()=="speedlinks"){
  jQuery('.speedlinks_form').addClass('display');
  jQuery('.omega_form, .scaleserp_form').removeClass('display');
 }else if(jQuery(this).val()=="ingoogle"){
  jQuery('.scaleserp_form').addClass('display');
  jQuery('.omega_form, .speedlinks_form').removeClass('display');
 }else jQuery('.omega_form, .speedlinks_form, .scaleserp_form').removeClass('display');

});

jQuery('#bulk_action').change(function(){
 if(jQuery(this).val()=="generateXml"){
  jQuery('.sitemap_form').addClass('display');
 }else jQuery('.sitemap_form').removeClass('display');
});

jQuery(".checkall").change(function() {
	var el = jQuery(this);
	el.closest("table").find("tbody input").prop("checked", el.is(":checked"));
});
});
</script>
<?php
		// Entries to delete
		if( isset( $_POST['delete_sitemap'] ) && !empty( $_POST['old_links_to_remove'] ) ) {			
			check_admin_referer( 'seo-monster-sitemaps' );
			foreach( $_POST['old_links_to_remove'] as $key ) {

				$filename = end(explode('/',$key));
				if (!unlink($_SERVER['DOCUMENT_ROOT']."/".$filename)) {
					echo ("<div id='global_message' class='fail show' style='max-height:100px;'><div class='message'><div class='sitemap_message'>$filename File is not found from the folder.</div></div></div>");
					$option = get_option('seo_monster_xmlsitemaps');
					$option = str_replace($key,'',$option);
					$option = str_replace('||','|',$option);
					update_option('seo_monster_xmlsitemaps',$option);
				}
				else {
					echo ("<div id='global_message' class='success show' style='max-height:100px;'><div class='message'><div class='sitemap_message'>$filename has been deleted</div></div></div>");
					$option = get_option('seo_monster_xmlsitemaps');
					$option = str_replace($key,'',$option);
					$option = str_replace('||','|',$option);
					update_option('seo_monster_xmlsitemaps',$option);
				}
			}			
		}



	?>
	<style>
	tr.invalid label {color: red}
	p.invalid {	background: #FFEBE8; border: 1px solid red; border-radius: 5px; padding: 5px 10px}
	.wp-410-table-wrap {max-width: 890px; max-height: 900px; overflow-y: scroll}
	label code {background:none;}
	.testurl {margin-right:20px;}
	#wp_gone_old_links {border:0;}
	#wp_gone_old_links tr:nth-child(odd) {background:#fafafa;}
		#wp_gone_old_links tr {display:table-row;}
		#wp_gone_old_links tr td:first-child {width:11px;}
	.check-column {padding-bottom: 0!important; padding-top: 10px!important;}
	#wp_gone_old_links tr.hide {display:none;}
	#wp_gone_old_links tr.hide.show {display:table-row;}
	.searchbar {max-width: 890px;display:flex;align-items: center;justify-content: space-between;}
		#go {padding:4px 10px 3px;margin-left:-4px;}
	</style>

<div class="table_wrap">
	<div class="global_spinner">
		<div class="loader"></div>
	</div>
	<div id="layoutSidenav">
		<div id="layoutSidenav_content">
			<main>
				<div class="container-fluid">
<h1 class="mt-4"><img src="<?php echo plugins_url( 'seo-monster/images/Monster-ICO.png' ); ?>" />Seo Monster XML Sitemaps</h1>
<div class="seomonster_header nonsticky">
<div class="card mb-4" style="max-width: 100%; padding: 0;">
	<div class="card-header">

		<div class="fitem fitem_settings">
		<!--a href="#" class="seo_settings"><span><i class="fas fa-cog"></i></span></a-->
		</div>
	</div>
</div>
</div>
					<div class="fitem">
						<div class="flexme between actions">
							<div class="fitem"></div>
							<!--span><label for="saveresult"><input type="checkbox" name="saveresult" id="saveresult" value="saveresult" /> Save Result &nbsp;</label></span-->
							<!--span><a class="btn btn-primary refresh-indexability" href="#">Refresh Indexability Status</a></span-->
							<div class="fitem">
								<div class="flexme bulk_actions end">
									<div style="position:relative;">
										<select id="indexing">
											<option value="default">Indexing Options</option>
											<option value="omega">Omega Indexer</option>
											<option value="speedlinks">SpeedLinks Indexer</option>
										</select>
										<div class="omega_form">
											<a href="#" onclick="jQuery(this).parent().removeClass('display');return false;" class="closeform">close</a>
											<form method="post" id="omega_form" onsubmit="omega_send();return false;">
											<input type="text" class="form-control" name="campaign_name" placeholder="Campaign Name" id="camp_name" required />
											<input type="text" class="form-control" name="drip_feed" placeholder="Drip Feed" id="drip_feed" required />
											<input type="submit" id="send_omega" class="button action" value="Send">
											</form>
										</div>
										<div class="speedlinks_form">
											<a href="#" onclick="jQuery(this).parent().removeClass('display');return false;" class="closeform">close</a>
											<form method="post" id="speedlinks_form" onsubmit="speedlinks_send();return false;">
											<input type="text" class="form-control" name="s_campaign_name" placeholder="Campaign Name" id="s_camp_name" required />
										<input type="submit" id="send_speedlinks" class="button action" value="Send">
											</form>
										</div>

									</div>
									<textarea id="copySelectedUrls"></textarea>
								</div><!--end bulk_actions-->
							</div>
						</div>
					</div>
				</div>



				<div class="card-body">
					<div class="table-responsive">


						<div class="seo-table page">
						<form action="" method="post">
						<?php
						if(!get_option('seo_monster_xmlsitemaps')){
							echo '<p>There are currently no 410 URLs in this list. You can add some manually below.</p>';
						}
						else {
							$option = get_option('seo_monster_xmlsitemaps');
							$urls = array_filter(explode('|',$option));
							?>
							<table class="table table-bordered" id="table410" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th><input id="select-all-410" class="checkall" type="checkbox" /> Sitemap Urls</th>
									<th>Open In New Tab</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$count = 0;
							foreach( $urls as $k ) {
								echo "<tr>
								<td><input type='checkbox' name='old_links_to_remove[]' id='xml$count' value='$k' class='selecturl' /><label for='xml$count'><code>$k</code> </label></td>
								<td><a href='$k' target='_blank'>Open Link</a></td>
								</tr>";
							$count++;
							}

							?>
							</tbody>
							</table>
							<?php
							wp_nonce_field( 'seo-monster-sitemaps' );
							echo '<p class="submit"><input class="button button-primary wider nofloat" type="submit" name="delete_sitemap" value="Delete selected entries" /></p>';
						}
						?>
						</form>



						</div>

					</div>
				</div>
			</main>
		</div>
	</div>
</div>
