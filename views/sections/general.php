 
<?php  if ($data['success'])  
   echo ActiveTHelper::message($data['success'][0], $data['success'][1]);
?> 
 
<form method="post">
    <div class="control-group">
        <label class="control-label" for="inputEmail">Username</label>
        <div class="controls">
            <input type="text" name="user" value="<?php echo esc_html($data['creds']['user']); ?>" placeholder="Username" id="inputEmail" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPassword">Password</label>
        <div class="controls">
            <input type="password" value="<?php echo esc_html($data['creds']['password']) ?>" name="password" id="inputPassword" placeholder="Password">
        </div>
    </div>
    
	<div class="control-group">
			    <div class="controls">
			    <label class="checkbox">
			        <input <?php echo checked($data['logging'], 1)?> name="logging" value="1" type="checkbox"> <?php  _e('Enable plugin logging?', 'atl'); ?>
			    </label>
			    <p class="help-block"><?php _e('Check this box if you want to turn on plugin logging (Helps if you encounter any problems with the plugin).', 'atl')?></p> 
			    </div>
	 </div>
    <br />
    <div class="control-group">
        <div class="controls">
            <button value="1" name="save_data" class="btn btn-primary btn-large" type="submit" style="margin-right:15px;" class="btn">
                <?php _e('Apply', 'atl')?>
            </button>
            <?php  /*
            <button value="1" <?php echo ( ! $data['creds']['user'] ? 'disabled="disabled" onclick="alert(\'You can only check login once you enter your credentials!\');" ' : '')?> name="check_login" class="btn btn-inverse">
            	<?php _e('Check Login', 'atl')?>
            </button> */?>
        </div>
    </div>
    <input type="hidden" value="1" name="general" />
    <?php echo Active_Trail_Plugin::get_nonce(); ?>
</form>



<br />
<?php echo ActiveTHelper::message(sprintf(__('You need to enter your ActiveTrail.com username and password in order to integrate.
 If you didn\'t register, you can do it <a href="%s">here.</a>', 'atl'), ACTIVE_TRAIL_REGISTER_URI));?>
<br />
