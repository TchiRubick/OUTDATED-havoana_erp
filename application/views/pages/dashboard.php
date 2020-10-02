<div class="container">
    <h2>Visualisation vente</h2>

    <div class="row mt-3">
        <div class="col-6">
            <?php $this->view("component/chartVente") ?>
        </div>
        <div class="col-6">
            <?php $this->view("component/chartProduit") ?>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <table class="table table-bordered ">
                <caption>Ventes</caption>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Magasin</th>
                        <th scope="col">Nom caisse</th>
                        <th scope="col">Email caissier</th>
                        <th scope="col">Login caissier</th>
                        <th scope="col">Date de vente</th>
                        <th scope="col">Montant vente</th>
                        <th scope="col">Quantite vendu</th>
                        <th scope="col">Article vendu</th>
                    </tr>
                </thead>
                <tbody id="venteTable"></tbody>
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
                        <div class="col-5 ">
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

<script>
    $(document).ready(function() {
        loadGrid({
            url: '<?php echo base_url('dashboard/getallvente') ?>',
            selector: "#venteTable",
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

    $("#opt_magasin").on("change", function() {
        if (!$(this).children('option:first-child').is(':selected')) {
            var argUrl = $("#opt_magasin").val();
            reloadCustom('<?php echo base_url('dashboard/getMagasinVente/') ?>' + argUrl);
        } else {
            forceHotReload();
        }
    })

</script>
