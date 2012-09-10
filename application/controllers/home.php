<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/home
	 *	- or -
	 * 		http://example.com/index.php/home/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
	    $this->navbar->setCurrentItem(NavBar::ITEM_HOME);
	    $this->setTitle("首页--在线成就系统");
		$this->addJavascriptFile ( '/js/nailthumb/jquery.nailthumb.1.1.min.js' );
		$this->addStyleFile( '/js/nailthumb/jquery.nailthumb.1.1.min.css' );
	    $this->addAutoRunJavascriptCode("jQuery('.home_wall_item').nailthumb({titleAnimationTime:200, width:128,height:128});");

	    $this->loadAchievementModel();
	    $achievements = AchievementPeer::model()->getMostPopularAchievements(21);

	    $data = compact('achievements');

		$this->view('/home/homepage', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */