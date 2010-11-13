<?php
/**
 * adds WordPress filters for injection
 * @package RSSINjection
 * @subpackage RSSInjectionFilterController
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class DLinkSyncActionsController extends d6vCode_Controller_Action_WP_Action
{

	public function shutdownAction ()
	{
		$dataObj = new DLinkSyncData ( );
		$data = $dataObj->get ();
		if ($data ['id'] != '')
		{
			$dataObj->update();
		}
	}
}
		