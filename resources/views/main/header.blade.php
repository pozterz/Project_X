<nav class="blue lighten-1 z-depth-2">
    <div class="navbar-fixed container nav-wrapper">
        <a href="{{ url('index') }}" class="brand-logo">Queue System</a>
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="fa fa-bars"></i></a>
        <ul class="right hide-on-med-and-down">
            <li><a href="{{ url('index') }}"><i class="fa fa-home"></i> Home</a></li>
            @if(Auth::guest())
            <li><a href="{{ url('login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
            <li><a href="{{ url('register') }}"><i class="fa fa-pencil"></i> Register</a></li>
            @else
            <li><a class="dropdown-button" data-beloworigin="true" data-constrainwidth="false" data-hover="true" href="#!" data-activates="nav_dropdown">{{ Auth::user()->username }}  <i class="fa fa-caret-down"></i></a></li>
            <ul id="nav_dropdown" class="dropdown-content">
                @if(Auth::user()->isAdmin(Auth::user()))
                <li><a href="{{ url('admin/activities') }}"><i class="fa fa-check"></i> Check User</a></li>
                <li><a href="{{ url('admin') }}"><i class="fa fa-gear"></i> Admin Panel</a></li>
                @endif
                <li><a href="{{ url('profile') }}"><i class="fa fa-user"></i> Profile</a></li>
                <li class="divider"></li>
                <li><a href="{{ url('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
            @endif
        </ul>
        <!-- sidenav -->
        <ul class="side-nav" id="mobile-demo">
            @if(Auth::guest())
            <li>
                <div style="background: rgba(0,0,0,0.3);">
                    <div class="userView">
                        <img class="background" src="http://static1.squarespace.com/static/524d09ece4b05018590c5c59/t/5260e196e4b055fef802e254/1382080921506/sea-sanctuaries-siteimage01.jpg"></img>
                        <a href="{{ url('profile') }}"> <img src="" width="54px" class="circle responsive-img" > </a>
                        <a href="#!"><p class="flow-text white-text"> Guest </p> </a>
                    </div>
                </div>
            </li>
            <li><div class="divider"></div></li>
            <li><a href="{{ url('index') }}"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="{{ url('login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
            <li><a href="{{ url('register') }}"><i class="fa fa-pencil"></i> Register</a></li>
            @else
            <li>
             <div style="background: rgba(0,0,0,0.3);">
                <div class="userView">
                    <img class="background" src="http://static1.squarespace.com/static/524d09ece4b05018590c5c59/t/5260e196e4b055fef802e254/1382080921506/sea-sanctuaries-siteimage01.jpg"></img>
                   
                    <a href="{{ url('profile') }}"><p class="flow-text white-text"> {{ Auth::user()->name }} </p></a>

                    </div>
                </div>
            </li>
            <li><div class="divider"></div></li>
            <li><a href="{{ url('index') }}"><i class="fa fa-home"></i> Home</a></li>
            @if(Auth::user()->level == 'admin')
                <li><a href="{{ url('admin') }}"><i class="fa fa-gear"></i> Admin Panel</a></li>
            @endif
            <li><a href="{{ url('profile') }}"><i class="fa fa-user"></i> Profile</a></li>
            <li><a href="{{ url('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
            @endif
        </ul>
        <!-- sidenav -->
    </div>
</nav>
