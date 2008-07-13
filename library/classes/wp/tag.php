<?php
class dc_wp_tag_1_1_1 extends dc_base_2_4_0 {

	function tokenise($post,$timer)
	{
		$cnt=0;

		// pattern for ancho tags
		$patterns[0]='|<[\s]*a[\s\w]*(.*)[\s\w]*>([\w\W]*)<\s*/\s*a\s*>|Ui';
		 // pattern of all other html anchor tags
 		$patterns[1]='|<(.*)>|Ui';

		 //pattern for [] tags,
 		$patterns[2]='|\[(.*)]|Ui';

		$source=$post;
 		$tokens=array();
 		foreach((array)$patterns as $pattern)
 		{
  			preg_match_all($pattern,$source,$matches,PREG_SET_ORDER);
  			foreach((array)$matches as $match)
  			{
   				if ($timer->timeout())
   	  				return $post;
   				// create token
   				$token="#".str_pad($cnt, 4, "0", STR_PAD_LEFT)."#";

   			$tokens[$cnt]=$match[0];
   			$post= str_replace($match[0],$token,$post);
   			$cnt++;
  			}
		 }
 		//return new post text and tokens
 		$retval->text=$post;
 		$retval->tokens=$tokens;
 		return $retval;
	}

		// function to replace tokens with original text.
	function detokenise($text,$timer)
	{
  		$retval=$text->text;
  		$cnt=0;
  		foreach((array)$text->tokens as $tag)
  		{
	   		if ($timer->timeout())
	   	  		return "";
   			$token="#".str_pad($cnt, 4, "0", STR_PAD_LEFT)."#";
   			$retval= str_replace($token,$tag,$retval);
   			$cnt++;
  		}
		return $retval;
	}

}
?>