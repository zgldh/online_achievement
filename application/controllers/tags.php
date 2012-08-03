<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tags extends MY_Controller
{
	public function index()
	{
		$this->navbar->setCurrentItem(NavBar::$ITEM_TAGS);
		$this->setTitle("标签分类--在线成就系统");
		$this->view('/tags/all');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */