<header id="header" class="custom-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light container homeNavBar">
        <a class="navbar-brand my-1" href="#">
            <img src="{{ asset('images/happy-trip.png') }}" class="d-none d-sm-block"/>
            <img src="{{ asset('images/whitehappy-trip.png') }}" class="d-block d-sm-none"/>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ml-auto nav-links">
            <li class="fl mt-1 active">
            <a href="{{route('welcome')}}">
                <img src="{{ asset('images/menu-home-white.png') }}" alt="">
                <span>{{ __('Home') }}</span>
            </a>
            </li>
            <li class="fl mt-1">
            <a href="#">
                <img src="{{ asset('images/menu-flight-white.png') }}" alt="">
                <span>{{ __('Flights') }}</span>
            </a>
            </li>
            <li class="fl mt-1">
            <a href="#">
                <img src="{{ asset('images/menu-hotel-white.png') }}" alt="">
                <span>{{ __('Hotels') }}</span>
            </a>
            </li>
            <li class="nav-item dropdown mt-1">
            <a class="nav-link dropdown-toggle rad-border" href="#" id="navbarDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               @if (App::isLocale('en')) English @else العربية @endif
            </a>
          
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ url('setlocale/ar') }}"> {{ __('Arabic') }}</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ url('setlocale/en') }}"> {{ __('English') }}</a>
            </div>
            </li>
            <li class="nav-item dropdown mt-1">
                <a class="nav-link dropdown-toggle rad-border" href="#" id="navbarDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if( session()->get('currency') !=null)
                        {{ session()->get('currency') }}
                    @else
                        SAR
                    @endif
                </a>
                <div class="dropdown-menu currency-div" aria-labelledby="navbarDropdown" >
                    <a href="javascript:void(0);" title="د.إ.&rlm; AED" onclick="convertCurrency(this);" id="AED">AED</a>
                    <a href="javascript:void(0);" title="د.ب.&rlm; BHD" onclick="convertCurrency(this);" id="BHD">BHD</a>
                    <a href="javascript:void(0);" title="ج.م.&rlm; EGP" onclick="convertCurrency(this);" id="EGP">EGP</a>
                    <a href="javascript:void(0);" title="€ EUR" onclick="convertCurrency(this);" id="EUR">EUR</a>
                    <a href="javascript:void(0);" title="£ GBP" onclick="convertCurrency(this); "id="GBP">GBP</a>
                    <a href="javascript:void(0);" title="د.ك.&rlm; KWD" onclick="convertCurrency(this);" id="KWD">KWD</a>
                    <a href="javascript:void(0);" title="ر.ع.&rlm; OMR" onclick="convertCurrency(this);" id="OMR">OMR</a>
                    <a href="javascript:void(0);" title="ر.س.&rlm; SAR" onclick="convertCurrency(this);" id="SAR">SAR</a>
                    <a href="javascript:void(0);" title="$ USD" onclick="convertCurrency(this);" id="USD">USD</a>
                </div>
            </li>
            @if (!Auth::guest())
                <!-- if logged in -->
                <li class="fl submenu user-info">
                    <a href="javascript:;" title="">
                        <span class="img"></span>
                        <span class="user-name">{{auth()->user()->name}}<i class="fa fa-chevron-down"></i></span>
                    </a>
                    <ul class="menu mini text-left">
                        <li><a href="/myaccount" title="My Account"><i class="icon fal fa-user"></i>  
                        {{ __('My Account') }}</a></li>
                        <li><a href="/myloyalty" title="My Loyalty"><i class="icon fal fa-star"></i> {{ __('My Loyalty') }}</a></li>
                        <li><a href="/mybookings" title="My Bookings"><i class="icon fal fa-calendar"></i> {{ __('My Bookings') }}</a></li>
                        <li><a href="/changepassword" title="Change Password"><i class="icon fal fa-lock"></i> {{ __('Change Password') }}</a>
                        </li>
                        <li><a href="{{route('logout')}}" title="Logout"><i class="icon fal fa-sign-out"></i> {{ __('Logout') }}</a></li>
                    </ul>
                </li>
            @else

                <!-- for guest -->
                <li class="nav-item rad-border">
                    <livewire:navbar-auth/>
                </li>
            @endif
        </ul>
        </div>
        </nav>
    </div>
</header>

