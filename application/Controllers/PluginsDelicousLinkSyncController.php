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
class PluginsDelicousLinkSyncController extends d6vCode_Controller_Action_WP_AdminMenu {
	public function AboutAction($content)
	{
		return $content.$this->showAbout();
	}
}
		