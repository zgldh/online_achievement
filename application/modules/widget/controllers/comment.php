<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Widget_comment_module extends CI_Module {

    const PAGE_SIEZ = 5;
	/**
	 * 构造函数
	 *
	 * @return void
	 * @author
	 **/
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
	    echo 'Widget_comment_module';
	}
	/**
	 * 得到评论列表内容
	 * @param unknown_type $achievement_id
	 * @param unknown_type $page
	 */
	public function list_by_achievement($achievement_id, $page = 1)
	{
	    $page = max($page, 0);
	    $offset = ($page - 1) * Widget_comment_module::PAGE_SIEZ;
	    $this->load->model('comment_model');
	    $total_num = 0;
	    $comments = CommentPeer::model()->getByAchievement($achievement_id,null,$offset,Widget_comment_module::PAGE_SIEZ,$total_num);
	    $data = compact('achievement_id','comments','page','total_num');
		$this->load->view('list_by_achievement',$data);
	}
	/**
	 * 显示评论列表和评论对话框
	 * @param unknown_type $achievement_id
	 */
	public function by_achievement($achievement_id)
	{
	    $data = compact('achievement_id');
		$this->load->view('by_achievement',$data);
	}
}