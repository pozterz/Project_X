<ul id="dropdown1" class="dropdown-content">
  <li><a href="{{ url('profile') }}"><i class="fa fa-user"></i> Profile</a></li>
  <li class="divider"></li>
  <li><a href="{{ url('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
</ul>
<nav class="blue lighten-1 z-depth-2">
    <div class="navbar-fixed container">
        <a href="{{ url('index') }}" class="brand-logo">Queue System</a>
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
        <ul class="right hide-on-med-and-down">
            <li><a href="{{ url('index') }}"><i class="fa fa-home"></i> Home</a></li>
            @if(Auth::guest())
                <li><a href="{{ url('login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
                <li><a href="{{ url('register') }}"><i class="fa fa-pencil"></i> Register</a></li>

            @else
                <li><a class="dropdown-button" href="#!" data-activates="dropdown1">{{ Auth::user()->username }} <i class="fa fa-caret-down"></i></a></li>
            @endif
        </ul>
        <ul class="side-nav" id="mobile-demo">
           <li><a href="{{ url('index') }}"><i class="fa fa-home"></i> Home</a></li>
           @if(Auth::guest())
               <li><a href="{{ url('login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
               <li><a href="{{ url('register') }}"><i class="fa fa-pencil"></i> Register</a></li>
           @else
               <li><a class="dropdown-button" href="#!" data-activates="dropdown1">{{ Auth::user()->username }} <i class="fa fa-caret-down"></i></a></li>
           @endif
        </ul>
    </div>
</nav>
