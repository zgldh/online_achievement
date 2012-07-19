<form class="form-horizontal" method="post">
	<input type="hidden" name="redirect_to" value="<?php echo $this->navbar->getRedirectTo();?>" />
    <fieldset>
        <legend>会员登录</legend>
        <?php if($error):?>
        <div class="alert alert-error">
            <strong>登录失败</strong>
            <?php echo $error['msg'];?>
        </div>
        <?php endif;?>
        <div class="control-group">
            <label class="control-label" for="user_name">帐号</label>
            <div class="controls">
                <input type="text" class="input-xlarge" id="user_name" name="user_name">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="password">密码</label>
            <div class="controls">
                <input type="password" class="input-xlarge" id="password" name="password">
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <p>您也可以用新浪微博帐号、QQ帐号、人人网帐号直接登录。</p>
                <img src="http://timg.sjs.sinajs.cn/t4/appstyle/widget/images/loginButton/loginButton_24.png" />
                <img src="http://wiki.dev.renren.com/mediawiki/images/9/95/%E8%BF%9E%E6%8E%A5%E6%8C%89%E9%92%AE2_%E7%99%BD%E8%89%B2132X28.png" />
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary btn-large" type="submit">登录</button>
        </div>
    </fieldset>
</form>