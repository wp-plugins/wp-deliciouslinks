<?php
class SandBoxSandBoxController extends w3v_Controller_Action_AdminMenu
{
	public function ConfigFilesActionMeta($return)
	{
		$return['priority'] = 99;
		return $return;
	}
	/**
	 *
	 */
	public function ConfigFilesAction($content)
	{
		$page = $this->application()->Settings()->ConfigFiles();
 		$http = new b3v_Http('http://wordpress.org/extend/plugins/about/validator/');
 		$data = 'readme_contents='.urlencode($page).'&text=1';
 		$http->method('POST');
 		$http->data($data);
 		$page = $http->get();
 		$page = substr($page,0,strpos($page,"<h2 id='re-edit'>Re-Edit your Readme File</h2>"));
 		return $content.$page;
	}
	public function UnserializeAction ()
	{}
}
