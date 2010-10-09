<?php
/**
 * Default action controler to add wp actions
 * @package Library
 * @subpackage ActionController
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class ActionController extends D5vCode_Controller_Action_WP_Action
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
		return D5vCode_WP_Values::urlFromFileame($file);
	}
}
