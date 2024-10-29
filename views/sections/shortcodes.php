<div>
    <div class="clearfix"></div>
    <script type="text/javascript">
    	jQuery(function ($) {
    		$('#generateShortcode').submit(function () {
    			try { 
	    			if (typeof (tinyMCE) == 'object')
	    			tinyMCE.triggerSave();
	    			$.ajax({
	    				type : 'post',
	    				data : $(this).serialize(),
	    				url : ajaxurl + '?action=active_trail_general&page=shortcodes',
	    				success : function (data)
	    				{
	    					var html = $(data).find('#ajaxified_response').html();
	    					$('#ajaxified_response').html(html);
	    					$('body').scrollTop($(document).height());
	    				}
	    			});
	    			return false;
    			}
    			catch (e)
    			{
    				return true;
    			}
    		});
    		
    		$('#sall').click(function () {
    			var that = $(this);
    			that.parents('div:first').find('input').each(function () { 
    			 if ($(this).attr('name') != 'check_all')
    			 {
    			 	if (that.is(':checked'))
    			 	{
    			 		this.checked = true; 
    			 	}
    			 	else
    			 	{
    			 		this.checked = false;
    			 	}
    			 }
	    		
	    		});
    		});
    	});
    </script>

 	<form id="generateShortcode" class="form-vertical" method="post">
 
	<div id="cdata">
     <?php /*
     <div class="control-group">
			    <label class="control-label"><?php  _e('Title', 'atl'); ?></label>
			    <div class="controls">
			   	<input class="input-xlarge" value="<?php echo esc_html($_POST['title'])?>" type="text" name="title" />
 				<p class="help-text"><?php  _e('Choose title for this shortcode', 'atl'); ?></p>
			    </div>
	 </div>
	 <br /> */?>
	 <div class="control-group con">
			    <label class="control-label large-label"><?php  _e('Add custom text', 'atl'); ?></label>
			    <div class="controls">
			    <style>
			    	.con textarea {border:none;}
			    </style>
			    <?php  wp_editor(stripslashes($_POST['text']), 'activetrailx', array('textarea_rows' => 5, 'textarea_name' => 'text'));?>
 				<p class="help-text"><?php  _e('Set custom text', 'atl'); ?></p>
			    </div>
	 </div>
	 <br />
 
	
	 <label class="control-label large-label"><?php  _e('Select Fields Layout', 'atl'); ?></label>
 	 <div class="control-group layouter">
	    
	    <div style="width:100%;" id="layoutChoices" class="controls">
		    	<?php  $iter = 0; $it = 0; $max = array(6, 3, 7);
				$height = array
				(
				   1 => '120px',
				   2 => '120px',
				   4 => '265px',
				   5 => '265px',
				);
		        foreach (Active_Trail_Plugin::$LAYOUTS as $layout_id => $layout) : $iter++; $it++;
				?>
		    	 
		    	<?php  /*
				  <label class="radio">
				  <input <?php echo checked($_POST['layout'], $layout_id)?> id="layout_<?php echo $layout_id?>" type="radio" name="layout" value="<?php echo $layout_id?>"  />
				  <?php echo $layout['label']?>
				</label>
				  <div style="display:none;" class="layout_preview" id="layoutPreview_<?php echo $layout_id?>">
		 			<?php  include $layout['view']; ?>
		 			<div class="clearfix"></div>
		 	     </div> */ ?>
		 	       <?php if (isset($_POST['layout'])) {
		 			   	  
						    $checked = $_POST['layout'];
							 
		 			   } else { $checked = ($iter == 1 ? $layout_id : NULL); }?>	
	 
				  <div style="float:left;<?php echo (in_array($layout_id, $max) ? 'width:100%;margin-right:0px;' : 'width:50%;')?>" class="layout_preview2 <?php echo (($checked == $layout_id) ? 'layout_preview2active' : ($checked === NULL ? ($iter == 1 ? 'layout_preview2active' : '') : ''))?>" id="layoutPreview_<?php echo $layout_id?>">
				  	
				  	<div style="width:100%;position:absolute;height:100%;"></div>
		 			<div style="<?php echo (@$height[$layout_id] ? 'height:'.@$height[$layout_id].';' : '')?><?php echo ((in_array($layout_id, array(7, 5, 2)) || in_array($layout_id, $max)) ? 'margin-right:0px;': '')?>" class="padded">
		 			<?php  
		 			ob_start();
					$dt = array();
		 			include $layout['view'];
				    $tkg = ob_get_clean();
					
					$tkg = str_replace('{message_email}', '<p class="help-block">{message_email}</p>', $tkg);
					
					echo $tkg;
				    ?>
		 			<div class="clearfix"></div>
		 			<div style="display:none;">
		 			 
		 			   <input <?php echo checked($checked, $layout_id)?> id="layout_<?php echo $layout_id?>" type="radio" name="layout" value="<?php echo $layout_id?>"  />
		 			</div>
		 			</div>
		 	     </div> 
			 
	 			<?php  endforeach; ?>
		   	    <?php  Active_Trail_Plugin::do_action('layout_choices', get_defined_vars()); ?> 
		   	    <div class="clearfix"></div>
	    </div>
    </div>
    <br />
    
     <div class="control-group">
			    <label class="control-label large-label"><?php  _e('Button text <small style="font-weight:normal;">(on your form)</small>', 'atl'); ?></label>
			    <div class="controls">
			   	<input placeholder="<?php _e('for example: '.esc_html('"Subscribe"'), 'atl')?>" class="input-xlarge" value="<?php echo esc_html($_POST['button_txt'])?>" type="text" name="button_txt" />
 				<?php  /* <p class="help-text"><?php  _e('Choose button text for this shortcode', 'atl'); ?></p> */ ?>
			    </div>
	 </div>
	 <br />
	 
	 
    <div class="control-group">
			    <label class="control-label large-label"><?php  _e('Feedback message text', 'atl'); ?></label>
			    <div class="controls">
			    <label class="control-label"><?php _e('<small style="font-weight:normal;">Success message</small>', 'atl')?></label>
			   	<input placeholder="<?php _e('for example: '.esc_html('"Thank you for subscribing"'), 'atl')?>" class="input-xlarge" value="<?php echo esc_html($_POST['msg_success_general'])?>" type="text" name="msg_success_general" />
 				<?php  /* <p class="help-text"><?php  _e('Choose button text for this shortcode', 'atl'); ?></p> */?>
			    </div>
			    <div class="controls">
			    <label class="control-label"><?php _e('<small style="font-weight:normal;">Error message</small>', 'atl')?></label>
			   	<input placeholder="<?php _e('for example: '.esc_html('"An error occurred!"'), 'atl')?>" class="input-xlarge" value="<?php echo esc_html($_POST['msg_error_general'])?>" type="text" name="msg_error_general" />
 				<?php  /* <p class="help-text"><?php  _e('Choose button text for this shortcode', 'atl'); ?></p> */?>
			    </div>
	 </div>
	 <br />
	 
	 <div class="control-group">
			    <label class="control-label large-label"><?php  _e('Subscriber Groups <small style="font-weight:normal;">(your subscribers will be added to these groups)</small>', 'atl'); ?></label>
			    <div class="controls">
			    <?php  
			 
				 ?>
			   	<div class="checkbox-selector">
			   	  <label for="sall" class="checkbox" style="font-weight:normal;">
			   	    <input id="sall" type="checkbox" name="check_all" /> 
			   	    <?php _e('All Subscribers', 'atl')?>
			   	  </label>
 
			   	  <?php  foreach ($groups as $grp) : ?>
			   	  <label style="font-weight:normal;" class="checkbox">
			   	  	<input type="checkbox" name="group_id[]" <?php echo checked(in_array($grp->id, (array) $_POST['group_id']), true)?> value="<?php echo $grp->id?>" />
			   	    <?php echo $grp->name?>
			   	  </label>
			   	  <?php  endforeach; ?>
			   	</div>
 				<?php /* <p class="help-text"><?php  _e('Choose group. Group lists are cached on the site for performance. <a href="'.Active_Trail_Plugin::admin_url('general').'&clear_group_cache=true">Clear cache</a>?', 'atl'); ?></p> */?>
			    </div>
	 </div>
	 <br />
	 
	 <div class="control-group">
			    
			    <div class="controls">
			    <label class="checkbox">
			        <input <?php echo checked($_POST['as_verified'], 1)?> name="as_verified" value="1" type="checkbox"> <?php  _e('Import user as verified?', 'atl'); ?>
			    
			    </label>
 				<p class="help-block"><?php _e('Check this box if you want users to be automatically marked as confirmed when they submit sign up form on your site.', 'atl')?></p> 
			    </div>
	 </div>
 
	 
	 	 <div class="control-group">
			    
			    <div class="controls">
			    <label class="checkbox">
			        <input <?php echo checked($_POST['ajax'], 1)?> name="ajax" value="1" type="checkbox"> <?php  _e('Disable ajax?', 'atl'); ?>
			    </label>
			    <p class="help-block"><?php _e('Check this box if you want to disable fancy ajax submit (Ajax does not refresh the page when the form is submitted!).', 'atl')?></p> 
 				 
			    </div>
	 </div>
	 <br />
	 
    </div>
    <br />
    <div class="form-actions" id="AddControls">
    	<button style="margin-right:10px;"  class="btn btn-success btn-large" type="submit">
    		<?php _e('Generate Code', 'atl')?>
    	</button>
    </div>
    <?php echo Active_Trail_Plugin::get_nonce();?>
    <input type="hidden" value="1" name="shortcodes" />
    </form>
    
    <div id="ajaxified_response">
	    <?php  if ($_POST) : ?>
	    <?php echo ActiveTHelper::message(__('Shortcode generated! <strong>Copy the code below and paste it inside your page or post.</strong>', 'atl'), 'success');?>
	    <div style="margin-bottom:20px;">
	    	<textarea style="height:100px;width:96%;" onclick="this.select();" id="codeready"><?php  echo stripslashes($scode); ?></textarea>
	    </div>
 
	    <div style="padding:10px;margin:10px;border:1px solid #EEE;"><?php echo do_shortcode(stripslashes($scode))?></div>
	    <?php  endif; ?>
    </div>
</div>