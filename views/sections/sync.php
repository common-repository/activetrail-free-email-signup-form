<form method="post">
	<div class="control-group">
		<label class="control-group">
			<?php _e('Synchronize Groups', 'atl')?>
		</label>	
		<div class="controls">
		<input class="btn btn-primary" type="submit" value="<?php _e('Synchronize Groups', 'atl')?>" name="clear_group_cache" />
		<br /><br />
		<p class="help-block">
			<?php _e('By clicking this button, you will cache your groups inside database for better performance.
			 By default, the system will cache groups every 24 hours automatically.', 'atl')?></p>
		</div>
		<div class="">
			<label><?php _e('List of available groups', 'atl')?></label>
			<ul style="list-style:disc;">
				<?$groups =  Active_Trail_Plugin::get_groups();
				  foreach ($groups as $group) :
				?>
				<li><b><?php echo $group->name?></b> <i>ID:<?php echo $group->id?></i></li>
				<?php  endforeach; ?>
			</ul>
		</div>
	</div>
	<hr />
    <input type="hidden" value="1" name="sync" />
    <?php echo Active_Trail_Plugin::get_nonce(); ?>
</form>