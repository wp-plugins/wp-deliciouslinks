<?php
require_once( ABSPATH . 'wp-admin/includes/admin.php');
class dc_wp_blogroll_1_0_0 extends dc_base_2_4_0 {

	/**
	 * add category and return term id
	 * easy way to get term id, does not create double entries
	 *
	 * @param string $name
	 * @return integer
	 */
	function add_category($name) {
		$result=wp_insert_term($name, 'link_category', array('cat_name' => $name));
		return $result['term_id'];
	}

	/**
	 * add link to blogroll and enter into categories
	 *
	 * @param array $link_details
	 * @param array $categories
	 */

	function categories($link_id){
		$return = array();
		foreach(wp_get_link_cats($link_id) as $category)
		{
			$details = get_term($category,'link_category');
			$return[]= $details->name;
		}
		return $return;
		}
	function get($category='') {
		return get_bookmarks(array('type'=>'link_category','category_name'=>$category));
	}
	function add_link($link_details,$categories = array()){
		if (count($categories)>0){
			$link_category=array();
			foreach($categories as $category) {
				$link_category[]=$this->add_category($category);
			}
			$link_details['link_category']=$link_category;
		}
		return wp_insert_link($link_details);
	}
	function ids($category_name) {
		$cids = get_bookmarks(array('type'=>'link_category','category_name'=>$category_name));
		$ids = array();
		foreach($cids as $id) {
			$ids[]=$id->link_id;
		}
		return $ids;
	}
	function names($category_name) {
		$cnames = get_bookmarks(array('type'=>'link_category','category_name'=>$category_name));
		$names = array();
		foreach($cnames as $name) {
			$names[]=$name->link_name;
		}
		return $names;
	}
}
?>