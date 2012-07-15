<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  web用户类。 本类用来表示当前正在访问的用户。
 */
class WebUser{

    private static $_session_key = 'swu';
    private static $_cookie_key = 'cwu';

    private $_user = null;
    private $_user_id = 0;
    private $_user_name = null;
    private $_session_id = null;

    function __construct()
    {
        $ci = & get_instance();
        $ci->load->library('session');
        $session_data = $ci->session->userdata(WebUser::$_session_key);
        $cookie_data = $ci->input->cookie(WebUser::$_cookie_key);
        if($session_data)
        {
            // 有session
            $this->_user_id = $session_data['user_id'];
            $this->_user_name = $session_data['user_name'];
//            $this->_user = get user by user id
        }
        elseif($cookie_data = $ci->input->cookie(WebUser::$_cookie_key))
        {
            // TODO 有cookie，自动登录
        }
        else
        {
            // 啥都没，游客
        }
    }

    public function getUser()
    {
        return $this->_user;
    }
    public function getUserId()
    {
        return $this->_user_id;
    }
    public function getUserName()
    {
        return $this->_user_name;
    }
    public function isLogin()
    {
        return ($this->_user_id == 0)?false:true;
    }
    public function getSessionId()
    {
        return $this->_session_id;
    }


    public function login($user_name, $password)
    {
    }

    public function logout()
    {
    }
}
// END Controller class

/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */