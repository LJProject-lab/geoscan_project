<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Control Panel</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
                    aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    User configuration
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link" href="students.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                            Students
                        </a>
                        <a class="nav-link" href="coordinators.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-user-tie"></i></div>
                            Coordinators
                        </a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages_ctrl"
                    aria-expanded="false" aria-controls="collapsePages_ctrl">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                    Programs & Company Configuration
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages_ctrl" aria-labelledby="headingTwo"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link" href="program.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-book"></i></div>
                            Programs
                        </a>
                        <a class="nav-link" href="companies.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-building"></i></div>
                            Companies
                        </a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages2"
                    aria-expanded="false" aria-controls="collapsePages2">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Audit Trail
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages2" aria-labelledby="headingTwo"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Time Logs
                        </a>
                        <a class="nav-link" href="action_logs.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-simple"></i></div>
                            Action Logs
                        </a>
                    </nav>
                </div>
                <div class="sb-sidenav-menu-heading">Extras</div>
                <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                    Account Settings
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            Admin
        </div>
    </nav>
</div>