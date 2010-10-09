<?php
/**
 * interface to information about the project
 * @package Library
 * @subpackage Project
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_Project extends D5vCode_Base
{
	//--- filename
	private $_filename = null;

	public function filename ()
	{
		return $this->_filename;
	}

	protected function set_filename ( $filename )
	{
		$this->_filename = $filename;
	}
	private $_blocks = null;
	private $_matches = null;

	public function blocks ()
	{
		$this->info ();
		return $this->_blocks;
	}

	protected function add_block ( $block  )
	{
		if (null === $this->_blocks)
		{
			$this->_blocks = array ();
		}
		$block = trim ( $block );
		$blocks = explode ( "\n" , $block );
		$block = "";
		foreach ( $blocks as $b )
		{
			$block .= ltrim ( $b , '*;# ' ) . "\n";
		}
		$newblock=array();
		$newblock ['svn']= $this->find ( $block , '/\$(.*?):(.*)\$/i' );
		$newblock ['doc'] = $this->find ( $block , '/@(.*?) (.*)/i' );
		$newblock ['readme'] = $this->findReadme ( $block );
		$newblock ['tags'] = $this->findTags ( $block );
		$block=explode("\n\n",trim($block),2);
		if(trim($block[0])!="")
		{
			$newblock['short desc'] = trim($block[0]);
		}
		if(count($block)>1 && trim($block[1])!="")
		{
			$newblock['desc'] = trim($block[1]);
		}
		$this->clean ( $newblock );
		$this->_blocks[] = $newblock;
	}

	public function clean ( &$array )
	{
		foreach ( ( array ) $array as $key => $value )
		{
			if (is_array ( $value ))
			{
				switch (count ( $value ))
				{
					case 0 :
						unset ( $array [$key] );
						break;
					case 1 :
						break;
					default :
						$this->clean ( $array [$key] );
				}
			}
			else
			{
				if (trim ( $value ) == '')
				{
					unset ( $array [$key] );
				}
			}
		}
	}

	public function findTags ( &$block )
	{
		$return = array ();
		$tags = array ( 
			'contributors' , 'donate link' , 'tags' , 'requires at least' , 'tested up to' , 'stable tag' , 'plugin name' , 'plugin uri' , 'description' , 'author' , 'author uri' , 'version' 
		);
		$explode_tag = array ( 
			'contributors' , 'tags' 
		);
		$pattern = '|(.*?):(.*)|i';
		preg_match_all ( $pattern , $block , $matches , PREG_SET_ORDER );
		foreach ( $matches as $match )
		{
			if (in_array ( strtolower ( $match [1] ) , $tags ))
			{
				if (in_array ( strtolower ( $match [1] ) , $explode_tag ))
				{
					$value = explode ( ',' , $match [2] );
					foreach ( $value as $key => $newvalue )
					{
						$value [$key] = trim ( $newvalue );
					}
				}
				else
				{
					$value = trim ( $match [2] );
				}
				$return [trim ( $match [1] )] = $value;
				$block = str_replace ( $match [0] , '' , $block );
			}
		}
		return $return;
	}

	public function find ( &$block , $pattern )
	{
		$return = array ();
		preg_match_all ( $pattern , $block , $matches , PREG_SET_ORDER );
		foreach ( $matches as $match )
		{
			$return [trim ( $match [1] )] = trim ( $match [2] );
			$block = str_replace ( $match [0] , '' , $block );
		}
		return $return;
	}

	public function findReadme ( &$block )
	{
		$return = array ();
		$pattern = '/^=== (.*) ===\n([\w\W]*)$/Ui';
		preg_match_all ( $pattern , $block , $matches , PREG_SET_ORDER );
		foreach ( $matches as $match )
		{
			$return ['name'] = $match ['1'];
			$return ['content'] = $this->findReadmeSection ( $match ['2'] );
			$block = trim ( $match ['2'] );
		}
		return $return;
	}

	public function findReadmeSection ( &$section )
	{
		$return = array ();
		$section .= "\n== ";
		$pattern = '/ (.*) ==\n([\w\W]*)==/Ui';
		preg_match_all ( $pattern , $section , $matches , PREG_SET_ORDER );
		foreach ( $matches as $match )
		{
			$isfaq = $this->faq ( trim ( $match ['2'] ) );
			if (is_array ( $isfaq ))
			{
				$return [trim ( $match ['1'] )] = $isfaq;
			}
			else
			{
				$return [trim ( $match ['1'] )] = str_replace ( '(c)' , '&copy;' ,   trim ( $match ['2'] , "\n "  ) );
			}
			$section = str_replace ( $match [0] , '' , $section );
		}
		$section = str_replace ( "\n== " , '' , $section );
		return $return;
	}

	private function faq ( $string )
	{
		$string .= "\n= ";
		$pattern = '/ (.*) =\n([\w\W]*)=/Ui';
		preg_match_all ( $pattern , $string , $matches , PREG_SET_ORDER );
		$return = null;
		foreach ( ( array ) $matches as $match )
		{
			if (null === $return)
			{
				$return = array ();
			}
			$return [trim ( $match [1] )] =   trim ( $match [2]  );
		}
		return $return;
	}
	//--- info
	private $_info = false;

	protected function info ()
	{
		if (! $this->_info)
		{
			$file = file_get_contents ( $this->filename () );
			$pattern = '/\/\*\*([\w\W]*)\*\/|##([\w\W]*)##|;;([\w\W]*);;|^=== (.*) ===\n([\w\W]*)$/Ui';
			preg_match_all ( $pattern , $file , $matches , PREG_SET_ORDER );
			foreach ( ( array ) $matches as $match )
			{
				for($block = 1 ; $block < 5 ; $block ++)
				{
					if (isset ( $match [$block] ) && $match [$block] != '')
					{
						if ($block == 4)
						{
							$mblock = '=== ' . $match [$block] . " ===\n" . $match [$block + 1];
						}
						else
						{
							$mblock = $match [$block];
						}
						$this->add_block ( $mblock  );
					}
				}
			}
			$this->_info = true;
			return $file;
		}
	}

	public function __construct ( $application , $filename = null )
	{
		parent::__construct ( $application );
		if (null === $filename)
		{
			$this->set_filename ( $this->application ()->filename () );
		}
		else
		{
			$this->set_filename ( $filename );
		}
	}
	
	public function project_files ()
	{
		$fs = new D5vCode_FS ( $this->application () , $this->home () );
		$files = $fs->dir ( "*.*" , D5vCode_FS::type_file , 999 );
		//return array('/volumes/Code/www/local/wp-content/plugins/sandbox/library/D5vCode/Loader.php');
		return $files;
	}

	public function files ()
	{
		$non_doc = $this->non_doc ();
		$library = $this->library ();
		$external = $this->external ();
		foreach ( $this->project_files () as $file )
		{
			$pi = pathinfo ( $file );
			$return [$file] ['file'] = $file;
			$return [$file] ['isdoc'] = ! in_array ( strtolower ( $pi ['extension'] ) , $non_doc );
			if (strpos ( $file , $external ) === 0)
			{
				$return [$file] ['type'] = 'external';
			}
			else if (strpos ( $file , $library ) === 0)
			{
				$return [$file] ['type'] = 'library';
			}
			else
			{
				$return [$file] ['type'] = 'project';
			}
		}
		return $return;
	}

	public function non_doc ()
	{
		return array ( 
			'gif' , 'png' 
		);
	}

	public function home ()
	{
		return dirname ( $this->application ()->filename () );
	}

	public function external ()
	{
		return $this->library () . '/external';
	}

	public function library ()
	{
		return $this->home () . '/library';
	}

	public function plugin ()
	{
		return $this->application ()->filename ();
	}

	public function readme ()
	{
		return $this->home () . '/readme/readme.txt';
	}
}
