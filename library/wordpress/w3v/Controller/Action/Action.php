<?php
abstract class w3v_Controller_Action_Action extends w3v_Controller_Action_Abstract
{

	public function __construct ( $application )
	{
		$this->set_type ( self::WP_ACTION );
		parent::__construct ( $application );
	}

	public function setup ()
	{
		foreach ( ( array ) $this->actions () as $action )
		{
			add_action ( $action ['raw_title'] , array ( 
				$this , "controller" 
			) , $action ['priority'] );
		}
	}
}
