<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->

        <div class="logo-header" data-background-color="dark">
            <a href="<?= base_url('Dashboard') ?>" class="logo">
                <img src="<?= base_url('uploads/settings/') . get_setting('logo') ?>" alt="navbar brand"
                    class="navbar-brand" height="40" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item">
                    <a href="<?= base_url('admin/dashboard/') ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('admin/users/') ?>">
                        <i class="fas fa-users"></i>
                        <p>Usuarios</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>