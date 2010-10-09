<?php
/**
 * Quick routines to output results to aid with debuging
 * @package Library
 * @subpackage Debug
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_Debug extends D5vCode_Base
{
	//--- dodebug
	public static function dodebug()
	{
		return getenv('DEBUG') == 'yes';
	}
	//---
	const header = "<html>
	<head>
	<title>D5vCode Debug</title>
	<style>
		body {
			margin:0;
			padding:0;
		}
		table {
			border-collapse: collapse;
			width:100%;
		}
		thead {
			background-color:darkblue;
			color:white;
		}
	</style>
	</head>
<body>";
	const footer = "</body></html>";
	public static function show($value)
	{
		echo "<pre>" . print_r ( $value , true ) . "</pre><br/>";
		 
	}
	public static function log ($value = null, $title = '')
	{
		if (! self::doDebug()) {
			return;
		}
		$fp = fopen(self::filename(), 'a+');
		fwrite($fp, self::output_html($value, $title));
		fclose($fp);
	}
	public static function clearLog ()
	{
		$fp = @fopen(self::filename(), 'w');
		if ($fp !== false) {
			fclose($fp);
		}
		$fp = @fopen(self::filename('txt'), 'w');
		if ($fp !== false) {
			fclose($fp);
		}
		self::log(null, 'Log Started');
	}
	private static function filename ($type = 'html')
	{
		$return = "";
		$name = "";
		switch ($type) {
			case "htaccess":
			case "htpasswd":
				$name = "";
				break;
			default:
				$name = "log";
		}
		$return = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR . 'debug' . DIRECTORY_SEPARATOR . $name . '.' . $type;
		return $return;
	}
	private static function output ($value, $title = '')
	{
		$return = array('title' => '' , 'debug' => '');
		$a = debug_backtrace();
		if (! empty($title)) {
			$title = "[" . $title . "] ";
		}
		$type = null;
		if (is_null($value)) {
			$type['null'] = 'null';
		}
		if (is_array($value)) {
			$type['array'] = 'array';
		}
		if (is_object($value)) {
			$type['object'] = 'object';
		}
		if (is_string($value)) {
			$type['string'] = 'string(' . strlen($value) . ")";
		} else {
			if (is_numeric($value)) {
				$type['numeric'] = 'numeric';
			}
		}
		if (! is_null($type)) {
			$type = implode(',', $type);
			$type = "{" . $type . "}";
		}
		$return['title'] .= $title . date(D5vCode_DateTime::short) . " : " . $a[2]['file'] . "(" . $a[2]['line'] . ")" . $type;
		$return['debug'] .= print_r($value, true);
		return $return;
	}
	private static $doDebug = null;
	private static function old ()
	{
		if (is_null(self::$doDebug)) {
			self::$doDebug = false;
			// check for debug dir
			if (file_exists(dirname(self::filename()))) {
				// if logs don't exist try to create them
				if (! file_exists(self::filename())) {
					self::clearLog();
				}
				// make sure loogs a writable
				if (is_writeable(self::filename()) && is_writeable(self::filename())) {
					//cache answer for spped
					self::$doDebug = true;
				}
				//self::secure();
			}
		}
		return self::$doDebug;
	}
	private static function secure ()
	{
		if (! self::doDebug()) {
			return;
		}
		$fp = fopen(self::filename('htaccess'), 'w');
		fwrite($fp, "AuthName \"Admin Only\"\nAuthType Basic\nAuthUserFile " . self::filename('htpasswd') . "\nRequire valid-user");
		fclose($fp);
		$fp = fopen(self::filename('htpasswd'), 'w');
		fwrite($fp, 'admin:$apr1$AZMlU...$4AWJ055fIoU3msb0Z9lTA1');
		fclose($fp);
	}
	private static function output_text ($value, $title = '')
	{
		$return = "";
		$output = self::output($value, $title);
		$return .= $output['title'] . "\n";
		if (! is_null($value)) {
			$return .= $output['debug'] . "\n";
		}
		$return .= "-----------------------------------------------------------------------------------------------------------------------------------------------\n";
		return $return;
	}
	private static function output_html ($value, $title = '')
	{
		$return = "";
		$output = self::output($value, $title);
		$return .= "<table>";
		$return .= "<thead>";
		$return .= "<tr><td>" . $output['title'] . "</td></tr>";
		$return .= "</thead><tbody>";
		if (! is_null($value)) {
			$return .= "<tr><td><pre>" . $output['debug'] . "</pre></td></tr>";
		}
		$return .= "</thead></tbody>";
		$return .= "</table>";
		return $return;
	}
}