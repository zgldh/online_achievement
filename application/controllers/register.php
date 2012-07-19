<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class register extends MY_Controller
{

	/**
	 * 注册页面控制器
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/register
	 *	- or -
	 * 		http://example.com/index.php/register/index
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
		$this->navbar->hideSignIn();
	    $this->navbar->setCurrentItem(NavBar::$ITEM_REGISTER);
	    $this->setTitle("注册--在线成就系统");
		$this->view('/register/register');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */