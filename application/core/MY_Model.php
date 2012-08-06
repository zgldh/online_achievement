<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model{
}
// END Controller class


class BasePeer
{
	function __construct($raw, $className)
	{
		if (is_array ( $raw ) || is_object ( $raw ) || get_class ( $raw ) == $className)
		{
			foreach ( $raw as $key => $item )
			{
				if (isset ( $this->$key ))
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
	public function getPrimaryKeyName()
	{
		throw new Exception('BasePeer.getPrimaryKeyName must be rewrite.');
	}
}


/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */