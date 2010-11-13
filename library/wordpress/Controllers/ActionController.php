<?php
class ActionController extends w3v_Controller_Action_Action
{
	public function admin_headAction ()
	{
		$this->wp_headAction();
	}
	protected static $shownCommonHead = false;
	public function wp_headAction ()
	{
		if (! self::$shownCommonHead) {
			$this->view->url = $this->url($this->application()->loader()->find_file('public/style.css'));
			$this->view->_e($this->renderScript('head.phtml'));
			self::$shownCommonHead = true;
		}
	}
	protected function url ($file)
	{
		return w3v_Values::urlFromFileame($file);
	}
}
