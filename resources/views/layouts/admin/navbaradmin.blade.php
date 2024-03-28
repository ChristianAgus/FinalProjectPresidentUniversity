<header id="page-header">
    <!-- Header Content -->
    <div class="content-header">
      <!-- Left Section -->
      <div class="space-x-1">
        <!-- Toggle Sidebar -->
        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
        <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="layout" data-action="sidebar_toggle">
          <i class="fa fa-fw fa-bars"></i>
        </button>
        <!-- END Toggle Sidebar -->

        <!-- User Dropdown -->
        <div class="dropdown d-inline-block" role="group">
          <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fw-semibold">{{ Auth::user()->name }}</span>
            <i class="fa fa-angle-down opacity-50 ms-1"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-md p-0" aria-labelledby="page-header-user-dropdown">
            <div class="p-2">
              <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" href="{{ route('akun.profil') }}">
                <span>Profile</span>
                <i class="fa fa-fw fa-user opacity-25"></i>
              {{-- </a>
              <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" href="{{ route('akun.user') }}">
                <span>User</span>
                <i class="fa fa-address-card opacity-25"></i>
              </a> --}}
              <div class="dropdown-divider"></div>
                <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                    <span>Sign Out</span>
                    <i class="fa fa-fw fa-sign-out-alt opacity-25"></i>
                  </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                      @csrf
                    </form>
            </div>
          </div>
        </div>
        <!-- END User Dropdown -->
      </div>
      <!-- END Left Section -->
    </div>
    <!-- END Header Content -->

    <!-- Header Search -->
    <div id="page-header-search" class="overlay-header bg-body-extra-light">
      <div class="content-header">
        <form class="w-100" action="db_minimal.html" method="POST">
          <div class="input-group">
            <!-- Close Search Section -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-secondary" data-toggle="layout" data-action="header_search_off">
              <i class="fa fa-fw fa-times"></i>
            </button>
            <!-- END Close Search Section -->
            <input type="text" class="form-control" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
            <button type="submit" class="btn btn-secondary">
              <i class="fa fa-fw fa-search"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- END Header Search -->

    <!-- Header Loader -->
    <div id="page-header-loader" class="overlay-header bg-primary">
      <div class="content-header">
        <div class="w-100 text-center">
          <i class="far fa-sun fa-spin text-white"></i>
        </div>
      </div>
    </div>
    <!-- END Header Loader -->
  </header>