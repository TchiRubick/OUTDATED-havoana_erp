<?php

interface ISociete
{
    const FIELD_idinterne = "soc_idinterne";
    const FIELD_idexterne = "soc_idexterne";
    const FIELD_nom = "soc_nom";
    const FIELD_code = "soc_code";
    const FIELD_user_idinterne = "soc_user_idinterne";
    const FIELD_namebase = "soc_namebase";
    const FIELD_hostbase = "soc_hostbase";
    const FIELD_userbase = "soc_userbase";
    const FIELD_passbase = "soc_passbase";
    const FIELD_portbase = "soc_portbase";
    const FIELD_status = "soc_status";
    const FIELD_datecrea = "soc_datecrea";
    const FIELD_datemodif = "soc_datemodif";

    const TABLE = "t_societe";

    const QUERY_SOCIETE_ID_BY_CODE = " SELECT " . self::FIELD_idexterne . " FROM " . self::TABLE . " WHERE " . self::FIELD_code . " = ?";

    const QUERY_SOCIETE_INFO_CONN = " SELECT "
        . self::FIELD_namebase . ", "
        . self::FIELD_hostbase . ", "
        . self::FIELD_userbase . ", "
        . self::FIELD_passbase . ", "
        . self::FIELD_portbase . " "
        . " FROM " . self::TABLE . " "
        . " WHERE " . self::FIELD_idexterne . " = ?";
}
