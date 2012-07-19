<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class create extends MY_Controller
{

	/**
	 * 创建单个成就的编辑器
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/create
	 * 		http://example.com/index.php/edit
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        if(!$this->webuser->isLogin())
        {
            $this->redirectToSignIn('/create');
        }

	    $this->addJavascriptFile('js/jquery.nestable.js');
	    $this->addJavascriptFile('js/create.js');
	    $this->addAutoRunJavascriptCode("$('.dd').nestable();");
	    $this->addStyleFile('css/icons_big.css');
	    $this->addStyleFile('css/create.css');

	    $this->navbar->setCurrentItem(NavBar::$ITEM_CREATE);

	    $this->setTitle("编写成就--在线成就系统");
		$this->view('/create/create');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */