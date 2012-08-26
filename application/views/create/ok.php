<?php 
$achievement instanceof AchievementPeer;
?>
<div class="alert alert-success">
    <h2>创建完毕</h2>
    <p>快点着手实现这个成就吧！</p>
</div>
<div class="created_ok_preview_box" >
	<h3><?php echo $achievement->name;?></h3>
	<img class="thumbnail" src="<?php echo $achievement->getLogo()->getFileURL();?>" />
	<a href="<?php echo BASEURL;?>detail/<?php echo $achievement->achievement_id;?>" class="btn btn-large btn-primary ">》 现在去看看 》</a>
</div>
