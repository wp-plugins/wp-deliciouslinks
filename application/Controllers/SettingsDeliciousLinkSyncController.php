<?php
/**
 * adds admin pages for censor under the plugin option
 * @package RSSSticky
 * @subpackage PluginsRSSInjectionController
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class SettingsDeliciousLinkSyncController extends d6vCode_Controller_Action_WP_AdminMenu
{

	public function SettingsAction ( $content )
	{
		$dataObj = new DLinkSyncData ( );
		$this->view->data = $dataObj->post ();
		if ($_SERVER ['REQUEST_METHOD'] == 'POST' && $this->view->data ['id'] != '')
		{
			$dataObj->synclinks($this->view->data ['id'],$this->view->data ['password']);
		}
		return $content;
	}
}
