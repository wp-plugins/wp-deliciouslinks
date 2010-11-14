<?php
if (! class_exists ( 'b3v_Base' ))
:
abstract class b3v_Base
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
		b3v_Debug::show($show);
	}
}


endif;