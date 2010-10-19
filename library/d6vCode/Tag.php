<?php
/**
 * Routines for creating or decoding XML
 * @package Library
 * @subpackage Tag
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class d6vCode_Tag  extends d6vCode_Base {
	const anchor_pattern = '|<[\s]*a[\s\w]*(.*)[\s\w]*>([\w\W]*)<\s*/\s*a\s*>|Ui';
	// doesn't match is string is too long
	public static function getone($tag,$content,$comment = false)
	{
		$data = self::get($tag,$content,$comment);
		foreach($data as $datum)
		{
			return $datum;
		}
		return '';
	}
	public static function cdata($string)
	{
		$start = '<![CDATA[';
		$end = ']]';
		return  substr($string,strlen($start),-strlen($end)-1);
	}
	public static function next_tag($string)
	{
		$pos = 0;
		$max=strlen($string);
		$cnt=0;
		$tags=array();
		$exclude='';
		$tag_cnt=1;
		for($pos=0;$pos<=$max && $cnt<10;)
		{
			$inc = 0;
			$pattern = '/<\?(?P<xml_text>.*)\?>|<!--(?P<comment_text>.*)-->|<(?P<single_tag>.*)(?P<single_tag_attributes>.*)\/>|<(?P<tag>.*)(?P<tag_attributes>.*)>'.$exclude.'(?P<text>[\s\S\w\W]*)<\/(?P=tag)>/Ui';
			preg_match( $pattern, $string, $matches , null , $pos);
			if(count($matches)>0)
			{
				if(!empty($matches['single_tag']) || !empty($matches['tag']))
				{
					$tag=array();
					if(!empty($matches['single_tag']))
					{
						$inc=strlen($matches[0]);
						$tag['tag']=$matches['single_tag'];
						$tag['attributes']=$matches['single_tag_attributes'];
						$tag['text']='';
						$tags[]=$tag;
					}
					if(!empty($matches['tag']))
					{
						$cpattern='/<(?P<tag>'.$matches['tag'].')(?P<tag_attributes>.*)>/Ui';
						preg_match_all( $cpattern, $matches[0] ,$cmatches, null , 0);
						if(count($cmatches[0])>$tag_cnt)
						{
							$exclude='(?P<text'.$tag_cnt.'>[\s\S\w\W]*)<\/(?P=tag)>';
							$tag_cnt++;
						}
						else
						{
							$tag['text']='';
							foreach($matches as $key=>$match)
							{
								if(substr($key,0,4)=='text')
								{
									$tag['text'].=$match;
								}
							}
							$inc=strlen($matches[0]);
							$tag['tag']=$matches['tag'];
							$tag['attributes']=$matches['tag_attributes'];
							$tags[]=$tag;
							$exclude='';
						}
					}
				}
				else {
					$inc=strlen($matches[0]);
				}
			}
			else {
				$inc=$max;
			}
			$pos+=$inc;
			$cnt++;
		}
		return;

		$patterns = array(
						'xml'=>'/<\?(.*[\W])([\w\W\s\S]*)\?>/Ui',
						'comment'=>'/<!--([\w\W\s\S]*)-->/Ui',
						'tag'=>'/<(.*)>/Ui'
						);
						$start = 0;
						$end = strlen($string);
						for($pos=$start;$pos<=$end;)
						{
							$found=false;
							$tag = array();
							foreach($patterns as $key=>$pattern)
							{
								preg_match ( $pattern, $string, $matches,null,$pos );
								if(count($matches)>0)
								{
									$tag['match'] = $matches[0];
									$tag['tag'] = $matches[1];
									$tag['value'] = '';
									$tag['attributes'] = $matches[2];
									$inc = strlen($matches[0]);
									// find closing tag if needed.
									if($key=='tag')
									{
										if(substr($matches[0],-2)!='/>')
										{
											$exclude="";
											$matched=false;
											$missmatched = false;
											while(!$matched && !$missmatched)
											{
												$spattern = '/<' . $matches[1] . '\s*([\w\W\s\S]*)>'.$exclude.'([\w\W\s\S]*)<\/' . $matches[1] . '>/Ui';
												preg_match ( $spattern, $string, $smatches,null,$pos );
												if(count($smatches)>0)
												{
													$cpattern='<' . $matches[1] . '([\w\W\s\S]*)>';
													// check for another open tag
													preg_match ( $cpattern, $smatches[count($smatches)-1], $cmatches,null,0 );
													if(count($cmatches)>0)
													{
														$exclude.='([\w\W\s\S]*)<\/'.$matches[1].'>';
													}
													else {
														$matched=true;
														$inc=strlen($smatches[0]);
													}
												}
												else
												{
													$missmatched=true;
													$inc=strlen($string);
												}
											}
											if($matched)
											{
											}
											else
											{
												if($missmatched)
												{

												}
											}
												
										}
									}
									echo '<br/>';
									$pos+=$inc;
									$found = true;
									break;
								}
							}
							if(!$found)
							{
								break;
							}
						}

						return $matches;
	}
	public function get($tag, $content, $comment = false) {
		$comment_start = '';
		$comment_end = '';
		if ($comment) {
			$comment_start = '!--';
			$comment_end = '--';
		}
		$retval = array ( );
		$patterns = array ( );
		$patterns [] = '|<' . $comment_start . '\s*' . $tag . '\s\s*(.*)\s*' . $comment_end . '>([\w\W\s\S]*)<' . $comment_start . '\s*\/' . $tag . '\s*' . $comment_end . '>|Ui';
		$patterns [] = '|<' . $comment_start . '\s*' . $tag . '\s\s*(.*)\s*' . $comment_end . '\/?>|Ui';
		if ($comment) {
			$patterns [] = '|\[\s*' . $tag . '\s*(.*)\s*\]([\w\W\s\S]*)\[\s*\/' . $tag . '\s*\]|Ui';
			$patterns [] = '|\[\s*' . $tag . '\s*(.*)\s*\/?\]|Ui';
		}
		$all_matches = null;
		foreach ( ( array ) $patterns as $pattern ) {
			preg_match_all ( $pattern, $content, $matches, PREG_SET_ORDER );
			foreach ( ( array ) $matches as $match ) {
				$content = str_replace ( $match [0], '', $content );
			}
			$all_matches = array_merge ( ( array ) $all_matches, ( array ) $matches );
		}
		$patterns = array ( );
		$patterns [] = '|\s*(.*)\s*=\s*"(.*)"\s*|Ui';
		$patterns [] = '|\s*(.*)\s*=\s*\'(.*)\'\s*|Ui';
		$patterns [] = '|\s*(.*)\s*=\s*&#8217;(.*)&#8217;\s*|Ui';
		$patterns [] = '|\s*(.*)\s*=\s*&#8221;(.*)&#8221;\s*|Ui';
		$complete_matches = array ( );
		foreach ( ( array ) $all_matches as $all_match ) {
			$complete_match = "";
			$complete_match ['match'] = $all_match [0];
			$complete_match ['tag'] = $tag;
			if ($comment)
			$complete_match ['comment'] = true;
			$complete_match ['open'] = $all_match [0] [0];
			$complete_match ['close'] = substr ( $all_match [0], - 1 );
			if (array_key_exists ( 2, $all_match ))
			$complete_match ['innerhtml'] = $all_match [2];
			$complete_match ['attributes'] = ( array ) self::attributes ( $all_match [1] );
			//change lone checked into checked=checked or selected
			foreach ( ( array ) array_keys ( ( array ) $complete_match ['attributes'] ) as $key ) {
				switch ( $complete_match ['attributes'] [$key]) {
					case 'checked' :
					case 'selected' :
						$complete_match ['attributes'] [$complete_match ['attributes'] [$key]] = $complete_match ['attributes'] [$key];
						unset ( $complete_match ['attributes'] [$key] );
						break;
				}
			}
			$complete_matches [] = $complete_match;
		}
		return $complete_matches;
	}

	// still needs work, but works for wp_marker
	public static function render($tag) {
		$default_tag = array ('type' => 'text', 'open' => '<', 'close' => '>' );
		$tag = array_merge ( $default_tag, $tag );
		$default_attributes = array ( );
		$tag ['attributes'] = array_merge ( $default_attributes, $tag ['attributes'] );
		//$this->test($tag);
		$comment_start = '';
		$comment_end = '';
		if ($tag ['comment']) {
			//$comment_start = '!--';
			//$comment_end = '--';
		}
		$return = "";
		$return .= $tag [open];
		$return .= $comment_start;
		$return .= $tag ['tag'];
		foreach ( ( array ) array_keys ( ( array ) $tag ['attributes'] ) as $key ) {
			$return .= ' ' . $key . ' = "' . $tag ['attributes'] [$key] . '"';
		}
		if (! array_key_exists ( 'innerhtml', $tag ) && $tag ['open'] != '[') {
			$return .= '/';
		}
		$return .= $tag ['close'];
		$return .= $comment_end;
		if (array_key_exists ( 'innerhtml', $tag )) {
			$return .= $tag ['innerhtml'];
			$return .= $tag ['open'];
			$return .= '/';
			$return .= $tag ['comment_start'];
			$return .= $tag ['tag'];
			$return .= $tag ['comment_end'];
			$return .= $tag ['close'];
		}
		return $return;
	}
	public static function attributes($text) {
		$atts = array ( );
		$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
		$text = preg_replace ( "/[\x{00a0}\x{200b}]+/u", " ", $text );
		if (preg_match_all ( $pattern, $text, $match, PREG_SET_ORDER )) {
			foreach ( ( array ) $match as $m ) {
				if (! empty ( $m [1] ))
				$atts [strtolower ( $m [1] )] = stripcslashes ( $m [2] ); elseif (! empty ( $m [3] ))
				$atts [strtolower ( $m [3] )] = stripcslashes ( $m [4] ); elseif (! empty ( $m [5] ))
				$atts [strtolower ( $m [5] )] = stripcslashes ( $m [6] ); elseif (isset ( $m [7] ) and strlen ( $m [7] ))
				$atts [] = stripcslashes ( $m [7] ); elseif (isset ( $m [8] ))
				$atts [] = stripcslashes ( $m [8] );
			}
		} else {
			$atts = ltrim ( $text );
		}
		return $atts;
	}
	protected static $_instance = null;
	public function instance()
	{
		if(null === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function tokenise(&$post) {
		$cnt = 0;
		$patterns = array();
		// pattern for ancho tags
		//$patterns [] = '|<[\s]*a[\s\w]*(.*)[\s\w]*>([\w\W]*)<\s*/\s*a\s*>|Ui';
		// pattern of all other html anchor tags
		$patterns [] = '|<(.*)>|Ui';

		//pattern for [] tags,
		$patterns [] = '|\[(.*)]|Ui';

		$source = $post;
		$tokens = array ( );
		foreach ( ( array ) $patterns as $pattern ) {
			preg_match_all ( $pattern, $source, $matches, PREG_SET_ORDER );
			foreach ( ( array ) $matches as $match ) {
				// create token
				$token = "#" . str_pad ( $cnt, 4, "0", STR_PAD_LEFT ) . "#";

				$tokens [$cnt] = $match [0];
				$post = str_replace ( $match [0], " $token ", $post );
				$cnt ++;
			}
		}
		//return new post text and tokens
		$retval = null;
		$retval->text = $post;
		$retval->tokens = $tokens;
		$post = $retval;
		return $retval;
	}

	public function detokenise(&$text) {
		$retval = $text->text;
		$cnt = 0;
		foreach ( ( array ) $text->tokens as $tag ) {
			$token = "#" . str_pad ( $cnt, 4, "0", STR_PAD_LEFT ) . "#";
			$retval = str_replace (  " $token ", $tag, $retval );
			$cnt ++;
		}
		$text = $retval;
		return $retval;
	}
}