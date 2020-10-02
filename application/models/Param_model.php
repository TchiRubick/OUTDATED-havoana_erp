<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . "/interface/Param_interface.php";

class Param_model extends CI_Model implements IParam
{
    private $_db;

    public function conn($config)
    {
        $this->_db = $this->load->database($config, TRUE);
    }

    public function getDeviceLimit()
    {
        $result = 0;

        try {
            $query = $this->_db->query(IParam::QUERY_SELECT_DEVICE_LIMIT);

            if (!$query) {
                throw new Exception();
            } else {
                $temp = $query->result_array();

                $result = $temp[0]['prm_value'];
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Param_model: getDeviceLimit() : ' . IParam::QUERY_SELECT_DEVICE_LIMIT . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function getParam($code)
    {
        $result = [];

        $requete = " SELECT prm_value FROM tr_param WHERE prm_code = ? ";

        try {
            $query = $this->_db->query($requete, [$code]);

            if (!$query) {
                throw new Exception();
            }

            $temp = $query->result_array();

            $result = $temp[0]['prm_value'];
        } catch (\Exception $e) {
            log_message('ERROR', 'Param_model: getParam() : ' . IParam::QUERY_SELECT_DEVICE_LIMIT . ' ' . $e->getMessage());
        }

        return $result;
    }
}
