<?php
class dc_wp_post_1_2_0 extends dc_base_2_4_0 {

	var	$postStatuses = array('draft','private','publish');
	var $postTypes = array('post','page');
	function onPosts($callback,$postStatuses=null,$postTypes=null)
	{
		if (is_null($postStatuses))
			$postStatuses=$this->postStatuses;
		if (is_null($postTypes))
			$postTypes=$this->postTypes;
		$return = "";
		foreach ($postTypes as $postType)
		{
			foreach($postStatuses as $postStatus)
			{
				$query = array(
					'post_type'=>$postType,
					'post_status'=>$postStatus,
					'offset'=>0,
					'numberposts'=>5
				);

				while(count($posts = get_posts($query)))
				{
					foreach ($posts as $post)
					{
						$return.=call_user_func_array($callback,$post->ID);
					}
					$query['offset']+=$query['numberposts'];
				}
			}
		}
		return $return;
	}


	function add_category($name) {
		$result=wp_insert_term($name, 'category', array('cat_name' => $name));
		return $result['term_id'];
	}
	function ids($category_name) {
		$cids = get_posts(array('type'=>'category','category_name'=>$category));
		$ids = array();
		foreach($cids as $id) {
			$ids[]=$post->post_id;
		}
		return $ids;
	}
	function category_name($category_id)
	{
		return get_cat_name($category_id);
	}
	function flatten()
	{
		$posts=get_posts(array('post_status'=>'draft','numberposts'=>9999999));
		foreach($posts as $post)
		{
			wp_delete_post($post->ID);
		}
		$posts=get_posts(array('post_status'=>'publish','numberposts'=>9999999));
		foreach($posts as $post)
		{
			wp_delete_post($post->ID);
		}
	}
	function get_post_by_title($post_title, $output = OBJECT) {
		global $wpdb;
		$post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='post'", $post_title ));
		if ( $post )
			return get_post_to_edit($post);

		return NULL;
	}

}

?>