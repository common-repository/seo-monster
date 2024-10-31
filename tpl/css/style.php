<?php
$theme_color = 'dark';
$color = get_option('seo_monster_color');
if($color){
$theme_color = $color;
}
//$theme_color = 'light';
?>
<script>
jQuery('#wpwrap').addClass('<?php echo $theme_color; ?>');
</script>
<style>
   .dark .bulk_actions .scaleserp_form label,
   .dark .bulk_actions form label {color:#000!important;}
   .bottom ul.pagination {position:relative;}
   .loading + .bottom ul.pagination:before, .drawing + .bottom ul.pagination:before{content:'';display:block;position:absolute;top:0;bottom:0;width:100%;z-index: 9999;}
   .loading + .bottom ul.pagination:hover::before,.drawing + .bottom ul.pagination:hover::before{cursor: wait;}
   h1 img {max-width: 50px;}
   .seo-table.running .runaction {left: calc(50% - 212px);text-align:center;top:40%;}
   .seo-table.running .runaction p {color: #fff!important;font-size: 20px;max-width: 425px;text-shadow: 0px 0px 3px #000;font-weight: bold;}
   .seo-table.running .runaction input[type=submit] {float:none}
</style>
