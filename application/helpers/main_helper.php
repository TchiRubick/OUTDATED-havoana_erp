<?php
function gen_uuid()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

function optionFormatter($variable)
{
    $result = "";

    foreach ($variable as $value) {
        $result .= "<option value='" . $value['value'] . "'>" . $value['libelle'] . " </option>";
    }

    return $result;
}


function formatGridProduit($variable)
{
    foreach ($variable as &$value) {
        $value['edit'] = "<button type=\"button\" class=\"btn btn-info btn-sm\"  onclick=\"gridEditHandler('" . $value['prd_codebarre'] . "', '" . $value['magst_id'] . "')\""
            . "data-toggle=\"tooltip\" data-placement=\"left\" title=\"Modification\" >"
            . "<svg class=\"bi bi-pencil-square\" width=\"1em\" height=\"1em\" viewBox=\"0 0 16 16\" fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\">"
            . "<path d=\"M15.502 1.94a.5.5 0 010 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 01.707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 00-.121.196l-.805 2.414a.25.25 0 00.316.316l2.414-.805a.5.5 0 00.196-.12l6.813-6.814z\"/>"
            . "<path fill-rule=\"evenodd\" d=\"M1 13.5A1.5 1.5 0 002.5 15h11a1.5 1.5 0 001.5-1.5v-6a.5.5 0 00-1 0v6a.5.5 0 01-.5.5h-11a.5.5 0 01-.5-.5v-11a.5.5 0 01.5-.5H9a.5.5 0 000-1H2.5A1.5 1.5 0 001 2.5v11z\" clip-rule=\"evenodd\"/>"
            . "</svg>"
            . "</button>";

        if ($value['mag_type'] === "STK") {
            $value['migration'] = "<button type=\"button\" class=\"btn btn-success btn-sm\" onclick=\"gridMergeMag('" . $value['prd_codebarre'] . "', '" . $value['magst_id'] . "', '" . $value['magst_prix'] . "')\""
                . "data-toggle=\"tooltip\" data-placement=\"left\" title=\"Migrer en vente\" >"
                . "<svg class=\"bi bi-bag-plus\" width=\"1em\" height=\"1em\" viewBox=\"0 0 16 16\" fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\">"
                . "<path fill-rule=\"evenodd\" d=\"M14 5H2v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5zM1 4v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4H1z\"/>"
                . "<path d=\"M8 1.5A2.5 2.5 0 0 0 5.5 4h-1a3.5 3.5 0 1 1 7 0h-1A2.5 2.5 0 0 0 8 1.5z\"/>"
                . "<path fill-rule=\"evenodd\" d=\"M8 7.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5z\"/>"
                . "<path fill-rule=\"evenodd\" d=\"M7.5 10a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-2z\"/>"
                . "</svg>"
                . "</button>";
        } else {
            $value['migration'] = "";
        }

        $value['suppression'] = "<button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"gridDeleteHandler('" . $value['prd_codebarre'] . "', '" . $value['magst_id'] . "')\""
            . "data-toggle=\"tooltip\" data-placement=\"left\" title=\"Supprimer le produit en sotck\" >"
            . "<svg class=\"bi bi-x-square\" width=\"1em\" height=\"1em\" viewBox=\"0 0 16 16\" fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\">"
            . "<path fill-rule=\"evenodd\" d=\"M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z\"/>"
            . "<path fill-rule=\"evenodd\" d=\"M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z\"/>"
            . "<path fill-rule=\"evenodd\" d=\"M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z\"/>"
            . "</svg>"
            . "</button>";

        unset($value['magst_id']);
        unset($value['mag_type']);
    }

    return $variable;
}

function formatGridDevice($variable)
{
    foreach ($variable as &$value) {
        $value['dvc_detail'] = str_replace("&", "<br/>", str_replace("?", "", str_replace("=", " = ", $value['dvc_detail'])));

        $value['dvc_nom'] = ($value['dvc_nom'] === null ? "" : $value['dvc_nom']);

        $value['lxq_libelle'] = '<label class="custom-toggle">
            <input type="checkbox" id="switch' . $value['dvc_idexterne'] . '" ' . ($value['dvc_statut'] === "ACT" ? "checked" : " ") . ' onclick="gridSwitchHandler(\'' . $value['dvc_idexterne'] . '\')">
            <span class="custom-toggle-slider rounded-circle" data-label-off="Inactif" data-label-on="Actif"></span>
        </label>';

        $value['action'] = "<button type=\"button\" class=\"btn btn-light btn-sm\" onclick=\"gridEditHandler('" . $value['dvc_idexterne'] . "', '" . $value['dvc_nom'] . "')\">"
            . "<svg class=\"bi bi-pencil-square\" width=\"1em\" height=\"1em\" viewBox=\"0 0 16 16\" fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\">"
            . "<path d=\"M15.502 1.94a.5.5 0 010 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 01.707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 00-.121.196l-.805 2.414a.25.25 0 00.316.316l2.414-.805a.5.5 0 00.196-.12l6.813-6.814z\"/>"
            . "<path fill-rule=\"evenodd\" d=\"M1 13.5A1.5 1.5 0 002.5 15h11a1.5 1.5 0 001.5-1.5v-6a.5.5 0 00-1 0v6a.5.5 0 01-.5.5h-11a.5.5 0 01-.5-.5v-11a.5.5 0 01.5-.5H9a.5.5 0 000-1H2.5A1.5 1.5 0 001 2.5v11z\" clip-rule=\"evenodd\"/>"
            . "</svg>"
            . "</button>"
            . "<button type=\"button\" class=\"btn btn-light btn-sm\" onclick=\"gridDeleteHandler('" . $value['dvc_idexterne'] . "')\">"
            . "<svg class=\"bi bi-x-square\" width=\"1em\" height=\"1em\" viewBox=\"0 0 16 16\" fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\">"
            . "<path fill-rule=\"evenodd\" d=\"M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z\"/>"
            . "<path fill-rule=\"evenodd\" d=\"M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z\"/>"
            . "<path fill-rule=\"evenodd\" d=\"M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z\"/>"
            . "</svg>"
            . "</button>";

        unset($value['dvc_idexterne']);
        unset($value['dvc_statut']);
    }

    return $variable;
}


function formatGridUser($variable)
{
    foreach ($variable as &$value) {

        $value['utl_email'] = ($value['utl_email'] === null ? "" : $value['utl_email']);

        $value['utl_status'] = ($value['utl_status'] === "1" ? "Actif" : "Inactif");

        $value['utl_status'] = '<label class="custom-toggle">
                <input type="checkbox" id="switch' . $value['utl_idexterne'] . '" ' . ($value['utl_status'] === "Actif" ? "checked" : " ") . ' onclick="gridSwitchHandler(\'' . $value['utl_idexterne'] . '\')">
                <span class="custom-toggle-slider rounded-circle" data-label-off="Actif" data-label-on="Inactif"></span>
            </label>';

        $value['action'] = "<button type=\"button\" class=\"btn btn-light btn-sm\" onclick=\"gridEditHandler('" . $value['utl_idexterne'] . "', '" . $value['utl_email'] . "')\">"
            . "<svg class=\"bi bi-pencil-square\" width=\"1em\" height=\"1em\" viewBox=\"0 0 16 16\" fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\">"
            . "<path d=\"M15.502 1.94a.5.5 0 010 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 01.707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 00-.121.196l-.805 2.414a.25.25 0 00.316.316l2.414-.805a.5.5 0 00.196-.12l6.813-6.814z\"/>"
            . "<path fill-rule=\"evenodd\" d=\"M1 13.5A1.5 1.5 0 002.5 15h11a1.5 1.5 0 001.5-1.5v-6a.5.5 0 00-1 0v6a.5.5 0 01-.5.5h-11a.5.5 0 01-.5-.5v-11a.5.5 0 01.5-.5H9a.5.5 0 000-1H2.5A1.5 1.5 0 001 2.5v11z\" clip-rule=\"evenodd\"/>"
            . "</svg>"
            . "</button>";

        unset($value['utl_idexterne']);
    }

    return $variable;
}

function setDbConfig($arr)
{
    $config['hostname']      = $arr['soc_hostbase'];
    $config['username']      = $arr['soc_userbase'];
    $config['password']      = $arr['soc_passbase'];
    $config['database']      = $arr['soc_namebase'];
    $config['dbdriver']      = 'mysqli';
    $config['dbprefix']      = '';
    $config['pconnect']      = FALSE;
    $config['db_debug']      = FALSE;
    $config['cache_on']      = FALSE;
    $config['cachedir']      = '';
    $config['char_set']      = 'utf8';
    $config['dbcollat']      = 'utf8_general_ci';
    $config['swap_pre']      = '';
    $config['encrypt']       = FALSE;
    $config['compress']      = FALSE;
    $config['stricton']      = FALSE;
    $config['failover']      = array();
    $config['save_queries']  = TRUE;

    return $config;
}

function generatePassword()
{
    $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($permitted_chars), 0, 8);
}

function generateLogin()
{
    $permitted_chars = 'abcdefghijklmnopqrstuv';
    return substr(str_shuffle($permitted_chars), 0, 4);
}
