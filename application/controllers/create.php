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
			$this->signinAndRedirectTo('/create');
		}

		$this->addJavascriptFile('js/select2/select2.js');
		$this->addJavascriptFile('js/bootstrap.min.js');
		$this->addJavascriptFile('js/jquery.nestable.js');
		$this->addJavascriptFile('js/jcrop/jquery.Jcrop.min.js');
		$this->addJavascriptFile('js/create.js');
		$this->addAutoRunJavascriptCode("$('.dd').nestable();");
		$this->addStyleFile('js/select2/select2.css');
		$this->addStyleFile('js/jcrop/jquery.Jcrop.min.css');
		$this->addStyleFile('css/icons_big.css');
		$this->addStyleFile('css/create.css');
		$this->addStyleCode('.jcrop-handle{display:none;}');

		$this->navbar->setCurrentItem(NavBar::$ITEM_CREATE);

		$this->setTitle("编写成就--在线成就系统");
		$this->view('/create/create');
	}


	public function jsonp_logo_upload()
	{
		$config['upload_path'] = 'uploads';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '1000';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		$this->load->library('upload', $config);
		$callback = $this->inputGet('callback');
		$iframe_id = $this->inputGet('iframe_id');
		if ( ! $this->upload->do_upload('file'))
		{
			echo $this->getJSONP($callback, $this->upload->display_errors(), $iframe_id);
		}
		else
		{
			$data = $this->upload->data();

			$config['source_image'] = $data['full_path'];
			$config['width'] = 255;
			$config['height'] = 255;
			$this->load->library('image_lib', $config);
			$this->image_lib->resize();

			$image_url = '/'.$this->upload->relative_path.$data['file_name'];
			echo $this->getJSONP($callback, $image_url, $iframe_id);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */