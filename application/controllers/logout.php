<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class logout extends MY_Controller
{

	/**
	 * 用户退出控制器
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/logout
	 *	- or -
	 * 		http://example.com/index.php/logout/index
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
        $this->webuser->logout();
        $this->load->helper('url');
        redirect('/');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */