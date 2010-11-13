<?php
class DLinkSyncData extends b3v_Base {
	
	private function key() {
		return 'DLinkSync';
	}
	public function update() {
		$values = $this->get ();
		if ($values ['between'] != '' && (time () - $values ['last_updated']) / (60 * 60 * 24) >= $values ['between']) //if ($values['between'] !='' && (time()-$values['last_updated'])/(1)>=$values['between'])
		{
			$this->synclinks ( $values ['id'], $values ['password'] );
			$values = $this->set ( $values );
		}
		return $values;
	}
	
	public function post() {
		if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			$this->set ( $_POST );
		}
		return $this->get ();
	}
	
	public function set($values) {
		if ($this->global) {
			$data = new w3v_Table_SiteMeta ( );
		} else {
			$data = new w3v_Table_Options ( );
		}
		$values ['last_updated'] = time ();
		$data->set ( $this->key (), $values );
		return $this->get ();
	}
	
	public function defaults() {
		return array ('id' => '', 'password' => '', 'between' => '', 'last_updated' => '' );
	}
	
	public function get() {
		if ($this->global) {
			$data = new w3v_Table_SiteMeta ( );
			$settings = $data->get ( $this->key () );
		} else {
			$data = new w3v_Table_Options ( );
			$settings = $data->get ( $this->key () );
		}
		if (! is_array ( $settings )) {
			$settings = $this->defaults ();
		}
		return $settings;
	}
	
	public function __construct($global = false) {
		$this->global = $global;
		parent::__construct ();
	}
	
	public function synclinks($id, $password) {
		$delObj = new fv1_Delicious ( );
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
