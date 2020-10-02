<div class="container">
    <?php
    $input = $this->session->flashdata("autoComplete");
    $erreur = $this->session->flashdata('erreurformulaire');
    $success = $this->session->flashdata('successformulaire');
    $action = $this->session->flashdata('action');
    ?>

    <h2 class="mb-3">Ajout de produit</h2>
    <form method="POST" action="<?php echo base_url('stock/ajout') ?>">
        <div class="row">
            <div class="col-5">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nom">Nom produit <b class="text-danger">*</b></label>
                            <input type="text" class="form-control" name="nom" id="nom" placeholder="" value="<?php echo $input['nom'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="codebarre">Code-Barre <b class="text-danger">*</b></label>
                            <input type="text" class="form-control" name="codebarre" id="codebarre" placeholder="" value="<?php echo $input['codebarre'] ?>">
                            <small id="codebarreExist">
                                <div id="verifRun" style="display:none;">
                                    <div class="spinner-border spinner-border-sm text-info" role="status"></div> Verification du code-barre
                                </div>
                                <div id="verifOk" class="text-success" style="display:none;">
                                    Ce code-barre est encore libre
                                </div>
                                <div id="verifNonOk" class="text-warning" style="display:none;">
                                    Ce code-barre est déjà utilisé par le produit <b id="prdVerif"></b>
                                </div>
                            </small>
                            <small id="codebarreHelp" class="form-text text-mute">
                                <b class="text-danger">ATTENTION!</b> Si le code-barre est déjà éxistant, le nouveau produit ne sera pas inséré.
                                A la place le produit avec le code-barre sera mis à jour.
                                Soit la <a href="#quantite">quantité</a>, le <a href="#prixa">prix d'achat</a> et le <a href="#prixv">prix de vente</a>.
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="quantite">Quantité <b class="text-danger">*</b></label>
                            <input type="number" min="1" class="form-control" name="quantite" id="quantite" value="<?php echo $input['quantite'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="prixa">Prix d'achat unitaire</label>
                            <input type="number" min="1" class="form-control" name="prixa" id="prixa" value="<?php echo $input['prixa'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="prixv">Prix de vente unitaire <b class="text-danger">*</b></label>
                            <input type="number" min="1" class="form-control" name="prixv" id="prixv" value="<?php echo $input['prixv'] ?>">
                        </div>
                        <input type="hidden" name="action" value="add">
                        <small id="codebarreHelp" class="form-text text-mute">
                            Les champs accompagner d'un <b>Astérisque</b> (<b class="text-danger">*</b>) sont obligatoire.
                        </small>
                        <input type="submit" class="btn btn-primary">
                    </div>
                </div>
            </div>
            <div class="col-6 mt-3">
                <div class="row">
                    <div class="col">
                        <label class="my-1 mr-2" for="produit">Produit à modifier</label>
                        <select class="custom-select my-1 mr-sm-2" id="produit">
                            <option selected>-- PRODUIT --</option>
                            <?php echo $optionsProduit ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-5 mt-5">
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" id="tmp_codebarre" aria-describedby="button-new-cb" readonly>
                            <div class="input-group-append" id="button-new-cb">
                                <button class="btn btn-outline-primary" id="useNewCb" type="button">Utiliser</button>
                                <button type="button" class="btn btn-info " id="generatBC">Génerer un code-barre</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php if (isset($erreur) || isset($success)) { ?>
                        <div class="alert alert-<?php echo isset($erreur) ? 'danger' : 'success'; ?> alert-dismissible fade show text-center" role="alert">
                            <?php echo isset($erreur) ? $erreur : $success; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $("#generatBC").on('click', function() {
        let cb = Math.floor(Math.random() * 10000000000000);
        $("#tmp_codebarre").val(cb);
    })

    $("#useNewCb").on('click', function() {
        var New = $("#tmp_codebarre").val();

        if (New.length > 0) {
            $("#codebarre").val(New);
            checkSpecProduitByCb(New);
        }
    })

    $("#codebarre").on('change', function() {
        var cb = $(this).val();
        checkSpecProduitByCb(cb);
    })

    $("#produit").on('change', function() {
        var cb = $(this).val();

        checkSpecProduitByCb(cb);
    })

    function checkSpecProduitByCb(cb) {
        $("#verifRun").show();
        $("#verifOk").hide();
        $("#verifNonOk").hide();

        $.ajax({
            url: '<?php echo base_url('stock/getSpecifiqueProduit') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                cb
            },
            async: true,
            success: function(response) {
                if (response.success === true) {
                    $("#verifRun").hide();
                    $("#verifNonOk").show();
                    $("#prdVerif").html(response.data.prd_nom);
                    $("#nom").val(response.data.prd_nom);
                    $("#codebarre").val(response.data.prd_codebarre);
                    $("#quantite").val(response.data.prd_quantite);
                    $("#prixa").val(response.data.prd_prixachat);
                    $("#prixv").val(response.data.prd_prixvente);
                    $("#nom").attr("readonly", true);
                } else {
                    $("#verifRun").hide();
                    $("#verifOk").show();
                    $("#nom").attr("readonly", false);
                }
            }
        });
    }
</script>
