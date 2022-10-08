@php $userModelInstance = new App\Models\User(); @endphp
@if (auth()->check())
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ route('welcome') }}"> <img alt="image" src="{{ asset('assets_old/logo/sell_data_logo_icon.png') }}" class="header-logo" />
            {{-- <span class="logo-name">{{ env('APP_NAME') }}</span> --}}
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>

            <li class="dropdown active">
              <a href="{{ route('home') }}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>

            @if(auth()->user()->privilegeChecker('user-list-and-management'))
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="users"></i><span>Users</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('list-of-users') }}">List of User(s)</a></li>
              </ul>
            </li>
            @endif

            @if(auth()->user()->privilegeChecker('send-bulk-mails'))
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="users"></i><span>Mail</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('send-mail') }}">Send Mail</a></li>
              </ul>
            </li>
            @endif

            @if(auth()->user()->privilegeChecker('add-and-edit-display-banner'))
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="users"></i><span>Display Banner</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('upload-display-banner') }}">Add Display Banner</a></li>
                <li><a class="nav-link" href="{{ route('view-display-banner') }}">View Display Banner</a></li>
              </ul>
            </li>
            @endif

            @if(auth()->user()->type_of_user === auth()->user()->normalUserType)
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="user"></i><span>My Account</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('profile') }}">My Profile</a></li>
                <li><a class="nav-link" href="{{ route('create-bank') }}">Bank Account</a></li>
                <li><a class="nav-link" href="{{ route('change-password', [auth()->user()->unique_id]) }}">Change Password</a></li>
              </ul>
            </li>
            @endif

            @if(auth()->user()->privilegeChecker('add-and-view-category'))
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown">
                <i data-feather="user"></i><span>Support Ticket Category</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('create-support-category') }}">Create Ticket Category</a></li>
                    <li><a class="nav-link" href="{{ route('view-category') }}">View Ticket Categories</a></li>
                </ul>
            </li>
            @endif

            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="command"></i><span>Payments</span></a>
              <ul class="dropdown-menu">
                @php $paymentModalModelInstance = new App\Models\PaymentModal() @endphp
                @if(auth()->user()->privilegeChecker('access-payment-section'))
                <li><a class="nav-link" href="{{ route('create-payment') }}">Deposit Coin</a></li>
                @endif
                <li><a class="nav-link" href="{{ route('payment-history', [$paymentModalModelInstance->coinSaleActionType]) }}">Deposits</a></li>
                <li><a class="nav-link" href="{{ route('payment-history', [$paymentModalModelInstance->transferSettlementType]) }}">Withdrawals</a></li>
              </ul>
            </li>

            @if(auth()->user()->privilegeChecker('add-and-update-coin-rate'))
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Coin Rates</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('create-coin-converstion-rate') }}">Create/Update Coin Rate</a></li>
              </ul>
            </li>
            @endif

            <li class="dropdown">
              <a href="{{ route('support-summary', [auth()->user()->unique_id]) }}" class="nav-link"><i class="fa fa-support"></i><span>{{ __('Support') }}</span></a>
            </li>

            {{-- @if(auth()->user()->type_of_user == $userModelInstance->normalUserType) --}}
            <li class="dropdown">
              <a href="{{ route('referals', [auth()->user()->type_of_user == $userModelInstance->normalUserType ? auth()->user()->unique_id : '']) }}" class="nav-link"><i class="fa fa-users"></i><span>{{ __('Referal Bonus') }}</span></a>
            </li>
            {{-- @endif --}}

            @if(auth()->user()->privilegeChecker('roles-management'))
            <li class="dropdown">
              <a href="{{ route('add_roles') }}" target="_blank" class="nav-link"><i class="fa fa-support"></i><span>Roles Management</span></a>
            </li>
            @endif

            @if(auth()->user()->privilegeChecker('manage-main-settings'))
            <li class="dropdown">
              <a href="{{ route('settings') }}" class="nav-link"><i class="fa fa-gear"></i><span>{{ __('Settings') }}</span></a>
            </li>
            @endif

            <li class="dropdown">
              <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form2').submit();" class="nav-link"><i data-feather="monitor"></i><span>{{ __('Logout') }}</span></a>
                <form id="logout-form2" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            </li>
          </ul>
        </aside>
      </div>

@endif
