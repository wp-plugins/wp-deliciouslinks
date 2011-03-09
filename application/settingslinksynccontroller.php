<?php
class settingslinksynccontroller extends wv23v_controller_action_adminmenu
{

	public function settingsAction ( $content )
	{
		$dataObj = new linksyncsettings ($this->application() );
		$this->view->data = $this->settings()->post ('network');
		if ($_SERVER ['REQUEST_METHOD'] == 'POST' && $this->view->data ['url'] != '')
		{
			$this->settings()->synclinks($this->view->data ['url']);
		}
		return $content.$this->updated();
	}
}
