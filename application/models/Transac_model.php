<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . "/interface/Transac_interface.php";

class Transac_model extends CI_Model implements ITransac
{
    private $_db;
    public $_errorMessage = "";

    public function conn($config)
    {
        $this->_db = $this->load->database($config, TRUE);
    }

    public function insertTransac($arg)
    {
        $result = true;

        try {
            $query = $this->_db->query(ITransac::QUERY_INSERT_TRANSAC, $arg);

            if (!$query) {
                throw new Exception();
            }
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }
}
