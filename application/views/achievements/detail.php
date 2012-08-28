<?php
$achievement instanceof AchievementPeer;
$creater = $achievement->getCreater();
?>

<div class="container-fluid detail-content">
	<div class="page-header">
	    <img class="thumbnail pull-left" src="<?php echo $achievement->getLogo()->getFileURL();?>" />
	    <div class="header-right pull-left">
    		<h1><?php echo $achievement->name; ?></h1>
            <blockquote class="pull-right">
                <p><?php echo $achievement->requirement;?></p>
                <small><?php echo $creater->name; ?></small>
            </blockquote>
        </div>
        <div style="clear: both;"></div>
	</div>
    <div class="left-content row-fluid">
        <!-- 左侧栏 开始 -->
        <div class="span3">
            <div class="left-item">
                <h4>标签</h4>
                <div>
                    <?php $tags = $achievement->getTags();?>
                    <?php foreach($tags as $tag):?>
                    <?php $tag instanceof TagPeer;?>
                    <a href=""><?php echo $tag->tag_name;?></a>
                    <?php endforeach;?>
                </div>
            </div>
            <div class="left-item">
                <h4>前提条件</h4>
                <div>
                </div>
            </div>
            <div class="left-item">
                <h4>后续成就</h4>
                <div>
                </div>
            </div>
            <div class="left-item">
                <h4>类似成就</h4>
                <div>
                </div>
            </div>
        </div>
        <!-- 左侧栏 结束 -->

        <!-- 基本信息 开始 -->
        <div class="right-content span9">
            <div class="right-item alert">
                <h4>您还没有实现该成就</h4>
                <a>现在就开始！</a>
            </div>
            <div class="right-item">
                <h4>实现步骤</h4>
                <?php $procedures = $achievement->getProcedures();?>
                <?php if($procedures):?>
                <ol>
                <?php foreach($procedures as $procedure):?>
                <?php $procedure instanceof ProcedurePeer;?>
                    <li><?php echo $procedure->description;?>
                    <?php $sub_procedures = $procedure->getSubProcedures();?>
                    <?php if($sub_procedures):?>
                    <ol>
                    <?php foreach($sub_procedures as $sub_procedure):?>
                        <li><?php echo $sub_procedure->description;?></li>
                    <?php endforeach;?>
                    </ol>
                    <?php endif;?>
                    </li>
                <?php endforeach;?>
                </ol>
                <?php else:?>
                <p>完成本成就不需要步骤。。</p>
                <?php endif;?>
                <hr />
            </div>
            <div class="right-item">
                <h4>这些人完成了本成就</h4>
                <div></div>
                <hr />
            </div>
            <div class="right-item">
                <h4>这些人正在努力</h4>
                <div></div>
                <hr />
            </div>
            <div class="right-item">
                <h4>大家怎么说</h4>
                <div></div>
                <hr />
            </div>
        </div>
        <!-- 预览完成 结束 -->
    </div>
</div>
