<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ERP Havoana">
    <meta name="author" content="TchiRubick">
    <meta name="google-site-verification" content="uuDUX5AHLg1anp3xX_0W3ZeXXkiCZlZQa9U81Qhxiss" />
    <title>HAVOANA ERP</title>
    <!-- Favicon -->
    <!-- <link rel="icon" href="<?php echo base_url('/assets/img/brand/favicon.png') ?>" type="image/png"> -->
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo base_url('/assets/vendor/nucleo/css/nucleo.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') ?>" type="text/css">
    <!-- Argon CSS -->
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/argon.css?v=1.2.0') ?>" type="text/css">
</head>

<?php
$input = $this->session->flashdata("autoComplete");
?>

<body class="bg-default">
    <!-- Main content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header bg-gradient-primary py-5 py-lg-6 pt-lg-6">
            <div class="container">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <img src="<?php echo base_url('assets/img/logo/logo-big-white.png') ?>">
                    </div>
                </div>
            </div>

            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>

        <!-- Page content -->
        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card bg-secondary border-0 mb-0">
                        <div class="card-body px-lg-5 py-lg-5">
                            <div class="text-center text-muted mb-4">
                                <small>ERP HAVOANA AUTHENTIFICATION</small>
                            </div>
                            <form role="form" method="POST" action="<?php echo base_url('home/verification') ?>">
                                <div class=" form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-app"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Societe" name="societe" type="text" value="<?php echo isset($input['societe']) ? $input['societe'] : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Login" name="login" type="text" value="<?php echo isset($input['login']) ? $input['login'] : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Mot de passe" name="password" type="password">
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary my-4">Se connecter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Argon Scripts -->
    <!-- Core -->
    <script src="<?php echo base_url('/assets/vendor/jquery/dist/jquery.min.js') ?>"></script>
    <script src="<?php echo base_url('/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?php echo base_url('/assets/vendor/js-cookie/js.cookie.js') ?>"></script>
    <script src="<?php echo base_url('/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') ?>"></script>
    <script src="<?php echo base_url('/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') ?>"></script>
    <!-- Argon JS -->
    <script src="<?php echo base_url('/assets/js/argon.js?v=1.2.0') ?>"></script>
</body>

</html>
