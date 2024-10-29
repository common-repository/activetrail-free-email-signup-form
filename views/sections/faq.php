<div class="accordion" id="accordion2">
  <?
   
    $help = array
    (
	  array(
		  __('CSS styling'), 
		  sprintf(__('CSS styling can be done very easy by applying basic CSS override rules by adding a <b>activetrail-custom.css</b> into <b>your theme folder</b> and it will be automatically included <i>after</i> the main plugin css.<br />
		   In that .css file you can write css rules, for example the color of the submit button border to red, by simply writing this line:
		   <br /><br />
		   <pre>body .atrail .btn { border:1px solid red; }</pre><br />And that\'s it!
	  ', 'atl'))),
 
	);
  
   foreach ($help as $t) :
  ?>	
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        <?php echo ($t[0])?>
      </a>
    </div>
    <div id="collapseOne" class="accordion-body collapse in">
      <div class="accordion-inner">
        <?php echo ($t[1])?>
      </div>
    </div>
  </div>
  <?php  endforeach; ?>
</div>