<?php
class dc_delicious_1_2_0 extends dc_base_2_4_0 {
	var $password="";
	var $username="";
	var $base_url="https://api.del.icio.us/v1/";

	function _request($command)
	{
		$url = $this->base_url.$command;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username.":".$this->password);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_POSTFIELDS,$post );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;

	}

	function get_tags()
	{
		$command="tags/get";
		$data=$this->_request($command);
		$return=array();
		$pattern='|<tag count="(.*)" tag="(.*)" />|Ui';
		preg_match_all($pattern,$data,$matches,PREG_SET_ORDER);
		foreach($matches as $match)
		{
			$return[]=array(
				'count'	=> $match['1'],
				'tag'	=> $match['2'],
			);
		}
		return $return;
	}
	function get_all($tag=null)
	{
		$command="posts/all";
		if (!is_null($tag))
		{
			$command.="?tag=".urlencode($tag);
		}
		$data=$this->_request($command);
		$return=array();
		$t=$this->loadClass('tag');
		$return=$t->get('post',$data);
		if(count($return)==0)
		{
			$return=$t->get('body',$data);

		}
		/*
		$pattern='|<post href="(.*)" description="(.*)" extended="(.*)" hash="(.*)" tag="(.*)" time="(.*)" />|Ui';
		preg_match_all($pattern,$data,$matches,PREG_SET_ORDER);
		foreach($matches as $match)
		{
			$return[]=array(
				'href'		=> $match['1'],
				'description'	=> $match['2'],
				'extended'	=> $match['3'],
				'hash'		=> $match['4'],
				'time'		=> $match['5'],
			);
		}*/
		return $return;
	}
	function add_post($url,$description,$extended=null,$tags=null,$dt=null,$replace=null,$shared=null)
	{
		/*
		&url (required) - the url of the item.
		&description (required) - the description of the item.
		&extended (optional) - notes for the item.
		&tags (optional) - tags for the item (space delimited).
		&dt (optional) - datestamp of the item (format "CCYY-MM-DDThh:mm:ssZ").

		Requires a LITERAL "T" and "Z" like in ISO8601 at http://www.cl.cam.ac.uk/~mgk25/iso-time.html for example: "1984-09-01T14:21:31Z"
		&replace=no (optional) - don't replace post if given url has already been posted.
		&shared=no (optional) - make the item private
		*/
		$command="posts/add";
		$command.="?url=".urlencode($url);
		$command.="&description=".urlencode($description);
		if (!is_null($extended))
		{
			$command.="&extended=".urlencode($extended);
		}
		if (!is_null($tags))
		{
			$command.="&tags=".urlencode($tags);
		}
		if (!is_null($dt))
		{
			$command.="&dt=".urlencode($dt);
		}
		if (!is_null($replace))
		{
			$command.="&replace=".urlencode($replace);
		}
		if (!is_null($shared))
		{
			$command.="&shared=".urlencode($shared);
		}
		$data=$this->_request($command);
		$return=array();
		$pattern='|<result code="(.*)" />|Ui';
		preg_match_all($pattern,$data,$matches,PREG_SET_ORDER);
		if(count($matches)==0)
		{
			return null;
		}
		$return=$matches[0][1];
		return $return;
	}
	function delete_post($url)
	{
		$command="posts/delete";
		$command.="?url=".urlencode($url);
		$data=$this->_request($command);
		$return=array();
		$pattern='|<result code="(.*)" />|Ui';
		preg_match_all($pattern,$data,$matches,PREG_SET_ORDER);
		if(count($matches)==0)
			return null;
		$return=$matches[0][1];
		return $return;
	}
	function updated()
	{
		$command="posts/update";
		$data=$this->_request($command);
		$return=array();
		$pattern='|<update time="(.*)"/>|Ui';
		preg_match_all($pattern,$data,$matches,PREG_SET_ORDER);
		if(count($matches)==0)
			return null;
		$return=$matches[0][1];
		return $return;
	}
	function updated_posts($tag=null)
	{
		$date=$this->updated();
		return $this->get_all($tag);
	}

}
?>