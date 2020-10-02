<div class="card">
    <div class="card-header bg-transparent">
        <div class="row align-items-center">
            <div class="col">
                <h6 class="text-uppercase text-muted ls-1 mb-1">Quantité</h6>
                <h5 class="h3 mb-0">Total vente par produit</h5>
            </div>
            <div class="col">
                <select class="form-control" id="opt_produit_magasin">
                    <option value="0">Tous les magasins</option>
                    <?php echo $magasins_option ?>
                </select>
            </div>
            <div class="col">
                <select class="form-control" id="opt_produit_produit">
                    <?php echo $produits_option ?>
                </select>
            </div>
            <div class="col">
                <select class="form-control" id="opt_produit_annee">
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="chart">
            <canvas id="chart-produit" class="chart-canvas"></canvas>
        </div>
    </div>
</div>

<script>
    function chartOptionsProduit() {

        var $toggle = $('[data-toggle="chart"]');
        var mode = 'light'; //(themeMode) ? themeMode : 'light';
        var fonts = {
            base: 'Open Sans'
        }

        // Colors
        var colors = {
            gray: {
                100: '#f6f9fc',
                200: '#e9ecef',
                300: '#dee2e6',
                400: '#ced4da',
                500: '#adb5bd',
                600: '#8898aa',
                700: '#525f7f',
                800: '#32325d',
                900: '#212529'
            },
            theme: {
                'default': '#172b4d',
                'primary': '#5e72e4',
                'secondary': '#f4f5f7',
                'info': '#11cdef',
                'success': '#2dce89',
                'danger': '#f5365c',
                'warning': '#fb6340'
            },
            black: '#12263F',
            white: '#FFFFFF',
            transparent: 'transparent',
        };

        // Options
        var options = {
            defaults: {
                global: {
                    responsive: true,
                    maintainAspectRatio: false,
                    defaultColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
                    defaultFontColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
                    defaultFontFamily: fonts.base,
                    defaultFontSize: 13,
                    layout: {
                        padding: 0
                    },
                    legend: {
                        display: false,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 16
                        }
                    },
                    elements: {
                        point: {
                            radius: 0,
                            backgroundColor: colors.theme['primary']
                        },
                        line: {
                            tension: .4,
                            borderWidth: 4,
                            borderColor: colors.theme['primary'],
                            backgroundColor: colors.transparent,
                            borderCapStyle: 'rounded'
                        },
                        rectangle: {
                            backgroundColor: colors.theme['success']
                        },
                        arc: {
                            backgroundColor: colors.theme['primary'],
                            borderColor: (mode == 'dark') ? colors.gray[800] : colors.white,
                            borderWidth: 4
                        }
                    },
                    tooltips: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                    }
                },
                doughnut: {
                    cutoutPercentage: 83,
                    legendCallback: function(chart) {
                        var data = chart.data;
                        var content = '';

                        data.labels.forEach(function(label, index) {
                            var bgColor = data.datasets[0].backgroundColor[index];

                            content += '<span class="chart-legend-item">';
                            content += '<i class="chart-legend-indicator" style="background-color: ' + bgColor + '"></i>';
                            content += label;
                            content += '</span>';
                        });

                        return content;
                    }
                }
            }
        }

        // yAxes
        Chart.scaleService.updateScaleDefaults('linear', {
            gridLines: {
                borderDash: [2],
                borderDashOffset: [2],
                color: (mode == 'dark') ? colors.gray[900] : colors.gray[300],
                drawBorder: false,
                drawTicks: false,
                drawOnChartArea: true,
                zeroLineWidth: 0,
                zeroLineColor: 'rgba(0,0,0,0)',
                zeroLineBorderDash: [2],
                zeroLineBorderDashOffset: [2]
            },
            ticks: {
                beginAtZero: true,
                padding: 10,
                callback: function(value) {
                    if (!(value % 10)) {
                        return value
                    }
                }
            }
        });

        // xAxes
        Chart.scaleService.updateScaleDefaults('category', {
            gridLines: {
                drawBorder: false,
                drawOnChartArea: false,
                drawTicks: false
            },
            ticks: {
                padding: 20
            },
            maxBarThickness: 10
        });

        return options;

    }

    function parseOptions(parent, options) {
        for (var item in options) {
            if (typeof options[item] !== 'object') {
                parent[item] = options[item];
            } else {
                parseOptions(parent[item], options[item]);
            }
        }
    }

    var chartinitProduit = function(value) {

        if (window.Chart) {
            parseOptions(Chart, chartOptionsProduit());
        }

        var $chartp = $('#chart-produit');


        function initChartProduit($chartp) {

            var ordersChart = new Chart($chartp, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Quantité',
                        data: value
                    }]
                }
            });

            $chartp.data('chart', ordersChart);
        }

        if ($chartp.length) {
            initChartProduit($chartp);
        }

    };

    $(document).ready(function() {
        mockChartProduit()
    })

    $("#opt_produit_produit").on("change", function() {
        mockChartProduit()
    })

    $("#opt_produit_annee").on("change", function() {
        mockChartProduit()
    })

    $("#opt_produit_magasin").on("change", function() {
        mockChartProduit()
    })

    var mockChartProduit = function(codebarre, annee, magasin) {
        var codebarre = $("#opt_produit_produit").val();
        var annee = $("#opt_produit_annee").val();
        var magasin = $("#opt_produit_magasin").val();

        $.ajax({
            url: '<?php echo base_url('dashboard/chartProduitOnload') ?>',
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {codebarre, annee, magasin},
            success: function(response) {
                if (response.success === true) {
                    chartinitProduit(response.data);
                } else {
                    chartinitProduit([]);
                }
            },
            error: function (error) {
                console.log(error);
                chartinitProduit([]);
            }
        });
    }

</script>
