<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{

    /**
     * 当前正在访问的用户。
     * @var WebUser
     */
    public $webuser = null;

    /**
     * 顶部导航栏
     * @var NavBar
     */
    public $navbar = null;

    private $_javascripts = array();
    private $_auto_javascript_codes = array();
    private $_styles = array();
    private $_title = null;

    private $_show_navbar = true;
    private $_current_navbar_item = 'home';

    function __construct()
    {
        parent::__construct();
    }


    /**
     *
     * @return WebUser
     */
    public function getWebUser()
    {
        return $this->_webuser;
    }
    /**
     *
     * @param WebUser $webuser
     */
    public function setWebUser($webuser)
    {
        $this->_webuser = $webuser;
    }

    /**
     * 设置页面标题
     * @param string $str
     */
    public function setTitle($str)
    {
        $this->_title = $str;
    }
    /**
     * 得到页面标题
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * 添加一个Javascript文件
     * @param String $path
     * @return boolean true成功添加 false已经存在
     */
    public function addJavascriptFile($path)
    {
        if(!array_key_exists($path, $this->_javascripts))
        {
            $this->_javascripts[] = $path;
            return true;
        }
        return false;
    }
    /**
     * 添加一段自动执行的Javascript代码
     * @param String $path
     * @return boolean true成功添加 false已经存在
     */
    public function addAutoRunJavascriptCode($code)
    {
        $this->_auto_javascript_codes[] = $code;
    }
    /**
     * 添加一个css文件
     * @param String $path
     * @return boolean true成功添加 false已经存在
     */
    public function addStyleFile($path)
    {
        if(!array_key_exists($path, $this->_styles))
        {
            $this->_styles[] = $path;
            return true;
        }
        return false;
    }

    /**
     * 显示出一个模板来
     * @param string $view_path 模板路径
     * @param array $in_data 附加数据
     */
    protected function view($view_path, $in_data = array())
    {
        if($this->navbar->isDisplay())
        {
            $this->addJavascriptFile('/js/bootstrap-dropdown.js');
            $this->addAutoRunJavascriptCode("$('.dropdown-toggle').dropdown()");
        }

        $meta_data = array(
                'title'=>$this->getTitle(),
                'javascripts'=>$this->_javascripts,
                'auto_javascript_codes'=>$this->_auto_javascript_codes,
                'styles'=>$this->_styles
        );
        $data = array_merge($meta_data, $in_data);

        $this->load->view('common/header',$data);
        $this->load->view($view_path);
        $this->load->view('common/footer');
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

    public function signinAndRedirectTo($redirect_to_url = '/')
    {
        $redirect_to = urlencode($redirect_to_url);

        $this->load->helper('url');
        redirect('/signin?redirect_to='.$redirect_to);
        exit();
    }
}
// END Controller class

/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */