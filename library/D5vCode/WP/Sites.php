<?php
/**
 * an interface to the site table
 * @package Library
 * @subpackage WP_Sites
 * @copyright DCoda Ltd
 * @author DCoda Ltd
 * @license http://www.gnu.org/licenses/gpl.txt
 * $HeadURL$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 */
class D5vCode_WP_Sites extends D5vCode_WP_Table
{

	public function name ()
	{
		return $this->wpdb ()->site;
	}

	public function __construct ( $id  )
	{
		parent::__construct ();
	}

	public function exists_by_domain ( $domain , $path = '/' )
	{
		$results = $this->get_row_by_clause ( "`domain`='{$domain}' AND `path`='{$path}'" );
		return ! empty ( $results );
	}

	public function exists ( $id )
	{
		$results = $this->get ( $id );
		return ! empty ( $results );
	}

	public function get ( $id)
	{
		$result = $this->get_row_by_clause ( "`id`='{$id}'" );
		return $result;
	}

	public function add ( $domain , $path = '/' , $create_blog = true )
	{
		$return = false;
		if (! $this->exists_by_domain ( $domain , $path ))
		{
			$result = $this->wpdb ()->insert ( $this->name () , array (
				'domain' => $domain , 'path' => $path 
			) );
			$site_id = $this->wpdb ()->insert_id;
			if ($site_id)
			{
				if ($create_blog)
				{
					$wp_blogs = new D5vCode_WP_Blogs ( );
					$wp_blogs->add ( $site_id );
				}
				$options = array (
					'admin_email' , 'admin_user_id' , 'allowed_themes' , 'allowedthemes' , 'banned_email_domains' , 'first_post' , 'limited_email_domains' , 'site_admins' , 'welcome_email' 
					);
					$prime_site_meta = new D5vCode_WP_SiteMeta ( 1 );
					$new_site_meta = new D5vCode_WP_SiteMeta ( $site_id );
					foreach ( $options as $option )
					{
						$new_site_meta->set ( $option , $prime_site_meta->get ( $option ) );
					}
					$new_site_meta->set ( 'site_name' , $domain );
			}
			$return = true;
		}
		return $return;
	}

	public function remove ( $id )
	{
		$wp_sitemeta = new D5vCode_WP_SiteMeta ( );
		$sql = sprintf ( "DELETE FROM `%s` WHERE `id`=%d" , $this->name () , $id );
		$this->wpdb ()->query ( $sql );
		$sql = sprintf ( "DELETE FROM `%s` WHERE `site_id`=%d" , $wp_sitemeta->name () , $id );
		$this->wpdb ()->query ( $sql );
		return true;
	}
}
