<?php
class Procedure_model extends MY_Model
{
	const TABLE = 'oa_procedure';
	/**
	 * 得到一个步骤对象
	 * @param int $procedure_id
	 * @return Ambigous <boolean, ProcedurePeer>
	 */
	public function getByPK($procedure_id)
	{
		$raw = $this->db->get_where ( Procedure_model::TABLE, array (ProcedurePeer::PK => $procedure_id ) )->row_array ();
		$user = $raw ? new ProcedurePeer ( $raw ) : false;
		return $user;
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
		ProcedurePeer::model()->saveProcedurePeer( $this );
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
}

?>