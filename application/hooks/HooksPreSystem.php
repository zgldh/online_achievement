<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HooksPreSystem
{
    /**
     * pre_system <br />
     * 系统执行的早期调用.仅仅在benchmark 和 hooks 类 加载完毕的时候. 没有执行路由或者其它的过程.
     */
    function __construct()
    {
    }
}

/* End of file HooksPreSystem.php */
/* Location: ./application/hooks/HooksPreSystem.php */