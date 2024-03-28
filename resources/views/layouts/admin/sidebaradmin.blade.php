<nav id="sidebar">
    <!-- Sidebar Content -->
    <div class="sidebar-content">
      <!-- Side Header -->
      <div class="content-header justify-content-lg-center">
        <!-- Logo -->
        <div>
          <span class="smini-visible fw-bold tracking-wide fs-lg">
            <span  style="color: #E4E7ED;">H</span><span  style="color: #3F9CE8;">F</span>
          </span>
          <a class="link-fx fw-bold tracking-wide mx-auto" href="{{ route('home') }}">
            <span class="smini-hidden">
              <i class="fa-solid fa-seedling"></i>
              <span class="fs-4" style="color: #E4E7ED;">Haldin</span><span class="fs-4" style="color: #3F9CE8;">Foods</span>
            </span>
          </a>
        </div>
        <!-- END Logo -->

        <!-- Options -->
        <div>
          <!-- Close Sidebar, Visible only on mobile screens -->
          <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
          <button type="button" class="btn btn-sm btn-alt-danger d-lg-none" data-toggle="layout" data-action="sidebar_close">
            <i class="fa fa-fw fa-times"></i>
          </button>
          <!-- END Close Sidebar -->
        </div>
        <!-- END Options -->
      </div>
      <!-- END Side Header -->

      <!-- Sidebar Scrolling -->
      <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side content-side-full">
          <ul class="nav-main">
            @if(auth()->check() && auth()->user()->role == 'Admin')
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->is('exhibition/akun/user') ? 'active' : '' }}" href="{{ route('akun.user') }}">
                    <i class="nav-main-link-icon fa fa-address-card"></i>
                    <span class="nav-main-link-name">User Management</span>
                </a>
            </li>
        @endif
        @if(auth()->check() && auth()->user()->role == 'Sales')         
            <li class="nav-main-heading">Data Product & Order</li>
            <li class="nav-main-item">
              <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-star"></i>
                <span class="nav-main-link-name">Master Product</span>
              </a>
              <ul class="nav-main-submenu">
                <li class="nav-main-item {{ request()->is('exhibition/master/category/index') ? 'active' : '' }}">
                  <a class="nav-main-link {{ request()->is('exhibition/master/category/index') || request()->is('exhibition/master/category/index') ? 'active' : '' }}" href="{{ route('category.index') }}">
                    <span class="nav-main-link-name">Category</span>
                  </a>
                </li>
                <li class="nav-main-item {{ request()->is('exhibition/master/product/index') ? 'active' : '' }}">
                  <a class="nav-main-link {{ request()->is('exhibition/master/product/index') || request()->is('exhibition/master/product/index') ? 'active' : '' }}" href="{{ route('product.index') }}">
                    <span class="nav-main-link-name">Product</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-main-item">
              <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-bag-shopping"></i>
                <span class="nav-main-link-name">Data Order</span>
              </a>
              <ul class="nav-main-submenu">
                <li class="nav-main-item {{ request()->is('exhibition/history') ? 'active' : '' }}">
                  <a class="nav-main-link {{ request()->is('exhibition/master/history/order') || request()->is('exhibition/master/history/order') ? 'active' : '' }}" href="{{ route('order.index') }}">
                    <span class="nav-main-link-name">Order</span>
                  </a>
                </li>
              </ul>
            </li>
            @endif
          </ul>
        </div>
        <!-- END Side Navigation -->
      </div>
      <!-- END Sidebar Scrolling -->
    </div>
    <!-- Sidebar Content -->
  </nav>