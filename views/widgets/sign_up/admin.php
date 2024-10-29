<!-- This file is used to markup the administration form of the widget. -->
<!-- Note that the use of the 'Title' field is purely for example purposes only. -->
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'atl' ) ?></label>
	<br/>
	<input type="text" class="widefat" value="<?php echo esc_attr( $instance['title'] ); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" />
	<br/> 
</p>


<p>
	<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Custom Text:', 'atl' ) ?></label>
	<br/>
	<textarea class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_attr( $instance['text'] ); ?></textarea>
	<br/> 
 
</p>


<p>
	 <label for="<?php echo $this->get_field_id( 'layout' ); ?>"><?php _e( 'Layout:', 'atl' ) ?></label>
	<br />
	<select id="<?php echo $this->get_field_id( 'layout' ); ?>" style="width:140px;" name="<?php echo $this->get_field_name( 'layout' ); ?>">
		<?php  foreach (Active_Trail_Plugin::$LAYOUTS as $k => $name) : ?>
		 <option <?php echo selected($instance['layout'], $k)?> value="<?php echo $k?>"><?php echo $name['label']?></option>
		<?php  endforeach;?>
	</select>
</p>

<p>
	 <label for="<?php echo $this->get_field_id( 'groups' ); ?>"><?php _e( 'Groups:', 'atl' ) ?></label>
	 <?php/*
	<br />
	<select id="<?php echo $this->get_field_id( 'groups' ); ?>" style="width:140px;" name="<?php echo $this->get_field_name( 'groups' ); ?>[]">
		<?php  foreach (Active_Trail_Plugin::get_groups() as $name) : ?>
		 <option <?php echo selected(in_array($name->id, (array) $instance['groups']), true)?> value="<?php echo $name->id?>"><?php echo $name->name?></option>
		<?php  endforeach;?>
	</select>*/?>
	<style>
		.checkbox-selector { height:140px;width:210px;margin-bottom:10px;border:1px solid #EEE;padding:5px;overflow-y:scroll; }
	</style>
	<script>
		jQuery(function ($)
		{
			    $('.sall').click(function () {
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
				 <div class="checkbox-selector">
			   	  <label style="display:block;" for="sall" class="checkbox" style="font-weight:normal;">
			   	    <input id="sall" class="sall" type="checkbox" name="check_all" /> 
			   	    <?php _e('All Subscribers', 'atl')?>
			   	  </label>
			   	   
			   	  <?php  foreach (Active_Trail_Plugin::get_groups() as $grp) : ?>
			   	  <label for="select_gr_<?php echo $grp->id?>" style="font-weight:normal;display:block;" class="checkbox">
			   	  	<input id="select_gr_<?php echo $grp->id?>" type="checkbox" name="<?php echo $this->get_field_name( 'groups' ); ?>[]" <?php echo checked(in_array($grp->id, (array) (array) $instance['groups']), true)?> value="<?php echo $grp->id?>" />
			   	    <?php echo $grp->name?>
			   	  </label>
			   	  <?php  endforeach; ?>
			   	</div>
</p>


<p>
	<label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button text:', 'atl' ) ?></label>
	<br/>
	<input type="text" class="widefat" value="<?php echo esc_attr( $instance['button_text'] ); ?>" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" />
	<br/> 
 
</p>


<p>
	<label for="<?php echo $this->get_field_id( 'msg_success_general' ); ?>"><?php _e( 'Success message:', 'atl' ) ?></label>
	<br/>
	<input type="text" class="widefat" value="<?php echo esc_attr( $instance['msg_success_general'] ); ?>" id="<?php echo $this->get_field_id( 'msg_success_general' ); ?>" name="<?php echo $this->get_field_name( 'msg_success_general' ); ?>" />
	<br/> 
 
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'msg_error_general' ); ?>"><?php _e( 'Error message:', 'atl' ) ?></label>
	<br/>
	<input type="text" class="widefat" value="<?php echo esc_attr( $instance['msg_error_general'] ); ?>" id="<?php echo $this->get_field_id( 'msg_error_general' ); ?>" name="<?php echo $this->get_field_name( 'msg_error_general' ); ?>" />
	<br/> 
 
</p>



<p>
	<label style="display:block;" for="<?php echo $this->get_field_id( 'verified' ); ?>">
	<br/>
	
	<input <?php echo checked($instance['verified'], 1)?> type="checkbox" value="1" id="<?php echo $this->get_field_id( 'verified' ); ?>" name="<?php echo $this->get_field_name( 'verified' ); ?>" />
	<?php _e( 'Import users as verified', 'atl' ) ?>
	</label>
 <br />
	<label style="display:block;" for="<?php echo $this->get_field_id( 'ajax' ); ?>">
	 
	<input <?php echo checked($instance['ajax'], 1)?> type="checkbox" value="1" id="<?php echo $this->get_field_id( 'ajax' ); ?>" name="<?php echo $this->get_field_name( 'ajax' ); ?>" />
	<?php _e( 'Check this option to disable ajax', 'atl' ) ?>
	</label>
	<br/> 
 
</p>

<p>
	We recommend using shortcodes over widgets, see <a href="<?php echo Active_Trail_Plugin::admin_url('shortcodes');?>">Our shortcode generator</a>
</p>