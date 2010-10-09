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
class DLinkSyncFiltersController extends D5vCode_Controller_Action_WP_Filter
{

	public function plugin_action_linksAction ( $links , $file )
	{
		if ($file != plugin_basename ( $this->application ()->filename () ))
		{return $links;}
		$project = new D5vCode_Project ( $this->application () );
		$project = new D5vCode_Project ( $this->application () , $project->readme () );
		$project_readme = $project->blocks ();
		$project_readme = $project_readme[0]['tags'];
		array_unshift ( $links , '<b><a href="'.$project_readme['Donate link'].'">Donate</a></b>' , '<a href="plugins.php?page=D5vCode_Plugins_DelicousLinkSync">About</a>' , '<a href="options-general.php?page=D5vCode_Settings_DeliciousLinkSync">Settings</a>' );
		return $links;
	}
}
		