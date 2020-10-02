<?php
$input = $this->session->flashdata("autoComplete");
?>
<form method="POST" action="<?php echo base_url('home/verification') ?>">
    <div class="card authCard">
        <div class="card-body">
            <h5 class="card-title">
                <img src="<?php echo base_url('assets/img/logo.png') ?>" width="100"></img>
                Authentification
            </h5>
            <div class="form-group">
                <label for="societe">Société</label>
                <input type="text" class="form-control" name="societe" id="societe" placeholder="" value="<?php echo $input['societe'] ?>">
            </div>
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" class="form-control" name="login" id="login" placeholder="" value="<?php echo $input['login'] ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" min="1" class="form-control" name="password" id="password">
            </div>
            <input type="submit" class="btn btn-info btn-lg btn-block" value="Authentification">
            <?php if ($this->session->flashdata("erreurformulaire") !== null) { ?>
                <div class="alert alert-danger mt-5" role="alert">
                    <?php echo $this->session->flashdata("erreurformulaire") ?>
                </div>
            <?php } ?>
        </div>
    </div>
</form>
