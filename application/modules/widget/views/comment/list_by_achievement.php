<?php 
$achievement_id;
$comments;
$page;
$total_num;
$total_page = ceil($total_num / Widget_comment_module::PAGE_SIEZ);

$pagination_html = '';
?>
    <?php if($comments && is_array($comments)):?>
        
        <?php /** 生成分页 begin **/?>
        <?php if($total_num > Widget_comment_module::PAGE_SIEZ):?>
        <?php ob_start();?>
        <?php //pagination ajax载入评论?>
        <div class="pagination">
    	    <ul>
    	    	<li<?php echo $page == 1?' class="active"':'';?>><a href="/module/widget/comment/list_by_achievement/<?php echo $achievement_id;?>/<?php echo 1;?>">1</a></li>
        	<?php if($page > 5):?>
    	    	<li class="disabled"><a>...</a></li>
        	<?php endif;?>
    	    <?php for($i=$page - 5;$i<=$page + 5;$i++):?>
    	    	<?php if($i>1 && $i < $total_page):?>
    		    <li<?php echo $page == $i?' class="active"':'';?>><a href="/module/widget/comment/list_by_achievement/<?php echo $achievement_id;?>/<?php echo $i;?>"><?php echo $i;?></a></li>
    		    <?php endif;?>
    	    <?php endfor;?>
        	<?php if($page < $total_page - 5):?>
    	    	<li class="disabled"><a>...</a></li>
        	<?php endif;?>
    		    <li<?php echo $page == $total_page?' class="active"':'';?>><a href="/module/widget/comment/list_by_achievement/<?php echo $achievement_id;?>/<?php echo $total_page;?>"><?php echo $total_page;?></a></li>
    	    </ul>
        </div>
        <?php $pagination_html = ob_get_clean();?>
        <?php /** 生成分页 end **/?>
        
        <?php echo $pagination_html;?>
        <?php endif;?>
        
        <?php foreach($comments as $comment):?>
        <?php $comment instanceof CommentPeer;?>
        	<div>
        		<img class="thumbnail" src="<?php echo IMG_PLACEHOLDER_64x64;?>"
        			style="display:inline-block;margin: 0 10px 10px 0;"/>
        		<div style="display:inline-block;vertical-align: top;">
        			<strong><?php echo $comment->getUser()->name;?></strong>
        			<span><?php echo $comment->post_date;?></span><br />
        			<?php if($comment->track_id && $tmp_track = $comment->getTrack()):?>
        			    <div class="alert alert-info"><strong>评论：</strong><span><?php echo $tmp_track->content; ?></span></div>
        			<?php elseif($comment->reply_comment_id && $tmp_comment = $comment->getParentComment()):?>
        			    <div class="alert alert-info"><strong>回复：</strong><span><?php echo $tmp_comment->content;?></span></div>
        			<?php endif;?>
        			<?php echo $comment->content;?>
        		</div>
        	</div>
        	<hr />
        <?php endforeach;?>
        
        <?php echo $pagination_html;?>
        
    <?php else:?>
        <div class="alert">目前还没有评论。不占个沙发么？</div>
    <?php endif;?>
