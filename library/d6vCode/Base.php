<?php
/**
 * The base of all classes, adding feature that are required in most classes
 * @package Library
 * @subpackage Base
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
if (! class_exists ( 'd6vCode_Base' ))
:
abstract class d6vCode_Base
{
	public function __construct ( $application = null )
	{
		$this->set_application ( $application );
	}
	private $_application = null;

	public function set_application ( $application = null )
	{
		$this->_application = $application;
	}

	public function application ()
	{
		if (null === $this->_application)
		{throw new Exception ( "Application not set \n" );}
		return $this->_application;
	}
	public function debug($show)
	{
		d6vCode_Debug::show($show);
	}
}


endif;