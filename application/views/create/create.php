<div class="container-fluid">
    <div class="row-fluid">
        <!-- 左侧栏 开始 -->
        <div class="span3 well">
            <h1>编写成就</h1>
            <hr />
            <div id="step_titles" class="unstyled">
                <div id="step_title_1" class="current">
                    <i class="icon-big icon-file"></i>
                    <span>基本信息</span>
                </div>
                <div id="step_title_2">
                    <i class="icon-big icon-list"></i>
                    <span>步骤计划</span>
                </div>
                <div id="step_title_3">
                    <i class="icon-big icon-circle_ok"></i>
                    <span>预览，完成！</span>
                </div>
            </div>
        </div>
        <!-- 左侧栏 结束 -->

        <!-- 基本信息 开始 -->
        <form id="form_step_1" class="form-horizontal span9">
            <fieldset>
                <legend><i class="icon-big icon-file"></i>基本信息</legend>
                <div class="control-group">
                    <label class="control-label" for="input01">成就名称</label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" id="input01">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="input01">成就描述</label>
                    <div class="controls">
                        <textarea class="input-xlarge" rows="7" style="max-height: 200px;"></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="input01">成就LOGO</label>
                    <div class="controls">
                        <a id="logo_handle" href="#logo_modal" data-toggle="modal" class="thumbnail" style="width: 128px;">
                            <img src="/images/image_placeholder_128x128.png" alt="image_placeholder_128x128">
                        </a>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="input01">标签</label>
                    <div class="controls">
                        <input class="input-xlarge" id="category_select" name="category" type="text"/>
                    </div>
                </div>
                <?php
                /**
                 * TODO 将来这里可以选择“前驱成就”, 显示“复制自某成就”
                 */
                ?>
                <div class="form-actions">
                    <button id="step_1_to_2_btn" class="btn btn-primary btn-large" type="button" >下一步</button>
                </div>
            </fieldset>
        </form>
        <!-- 基本信息 结束 -->

        <!-- 步骤计划 开始 -->
        <form id="form_step_2" class="form-horizontal span9">
            <fieldset>
                <legend><i class="icon-big icon-list"></i>步骤计划</legend>
                <div class="control-group">
                    <label class="control-label" for="input01">成就名称</label>
                    <div class="controls">
                        <img alt="成就logo">
                        <h2 class="help-inline">成就名称</h2>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="input01">实现步骤</label>
                    <div class="controls">
                        <div id="procedure_editor" class="dd">
                            <ol class="dd-list">
                                <li class="dd-item" data-id="1">
                                    <div class="dd-handle" style="display: inline-block;"><i class="icon-resize-vertical"></i></div>
                                    <textarea class="procedure_content" placeholder="这一步做什么呢..." ></textarea>
                                    <button type="button" class="btn btn-danger procedure_remove_btn"><i class="icon-remove icon-white"></i></button>
                                </li>
                            </ol>
                        </div>
                        <button id="procedure_add_btn" type="button" class="btn"><i class="icon-plus"></i> 增加步骤</button>
                    </div>
                </div>
                <div class="form-actions">
                    <button id="step_2_to_1_btn" class="btn btn-large" type="button" >上一步</button>
                    <button id="step_2_to_3_btn" class="btn btn-primary btn-large" type="button" >下一步</button>
                </div>
            </fieldset>
        </form>
        <!-- 步骤计划 结束 -->

        <!-- 预览完成 开始 -->
        <form id="form_step_3" class="form-horizontal span9">
            <fieldset>
                <legend><i class="icon-big icon-circle_ok"></i>预览，完成！</legend>
                <div class="control-group">
                    <label class="control-label" for="input01">成就名称</label>
                    <div class="controls">
                        <img alt="成就logo">
                        <h2 class="help-inline">成就名称</h2>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="input01">成就描述</label>
                    <div class="controls">
                        {成就描述,一大堆}
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="input01">实现步骤</label>
                    <div class="controls">
                        <ol>
                            <li>
                                <div>先干啥</div>
                            </li>
                            <li>
                                <div>然后干啥</div>
                                <ol>
                                    <li>
                                        <div>然后干啥1</div>
                                    </li>
                                    <li>
                                        <div>然后干啥2</div>
                                        <ol>
                                            <li>
                                                <div>然后干啥2.1</div>
                                            </li>
                                            <li>
                                                <div>然后干啥2.2</div>
                                            </li>
                                        </ol>
                                    </li>
                                </ol>
                            </li>
                            <li>
                                <div>最后干啥</div>
                                <ol>
                                    <li>
                                        <div>最后干啥1</div>
                                    </li>
                                    <li>
                                        <div>最后干啥2</div>
                                    </li>
                                </ol>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="form-actions">
                    <button id="step_3_to_2_btn" class="btn btn-large" type="button" >上一步</button>
                    <button id="submit_btn" class="btn btn-primary btn-large" type="button" >完成！</button>
                </div>
            </fieldset>
        </form>
        <!-- 预览完成 结束 -->
    </div>
</div>

<div class="modal hide" id="logo_modal">
    <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal">×</button>
	    <h3>上传LOGO</h3>
    </div>
    <div class="modal-body">
    	<?php $this->load->helper(array('form', 'url'));?>
    	<form id="logo_form" action="/create/jsonp_logo_upload" class="form-horizontal" accept-charset="utf-8" method="post" enctype="multipart/form-data">
    		<fieldset>
                <div class="control-group">
                    <label class="control-label" for="image_file">请选择成就Logo</label>
                    <div class="controls">
    			        <input type="file" name="file" class="input-file" id="image_file" accept="image/*" />
    			        <div class="alert hide">上传中...</div>
                    </div>
                </div>
                <div class="control-group hide" id="logo_chop_group">
                    <label class="control-label">选择区域</label>
                    <div class="controls">
    			        <img src="" id="logo_img"/>
                    </div>
                </div>
                <div class="control-group hide" id="logo_preview_group">
                    <label class="control-label">预览</label>
                    <div class="controls">
                        <div style="width: 128px;height: 128px;overflow:hidden;">
                            <img src="" id="logo_preview" alt="Preview" class="jcrop-preview" />
                        </div>
                    </div>
                </div>
    		</fieldset>
    	</form>
    </div>
    <div class="modal-footer">
	    <button class="btn btn-success" type="button">确定</button>
	    <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>

