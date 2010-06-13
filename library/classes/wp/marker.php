<?php
class dc_wp_marker_1_3_0 extends dc_base_2_4_0 {
	var $marker = array();
	var $callback = null;

	function addMarker($marker,$callback,$priority=1) {
		$this->marker[] = $marker;
		$this->callback = $callback;
		add_filter('the_content', array($this,'_matchMarker'),$priority);
	}
	function _matchMarker($content)
	{
		$t = $this->loadClass('tag');
		foreach($this->marker as $marker)
		{
			$matches = $t->get($marker,$content,true);
			foreach($matches as $match) {
				if ($match['attributes']['demo']=="true")
				{
					unset($match['attributes']['demo']);
					$newmatch=$t->render($match);
					$content=str_replace($match['match'],$newmatch,$content);
				}
				else
					$content=call_user_func($this->callback,$content,$match);
			}
		}
		return $content;
	}



//-----------------------------------------------------------------------------------------------------------------
// depreciated functions
	function config($marker,$callback,$priority=1) {
		$this->addMarker($marker,$callback,$priority);
	}
}

?>