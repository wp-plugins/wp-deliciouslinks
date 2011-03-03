<?php
class dlinksyncactionscontroller extends wv15v_controller_action_action {
	public function shutdownAction() {
		$dataObj = new dLinksyncdata ( );
		$data = $dataObj->get ();
		if ($data ['id'] != '') {
			$dataObj->update ();
		}
	}
}