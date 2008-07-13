<?php
class dc_wp_filters_1_0_0 extends dc_base_2_4_0 {
	function prepare($content)
	{
		$replace = array(
			'post_rss_title' => 'get_the_title_rss',
			'post_author' => 'get_the_author',
			'post_author_description' => 'get_the_author_description',
			'post_author_email' => 'get_the_author_email',
			'post_author_firstname' => 'get_the_author_firstname',
			'post_author_lastname' => 'get_the_author_lastname',
			'post_author_nickname' => 'get_the_author_nickname',
			'post_author_url' => 'get_the_author_url',
			'post_url' => 'get_the_guid',
			'post_date' => 'get_the_modified_date',
			'post_time' => 'get_the_modified_time',
			'post_title' => 'get_the_title',
			'blog_url'=> array('get_bloginfo','url'),
			'blog_name'=> array('get_bloginfo','name'),
			'blog_desc'=> array('get_bloginfo','description'),
			'blog_home'=> array('get_bloginfo','home'),
		);
		foreach(array_keys($replace) as $key)
		{
			if(is_array($replace[$key]))
			{
				$value = call_user_func($replace[$key][0],$replace[$key][1]);
			}
			else
			{
				$value = call_user_func($replace[$key]);
			}
			$content = str_replace("@@$key@@",$value,$content);
		}
		return $content;
	}
}
?>