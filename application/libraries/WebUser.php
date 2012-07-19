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

    /**
     * 登录操作, 登录失败返回错误信息, 登录成功不返回任何东西
     *
     */
    public function login($user_name, $password)
    {
        // TODO 从数据模型里验证能否登录
        $m_user_id = 9527;
        $m_user_name = 'test';
        $m_password = 'test';
        if($user_name != $m_user_name || $password != $m_password)
        {
            $error['msg'] = '错误的帐号或密码。';
            return $error;
        }

        //设置 webuser
        $this->_user = null; // 数据模型 user
        $this->_user_id = $m_user_id;
        $this->_user_name = $m_user_name;

        //设置session
        $ci = & get_instance();
        $ci->load->library('session');
        $userdata = array(
                    WebUser::$_session_key => array(
                            'user_id'  => $this->_user_id,
                            'user_name'     => $this->_user_name)
               );
        $ci->session->set_userdata($userdata);

        //设置 cookie
        // TODO


    }

    public function logout()
    {
        //设置session
        $ci = & get_instance();
        $ci->load->library('session');
        $userdata = array(
            WebUser::$_session_key => array(
                    'user_id'  => 0,
                    'user_name'     => null)
       );
        $ci->session->set_userdata($userdata);

        //设置 cookie
        // TODO

        //设置 webuser
        $this->_user = null;
        $this->_user_id = 0;
        $this->_user_name = null;
    }
}
// END Controller class

/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */