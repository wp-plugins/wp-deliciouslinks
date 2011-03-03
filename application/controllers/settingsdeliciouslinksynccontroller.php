<?php
class settingsdeliciouslinksynccontroller extends wv15v_controller_action_adminmenu {
	public function settingsActionMeta($return) {
		$return ['title'] = 'Settings';
		return $return;
	}
	public function settingsAction($content) {
		$dataObj = new dlinksyncdata ( );
		$this->view->data = $dataObj->post ();
		if ($_SERVER ['REQUEST_METHOD'] == 'POST' && $this->view->data ['id'] != '') {
			$dataObj->synclinks ( $this->view->data ['id'], $this->view->data ['password'] );
		}
		return $content . $this->updated ();
	}
}
