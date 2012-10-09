<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class MY_Model extends CI_Model
{
	/**
	 * 主键缓存
	 * @var DB_Cache
	 */
	protected $cache_pk = null;
	
	function __construct()
	{
		$this->cache_pk = new DB_Cache();
		parent::__construct();
	}
}
// END Model class
class BasePeer
{
	function __construct($raw = null, $className = null)
	{
		if ($raw == null || $className == null)
		{
			return;
		}
		if (is_array ( $raw ) || is_object ( $raw ) || get_class ( $raw ) == $className)
		{
			foreach ( $raw as $key => $item )
			{
				if (property_exists ( $className, $key ))
				{
					$this->$key = $item;
				}
			}
		}
		else
		{
			throw new Exception ( 'Bad raw data for ' . $className );
		}
	}
	
	public function getCachePK()
	{
		return $this->getPrimaryKeyValue();
	}
	
	public function getPrimaryKeyValue()
	{
		$pk = $this->getPrimaryKeyName ();
		return $this->$pk;
	}
	public function setPrimaryKeyvalue($value)
	{
		$pk = $this->getPrimaryKeyName ();
		$this->$pk = $value;
	}
	/**
	 * 得到主键名字，这个函数应该被子类重写
	 * 
	 * @throws Exception
	 */
	public function getPrimaryKeyName()
	{
		throw new Exception ( 'BasePeer.getPrimaryKeyName must be rewrite.' );
	}
	protected function needPKValue($exception_msg = 'Need primary key value.')
	{
		$value = $this->getPrimaryKeyValue ();
		if (! $value)
		{
			throw new Exception ( $exception_msg );
		}
	}
}

/**
 * 用来给 $this->db 做limit限制的对象<br />
 * 
 * @author Zhangwb
 *        
 */
class DB_Limit
{
	public $offset = null;
	public $limit = null;
	
	/**
	 * 必须要有 $limit
	 * 
	 * @param int $limit        	
	 * @param int $offset
	 *        	= null
	 */
	function __construct($limit = null, $offset = null)
	{
		$this->offset = $offset;
		$this->limit = $limit;
	}
	
	/**
	 * 如果limit为空 则什么也不做。<br />
	 * 如果offset为空， 则只限制limit<br />
	 * 否则将同时加上偏移量和limit
	 * 
	 * @param CI_DB_active_record $db        	
	 */
	public function setLimit($db)
	{
		if ($this->limit == null)
		{
			return;
		}
		if ($this->offset == null)
		{
			$limit = ( int ) $this->limit;
			$db->limit ( $limit );
		}
		else
		{
			$offset = ( int ) $this->offset;
			$limit = ( int ) $this->limit;
			$db->limit ( $limit, $offset );
		}
	}
}
class DB_Cache
{
	private $_data = array ();
	function __construct()
	{
	}
	public function hasData($key)
	{
		return array_key_exists ( $key, $this->_data );
	}
	public function getData($key)
	{
		return @$this->_data [$key];
	}
	public function setData($key, $val)
	{
		$this->_data [$key] = $val;
	}
	public function unsetData($key)
	{
		unset($this->_data[$key]);
	}
}

/* End of file Controller.php */
/* Location: ./application/core/MY_Model.php */