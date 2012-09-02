<?php
$achievement instanceof AchievementPeer;
$intent instanceof IntentPeer;
$creater = $achievement->getCreater();
?>

<div class="container-fluid detail-content">
	<div class="page-header">
	    <img class="thumbnail pull-left" src="<?php echo $achievement->getLogo()->getFileURL();?>" />
	    <div class="header-right pull-left">
    		<h1><?php echo $achievement->name; ?></h1>
            <blockquote>
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
            <?php if($intent):?>
            <div class="right-item alert alert-success">
                <h4>执行中</h4>
            </div>
            <?php else:?>
            <div class="right-item alert">
                <h4>您还没有实现该成就</h4>
                <a href="/achievements/work_intent/<?php echo $achievement->achievement_id;?>">现在就开始！</a>
            </div>
            <?php endif;?>
            
            
            <div class="right-item procedures-box">
                <h4>实现步骤</h4>
                <?php $procedures = $achievement->getProcedures();?>
                <?php if($procedures):?>
                <ol>
                <?php foreach($procedures as $procedure):?>
                <?php $procedure instanceof ProcedurePeer;?>
                    <li>
                        <span class="procedure-desc"><?php echo $procedure->description;?></span>
                        <div class="procedure-tools btn-group">
<!--                             <span type="button" class="btn btn-small disabled btn-success"><i class="icon-ok-sign icon-white"></i> 已完成</span>  -->
<!--                             <button type="button" class="btn btn-small btn-info"><i class="icon-comment icon-white"></i> 评论</button> -->
                            <span type="button" class="btn btn-small"><i class="icon-ok"></i> 我完成了</span>
                            <button type="button" class="btn btn-small procedure_tools_view_track_btn" data-original-title="看看别人是怎么完成的"><i class="icon-eye-open"></i></button>
                        </div>
                    <?php $sub_procedures = $procedure->getSubProcedures();?>
                    <?php if($sub_procedures):?>
                    <ol>
                    <?php foreach($sub_procedures as $sub_procedure):?>
                        <li>
                            <span class="procedure-desc"><?php echo $sub_procedure->description;?></span>
                            <div class="procedure-tools btn-group">
<!--                             <button type="button" class="btn btn-small btn-success"><i class="icon-ok-sign icon-white"></i> 完成</button> -->
                                <button type="button" class="btn btn-small"><i class="icon-ok-sign"></i> 完成</button>
                                <button type="button" class="btn btn-small btn-info"><i class="icon-comment icon-white"></i> 评论</button>
                            </div>
                     </li>
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
                <div>//TODO 这里显示本成就的 intent标记为完成的用户头像、 对本成就留下的最后一个track</div>
                <hr />
            </div>
            <div class="right-item">
                <h4>这些人正在努力</h4>
                <div>//TODO 这里显示本成就的 intent标记为进行中的用户头像、 对本成就留下的最后一个track</div>
                <hr />
            </div>
            <div class="right-item">
                <h4>大家怎么说</h4>
                <div>//TODO 这里显示本成就的 comment 列表</div>
                <hr />
            </div>
        </div>
        <!-- 预览完成 结束 -->
    </div>
</div>