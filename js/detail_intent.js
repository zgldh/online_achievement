$(function()
{
    // 浏览该步骤其他track记录的按钮
    var procedure_tools_view_track_btn = $('button.procedure_tools_view_track_btn');
    procedure_tools_view_track_btn.tooltip();
    
    // “我完成了” 按钮
    var procedure_tools_done_btn = $('button.procedure_tools_done_btn');
    procedure_tools_done_btn.popover({
    	placement:'left',
    	trigger: 'manual',
    	content:'',
    	template: '<div class="popover procedure-track-popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'
    });
    procedure_tools_done_btn.click(function(){
    	procedure_tools_done_btn.not(this).popover('hide');
    	$(this).popover('toggle');
    });
});
