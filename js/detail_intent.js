$(function() {
	// 浏览该步骤其他track记录的按钮
	var procedure_tools_view_track_btn = $('button.procedure_tools_view_track_btn');
	procedure_tools_view_track_btn.tooltip();

	// “我完成了” 按钮
	var procedure_tools_done_btn = $('button.procedure_tools_done_btn');
	procedure_tools_done_btn.click(function(e) {
	    e.preventDefault();
	    var href = $(e.target).attr('href');
        $.get(href, function(data) {
            $(data).modal();
        });
	});
	
	// 完成某步骤 track 的提交按钮
	var procedure_done_form_submit = $('button.procedure-done-form-submit');
	procedure_done_form_submit.live('click',function(){
		var form = $(this).parent().parent().find('.procedure-done-form');
		form.submit();
	});
});
