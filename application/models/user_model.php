<?php
class User_model extends MY_Model
{
	const TABLE = 'oa_user';
	public function getByPK($user_id)
	{
		if($this->cache_pk->hasData($user_id))
		{
			return $this->cache_pk->getData($user_id);
		}
		
		$raw = $this->db->get_where ( User_model::TABLE, array (UserPeer::PK => $user_id ) )->row_array ();
		$user = $raw ? new UserPeer ( $raw ) : false;
		
		$this->cache_pk->setData($user_id, $user);
		
		return $user;
	}
	public function getByName($name)
	{
		$raw = $this->db->get_where ( User_model::TABLE, array ('name' => $name ) )->row_array ();
		$user = $raw ? new UserPeer ( $raw ) : false;
		return $user;
	}
	public function getByEmail($email)
	{
		$raw = $this->db->get_where ( User_model::TABLE, array ('email' => $email ) )->row_array ();
		$user = $raw ? new UserPeer ( $raw ) : false;
		return $user;
	}
	/**
	 * 检查是否能登录。不能登录返回false 能登录返回对应UserPeer
	 * 
	 * @param string $name        	
	 * @param string $password
	 *        	密码原文
	 * @return boolean UserPeer
	 */
	public function checkLogin($name, $password)
	{
		$raw = $this->db->get_where ( User_model::TABLE, array ('name' => $name, 'password' => md5 ( $password ) ), 1 )->row_array ();
		if (! $raw)
		{
			return false;
		}
		else
		{
			return new UserPeer ( $raw );
		}
	}
	/**
	 * 更新数据 或 插入数据
	 *
	 * @param UserPeer $user        	
	 */
	public function saveUserPeer(& $user)
	{
		$this->db->set ( 'name', strip_tags($user->name) );
		$this->db->set ( 'password', $user->password );
		$this->db->set ( 'email', $user->email );
		$this->db->set ( 'auto_login_expire', $user->auto_login_expire );
		$this->db->set ( 'auto_login_token', $user->auto_login_token );
		
		$pkValue = $user->getPrimaryKeyValue ();
		if ($pkValue)
		{
			$this->db->where ( UserPeer::PK, $pkValue );
			$this->db->update ( User_model::TABLE );
		}
		else
		{
			$this->db->set ( 'reg_datetime', 'NOW()', false );
			$this->db->insert ( User_model::TABLE );
			$user->setPrimaryKeyvalue ( $this->db->insert_id () );
		}
		
		$this->cache_pk->setData($user->getCachePK(), $user);
		
	}
	/**
	 * 用户注册业务
	 *
	 * @param string $user_name        	
	 * @param string $password        	
	 * @param string $re_password        	
	 * @param string $email        	
	 */
	public function register($user_name, $password, $re_password, $email)
	{
		$errors = array ();
		if (! strlen ( $password ))
		{
			$errors ['password'] = '请输入密码';
		}
		if ($password != $re_password)
		{
			$errors ['re_password'] = '两次输入的密码不一样';
		}
		$this->load->helper ( 'email' );
		if (! valid_email ( $email ))
		{
			$errors ['email'] = '电子邮箱地址无效';
		}
		elseif ($this->getByEmail ( $email ))
		{
			$errors ['email'] = '电子邮箱地址 ' . $email . ' 已经有人使用';
		}
		
		if ($this->getByName ( $user_name ))
		{
			$errors ['user_name'] = '帐号 ' . $user_name . ' 已经有人使用';
		}
		
		if ($errors)
		{
			return $errors;
		}
		
		$user = new UserPeer ( array ('name' => $user_name, 'password' => md5 ( $password ), 'email' => $email, 'reg_datetime' => time () ) );
		$user->save ();
	}
}
class UserPeer extends BasePeer
{
	const PK = 'user_id';
	/**
	 * 用户ID
	 * 
	 * @var int
	 */
	public $user_id = 0;
	/**
	 * 用户名
	 * 
	 * @var string
	 */
	public $name = '';
	/**
	 * 密码(md5过的)
	 * 
	 * @var string
	 */
	public $password = '';
	/**
	 * 电子邮件
	 * 
	 * @var string
	 */
	public $email = '';
	/**
	 * 注册日期
	 */
	public $reg_datetime = 0;
	/**
	 * 自动登录令牌
	 * 
	 * @var string
	 */
	public $auto_login_token = null;
	/**
	 * 自动登录令牌过期日期
	 * 
	 * @var
	 *
	 */
	public $auto_login_expire = null;
	function __construct($raw = null)
	{
		parent::__construct ( $raw, __CLASS__ );
	}
	public function getPrimaryKeyName()
	{
		return UserPeer::PK;
	}
	public function save()
	{
		UserPeer::model()->saveUserPeer($this);
	}
	/**
	 * @return User_model
	 */
	public static function model()
	{
		$CI = & get_instance ();
		return $CI->user_model;
	}
	
	/**
	 * 生成并且保存新的自动登录token
	 */
	public function newAutoLoginToken()
	{
		$t = time ();
		$this->auto_login_token = md5 ( $t . 'cJ4mzYwv' ) . $t;
		$this->auto_login_expire = date ( 'Ymd', $t + 30 * 24 * 60 * 60 );
		$this->save();
	}
	/**
	 * 检查自动登录token是否有效
	 */
	public function checkAutoLoginToken($token)
	{
		/*
		 * 本来就没有自动登录token或过期时间，则外来的token无效
		 */
		if(!$this->auto_login_token || !$this->auto_login_expire)
		{
			return false;
		}
		
		$expire = strtotime($this->auto_login_expire);
		$current = time();
		if($current > $expire )
		{
			return false;
		}
		
		if($this->auto_login_token == $token)
		{
			return true;
		}
		return false;
	}
	
	/**
	 * 该用户要向某成就发起挑战了！<br />
	 * 建立一个该用户对特定成就的 intent
	 * @param int $achievement_id
	 * @return 成功什么也不返回， 否则返回error数组
	 */
	public function createIntent($achievement_id)
	{
	    $CI = & get_instance ();
        $CI->load->model('Intent_model','intent_model',true);
	    //检测是否已经有该intent
        $error = array();
	    
	    $intent = IntentPeer::model()->getByUserAndAchievement($this->user_id, $achievement_id);
	    if(!$intent)
	    {
	        //TODO 未来加上前提条件过滤
	    
	        //设立该 intent
	        $intent = new IntentPeer();
	        $intent->user_id = $this->user_id;
	        $intent->achievement_id = $achievement_id;
	        $intent->save();
	    }
	    else
	    {
	        $error[] = '不能重复挑战';
	        return $error;
	    }
	}
}

?>