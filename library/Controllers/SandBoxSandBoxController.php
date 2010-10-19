<?php
/**
 * add an admin menu action 'sandbox' by default to all apps
 * @package Library
 * @subpackage SandBoxSandBoxController
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class SandBoxSandBoxController extends d6vCode_Controller_Action_WP_AdminMenu
{
	public function FilesAction ( $content )
	{
		$page = "";
		$library = 0;
		$external = 0;
		$project = 0;
		$pclass = new d6vCode_Project ($this->application());
		$phpdoc = new d6vCode_Project ( $this->application () , $pclass->plugin () );
		$plugin = $phpdoc->blocks ();
		$plugin = $plugin [0];
		$phpdoc = new d6vCode_Project ( $this->application () , $pclass->readme () );
		$readme = $phpdoc->blocks ();
		$readme = $readme [0];
		$project_ver = 0;
		$library_ver = 0;
		$short_desc = array ();
		$sub_package = array ();
		foreach ( $pclass->files () as $file )
		{
			$$file ['type'] ++;
			if ($file ['isdoc'] && $file ['type'] != 'external')
			{
				$phpdoc = new d6vCode_Project ( $this->application () , $file ['file'] );
				$info = $phpdoc->blocks ();
				$error = array ();
				if (count ( $info ) > 0)
				{
					$info = $info [0];
					if (array_key_exists ( 'readme' , $info ))
					{
						if (array_key_exists ( 'svn' , $info ))
						{
							switch ($file ['type'])
							{
								case 'library' :
									$library_ver = max ( $library_ver , $info ['svn'] ['LastChangedRevision'] );
									break;
								case 'project' :
									$project_ver = max ( $project_ver , $info ['svn'] ['LastChangedRevision'] );
									break;
							}
							if (! isset ( $info ['readme'] ['name'] ) || $info ['readme'] ['name'] != $package)
							{
								$error [] = "readme:name should be `$package`";
							}
						}
					}
					if (! array_key_exists ( 'short desc' , $info ))
					{
						$error [] = "`short desc` is missing";
					}
					else
					{
						if (isset ( $short_desc [$info ['short desc']] ))
						{
							$error [] = "`short desc` is the same as " . $short_desc [$info ['short desc']];
						}
						else
						{
							$short_desc [$info ['short desc']] = $file ['file'];
						}
					}
					if (array_key_exists ( 'svn' , $info ))
					{
						if (! isset ( $info ['svn'] ['HeadURL'] ))
						{
							$error [] = "SVN:HeadURL is missing";
						}
						if (! isset ( $info ['svn'] ['LastChangedDate'] ))
						{
							$error [] = "SVN:LastChangedDate is missing";
						}
						if (! isset ( $info ['svn'] ['LastChangedRevision'] ))
						{
							$error [] = "SVN:LastChangedRevision is missing";
						}
						else
						{
							switch ($file ['type'])
							{
								case 'library' :
									$library_ver = max ( $library_ver , $info ['svn'] ['LastChangedRevision'] );
									break;
								case 'project' :
									$project_ver = max ( $project_ver , $info ['svn'] ['LastChangedRevision'] );
									break;
							}
						}
						if (! isset ( $info ['svn'] ['LastChangedBy'] ))
						{
							$error [] = "SVN:LastChangedBy is missing";
						}
						if (array_key_exists ( 'doc' , $info ))
						{
							switch ($file ['type'])
							{
								case 'library' :
									$package = 'Library';
									break;
								case 'project' :
									$package = $plugin ['tags'] ['Plugin Name'];
									break;
							}
							if (! array_key_exists ( 'subpackage' , $info ['doc'] ))
							{
								$error [] = "`DOC:subpackage` is missing";
							}
							else
							{
								if (isset ( $sub_package [$info ['doc'] ['subpackage']] ))
								{
									$error [] = "`subpackage` is the same as " . $sub_package [$info ['doc'] ['subpackage']];
								}
								else
								{
									$sub_package [$info ['doc'] ['subpackage']] = $file ['file'];
								}
							}
							if (! isset ( $info ['doc'] ['copyright'] ) || $info ['doc'] ['copyright'] != 'DCoda Ltd')
							{
								$error [] = "DOC:copyright should be `DCoda Ltd`";
							}
							if (! isset ( $info ['doc'] ['package'] ) || $info ['doc'] ['package'] != $package)
							{
								$error [] = "DOC:package should be `$package`";
							}
							if (! isset ( $info ['doc'] ['author'] ) || $info ['doc'] ['author'] != 'DCoda Ltd')
							{
								$error [] = "DOC:author should be `DCoda Ltd`";
							}
							if (! isset ( $info ['doc'] ['license'] ) || $info ['doc'] ['license'] != 'http://www.gnu.org/licenses/gpl.txt')
							{
								$error [] = "DOC:license should be `http://www.gnu.org/licenses/gpl.txt`";
							}
						}
						else
						{
							$error [] = ' is missing php docs';
						}
					}
					else
					{
						$error [] = ' is missing svn info';
					}
				}
				else
				{
					$error [] = 'has no comment blocks';
				}
				if (count ( $error ) > 0)
				{
					$page .= sprintf ( '<strong>%s</strong> %s<br/>' , $file ['file'] , implode ( ', ' , $error ) );
				}
			}
		}
		$info = sprintf ( '<h1>project:revision %d %d files , Library:revision %d %d , External:%d</h1>' , $project_ver , $project , $library_ver , $library , $external );
		return $content . $info . $page;
	}



	//--- DCoda_20Plugin_20Info
	private function DCoda_20Plugin_20InfoAction ()
	{
		if (array_key_exists ( 'submit' , $_POST ))
		{
			foreach ( $this->plugins () as $project )
			{
				$this->update ( $project );
			}
		}
		$this->view->plugins = $this->plugins ();
		return;
	}

	public function update ( $project )
	{
		$filename = $project ['info']->filename ();
		if (file_exists ( $filename ))
		{
			$file = file_get_contents ( $filename );
			$pattern = '|Version:(.*)|i';
			preg_match_all ( $pattern , $file , $matches , PREG_SET_ORDER );
			foreach ( $matches as $match )
			{
				$o = array ();
				$o ['line'] = $match [0];
				$o ['newline'] = explode ( '.' , $match [0] );
				$o ['newline'] [count ( $o ['newline'] ) - 1] = $project ['svn']->lastchangedrevision ();
				$o ['newline'] = implode ( '.' , $o ['newline'] );
				$file = str_replace ( $o ['line'] , $o ['newline'] , $file );
				//$this->view->_p($o);
				file_put_contents ( $filename , $file );
			}
			return;
		}
	}

	protected function plugins ()
	{
		$content = array ( 
			'plugins' , 'mu-plugins' , 'themes' 
		);
		$this->plugins = array ();
		$this->view->filenames = array ();
		foreach ( $content as $dir )
		{
			$projectsDir = new d6vCode_FS ( $this->application () , dirname ( dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) ) . '/' . $dir . '/' );
			$php = $projectsDir->dir ( "*.php" , d6vCode_FS::type_file , 1 );
			foreach ( $this->application ()->applications () as $application )
			{
				$this->view->filenames [] = $application->filename ();
			}
			foreach ( $php as $file )
			{
				$p = new d6vCode_Application_Info ( $this->application () , $file );
				if ($p->author () == "DCoda Ltd")
				{
					$pmore ['info'] = $p;
					$pmore ['svn'] = new d6vCode_Application_Info_SVN ( $this->application () , $file );
					$pmore ['readme'] = new d6vCode_Application_info_Readme ( $this->application () , $file );
					$this->plugins [] = $pmore;
				}
			}
		}
		return $this->plugins;
	}

	//---
	//--- unserialize
	public function UnserializeAction ()
	{}

	public function MySQLAction ()
	{
		$mysql = new d6vCode_WP_Users ( );
	}

	//--- test
	public function testAction ()
	{
		$test = new d6vCode_Gzip ( $this->application () );
		$test->make_file ();
	}
}
