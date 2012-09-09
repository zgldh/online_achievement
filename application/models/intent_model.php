<?php
class Intent_model extends MY_Model
{
	const TABLE_INTENT = 'oa_intent';
	
	/**
	 * 根据intent_id 来得到一个 IntentPeer
	 *
	 * @param int $intent_id        	
	 * @return IntentPeer
	 */
	public function getByPK($intent_id)
	{
		$raw = $this->db->get_where ( Intent_model::TABLE_INTENT, array (IntentPeer::PK => $intent_id ) )->row_array ();
		$intent = $raw ? new IntentPeer ( $raw ) : false;
		return $intent;
	}
	/**
	 * 
	 * 根据 achievement_id 得到该成就所有的 intent
	 * @param int $achievement_id
	 * @param int $status
	 * @param int $remove_user_id
	 * @param DB_Limit $limit
	 * @return multitype:IntentPeer
	 */
	public function getIntentsByAchievementID($achievement_id,$status = null,$remove_user_id = null, DB_Limit $limit = null)
	{
		$this->db->order_by ( 'intent_id', 'DESC' );
		
		if($status!= null)
		{
		    $this->db->where('status',$status);
		}
		
		if($remove_user_id)
		{
		    $this->db->where('user_id !='.(int)$remove_user_id);
		}
		
		if ($limit)
		{
			$limit->setLimit ( $this->db );
		}
		
		$this->db->where ( 'achievement_id', $achievement_id );
		$result = $this->db->get ( Intent_model::TABLE_INTENT )->result_array ();
		
		$re = array ();
		foreach ( $result as $raw )
		{
			$re [] = new IntentPeer ( $raw );
		}
		return $re;
	}
	
	/**
	 * 根据 user_id 得到该用户的 intent列表
	 *
	 * @param int $user_id        	
	 * @param DB_Limit $limit
	 *        	= null
	 * @return multitype:IntentPeer
	 */
	public function getIntentsByUserID($user_id, DB_Limit $limit = null)
	{
		$re = array ();
		if ($limit)
		{
			$limit->setLimit ( $this->db );
		}
		$result = $this->db->get_where ( Intent_model::TABLE_INTENT_ACHIEVEMENT, array ('user_id' => $user_id ) )->result_array ();
		foreach ( $result as $raw )
		{
			$re [] = new IntentPeer ( $raw );
		}
		return $re;
	}
	/**
	 * 根据 user_id 和 achievement_id 搜索是否有该 intent
	 * @param int $user_id
	 * @param int $achievement_id
	 * @return Ambigous <boolean, IntentPeer>
	 */
	public function getByUserAndAchievement($user_id, $achievement_id)
	{
	    $raw = $this->db->get_where ( Intent_model::TABLE_INTENT, array ('user_id'=>$user_id, 'achievement_id'=>$achievement_id) )->row_array ();
	    $intent = $raw ? new IntentPeer ( $raw ) : false;
	    return $intent;
	}
	
	/**
	 * 更新数据 或 插入数据
	 *
	 * @param IntentPeer $intent        	
	 */
	public function saveIntentPeer(& $intent)
	{
		$this->db->set ( 'achievement_id', $intent->achievement_id );
		$this->db->set ( 'user_id', $intent->user_id );
		if($intent->achieve_date)
		{
		    $this->db->set ( 'achievem_date', $intent->achieve_date );
		}
		$this->db->set ( 'status', $intent->status );
		
		$pkValue = $intent->getPrimaryKeyValue ();
		
		if ($pkValue)
		{
			$this->db->where ( IntentPeer::PK, $pkValue );
			$this->db->update ( Intent_model::TABLE_INTENT );
		}
		else
		{
			$this->db->set ( 'intent_date', 'NOW()', false );
			$this->db->insert ( Intent_model::TABLE_INTENT );
			$intent->setPrimaryKeyvalue ( $this->db->insert_id () );
		}
	}
	
	public function saveIntentComplete(& $intent)
	{
		$this->db->set ( 'status', $intent->status );
		$this->db->set ( 'achieve_date','NOW()',false);
		$pkValue = $intent->getPrimaryKeyValue ();
		$this->db->where ( IntentPeer::PK, $pkValue );
		$this->db->update ( Intent_model::TABLE_INTENT );
	}
	/**
	 * 删除一个 intent
	 *
	 * @param IntentPeer $intent        	
	 * @return int 会返回删除的行数
	 */
	public function delete(& $intent)
	{
		$this->db->delete ( Intent_model::TABLE_INTENT, array ('intent_id' => $intent->intent_id ) );
		return $this->db->affected_rows ();
	}
}
class IntentPeer extends BasePeer
{
	const PK = 'intent_id';
	
	/**
	 * 该意图进行中
	 * @var int
	 */
	const STATUS_PROCESSING = 1;
	/**
	 * 该意图已经完成
	 * @var int
	 */
	const STATUS_COMPLETE = 2;
	
	/**
	 * 标签ID
	 *
	 * @var int
	 */
	public $intent_id = 0;
	/**
	 * 用户id
	 * 
	 * @var int
	 */
	public $user_id = 0;
	/**
	 * 成就id
	 * 
	 * @var int
	 */
	public $achievement_id = 0;
	/**
	 * 该意图建立的时间戳
	 * 
	 * @var string
	 */
	public $intent_date = '';
	/**
	 * 成就实现时间戳
	 * 
	 * @var string
	 */
	public $achieve_date = '';
	/**
	 * 该意图的状态<br />
	 * 1: 执行中 2: 已经达成
	 * 
	 * @var int IntentPeer:STATUS_PROCESSING | IntentPeer:STATUS_COMPLETE
	 */
	public $status = 1;
	function __construct($raw = null)
	{
		parent::__construct ( $raw, __CLASS__ );
	}
	public function getPrimaryKeyName()
	{
		return IntentPeer::PK;
	}
	/**
	 * 保存该标签
	 */
	public function save()
	{
		IntentPeer::model ()->saveIntentPeer( $this );
	}
	/**
	 *
	 * @return Intent_model
	 */
	public static function model()
	{
		$CI = & get_instance ();
		return $CI->intent_model;
	}
	
	/**
	 * 删除当前这个 intent
	 */
	public function delete()
	{
		IntentPeer::model()->delete($this);
	}
	
	/**
	 * 得到当前 intent 的所有track
	 * @return Ambigous <multitype:TrackPeer, multitype:TrackPeer >
	 */
	public function getTracks()
	{
		$CI = & get_instance ();
		$CI->load->model ( 'Track_model', 'track_model', true );
		$tracks = TrackPeer::model ()->getTracksByIntent($this->intent_id );
		return $tracks;
	}
	
	/**
	 * 得到当前 intent 对应的成就
	 * @return Ambigous <boolean, AchievementPeer>
	 */
	public function getAchievement()
	{
		$CI = & get_instance ();
		$CI->load->model ( 'Achievement_model', 'achievement_model', true );
		$achievement = AchievementPeer::model()->getByPK($this->achievement_id);
		return $achievement;
	}
	/**
	 * 得到当前 intent 对应的用户
	 * @return Ambigous <boolean, UserPeer>
	 */
	public function getUser()
	{
		$CI = & get_instance ();
		$CI->load->model ( 'User_model', 'user_model', true );
		$user = UserPeer::model()->getByPK($this->user_id);
		return $user;
	}
	
	/**
	 * 该意图是否已经完成
	 * @return boolean true已经完成, false没有完成，在进行中
	 */
	public function isComplete()
	{
		return $this->status == IntentPeer::STATUS_COMPLETE?true:false;
	}
	
	/**
	 * 检查该 intent 对应的成就的所有步骤是否都track完成了
	 * @return boolean true全都完成了
	 */
	public function isAllProceduresComplete()
	{
		$achievement = $this->getAchievement();
		$procedures = $achievement->getProcedures();
		$flag = true;
		foreach($procedures as $procedure)
		{
			$procedure instanceof ProcedurePeer;
			$tracks = $procedure->getTracksWithIntent($this);
			if(count($tracks) == 0)
			{
				$flag = false;
				break;
			}
		}
		return $flag;
	}
	
	/**
	 * 将当前意图记录为完成，并且保存
	 */
	public function complete()
	{
		$this->status = IntentPeer::STATUS_COMPLETE;
		IntentPeer::model()->saveIntentComplete($this);
	}
	
	/**
	 * 得到当前 Intent 的最后一个 TrackPeer
	 * @return TrackPeer
	 */
	public function getLastTrack()
	{
	    $tracks = $this->getTracks();
	    return array_shift($tracks);
	}
}

?>