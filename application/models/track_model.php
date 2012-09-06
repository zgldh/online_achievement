<?php
class Track_model extends MY_Model
{
	const TABLE = 'oa_track';
	/**
	 * 得到一个步骤对象
	 *
	 * @param int $track_id        	
	 * @return Ambigous <boolean, TrackPeer>
	 */
	public function getByPK($track_id)
	{
		$raw = $this->db->get_where ( Track_model::TABLE, array (TrackPeer::PK => $track_id ) )->row_array ();
		$procedure = $raw ? new TrackPeer ( $raw ) : false;
		return $procedure;
	}
	/**
	 * 根据特定步骤， 特定意向(intent)， 得到一系列的track
	 * @param int $procedure_id
	 * @param int $intent_id
	 * @return multitype:TrackPeer
	 */
	public function getTracksByProcedureAndIntent($procedure_id, $intent_id)
	{
		$re = array ();
		$this->db->where ( 'procedure_id', $procedure_id );
		$this->db->where ( 'intent_id', $intent_id );
		$this->db->order_by ( 'track_date', 'desc' );
		$result = $this->db->get ( Track_model::TABLE )->result_array ();
		foreach ( $result as $raw )
		{
			if ($raw)
			{
				$re [] = new TrackPeer ( $raw );
			}
		}
		return $re;
	}
	/**
	 * 根据特定意向(intent)， 得到一系列的track
	 * @param int $intent_id
	 * @return multitype:TrackPeer
	 */
	public function getTracksByIntent($intent_id)
	{
		$re = array ();
		$this->db->where ( 'intent_id', $intent_id );
		$this->db->order_by ( 'track_date', 'desc' );
		$result = $this->db->get ( Track_model::TABLE )->result_array ();
		foreach ( $result as $raw )
		{
			if ($raw)
			{
				$re [] = new TrackPeer ( $raw );
			}
		}
		return $re;
	}
	/**
	 * 更新数据 或 插入数据
	 *
	 * @param TrackPeer $track        	
	 */
	public function saveTrackPeer(& $track)
	{
		$this->db->set ( 'achievement_id', $track->achievement_id );
		$this->db->set ( 'intent_id', $track->intent_id );
		$this->db->set ( 'procedure_id', $track->procedure_id );
		$this->db->set ( 'content', $track->content );
		
		$pkValue = $track->getPrimaryKeyValue ();
		
		if ($pkValue)
		{
			$this->db->where ( TrackPeer::PK, $pkValue );
			$this->db->update ( Track_model::TABLE );
		}
		else
		{
			$this->db->set ( 'track_date', 'NOW()', false );
			$this->db->insert ( Track_model::TABLE );
			$track->setPrimaryKeyvalue ( $this->db->insert_id () );
		}
	}
}
class TrackPeer extends BasePeer
{
	const PK = 'track_id';
	
	/**
	 * 成就追踪记录 id
	 *
	 * @var int
	 */
	public $track_id = 0;
	/**
	 * 是哪条成就的追踪
	 *
	 * @var int
	 */
	public $achievement_id = 0;
	/**
	 * 意向 id
	 *
	 * @var int
	 */
	public $intent_id = 0;
	
	/**
	 * 这是哪个步骤的track
	 *
	 * @var int
	 */
	public $procedure_id = 0;
	
	/**
	 * 本条记录时间戳
	 *
	 * @var string
	 */
	public $track_date = '';
	/**
	 * 记录内容
	 *
	 * @var string
	 */
	public $content = '';
	function __construct($raw = null)
	{
		parent::__construct ( $raw, __CLASS__ );
	}
	public function getPrimaryKeyName()
	{
		return TrackPeer::PK;
	}
	public function save()
	{
		TrackPeer::model ()->saveTrackPeer ( $this );
	}
	/**
	 *
	 * @return Track_model
	 */
	public static function model()
	{
		$CI = & get_instance ();
		return $CI->track_model;
	}
}
?>