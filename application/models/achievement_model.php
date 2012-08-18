<?php
class Achievement_model extends MY_Model
{
	const TABLE = 'oa_achievement';
	public function getByPK($achievement_id)
	{
		$raw = $this->db->get_where ( Achievement_model::TABLE, array (AchievementPeer::PK => $achievement_id ) )->row_array ();
		$user = $raw ? new AchievementPeer ( $raw ) : false;
		return $user;
	}
	/**
	 * 得到某用户最后生成的成就
	 * @param int $user_id
	 * @return Ambigous <boolean, AchievementPeer>
	 */
	public function getLastCreatedByUserID($user_id)
	{
		$this->db->order_by('achievement_id','DESC');
		$raw = $this->db->get_where ( Achievement_model::TABLE, array ('creater_id'=> $user_id ) )->row_array ();
		$user = $raw ? new AchievementPeer ( $raw ) : false;
		return $user;
	}
	/**
	 * 更新数据 或 插入数据
	 *
	 * @param AchievementPeer $achievement        	
	 */
	public function saveAchievementPeer(& $achievement)
	{
		$this->db->set ( 'creater_id', $achievement->creater_id );
		$this->db->set ( 'logo_id', $achievement->logo_id );
		$this->db->set ( 'name', $achievement->name );
		$this->db->set ( 'requirement', $achievement->requirement );
		$this->db->set ( 'status', $achievement->status );
		$this->db->set ( 'super_achievement_id', $achievement->super_achievement_id );
		$this->db->set ( 'track_type', $achievement->track_type );
		
		$pkValue = $achievement->getPrimaryKeyValue ();
		
		if ($pkValue)
		{
			$this->db->where ( AchievementPeer::PK, $pkValue );
			$this->db->update ( Achievement_model::TABLE );
		}
		else
		{
			$this->db->set ( 'create_date', 'NOW()', false );
			$this->db->insert ( Achievement_model::TABLE );
			$achievement->setPrimaryKeyvalue ( $this->db->insert_id () );
		}
	}
}
class AchievementPeer extends BasePeer
{
	const PK = 'achievement_id';
	
	/**
	 * 成就id
	 *
	 * @var int
	 */
	public $achievement_id = 0;
	/**
	 * 本条成就继承自哪个成就
	 *
	 * @var int
	 */
	public $super_achievement_id = 0;
	/**
	 * 成就名字
	 *
	 * @var string
	 */
	public $name = '';
	/**
	 * 创作者id=>oa_user.user_id
	 *
	 * @var int
	 */
	public $creater_id = 0;
	/**
	 * 成就logo的文件 uploaded_id
	 *
	 * @var int
	 */
	public $logo_id = 0;
	/**
	 * 1: 普通 2: 阶段式 3: 无限式
	 *
	 * @var int
	 */
	public $track_type = 1;
	/**
	 * 达成条件，达成需求;
	 *
	 * @var string
	 */
	public $requirement = '';
	/**
	 * 0: 刚创建，编辑中；
	 * 1: 创建完成，待审核；
	 * 2: 审核通过；
	 * 3: 已屏蔽
	 *
	 * @var int
	 */
	public $status = 0;
	
	/**
	 * 创建时间
	 *
	 * @var string
	 */
	public $create_date = '';
	function __construct($raw = null)
	{
		parent::__construct ( $raw, __CLASS__ );
	}
	public function getPrimaryKeyName()
	{
		return AchievementPeer::PK;
	}
	public function save()
	{
		AchievementPeer::model ()->saveAchievementPeer ( $this );
	}
	
	/**
	 *
	 * @return Achievement_model
	 */
	public static function model()
	{
		$CI = & get_instance ();
		return $CI->achievement_model;
	}
	
	/**
	 * 为当前成就设置标签， 旧标签会被抹掉
	 *
	 * @param array $tags
	 *        	= null
	 *        	array('技能','自行车')
	 * @return boolean
	 */
	public function setTags($tags)
	{
		$this->needPKValue ( 'Current achievement is empty.' );
		
		if (! $tags)
		{
			return false;
		}
		
		$this->removeTags ();
		
		$CI = & get_instance ();
		$CI->load->model ( 'Tags_model', 'tags_model', true );
		
		foreach ( $tags as $value )
		{
			$tag = TagPeer::model ()->getTagByName ( $value, true );
			$tag->appendToAchievement ( $this->achievement_id );
		}
		return true;
	}
	/**
	 * 得到当前成就的所有标签
	 *
	 * @return multitype:TagPeer
	 */
	public function getTags()
	{
		$this->needPKValue ( 'Current achievement is empty.' );
		
		$re = array ();
		$relations = TagToAchievementPeer::model ()->getTagAchievementByAchievementID ( $this->achievement_id );
		foreach ( $relations as $tagToAchievement )
		{
			$re [] = $tagToAchievement->getTag ();
		}
		return $re;
	}
	/**
	 * 得到当前成就的Logo
	 *
	 * @return UploadedPeer
	 */
	public function getLogo()
	{
		$CI = & get_instance ();
		$CI->load->model ( 'Uploaded_model', 'uploaded_model', true );
		$logo = UploadedPeer::model()->getByPK($this->logo_id);
		return $logo;
	}
	/**
	 * 为当前成就添加标签
	 *
	 * @param array $tags
	 *        	= null
	 *        	array('技能','自行车')
	 */
	public function addTags($tags)
	{
		$this->needPKValue ( 'Current achievement is empty.' );
		
		foreach ( $tags as $tagName )
		{
			$tag = TagPeer::model ()->getTagByName ( $tagName, true );
			$tag->appendToAchievement ( $this->achievement_id );
		}
	}
	/**
	 * 删除一些标签
	 *
	 * @param array $tags
	 *        	= null
	 *        	array('技能','自行车'); 如果为null则删除所有标签
	 */
	public function removeTags($tags = null)
	{
		$this->needPKValue ( 'Current achievement is empty.' );
		
		$CI = & get_instance ();
		$CI->load->model ( 'Tags_model', 'tags_model', true );
		
		if ($tags == null)
		{
			$relations = TagToAchievementPeer::model ()->getTagAchievementByAchievementID ( $this->achievement_id );
			foreach ( $relations as $tagToAchievement )
			{
				$tagToAchievement->delete ( true );
			}
		}
		else
		{
			foreach ( $tags as $tagName )
			{
				$tag = TagPeer::model ()->getTagByName ( $tagName );
				$tag->removeFromAchievement ( $this->achievement_id );
			}
		}
	}
	
	/**
	 * 从一个json对象来设置并储存本成就的步骤。
	 *
	 * @param object $json
	 *        	[{"id":123, "text":"step1",
	 *        	"children":
	 *        	[{"id":456, "text":"step1.1"}]},
	 *        	{"id":789, "text":"step2",
	 *        	"children":
	 *        	[{"id":32, "text":"step2.1"},
	 *        	{"text":"step2.2"}]
	 *        	}]
	 * @param boolean $new
	 *        	= false 是否是新成就的步骤
	 */
	public function setProcedureFromJSON($json, $new = false)
	{
		$this->needPKValue ( 'Current achievement is empty.' );
		if (! $json)
		{
			return false;
		}
		
		$CI = & get_instance ();
		$CI->load->model ( 'Procedure_model', 'procedure_model', true );
		$this->setProcedures ( $json, null, $new );
	}
	private function setProcedures($json_array, $upper_id = null, $new = false)
	{
		$step = 1;
		foreach ( $json_array as $item )
		{
			$procedure = new ProcedurePeer ();
			if ($new == false && isset ( $item->id ))
			{
				$procedure->setPrimaryKeyvalue ( $item->id );
			}
			$procedure->achievement_id = $this->achievement_id;
			$procedure->description = $item->text;
			$procedure->step_num = $step ++;
			$procedure->upper_id = $upper_id;
			$procedure->save ();
			
			if (isset ( $item->children ))
			{
				$this->setProcedures ( $item->children, $procedure->getPrimaryKeyValue (), $new );
			}
		}
	}
	
	/**
	 * 验证这步骤JSON字符串是否是本成就的步骤
	 *
	 * @param string $json        	
	 * @return 只要有一个不是，就返回false
	 */
	public function isMyProceduresJSON($json)
	{
		$this->needPKValue ( 'Current achievement is empty.' );
		$json_obj = json_decode ( $json );
		if (! $json_obj)
		{
			throw new Exception ( 'Bad JSON string.' );
		}
		
		$CI = & get_instance ();
		$CI->load->model ( 'Procedure_model', 'procedure_model', true );
		$re = $this->isMyProcedures ( $json );
		return $re;
	}
	private function isMyProcedures($json_array)
	{
		$re = true;
		foreach ( $json_array as $item )
		{
			if (isset ( $item->id ))
			{
				$procedure = ProcedurePeer::model ()->getByPK ( $item->id );
				if ($procedure->achievement_id != $this->achievement_id)
				{
					return false;
				}
			}
			
			if (isset ( $item->children ))
			{
				$re = $this->isMyProcedures ( $item->children );
			}
		}
		return $re;
	}
}

?>