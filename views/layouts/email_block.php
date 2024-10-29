<div class="atrail">
<div>
  <div class="control-group {message_email_class}">
    <label class="control-label" for="inputEmail"><?php _e('E-mail', 'atl')?></label>
    <div class="controls">
      <input class="required" value="<?php echo esc_attr(@$dt['email'])?>" type="text" id="inputEmail" name="{module}_email" />
      {message_email}
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn btn-medium">{Button}</button>
    </div>
  </div>
</div>
</div>