<?php
/**
 * this is the base index.php that the .htaccess directs to, and shout be copied to the root of the app.
 * @package Library
 * @subpackage index.php
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
require_once dirname(__FILE__) . '/library/D5vCode/Application/Direct.php';
class D5vCode_Index extends D5vCode_Application_Direct
{
	public function __construct ($filename)
	{
		$this->set_name("Domain");
		$this->set_page('D5vCode/Private');
		parent::__construct($filename);
	}
}
new D5vCode_Index(__FILE__);
