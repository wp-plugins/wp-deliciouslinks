<?php
/**
 * Abstract method, modifyng the Controller action for use in a WordPress envirnment.
 * @package Library
 * @subpackage Controller_Action_WP_Abstract
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
abstract class D5vCode_Controller_Action_WP_Abstract extends D5vCode_Controller_Action
{

	protected function basic_auth ()
	{
		$credentials = array ();
		if(array_key_exists('PHP_AUTH_USER',$_SERVER) && array_key_exists('PHP_AUTH_PW',$_SERVER))
		{
			$credentials ['user_login'] = $_SERVER ['PHP_AUTH_USER'];
			$credentials ['user_password'] = $_SERVER ['PHP_AUTH_PW'];
		}
		$user = wp_signon ( $credentials );
		if (is_wp_error ( $user ))
		{
			header ( 'WWW-Authenticate: Basic realm="' . $_SERVER ['SERVER_NAME'] . '"' );
			header ( 'HTTP/1.0 401 Unauthorized' );
			die ();
		} // if
	}

	protected function superadmin_check ()
	{
		return is_super_admin ();
	}
	const WP_FILTER = 2;
	const WP_ACTION = 4;
	const WP_CONTROL = 8;
	const WP_DASHBOARD = 16;

	public function __construct ( $application )
	{
		parent::__construct ( $application );
	}

	protected function setView ()
	{
		$this->view = new D5vCode_WP_View ( $this->application () );
	}

	public function controller ()
	{
		$this->view->title = $this->title;
		$this->view->options = $this->actions ();
		$this->view->selected = $this->selected_action ();
		$args = func_get_args ();
		return call_user_func_array ( array ( 
			'parent' , 'controller' 
		) , $args );
	}

	protected function selected_action_wp()
	{
		
		$filter = explode ( '_' , current_filter () );
		if (count ( $filter ) > 1 && $filter [1] == 'page')
		{
			$pages = $this->subpages ();
			if (empty ( $pages ['page2'] ))
			{
				foreach ( $this->actions () as $r )
				{
					return $r;
				}
			}
			else
			{
				foreach ( $this->actions () as $r )
				{
					if ($pages ['page2'] == D5vCode_Type_String::staticSafe ( $r ['title'] ))
					{return $r;}
				}
			}
		}
		else
		{
			foreach ( ( array ) $this->actions () as $action )
			{
				if (strpos ( $action ['raw_title'] , current_filter () ) === 0)
				{return $action;}
			}
		}
		return null	;
	}
	protected function selected_action ()
	{
		return $this->selected_action_wp();
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

	protected function subpages ()
	{
		$pages = array ();
		foreach ( ( array ) $_GET as $key => $value )
		{
			if (D5vCode_Type_String::staticStartsWith ( $key , 'page' ))
			{
				$pages [$key] = $value;
			}
		}
		ksort ( $pages );
		return $pages;
	}
}
