<?php

/**
 * Active trail helper class
 */
class ActiveTHelper
{
	/**
	 * Renders styled message to the user
	 */
	public static function message($message, $type = '')
	{
		if (is_array($message))
		{
			$html = '';
			foreach ($message as $t)
			{
				$html .= self::message($t);
			}
			
			return $html;
		}
		else
		{
			return '<div class="alert alert-block alert-'.$type.'">
					<p>'.$message.'</p>
				 </div>';
		}
		
	}
	
	/**
	 * Form element wrapper
	 * @return string
	 */
	public static function wrap_form($label, $element)
	{
		static $occurances = 0;
		$occurances++;
		
		return '<div class="control-group">
			    <label class="control-label" for="sinput_'.$occurances.'">'.$label.'</label>
			    <div class="controls">
			           '.str_replace('id="sinput"', 'id="sinput_'.$occurances.'"', $element).'
			    </div>
			  </div>';
	}
}
