<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Comment_model extends MY_Model
{
	const TABLE = 'oa_comment';
	public function getByPK($comment_id)
	{
		$raw = $this->db->get_where ( Comment_model::TABLE, array (CommentPeer::PK => $comment_id ) )->row_array ();
		$user = $raw ? new CommentPeer ( $raw ) : false;
		return $user;
	}
	/**
	 * 得到多条评论
	 *
	 * @param int $achievement_id
	 *        	成就id
	 * @param int $track_id
	 *        	改成就某track的id， 忽略的话得到所有
	 * @param int $offset
	 *        	偏移量， 忽略的话则从头开始
	 * @param int $limit
	 *        	返回条数, 忽略的话则没有限制
	 * @param int $total_num
	 * 			会返回全部数量
	 * @return multitype:CommentPeer
	 */
	public function getByAchievement($achievement_id, $track_id = 0, $offset = 0, $limit = null, & $total_num = 0)
	{
		$this->db->where ( 'achievement_id', $achievement_id );
		if ($track_id != 0)
		{
			$this->db->where ( 'track_id', $track_id );
		}
		$this->db->order_by ( 'comment_id', 'DESC' );
		$total_num = $this->db->get ( Comment_model::TABLE )->num_rows();

		$limit_obj = new DB_Limit ( $limit, $offset );
		$limit_obj->setLimit($this->db);
		$result = $this->db->get ( Comment_model::TABLE )->result_array ();
		
		$re = array ();
		foreach ( $result as $raw )
		{
			$re [] = new CommentPeer ( $raw );
		}
		return $re;
	}
	/**
	 * 得到某个用户发出的评论
	 *
	 * @param int $user_id        	
	 * @param int $offset
	 *        	= 0
	 * @param int $limit
	 *        	= null
	 * @return multitype:CommentPeer
	 */
	public function getByUser($user_id, $offset = 0, $limit = null)
	{
		$this->db->where ( 'user_id', $user_id );
		$this->db->order_by ( 'comment_id', 'DESC' );
		$limit_obj = new DB_Limit ( $limit, $offset );
		$result = $this->db->get ( Comment_model::TABLE_INTENT )->result_array ();
		$re = array ();
		foreach ( $result as $raw )
		{
			$re [] = new CommentPeer ( $raw );
		}
		return $re;
	}
	/**
	 * 更新数据 或 插入数据
	 *
	 * @param CommentPeer $comment        	
	 */
	public function saveCommentPeer(& $comment)
	{
		$this->db->set ( 'user_id', $comment->user_id );
		$this->db->set ( 'content', $comment->content );
		$this->db->set ( 'deleted', $comment->deleted );
		$this->db->set ( 'reply_comment_id', $comment->reply_comment_id );
		$this->db->set ( 'achievement_id', $comment->achievement_id );
		$this->db->set ( 'track_id', $comment->track_id );
		
		$pkValue = $comment->getPrimaryKeyValue ();
		if ($pkValue)
		{
			$this->db->where ( CommentPeer::PK, $pkValue );
			$this->db->update ( Comment_model::TABLE );
		}
		else
		{
			$this->db->set ( 'post_date', 'NOW()', false );
			$this->db->insert ( Comment_model::TABLE );
			$comment->setPrimaryKeyvalue ( $this->db->insert_id () );
		}
	}
}
class CommentPeer extends BasePeer
{
	const PK = 'comment_id';
	
	/**
	 * 标记 已经删除, 用于 deleted 属性
	 *
	 * @var int
	 */
	const DELTED_YES = 1;
	/**
	 * 标记 没有删除, 用于 deleted 属性
	 *
	 * @var int
	 */
	const DELTED_NO = 0;
	
	/**
	 * 评论id
	 *
	 * @var int
	 */
	public $comment_id = 0;
	/**
	 * 发评论的用户id
	 *
	 * @var int
	 */
	public $user_id = 0;
	/**
	 * 评论内容
	 *
	 * @var string
	 */
	public $content = '';
	/**
	 * 评论时间戳
	 *
	 * @var string
	 */
	public $post_date = '';
	/**
	 * 是否删除了
	 * CommentPeer::DELETED_XX
	 *
	 * @var int
	 */
	public $deleted = 0;
	/**
	 * 这条评论是回复的哪条评论id
	 *
	 * @var int
	 */
	public $reply_comment_id = null;
	/**
	 * 这条评论隶属于的成就
	 *
	 * @var int
	 */
	public $achievement_id = null;
	/**
	 * 这条评论隶属于的track
	 *
	 * @var int
	 */
	public $track_id = null;
	function __construct($raw = null)
	{
		parent::__construct ( $raw, __CLASS__ );
	}
	public function getPrimaryKeyName()
	{
		return CommentPeer::PK;
	}
	public function save()
	{
		CommentPeer::model ()->saveCommentPeer ( $this );
	}
	/**
	 *
	 * @return Comment_model
	 */
	public static function model()
	{
		$CI = & get_instance ();
		return $CI->comment_model;
	}
	
	/**
	 *
	 *
	 * 创建一个新的评论对象
	 * 
	 * @param string $content        	
	 * @param int $user_id        	
	 * @param int $achievement_id
	 *        	= null
	 * @param int $track_id
	 *        	= null
	 * @param int $reply_comment_id
	 *        	= null
	 * @return CommentPeer
	 */
	public static function create($content, $user_id, $achievement_id = null, $track_id = null, $reply_comment_id = null)
	{
		$comment = new CommentPeer ();
		$comment->content = $content;
		$comment->user_id = $user_id;
		$comment->achievement_id = $achievement_id;
		$comment->track_id = $track_id;
		$comment->reply_comment_id = $reply_comment_id;
		return $comment;
	}
	
	/**
	 * 本评论是否被标记为 已经删除
	 *
	 * @return boolean
	 */
	public function isDeleted()
	{
		return $this->deleted == CommentPeer::DELTED_YES ? true : false;
	}
	
	public function getUser()
	{
		$CI = & get_instance ();
		$CI->load->model('User_model','user_model',true);
		$user = UserPeer::model()->getByPK($this->user_id);
		return $user;
	}
}

?>