        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="#">
                    <img src="img/logo-text.png" alt="MailMan Logo" />
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li <?php if($_SERVER['PHP_SELF'] == "/dashboard.php") { echo "class=\"active\"";} ?>>
                            <a class="js-arrow" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>
                        <li <?php if($_SERVER['PHP_SELF'] == "/users.php") { echo "class=\"active\"";}?>>
                            <a href="users.php">
                                <i class="fas fa-users"></i>Users</a>
                        </li>
                        <li <?php if($_SERVER['PHP_SELF'] == "/options.php") { echo "class=\"active\"";} ?>>
                            <a href="table.html">
                                <i class="fas fa-table"></i>Options</a>
                        </li>
                        
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Pages</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <a href="login.html">Login</a>
                                </li>
                                <li>
                                    <a href="register.html">Register</a>
                                </li>
                                <li>
                                    <a href="forget-pass.html">Forget Password</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->