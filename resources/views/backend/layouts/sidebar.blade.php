<style>
     .active_tab{
                border-radius: 8px;
                background: #233766;
                color: var(--White, var(--white, #FFF));
                border-radius: 10%;
            }
</style>
<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('admin')}}">

    <img src="{{asset('images/Group.png')}}" atl="virtuouscarat-logo" >
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item {{ request()->is('admin') ? 'active_tab' : '' }}">
      <a class="nav-link" href="{{ route('admin') }}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
      </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">


  {{-- Products --}}
  <li class="nav-item {{ request()->is('admin/product*') ? 'active_tab' : '' }}">
      <a class="nav-link collapsed" href="{{route('product.index')}}">
        <i class="fas fa-cubes"></i>
        <span>Products</span>
      </a>
  </li>




  <!--Orders -->
  <li class="nav-item {{ request()->is('admin/order*') ? 'active_tab' : '' }}">
    <a class="nav-link" href="{{ route('order.index') }}">
        <i class="fas fa-cart-plus"></i>
        <span>Orders</span>
    </a>
</li>



  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">
   <!-- Heading -->
  <div class="sidebar-heading">
      General Settings
  </div>

   <!-- Users -->
   <li class="nav-item {{ request()->is('admin/users*') ? 'active_tab' : '' }}">
    <a class="nav-link" href="{{ route('users.index') }}">
        <i class="fas fa-users"></i>
        <span>Vendor</span>
    </a>
</li>

<!-- General settings -->
<li class="nav-item {{ request()->is('admin/settings*') ? 'active_tab' : '' }}">
    <a class="nav-link" href="{{ route('settings') }}">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
    </a>
</li>

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>