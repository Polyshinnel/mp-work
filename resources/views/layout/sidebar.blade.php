<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <span class="brand-text font-weight-light">Кидсберри маркетплейсы</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">@yield('username')</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="/" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Главная
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">

                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            Озон
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/ozon-list" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Заказы</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/ozon-returnings" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Возвраты</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-space-shuttle"></i>
                        <p>
                            Wildberries
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/wb-list" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Заказы</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/wb-returnings" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Возвраты</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/wb-products" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Товары</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="/settings" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Настройки
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
