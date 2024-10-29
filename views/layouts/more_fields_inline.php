<div class="atrail">
<div class="">
	
    <div class="control-group {message_email_class}"><div class="controls">
    <span class="mright">	
    <label class="control-label" for="inputf"><?php _e('First Name', 'atl')?></label>
    <input value="<?php echo esc_attr(@$dt['first_name'])?>" type="text" id="inputf" name="{module}_first_name">
    </span>
    <span class="mright">
    <label class="control-label" for="inputn"><?php _e('Last Name', 'atl')?></label>
    <input value="<?php echo esc_attr(@$dt['last_name'])?>" type="text" id="inputn" name="{module}_last_name">
    </span>
    <span class="mright {message_email_class}">
    <label class="control-label" for="inputEmail"><?php _e('E-mail', 'atl')?></label>
    <input value="<?php echo esc_attr(@$dt['email'])?>" class="required" type="text" id="inputEmail" name="{module}_email">
    </span>
    <button style=" display: block; float: left; margin-top: 24px; " type="submit" class="btn btn-medium">{Button}</button>
    </div>
    <div style="clear:both;"></div>
    {message_email}
  </div>
</div>
</div>