<?php
/**
 * Ties wordpress actions to application actions IE
 * @example public function initAction() is the same as add_action('init,array($this,'initAction')
 * @package Library
 * @subpackage Controller_Action_WP_Action
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
abstract class d6vCode_Controller_Action_WP_Action extends d6vCode_Controller_Action_WP_Abstract
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
