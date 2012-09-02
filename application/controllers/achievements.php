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
		
		$this->loadIntentModel();
		$intent = IntentPeer::model()->getByUserAndAchievement($this->webuser->getUserId(), $achievement_id);
		if($intent)
		{
		    $this->addJavascriptFile('/js/detail_intent.js');
		    $this->addJavascriptFile('/js/bootstrap-tooltip.js');
		}
		else
		{
		    $this->addStyleCode('.procedure-tools{display:none;}');
		}
		
		$data = compact('achievement','intent');
		$this->view('achievements/detail',$data);
	}
	
	/**
	 * 开始向某成就努力<br />
	 * 如果条件允许，则添加该intent然后重定向到/detail/$achievement_id<br />
	 * 会检测： 是否登录， 前提条件 等
	 * @param int $achievement_id
	 */
	public function work_intent($achievement_id)
	{
		$this->needLoginOrExit('/achievements/work_intent/'.$achievement_id);

		//检测是否已经有该intent
        $this->loadIntentModel();

        $error = array();
        
        $intent = IntentPeer::model()->getByUserAndAchievement($this->webuser->getUserId(), $achievement_id);
        if(!$intent)
        {
            //TODO 未来加上前提条件过滤
            
            //设立该 intent
            $intent = new IntentPeer();
            $intent->user_id = $this->webuser->getUserId();
            $intent->achievement_id = $achievement_id;
            $intent->save();
        }
        
        if($error)
        {
            //有错误。设置错误信息
            $this->webuser->setSessFlashData('error',$error);
        }

        $this->load->helper ( 'url' );
        redirect ( '/detail/'.$achievement_id );
        exit();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */