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
    
    var logoModal = $('#logoModal');
    var logoHandle = $('#logoHandle');
    logoHandle.click(function(){
    	logoModal.modal();
    });
});