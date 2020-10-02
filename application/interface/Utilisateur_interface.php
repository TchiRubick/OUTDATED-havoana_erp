<?php

interface IUtilisateur
{

    const TABLE         = "sys_utilisateur";
    const TABLE_ROLE    = "sys_role";

    const FIELD_idinterne   = "utl_idinterne";
    const FIELD_idexterne   = "utl_idexterne";
    const FIELD_login       = "utl_login";
    const FIELD_email       = "utl_email";
    const FIELD_password    = "utl_password";
    const FIELD_status      = "utl_status";
    const FIELD_role        = "utl_role";
    const FIELD_datecrea    = "utl_datecrea";
    const FIELD_datemodif   = "utl_datemodif";

    const ROLE_code     = "rl_code";
    const ROLE_ide      = "rl_idexterne";
    const ROLE_libelle  = "rl_libelle";

    const ACAISSE = "AGC";

    const QUERY_SELECT_IDE_BY_LOGIN_PASS = " SELECT " . self::FIELD_idexterne . " FROM " . self::TABLE . " "
        . " INNER JOIN " . self::TABLE_ROLE . " ON " . self::ROLE_ide . " = " . self::FIELD_role
        . " WHERE " . self::FIELD_login . " = ? AND " . self::FIELD_password . " = ? AND " . self::FIELD_status . " = 1 AND " . self::ROLE_code . "<> 'AGC'";

    const QUERY_SELECT_ALL_ACAISSE = " SELECT " . " "
        . self::FIELD_idexterne . " AS value, "
        . self::FIELD_login . " AS libelle "
        . " FROM " . self::TABLE . " "
        . " INNER JOIN " . self::TABLE_ROLE . " ON " . self::ROLE_code . " = '" . self::ACAISSE . "' AND " . self::ROLE_ide . " = " . self::FIELD_role;

    const QUERY_SELECT_ALL_NOT_SUP = " SELECT " . self::FIELD_idexterne . ", " . self::FIELD_login . ", " . self::FIELD_email . ", " . self::ROLE_libelle . ", " . self::FIELD_status . " "
        . " FROM " . self::TABLE . " "
        . " INNER JOIN " . self::TABLE_ROLE . " ON " . self::FIELD_role . " = " . self::ROLE_ide . " "
        . " WHERE " . self::ROLE_code . " <> 'SUP'";

    const QUERY_SELECT_STATUT_NOT_ADMIN = " SELECT " . self::FIELD_status . " FROM " . self::TABLE . " "
        . " INNER JOIN " . self::TABLE_ROLE . " ON " . self::FIELD_role . " = " . self::ROLE_ide . " "
        . " WHERE (" . self::ROLE_code . " <> 'SUP' AND " . self::ROLE_code . " <> 'ADMIN' ) AND " .  self::FIELD_idexterne . " = ? AND " . self::FIELD_email . " IS NOT NULL AND " . self::FIELD_email . " <> '' ";

    const QUERY_UPDATE_STATUS_BY_IDE = " UPDATE " . self::TABLE . " SET " . self::FIELD_status . " = ? , " . self::FIELD_datemodif . " = NOW() WHERE " . self::FIELD_idexterne . " = ? ";

    const QUERY_SELECT_ADMIN_BY_PASS = " SELECT " . self::FIELD_idexterne . " , " . self::FIELD_login . " FROM " . self::TABLE . " "
    . " INNER JOIN " . self::TABLE_ROLE . " ON " . self::FIELD_role . " = " . self::ROLE_ide . " "
    . " WHERE " . self::FIELD_password . " = ? AND ( " . self::ROLE_code . " = 'ADMIN' OR " . self::ROLE_code . " = 'SUP' ) ";

    const QUERY_UPDATE_MAIL_BY_IDU = " UPDATE " . self::TABLE . " SET " . self::FIELD_email . " = ? , " . self::FIELD_datemodif . " = NOW() WHERE " . self::FIELD_idexterne . " = ? ";
    const QUERY_UPDATE_MAIL_PASS_BY_IDU = " UPDATE " . self::TABLE . " SET "
        . self::FIELD_email . " = ? , "
        . self::FIELD_password . " = ? , "
        . self::FIELD_login . " = ? , "
        . self::FIELD_datemodif . " = NOW() "
        . " WHERE " . self::FIELD_idexterne . " = ? ";

    const QUERY_SELECT_ROLE_BY_IDU = " SELECT " . self::ROLE_code . " FROM " . self::TABLE . " "
        . " INNER JOIN " . self::TABLE_ROLE . " ON " . self::FIELD_role . " = " . self::ROLE_ide . " "
        . " WHERE " . self::FIELD_idexterne . " = ? LIMIT 1";

        const QUERY_SELECT_ROLE_BY_IDU_ADMIN = " SELECT " . self::ROLE_code . " FROM " . self::TABLE . " "
        . " INNER JOIN " . self::TABLE_ROLE . " ON " . self::FIELD_role . " = " . self::ROLE_ide . " "
        . " WHERE " . self::FIELD_idexterne . " = ? AND (" . self::ROLE_code . " = 'SUP' OR " . self::ROLE_code . " = 'ADMIN' ) LIMIT 1";
}
