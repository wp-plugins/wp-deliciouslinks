<?php
class DLinkSyncActionsController extends w6v_Controller_Action_Action
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
		