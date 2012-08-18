<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * web用户类。 本类用来表示当前正在访问的用户。
 */
class WebUser
{
	const SESSION_KEY = 'swu';
	const COOKIE_KEY = 'cwu';
	
	/**
	 * 当前用户
	 *
	 * @var UserPeer
	 */
	private $_user = null;
	/**
	 *
	 * @var int
	 */
	private $_user_id = 0;
	/**
	 *
	 * @var string
	 */
	private $_user_name = null;
	function __construct()
	{
		$ci = & get_instance ();
		$ci->load->library ( 'session' );
		$session_data = $ci->session->userdata ( WebUser::SESSION_KEY );
		$cookie_data = $this->getCookieData ();
		if ($session_data)
		{
			// 有session
			$this->_user_id = $session_data ['user_id'];
			$this->_user_name = $session_data ['user_name'];
			$ci->load->model ( 'User_model', 'user_model', true );
			$this->_user = UserPeer::model()->getByPK( $this->_user_id );
		}
		elseif ($cookie_data)
		{
			// 有cookie，尝试自动登录
			$token = @$cookie_data ['auto_login_token'];
			$user_id = @$cookie_data ['user_id'];
			if ($token && $user_id)
			{
				$ci->load->model ( 'User_model', 'user_model', true );
				$temp_user = UserPeer::model()->getByPK ( $user_id );
				if ($temp_user->checkAutoLoginToken ( $token ))
				{
					// 自动登录成功,
					// 设置属性
					$this->_user = $temp_user;
					$this->_user_id = $this->_user->user_id;
					$this->_user_name = $this->_user->name;
					// 换自动登录token
					$this->_user->newAutoLoginToken ();
					// 设置 session
					$this->setSessionData ( $this->_user_id, $this->_user_name );
					// 设置cookie
					$this->setCookieData ( $this->_user_id, $this->_user->auto_login_token );
				}
			}
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
		return ($this->_user_id == 0) ? false : true;
	}
	
	/**
	 * 登录操作, 登录失败返回错误信息, 登录成功不返回任何东西
	 */
	public function login($user_name, $password)
	{
		// 从数据模型里验证能否登录
		$ci = & get_instance ();
		$ci->load->model ( 'User_model', 'user_model', true );
		$_user = UserPeer::model()->checkLogin ( $user_name, $password );
		if (! $_user)
		{
			$error ['msg'] = '错误的帐号或密码。';
			return $error;
		}
		
		// 生成新的自动登录token
		$_user->newAutoLoginToken ();
		
		// 设置 webuser
		$this->_user = $_user; // 数据模型 user
		$this->_user_id = $_user->user_id;
		$this->_user_name = $_user->name;
		
		// 设置session
		$this->setSessionData ( $this->_user_id, $this->_user_name );
		
		// 设置 cookie 自动登录
		$this->setCookieData ( $this->_user_id, $this->_user->auto_login_token );
	}
	public function logout()
	{
		// 设置session
		$this->setSessionData ( 0, null );
		
		// 抹掉 cookie
		$this->deleteCookieData ();
		
		// 设置 webuser
		$this->_user = null;
		$this->_user_id = 0;
		$this->_user_name = null;
	}
	private function setSessionData($user_id, $user_name)
	{
		$ci = & get_instance ();
		$ci->load->library ( 'session' );
		$userdata = array (WebUser::SESSION_KEY => array ('user_id' => $user_id, 'user_name' => $user_name ) );
		$ci->session->set_userdata ( $userdata );
	}
	private function getCookieData()
	{
		$ci = & get_instance ();
		$ci->load->helper ( 'cookie' );
		$data = $ci->input->cookie ( WebUser::COOKIE_KEY );
		$obj = unserialize ( $data );
		return $obj;
	}
	private function setCookieData($user_id, $token)
	{
		$data = serialize ( array ('auto_login_token' => $token, 'user_id' => $user_id ) );
		$ci = & get_instance ();
		$ci->load->helper ( 'cookie' );
		$ci->input->set_cookie ( WebUser::COOKIE_KEY, $data,30 * 24 * 60 * 60 );
	}
	/**
	 * 删除所有 cookie
	 */
	private function deleteCookieData()
	{
		$ci = & get_instance ();
		$ci->load->helper ( 'cookie' );
		delete_cookie ( WebUser::COOKIE_KEY );
	}
}
// END Controller class

/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */