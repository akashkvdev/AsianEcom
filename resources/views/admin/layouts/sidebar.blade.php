<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">FLIPKART SHOP</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{route('admin.dashboard')}}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Categories -->
                <li class="nav-item has-treeview {{ request()->is('categories*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('categories*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Categories
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->is('categories*') ? 'display: block;' : 'display: none;' }}">
                        <li class="nav-item">
                            <a href="{{ route('categories.create') }}" class="nav-link {{ request()->routeIs('categories.create') ? 'active' : '' }}">
                                <i class="nav-icon {{ request()->routeIs('categories.create') ? 'fas fa-circle' : 'far fa-circle' }}"></i>
                                <p>Add Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                                <i class="nav-icon {{ request()->routeIs('categories.index') ? 'fas fa-circle' : 'far fa-circle' }}"></i>
                                <p>Category List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Sub Categories -->
                <li class="nav-item has-treeview {{ request()->is('sub-categories*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('sub-categories*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Sub Categories
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->is('sub-categories*') ? 'display: block;' : 'display: none;' }}">
                        <li class="nav-item">
                            <a href="{{ route('sub-categories.create') }}" class="nav-link {{ request()->routeIs('sub-categories.create') ? 'active' : '' }}">
                                <i class="nav-icon {{ request()->routeIs('sub-categories.create') ? 'fas fa-circle' : 'far fa-circle' }}"></i>
                                <p>Add Sub Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sub-categories.index') }}" class="nav-link {{ request()->routeIs('sub-categories.index') ? 'active' : '' }}">
                                <i class="nav-icon {{ request()->routeIs('sub-categories.index') ? 'fas fa-circle' : 'far fa-circle' }}"></i>
                                <p>Sub Category List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                  <!-- Brands -->
                  <li class="nav-item has-treeview {{ request()->is('brand*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('brand*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                           Brands
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->is('brands*') ? 'display: block;' : 'display: none;' }}">
                        <li class="nav-item">
                            <a href="{{ route('brands.create') }}" class="nav-link {{ request()->routeIs('brands.create') ? 'active' : '' }}">
                                <i class="nav-icon {{ request()->routeIs('brands.create') ? 'fas fa-circle' : 'far fa-circle' }}"></i>
                                <p>Add Brands</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('brands.index') }}" class="nav-link {{ request()->routeIs('brands.index') ? 'active' : '' }}">
                                <i class="nav-icon {{ request()->routeIs('brands.index') ? 'fas fa-circle' : 'far fa-circle' }}"></i>
                                <p>Brands List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                  <!-- Products -->
                  <li class="nav-item has-treeview {{ request()->is('products*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('products*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                           Products
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ request()->is('products*') ? 'display: block;' : 'display: none;' }}">
                        <li class="nav-item">
                            <a href="{{ route('products.create') }}" class="nav-link {{ request()->routeIs('products.create') ? 'active' : '' }}">
                                <i class="nav-icon {{ request()->routeIs('products.create') ? 'fas fa-circle' : 'far fa-circle' }}"></i>
                                <p>Add Products</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}">
                                <i class="nav-icon {{ request()->routeIs('products.index') ? 'fas fa-circle' : 'far fa-circle' }}"></i>
                                <p>Products List</p>
                            </a>
                        </li>
                    </ul>
                </li>
               

                <!-- Products -->
                <li class="nav-item">
                    <a href="products.html" class="nav-link {{ request()->routeIs('products') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Products</p>
                    </a>
                </li>

                <!-- Shipping -->
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('shipping') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shipping-fast"></i>
                        <p>Shipping</p>
                    </a>
                </li>

                <!-- Orders -->
                <li class="nav-item">
                    <a href="orders.html" class="nav-link {{ request()->routeIs('orders') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>Orders</p>
                    </a>
                </li>

                <!-- Discounts -->
                <li class="nav-item">
                    <a href="discount.html" class="nav-link {{ request()->routeIs('discounts') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-percent"></i>
                        <p>Discounts</p>
                    </a>
                </li>

                <!-- Users -->
                <li class="nav-item">
                    <a href="users.html" class="nav-link {{ request()->routeIs('users') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>

                <!-- Pages -->
                <li class="nav-item">
                    <a href="pages.html" class="nav-link {{ request()->routeIs('pages') ? 'active' : '' }}">
                        <i class="nav-icon far fa-file-alt"></i>
                        <p>Pages</p>
                    </a>
                </li>

            </ul>
        </nav>
        
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        // Toggle submenu on click
        $('.nav-item.has-treeview > a').click(function(e) {
            e.preventDefault();
            var $submenu = $(this).next('.nav-treeview');

            // Toggle the submenu display
            if ($submenu.is(':visible')) {
                $submenu.slideUp();
            } else {
                $('.nav-treeview').slideUp(); // Close other open submenus
                $submenu.slideDown();
            }
        });

        // Check if there's any active menu item and keep its parent menu open
        $('.nav-item.has-treeview ul.nav-treeview .nav-link.active').closest('.has-treeview').addClass('menu-open');
        $('.nav-item.has-treeview ul.nav-treeview .nav-link.active').closest('.nav-treeview').css('display', 'block');
    });
</script>
