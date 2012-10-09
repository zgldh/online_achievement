<?php

if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Achievements extends MY_Controller
{
    public function all()
    {
        $this->navbar->setCurrentItem ( NavBar::ITEM_ALL );
        $this->setTitle ( "所有成就--在线成就系统" );
        $this->view ( '/achievements/all' );
    }

    /**
     * 一个成就的详细页面
     *
     * @param int $achievement_id
     */
    public function detail($achievement_id)
    {
        $this->navbar->setCurrentItem ( NavBar::ITEM_NONE );

        $this->loadAchievementModel ();
        $achievement = AchievementPeer::model ()->getByPK ( $achievement_id );

        if (! $achievement)
        {
            show_404 ();
        }

        $intent = $achievement->getIntentByUser($this->webuser->getUserId());

        $complete_intents = $achievement->getIntents(true,$this->webuser->getUserId(),new DB_Limit(7));
        $processing_intents = $achievement->getIntents(false,$this->webuser->getUserId(),new DB_Limit(3));

        $this->setTitle ( $achievement->name . "--在线成就系统" );

        $this->addJavascriptFile ( '/js/detail_intent.js' );
        $this->addStyleFile ( '/css/detail.css' );
        $this->addStyleFile ( '/css/icon_big.css' );

        $data = compact ( 'achievement', 'intent','complete_intents','processing_intents');
        $this->view ( 'achievements/detail', $data );
    }

    /**
     * 开始向某成就努力<br />
     * 如果条件允许，则添加该intent然后重定向到/detail/$achievement_id<br />
     * 会检测： 是否登录， 前提条件 等
     *
     * @param int $achievement_id
     */
    public function work_intent($achievement_id)
    {
        $this->needLoginOrExit ( '/achievements/work_intent/' . $achievement_id );

        $error = $this->webuser->getUser ()->createIntent ( $achievement_id );
        if ($error)
        {
            // 有错误。设置错误信息
            $this->webuser->setSessFlashData ( 'error', $error );
        }

        $this->load->helper ( 'url' );
        redirect ( '/detail/' . $achievement_id );
    }

    /**
     * 向一个procedure 添加一条 track<br />
     * 如果条件允许，则添加该 track 然后重定向到/detail/$achievement_id<br />
     * 会检测： 是否登录， 当前用户是否有该成就的 intent
     *
     * @param
     *            Post Form =
     *            array('achievement_id','intent_id','procedure_id','content')
     */
    public function work_add_track()
    {
        $form = $this->inputPost ( 'Form' );
        if (trim(@$form ['content']))
        {
            $this->needLoginOrExit ( '/detail/' . $form ['achievement_id'] );

            $this->loadIntentModel ();
            $intent = IntentPeer::model ()->getByPK ( $form ['intent_id'] );

            if ($intent && $intent->user_id == $this->webuser->getUserId ())
            {
                $this->loadProcedureModel ();
                $procedure = ProcedurePeer::model ()->getByPK ( $form ['procedure_id'] );

                if ($procedure && $procedure->achievement_id == $form ['achievement_id'])
                {
                    $this->loadTrackModel ();
                    $track = new TrackPeer ();
                    $track->achievement_id = $procedure->achievement_id;
                    $track->intent_id = $intent->intent_id;
                    $track->procedure_id = $procedure->procedure_id;
                    $track->content = trim($form ['content']);
                    $track->save ();
                }
            }
        }
        $this->load->helper ( 'url' );
        redirect ( '/detail/' . $form ['achievement_id'] );
    }

    /**
     * 完成一个成就的 intent <br />
     * 如果条件允许，则设置该 intent 为完成状态。 然后重定向到/detail/$achievement_id<br />
     * 会检测： 是否登录， 当前用户是否有该成就的 intent , 步骤是否都完成了
     *
     * @param int $intent_id
     */
    public function work_complete($intent_id)
    {
        $this->needLoginOrExit ( '/achievements/work_complete/' . $intent_id );

        // 检测是否已经有该intent
        $this->loadIntentModel ();

        $error = array ();

        $intent = IntentPeer::model ()->getByPK ( $intent_id );
        if (! $intent)
        {
            // 没有该intent
            $error [] = '你没有开始做这个成就';
        }
        elseif (! $intent->isAllProceduresComplete ())
        {
            // 还没有全部完成
            $error [] = '你还有要求的步骤没有完成';
        }
        else
        {
            // 都完成了
            $intent->complete ();
        }

        if ($error)
        {
            // 有错误。设置错误信息
            $this->webuser->setSessFlashData ( 'error', $error );
        }

        $this->load->helper ( 'url' );
        redirect ( '/detail/' . $intent->achievement_id );
        exit ();
    }

    /**
     * 完成某步骤时的弹出窗口的表单内容 <br />
     * 该表单会被提交到 achievements::work_add_track
     *
     * @see achievements::work_add_track
     * @param int $intent_id
     * @param int $procedure_id
     */
    public function modal_procedure_done_form($intent_id, $procedure_id)
    {
        $this->needLoginOrExit ();

        $this->loadProcedureModel ();
        $procedure = ProcedurePeer::model ()->getByPK ( $procedure_id );
        $intent_id = ( int ) $intent_id;

        $data = compact ( 'intent_id', 'procedure' );
        $this->load->view ( '/achievements/modal_procedure_done_form', $data );
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */