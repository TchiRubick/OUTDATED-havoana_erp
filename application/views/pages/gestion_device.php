<div class="container">
    <h2>Liste appareil de caisse</h2>
    <div class="row mt-3">
        <div class="col-12">
            <table class="table table-bordered">
                <caption>Liste des appareils</caption>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Detail</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="visuDevice"></tbody>
                <nav aria-label="Page navigation">
                    <div class="row">
                        <div class="col-3">
                            <ul class="pagination" id="paginationTable"></ul>
                        </div>
                        <div class="col-5 offset-3">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="valueSearch" placeholder="Recherche Nom, detail" aria-describedby="search">
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

<div class="modal fade" id="modifDevice" tabindex="-1" role="dialog" aria-labelledby="modifDeviceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifDeviceLabel">Modification nom appareil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="new-name" class="col-form-label">Nom appareil</label>
                        <input type="text" class="form-control" id="new-name">
                    </div>
                    <div class="form-group">
                        <label for="allowed-user" class="col-form-label">Agent de caisse autorisé</label>
                        <select class="custom-select" id="allowed-user" multiple>
                            <?php echo $options_user; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="allowed-mag" class="col-form-label">Magasin assignée au caisse</label>
                        <select class="custom-select" id="allowed-mag">
                            <option value="0"> -- Magasin -- </option>
                            <?php echo $options_magasin; ?>
                        </select>
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

<script>
    $(document).ready(function() {
        loadGrid({
            url: '<?php echo base_url('parametre/gridDevice') ?>',
            selector: "#visuDevice",
            perPage: 2
        });
    })

    $("#refresh").on('click', function() {
        forceHotReload();
        $("#valueSearch").val("");
    })

    $("#search").on('click', function() {
        searching($("#valueSearch").val());
    })

    $("#saveInfo").on('click', function() {
        var idd = $("#modifDevice").barika('getState', 'IDD');
        var nn = $("#new-name").val();
        var lu = $("#allowed-user").val();
        var lm = $("#allowed-mag").val();

        $(".modal").modal('hide');
        $("#modal-spinner").modal('show');
        $.ajax({
            url: '<?php echo base_url('parametre/changeNomDevice') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                idd,
                nn,
                lu,
                lm
            },
            async: true,
            success: function(response) {
                if (response.success === true) {
                    $("#modifDevice").modal('hide');
                    $("#modifDevice").barika('setState', {
                        'IDD': null
                    });
                    $.notify("Enregistrer avec success", "success");
                    forceHotReload();
                } else {
                    $.notify(response.message, "error");
                    $("#modifDevice").barika('setState', {
                        'IDD': null
                    });
                }
                $("#allowed-user").val([]);
                $(".modal-backdrop").remove();
                $("#modal-spinner").modal('hide');
            },
            error: function(error) {
                console.log(error)
                $.notify("Erreur interne, Veuillez contacter le responsable.", "error");
                $(".modal-backdrop").remove();
                $("#modal-spinner").modal('hide');
            }
        })
    })

    var gridSwitchHandler = function(ideDevice) {
        console.log(ideDevice)
        $("#modal-spinner").modal('show');
        $.ajax({
            url: '<?php echo base_url('parametre/switchDevice') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                ideDevice
            },
            async: true,
            success: function(response) {
                if (response.success === true) {
                    $.notify("Satut appareil changer avec succès", "success");
                } else {
                    $.notify(response.message, "error");
                }
                forceHotReload();
                $(".modal-backdrop").remove();
                $("#modal-spinner").modal('hide');
            },
            error: function(error) {
                console.log(error);
                $.notify("Erreur interne, Veuillez contacter le responsable.", "error");
                forceHotReload();
                $(".modal-backdrop").remove();
                $("#modal-spinner").modal('hide');
            }
        });
    }

    var gridEditHandler = function(ideDevice, nom) {
        $("#allowed-user").val([]);

        $.ajax({
            url: '<?php echo base_url('parametre/getUserMagByDevice') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                ideDevice
            },
            async: true,
            success: function(response) {
                if (response.success === true) {
                    if (response.data.length > 0) {
                        response.data.forEach(el => {

                            if (el.utl_idexterne.trim().length > 0) {
                                $("#allowed-user option[value=" + el.utl_idexterne + "]").prop('selected', true)
                            }

                            if (el.mag_code.trim() > 0) {
                                $("#allowed-mag option[value=" + el.mag_code + "]").prop('selected', true)
                            }
                        });
                    }
                }
            }
        })

        $("#modifDevice").modal('show');
        $("#new-name").val(nom);
        $("#modifDevice").barika('setState', {
            'IDD': ideDevice
        });
    }

    var gridDeleteHandler = function(ideDevice) {

        if (confirm("Êtes vous sûr de vouloir supprimer cette appareil ?") === true) {
            $("#modal-spinner").modal('show');
            $.ajax({
                url: '<?php echo base_url('parametre/deleteDevice') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    ideDevice
                },
                async: true,
                success: function(response) {
                    if (response.success === true) {
                        $.notify("Suppression avec success", "success");
                        forceHotReload();
                    } else {
                        $.notify(response.message, "error");
                    }
                    $(".modal-backdrop").remove();
                    $("#modal-spinner").modal('hide');
                },
                error: function(error) {
                    console.log(error);
                    $.notify("Erreur interne, Veuillez contacter le responsable.", "error");
                    forceHotReload();
                    $(".modal-backdrop").remove();
                    $("#modal-spinner").modal('hide');
                }
            })
        }
    }
</script>
