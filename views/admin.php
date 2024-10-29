<div class="wrap">
<div style="width:794px;width:794px;margin-left: 23px;" class="atrail">
	<h2><?php  _e('Configure ActiveTrail', 'atl')?> <small><?php echo ucfirst(esc_html($data['title']))?></small></h2>
     <br />
     <?php  /*
     <div class="tabbable tabs-left">
              <ul class="nav nav-tabs">
              	<?php  foreach (Active_Trail_Plugin::$PAGES as $k => $tab) :  ?>
                   <li <?php echo ($data['section'] == $k ? 'class="active"' : '')?>><a href="<?php echo admin_url('admin.php?page='.ACTIVE_TRAIL_NAME.'-'.$k)?>"><?php echo ($tab)?></a></li>
                <?php  endforeach; ?>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="lA"> */ ?>
               		  <?php  include ACTIVE_TRAIL_SECTIONS_DIR.'/'.$data['section'].'.php'; ?>
                <?php  /*		  
                <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
              </div>
            </div> <!-- /tabbable --> */?>
            <div class="clearfix"></div>
     </div>
</div>
<style>
	.atrail .tab-content {overflow-x:hidden;}
</style>