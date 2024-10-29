<div class="atrail">
<div class="form-inline">
	
    <div class="controls {message_email_class}">
    <label class="control-label" for="inputEmail"><?php _e('E-mail', 'atl')?></label>
      <input class="required" value="<?php echo esc_attr(@$dt['email'])?>" type="text" id="inputEmail" name="{module}_email"/> 
      <button type="submit" class="btn btn-medium">{Button}</button>
      <div style="clear:both;"></div>
      {message_email}
    </div>
    
</div>
</div>