<div class="header">
    <div class="header-left">
        <div class="logo">
            <a href="index.html"><img src="<%$__RESOURCE%>images/logo.png" alt=""/></a>
        </div>
        <div class="menu">
            <a class="toggleMenu" href="#"><img src="<%$__RESOURCE%>images/nav.png" alt="" /></a>
            <ul class="nav" id="nav">
                <li class="active"><a href="index.html">首页</a></li>
                <li><a href="living.html">想去哪儿玩</a></li>
                <li><a href="education.html">酒店</a></li>
                <li><a href="entertain.html">机票</a></li>
                <li><a href="404.html">旅行必备</a></li>
                <div class="clearfix"></div>
            </ul>
            <script type="text/javascript" src="<%$__RESOURCE%>js/responsive-nav.js"></script>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="header_right">
        <%if $nav!="index"%>
        <!-- start search-->
        <div class="search-box">
            <div id="sb-search" class="sb-search">
                <form>
                    <input class="sb-search-input" placeholder="Enter your search term..." type="search" name="search" id="search">
                    <input class="sb-search-submit" type="submit" value="">
                    <span class="sb-icon-search"> </span>
                </form>
            </div>
        </div>
        <!----search-scripts---->
        <script src="<%$__RESOURCE%>js/classie.js"></script>
        <script src="<%$__RESOURCE%>js/uisearch.js"></script>
        <script>
            new UISearch( document.getElementById( 'sb-search' ) );
        </script>
        <!----//search-scripts---->
        <%/if%>
        <div id="loginContainer"><a href="#" id="loginButton"><img src="<%$__RESOURCE%>images/login.png"><span>登录</span></a>
            <div id="loginBox">
                <form id="loginForm">
                    <fieldset id="body">
                        <fieldset>
                            <label for="email">Email Address</label>
                            <input type="text" name="email" id="email">
                        </fieldset>
                        <fieldset>
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password">
                        </fieldset>
                        <input type="submit" id="login" value="Sign in">
                        <label for="checkbox"><input type="checkbox" id="checkbox"> <i>Remember me</i></label>
                    </fieldset>
                    <span><a href="#">Forgot your password?</a></span>
                </form>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>