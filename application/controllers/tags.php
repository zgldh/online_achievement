<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Tags extends MY_Controller
{
	public function index()
	{
		$this->navbar->setCurrentItem ( NavBar::ITEM_TAGS );
		$this->setTitle ( "标签分类--在线成就系统" );
		$this->view ( '/tags/all' );
	}
	/**
	 * 用来查询可能的标签<br />
	 * 返回一个json字符串，被浏览器解析为一个json对象
	 */
	public function ajax_query_tags()
	{
		// TODO 将来可能要加上访问限制
		$query_name = $this->inputGet ( 'q' );
		$this->loadTagsModel ();
		$tags = TagPeer::model ()->getTagsByNameQuery ( $query_name );
		foreach ( $tags as $tag )
		{
			$tag->id = $tag->tag_name;
			$tag->text = $tag->tag_name;
			unset ( $tag->tag_id );
			unset ( $tag->tag_name );
			unset ( $tag->tag_count );
		}

		echo json_encode ( $tags );
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */