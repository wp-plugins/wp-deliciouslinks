<?php
class dc_http_1_3_0 extends dc_base_2_4_0 {
	//--------------------------------------------------------------------------------------------------------------------
	// http functions
	//--------------------------------------------------------------------------------------------------------------------
	/*
	* httpRequest
	* post to and retieve data from web page
 	* @param	string		$Url
	* @param	string		$Method
 	* @param	string		$Data
 	* @return	string
 	*/
	var $Timeout=60;
	var $Port=null;
	var $Headers=null;

	function init()
	{
		$Headers=array();
	}
	function logonBasic($username,$password)
	{
	    	$this->Headers["Authorization"]="Basic ".base64_encode($username.":".$password);
	}
	function request($Url,$Method = 'GET',$Data = null)
	{
    		$pURL = parse_url($Url);
	   	if (empty($pURL['host']))
	   	{
			return false;
		}
		$Host		 = $pURL['host'];
		$Path		 = (isset($pURL['path'])) ? $pURL['path'] : '/';
		$Method = strtoupper($Method);
		switch ($pURL['scheme'])
		{
            		case 'https':
                			$scheme = 'ssl://';
                			$port = 443;
                		break;
            		case 'http':
            		default:
	                		$scheme = '';
                			$port = 80;
        		}
        		if (is_null($this->Port))
        		{
        			$this->Port=$port;
        		}
		if (!$Stream = fsockopen($scheme.$pURL['host'], $this->Port, $Errno, $Errstr, $this->Timeout)) {
			return false;
		}
		if ($Method == 'GET')
		{
		       $Path .= '?' . $Data;
	    	}
	    	$Request = '';
	    	$Request .= "$Method $Path HTTP/1.1\r\n";
	    	$this->Headers['Host']=$Host;
		if ($Method == 'POST')
		{
		   	$this->Headers['Content-Type']="application/x-www-form-urlencoded";
	    		$this->Headers['Content-Lengtht']=strlen($Data) ;
		}
    		$this->Headers['Connection']='close' ;
	    	foreach(array_keys($this->Headers) as $key )
	    	{
		    	$Request .= $key.": ".$this->Headers[$key]."\r\n";
	    	}
	    	$Request .= "\r\n";
	    	fwrite($Stream,$Request);
		if ($Method == 'POST')
		{
		       fputs($Stream, $Data);
	    	}
	    	$Page = '';
		while (!feof($Stream))
		{
			$Page .= fgets($Stream,128);
		}
		fclose($Stream);
		$parts = explode("\r\n\r\n", $Page, 2);
	    	$headers = $parts[0];
	    	$content = $parts[1];
	    	unset($parts);
		$RetVal['Headers']	= $this->_headers($headers);
		$RetVal['HTML']		= $content;
		return $RetVal;
	}
	//--------------------------------------------------------------------------------------------------------------------
	/*
 	* Headers
 	* explode headers
 	* @param	string	$Headers
 	* @return	array
 	*/
 	function _headers($Headers) {
 		foreach(explode("\r\n",$Headers) as $Header)
		{
			$part = explode(" ",$Header,2);
			$key = $part[0];
			$value = $part[1];
			$RetVal[$key] = $value;
		}
		return $RetVal;
 	}

}
?>