
<div class="container">
    <h2>Gestion des comptes utilisateur</h2>
    <div class="row mt-3">
        <div class="col-12">
            <table class="table table-bordered">
                <caption>Liste des utilisateurs</caption>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Login</th>
                        <th scope="col">Email</th>
                        <th scope="col">Compte</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="visuUser"></tbody>
                <nav aria-label="Page navigation">
                    <div class="row">
                        <div class="col-3">
                            <ul class="pagination" id="paginationTable"></ul>
                        </div>
                        <div class="col-5 offset-3">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="valueSearch" placeholder="Recherche Nom, Email, Role" aria-describedby="search">
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

<div class="modal fade" id="modifUser" tabindex="-1" role="dialog" aria-labelledby="modifUserLabel" aria-hidden="true" tabindex="998">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifUserLabel">Modification Utilisateur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <span class="alert-text">
                        <span class="alert-icon">
                            <i class="ni ni-active-40"></i>
                        </span>
                        <strong>Attention!</strong>
                        Soyez prudent lors de la modification de l'accès à un compte.<br/>
                        Un nouveau login et mot de passe seront envoyés à l'adresse mail spécifier.<br/>
                        Les identifiants de connexion au compte seront directement mises à jour même si le mail à rencontrer une erreur lors de l'envoi.<br/>
                        <b>L'envoie du mail peu prendre plusieurs minutes.</b>
                        </span>
                </div>
                <form>
                    <div class="form-group">
                        <label for="new-email" class="col-form-label">E-mail de la personne assignée à ce compte</label>
                        <input type="email" class="form-control" id="new-email">
                        <small class="text-mute">Un mail sera envoyé à cette adresse. Il contiendra le nouveau login et mot de passe d'authentification</small>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-form-label">Veuillez entrer le mot de pass administrateur pour pouvoir valider</label>
                        <input type="password" class="form-control" id="password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close-modal" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="saveInfo">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        loadGrid({
            url: '<?php echo base_url('parametre/gridUser') ?>',
            selector: "#visuUser",
            perPage: 5
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
        var nem = $("#new-email").val();
        var pas = $("#password").val();
        var idu = $("#modifUser").barika('getState', 'IDU');

        $(".modal").modal('hide');
        $("#modal-spinner").modal('show');
        $.ajax({
            url: '<?php echo base_url('parametre/editUser') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                nem,
                pas,
                idu
            },
            async: true,
            success: function(response) {

                if (response.success === true) {
                    $.notify("Mise à jour appliquée, vous recevrez un mail avec les identifiants d'authentification !", "success");

                    if (response.data.relog === true) {
                        alert("Le compte modifier est le votre, Vous allez être déconnecté");
                        window.location.href("<?php base_url("gateway/deconnect") ?>")
                    }
                } else {
                    $.notify(response.message, "error");
                }
                console.log('ok')
                $(".modal-backdrop").remove();
                $("#modal-spinner").modal('hide');
            },
            error: function(error) {
                console.log(error);
                $.notify("Erreur interne, Veuillez contacter le responsable.", "error");
                $(".modal-backdrop").remove();
                $("#modal-spinner").modal('hide');
            }
        })
    })

    var gridSwitchHandler = function(ideUser) {
        $("#modal-spinner").modal('show');
        $.ajax({
            url: '<?php echo base_url('parametre/switchUser') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                ideUser
            },
            async: true,
            success: function(response) {
                if (response.success === true) {
                    $.notify("Statut utilisateur changer avec succès", "success");
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
                $(".modal-backdrop").remove();
                $("#modal-spinner").modal('hide');
            }
        });
    }

    var gridEditHandler = function(ideUser, email) {
        $("#modifUser").modal('show');
        $("#new-email").val(email);
        $("#password").val("");
        $("#switch-password").removeAttr("checked")
        $("#modifUser").barika('setState', {
            'IDU': ideUser
        });
    }
</script>
