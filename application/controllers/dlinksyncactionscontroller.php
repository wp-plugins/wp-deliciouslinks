<?php
class dlinksyncactionscontroller extends wv23v_controller_action_action {
	public function shutdownAction ()
	{
		$this->settings()->update();
	}
}