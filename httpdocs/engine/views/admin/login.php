 <div id="login-pane">
    <div class="tubes">
        <div class="the-pic"></div>
        <div class="login">
            <a href="http://www.navigatormm.com">
                <img src="/images/system/kerosene.png" />
            </a>
            <h2><?=Settings::GetSetting("SITE_TITLE");?> Management</h2>
            <?=Session::flash("login_error");?>
            <form action="/admin/login" method="post">
                <input type="text" id="login" name="login" onclick="this.value='';" onfocus="this.select()" onblur="this.value=!this.value?'Username':this.value;" value="Username"/><br />
                <input type="password" id="password" name="password" onclick="this.value='';" onfocus="this.select()" onblur="this.value=!this.value?'Password':this.value;" value="Password"/><br />
                <input type="submit" class="save-btn" value="Log In" />
            </form>
        </div>
    </div>
    <p>Kerosene v1.3 &mdash; Development by <a href="http://www.navigatormm.com" target="_blank">Navigator Multimedia Inc</a></p>
</div>