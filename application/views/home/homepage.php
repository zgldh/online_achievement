<div>
    <div id="home_wall">
    	<?php foreach($achievements as $achievement):?>
        <div class="home_wall_item">
        	<img
        		alt="<?php echo $achievement->name;?>"
        		title="<?php echo $achievement->name;?>"
        		src="<?php echo $achievement->getLogo()->getFileURL();?>" />
        </div>
        <?php endforeach;?>
        <div style="clear: both;"></div>
    </div>
<!--     <h1>快来实现你的成就!</h1> -->
<!-- 	<p class="">良好的规划，非凡的目标，让你的生活充满乐趣。</p> -->
<!--     <a class="btn btn-primary btn-large" href="/create">立刻开始!</a> -->
<!--     <a class="btn btn-info btn-large">更多介绍>></a> -->
</div>
