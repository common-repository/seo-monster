<?php
$urlgone = new seomonster_410();
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
	el.next().find("tbody input").prop("checked", el.is(":checked"));
});


});
</script>
<?php

		$links = $urlgone->get_links();
		$links_to_add = array();

		// Entries to delete
		if( isset( $_POST['delete-from-410-list'] ) && !empty( $_POST['old_links_to_remove'] ) ) {
			check_admin_referer( 'seo-monster-410' );
			foreach( $_POST['old_links_to_remove'] as $key ) {
				$key = stripslashes( $key );
				if( isset( $links[$key] ) ) {
					$urlgone->remove_link( $key );
					unset( $links[$key] );
				}
			}
		}

		else if ( isset( $_POST['add-to-410-list'] ) ) {
			check_admin_referer( 'seo-monster-410' );
			$failed_to_add = array();
			if( !empty( $_POST['links_to_add'] ) ) {
				foreach( preg_split( '/(\r?\n)+/', $_POST['links_to_add'], -1, PREG_SPLIT_NO_EMPTY ) as $link ) {
					$link = stripslashes( $link );
					if( $urlgone->is_valid_url( $link ) ) {
			if( is_ssl() ) { $link = str_replace('http:','https:',$link); }
            if(isset($_POST['removewww'])){$link = str_replace('www.','',$link);}
            if(isset($_POST['removeindexphp'])){$link = str_replace('index.php','',$link);}
						$urlgone->add_link( $link );
					}
					else {
						$failed_to_add[] = '<code>' . htmlspecialchars( $link ) . '</code>';
					}
				}
			}



			// Update lists
			$links = $urlgone->get_links();

			if( $failed_to_add )
				echo '<div class="error"><p>The following entries could not be recognised as URLs that your WordPress site handles, and were not added to the list. This can be because the domain name and path does not match that of your WordPress site, or because pretty permalinks are disabled.</p><p>- ' . implode( '<br> - ', $failed_to_add ) .'</p></div>';
		}
		else if ( isset( $_POST['410-white-list'] ) ) {
      $savepages = false;
      $saveposts = false;
      $fromform = true;
		  if( isset( $_POST['excludepages'] ) ){
      $savepages = true;
			update_option('410whitelistpages','1');
		  }else update_option('410whitelistpages','0');

		  if( isset( $_POST['excludeposts'] ) ){
        $saveposts = true;
        update_option('410whitelistposts','1');
		  }else update_option('410whitelistposts','0');

      if( isset( $_POST['customexclude'] ) ){
        $customexclude = $_POST['customexclude'];
        update_option('410customlist',$_POST['customexclude']);
		  }
      $urlgone->save_410_whitelist($savepages, $saveposts, $customexclude, $fromform);
		}

			if( !empty( $_POST['delete-from-410-list'] ) )
			echo "<div id='global_message' class='success' style='max-height:100px;'><div class='message'>URLs Removed From 410 List.</div></div>";

			if( !empty( $_POST['links_to_add'] ))
			echo "<div id='global_message' class='success' style='max-height:100px;'><div class='message'>URL(s) Added.</div></div>";

		ksort( $links );
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
					<h1 class="mt-4"><img src="<?php echo plugins_url( 'seo-monster/images/Monster-ICO.png' ); ?>" />Seo Monster 410 Dashboard</h1>
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
						<div class="flexme between actions end">
							<div class="fitem"></div>
							<!--span><label for="saveresult"><input type="checkbox" name="saveresult" id="saveresult" value="saveresult" /> Save Result &nbsp;</label></span-->
							<!--span><a class="btn btn-primary refresh-indexability" href="#">Refresh Indexability Status</a></span-->
							<div class="fitem">
								<div class="flexme bulk_actions end">
									<div style="margin-right:10px;position:relative;">
										<label class="white">Bulk Actions:</label>
										<input type="button" class="button action bulkaction" value="enable410" />
										<input type="button" class="button action bulkaction"  value="copySelected" />
										<input type="button" class="button action bulkaction"  value="generateXml" />
										<div class="sitemap_form">
											<a href="#" onclick="jQuery(this).parent().removeClass('display');return false;" class="closeform">close</a>
											<form method="post" id="sitemap_form" onsubmit="xml_create();return false;">
											<input type="text" class="form-control" name="sitemap_name" placeholder="File_Name.xml" id="sitemap_name" required />
											<input class="wider" type="submit" value="Create" />
											</form>
										</div>
									</div>
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


				<div id="global_message"><div class="message"></div></div>
				<div class="card-body">
					<div class="table-responsive">

						<!-------PAGE LIST-------->
						<div class="seo-table page">
						<form action="" method="post">
						<?php
						if( empty( $links ) ) {
							echo '<p>There are currently no 410 URLs in this list. You can add some manually below.</p>';
						}
						else {
							echo '<p>The following URLs (or masks) will receive a 410 response. If you create or update an article whose URL matches one of those below, that URL will automatically be removed from the list.</p>';

						}


							?>
							<table class="table table-bordered" id="table410" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th><input id="select-all-410" type="checkbox" /> <span class="th sorting_asc">410 Urls</span></th>
									<th style="width:105px;">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php
							if( empty( $links ) ) { ?>
							<tr>
								<td>&nbsp;</td>
								<td style="width:105px;">&nbsp;</td>
							</tr>
							<?php }
							foreach( array_keys( $links ) as $k ) {
								$k_attr = esc_attr( $k );
								$k = htmlspecialchars( $k );
								$k = str_replace(get_site_url(),'',$k);
								echo "<tr>
								<td class='checkurl'><input type='checkbox' name='old_links_to_remove[]' id='wp-410-$k' value='$k_attr' class='selecturl' /><label for='wp-410-$k_attr'><code>$k</code> </label></td>
								<td><label class='testurl'><a href='$k_attr' target='_blank'>Test URL</a></label></td>
								</tr>";
							}
							?>
							</tbody>
							</table>
							<?php
							wp_nonce_field( 'seo-monster-410' );
							echo '<p class="submit"><input class="button button-primary wider nofloat" type="submit" name="delete-from-410-list" value="Delete selected entries" /></p>';

						?>
						</form>

						</div>

					</div>
				</div>
			</main>
		</div>
	</div>
</div>



	<div class="wrap">

	<?php if( WP_CACHE ) :?>
		<div class="updated">
		<p style="color:#000!important;"><strong>Warning:</strong> It seems that a caching/performance plugin is active on this site. This plugin has only been tested with the following caching plugins:</p>
		<ul style="list-style: disc; margin-left: 2em">
		<li>W3 Total Cache</li>
		<li> WP Super Cache</li>
		</ul>
		<p  style="color:#000!important;"><strong>Other caching plugins may cache responses to requests for pages that have been removed</strong>, in which case this plugin will not be able to intercept the requests and issue a 410 response header.</p>
		</div>
	<?php endif; ?>
	<h3>Manually add URLs</h3>
	<form action="" method="post">
	<p>You can manually add items to the list by entering them below. Please enter one <strong>fully qualified</strong> URL per line.</p>
	<p>Use <code>*</code> as a wildcard character. So <code>http://www.domain.com/*/media/</code> will match all URLs ending in <code>/media/</code>.</p>
	<textarea name="links_to_add" rows="8" cols="80"></textarea>
	<?php wp_nonce_field( 'seo-monster-410' ); ?>
	<div><p><label>Auto Remove WWW From Submitted URLs <input type="checkbox" name="removewww" value="removewww" /></label></p></div>
	<div><p><label>Auto Remove "index.php" From Submitted URLs <input type="checkbox" name="removeindexphp" value="removeindexphp" /></label></p></div>
	<p class="submit"><input class="button button-primary wider nofloat " type="submit" name="add-to-410-list" value="Add entries to 410 list" /></p>
	</form>

	<h3>410 reponse message</h3>
	<p>By default, the plugin issues the following plain-text message as part of the 410 response: <code>Sorry, the page you requested has been permanently removed.</code></p>
	<?php
		if( locate_template( '410.php' ) )
			echo '<p><strong>A template file <code>410.php</code> has been detected in your theme directory. This file will be used to display 410 responses.</strong> To revert back to the default message, remove the file from your theme directory.</p>';
		else
			echo '<p>If you would like to use your own template instead, simply place a file called <code>410.php</code> in your theme directory, containing your template. Have a look at your theme\'s <code>404.php</code> template to see what it should look like.</p>';
	?>
  <p>&nbsp;</p>
  <form action="" method="post">
  <h3>Enable 410 On All Other Pages Except The Following:</h3>
  <div>
    <p>
      <label>Pages <input type="checkbox" name="excludepages" value="excludepages" <?php if( get_option('410whitelistpages')=='1' ){ echo 'checked'; } ?> /></label>
      <label>Posts <input type="checkbox" name="excludeposts" value="excludeposts" <?php if( get_option("410whitelistposts")=="1" ){ echo "checked"; } ?> /></label>
    </p>
    <p>
    <label for="customexclude">Manually Add Items to Whitelist. 1 URL Per Line. <br>
    <textarea name="customexclude" rows="8" cols="80"><?php
      if( get_option('410customlist')!='' ){
        echo get_option('410customlist');
      }
      ?></textarea>
    </label>
    </p>
  </div>
    <p class="submit"><input class="button button-primary wider nofloat " type="submit" name="410-white-list" value="Save 410 Whitelist" /></p>
  </form>

  </div>

	<script>
	jQuery(function($){
		jQuery('#table410').DataTable({"initComplete": function(settings, json){},"language": { "info": "Showing _END_ of _TOTAL_" },"autoWidth": false,"columns": [ null,null ],"dom": '<"top">rt<"bottom"filp><"clear">', "lengthMenu" : [ 10, 25, 50, 75, 100, 500 ] });

		$("#wp-gone-captcha-settings input").change( function(){
			$("#message").slideUp('slow');
		});
		$("#select-all-410, #select-all-404").change(function() {
			var el = $(this);
			el.closest("table").find("tbody input").prop("checked", el.is(":checked"));
		});
		$('#go').on('click',function(e){
			$("#wp_gone_old_links tr").addClass('hide');
			var search = $('#searchrecord').val();
			if(search==''){$("#wp_gone_old_links tr").removeAttr('class')}else $("#wp_gone_old_links tr:contains("+search+")").addClass('show');
		});
		$('#searchrecord').on('keyup keypress', function(e) {
		  var keyCode = e.keyCode || e.which;
		  if (keyCode === 13) {
			$("#wp_gone_old_links tr").addClass('hide');
			var search = $('#searchrecord').val();
			if(search==''){$("#wp_gone_old_links tr").removeAttr('class')}else $("#wp_gone_old_links tr:contains("+search+")").addClass('show');
			e.preventDefault();
			return false;
		  }
		});

	});
	</script>
