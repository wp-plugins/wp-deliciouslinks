<?php
abstract class w3v_Controller_Action_AdminMenu extends w3v_Controller_Action_Abstract
{
	protected $adminMenuTitle = "";

	public function __construct ( $application )
	{
		parent::__construct ( $application );
		$this->set_type ( self::WP_DASHBOARD );
		$this->decode_controller ();
	}
	protected function decode_controller ()
	{
		$menus = array ( 
			'SandBox' , 'Tools' , 'SuperAdmin' , 'Dashboard' , 'Posts' , 'Pages' , 'Appearance' , 'Comments' , 'Media' , 'Links' , 'Plugins' , 'Users' , 'Settings' 
		);
		//$class = get_class ( $this );
		$class = $this->controllerName();
		foreach ( $menus as $key )
		{
			if (strpos ( $class , $key ) === 0)
			{
				$this->adminMenuTitle = $key;
				$this->title = substr ( $class , strlen ( $this->adminMenuTitle ) );
				$this->title = explode ( 'Controller' , $this->title );
				$this->title = $this->title [0];
		break;
			}
		}
	}

	public function controller ()
	{
		$args = func_get_args ();
		$this->view->_e ( call_user_func_array ( array ( 
			'parent' , 'controller' 
		) , $args ) );
	}
	public function AboutActionMeta($return)
	{
		$return['priority'] = 99;
		if(strpos($this->controllerName(),'Settings')!==0)
		{
			$return['title'] = '';	
		}
		return $return;
	}
	public function AboutAction ( $content )
	{
		$this->view->options=$this->application()->Settings()->get_full(true);
		//unset($this->view->options['sections']['Installation']);
		unset($this->view->options['sections']['Screenshots']);
		unset($this->view->options['sections']['Upgrade Notice']);
		return $content.$this->renderScript ( 'Common/About.phtml' );
	}
	protected static $sandbox_shown = false;

	public function setup ()
	{
		//$name = $this->application()->name();
		switch ($this->adminMenuTitle)
		{
			case 'Tools' :
				add_management_page ( $this->title , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'SuperAdmin' :
				add_submenu_page ( 'wpmu-admin.php' , $this->title , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Dashboard' :
				add_dashboard_page ( $this->title , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Posts' :
				add_posts_page ( $this->title , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Pages' :
				add_Pages_page ( $this->title , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Appearance' :
				add_theme_page ( $this->title , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Comments' :
				add_comments_page ( $this->title , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Media' :
				add_media_page ( $this->title , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Settings' :
				add_options_page ( $this->title , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Users' :
				add_media_page ( $this->title , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Links' :
				add_submenu_page ( 'users.php' , $this->title , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Plugins' :
				add_submenu_page ( 'plugins.php' , $this->title , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'SandBox' :
				if (b3v_Debug::dodebug ())
				{
					if ($this->title == $this->adminMenuTitle)
					{
						if (self::$sandbox_shown === false)
						{
							add_menu_page ( $this->adminMenuTitle , $this->title , 'administrator' ,  $this->adminMenuTitle , array ( 
								$this , 'controller' 
							) );
							self::$sandbox_shown = true;
						}
					}
					else
					{
						add_submenu_page (  $this->adminMenuTitle , $this->adminMenuTitle , $this->title , 'administrator' ,  $this->adminMenuTitle . '_' . $this->title , array ( 
							$this , 'controller' 
						) );
					}
				}
				break;
		}
	}

	protected function preDispatch ()
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
		$return = $this->renderScript ( 'Common/header.phtml' );
		if (null !== $return)
		{
			$this->view->args [0] .= $return;
		}
		$return = ( $this->menu () );
		if (null !== $return)
		{
			$this->view->args [0] .= $return;
		}
		return $this->view->args [0];
	}

	protected function postDispatch ()
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
		$return = $this->renderScript ( 'Common/footer.phtml' );
		if (null !== $return)
		{
			$this->view->args [0] .= $return;
		}
		return $this->view->args [0];
	}

	public function menu ()
	{
		$this->view->menu = array ();
		foreach ( $this->view->options as $option )
		{
			$show = true;
			if (isset ( $option ['security'] ) && $option ['security'] != "")
			{
				$function = $option ['security'] . '_check';
				$show = $this->$function ();
			}
			if ($show && $option ['action'] != 'errorAction')
			{
				$this->view->menu [] = $option;
			}
		}
		if ($this->view->title != $this->view->selected ['title'])
		{
			$this->view->title .= ' : ' . $this->view->selected ['title'];
		}
		$request_uri = explode ( '?' , $_SERVER ['REQUEST_URI'] );
		$request_uri = $request_uri [0];
		$this->view->baseUrl = 'http://' . $_SERVER ['HTTP_HOST'] . $request_uri . '?page=' . $_GET ['page'];
		return $this->renderScript ( 'Common/menu.phtml' );
	}
}
