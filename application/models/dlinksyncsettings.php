<?php
class dlinksyncsettings extends wv23v_settings {
	public function __construct($application)
	{
		parent::__construct($application);
		$this->legacy_move('delicious_linksync','network');
		$this->legacy_move('DLinkSync','network');
	}
	public function prepare_data($data) {
		$data=parent::prepare_data($data);
		$data['network'] ['last_updated'] = time ();
		return $data;
	}
	public function update() {
		$data = $this->all();
		if(!empty($data['network']['last_updated']) && !empty($data['network']['id']) && !empty($data['network']['password']))
		{
			if ((time () - $data ['network']['last_updated']) / (60 * 60 * 24) >= $data ['network']['between']) {
				$this->synclinks ( $data ['network']['id'],$data['network']['password'] );
				$data = $this->set ( $data,'network' );
			}
		}
	}
	

 	public function synclinks($id, $password) {
		$delObj = new av23v_Delicious ( );
		$delObj->logonBasic ( $id, $password );
		$links = $delObj->get_all_posts ( 'Sync' );
		$newlinks = array ();
		foreach ( $links as $link ) {
			$newlink = array ();
			$newlink ['title'] = $link ['description'];
			$newlink ['html_url'] = $link ['href'];
			$newlink ['xml_url'] = '';
			$newlink ['categories'] = explode ( ' ', $link ['tag'] );
			foreach ( $newlink ['categories'] as $key => $value ) {
				$newlink ['categories'] [$key] = ucwords ( str_replace ( '-', ' ', str_replace ( '_', ' ', $value ) ) );
			}
			$newlink ['categories'] = implode ( ',', $newlink ['categories'] );
			$newlinks [$link ['description']] = $newlink;
		}
		$links = $newlinks;
		$newlinks = null;
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
						$terms [$category] = $termObj ['term_id'];
					} else {
						$terms [$category] = $termObj->term_id;
					}
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
}