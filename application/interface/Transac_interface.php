<?php
interface ITransac {

    const TABLE = "t_transac";

    const FIELD_idinterne = "tsc_idinterne";
    const FIELD_idexterne = "tsc_idexterne";
    const FIELD_action = "tsc_action";
    const FIELD_statut = "tsc_statut";
    const FIELD_montant = "tsc_montant";
    const FIELD_origin = "tsc_origin";
    const FIELD_user = "tsc_user";
    const FIELD_device = "tsc_device";
    const FIELD_datecrea = "tsc_datecrea";
    const FIELD_datemodif = "tsc_datemodif";

    const QUERY_INSERT_TRANSAC = " INSERT INTO " . self::TABLE . " ( "
        . self::FIELD_idexterne . ", "
        . self::FIELD_action . ", "
        . self::FIELD_statut . ", "
        . self::FIELD_montant . ", "
        . self::FIELD_origin . ", "
        . self::FIELD_user . ", "
        . self::FIELD_device . ", "
        . self::FIELD_datecrea . ", "
        . self::FIELD_datemodif . " "
        . " ) VALUES ( ? , ? , ? , ? , ? , ? , ? , ? , ? )";
}
