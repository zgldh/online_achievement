<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class signin extends MY_Controller
{

	/**
	 * 登录页面控制器
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/signin
	 *	- or -  
	 * 		http://example.com/index.php/signin/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
	    $this->setTitle("首页--在线成就系统");
		$this->view('/home/homepage');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */