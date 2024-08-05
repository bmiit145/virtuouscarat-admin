<style>
.navbar {
    gap: 30px;

}

.head_menu {
    display: flex;
    gap: 25px;
}

.head_menu a {
    font-size: 16px;
    color: #000;
    font-weight: 600;
    line-height: 1.2;
    transition: all 0.5s ease;
    color: white;
}

.head_menu a:hover {
    text-decoration: none;
    color: #dbc675;
}

a.active_tab {
    color: #d0b861;
}

.bg-navbar {
    background-color: #132644 !important;
}

.text-gray-600 {
    color: #ffffff !important;
}
</style>

<nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    {{-- <button id="sidebarToggleTop" class="btn btn-link  rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button> --}}

    <img src="{{ asset('images/Group.png') }}" atl="virtuouscarat-logo" id="smallLogo" class="sidebar-logo2"
        style="width: 8%">


    <div class="head_menu">
        <a class="{{ request()->routeIs('admin') ? 'active_tab' : '' }}" href="{{ route('admin') }}">
            <span>Dashboard</span>
        </a>
        <a class="{{ request()->routeIs('product.index') ? 'active_tab' : '' }}" href="{{route('product.index')}}">
            <span>Products</span>
        </a>
        <a class="{{ request()->routeIs('order.index') ? 'active_tab' : '' }}" href="{{ route('order.index') }}">
            <span>Orders</span>
        </a>
        <a class="{{ request()->routeIs('users.index') ? 'active_tab' : '' }}" href="{{ route('users.index') }}">
            <span>Vendors</span>
        </a>
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown no-arrow">
            <form id="logout-form" action="{{ route('login.logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> {{ __('Logout') }}
                </button>
            </form>
        </li>

        {{-- Home page --}}

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <!-- Visit 'codeastro' for more projects -->

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @if(Auth()->user()->photo)
                <img class="img-profile rounded-circle" src="{{Auth()->user()->photo}}">
                @else
                <img class="img-profile rounded-circle" src="{{asset('backend/img/avatar.png')}}">
                @endif
                <span class="ml-2 text-gray-600 small">{{ Auth()->user()->name }}</span>
                <i class="fas fa-chevron-down ml-2"></i>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('admin-profile') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="{{ route('change.password.form') }}">
                    <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                    Change Password
                </a>
                <!-- <a class="dropdown-item" href="{{ route('settings') }}">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a> -->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('login.logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('login.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>

    </ul>

</nav>
