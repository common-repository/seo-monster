<?php
$labui = new seo_monster_wp_ui();
include 'css/style.php';
?>
<script>
jQuery(window).on('load',function(){
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




<div class="table_wrap">
	<div class="global_spinner">
		<div class="loader"></div>
	</div>
	<div id="layoutSidenav" class="<?php echo $update;?>">
		<div id="layoutSidenav_content">
			<main>
				<div class="container-fluid">
					<h1 class="mt-4"><img src="<?php echo plugins_url( 'seo-monster/images/Monster-ICO.png' ); ?>" />Monster SEO - Monitor & Manage Links</h1>
					<div class="seomonster_header">
					<div class="card mb-4" style="max-width: 100%; padding: 0;">
						<div class="card-header">
							<div class="fitem title">

							 <label for="fpageType"><input type="checkbox" checked name="fpageType" id="selectpage" value="selectpage" /> Page</label>
							 <label for="fpostType"><input type="checkbox" checked name="fpostType" id="selectpost" value="selectpost" /> Post</label>
							 <label for="fcategory"><input type="checkbox" checked name="fcategory" id="selectcategory" value="selectcategory" /> Category</label>
							 <label for="ftag"><input type="checkbox" checked name="ftag" id="selecttag" value="selecttag" /> Tag</label>
							 <label for="fAttach"><input type="checkbox" checked name="fAttach" id="selectattachment" value="selectattachment" /> Attachment</label>
							 <label for="fAuthor"><input type="checkbox" checked name="fAuthor" id="selectauthor" value="selectauthor" /> Author</label>
							 <?php
							 echo $labui->get_custom_post_types();
							 ?>
							</div>
							<div class="fitem fitem_settings">
							<span class="switchcolorlabel">switch color </span> <!--a href="#" class="seo_settings"><span><i class="fas fa-cog"></i></span></a-->
							<select id="colorswitch">
							<?php $color = get_option('seo_monster_color'); ?>
							<option value="dark" <?php if($color=='dark'){echo 'selected';} ?>>Dark</option>
							<option value="light" <?php if($color=='light'){echo 'selected';} ?>>Light</option>
							</select>
							</div>
						</div>
					</div>
					<div class="fitem">
						<div class="flexme between actions">
							<div class="fitem">
							<div class="recommendation"><i class="far fa-question-circle"></i> Recommendations
							<div class="recommendation-content">
								<p>Categories, Tag, Attachment is recommended to be noindex.</p>
								<hr>
								<p>
								We recommend these lines on your robots.txt file<br>
								User-agent: *<br>
								Disallow: /wp-admin/<br>
								Allow: /wp-admin/admin-ajax.php<br><br>
								Sitemap: https://example.com/your_sitemap.xml
								</p>


							</div>
							</div>
							<a href="/robots.txt" class="btn success robots_link" target="_blank">View Robots.txt</a>
							</div>
							<!--span><label for="saveresult"><input type="checkbox" name="saveresult" id="saveresult" value="saveresult" /> Save Result &nbsp;</label></span-->
							<!--span><a class="btn btn-primary refresh-indexability" href="#">Refresh Indexability Status</a></span-->
							<div class="fitem">
								<div class="flexme end  bulk_actions">
									<div style="margin-right:10px;position:relative;">
										<label class="white">Bulk Actions:</label>
										<input type="button" class="button action bulkaction" value="enable410" />
										<input type="button" class="button action bulkaction"  value="copySelected" />
										<input type="button" class="button action bulkaction"  value="generateXml" />
										<div class="sitemap_form">
											<a href="#" onclick="jQuery(this).parent().removeClass('display');return false;" class="closeform">close</a>
											<form method="post" id="sitemap_form" onsubmit="xml_create();return false;">
											<input type="text" class="form-control" name="sitemap_name" placeholder="File_Name.xml" id="sitemap_name" required />
											<input type="submit" value="Create" />
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
										<div class="scaleserp_form">

											<a href="#" onclick="jQuery(this).parent().removeClass('display');return false;" class="closeform">close</a>
											<label>Please confirm action.</label>
											<?php
											echo Seo_Monster::get_scaleserp_action(); ?>
										</div>

									</div>
									<textarea id="copySelectedUrls"></textarea>
								</div><!--end bulk_actions-->
							</div>
						</div>
					</div>
					</div>
				</div>


				<div id="global_message"><div class="message"></div></div>
				<?php
				$pagecount = wp_count_posts('page')->publish;
				$postcount = wp_count_posts('post')->publish;
				$attachmentcount = Seo_Monster::get_attachlist(1);
				$authorcount = Seo_Monster::get_authlist(1);
				echo '<input type="hidden" id="pagetotal" value="'.$pagecount.'" />
				<input type="hidden" id="posttotal" value="'.$postcount.'" />
				<input type="hidden" id="attachmenttotal" value="'.$attachmentcount.'" />
				<input type="hidden" id="authortotal" value="'.$authorcount.'" />';	?>
				<div class="card-body">
					<div class="table-responsive">

						<!-------PAGE LIST-------->
						<div class="seo-table withnavi page">
						<div class="runaction">
						<p>Crawlling in progress... Click "Quit & Save" to continue crawlling next time.</p>
						<input type="submit" class="quitsave page" value="Quit & Save" /></div>
						<textarea id="allpagelistapi"></textarea>
						<div class="page_spinner local_spinner"><div class="loader"></div></div>
						<input type="checkbox" name="checkpage" id="checkpage" class="checkall" />
						<table class="table table-bordered pagelist" id="pagelist" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th><span class="th sorting_asc">Page Urls</span></th>
								<th style="width:165px;">Crawl Frequency</th>
								<th style="width:160px;">Last Modified</th>
								<th style="width:170px;">Last Google Crawl</th>
								<th style="width:140px;" class="ingoogle">Is In Google <span><i class="far fa-question-circle"></i></span>
								<div class="scaleserp">This feature is per request to protect Your Scaleserp credits. Can be change in settings (Top-Right).</div>
								</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
						</table>
						</div>
						<!-------POST LIST-------->
						<div class="seo-table withnavi post">
						<div class="runaction"><input type="submit" class="quitsave page" value="Quit & Save" /></div>
						<textarea id="allpostlistapi"></textarea>
							<div class="post_spinner local_spinner"><div class="loader"></div></div>
							<input type="checkbox" name="checkpost" id="checkpost" class="checkall" />
							<table class="table table-bordered postlist" id="postlist" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th><span class="th sorting_asc">Post Urls</span></th>
										<th style="width:165px;">Crawl Frequency</th>
										<th style="width:160px;">Last Modified</th>
										<th style="width:170px;">Last Google Crawl</th>
										<th style="width:140px;" class="ingoogle">Is In Google <span><i class="far fa-question-circle"></i></span>
										<div class="scaleserp">This feature is per request to protect Your Scaleserp credits. Can be change in settings (Top-Right).</div>
										</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
						<!-------CATEGORY LIST-------->
						<div class="seo-table category">
						<div class="runaction"><input type="submit" class="quitsave page" value="Quit & Save" /></div>
						<textarea id="allcatlistapi"></textarea>
							<div class="cat_spinner local_spinner"><div class="loader"></div></div>
							<input type="checkbox" name="checkcategory" id="checkcategory" class="checkall" />
							<table class="table table-bordered catlist" id="catlist" width="100%" cellspacing="0">
                <thead>
									<tr>
										<th><span class="th sorting_asc">Category Urls</span></th>
										<th style="width:165px;">Crawl Frequency</th>
										<th style="width:160px;">Last Modified</th>
										<th style="width:170px;">Last Google Crawl</th>
										<th style="width:140px;" class="ingoogle">Is In Google <span><i class="far fa-question-circle"></i></span>
										<div class="scaleserp">This feature is per request to protect Your Scaleserp credits. Can be change in settings (Top-Right).</div>
										</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<!-------ATTACHMENT LIST-------->
						<div class="seo-table sm_attachment">
						<div class="runaction"><input type="submit" class="quitsave page" value="Quit & Save" /></div>
						<textarea id="allattachlistapi"></textarea>
							<div class="attach_spinner local_spinner"><div class="loader"></div></div>
							<input type="checkbox" name="checkattachment" id="checkattachment" class="checkall" />
							<table class="table table-bordered attachlist" id="attachlist"  width="100%" cellspacing="0">
								<thead>
									<tr>
										<th><span class="th sorting_asc">Attachment Url</span></th>
										<th style="width:165px;">Crawl Frequency</th>
										<th style="width:160px;">Last Modified</th>
										<th style="width:170px;">Last Google Crawl</th>
										<th style="width:140px;" class="ingoogle">Is In Google <span><i class="far fa-question-circle"></i></span>
										<div class="scaleserp">This feature is per request to protect Your Scaleserp credits. Can be change in settings (Top-Right).</div>
										</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<!-------TAGS LIST-------->
						<div class="seo-table tag">
						<div class="runaction"><input type="submit" class="quitsave page" value="Quit & Save" /></div>
						<textarea id="alltaglistapi"></textarea>
							<div class="tag_spinner local_spinner"><div class="loader"></div></div>
							<input type="checkbox" name="checktag" id="checktag" class="checkall" />
							<table class="table table-bordered taglist" id="taglist" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th><span class="th sorting_asc">Tag Urls</span></th>
										<th style="width:165px;">Crawl Frequency</th>
										<th style="width:160px;">Last Modified</th>
										<th style="width:170px;" >Last Google Crawl</th>
										<th style="width:140px;" class="ingoogle">Is In Google <span><i class="far fa-question-circle"></i></span>
										<div class="scaleserp">This feature is per request to protect Your Scaleserp credits. Can be change in settings (Top-Right).</div>
										</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<!-------Author LIST-------->
						<div class="seo-table author">
						<div class="runaction"><input type="submit" class="quitsave page" value="Quit & Save" /></div>
						<textarea id="allauthlistapi"></textarea>
							<div class="author_spinner local_spinner"><div class="loader"></div></div>
							<input type="checkbox" name="checkauthor" id="checkauthor" class="checkall" >
							<table class="table table-bordered authlist" id="authlist" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th><span class="th sorting_asc">Author Urls</span></th>
										<th style="width:165px;">Crawl Frequency</th>
										<th style="width:160px;">Last Modified</th>
										<th style="width:170px;">Last Google Crawl</th>
										<th style="width:140px;" class="ingoogle">Is In Google <span><i class="far fa-question-circle"></i></span>
										<div class="scaleserp">This feature is per request to protect Your Scaleserp credits. Can be change in settings (Top-Right).</div>
										</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<?php
							echo $labui->display_pages_other_post_types();
						?>
					</div>
				</div>
			</main>
		</div>
	</div>
</div>
