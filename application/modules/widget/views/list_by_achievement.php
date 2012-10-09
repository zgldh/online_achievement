<?php 
$achievement_id;
$comments;
$page;
$total_num;
$total_page = (int)($total_num / Widget_comment_module::PAGE_SIEZ)+1;
?>
    <?php if($comments && is_array($comments)):?>
    <?php foreach($comments as $comment):?>
    <?php $comment instanceof CommentPeer;?>
    	<div>
    		<img class="thumbnail" src="<?php echo IMG_PLACEHOLDER_64x64;?>"
    			style="display:inline-block;margin: 0 10px 10px 0;"/>
    		<div style="display:inline-block;vertical-align: top;">
    			<strong><?php echo $comment->getUser()->name;?></strong>
    			<span><?php echo $comment->post_date;?></span><br />
    			<?php echo $comment->content;?>
    		</div>
    	</div>
    <?php endforeach;?>
    <?php if($total_num > Widget_comment_module::PAGE_SIEZ):?>
    <?php //TODO pagination ajax载入评论?>
    <div class="pagination">
	    <ul>
	    	<li<?php echo $page == 1?' class="active"':'';?>><a href="#">1</a></li>
    	<?php if($page > 5):?>
	    	<li class="disabled"><a>...</a></li>
    	<?php endif;?>
	    <?php for($i=$page - 5;$i<=$page + 5;$i++):?>
	    	<?php if($i>1 && $i < $total_page):?>
		    <li<?php echo $page == $i?' class="active"':'';?>><a href="#"><?php echo $i;?></a></li>
		    <?php endif;?>
	    <?php endfor;?>
    	<?php if($page < $total_page - 5):?>
	    	<li class="disabled"><a>...</a></li>
    	<?php endif;?>
		    <li<?php echo $page == $total_page?' class="active"':'';?>><a href="#"><?php echo $total_page;?></a></li>
	    </ul>
    </div>
    <?php endif;?>
    <?php else:?>
        <div class="alert">目前还没有评论。不占个沙发么？</div>
    <?php endif;?>
