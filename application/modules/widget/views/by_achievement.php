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
    	action="/comment/ajax_post/<?php echo $achievement_id;?>" 
    	method="post"
    	data-load-url="/comment/ajax_get_list/<?php echo $achievement_id;?>">
        <div class="control-group">
            <textarea class="widget_comment_textarea" style="width:100%;min-height:150px;" ></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-large" data-loading-text="正在提交...">提交评论</button>
    </form>
    <?php else:?>
    <div class="alert">您还没有登录，请先登录再评论。</div>
    <?php endif;?>
</div>
<!-- 评论列表 end -->