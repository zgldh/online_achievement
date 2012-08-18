<?php
class Uploaded_model extends MY_Model
{
	const TABLE = 'oa_uploaded';
	
	/**
	 * 根据id得到一个上传的文件对象
	 *
	 * @param int $uploaded_id        	
	 * @return Ambigous <boolean, UploadedPeer>
	 */
	public function getByPK($uploaded_id)
	{
		$raw = $this->db->get_where ( Uploaded_model::TABLE, array (UploadedPeer::PK => $uploaded_id ) )->row_array ();
		$uploaded = $raw ? new UploadedPeer ( $raw ) : false;
		return $uploaded;
	}
	/**
	 * 根据id删除一个上传的文件记录
	 *
	 * @param int $uploaded_id        	
	 */
	public function deleteByPK($uploaded_id)
	{
		$this->db->delete ( Uploaded_model::TABLE, array (UploadedPeer::PK => $uploaded_id ) );
	}
	/**
	 * 更新数据 或 插入数据
	 *
	 * @param UploadedPeer $uploaded        	
	 */
	public function saveUploadedPeer(& $uploaded)
	{
		$this->db->set ( 'file_name', $uploaded->file_name );
		$this->db->set ( 'file_ext', $uploaded->file_ext );
		$this->db->set ( 'relative_path', $uploaded->relative_path );
		$this->db->set ( 'size', $uploaded->size );
		$this->db->set ( 'file_type', $uploaded->file_type );
		$this->db->set ( 'user_id', $uploaded->user_id );
		$this->db->set ( 'statues', $uploaded->statues );
		
		$pkValue = $uploaded->getPrimaryKeyValue ();
		if ($pkValue)
		{
			$this->db->where ( UploadedPeer::PK, $pkValue );
			$this->db->update ( Uploaded_model::TABLE );
		}
		else
		{
			$this->db->set ( 'upload_datetime', 'NOW()', false );
			$this->db->insert ( Uploaded_model::TABLE );
			$uploaded->setPrimaryKeyvalue ( $this->db->insert_id () );
		}
	}
	
	/**
	 * 删除一个用户上传的所有仍处于processing状态的文件
	 *
	 * @param int $user_id        	
	 */
	public function deleteProcessingFilesByUserID($user_id)
	{
		$results = $this->db->get_where ( Uploaded_model::TABLE, array ('user_id' => $user_id, 'statues' => UploadedPeer::STATUES_PROCESSING ) )->result_array ();
		foreach ( $results as $raw )
		{
			$uploaded = new UploadedPeer ( $raw );
			$uploaded->delete ();
		}
	}
}
class UploadedPeer extends BasePeer
{
	const PK = 'uploaded_id';
	
	/**
	 * 用于成就logo图
	 *
	 * @var string
	 */
	const FILE_TYPE_LOGO = 'logo';
	
	/**
	 * 文件状态： processing 临时，说不定就被删了;
	 *
	 * @var string
	 */
	const STATUES_PROCESSING = 'processing';
	/**
	 * 文件状态： saved:已保存，用户可以看见;
	 *
	 * @var string
	 */
	const STATUES_SAVED = 'saved';
	/**
	 * 文件状态: archive:已存档;
	 *
	 * @var string
	 */
	const STATUES_ARCHIVE = 'archive';
	public $uploaded_id = 0;
	/**
	 * 文件名
	 *
	 * @var string
	 */
	public $file_name = '';
	/**
	 * 扩展名
	 *
	 * @var string
	 */
	public $file_ext = '';
	/**
	 * 相对路径。
	 *
	 * @var unknown_type
	 */
	public $relative_path = '';
	/**
	 * 文件大小，字节
	 *
	 * @var int
	 */
	public $size = 0;
	/**
	 * UploadedPeer::FILE_TYPE_xxx
	 *
	 * @var string
	 */
	public $file_type = '';
	/**
	 * 上传用户id
	 *
	 * @var int
	 */
	public $user_id = 0;
	/**
	 * 上传时间戳
	 * 
	 * @var string
	 */
	public $upload_datetime = '';
	
	/**
	 * 图片状态: UploadedPeer::STATUES_xxx
	 *
	 * @var string
	 */
	public $statues = UploadedPeer::STATUES_PROCESSING;
	function __construct($raw = null)
	{
		parent::__construct ( $raw, __CLASS__ );
	}
	public function getPrimaryKeyName()
	{
		return UploadedPeer::PK;
	}
	
	/**
	 * 得到文件的URL地址
	 * @param string $base_url
	 * @return string
	 */
	public function getFileURL($base_url = BASEURL)
	{
		return $base_url.$this->relative_path.$this->file_name;
	}
	
	/**
	 * 保存
	 */
	public function save()
	{
		UploadedPeer::model ()->saveUploadedPeer ( $this );
	}
	/**
	 * 标记为 已保存 UploadedPeer::STATUES_SAVED
	 */
	public function markAsSaved()
	{
		$this->statues = UploadedPeer::STATUES_SAVED;
	}
	/**
	 * 标记为 临时 UploadedPeer::STATUES_PROCESSING
	 */
	public function markAsProcessing()
	{
		$this->statues = UploadedPeer::STATUES_PROCESSING;
	}
	/**
	 * 标记为 已存档 UploadedPeer::STATUES_ARCHIVE
	 */
	public function markAsArchive()
	{
		$this->statues = UploadedPeer::STATUES_ARCHIVE;
	}
	/**
	 * 删除,同时删除文件
	 *
	 * @param boolean $delete_file
	 *        	= true 是否同时删除文件
	 */
	public function delete($delete_file = true)
	{
		$this->needPKValue ( 'Current uploaded object is empty.' );
		UploadedPeer::model ()->deleteByPK ( $this->uploaded_id );
		$path = $this->relative_path . $this->file_name;
		if (file_exists ( $path ))
		{
			unlink ( $path );
		}
	}
	
	/**
	 *
	 * @return Uploaded_model
	 */
	public static function model()
	{
		$CI = & get_instance ();
		return $CI->uploaded_model;
	}
	
	/**
	 * 将本文件进行缩放。只有 UploadedPeer::FILE_TYPE_LOGO才能执行。
	 *
	 * @param obj $crop
	 *        	{"x":0,"y":0,"x2":256,"y2":256,"w":256,"h":256}
	 */
	public function resize($crop)
	{
		if (! $crop)
		{
			return false;
		}
		if (! isset ( $crop->x ) || ! isset ( $crop->y ) || ! isset ( $crop->w ) || ! isset ( $crop->h ))
		{
			throw new Exception ( 'Bad crop parameter. Need x, y, w, h.' );
		}
		if ($this->file_type != UploadedPeer::FILE_TYPE_LOGO)
		{
			return false;
		}
		
		$CI = & get_instance ();
		$CI->load->library ( 'image_moo' );
		
		$file_path = $this->relative_path . $this->file_name;
		$config ['source_image'] = $file_path;
		$config ['maintain_ratio'] = false;
		$config ['width'] = $crop->w;
		$config ['height'] = $crop->h;
		$config ['x_axis'] = $crop->x;
		$config ['y_axis'] = $crop->y;
		$CI->load->library ( 'image_lib', $config );
		$CI->image_lib->crop ();
		$CI->image_lib->clear ();
		
		$config ['width'] = 128;
		$config ['height'] = 128;
		$CI->image_lib->initialize ( $config );
		$CI->image_lib->resize ();
		
		$this->size = filesize ( $file_path );
		$this->save ();
		
		return true;
	}
}

?>