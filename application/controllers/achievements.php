<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class achievements extends MY_Controller
{
	public function all()
	{
		$this->navbar->setCurrentItem(NavBar::$ITEM_ALL);
		$this->setTitle("所有成就--在线成就系统");
		$this->view('/achievements/all');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */