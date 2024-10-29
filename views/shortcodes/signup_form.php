<?php   
   if ( ! isset($attrs['ajax'])) $attrs['ajax'] = 1;
   
   $find = array
   (
      '{message_email}' => '',
      '{message_email_class}' => '',
 
   ); 
   
   if ( ! isset($result['no_config'])) : 
   
	   foreach ( (array) $result['errors'] as $k => $v)
	   {
		   $find['{message_'.$k.'}'] = '<div class="alert alert-error"><p>'.$v.'</p></div>';
		   $find['{message_'.$k.'_class}'] = 'error';
	   }
	   
	   foreach ( (array) $result['success'] as $k => $v)
	   {
		   $find['{message_'.$k.'}'] = '<div class="alert alert-success"><p>'.$v.'</p></div>';
		   $find['{message_'.$k.'_class}'] = 'success';
	   }
 
   endif;
 
?>
<div class="<?php echo ACTIVE_TRAIL_NAME;?>-signup-form <?php echo ACTIVE_TRAIL_NAME;?>-signup-form-ajax-<?php echo (($attrs['ajax'] == '1') ? 'active' : 'disabled')?>">
<?php  if ($attrs['name'] AND FALSE) : ?>
<h3><?php echo $attrs['name']?></h3>
<?php  endif; ?>
<?php  if ($content) : ?>
<div class="<?php echo ACTIVE_TRAIL_NAME;?>-signup-form-content">
	<?php  echo apply_filters('wpautop', $content); ?>
</div>
<?php  endif; ?>
<form id="form-submit-<?php echo Active_Trail_Plugin::$FORM_ID;?>" class="<?php echo ACTIVE_TRAIL_NAME;?>-main-form" method="post">
	<input type="hidden" name="<?php echo (ACTIVE_TRAIL_NAME.'_form_id')?>" value="<?php echo Active_Trail_Plugin::$FORM_ID;?>" />
	<?php  $layout = str_replace(array('{Button}', '{module}'), 
				   array(
					   ($attrs['button_text'] ? $attrs['button_text'] : __('Sign Up', 'atl')),
					   ACTIVE_TRAIL_NAME,
				   ), 
				   $layout);
	
	 $layout = str_replace(array_keys($find), array_values($find), $layout);
				    
	 echo $layout; ?>
    <input type="hidden" name="<?php echo (ACTIVE_TRAIL_NAME.'_signup')?>" value="1" />
</form>
</div>