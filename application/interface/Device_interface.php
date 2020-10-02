<?php
interface IDevice {
    const TABLE = "t_devices";
    const TABLE_LEXIQUE = "sys_lexique";
    const TABLE_DVCUSER = "tr_device_user";

    const FIELD_idexterne   = "dvc_idexterne";
    const FIELD_detail      = "dvc_detail";
    const FIELD_statut      = "dvc_statut";
    const FIELD_transac     = "dvc_transac";
    const FIELD_nom         = "dvc_nom";
    const FIELD_datecrea    = "dvc_datecrea";
    const FIELD_datemodif   = "dvc_datemodif";

    const FIELD_lxq_code    = "lxq_code";
    const FIELD_lxq_libelle = "lxq_libelle";

    const FIELD_dvcu_device = "dvcu_device";
    const FIELD_dvcu_user   = "dvcu_user";

    const QUERY_SELECT_ALL_FOR_GRID = " SELECT "
        . self::FIELD_idexterne . ", "
        . self::FIELD_detail . ", "
        . self::FIELD_nom . ", "
        . self::FIELD_lxq_libelle . ", "
        . self::FIELD_statut
        . " FROM " . self::TABLE . " "
        . " INNER JOIN " . self::TABLE_LEXIQUE . " ON " . self::FIELD_statut . " = " . self::FIELD_lxq_code . " "
        . " ORDER BY " . self::FIELD_datecrea . " ASC";

    const QUERY_GET_STATUT_BY_IDE = " SELECT " . self::FIELD_statut . " FROM " . self::TABLE . " WHERE " . self::FIELD_idexterne . " = ? LIMIT 1";

    const QUERY_UPDATE_STATE_BY_IDE = " UPDATE " . self::TABLE . " SET " . self::FIELD_statut . " = ? , " . self::FIELD_datemodif . " = NOW() WHERE " . self::FIELD_idexterne . " = ? ";

    const QUERY_COUNT_DEVICE_BY_STATE = " SELECT COUNT(" . self::FIELD_idexterne . ") AS nombre FROM " . self::TABLE . " WHERE " . self::FIELD_statut . " = ? " ;

    const QUERY_UPDATE_NOM_BY_IDE = " UPDATE " . self::TABLE . " SET " . self::FIELD_nom . " = ? , " . self::FIELD_datemodif . " = NOW() WHERE " . self::FIELD_idexterne . " = ? ";

    const QUERY_SELECT_USER_BY_IDE = " SELECT " . self::FIELD_dvcu_user . " FROM " . self::TABLE_DVCUSER . " WHERE " . self::FIELD_dvcu_device . " = ? ";

    const QUERY_INSERT_IGNORE_USER_IDE = " INSERT IGNORE INTO " . self::TABLE_DVCUSER . " (" . self::FIELD_dvcu_device . " , " . self::FIELD_dvcu_user . " ) VALUES( ? , ?) ";

    const QUERY_DEL_USER_DEVICE = " DELETE FROM " . self::TABLE_DVCUSER . " WHERE " . self::FIELD_dvcu_device . " = ? ";

    const QUERY_DEL_DEVICE_BY_IDE = " DELETE FROM " . self::TABLE . " WHERE " . self::FIELD_idexterne . " = ? ";
}
