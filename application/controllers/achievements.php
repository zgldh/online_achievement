<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class achievements extends MY_Controller
{
	public function all()
	{
		$this->navbar->setCurrentItem(NavBar::ITEM_ALL);
		$this->setTitle("所有成就--在线成就系统");
		$this->view('/achievements/all');
	}
	
	/**
	 * 一个成就的详细页面
	 * @param int $achievement_id
	 */
	public function detail($achievement_id)
	{
		$this->navbar->setCurrentItem(NavBar::ITEM_NONE);
		
		$this->loadAchievementModel();
		$achievement = AchievementPeer::model()->getByPK($achievement_id);
		
		if(!$achievement)
		{
		    show_404();
		}
		
		$this->setTitle($achievement->name."--在线成就系统");
		
		$data = compact('achievement');
		$this->view('achievements/detail',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */