<?php
interface IParam {
    const TABLE = "tr_param";

    const FIELD_code = "prm_code";
    const FIELD_value = "prm_value";

    const DEVICE_LIMIT = "DEVICE_LIMIT";

    const QUERY_SELECT_DEVICE_LIMIT = " SELECT  " . self::FIELD_value . " FROM " . self::TABLE . " WHERE " . self::FIELD_code . " = '" . self::DEVICE_LIMIT . "' ";
}
