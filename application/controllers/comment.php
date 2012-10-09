<?php

if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Comment extends MY_Controller
{
	public function ajax_post($achievement_id, $track_id = null, $reply_comment_id = null)
	{
	    if(!$this->isPostRequest())
	    {
	        return false;
	    }
	    if(!$this->webuser->isLogin())
	    {
	        return false;
	    }
	    $content = $this->inputPost('content');
	    $this->loadCommentModel();
	    $comment = CommentPeer::create($content, $this->webuser->getUserId(),$achievement_id,$track_id,$reply_comment_id);
	    $comment->save();
	    echo 'ok';
	}
	
	public function ajax_get_list($achievement_id)
	{
		$this->load->module('/widget/comment/list_by_achievement',array($achievement_id));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */