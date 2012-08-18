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
    
    /**
     * @var Uploaded_model
     */
    public $uploaded_model = null;
    /**
     * @var Achievement_model
     */
    public $achievement_model = null;
    /**
     * @var Procedure_model
     */
    public $procedure_model = null;
    /**
     * @var User_model
     */
    public $user_model = null;
    /**
     * @var Tags_model
     */
    public $tags_model = null;

    private $_javascripts = array();
    private $_auto_javascript_codes = array();
    private $_styles = array();
    private $_style_codes = array();
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
     * 添加一段css代码
     * @param String css code
     */
    public function addStyleCode($code)
    {
        $this->_style_codes[] = $code;
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
,
                            'css_codes'=>$this->_style_codes
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

	/**
	 * 跳转到登录页面， 登录后重定向到redirect_to_url
	 * @param string $redirect_to_url 登录后重定向到的位置
	 */
    public function signinAndRedirectTo($redirect_to_url = '/')
    {
        $redirect_to = urlencode($redirect_to_url);

        $this->load->helper('url');
        redirect('/signin?redirect_to='.$redirect_to);
        exit();
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
    
    protected function loadUserModel()
    {
	    $this->load->model('User_model','user_model',true);
    }
    protected function loadUploadedModel()
    {
	    $this->load->model('Uploaded_model','uploaded_model',true);
    }
    protected function loadAchievementModel()
    {
	    $this->load->model('Achievement_model','achievement_model',true);
    }
    protected function loadProcedureModel()
    {
	    $this->load->model('Procedure_model','procedure_model',true);
    }
    protected function loadTagsModel()
    {
	    $this->load->model('Tags_model','tags_model',true);
    }
}
// END Controller class

/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */