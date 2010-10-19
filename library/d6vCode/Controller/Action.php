<?php
/**
 * abstract class of action, this is the control part of the view. 
 * @package Library
 * @subpackage Controller_Action
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
abstract class d6vCode_Controller_Action extends d6vCode_Base
{
	protected function marker($tag,$content)
	{
		$tagc = d6vCode_Tag::instance ();
		$matches =$tagc->get($tag, $content, true);
		foreach ((array) $matches as $match) {
			$new = call_user_func(array($this,$tag.'_Marker'),$match);
			$content = str_replace($match['match'], d6vCode_WP_Values::fixPostInsert($new), $content);
		}
		return $content;
	}
	private $_type = null;

	public function getType ()
	{
		return $this->_type;
	}

	protected function set_type ( $type )
	{
		$this->_type = $type;
	}
	const DIRECT = 1;
	protected $title = "";

	public function __construct ( $application )
	{
		parent::__construct ( $application );
		$this->getView ();
	}
	//--- request
	private $_request = null;

	private function set_request ()
	{
		if (null === $this->_request)
		{
			$this->_request = $this->application ()->frontcontroller ()->request ();
		}
	}

	public function request ()
	{
		$this->set_request ();
		return $this->_request;
	}
	protected $_actions = null;

	protected function actions ()
	{
		if (null === $this->_actions)
		{
			$this->_actions = $this->decode_actions ();
			uasort ( $this->_actions , array ( 
				$this , 'action_sort' 
			) );
		}
		return $this->_actions;
	}
	///---
	protected $view = null;

	protected function getView ()
	{
		$this->setView ();
		return $this->view;
	}

	protected function setView ()
	{
		if (null === $this->view)
		{
			$this->view = new d6vCode_View ( $this->application () );
		}
	}

	protected function decode_actions ()
	{
		$return = array ();
		$methods = get_class_methods ( $this );
		foreach ( $methods as $method )
		{
			$decoded = $this->decode_action ( $method );
			if ($decoded !== false)
			{
				$return [$method] = $decoded;
			}
		}
		return $return;
	}

	protected function action_sort ( $a , $b )
	{
		if ($a ['priority'] == $b ['priority'])
		{
			if (strtolower ( $a ['title'] ) == strtolower ( $b ['title'] ))
			{return 0;}
			return ( strtolower ( $a ['title'] ) < strtolower ( $b ['title'] ) ) ? - 1 : 1;
		}
		return ( $a ['priority'] < $b ['priority'] ) ? - 1 : 1;
	}

	protected function decode_action ( $method )
	{
		if (strpos ( $method , 'Action' ))
		{
			$return = array ();
			$return ['action'] = $method;
			$return ['level'] = 'administrator';
			$return ['title'] = '';
			$return ['raw_title'] = '';
			$return ['priority'] = 0;
			$info = explode ( 'Action' , $method );
			$return ['raw_title'] = $info [0];
			$info [0] = str_replace('_2E','.',$info [0]);
			$info [0] = urldecode ( str_replace ( '_' , '%' , $info [0] ) );
			$security = "";
			if ($info [1] == "")
			{
				$info [1] = 0;
			}
			else
			{
				$info2 = explode ( '__' , $info [1] );
				$info [1] = str_replace ( '_' , '-' , $info2 [0] );
				if (count($info2)>1 && $info2 [1] != "")
				{
					$security = $info2 [1];
				}
			}
			$return ['title'] = $info [0];
			$return ['priority'] = $info [1];
			return $return;
		}
		return false;
	}

	protected function hidden_check ()
	{
		return false;
	}
	protected $baseURL = "";

	protected function renderScript ( $script,$absPath=false )
	{
		if (strpos ( $script , 'Common/' ) !== 0 && $absPath==false)
		{
			$script = $this->controllerName () . DIRECTORY_SEPARATOR . $script;
		}
		return $this->view->render ( $script );
	}
	protected $template_folders = array();
	private function template_path($filename)
	{
		$paths = $this->application()->frontcontroller()->getViewPaths();
		$dirs = $this->application()->loader()->includepath($paths);
		$newdirs = array();
		foreach($this->template_folders as $tp)
		{
			foreach($dirs as $dir)
			{
				$newdirs[]= $dir.'/'.$tp;
			}
		}
		$fullpath =$this->application()->loader()->find_file($filename, true, $newdirs);
		return $fullpath;	
	}
	public function loadTemplate ($filename)
	{
		$return = null;
		$fullpath = $this->template_path($filename);
		if($fullpath!==false)
		{
			$pi = pathinfo($fullpath);
			switch(strtolower($pi['extension']))
			{
				case 'ini':
					$data = new d6vCode_Data_INI($this->application(),$fullpath);
					$return = $data->load();
					break;
				case 'csv':
					$data = new d6vCode_Data_CSV($this->application(),$fullpath,true);
					$return = $data->load();
					break;
				default:
				$return = $this->renderScript ($fullpath,true);		
			}
		}
		
		return $return;
	}
	public function loadTemplateSettings($filename)
	{
		$return = array();
		$fullpath = $this->template_path($filename);
		if($fullpath!==false)
		{
			$return = $this->renderScript ($fullpath,true);
		}
		
		return $return;
	}
	protected function preDispatch ()
	{}

	protected function Dispatch ()
	{}

	protected function postDispatch ()
	{}

	public function controller ()
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
		$return = call_user_func_array ( array ( 
			$this , 'preDispatch' 
		) , $this->view->args );
		if (null !== $return)
		{
			$this->view->args [0] = $return;
		}
		$return = call_user_func_array ( array ( 
			$this , 'Dispatch' 
		) , $this->view->args );
		if (null !== $return)
		{
			$this->view->args [0] = $return;
		}
		$return = call_user_func_array ( array ( 
			$this , 'postDispatch' 
		) , $this->view->args );
		if (null !== $return)
		{
			$this->view->args [0] = $return;
		}
		return $this->view->args [0];
	}

	private function controllerName ()
	{
		$class = get_class ( $this );
		return substr ( $class , 0 , - 10 );
	}

	protected function selected_action_page()
	{
		$split = explode ( '/' , $this->application ()->page () );
		if (count ( $split ) < 3 || $split [2] == "")
		{
			$act = 'indexAction';
		}
		else
		{
			$split [2] = urlencode(	$split [2] );
			$split [2] = str_replace('%','_',$split[2]);
			$split [2] = str_replace('.','_2E',$split [2]);
			$act = $split [2] . 'Action';
		}
		$actions = $this->actions ();
		if (array_key_exists ( $act , $actions ))
		{return $actions [$act];}
		return $actions ['errorAction'];
		
	}
	protected function selected_action ()
	{
		return $this->selected_action_page();
	}

	protected function csv_headers ( $file = null )
	{
		header ( "Content-type: application/csv" );
		if (null !== $file)
		{
			header ( "Content-Disposition: attachment; filename=$file.csv" );
		}
		header ( "Pragma: no-cache" );
		header ( "Expires: 0" );
	}

	protected function txt_headers ()
	{
		header ( 'Content-Type: text/plain' );
	}
}
