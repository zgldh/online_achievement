<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{

    private $_javascripts = array();
    private $_auto_javascript_codes = array();
    private $_styles = array();
    private $_title = null;
    
    private $_show_navbar = true;
    
    public function showNavBar()
    {
        $this->setNavBar(true);
    }
    public function hideNavBar()
    {
        $this->setNavBar(false);
    }
    public function toggleNavBar()
    {
        $this->setNavBar(!$this->getNavBar());
    }
    public function setNavBar($show)
    {
        $this->_show_navbar = (boolean)$show;
    }
    public function getNavBar()
    {
        return $this->_show_navbar;
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
        if($this->getNavBar())
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
        if($this->getNavBar())
        {
            $this->load->view('common/navbar');
        }
        $this->load->view($view_path);
        $this->load->view('common/footer');
    }
}
// END Controller class

/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */