<?php
/**
 * @param WebUser $webuser
 * @param string $current_nav_item
 */
?>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container navbar-container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#" style="font-family: 微软雅黑;font-weight: bold;">在线成就系统 :-)</a>
            <div class="nav-collapse">
                <ul class="nav">
                    <li class="divider-vertical"></li>
                    <li class="<?php echo $this->navbar->getItemClass(NavBar::ITEM_HOME); ?>"><a href="/">首页</a></li>
                    <li class="<?php echo $this->navbar->getItemClass(NavBar::ITEM_CREATE); ?>"><a href="/create">编写成就</a></li>
                    <li class="<?php echo $this->navbar->getItemClass(NavBar::ITEM_ALL); ?>"><a href="/all">所有成就</a></li>
                    <li class="<?php echo $this->navbar->getItemClass(NavBar::ITEM_JUSTLOOK); ?>"><a href="/random">随便看看</a></li>
                    <li class="dropdown <?php echo $this->navbar->getItemClass(NavBar::ITEM_TAGS); ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">标签分类<b class="caret"></b></a>
                        <?php $tags = $this->navbar->getTagList();?>
                        <ul class="dropdown-menu">
                        <?php foreach($tags as $tag):?>
                            <li>
                            	<a href="#<?php echo $tag->tag_name;?>">
                            	<span class="label label-info"><?php echo $tag->tag_count;?></span>
                            	<?php echo $tag->tag_name;?>
                            	</a>
                            </li>
                        <?php endforeach;?>
                            <li class="divider"></li>
                            <li><a href="/tags">全部</a></li>
                        </ul>
                    </li>
                    <li class="divider-vertical"></li>
                </ul>
                <form class="navbar-search pull-left" action="">
                    <input type="text" class="search-query span2" placeholder="搜索" />
                </form>
                <?php if($this->webuser->isLogin()):?>
                <div class="btn-group pull-right">
                	<a href="#" class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> <?php echo $this->webuser->getUserName();?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                    	<li><a href="#"><i class="icon-list-alt"></i> 个人资料</a></li>
                        <li><a href="#"><i class="icon-star"></i> 我的成就</a></li>
                        <li><a href="#"><i class="icon-envelope"></i> 信息</a></li>
                        <li class="divider"></li>
                        <li><a href="/logout" title="退出"><i class="icon-off"></i> 退出</a></li>
                    </ul>
                </div>
                <?php else:?>
                <ul class="nav pull-right">
                    <li class="<?php echo $this->navbar->getItemClass(NavBar::ITEM_REGISTER); ?>"><a href="/register">注册</a></li>
                    <?php if($this->navbar->isDisplaySignIn()):?>
                    <li class="divider-vertical"></li>
                    <li class="dropdown <?php echo $this->navbar->getItemClass(NavBar::ITEM_SIGNIN); ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">登录<b class="caret"></b></a>
                        <div class="dropdown-menu" style="padding: 10px;">
                            <form method="post" action="/signin">
                                <input type="text" class="input-medium" placeholder="帐号" name="user_name">
                                <input type="password" class="input-medium" placeholder="密码" name="password">
                                <button type="submit" class="btn btn-primary">登录</button>
                                <a class="btn">忘记密码</a>
                                <hr />
                                <img src="http://timg.sjs.sinajs.cn/t4/appstyle/widget/images/loginButton/loginButton_24.png" />
                                <img src="http://wiki.dev.renren.com/mediawiki/images/9/95/%E8%BF%9E%E6%8E%A5%E6%8C%89%E9%92%AE2_%E7%99%BD%E8%89%B2132X28.png" />
                                <input type="hidden" name="redirect_to" value="<?php echo $this->navbar->getRedirectTo();?>" />
                            </form>
                        </div>
                    </li>
                    <?php endif;?>
                </ul>
                <?php endif;?>
            </div>
            <!-- /.nav-collapse -->
        </div>
    </div>
    <!-- /navbar-inner -->
</div>