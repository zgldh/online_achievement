<?php 
$achievement_id;
?>
<!-- 评论列表 begin -->
<div class="widget_comment list_by_achievement">
    <script type="text/javascript" src="/js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.js"></script>
    <script type="text/javascript" src="/js/widget_comment.js"></script>
    <div class="widget_list">
    <?php $this->load->module('/widget/comment/list_by_achievement',array($achievement_id,1));?>
    </div>
    <?php if($this->webuser->isLogin()):?>
    <form class="widget_comment_post_form" 
    	action="/module/widget/comment/ajax_post/<?php echo $achievement_id;?>" 
    	method="post"
    	data-load-url="/module/widget/comment/list_by_achievement/<?php echo $achievement_id;?>">
    	<a name="widget_comment_post_form"></a>
        <div class="alert alert-info widget_reference hide">
            <span class="content"></span>
            <button type="button" class="close" >撤销</button>
        </div>
        <div class="control-group">
            <textarea class="widget_comment_textarea" style="width:100%;min-height:150px;" ></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-large" data-loading-text="正在提交...">提交评论</button>
        <input class="input_track_id" type="hidden" name="track_id" value="" />
        <input class="input_reply_comment_id" type="hidden" name="reply_comment_id" value="" />
    </form>
    <?php else:?>
    <div class="alert">您还没有登录，请先登录再评论。</div>
    <?php endif;?>
</div>
<!-- 评论列表 end -->