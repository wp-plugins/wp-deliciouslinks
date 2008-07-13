<?php
define(MAGPIE_CACHE_ON,false);
// include WordPress RSS features
if (file_exists(ABSPATH . WPINC . '/rss.php')){
	include_once (ABSPATH . WPINC . '/rss.php');
}
else {
	include_once (ABSPATH . WPINC . '/rss-functions.php');
}
class dc_wp_feeds_1_1_0 extends dc_base_2_4_0 {
	function get($url,$number = 100,$cache_mins = 5) {
		return (array)"Update RSS get to fetch";
	}
	function fetch($url,$cache_mins = 5) {
		$options = $this->loadClass('wp_options');
		$items = array();
			//$options->values=null;
		//$options->save();
		// load feeds from cache

		// fix if feed cache gets messed up
		if (!is_array($options->values['feeds']))
		{
			$options->values = null;
			$options->save();
		}

		$feeds = $options->values['feeds'];
		// extract this feed
		$this_feed = $feeds[$url];

		// check for cache timeout
		if (($this_feed['cache']['time']+($cache_mins*60)) < time())
		{
			// get feed
			$rss = fetch_rss($url);
			// check feed returned correctly
			if ($rss) {
				if (get_class($rss) == "magpierss")
				{
					// cache feed
					$this_feed['cache']['time'] = time();
					$this_feed['cache']['items'] = $rss->items;
					$this_feed['cache']['channel'] = $rss->channel;
					$feeds[$url] = $this_feed;
					$options->values['feeds'] = $feeds;
					$options->save();
				}
			}
		}
		return $this_feed['cache'];
	}
}

?>