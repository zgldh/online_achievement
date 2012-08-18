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
		$user_name = '';
		$email = '';
		$errors = '';
		
		if($this->isPostRequest())
		{
			$user_name = $this->inputPost('user_name');
			$password = $this->inputPost('password');
			$re_password = $this->inputPost('repassword');
			$email = $this->inputPost('email');
			
			$this->loadUserModel();
			$errors = UserPeer::model()->register($user_name,$password,$re_password,$email);
			if(!$errors)
			{
				$this->load->helper('url');
				redirect('/register/ok');
				exit();
			}
		}
		$this->navbar->hideSignIn();
	    $this->navbar->setCurrentItem(NavBar::ITEM_REGISTER);
	    $this->setTitle("注册--在线成就系统");
	    
	    $data = compact('user_name','email','errors');
		$this->view('/register/register',$data);
	}
	
	public function ok()
	{
		$this->navbar->hideSignIn();
	    $this->navbar->setCurrentItem(NavBar::ITEM_REGISTER);
	    $this->setTitle("注册成功--在线成就系统");
	    
		$this->view('/register/register_ok');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */