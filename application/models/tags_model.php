<?php
class Tags_model extends MY_Model
{
	const TABLE_TAGS = 'oa_tags';
	const TABLE_TAGS_ACHIEVEMENT = 'oa_tags_achievement';

	/**
	 * 根据Tag id 来得到一个tag
	 *
	 * @param int $tag_id
	 * @return TagPeer
	 */
	public function getTagByPK($tag_id)
	{
		$raw = $this->db->get_where ( Tags_model::TABLE_TAGS, array (TagPeer::PK => $tag_id ) )->row_array ();
		$tag = $raw ? new TagPeer ( $raw ) : false;
		return $tag;
	}
	/**
	 * 根据名字得到/生成一个标签对象
	 *
	 * @param string $tag_name
	 * @param boolean $auto_create
	 *        	= false 如果找不到，是否自动创建
	 * @return TagPeer
	 */
	public function getTagByName($tag_name, $auto_create = false)
	{
		$tag_name = substr ( trim ( $tag_name ), 0, 255 );
		$raw = $this->db->get_where ( Tags_model::TABLE_TAGS, array ('tag_name' => $tag_name ) )->row_array ();

		if ($auto_create && ! $raw)
		{
			$raw = array ('tag_count' => 0, 'tag_name' => $tag_name );
		}

		$tag = $raw ? new TagPeer ( $raw ) : false;
		return $tag;
	}

	/**
	 * 根据名字查询出一些标签对象
	 * @param string $tag_name
	 * @param int $limit
	 * @return Ambigous <boolean, TagPeer>
	 */
	public function getTagsByNameQuery($tag_name, $limit = 10)
	{
		$tag_name = substr ( trim ( $tag_name ), 0, 255 );

		$this->db->like('tag_name', $tag_name, 'after');
		$this->db->order_by ( 'tag_count', 'DESC' );
		$this->db->limit ( $limit );
		$result = $this->db->get( Tags_model::TABLE_TAGS)->result_array ();

		$re = array ();
		foreach ( $result as $raw )
		{
			$re [] = new TagPeer ( $raw );
		}
		return $re;
	}

	/**
	 * 根据标签ID得到 “标签-成就”列表
	 *
	 * @param int $achievement_id
	 * @return multitype:TagToAchievementPeer
	 */
	public function getTagAchievementByTagID($tag_id)
	{
		$re = array ();
		$result = $this->db->get_where ( Tags_model::TABLE_TAGS_ACHIEVEMENT, array ('tag_id' => $tag_id ) )->result_array ();
		foreach ( $result as $raw )
		{
			$re [] = new TagToAchievementPeer ( $raw );
		}
		return $re;
	}
	/**
	 * 根据成就ID得到 “标签-成就”列表
	 *
	 * @param int $achievement_id
	 * @return multitype:TagToAchievementPeer
	 */
	public function getTagAchievementByAchievementID($achievement_id)
	{
		$re = array ();
		$result = $this->db->get_where ( Tags_model::TABLE_TAGS_ACHIEVEMENT, array ('achievement_id' => $achievement_id ) )->result_array ();
		foreach ( $result as $raw )
		{
			$re [] = new TagToAchievementPeer ( $raw );
		}
		return $re;
	}
	/**
	 * 根据成就ID和标签ID得到 唯一的一个“标签-成就”关系
	 *
	 * @param int $achievement_id
	 * @param int $tag_id
	 * @return TagToAchievementPeer
	 */
	public function getTagAchievementByPK($achievement_id, $tag_id)
	{
		$row = $this->db->get_where ( Tags_model::TABLE_TAGS_ACHIEVEMENT, array ('achievement_id' => $achievement_id, 'tag_id' => $tag_id ) )->row_array ();
		$tagToAchievement = new TagToAchievementPeer ( $row );
		return $tagToAchievement;
	}
	/**
	 * 得到使用最多的标签列表
	 * @param int $limit = 10 返回几个呢？
	 * @return multitype:TagPeer
	 */
	public function getMostUsedTags($limit = 10)
	{
		$this->db->order_by ( 'tag_count', 'DESC' );
		$this->db->limit ( $limit );
		$result = $this->db->get_where ( Tags_model::TABLE_TAGS )->result_array ();

		$re = array ();
		foreach ( $result as $raw )
		{
			$re [] = new TagPeer ( $raw );
		}
		return $re;
	}

    /**
	 * 根据多个 tag 获得 Array<TagRelationPeer>, 多个Tag之间是 and 关系
	 * @param Array<TagPeer> $tags
	 * @param Array('limit','offset') $parameters = null
	 * @return multitype:TagRelationPeer
	 */
	public function getTagRelationsByTags($tags, $parameters = array())
	{
		$re = array ();

		$tag_ids = array();
		foreach($tags as $tag)
		{
			$tag instanceof TagPeer;
			$tag_ids[] = $tag->tag_id;
		}

		$this->db->where_in( 'tag_id', $tag_ids );

		$this->db->group_by( 'item_id');
		$this->db->having('count(*)',count($tag_ids));

		if (isset ( $parameters ['limit'] ))
		{
			if (isset ( $parameters ['offset'] ))
			{
				$this->db->limit ( $parameters ['limit'], $parameters ['offset'] );
			}
			else
			{
				$this->db->limit ( $parameters ['limit'] );
			}
		}
		$result = $this->db->get ( Tags_model::TABLE_TAGS_RELATION )->result_array ();
		foreach ( $result as $raw )
		{
			$re [] = new TagRelationPeer ( $raw );
		}
		return $re;
	}

	/**
	 * 更新数据 或 插入数据
	 *
	 * @param TagPeer $tag
	 */
	public function saveTagPeer(& $tag)
	{
		$tag->tag_name = substr ( trim ( $tag->tag_name ), 0, 255 );
		$this->db->set ( 'tag_name', strip_tags($tag->tag_name) );
		$this->db->set ( 'tag_count', $tag->tag_count );

		$pkValue = $tag->getPrimaryKeyValue ();

		if ($pkValue)
		{
			$this->db->where ( TagPeer::PK, $pkValue );
			$this->db->update ( Tags_model::TABLE_TAGS );
		}
		else
		{
			$this->db->insert ( Tags_model::TABLE_TAGS );
			$tag->setPrimaryKeyvalue ( $this->db->insert_id () );
		}
	}
	/**
	 * 保存一个标签和成就的关系,会首先检查是否已经存在该关系
	 *
	 * @param TagToAchievementPeer $tag
	 * @return int 保存了一条，返回1; 没有保存，返回0
	 */
	public function saveTagAchievementPeer(& $tag)
	{
		$query = $this->db->get_where ( Tags_model::TABLE_TAGS_ACHIEVEMENT, array (
				'tag_id' => $tag->tag_id,
				'achievement_id' => $tag->achievement_id ) );
		if ($query->num_rows () == 0)
		{
			$this->db->set ( 'tag_id', $tag->tag_id );
			$this->db->set ( 'achievement_id', $tag->achievement_id );
			$this->db->insert ( Tags_model::TABLE_TAGS_ACHIEVEMENT );
			return $this->db->affected_rows ();
		}
		return 0;
	}
	/**
	 * 删除一个标签和成就的关系
	 *
	 * @param TagToAchievementPeer $tag
	 * @return int 会返回删除的行数
	 */
	public function deleteTagAchievementPeer(& $tag)
	{
		$this->db->delete ( Tags_model::TABLE_TAGS_ACHIEVEMENT, array ('tag_id' => $tag->tag_id, 'achievement_id' => $tag->achievement_id ) );
		return $this->db->affected_rows ();
	}
}
class TagToAchievementPeer extends BasePeer
{
	const PK = 'achievement_id';

	/**
	 * 成就ID
	 *
	 * @var int
	 */
	public $achievement_id = 0;
	/**
	 * 标签ID
	 *
	 * @var int
	 */
	public $tag_id = 0;
	function __construct($raw = null)
	{
		parent::__construct ( $raw, __CLASS__ );
	}
	public function getPrimaryKeyName()
	{
		return TagToAchievementPeer::PK;
	}
	/**
	 * 得到该关系的tag
	 */
	public function getTag()
	{
		if (! $this->tag_id)
		{
			throw new Exception ( 'This tag to achievement relationship is empty.' );
		}
		$tag = TagPeer::model ()->getTagByPK ( $this->tag_id );
		return $tag;
	}
	/**
	 * 保存该"标签-成就"关系。 会首先检查是否有该关系
	 *
	 * @return int 保存了一条，返回1; 没有保存，返回0
	 */
	public function save()
	{
		return TagToAchievementPeer::model ()->saveTagAchievementPeer ( $this );
	}
	/**
	 * 删除该"标签-成就"关系。
	 *
	 * @param boolean $auto_decrease
	 *        	= false 是否自动将该tag的计数减一
	 * @return boolean 的确删除了true; 否则返回false
	 */
	public function delete($auto_decrease = false)
	{
		$num = TagToAchievementPeer::model ()->deleteTagAchievementPeer ( $this );

		if ($auto_decrease)
		{
			if ($num != 0)
			{
				$tag = TagPeer::model ()->getTagByPK ( $this->tag_id );
				$tag->countDelta ( - $num );
				$tag->save ();
			}
		}
	}
	/**
	 *
	 * @return Tags_model
	 */
	public static function model()
	{
		$CI = & get_instance ();
		return $CI->tags_model;
	}
}
class TagPeer extends BasePeer
{
	const PK = 'tag_id';

	/**
	 * 标签ID
	 *
	 * @var int
	 */
	public $tag_id = 0;
	/**
	 * 标签名字
	 *
	 * @var string
	 */
	public $tag_name = '';
	/**
	 * 标签计数
	 *
	 * @var int
	 */
	public $tag_count = 0;
	function __construct($raw = null)
	{
		parent::__construct ( $raw, __CLASS__ );
	}
	public function getPrimaryKeyName()
	{
		return TagPeer::PK;
	}
	/**
	 * 保存该标签
	 */
	public function save()
	{
		TagPeer::model ()->saveTagPeer ( $this );
	}
	/**
	 *
	 * @return Tags_model
	 */
	public static function model()
	{
		$CI = & get_instance ();
		return $CI->tags_model;
	}

	/**
	 * 将标签的计数加/减一定数量
	 *
	 * @param int $delta
	 */
	public function countDelta($delta = 0)
	{
		$delta = ( int ) $delta;
		$this->tag_count += $delta;
		$this->tag_count = max ( $this->tag_count, 0 );
	}
	/**
	 * 将该标签从目标成就上揭下来, 如果该标签本来就没有贴在该成就上，则返回0
	 *
	 * @param int $achievement_id
	 * @return boolean 揭掉了，返回true
	 */
	public function removeFromAchievement($achievement_id)
	{
		$this->needPKValue ( 'Current Tag is empty.' );

		$tagToAchievement = TagToAchievementPeer::model ()->getTagAchievementByPK ( $achievement_id, $this->tag_id );
		if ($tagToAchievement->delete ())
		{
			$this->tag_count --;
			$this->save ();
			return true;
		}
		return false;
	}
	/**
	 * 将该标签贴到目标成就上, 如果该标签已经贴在该成就上，则返回0
	 *
	 * @param int $achievement_id
	 * @return int 贴成功了，返回1
	 */
	public function appendToAchievement($achievement_id)
	{
		$this->needPKValue ( 'Current Tag is empty.' );

		$tagToAchievement = new TagToAchievementPeer ( array ('achievement_id' => $achievement_id, 'tag_id' => $this->tag_id ) );
		$num = $tagToAchievement->save ();
		if ($num)
		{
			$this->tag_count ++;
			$this->save ();
		}
		return $num;
	}
}

?>