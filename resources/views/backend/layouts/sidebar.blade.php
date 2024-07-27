{{-- <style>
    .active_tab {
        border-radius: 8px;
        background: #233766;
        color: var(--White, var(--white, #FFF));
        border-radius: 10%;
    }
    .sidebar.toggled .sidebar-logo2 {
        display: block !important;
    }
    .sidebar.toggled .sidebar-logo1 {
        display: none;
    }
</style>
<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion toggled " id="accordionSidebar">


    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin') }}"
        id="sidebarBrand">
        <img src="{{ asset('images/Group.png') }}" atl="virtuouscarat-logo" id="fullLogo" class="sidebar-logo1">
        <img src="{{ asset('images/vs.png') }}" atl="virtuouscarat-logo" id="smallLogo" class="sidebar-logo2 d-none">
    </a>

    <hr class="sidebar-divider my-0">

 
    <li class="nav-item {{ request()->is('admin') ? 'active_tab' : '' }}">
        <a class="nav-link" href="{{ route('admin') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>


    <hr class="sidebar-divider">



    <li class="nav-item {{ request()->is('admin/product*') ? 'active_tab' : '' }}">
        <a class="nav-link collapsed" href="{{ route('product.index') }}">
            <i class="fas fa-cubes"></i>
            <span>Products</span>
        </a>
    </li>





    <li class="nav-item {{ request()->is('admin/order*') ? 'active_tab' : '' }}">
        <a class="nav-link" href="{{ route('order.index') }}">
            <i class="fas fa-cart-plus"></i>
            <span>Orders</span>
        </a>
    </li>




    <hr class="sidebar-divider d-none d-md-block">
    
    <div class="sidebar-heading">
        General Settings
    </div>


    <li class="nav-item {{ request()->is('admin/users*') ? 'active_tab' : '' }}">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-users"></i>
            <span>Vendor</span>
        </a>
    </li>



</ul>
 --}}
