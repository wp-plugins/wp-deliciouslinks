<?php
/**
 * Filesystem handling routines
 * @package Library
 * @subpackage FS
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_FS extends D5vCode_Base
{
	private $dirHandle = null;
	private $path = "";
	public function path ($path = null)
	{
		$return = $this->path->value($path);
		return $return;
	}
	private $dir = null;
	const type_file = 1;
	const type_directory = 2;
	public function dir ($pattern = '*.*', $type = null, $recursionDepth = 0)
	{
		if (is_null($type)) {
			$type = self::type_directory + self::type_file;
		}
		if (is_null($this->dir)) {
			$this->dir = array();
			if ($this->openDir()) {
				while (($file = readdir($this->dirHandle)) !== false) {
					// exclude system pointer folders
					if (! in_array($file, array('.' , '..' , '.svn','.DS_Store'))) {
						$this->dir[] = $this->path() .DIRECTORY_SEPARATOR. $file;
					}
					//echo "filename: $file : filetype: " . filetype($this->path() . $file) . "\n";
				}
				$this->closeDir();
			}
		}
		$return = array();
		$start = 0;
		foreach ($this->dir as $entry) {
			if ((is_dir($entry) && ($type & self::type_directory)) || (! is_dir($entry) && ($type & self::type_file))) {
				if (fnmatch($pattern, $entry)) {
					$return[] = $entry;
				}
			}
			if (is_dir($entry)) {
				$child = new self($this->application(),$entry);
				if ($recursionDepth > 0 || is_null($recursionDepth)) {
					foreach($child->dir($pattern, $type, $recursionDepth - 1) as $sub)
					{
						$return[]=$sub;
					}
				}
			}
		}
		return $return;
	}
	public function relativeDir ($pattern = '*.*', $type = null, $recursionDepth = 0)
	{
		$dir = $this->dir($pattern, $type, $recursionDepth);
		$start = strlen($this->path()) + 1;
		$return = new D5vCode_Array();
		foreach ((array) $dir as $item) {
			$return[] = substr($item, $start);
		}
		return $return;
	}
	public function __construct ($application,$path = null)
	{
		parent::__construct($application);
		$this->path = new D5vCode_Type_String($path);
	}
	private function openDir ()
	{
		if (is_dir($this->path())) {
			$this->dirHandle = opendir($this->path());
		} else {
			$this->dirHandle = null;
		}
		return (! is_null($this->dirHandle));
	}
	private function closeDir ()
	{
		if (! is_null($this->dirHandle)) {
			closedir($this->dirHandle);
			$this->dirHandle = null;
		}
	}
}
