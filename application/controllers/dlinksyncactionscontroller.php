<?php
class dlinksyncactionscontroller extends wv25v_controller_action_action {
	public function shutdownAction ()
	{
		$this->settings()->update();
	}
}