<?php $this->view('component/spinner') ?>
<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header  align-items-center">
            <a class="navbar-brand" href="javascript:void(0)">
                <img src="<?php echo base_url('assets/img/logo/logo-big-black.png') ?>" class="navbar-brand-img" alt="HAVOANA DIGITAL SOLUTION">
            </a>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">

                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">
                    <span class="docs-normal">GESTION</span>
                </h6>
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $parent_menu === 'dashboard' ? 'active' : '' ?>" href="<?php echo base_url('dashboard') ?>">
                            <i class="ni ni-tv-2 text-primary"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $parent_menu === 'gestion_stock' ? 'active' : '' ?>" href="<?php echo base_url('stock/gestion_stock') ?>">
                            <i class="ni ni-fat-add text-orange"></i>
                            <span class="nav-link-text">Ajout articles</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $parent_menu === 'visu_stock' ? 'active' : '' ?>" href="<?php echo base_url('stock/visu_stock') ?>">
                            <i class="ni ni-bullet-list-67 text-primary"></i>
                            <span class="nav-link-text">Mes articles</span>
                        </a>
                    </li>
                </ul>

                <hr class="my-3">

                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">
                    <span class="docs-normal">ADMINISTRATION</span>
                </h6>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $parent_menu === 'gestion_user' ? 'active' : '' ?>" href="<?php echo base_url('parametre/gestion_user') ?>">
                            <i class="ni ni-single-02 text-yellow"></i>
                            <span class="nav-link-text">Mes comptes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $parent_menu === 'gestion_device' ? 'active' : '' ?>" href="<?php echo base_url('parametre/gestion_device') ?>">
                            <i class="ni ni-laptop text-default"></i>
                            <span class="nav-link-text">Mes appareils</span>
                        </a>
                    </li>
                </ul>

                <!-- Divider -->
                <hr class="my-3">

                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">
                    <span class="docs-normal">Documentation</span>
                </h6>
                <!-- Navigation -->
                <ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $parent_menu === 'tutoriel' ? 'active' : '' ?>" href="<?php echo base_url('Documentation/tutoriel') ?>">
                            <i class="ni ni-hat-3"></i>
                            <span class="nav-link-text">Tutoriel</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $parent_menu === 'cgu' ? 'active' : '' ?>" href="<?php echo base_url('Documentation/cgu') ?>">
                            <i class="ni ni-book-bookmark"></i>
                            <span class="nav-link-text">C G U</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $parent_menu === 'aide' ? 'active' : '' ?>" href="<?php echo base_url('Documentation/aide') ?>">
                            <i class="ni ni-satisfied"></i>
                            <span class="nav-link-text">Besoin d'aide ?</span>
                        </a>
                    </li>
                </ul>
                <!-- Divider -->
                <hr class="my-3">
                <ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('Gateway/deconnect') ?>">
                            <i class="ni ni-user-run text-red"></i>
                            <span class="nav-link-text">Deconnexion</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<div class="main-content" id="panel">
