<table class="table table-stripped">
	<thead>
		<th><?php _e('Log entry', 'atl')?></th>
		<th><?php _e('Last change', 'atl')?></th>
	</thead>
	<tbody>
		<?php
		 $filesf = scandir(ACTIVE_TRAIL_LOG_DIR);
		 if (count($filesf) == 2)
		 {
		 	?>
		 	<tr>
		 		<td align="center" colspan="2">
		 			<?php echo ActiveTHelper::message(__('No logs available', 'atl'), 'error');?>
		 		</td>
		 	</tr>
		 	<?php
		 }
		 else {
			 foreach ($filesf as $file) :
			   if ( ! in_array($file, array('.', '..'))) :?>
			<tr>
				<td><a target="_blank" href="<?php echo Active_Trail_Plugin::plugins_url('logs/'.$file);?>"><?php echo esc_html($file)?></a></td>
				<td><?php echo date('d.m.y H:i:s', Active_Trail_Plugin::filemtime('logs/'.$file));?></td>
			</tr>
			<?php  endif; endforeach; 
		 }?>
	</tbody>
</table>