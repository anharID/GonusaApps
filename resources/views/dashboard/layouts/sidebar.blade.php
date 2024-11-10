<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Gonusa App</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Admin
    </div>

    <!-- Nav Item - Data -->
    <li class="nav-item {{ request()->is('users*') || request()->is('apps*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Data</span>
        </a>
        <div id="collapseUtilities"
            class="collapse {{ request()->is('users*') || request()->is('apps*') ? 'show' : '' }}"
            aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Data:</h6>
                <a class="collapse-item {{ request()->is('users*') ? 'active' : '' }}"
                    href="{{ route('users.index') }}">User</a>
                <a class="collapse-item {{ request()->is('apps*') ? 'active' : '' }}"
                    href="{{ route('apps.index') }}">Apps</a>
                {{-- <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a> --}}
            </div>
        </div>
    </li>

    <!-- Nav Item - Users Access -->
    <li class="nav-item {{ request()->is('user-access*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user-access.index') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Users Access</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Applications
    </div>

    @foreach($sidebarApps as $groupName => $groupedApps)
    <!-- Nav Item - Apps Group -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse{{ Str::slug($groupName) }}"
            aria-expanded="true" aria-controls="collapse{{ Str::slug($groupName) }}">
            <i class="fas fa-fw fa-folder"></i>
            <span>{{ $groupName }}</span>
        </a>
        <div id="collapse{{ Str::slug($groupName) }}" class="collapse"
            aria-labelledby="heading{{ Str::slug($groupName) }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">{{ $groupName }}:</h6>
                @foreach($groupedApps as $app)
                <a class="collapse-item" href="{{ $app->app_url }}" target="_blank">
                    {{ $app->app_name }}
                </a>
                @endforeach
            </div>
        </div>
    </li>
    @endforeach

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
