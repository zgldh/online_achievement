$(function()
{
    var widget_list = $('.widget_comment .widget_list');
    var textarea = $('.widget_comment .widget_comment_textarea');
    var editor = textarea.xheditor({
    		tools:'simple',
    		shortcuts:{
    			'ctrl+enter':function(){
    				form.submit();
    			}
    		}
    });
    var form = $('.widget_comment .widget_comment_post_form');
    var submit_btn = form.find('button[type="submit"]');
    var submitting = false;
    form.submit(function(){
        if(submitting)
        {
            return false;
        }
        var url = form.attr('action');
        var content = editor.getSource();
        
        //评论长度限制
        if(content.length < 15)
    	{
        	submit_btn.tooltip({
        		title:'您的评论太短，至少要15个字哦。',
        		trigger:'manual'
        	});
        	submit_btn.tooltip('show');
        	setTimeout(function(){
            	submit_btn.tooltip('destory');
        	},2000);
    	}
        else
    	{
        	submitting = true;
        	submit_btn.tooltip('hide');
	        submit_btn.button('loading');
	        $.post(url,{content:content},function(re){
	        	if(re == 'ok')
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
});