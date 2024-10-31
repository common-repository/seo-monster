<?php
$labui = new seo_monster_wp_ui();
include 'css/style.php';
?>
<script>
jQuery(document).ready(function(){
	jQuery('#themonster_api input[name="theseomonster_api"]').keyup(function(){
		jQuery('.monsterstatus .action').addClass('disabled');
	});
});
</script>
<div class="table_wrap">
<div id="">
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4"><img src="<?php echo plugins_url( 'seo-monster/images/Monster-ICO.png' ); ?>" />My SEO Bots Integration</h1>

                        <div class="card mb-4" style="max-width: 100%; padding: 0;">
                            <div class="card-header">
                                <div class="fitem title">
                                 <i class="fas fa-table mr-1"></i>
                                 Integrations
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">


<div class="container-fluid">
                        <div class="row">                          
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-secondary integration-card text-white mb-4">
				    <div class="card-body">
				    <div class="flexme">
				    <span><img src="<?php echo SEMO_PLUGIN_PATH .'images/omega.png'; ?>" /></span>
				    <h4>Omega Indexer</h4>
				    </div>
				    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <form method="post" name="omega_api" id="omega_api">
						<input type="text" class="fw" name="omega_api" placeholder="API KEY" value="<?php $labui->check_integration(true,'omega'); ?>" />

	                                        <div class="small text-white" style="display: inline-block; vertical-align: middle; position: relative;">
	                                        <input type="submit" name="submit" value="Save" class="btn btn-secondary" style="font-size: 12px;" />
	                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                           <div class="card bg-secondary integration-card text-white mb-4">
                                    <div class="card-body">
				    <div class="flexme">
				    <span><img src="<?php echo SEMO_PLUGIN_PATH .'images/speedlinks.png'; ?>" /></span>
				    <h4>Speed Links</h4>
				    </div>
				    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <form method="post" name="speedlinks_api" id="speedlinks_api">
						<input type="text" class="fw" name="speedlinks_api" placeholder="API KEY" value="<?php echo $labui->check_integration(true,'speedlinks'); ?>" />

	                                        <div class="small text-white" style="display: inline-block; vertical-align: middle; position: relative;">
	                                        <input type="submit" name="submit" value="Save" class="btn btn-secondary" style="font-size: 12px;" />
	                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                            <div class="card bg-secondary integration-card text-white mb-4">
                                    <div class="card-body">
				    <div class="flexme">
				    <span><img src="<?php echo SEMO_PLUGIN_PATH .'images/scaleserp.png'; ?>" /></span>
				    <h4>Scale Serp</h4>
				    </div>
				    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <form method="post" name="scaleserp_api" id="scaleserp_api">
						<input type="text" class="fw" name="scaleserp_api" placeholder="API KEY" value="<?php echo $labui->check_integration(true,'scaleserp'); ?>" />
	                                        <div class="small text-white" style="display: inline-block; vertical-align: middle; position: relative;">
	                                        <input type="submit" name="submit" value="Save" class="btn btn-secondary" style="font-size: 12px;" />
	                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>       </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </main>

            </div>
        </div>
    </div>
