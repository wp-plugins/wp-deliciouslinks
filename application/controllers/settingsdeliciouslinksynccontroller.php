<?php
class settingsdeliciouslinksynccontroller extends wv25v_controller_action_adminmenu {
	public function settingsActionMeta($return) {
		$return ['title'] = 'Settings';
		return $return;
	}
	public function settingsAction($content) {
		$this->view->data = $this->settings()->post ('network');
//		if ($_SERVER ['REQUEST_METHOD'] == 'POST' && $this->view->data ['id'] != '') {
//			$dataObj->synclinks ( $this->view->data ['id'], $this->view->data ['password'] );
//		}
		return $content . $this->updated ();
	}
}
