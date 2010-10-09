<?php
/**
 * Create rewrite rules to point to Actions
 * A Class of exampleController will rewite http://localhost/example to the action class
 * http://localhost/example/action will map to the actionAction function inside the class.
 * If there is no function indexAction will be called.
 * @package Library
 * @subpackage Controller_Action_WP_Control
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
abstract class D5vCode_Controller_Action_WP_Control extends D5vCode_Controller_Action_WP_Abstract
{

	protected function selected_action ()
	{
		return $this->selected_action_page();
	}
	
	public function __construct ( $application )
	{
		$this->set_type ( self::WP_CONTROL );
		parent::__construct ( $application );
	}

	public function controller ()
	{
		$args = func_get_args ();
		$page = call_user_func_array ( array ( 
			'parent' , 'controller' 
		) , $args );
		$this->view->_e ( $page );
	}

	public function setup ()
	{
		add_action ( 'init' , array ( 
			$this , 'init' 
		) );
		add_action ( 'generate_rewrite_rules' , array ( 
			$this , 'generate_rewrite_rules' 
		) );
		add_filter ( 'query_vars' , array ( 
			$this , 'query_vars' 
		) );
		add_filter ( 'template_redirect' , array ( 
			$this , 'template_redirect' 
		) );
	}

	public function template_redirect ()
	{
		global $wp_query;
		if ($wp_query->get ( 'view' ))
		{
			$control = $this->get_controller();
			$class = new $control ( $this->application () );
			$class->controller ();
			die ();
		}
	}
	public function query_vars ( $qvars )
	{
		$qvars [] = 'view';
		return $qvars;
	}

	public function init ()
	{
		global $wp_rewrite;
		// uncomment for debugging
		//$wp_rewrite->flush_rules ();
		if (isset ( $wp_rewrite ))
		{
			$option = $this->get_page() . '_controls';
			$registered = get_option ( $option );
			$class = get_class ( $this );
			if ($registered !== $class)
			{
				$wp_rewrite->flush_rules ();
				update_option ( $option , $class );
			}
		}
	}
	private function get_page()
	{
		$class = get_class ( $this );
		$class = explode ( 'Controller' , $class );
		$class = $class [0];
		$class = str_replace('CSV','.csv',$class);
		return $class;
	}
	public function get_controller()
	{
		global $wp_query;
		$split = explode ( '/' , $wp_query->get ( 'view' ) );
		$control = $split [0] . 'Controller';
		$control = str_replace('.csv','CSV',$control);
		return $control;
	}
	public function generate_rewrite_rules ( $wp_rewrite )
	{
		$class = $this->get_page();
		$new_rules = array ();
		$new_rules [$class] = 'index.php?view=' . $class;
		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
	}
}
