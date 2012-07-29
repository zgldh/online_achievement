$(function(){
    var step_titles = $('#step_titles');
    var form_step_1 = $('#form_step_1');
    var form_step_2 = $('#form_step_2');
    var form_step_3 = $('#form_step_3');
    var forms = [form_step_1,form_step_2,form_step_3];

    var step_1_to_2_btn = $('#step_1_to_2_btn');
    var step_2_to_1_btn = $('#step_2_to_1_btn');
    var step_2_to_3_btn = $('#step_2_to_3_btn');
    var step_3_to_2_btn = $('#step_3_to_2_btn');
    var submit_btn = $('#submit_btn');

    var current_step = 1;

    function setStep(num)
    {
        num = parseInt(num);
        if(num >0 && num < 4)
        {
            var step_title = step_titles.find('#step_title_'+num);
            step_title.addClass('current').siblings().removeClass('current');
            for(var i = 0;i<forms.length;i++)
            {
                var form = forms[i];
                form.toggle((i == num - 1)?true:false);
            }

            current_step = num;
            return true;
        }
        alert('错误的step数: '+num);
        return false;
    }

    step_1_to_2_btn.click(function(){
        setStep(2);
    });
    step_2_to_1_btn.click(function(){
        setStep(1);
    });
    step_2_to_3_btn.click(function(){
        setStep(3);
    });
    step_3_to_2_btn.click(function(){
        setStep(2);
    });


    var category_select = $('#category_select');
    category_select.select2({
                                createSearchChoice:function(term, data)
                                {
                                    if ($(data).filter(function()
                                            {
                                                return this.text.localeCompare(term)===0;
                                            }).length===0)
                                    {
                                        return {id:term, text:term};
                                    }
                                },
                                multiple: true,
                                data: [ {id: 0, text: '吃货'},
                                        {id: 1, text: '到此一游'},
                                        {id: 2, text: '前无古人'},
                                        {id: 3, text: '发奋图强'}]
                            });


    //logo图像上传选择
    var logo_modal = $('#logo_modal');
    var logo_handle = $('#logo_handle');
    var logo_form = $('#logo_form');
    var image_file = $('#image_file');
    var image_file_uploading = image_file.siblings('.alert');
    image_file.change(function(){
        var iframe_name = 'LOGO_'+Date.parse(new Date());
        var iframe = $('<iframe style="display:none;" name="'+iframe_name+'" id="'+iframe_name+'"></iframe>');
        $(document.body).append(iframe);
        var action_url = '/create/jsonp_logo_upload?callback=LOGO_CALLBACK&iframe_id='+iframe_name;
        logo_form.attr('action',action_url).attr('target',iframe_name).submit();
        image_file.hide();
        image_file_uploading.show();
    });
    var logo_img = $('#logo_img');


    //过程编辑器
    var procedure_editor = $('#procedure_editor');
    var procedure_editor_ol = procedure_editor.find('ol');
    procedure_editor.nestable({maxDepth:2,expandBtnHTML:'',collapseBtnHTML:''});
    var procedure_add_btn = $('#procedure_add_btn');
    procedure_add_btn.click(function(){
        var tpl= '<li class="dd-item" data-id="1"> ';
        tpl+='<div class="dd-handle" style="display: inline-block;"><i class="icon-move"></i></div> ';
        tpl+='<textarea class="procedure_content" placeholder="这一步做什么呢..."></textarea> ';
        tpl+='<button type="button" class="btn btn-danger procedure_remove_btn"><i class="icon-remove icon-white"></i></button> ';
        tpl+='</li>';
        tpl = $(tpl);
        procedure_editor_ol.append(tpl);
        tpl.find('textarea').autosize();
    });
    var procedure_remove_btn = procedure_editor.find('.procedure_remove_btn');
    procedure_remove_btn.live('click',function(){
        var btn = $(this);
        var children_li = btn.siblings('ol').find('li');
        procedure_editor_ol.append(children_li);
        btn.parent().remove();
    });

});
var LOGO_CALLBACK = function(re, iframe_id){
    if(typeof(re.error_msg) == 'undefined')
    {
        var logo_img = $('#logo_img');
        var logo_chop_group = $('#logo_chop_group');
        var logo_preview = $('#logo_preview');
        var logo_preview_group = $('#logo_preview_group');
        logo_img.attr('src',re.image_url);
        logo_chop_group.removeClass('hide');
        logo_preview.attr('src',re.image_url);
        logo_preview_group.removeClass('hide');

        var updatePreview = function(c)
        {
            if (parseInt(c.w) > 0)
            {
                var rx = 128 / c.w;
                var ry = 128 / c.h;

                logo_preview.css({
                    width: Math.round(rx * boundx) + 'px',
                    height: Math.round(ry * boundy) + 'px',
                    marginLeft: '-' + Math.round(rx * c.x) + 'px',
                    marginTop: '-' + Math.round(ry * c.y) + 'px'
                });
            }
        };

        var pre_width = Math.min(128,re.image_width);
        var pre_height= Math.min(128,re.image_height);
        pre_width = Math.min(pre_width,re.pre_height);
        pre_height = pre_width;

        var boundx, boundy;
        if(LOGO_CALLBACK.jcrop_api != null)
       	{
           	LOGO_CALLBACK.jcrop_api.destroy();
       	}
        logo_img.Jcrop(
            {
                aspectRatio: 1,
                maxSize: [128,128],
                bgFade:     false,
                bgOpacity: .3,
                setSelect: [ 0, 0, pre_width, pre_height ],
                onChange: updatePreview,
                onSelect: updatePreview,
            },
            function()
            {
                var bounds = this.getBounds();
                boundx = bounds[0];
                boundy = bounds[1];
                LOGO_CALLBACK.jcrop_api = this;
            }
        );
    }
    else
   	{
       	alert(re.error_msg);
   	}

    var image_file = $('#image_file');
    var image_file_uploading = image_file.siblings('.alert');
    image_file.show();
    image_file_uploading.hide();

    $('#'+iframe_id).remove();
};