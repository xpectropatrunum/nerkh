<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-legacy" data-widget="treeview" role="menu"
            data-accordion="false">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs(['admin.dashboard', 'admin.']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        {{ __('admin.dashboard') }}
                    </p>
                </a>
            </li>


 

            <li class="nav-item has-treeview {{ request()->routeIs(['admin.currencies.*']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-coins"></i>
                    <p>
                        Currencies
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview {{ request()->routeIs(['admin.currencies.*']) ? 'd-block' : 'display-none' }}">
                    <li class="nav-item">
                        <a href="{{ route('admin.currencies.index') }}"
                           class="nav-link {{ request()->routeIs(['admin.currencies.index']) ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>All Currencies</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.currencies.create') }}"
                           class="nav-link {{ request()->routeIs(['admin.currencies.create']) ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Add New</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview {{ request()->routeIs(['admin.prices.*']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-coins"></i>
                    <p>
                        Prices
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview {{ request()->routeIs(['admin.prices.*']) ? 'd-block' : 'display-none' }}">
                    <li class="nav-item">
                        <a href="{{ route('admin.prices.index') }}"
                           class="nav-link {{ request()->routeIs(['admin.prices.index']) ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>All Prices</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.currencies.create') }}"
                           class="nav-link {{ request()->routeIs(['admin.currencies.create']) ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Add New</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview {{ request()->routeIs(['admin.changes.*']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon  fa-solid fa-plus-minus"></i>
                    <p>
                        Diffs
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview {{ request()->routeIs(['admin.changes.*']) ? 'd-block' : 'display-none' }}">
                    <li class="nav-item">
                        <a href="{{ route('admin.changes.index') }}"
                           class="nav-link {{ request()->routeIs(['admin.changes.index']) ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>All Diffs</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.changes.create') }}"
                           class="nav-link {{ request()->routeIs(['admin.changes.create']) ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Add New</p>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
