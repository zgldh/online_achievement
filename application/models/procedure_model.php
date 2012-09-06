<?php
class Procedure_model extends MY_Model
{
	const TABLE = 'oa_procedure';
	/**
	 * 得到一个步骤对象
	 *
	 * @param int $procedure_id        	
	 * @return Ambigous <boolean, ProcedurePeer>
	 */
	public function getByPK($procedure_id)
	{
		$raw = $this->db->get_where ( Procedure_model::TABLE, array (ProcedurePeer::PK => $procedure_id ) )->row_array ();
		$procedure = $raw ? new ProcedurePeer ( $raw ) : false;
		return $procedure;
	}
	/**
	 * 得到一个成就的所有步骤对象
	 *
	 * @param int $achievement_id        	
	 * @return Ambigous <boolean, ProcedurePeer>
	 */
	public function getByAchievementID($achievement_id, $formation = false)
	{
		$re = array ();
		if ($formation)
		{
			$this->db->order_by ( 'step_num' );
			$this->db->where ( 'upper_id IS NULL' );
			$result = $this->db->get_where ( Procedure_model::TABLE, array ('achievement_id' => $achievement_id ) )->result_array ();
			
			foreach ( $result as $raw )
			{
				$procedure = new ProcedurePeer ( $raw );
				$sub_procedures = $this->getSubProcedures ( $procedure->procedure_id );
				$procedure->setSubProcedures ( $sub_procedures );
				$re [] = $procedure;
			}
		}
		else
		{
			$result = $this->db->get_where ( Procedure_model::TABLE, array ('achievement_id' => $achievement_id ) )->result_array ();
			foreach ( $result as $raw )
			{
				$re [] = new ProcedurePeer ( $raw );
			}
		}
		return $re;
	}
	
	/**
	 *
	 *
	 * 得到 $procedure_id 所有的子步骤
	 * 
	 * @param int $procedure_id        	
	 * @return multitype:ProcedurePeer
	 */
	public function getSubProcedures($procedure_id)
	{
		$re = array ();
		$this->db->order_by ( 'step_num' );
		$result = $this->db->get_where ( Procedure_model::TABLE, array ('upper_id' => $procedure_id ) )->result_array ();
		foreach ( $result as $raw )
		{
			$re [] = new ProcedurePeer ( $raw );
		}
		
		return $re;
	}
	/**
	 * 更新数据 或 插入数据
	 *
	 * @param ProcedurePeer $procedure        	
	 */
	public function saveProcedurePeer(& $procedure)
	{
		$this->db->set ( 'achievement_id', $procedure->achievement_id );
		$this->db->set ( 'description', $procedure->description );
		$this->db->set ( 'step_num', $procedure->step_num );
		$this->db->set ( 'upper_id', $procedure->upper_id );
		
		$pkValue = $procedure->getPrimaryKeyValue ();
		
		if ($pkValue)
		{
			$this->db->where ( ProcedurePeer::PK, $pkValue );
			$this->db->update ( Procedure_model::TABLE );
		}
		else
		{
			$this->db->insert ( Procedure_model::TABLE );
			$procedure->setPrimaryKeyvalue ( $this->db->insert_id () );
		}
	}
}
class ProcedurePeer extends BasePeer
{
	const PK = 'procedure_id';
	
	/**
	 * 步骤ID
	 *
	 * @var int
	 */
	public $procedure_id = 0;
	/**
	 * 成就ID
	 *
	 * @var int
	 */
	public $achievement_id = 0;
	/**
	 * 步骤顺序，小序在前
	 *
	 * @var int
	 */
	public $step_num = 0;
	/**
	 * 上一级步骤ID
	 *
	 * @var int
	 */
	public $upper_id = 0;
	/**
	 * 步骤描述
	 *
	 * @var string
	 */
	public $description = '';
	
	/**
	 * 子步骤
	 * 
	 * @var array
	 */
	protected $sub_procedure = array ();
	function __construct($raw = null)
	{
		parent::__construct ( $raw, __CLASS__ );
	}
	public function getPrimaryKeyName()
	{
		return ProcedurePeer::PK;
	}
	public function save()
	{
		ProcedurePeer::model ()->saveProcedurePeer ( $this );
	}
	/**
	 *
	 * @return Procedure_model
	 */
	public static function model()
	{
		$CI = & get_instance ();
		return $CI->procedure_model;
	}
	
	/**
	 * 设置本步骤的子步骤数组
	 * 
	 * @param array $sub_procedures        	
	 */
	public function setSubProcedures($sub_procedures)
	{
		if ($sub_procedures && is_array ( $sub_procedures ))
		{
			$this->sub_procedure = $sub_procedures;
		}
	}
	/**
	 * 得到本步骤的子步骤数组
	 * 
	 * @return unknown
	 */
	public function getSubProcedures()
	{
		return $this->sub_procedure;
	}
	
	/**
	 * 根据一个 IntentPeer, 得到一个意向(intent)在本步骤留下的 Track记录
	 * 
	 * @param IntentPeer $intent        	
	 */
	public function getTracksWithIntent($intent)
	{
		if(!$intent)
		{
			return null;
		}
		$CI = & get_instance ();
		$CI->load->model ( 'Track_model', 'track_model', true );
		$tracks = TrackPeer::model ()->getTracksByProcedureAndIntent ( $this->procedure_id, $intent->intent_id );
		return $tracks;
	}
}
?>