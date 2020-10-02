<?php
require "config.php";

$a_enseignes = getopt('b::');

if (isset($a_enseignes['b'])) {
    $s_enseignes = $a_enseignes['b'];
}

if (empty($s_enseignes)) {
    exit('Aucune enseigne séléctionnée');
}

// Création de l'array des code enseigne
$a_enseigne = explode(',', $s_enseignes);

foreach ($a_enseigne as $enseigne) {
    $conn = new Mysqli(HOSTDB, USERDB, PASSDB, $enseigne);

    echo ("Traitement " . $enseigne . "debut \n");

    $query = " SELECT tsc_device, tsc_user, tsc_datecrea, prd_idexterne, tscd_quantite, tscd_montant FROM t_transac "
     . " INNER JOIN tr_transacdetail ON tscd_transac = tsc_idexterne "
     . " INNER JOIN t_produit ON tscd_produit = prd_codebarre "
     . " WHERE tsc_action = 'SELL'";
    $result = mysqli_query($conn, $query);

    foreach ($result as $key => $value) {
        $insertQuery = " INSERT INTO t_vente (vnt_magid, vnt_caisse, vnt_userid, vnt_date, vnt_prix, vnt_type, vnt_quantite, vnt_prdid)"
            . " VALUES ('DEMO2', '" . $value['tsc_device'] . "', '" . $value['tsc_user'] . "', '" . $value['tsc_datecrea'] . "',"
            . " '" . $value['tscd_montant'] . "', 'VNT', '" . $value['tscd_quantite'] . "', '" . $value['prd_idexterne'] . "')";

        mysqli_query($conn, $insertQuery);
    }

    echo ("Traitement " . $enseigne . "Fin \n");
}

echo ("Terminée");
