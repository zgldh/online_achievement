<?php
$achievement instanceof AchievementPeer;
$intent instanceof IntentPeer;
$complete_intents;
$processing_intents;
$creater = $achievement->getCreater();
$tracks = $intent?$intent->getTracks():null;
$procedures = $achievement->getProcedures();
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
        
        	<!-- 成就进度指示器 begin -->
            <?php if($intent):?>
	            <?php if($intent->isComplete()):?>
	            <div class="right-item alert alert-success">
	                <h4>恭喜！您已经于<span><?php echo $intent->achieve_date;?></span>实现了本成就</h4>
	            </div>
	            <?php else:?>
	            <div class="right-item processing-box">
	            	<div class="alert alert-info">
	            		<h4>执行中</h4>
	                </div>
	                <div class="processing-box-bar-container">
	                	<?php $tmp_tracks_count = count($tracks);?>
	                	<?php $tmp_procedures_count = count($procedures);?>
	                	<?php $tmp_track_rate = 100;?>
	                	<?php if($tmp_procedures_count != 0):?>
	                	<?php $tmp_track_rate = (int)(100* $tmp_tracks_count / $tmp_procedures_count);?>
	                	<?php endif;?>
	                	
	                	<?php if($tmp_track_rate == 100):?>
	                	<div class="progress-striped clearfix">
	                		<a class="btn btn-warning btn-large"
	                			href="<?php echo BASEURL;?>achievements/work_complete/<?php echo $intent->intent_id;?>"
	                		><i class="icon-flag icon-white"></i> 进度已经100%完成！快点点我！</a>
	                	</div>
	                	<?php else:?>
	                	<span class="progress-bar-text">
	                		<strong>实现进度：</strong><span><?php echo $tmp_track_rate;?>%</span>
	                	</span>
	                	<div class="progress progress-striped active">
						    <div class="bar" style="width: <?php echo $tmp_track_rate; ?>%;"></div>
					    </div>
					    <?php endif;?>
	                </div>
	            </div>
	            <?php endif;?>
            <?php else:?>
            <div class="right-item alert">
                <h4>您还没有实现该成就</h4>
                <a href="/achievements/work_intent/<?php echo $achievement->achievement_id;?>">现在就开始！</a>
            </div>
            <?php endif;?>
        	<!-- 成就进度指示器 end -->
        	
            
        	<!-- 成就实现步骤 begin -->
            <div class="right-item procedures-box">
                <h4><i class="icon-big icon-list"></i> 实现步骤</h4>
                <?php if($procedures):?>
                <ol>
                <?php foreach($procedures as $procedure):?>
                <?php $procedure instanceof ProcedurePeer;?>
                <?php $tmp_tracks = $procedure->getTracksWithIntent($intent);?>
                    <li>
                        <?php if($tmp_tracks):?> 
                        <?php $tmp_track = array_shift($tmp_tracks);?>
                        <?php $tmp_track instanceof TrackPeer;?> 
                        <div class="procedure-desc">
                            <span class="label label-success"><?php echo $procedure->description;?></span>
                            <blockquote class="procedure-track">
                                <p><?php echo $tmp_track->content;?></p>
                                <small><?php echo $tmp_track->track_date;?></small>
                            </blockquote>
                        </div>
                        <div class="procedure-tools">
                            <div class="btn-group">
                            	<span type="button" class="btn btn-small disabled btn-success"><i class="icon-ok-sign icon-white"></i> 已完成</span>
                                <button type="button" class="btn btn-small procedure_tools_view_track_btn" data-original-title="看看别人是怎么完成的"><i class="icon-eye-open"></i></button>
                            </div>
                            <div class="btn-group pull-right">
                                <span class="btn btn-mini disabled"><i class="icon-thumbs-up"></i> 123</span>
                                <span class="btn btn-mini disabled"><i class="icon-thumbs-down"></i> 321</span>
                                <button type="button" class="btn btn-mini"><i class="icon-comment"></i> 456</button>
                            </div>
                        </div>
                        <?php elseif($intent):?>
                        <span class="procedure-desc"><?php echo $procedure->description;?></span>
                        <div class="procedure-tools btn-group">
                           <button 
                            	type="button" 
                            	class="btn btn-small procedure_tools_done_btn" 
                            	href="/achievements/modal_procedure_done_form/<?php echo $intent->intent_id;?>/<?php echo $procedure->procedure_id;?>"
                            	data-loading-text="载入中..."
                            	><i class="icon-ok"></i> 我完成了</button>
                            <button type="button" class="btn btn-small procedure_tools_view_track_btn" data-original-title="看看别人是怎么完成的"><i class="icon-eye-open"></i></button>
                        </div>
                        <?php else:?>
                        <span class="procedure-desc"><?php echo $procedure->description;?></span>
                        <div class="procedure-tools btn-group">
                            <button type="button" class="btn btn-small procedure_tools_view_track_btn" data-original-title="看看别人是怎么完成的"><i class="icon-eye-open"></i></button>
                        </div>
                        <?php endif;?>
                    <?php $sub_procedures = $procedure->getSubProcedures();?>
                    <?php if($sub_procedures):?>
                        <ol>
                        <?php foreach($sub_procedures as $sub_procedure):?>
                        <?php $sub_procedure instanceof ProcedurePeer;?>
                        <?php $tmp_tracks = $sub_procedure->getTracksWithIntent($intent);?>
                            <li>
                                <?php if($tmp_tracks):?> 
                                <?php $tmp_track = array_shift($tmp_tracks);?>
                                <?php $tmp_track instanceof TrackPeer;?> 
                                <div class="procedure-desc">
                                    <span class="label label-success"><?php echo $sub_procedure->description;?></span>
                                    <blockquote class="procedure-track">
                                        <p><?php echo $tmp_track->content;?></p>
                                        <small><?php echo $tmp_track->track_date;?></small>
                                    </blockquote>
                                </div>
                                <div class="procedure-tools">
                                    <div class="btn-group">
                                    	<span type="button" class="btn btn-small disabled btn-success"><i class="icon-ok-sign icon-white"></i> 已完成</span>
                                        <button type="button" class="btn btn-small procedure_tools_view_track_btn" data-original-title="看看别人是怎么完成的"><i class="icon-eye-open"></i></button>
                                    </div>
                                    <div class="btn-group pull-right">
                                        <span class="btn btn-mini disabled"><i class="icon-thumbs-up"></i> 123</span>
                                        <span class="btn btn-mini disabled"><i class="icon-thumbs-down"></i> 321</span>
                                        <button type="button" class="btn btn-mini"><i class="icon-comment"></i> 456</button>
                                    </div>
                                </div>
                                <?php elseif($intent):?>
                                <span class="procedure-desc"><?php echo $sub_procedure->description;?></span>
                                <div class="procedure-tools btn-group">
                                   <button 
                                    	type="button" 
                                    	class="btn btn-small procedure_tools_done_btn" 
                                    	href="/achievements/modal_procedure_done_form/<?php echo $intent->intent_id;?>/<?php echo $sub_procedure->procedure_id;?>"
                                    	data-loading-text="载入中..."
                                    	><i class="icon-ok"></i> 我完成了</button>
                                    <button type="button" class="btn btn-small procedure_tools_view_track_btn" data-original-title="看看别人是怎么完成的"><i class="icon-eye-open"></i></button>
                                </div>
                                <?php else:?>
                                <span class="procedure-desc"><?php echo $sub_procedure->description;?></span>
                                <div class="procedure-tools btn-group">
                                    <button type="button" class="btn btn-small procedure_tools_view_track_btn" data-original-title="看看别人是怎么完成的"><i class="icon-eye-open"></i></button>
                                </div>
                                <?php endif;?>
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
        	<!-- 成就实现步骤 end -->
            
            
            <div class="right-item">
                <h4><i class="icon-big icon-flag"></i> 这些人完成了本成就</h4>
                <div>//TODO 这里显示本成就的 intent标记为完成的用户头像</div>
                <?php if($complete_intents):?>
                <ul class="track_list_light">
                    <?php foreach($complete_intents as $complete_intent):?>
                    <?php $complete_intent instanceof IntentPeer;?>
                    <?php $tmp_user = $complete_intent->getUser();?>
                    <li>
                        <img src="<?php echo IMG_PLACEHOLDER_64x64;?>" class="thumbnail" />
                        <div><?php echo $tmp_user->name;?></div>
                    </li>
                    <?php endforeach;?>
                </ul>
                <?php elseif($intent):?>
                    <div class="alert">目前还没有其他人完成本成就。请继续努力！</div>
                <?php else:?>
                    <div class="alert">
                        <p>目前还没有人完成本成就。想做第一个吗？</p>
                        <a href="/achievements/work_intent/<?php echo $achievement->achievement_id;?>">现在就开始！</a>
                    </div>
                <?php endif;?>
                <hr />
            </div>
            
            <div class="right-item">
                <h4><i class="icon-big icon-riflescope"></i> 这些人正在努力</h4>
                <div>//TODO 这里显示本成就的 intent标记为进行中的用户头像、 对本成就留下的最后一个track</div>
                <?php if($processing_intents):?>
                <ul class="track_list">
                    <?php foreach($processing_intents as $processing_intent):?>
                    <?php $processing_intent instanceof IntentPeer;?>
                    <?php $tmp_user = $processing_intent->getUser();?>
                    <li>
                        <div class="track_face">
                            <img src="<?php echo IMG_PLACEHOLDER_64x64;?>" class="thumbnail" />
                        </div>
                        <?php $tmp_track = $processing_intent->getLastTrack();?>
                        <?php if($tmp_track):?>
                        <div class="track_content">
                            <div class="track_info"><a><?php echo $tmp_user->name;?></a> 于 <?php echo $tmp_track->track_date;?>:</div>
                            <p><?php echo $tmp_track->content;?></p>
                        </div>
                        <?php else:?>
                        <div class="track_content">
                            <p>努力ING</p>
                        </div>
                        <?php endif;?>
                    </li>
                    <?php endforeach;?>
                </ul>
                <?php elseif($intent):?>
                    <div class="alert">目前还没有其他人，请继续努力！</div>
                <?php else:?>
                    <div class="alert">
                        <p>目前还没有人。想做第一个吗？</p>
                        <a href="/achievements/work_intent/<?php echo $achievement->achievement_id;?>">现在就开始！</a>
                    </div>
                <?php endif;?>
                <hr />
            </div>
            
            <div class="right-item">
                <h4><i class="icon-big icon-comments"></i> 大家怎么说</h4>
                <div>//TODO 这里显示本成就的 comment 列表</div>
                <hr />
            </div>
        </div>
    </div>
</div>
