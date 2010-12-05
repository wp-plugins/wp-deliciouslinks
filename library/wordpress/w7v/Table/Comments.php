<?php
class w7v_Table_Comments extends w7v_Table {
	public function name() {
		return $this->wpdb ()->comments;
	}
}