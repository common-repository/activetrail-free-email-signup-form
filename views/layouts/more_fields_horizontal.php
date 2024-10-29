<div class="atrail">
<div class="form-horizontal">
  <div class="control-group">
    <label class="control-label" for="inputf"><?php _e('First Name', 'atl')?></label>
    <div class="controls">
      <input value="<?php echo esc_attr(@$dt['first_name'])?>" type="text" id="inputf" name="{module}_first_name">
    </div>
  </div>
   <div class="control-group">
    <label class="control-label" for="inputn"><?php _e('Last Name', 'atl')?></label>
    <div class="controls">
      <input value="<?php echo esc_attr(@$dt['last_name'])?>" type="text" id="inputn" name="{module}_last_name">
    </div>
  </div>
  <div class="control-group {message_email_class}">
    <label class="control-label" for="inputEmail"><?php _e('E-mail', 'atl')?></label>
    <div class="controls">
      <input value="<?php echo esc_attr(@$dt['email'])?>" class="required" type="text" id="inputEmail" name="{module}_email">
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