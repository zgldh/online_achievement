<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  用来控制顶部导航条
 */
class NavBar{

    public static $ITEM_HOME = 'home';
    public static $ITEM_CREATE = 'create';
    public static $ITEM_ALL = 'all';
    public static $ITEM_JUSTLOOK = 'justlook';
    public static $ITEM_REGISTER = 'register';
    public static $ITEM_SIGNIN = 'signin';
    public static $ITEM_TAGS = 'tags';

    private $_show_navbar = true;
    private $_current_navbar_item = 'home';
    private $_show_signin = true;
    private $_redirect_to = null;
    
    function __construct()
    {
    }
    
    /**
     * 得到当前处于active的项目
     * @return string
     */
    public function getCurrentItem()
    {
        return $this->_current_navbar_item;
    }
    /**
     * 设置某项目处于active
     * @param String $item NavBar::$ITEM_xxx
     */
    public function setCurrentItem($item)
    {
        $this->_current_navbar_item = $item;
    }
    /**
     * 检测$item_name,如果当前处于active，则返回字符串active
     * @return string
     */
    public function getItemClass($item_name)
    {
        if($item_name == $this->_current_navbar_item)
        {
            return 'active';
        }
        return '';
    }
    
    /**
     * 显示导航条
     */
    public function showNavBar()
    {
        $this->setNavBar(true);
    }
    /**
     * 不显示导航条
     */
    public function hideNavBar()
    {
        $this->setNavBar(false);
    }
    /**
     * 切换导航条的显示状态
     */
    public function toggleNavBar()
    {
        $this->setNavBar(!$this->getNavBar());
    }
    /**
     * 直接设置导航条的显示状态
     * @param boolean $show true显示, false不显示
     */
    public function setNavBar($show)
    {
        $this->_show_navbar = (boolean)$show;
    }
    /**
     * 当前导航条到底显示不显示？
     * @return boolean true显示, false不显示
     */
    public function isDisplay()
    {
        return $this->_show_navbar;
    }
    

    // 登录栏相关 start
    /**
     * 显示登录栏
     */
    public function showSignIn()
    {
    	$this->setSignIn(true);
    }
    /**
     * 不显示登录栏
     */
    public function hideSignIn()
    {
    	$this->setSignIn(false);
    }
    /**
     * 直接设置登录栏的显示状态
     * @param boolean $show true显示, false不显示
     */
    public function setSignIn($show)
    {
        $this->_show_signin = (boolean)$show;
    }
    /**
     * 当前登录栏到底显示不显示？
     * @return boolean true显示, false不显示
     */
    public function isDisplaySignIn()
    {
        return $this->_show_signin;
    }
    // 登录栏相关 end
    
    /**
     * 设置登录后重定向位置
     * @param unknown_type $url
     */
    public function setRedirectTo($url)
    {
    	$this->_redirect_to = $url;
    }
    /**
     * 得到登录后重定向位置
     */
    public function getRedirectTo()
    {
    	if($this->_redirect_to == null)
    	{
    		if(isset($_SERVER['REQUEST_URI']))
    		{
    			$this->_redirect_to = $_SERVER['REQUEST_URI'];
    		}
    		else
    		{
    			$this->_redirect_to = '/';
    		}
    	}
    	return $this->_redirect_to;
    }
}
// END Controller class

/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */