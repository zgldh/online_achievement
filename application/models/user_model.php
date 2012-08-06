<?php
class User_model extends MY_Model
{
	const TABLE = 'oa_user';
	
	public function checkLogin($name, $password)
	{
		$raw = $this->db->get_where ( User_model::TABLE, array ('name' => $name, 'password' => md5 ( $password ) ), 1 )->row_array();
		if ( !$raw )
		{
			return false;
		}
		else
		{
			return new UserPeer($raw);
		}
	}
	public function getByPK($user_id)
	{
		$raw = $this->db->get_where ( User_model::TABLE, array (UserPeer::PK => $user_id ) )->row_array ();
		$user = $raw?new UserPeer ( $raw ):false;
		return $user;
	}
	public function getByName($name)
	{
		$raw = $this->db->get_where ( User_model::TABLE, array ('name' => $name ) )->row_array ();
		$user = $raw?new UserPeer ( $raw ):false;
		return $user;
	}
	public function getByEmail($email)
	{
		$raw = $this->db->get_where ( User_model::TABLE, array ('email' => $email ) )->row_array ();
		$user = $raw?new UserPeer ( $raw ):false;
		return $user;
	}
	/**
	 * 更新数据 或 插入数据
	 * @param UserPeer $user
	 */
	public function saveUserPeer(& $user)
	{
		$this->db->set('name',$user->name);
		$this->db->set('password',$user->password);
		$this->db->set('email',$user->email);
		
		if($user->getPrimaryKeyValue())
		{
			$this->db->where ( UserPeer::PK , $user->getPrimaryKeyValue() );
			$this->db->update ( User_model::TABLE );
		}
		else
		{
			$this->db->set('reg_datetime','NOW()',false);
			$this->db->insert ( User_model::TABLE);
			$user->setPrimaryKeyvalue($this->db->insert_id());
		}
	}
	/**
	 * 用户注册业务
	 * @param string $name
	 * @param string $password
	 * @param string $re_password
	 * @param string $email
	 */
	public function register($name,$password,$re_password,$email)
	{
		$errors = array();
		if(!strlen($password))
		{
			$errors[] = '请输入密码';
		}
		if($password != $re_password)
		{
			$errors[] = '两次输入的密码不一样';
		}
		$this->load->helper('email');
		if(!valid_email($email))
		{
			$errors[] = '电子邮箱地址无效';
		}
		elseif($this->getByEmail($email))
		{
			$errors[] = '电子邮箱地址 '.$email.' 已经有人使用';
		}
		
		if($this->getByName($name))
		{
			$errors[] = '帐号 '.$name.' 已经有人使用';
		}
		
		if($errors)
		{
			return $errors;
		}
		
		$user = new UserPeer(array(
					'name'=>$name,
					'password'=>md5($password),
					'email'=>$email,
					'reg_datetime'=>time()
				));
		$user->save();
	}
}
class UserPeer extends BasePeer
{
	const PK = 'user_id';
	
	public $user_id = 0;
	public $name = '';
	public $password = '';
	public $email = '';
	public $reg_datetime = 0;
	
	function __construct($raw)
	{
		parent::__construct($raw, __CLASS__);
	}
	
	public function getPrimaryKeyName()
	{
		return UserPeer::PK;
	}
	
	public function save()
	{
		$CI = & get_instance();
		$CI->user->saveUserPeer($this);
	}
}

?>