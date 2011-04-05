<?php
class linksyncsettings extends wv25v_settings {
	public function __construct($application)
	{
		parent::__construct($application);
		$this->legacy_move('link-sync','network');
		$this->legacy_move('LinkSync','network');
	}
	public function update() {
		$data = $this->all();
		if(!empty($data['last_updated']) && !empty($data['url']))
		{
			if ((time () - $data ['last_updated']) / (60 * 60 * 24) >= $data ['between']) {
				$this->synclinks ( $data ['url'] );
				$data = $this->set ( $data,'network' );
			}
		}
		return $data;
	}
	public function get_links($blogroll) {
		$f = new av25v_opml ( $blogroll );
		$OPML = $f->get ();
		$fixed = array ();
		foreach ( $OPML as $okey => $o ) {
			foreach ( ( array ) $o as $v ) {
				if (isset ( $v ['tag'] ) && $v ['tag'] == 'BODY') {
					foreach ( ( array ) $v as $v2 ) {
						if (isset ( $v2 ['tag'] ) && $v2 ['tag'] == 'OUTLINE') {
							$cat = $v2 ['attributes'] ['TITLE'];
							foreach ( ( array ) $v2 as $v3 ) {
								if (isset ( $v3 ['tag'] ) && $v3 ['tag'] == 'OUTLINE' && $v3 ['attributes'] ['TYPE'] == 'link') {
									$fixed_new = array ();
									$fixed_new ['title'] = $v3 ['attributes'] ['TEXT'];
									$fixed_new ['html_url'] = $v3 ['attributes'] ['HTMLURL'];
									$fixed_new ['xml_url'] = $v3 ['attributes'] ['XMLURL'];
									$fixed_new ['categories'] = $cat;
									if (array_key_exists ( $fixed_new ['title'], $fixed )) {
										$fixed [$fixed_new ['title']] ['categories'] .= ',' . $fixed_new ['categories'];
									} else {
										$fixed [$fixed_new ['title']] = $fixed_new;
									}
								}
							}
						}
					}
				}
			}
		}
		foreach ( $fixed as $key => $value ) {
			if (! in_array ( 'Sync', explode ( ',', $value ['categories'] ) )) {
				unset ( $fixed [$key] );
			}
		}
		return $fixed;
	}
	
	public function synclinks($url) {
		$links = $this->get_links ( $url );
		$bookmarks = get_bookmarks ( array ('hide_invisible' => 0, 'category_name' => 'Sync' ) );
		foreach ( ( array ) $bookmarks as $key => $value ) {
			if (! isset ( $links [$value->link_name] )) {
				wp_delete_link ( $value->link_id );
				unset ( $bookmarks [$key] );
			}
		}
		$bookmarks = get_bookmarks ( array ('hide_invisible' => 0 ) );
		$newbookmarks = array ();
		foreach ( $bookmarks as $bookmark ) {
			$newbookmarks [$bookmark->link_name] = $bookmark;
		}
		$bookmarks = $newbookmarks;
		$newbookmarks = null;
		$terms = array ();
		foreach ( ( array ) $links as $key => $value ) {
			$BLink = array ();
			$BLink ['link_name'] = $value ['title'];
			$BLink ['link_url'] = $value ['html_url'];
			$BLink ['link_rss'] = $value ['xml_url'];
			$categories = explode ( ',', $value ['categories'] );
			$BLink ['link_category'] = array ();
			foreach ( $categories as $category ) {
				$term = null;
				if (! isset ( $terms [$category] )) {
					$termObj = get_term_by ( 'name', $category, 'link_category' );
					if (! $termObj) {
						$BCat = array ();
						$BCat ['cat_name'] = $category;
						$termObj = wp_insert_term ( $category, 'link_category', $BCat );
					}
					$terms [$category] = $termObj->term_id;
				}
				$term = $terms [$category];
				$BLink ['link_category'] [] = $term;
			}
			if (isset ( $bookmarks [$value ['title']] )) {
				$BLink ['link_id'] = $bookmarks [$value ['title']]->link_id;
				wp_update_link ( $BLink );
			} else {
				wp_insert_link ( $BLink );
			}
		}
	}
	public function prepare_data($data) {
		$data=parent::prepare_data($data);
		$data['network'] ['last_updated'] = time ();
		return $data;
	}
	
}
