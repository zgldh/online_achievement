$(function()
{
    var widget_list = $('.widget_comment .widget_list');
    var textarea = $('.widget_comment .widget_comment_textarea');
    var editor = textarea.xheditor(
    {
        tools : 'simple',
        shortcuts :
        {
            'ctrl+enter' : function()
            {
                form.submit();
            }
        }
    });
    var form = $('.widget_comment .widget_comment_post_form');
    var submit_btn = form.find('button[type="submit"]');
    var submitting = false;
    form.submit(function()
    {
        if (submitting)
        {
            return false;
        }
        var url = form.attr('action');
        var content = editor.getSource();

        // 评论长度限制
        if (content.length < 15)
        {
            submit_btn.tooltip(
            {
                title : '您的评论太短，至少要15个字哦。',
                trigger : 'manual'
            });
            submit_btn.tooltip('show');
            setTimeout(function()
            {
                submit_btn.tooltip('destory');
            }, 2000);
        } else
        {
            submitting = true;
            submit_btn.tooltip('hide');
            submit_btn.button('loading');
            var data = {content : content};
            if(input_track_id.val())
            {
                data.track_id = input_track_id.val();
            }
            if(input_reply_comment_id.val())
            {
                data.reply_comment_id = input_reply_comment_id.val();
            }
            
            $.post(url,
            data,
            function(re)
            {
                if (re == 'ok')
                {
                    var data_load_url = form.attr('data-load-url');
                    widget_list.load(data_load_url);
                    editor.setSource('');
                }
                submit_btn.button('reset');
                submitting = false;
            });
        }
        return false;
    });
    //分页
    var pagination = $('.widget_comment .pagination');
    var pagination_a = pagination.find('li a[href]');
    pagination_a.live('click', function()
    {
        var a = $(this);
        var href = a.attr('href');
        widget_list.load(href);
        
        return false;
    });
    //引用/回复
    var input_track_id = form.find('.input_track_id');
    var input_reply_comment_id = form.find('.input_reply_comment_id');
    var widget_reference = form.find('div.widget_reference');
    var widget_reference_close = widget_reference.find('.close');
    widget_reference_close.click(function(){
        input_track_id.val('');
        input_reply_comment_id.val('');
        widget_reference.hide();
        return true;
    });
    
    //track评论按钮
    var reference_track_id_btn = $('.reference_track_id_btn');
    reference_track_id_btn.click(function(){
        window.location.href = '#widget_comment_post_form';
        
        var btn = $(this);
        var track_id = btn.attr('data-track-id');
        input_track_id.val(track_id);
        var track_content = $('#track_content_'+track_id).text();
        widget_reference.show().find('span.content').html('<strong>评论：</strong><span>'+track_content+'</span>');
        
        editor.focus();
    });
});