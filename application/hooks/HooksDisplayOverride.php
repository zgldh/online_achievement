<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HooksDisplayOverride
{
    /**
     * display_override <br />
     * 覆盖_display()函数, 用来在系统执行末尾向web浏览器发送最终页面.这允许你用自己的方法来显示.<br />
     * 注意，你需要通过 $this->CI =& get_instance() 引用 CI 超级对象，<br />
     * 然后这样的最终数据可以通过调用 $this->CI->output->get_output() 来获得。
     */
    function __construct()
    {
    }

    public function hook()
    {
    }
}

/* End of file HooksDisplayOverride.php */
/* Location: ./application/hooks/HooksDisplayOverride.php */