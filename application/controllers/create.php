<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class create extends MY_Controller
{
    
    /**
     * 创建单个成就的编辑器
     *
     * Maps to the following URL
     * http://example.com/index.php/create
     * http://example.com/index.php/edit
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     *
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        if (! $this->webuser->isLogin ())
        {
            $this->signinAndRedirectTo ( '/create' );
        }
        
        if ($this->isPostRequest ())
        {
            $errors = $this->save ();
            
            $this->load->helper ( 'url' );
            redirect ( '/create/ok' );
        }
        
        $this->addJavascriptFile ( '/js/jquery.json-2.3.min.js' );
        $this->addJavascriptFile ( '/js/select2/select2.min.js' );
        $this->addJavascriptFile ( '/js/bootstrap/bootstrap.min.js' );
        $this->addJavascriptFile ( '/js/jquery.nestable.js' );
        $this->addJavascriptFile ( '/js/jcrop/jquery.Jcrop.min.js' );
        $this->addJavascriptFile ( '/js/jquery.autosize.js' );
        $this->addAutoRunJavascriptCode ( "$('textarea').autosize();" );
        $this->addJavascriptFile ( '/js/create.js' );
        
        $this->addStyleFile ( '/js/select2/select2.css' );
        $this->addStyleFile ( '/js/jcrop/jquery.Jcrop.min.css' );
        $this->addStyleFile ( '/css/icon_big.css' );
        $this->addStyleFile ( '/css/create.css' );
        
        $this->navbar->setCurrentItem ( NavBar::ITEM_CREATE );
        
        $this->setTitle ( "编写成就--在线成就系统" );
        $this->view ( '/create/create' );
    }
    /**
     * logo上传的响应函数<br />
     * 返回一个jsonp脚本
     */
    public function jsonp_logo_upload()
    {
        $this->needLoginOrExit ();
        
        $config ['upload_path'] = 'uploads';
        $config ['allowed_types'] = 'gif|jpg|png';
        $this->load->library ( 'upload', $config );
        $callback = $this->inputGet ( 'callback' );
        $iframe_id = $this->inputGet ( 'iframe_id' );
        if (! $this->upload->do_upload ( 'file' ))
        {
            $re = array (
                    'error_msg' => $this->upload->error_msg 
            );
            echo $this->getJSONP ( $callback, $re, $iframe_id );
        }
        else
        {
            $data = $this->upload->data ();
            
            $this->loadUploadedModel ();
            $uploaded = new UploadedPeer ( array (
                    'file_name' => $data ['file_name'], 
                    'file_ext' => $data ['file_ext'], 
                    'relative_path' => $data ['relative_path'], 
                    'size' => filesize ( $data ['full_path'] ), 
                    'file_type' => UploadedPeer::FILE_TYPE_LOGO, 
                    'user_id' => $this->webuser->getUserId (), 
                    'statues' => 'processing' 
            ) );
            $uploaded->preResize ();
            $uploaded->save ();
            
            $image_url = '/' . $this->upload->relative_path . $data ['file_name'];
            $image_size = getimagesize ( $data ['full_path'] );
            $re = array (
                    'image_url' => $image_url, 
                    'image_width' => $image_size [0], 
                    'image_height' => $image_size [1], 
                    'uploaded_id' => $uploaded->uploaded_id 
            );
            
            echo $this->getJSONP ( $callback, $re, $iframe_id );
        }
    }
    public function ok()
    {
        $this->navbar->hideSignIn ();
        $this->navbar->setCurrentItem ( NavBar::ITEM_CREATE );
        $this->setTitle ( "成就创建成功！--在线成就系统" );
        $this->addStyleFile ( '/css/create.css' );
        
        $this->loadAchievementModel ();
        $achievement = AchievementPeer::model ()->getLastCreatedByUserID ( $this->webuser->getUserId () );
        
        $data = compact ( 'achievement' );
        
        $this->view ( '/create/ok', $data );
    }
    
    /**
     * 保存成就
     * 收集刚填写的信息，储存起来
     */
    private function save()
    {
        /**
         * *
         * Achievement[categories]	技能,自行车
         * Achievement[description]	学会骑两轮自行车
         * Achievement[logo_crop]	{"x":0,"y":0,"x2":256,"y2":256,"w":256,"h":256}
         * Achievement[logo_src]	/uploads/f8/8c/b6/f88cb6b7d55d1b80bf7f7f8caf04f2d2/100103104015z01dgjic0yzl.jpg
         * Achievement[name] 会骑自行车
         * Achievement[procedure]	[{"text":"step1","children":[{"text":"step1.1"}]},{"text":"step2","children":[{"text":"step2.1"},{"text":"step2.2"}]}]
         * Achievement[uploaded_id]	15
         */
        $form = $this->inputPost ( 'Achievement' );
        $form ['description'] = trim ( $form ['description'] );
        $form ['name'] = trim ( $form ['name'] );
        
        $errors = $this->validate ( $form );
        if ($errors)
        {
            return $errors;
        }
        
        // 缩放logo，并且标记为已保存
        $this->loadUploadedModel ();
        $logo = UploadedPeer::model ()->getByPK ( $form ['uploaded_id'] );
        if ($logo->user_id != $this->webuser->getUserId ())
        {
            // TODO 当logo上传者id和当前用户id不一样的时候
        }
        $logo->crop ( json_decode ( $form ['logo_crop'] ) );
        $logo->markAsSaved ();
        $logo->save ();
        
        // 储存成就信息
        $this->loadAchievementModel ();
        $achievement = new AchievementPeer ();
        $achievement->creater_id = $this->webuser->getUserId ();
        $achievement->logo_id = $logo->uploaded_id;
        $achievement->name = $form ['name'];
        $achievement->requirement = $form ['description'];
        $achievement->status = 2;
        $achievement->track_type = 1;
        $achievement->save ();
        
        // 储存步骤
        $achievement->setProcedureFromJSON ( json_decode ( $form ['procedure'] ), true );
        
        // 储存标签分类
        $tags = explode ( ',', trim ( $form ['categories'] ) );
        $achievement->setTags ( $tags );
        
        // 删除掉临时文件
        UploadedPeer::model ()->deleteProcessingFilesByUserID ( $this->webuser->getUserId () );
    }
    
    /**
     * 验证表单
     *
     * @param array $form            
     * @return array()
     */
    private function validate($form)
    {
        /**
         * *
         * Achievement[categories]	技能,自行车
         * Achievement[description]	学会骑两轮自行车
         * Achievement[logo_crop]	{"x":0,"y":0,"x2":256,"y2":256,"w":256,"h":256}
         * Achievement[logo_src]	/uploads/f8/8c/b6/f88cb6b7d55d1b80bf7f7f8caf04f2d2/100103104015z01dgjic0yzl.jpg
         * Achievement[name] 会骑自行车
         * Achievement[procedure]	[{"text":"step1","children":[{"text":"step1.1"}]},{"text":"step2","children":[{"text":"step2.1"},{"text":"step2.2"}]}]
         * Achievement[uploaded_id]	15
         */
        $errors = array ();
        if (! $form ['name'])
        {
            $errors ['name'] = '请填写成就名称';
        }
        if (! $form ['description'])
        {
            $errors ['description'] = '请填写成就描述';
        }
        if (! $form ['uploaded_id'])
        {
            $errors ['uploaded_id'] = '请为成就选择一个LOGO';
        }
        return $errors;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */