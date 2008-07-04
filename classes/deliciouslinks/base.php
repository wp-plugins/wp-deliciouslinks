<?php
class dc_deliciouslinks_1_0_0 extends dc_base_2_4_0 {
	function init()
	{
		$this->loadClass('wp_dashboard',array($this,'dash'));
		$o=$this->loadClass('wp_options');
		if($o->values['refresh']!="")
		{
			$this->loadClass('wp_footer',array($this,'footer'));
		}
	}
	function dash()
	{
		$d = $this->loadClass('wp_dashboard',array($this,'dash'));
		$d->add_page('Manage','DeliciousLinkSync',array($this,'page'));
	}
	function page()
	{
		$o=$this->loadClass('wp_options');
		if($_POST)
		{
			$o->values['setttings'] = $_POST;
			unset($o->values['setttings']['Submit']);
			unset($o->values['setttings']['Now']);
			$o->save();
		}
		$d = $this->loadClass('wp_dashboard');
		$page = $this->loadHTML('deliciouslinks_dashboard');
		$page=str_replace('@@id@@',$o->values['setttings']['id'],$page);
		$page=str_replace('@@password@@',$o->values['setttings']['password'],$page);
		$page=str_replace('@@cat@@',$o->values['setttings']['cat'],$page);
		$page=str_replace('@@refresh@@',$o->values['setttings']['refresh'],$page);
		$p=$this->loadClass('wp_plugin');
		if ($_POST['cron']!='')
		{
			$this->createCron('BlogrollSyncCron');
		}
		$post=get_page_by_title(BlogrollSyncCron);
		if(empty($post))
		{
			$page=str_replace('@@url@@',' style="display:none" ',$page);
			$page=str_replace('@@button@@','',$page);
		}
		else
		{
			$page=str_replace('@@curl@@',$post->guid,$page);
			$page=str_replace('@@url@@','',$page);
			$page=str_replace('@@button@@',' style="display:none" ',$page);
		}
		$err="";
		if ($_POST['Now']!='')
		{
			$t = $this->loadClass('wp_timer');
			$t->checkTimer(0,array($this,'Sync'));
		}
		$d->template("Delicous Link Sync",$page);
	}
	function createCron($cron)
	{
		$post=get_page_by_title($cron);
		if(empty($post))
		{
			$post=get_default_page_to_edit();
			$post->post_title=$cron;
			$post->post_content="[$cron]";
			$postid=wp_insert_post($post);
		}

	}
	function footer()
	{
		$t = $this->loadClass('wp_timer');
		$t->checkTimer($o->values['refresh']*60*60,array($this,'Sync'));
	}

	function Sync()
	{
		$o=$this->loadClass('wp_options');
		$d=$this->loadClass('delicious');
		$d->username=$o->values['setttings']['id'];
		$d->password=$o->values['setttings']['password'];
		$cats=null;
		if(strpos($o->values['setttings']['cat'],',') == 0 )
			$cats=$o->values['setttings']['cat'];
		$newlinks=$d->get_all($cats);
		//$o->values['temp']=$newlinks;
		//$o->save();
		//$newlinks=$o->values['temp'];
		if(count($newlinks)>0)
		{
			if($newlinks[0]['tag']=='body')
			{
				//echo $newlinks[0]['innerhtml'];
				return $newlinks[0]['innerhtml'];
			}
		}
		$cnt=0;
		$links=array();
		foreach($newlinks as $link)
		{
			$links[]=$link;
			$cnt++;
			if($cnt>300)
				break;
		}
		//$links=$o->values['temp'];
		if(count($links)>0)
		{
			// get bookmarks in selected categories
			$bookmarks=$this->getBookmarksByCats($o->values['setttings']['cat']);
			// turn them into an array for merging
			$bookmarks=$this->bookmarksToArray($bookmarks);

			// change links into bookarmsk in selected categories
			$links=$this->linksToBookmarks($links,$o->values['setttings']['cat']);


			// find and delete bookmarks in categories
			$delete=array_diff_assoc($bookmarks,$links);
			//$delete=$this->getBookmarksByCats();
			//$delete=$this->bookmarksToArray($delete);
			foreach((array)$delete as $bookmark)
			{
				wp_delete_link($bookmark['link_id']);
				unset($bookmark['link_name']);
			}
			$delete=null;


			// merge bookmarks and links
			$update=array_intersect_assoc($links,$bookmarks);
			foreach((array)$update as $link)
			{
				$bookmarks[$link['link_name']]=array_merge($bookmarks[$link['link_name']],$link);
			}

			$insert=array_diff_assoc($links,$bookmarks);
			foreach((array)$insert as $link)
			{
				$bookmarks[$link['link_name']]=$link;
			}
			// insert and  update bookmarks
			foreach((array)$bookmarks as $bookmark)
			{
				wp_update_link($bookmark);
			}

		}
		return "";
	}





	function getBookmarksByCats($cats="")
	{
		$bookmarks=array();
		// get the bookmarks in the seleted categories
		$catsA = split(',',$cats);
		foreach($catsA as $cat)
		{
			$newbookmarks=get_bookmarks(array(
											'category_name' => $cat,
											'hide_invisible' => 0,
											)
			);
			$bookmarks=array_merge($bookmarks,$newbookmarks);
		}
		return $bookmarks;
	}

	function bookmarksToArray($bookmarks)
	{
		$newbookmarks=array();
		foreach(array_keys((array)$bookmarks) as $key)
		{
			$newbookmarks[$bookmarks[$key]->link_name]=(array)$bookmarks[$key];
		}
		return $newbookmarks;
	}
	function linksToBookmarks($links,$cats="")
	{
		$catsA=split(',',$cats);
		$newlinks=array();
		$keys=array_keys((array)$links);
		foreach($keys as $keys)
		{
			$link=$links[$keys]['attributes'];
			if(!array_key_exists('user',$link))
			{
				$tags=split(' ',$link['tag']);
				if (count(array_intersect($tags,$catsA)) || $cats=='')
				{
					$lcats=array();
					foreach ($tags as $tag)
					{
						$tag=str_replace('-'," ",$tag);
						$tag=ucwords($tag);
						$BCat = array();
						$BCat['cat_name']=$tag;
						$BCat=wp_insert_term($tag, 'link_category', $BCat);
						$lcats[$tag]=$BCat['term_id'];
					}
					$newlinks[$link['description']]['link_name']=$link['description'];
					$newlinks[$link['description']]['link_url']=$link['href'];
					$newlinks[$link['description']]['link_category']=$lcats;;
					$newlinks[$link['description']]['link_description']=$link['extended'];
					if($link['shared']=="no")
					{
						$newlinks[$link['description']]['link_visible']="N";
					}
					else
					{
						$newlinks[$link['description']]['link_visible']="Y";
					}
					//$newlinks[$links['descriptions']]['link_image']=$links[$keys]['attributes'];
					//$newlinks[$links['descriptions']]['link_rss']=$links[$keys]['attributes'];
				}
			}
		}
		return $newlinks;
	}
}