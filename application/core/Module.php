<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HMVC Module 类
 *
 * HMVC 核心对象
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		Hex
 * @category	HMVC
 * @link		http://codeigniter.org.cn/forums/thread-1319-1-2.html
 */
class CI_Module {

	/**
	 * 当前正在访问的用户。
	 * @var WebUser
	 */
	public $webuser = null;

	/**
	 *
	 * @var JavascriptCssManager
	 */
	public $javascript_css_manager = null;
	
	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct()
	{
		// 实例化自己的 Loader 类
		$CI =& get_instance();
		$this->load = clone $CI->load;

		// CI 系统对象采用引用传递的方式，直接赋值给 Module。
		// 当然也可以采用 clone 的方式，可能需要根据不同项目做权衡。
		foreach ($CI->load->get_base_classes() as $var => $class)
		{
			// 排除 Loader 类，因为已经 clone 过了
			if ($var == 'loader')
			{
				continue;
			}
			// 赋值给 Module
			$this->$var =& load_class($class);
		}
		// 处理自动装载的类库和模型
		$autoload = array_merge($CI->load->_ci_autoload_libraries, $CI->load->_ci_autoload_models);
		foreach ($autoload as $item)
		{
			if (!empty($item) and isset($CI->$item))
			{
				$this->$item =& $CI->$item;
			}
		}
		// 处理数据库对象
		if (isset($CI->db))
		{
			$this->db =& $CI->db;
		}

		// 利用 PHP5 的反射机制，动态确定 Module 类名和路径
		$reflector = new ReflectionClass($this);

		$path = substr(dirname($reflector->getFileName()), strlen(realpath(APPPATH.'modules').DIRECTORY_SEPARATOR));
		$class_path = implode('/', array_slice(explode(DIRECTORY_SEPARATOR, $path), 0, -1));
		$class_name = $reflector->getName();

		// 通知 Loader 类，Module 就绪
		$this->load->_ci_module_ready($class_path, $class_name);

		// 把自己放到全局超级对象中
		$CI->$class_name = $this;
		
		$this->load->library('WebUser');

// 		include_once(dirname(__FILE__).'/JavascriptCssManager.php');
// 		$this->javascript_css_manager = new JavascriptCssManager();

		log_message('debug', "$class_name Module Class Initialized");
	}
	

	/**
	 * 得到请求方法
	 * @return string “GET”, “HEAD”，“POST”，“PUT”
	 */
	protected function getRequestMethod()
	{
		return $_SERVER['REQUEST_METHOD'];
	}
	
	/**
	 * 是否是post请求
	 * @return boolean true|false
	 */
	protected function isPostRequest()
	{
		return ($this->getRequestMethod() == 'POST')?true:false;
	}
	/**
	 * 是否是get请求
	 * @return boolean true|false
	 */
	protected function isGetRequest()
	{
		return ($this->getRequestMethod() == 'GET')?true:false;
	}
	
	
	public function inputPost($key, $xss_filter = false)
	{
		return $this->input->post ( $key, $xss_filter );
	}
	
	public function inputGet($key, $xss_filter = false)
	{
		return $this->input->get ( $key, $xss_filter );
	}
	

	/**
	 * 得到JSONP输出字符串
	 * @param string $callback    回调javascript函数名字
	 * @param any $parameter      回调参数
	 * @param string $iframe_id = false  如果输出在iframe里面， 则需要在这里提供iframe的id
	 * @return string 是一个&lt;script&gt;...js代码...&lt;/script&gt; 的字符串
	 */
	public function getJSONP($callback,$parameter, $iframe_id = false)
	{
		$parameter = json_encode($parameter);
		$str = '<script>';
		if($iframe_id)
		{
			$str.= 'parent.'.$callback.'('.$parameter.',"'.$iframe_id.'");';
		}
		else
		{
			$str.= $callback.'('.$parameter.');';
		}
		$str .= '</script>';
		return $str;
	}
	
	/**
	 * 需要登录，不然直接断掉
	 */
	public function needLoginOrExit()
	{
		if(!$this->webuser->isLogin())
		{
			exit();
		}
	}
	
}

// END Module Class

/* End of file Module.php */
/* Location: ./application/core/Module.php */
