<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model{
}
// END Controller class


class BasePeer
{
	function __construct($raw = null, $className = null)
	{
	    if($raw == null || $className == null)
	    {
	        return;
	    }
		if (is_array ( $raw ) || is_object ( $raw ) || get_class ( $raw ) == $className)
		{
			foreach ( $raw as $key => $item )
			{
				if (property_exists($className, $key))
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
	
	public function getPrimaryKeyValue()
	{
		$pk = $this->getPrimaryKeyName();
		return $this->$pk;
	}
	public function setPrimaryKeyvalue($value)
	{
		$pk = $this->getPrimaryKeyName();
		$this->$pk = $value;
	}
	/**
	 * 得到主键名字，这个函数应该被子类重写
	 * @throws Exception
	 */
	public function getPrimaryKeyName()
	{
		throw new Exception('BasePeer.getPrimaryKeyName must be rewrite.');
	}
	
	protected function needPKValue($exception_msg = 'Need primary key value.')
	{
		$value = $this->getPrimaryKeyValue();
		if(!$value)
		{
			throw new Exception($exception_msg);
		}
	}
}


/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */