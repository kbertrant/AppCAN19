<header class="header black-bg">
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>
    <!--logo start-->
    <a href="{{ route('home') }}" class="logo"><img src="{{ asset('img/logo_appcan19v31.png') }}"> AppCAN19</a>
    <!--logo end-->

    <div class="top-menu logout  pull-right">
            <button type="button" class="logout">{{ Auth::user()->name }}</button>
    <button type="button" class="logout">Credit: {{Utils::get_amount()}}</button>
            <button type="button" class="logout dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('credit') }}"><i class="fa fa-usd"> </i> Credit</a></li>
                <li><a href="{{ route('profile') }}"><i class="fa fa-user"> </i> Profile</a></li>
                <li><a href="#" onclick="document.getElementById('logout-form').submit()"><i class="fa fa-lock"> </i> Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                    </form>
                </li>
            </ul>

        </div>
</header>
