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
        $data = array();
        $redirect_to = $this->inputPost('redirect_to');
        if($this->isPostRequest())
        {
            $error = $this->doLogin();
            if(!$error)
            {
                $this->load->helper('url');
                redirect($redirect_to);
                exit();
            }
        }
        $this->navbar->setRedirectTo($redirect_to);
	    $this->navbar->setCurrentItem(NavBar::$ITEM_SIGNIN);
	    $this->navbar->hideSignIn();
	    $this->setTitle("登录--在线成就系统");
        $data = compact('error');
		$this->view('/signin/signin', $data);
	}

    private function doLogin()
    {
        $re_data = array();
        $user_name = $this->inputPost('user_name');
        $password = $this->inputPost('password');

        $error = $this->webuser->login($user_name, $password);
        return $error;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */