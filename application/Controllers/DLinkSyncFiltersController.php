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
class DLinkSyncFiltersController extends d6vCode_Controller_Action_WP_Filter
{

	public function plugin_action_linksAction ( $links , $file )
	{
		if ($file != plugin_basename ( $this->application ()->filename () ))
		{return $links;}
		$project = new d6vCode_Project ( $this->application () );
		$project = new d6vCode_Project ( $this->application () , $project->readme () );
		$project_readme = $project->blocks ();
		$project_readme = $project_readme[0]['tags'];
		array_unshift ( $links , '<a href="'.$project_readme['Donate link'].'" title = "Help support the development of this plugin"><img alt = "Donate" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif"/></a>' , '<a href="plugins.php?page=d6vCode_Plugins_DelicousLinkSync">About</a>' , '<a href="options-general.php?page=d6vCode_Settings_DeliciousLinkSync">Settings</a>' );
		return $links;
	}
}
		