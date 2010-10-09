<?php
/**
 * Ties wordpress Admin menus to actions.
 * An Action class of ToolsExampleAction will create a menu page Example under the Tools admin menu.
 * Action functions as used to create menued content.
 * @package Library
 * @subpackage Controller_Action_WP_AdminMenu
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
if (! defined ( 'MARKDOWN_VERSION' ))
{
	require_once dirname( dirname (  dirname ( dirname ( dirname ( __FILE__ ) ) ) ) ) . '/external/PHP Markdown 1.0.1n/markdown.php';
}
abstract class D5vCode_Controller_Action_WP_AdminMenu extends D5vCode_Controller_Action_WP_Abstract
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
		$class = get_class ( $this );
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

	public function showAbout ( $file = null )
	{
		$project = new D5vCode_Project ( $this->application () );
		$project = new D5vCode_Project ( $this->application () , $project->readme () );
		$this->view->project_readme = $project->blocks ();
		$this->view->project_readme = $this->view->project_readme [0];
		$project = new D5vCode_Project ( $this->application () , $project->plugin () );
		$this->view->project_plugin = $project->blocks ();
		$this->view->project_plugin = $this->view->project_plugin [0];
		return $this->renderScript ( 'Common/About.phtml' );
	}
	protected static $sandbox_shown = false;

	public function setup ()
	{
		//$name = $this->application()->name();
		switch ($this->adminMenuTitle)
		{
			case 'Tools' :
				add_management_page ( $this->title , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'SuperAdmin' :
				add_submenu_page ( 'wpmu-admin.php' , $this->title , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Dashboard' :
				add_dashboard_page ( $this->title , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Posts' :
				add_posts_page ( $this->title , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Pages' :
				add_Pages_page ( $this->title , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Appearance' :
				add_theme_page ( $this->title , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Comments' :
				add_comments_page ( $this->title , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Media' :
				add_media_page ( $this->title , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Settings' :
				add_options_page ( $this->title , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Users' :
				add_media_page ( $this->title , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Links' :
				add_submenu_page ( 'users.php' , $this->title , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'Plugins' :
				add_submenu_page ( 'plugins.php' , $this->title , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
					$this , 'controller' 
				) );
				break;
			case 'SandBox' :
				if (D5vCode_Debug::dodebug ())
				{
					if ($this->title == $this->adminMenuTitle)
					{
						if (self::$sandbox_shown === false)
						{
							add_menu_page ( $this->adminMenuTitle , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle , array ( 
								$this , 'controller' 
							) );
							self::$sandbox_shown = true;
						}
					}
					else
					{
						add_submenu_page ( 'D5vCode_' . $this->adminMenuTitle , $this->adminMenuTitle , $this->title , 'administrator' , 'D5vCode_' . $this->adminMenuTitle . '_' . $this->title , array ( 
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
