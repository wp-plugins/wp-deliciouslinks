<?php
/**
 * Based on the idea of Zend_Action, is an inter face between the the controller and view, Actions are called priory to attempting to load phtml files from the corresponding Scripts folder
 * @package Library
 * @subpackage Controller_Action_Direct
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
abstract class d6vCode_Controller_Action_Direct extends d6vCode_Controller_Action
{
	public function errorAction ()
	{
		header ( "HTTP/1.0 404 Not Found" );
		$page = $this->renderScript('Common/error.phtml');
		$this->view->_e($page);
	}
	
	public function __construct ( $application )
	{
		$this->set_type ( self::DIRECT );
		parent::__construct ( $application );
	}
	public function controller ()
	{
		$this->view->selected = $this->selected_action ();
		$args = func_get_args ();
		$page = call_user_func_array ( array ( 
			'parent' , 'controller' 
		) , $args );
		$this->view->_e ( $page );
	}

	protected function Dispatch ()
	{
		$this->view->args = array ();
		if (count ( func_get_args () ) > 0)
		{
			$this->view->args = func_get_args ();
		}
		else
		{
			$this->view->args [] = null;
		}
		if (is_array ( $this->view->selected ))
		{
			$return = call_user_func_array ( array ( 
				$this , $this->view->selected ['action'] 
			) , $this->view->args );
			if (null !== $return)
			{
				$this->view->args [0] = $return;
			}
		}
		$return = $this->renderScript ( $this->view->selected ['raw_title'] . '.phtml' );
		if (null !== $return)
		{
			$this->view->args [0] .= $return;
		}
		return $this->view->args [0];
	}
}
