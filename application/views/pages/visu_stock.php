<div class="container">
    <h2>Visualisation Stock</h2>
    <div class="row mt-3">
        <div class="col-12">
            <table class="table table-bordered">
                <caption>Liste des produits</caption>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Code-barre</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Magasin</th>
                        <th scope="col">Quantité</th>
                        <th scope="col">Prix achat unitaire</th>
                        <th scope="col">Prix vente unitaire</th>
                        <th scope="col" colspan="3">Action</th>
                    </tr>
                </thead>
                <tbody id="visuTable"></tbody>
                <nav aria-label="Page navigation">
                    <div class="row">
                        <div class="col-2">
                            <ul class="pagination" id="paginationTable"></ul>
                        </div>
                        <div class="col-3 offset-1">
                            <select class="form-control" id="opt_magasin">
                                <option>Tous les magasins</option>
                                <?php echo $magasins_option ?>
                            </select>
                        </div>
                        <div class="col-5">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="valueSearch" placeholder="Recherche Code-barre, Nom ..." aria-describedby="search">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-info" type="button" id="search">Rechercher</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-info mb-3" id="refresh">
                                <svg class="bi bi-arrow-clockwise" width="1.3em" height="1.3em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M3.17 6.706a5 5 0 017.103-3.16.5.5 0 10.454-.892A6 6 0 1013.455 5.5a.5.5 0 00-.91.417 5 5 0 11-9.375.789z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M8.147.146a.5.5 0 01.707 0l2.5 2.5a.5.5 0 010 .708l-2.5 2.5a.5.5 0 11-.707-.708L10.293 3 8.147.854a.5.5 0 010-.708z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                    </div>
                </nav>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modifProduit" tabindex="-1" role="dialog" aria-labelledby="modifProduitLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifProduitLabel">Modification information produit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="new-codebarre" class="col-form-label">Code-barre:</label>
                        <input type="text" class="form-control" id="new-codebarre">
                    </div>
                    <div class="form-group">
                        <label for="new-name" class="col-form-label">Nom produit</label>
                        <input type="text" class="form-control" id="new-name">
                    </div>
                    <div class="form-group">
                        <label for="new-prixv" class="col-form-label">Prix de vente</label>
                        <input type="number" class="form-control" id="new-prixv">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="saveInfo">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mergeProduit" tabindex="-1" role="dialog" aria-labelledby="mergeProduitLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mergeProduitLabel">Migration article</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    <span class="alert-text">
                        <span class="alert-icon">
                            <i class="ni ni-active-40"></i>
                        </span>
                        Ici vous pouvez éffectuer les sorties en stock d'un article pour le mettre parmis les produits disponibles en magasin. <br />
                        Veuillez séléctionner un magasin parmis la liste ainsi que la quantité à mettre en vente.
                    </span>
                </div>
                <form>
                    <div class="form-group">
                        <label for="li-magasin" class="col-form-label">Liste magasin</label>
                        <select class="form-control" id="li-mag">
                            <option>-- MAGASIN --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tex-prix" class="col-form-label">Prix de vente pour ce magasin</label>
                        <input type="number" min=1 id="tex-prix" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tex-quantite" class="col-form-label">Quantité à mettre en vente</label>
                        <input type="number" min=1 id="tex-quantite" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="saveMigration">Migrer en vente</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        loadGrid({
            url: '<?php echo base_url('stock/gridProduit') ?>',
            selector: "#visuTable",
            perPage: 8
        });
    })

    $("#refresh").on('click', function() {
        forceHotReload();
        $("#valueSearch").val("");
    })

    $("#search").on('click', function() {
        searching($("#valueSearch").val());
    })

    $("#saveInfo").on("click", function() {
        var new_cb = $("#new-codebarre").val();
        var new_nm = $("#new-name").val();
        var new_prixv = $("#new-prixv").val();
        var id = $("#modifProduit").barika('getState', 'ID');
        var mag = $("#modifProduit").barika('getState', 'mag');
        var cb = $("#modifProduit").barika('getState', 'codebarre');

        var isNewCb = 1;

        if (new_cb === cb) {
            isNewCb = 0;
        }

        $.ajax({
            url: '<?php echo base_url('stock/editProduit') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                new_cb,
                new_nm,
                new_prixv,
                id,
                isNewCb,
                mag
            },
            async: true,
            success: function(response) {
                if (response.success === true) {
                    $("#modifProduit").modal('hide');
                    $.notify("Mise à jour avec succès !", "success");
                    forceHotReload();
                    $("#valueSearch").val("");
                } else {
                    $.notify(response.message, "error");
                }
            },
            error: function(error) {
                console.log(error);
                $.notify("Erreur interne, Veuillez contacter le responsable.", "error");
            }
        });
    })

    $("#saveMigration").on("click", function() {
        var new_mag = $("#li-mag").val();
        var quantite = $("#tex-quantite").val();
        var prix = $("#tex-prix").val();
        var codebarre = $("#mergeProduit").barika('getState', 'codebarre');
        var stk_mag = $("#mergeProduit").barika('getState', 'magasin');

        $.ajax({
            url: '<?php echo base_url('stock/migrateProduit') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                new_mag,
                quantite,
                codebarre,
                stk_mag,
                prix
            },
            async: true,
            success: function(response) {
                if (response.success === true) {
                    $("#mergeProduit").modal('hide');
                    $.notify("Mise à jour avec succès !", "success");
                    forceHotReload();
                    $("#valueSearch").val("");
                } else {
                    $.notify(response.message, "error");
                }
            },
            error: function(error) {
                console.log(error);
                $.notify("Erreur interne, Veuillez contacter le responsable.", "error");
            }
        });
    })

    $("#opt_magasin").on("change", function() {
        if (!$(this).children('option:first-child').is(':selected')) {
            var argUrl = $("#opt_magasin").val();
            reloadCustom('<?php echo base_url('stock/getMagasinStock/') ?>' + argUrl);
        } else {
            forceHotReload();
        }
    })

    var gridEditHandler = function(cb, mag) {
        $("#modal-spinner").modal('show');
        $.ajax({
            url: '<?php echo base_url('stock/getSpecifiqueProduitMag') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                cb,
                mag
            },
            async: true,
            success: function(response) {
                $(".modal-backdrop").remove();
                $("#modal-spinner").modal('hide');
                if (response.success === true) {
                    $("#new-codebarre").val(response.data.prd_codebarre);
                    $("#new-name").val(response.data.prd_nom);
                    $("#new-prixv").val(response.data.magst_prix);
                    $("#modifProduit").modal('show');
                    $("#modifProduit").barika('setState', {
                        'ID': response.data.prd_idexterne,
                        'mag': mag,
                        'codebarre': cb
                    });
                }
            }
        });
    }

    var gridDeleteHandler = function(cb, mag) {
        if (confirm("Êtes vous sûr de vouloir mettre la quantité total d'article dans ce magasin en anomalie ?")) {
            $("#modal-spinner").modal('show');
            $.ajax({
                url: '<?php echo base_url('stock/setAnomalie') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    cb,
                    mag
                },
                async: true,
                success: function(response) {
                    $(".modal-backdrop").remove();
                    $("#modal-spinner").modal('hide');
                    if (response.success === true) {
                        $.notify("Suppression avec success !", "success");
                        forceHotReload();
                    } else {
                        $.notify(response.message, "error");
                    }
                },
                error: function(error) {
                    $(".modal-backdrop").remove();
                    $("#modal-spinner").modal('hide');
                    console.log(error);
                    $.notify("Erreur interne, Veuillez contacter le responsable.", "error");
                    forceHotReload();
                }
            })
        }
    }

    var gridMergeMag = function(cb, mag, prix) {

        var options = $("#li-mag")

        options
            .find('option')
            .remove()
            .end()
            .append('<option >-- MAGASIN --</option>');

        $("#modal-spinner").modal('show');

        $.ajax({
            url: '<?php echo base_url('stock/getListeMag') ?>',
            type: 'POST',
            dataType: 'json',
            async: true,
            success: function(response) {
                $(".modal-backdrop").remove();
                $("#modal-spinner").modal('hide');
                if (response.success === true) {
                    $("#mergeProduit").modal('show');
                    $("#mergeProduit").barika('setState', {
                        'codebarre': cb,
                        'magasin': mag
                    });

                    $("#tex-prix").val(prix);

                    $.each(response.data, function() {
                        options.append($("<option />")
                            .val(this.value)
                            .text(this.libelle)
                        );
                    });
                }
            }
        });
    }
</script>
