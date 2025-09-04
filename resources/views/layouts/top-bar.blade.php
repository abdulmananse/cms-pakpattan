<header class="navbar pcoded-header navbar-expand-lg navbar-light headerpos-fixed header-blue">


    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse" href="#"><span></span></a>
        <a href="{{ url('/') }}" class="b-brand">
            <b class="mb-0 navbarTextPser">{{ getSettingValue('site_name') }}</b>
            <!-- ========   change your logo hear   ============ -->
            {{-- <img src="{{asset('images/logo.png')}}" alt="" class="logo"> --}}
            {{-- <img src="{{asset('images/logo-icon.png')}}" alt="" class="logo-thumb"> --}}
        </a>
        <a href="#" class="mob-toggler">
            <i class="feather icon-more-vertical"></i>
        </a>
    </div>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a href="#" class="full-screen" onclick="javascript:toggleFullScreen()"><i class="feather icon-maximize"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li>
                <div class="dropdown drp-user">
                    
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="{{asset('images/avatar-1.png')}}" class="img-radius wid-40" alt="User-Profile-Image">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-notification">
                        <div class="pro-head">
                            <img src="{{asset('images/avatar-1.png')}}" class="img-radius" alt="User-Profile-Image">
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                        <ul class="pro-body">
                            <li><a href="{{ route('profile') }}" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li>
                            <li><a href="{{ route('change-password') }}" class="dropdown-item"><i class="feather icon-lock"></i> Change Password</a></li>
                            <li><a href="{{route('logout')}}" class="dropdown-item"><i class="feather icon-power"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>


</header>