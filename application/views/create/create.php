<div class="container-fluid">
	<div class="page-header">
		<h1>编写成就</h1>
	</div>
    <div class="row-fluid">
        <!-- 左侧栏 开始 -->
        <div class="span3 well">
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
        <form id="create_form" class="form-horizontal span9" method="post">
            <fieldset id="form_step_1">
                <div class="control-group">
                    <label class="control-label" for="ach_name"><span class="label label-important">必填</span> 成就名称</label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" id="ach_name" name="Achievement[name]" placeholder="起个响亮的名字">
                        <span class="help-inline error">请填写成就名称</span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="ach_description"><span class="label label-important">必填</span> 成就描述</label>
                    <div class="controls">
                        <textarea id="ach_description" name="Achievement[description]" class="input-xlarge" rows="7" style="max-height: 200px;"
                         placeholder="为什么要有这个成就呢？"
                        ></textarea>
                        <span class="help-inline error">请填写成就描述</span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><span class="label label-important">必填</span> 成就LOGO</label>
                    <div class="controls">
                        <a id="logo_handle" href="#logo_modal" data-toggle="modal" class="thumbnail logo_preview_box">
                        	<div class="logo_preview_box_div">
                        		<img src="/images/image_placeholder_128x128.png" alt="image_placeholder_128x128">
                            </div>
                        </a>
                        <input type="hidden" name="Achievement[logo_src]" id="ach_logo_src" value="" />
                        <input type="hidden" name="Achievement[logo_crop]" id="ach_logo_crop" value="" />
                        <input type="hidden" name="Achievement[uploaded_id]" id="ach_uploaded_id" value="" />
                    
                        <span class="help-inline error">请选择一个Logo</span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="ach_categories">标签</label>
                    <div class="controls">
                        <input class="input-xlarge" id="ach_categories" name="Achievement[categories]" type="text"/>
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
        <!-- 基本信息 结束 -->

        <!-- 步骤计划 开始 -->
            <fieldset id="form_step_2">
                <div class="control-group">
                    <label class="control-label">成就名称</label>
                    <div class="controls">
                        <div class="thumbnail logo_preview_box">
                        	<div class="logo_preview_box_div">
                        		<img src="/images/image_placeholder_128x128.png" alt="image_placeholder_128x128">
                            </div>
                        </div>
                        <h2 class="help-inline ach_name_preview">成就名称</h2>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">实现步骤</label>
                    <input type="hidden" id="ach_procedure" name="Achievement[procedure]" />
                    <div class="controls">
                        <div id="procedure_editor" class="dd">
                            <ol class="dd-list">
                                <li class="dd-item" data-text="">
                                    <div class="dd-handle" style="display: inline-block;"><i class="icon-move"></i></div>
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
        <!-- 步骤计划 结束 -->

        <!-- 预览完成 开始 -->
            <fieldset id="form_step_3">
                <div class="control-group">
                    <label class="control-label">成就名称</label>
                    <div class="controls">
                        <div class="thumbnail logo_preview_box">
                        	<div class="logo_preview_box_div">
                        		<img src="/images/image_placeholder_128x128.png" alt="image_placeholder_128x128">
                            </div>
                        </div>
                        <h2 class="help-inline ach_name_preview">成就名称</h2>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">成就描述</label>
                    <div class="controls">
                        <p id="ach_description_preview"></p>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">实现步骤</label>
                    <div class="controls" id="procedure_preview">
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
                <div class="control-group hide logo_chop_group">
                    <label class="control-label">选择区域</label>
                    <div class="controls">
    			        <img src="" class="logo_img"/>
                    </div>
                </div>
                <div class="control-group hide logo_preview_group">
                    <label class="control-label">预览</label>
                    <div class="controls">
                        <div style="width: 128px;height: 128px;overflow:hidden;">
                            <img src="" alt="Preview" class="logo_preview jcrop-preview" />
                        </div>
                    </div>
                </div>
    		</fieldset>
    	</form>
    </div>
    <div class="modal-footer">
	    <button class="btn btn-success logo_confirm_btn" type="submit">确定</button>
	    <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>

