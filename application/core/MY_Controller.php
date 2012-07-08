<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{

    private $_javascripts = array();
    private $_styles = array();
    private $_title = null;
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
    
    protected function view($view_path, $in_data = array())
    {
        $meta_data = array(
                'title'=>$this->getTitle(),
                'javascripts'=>$this->_javascripts,
                'styles'=>$this->_styles                
                );
        $data = array_merge($meta_data, $in_data);
        
        $this->load->view('common/header',$data);
        $this->load->view($view_path);
        $this->load->view('common/footer');
    }
}
// END Controller class

/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */