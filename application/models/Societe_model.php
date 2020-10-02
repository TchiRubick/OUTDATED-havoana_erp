<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . "/interface/Societe_interface.php";

class Societe_model extends CI_Model implements ISociete
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
    }

    public function getSocieteIdByCode($code)
    {
        $result = [];

        try {
            $query = $this->db->query(ISociete::QUERY_SOCIETE_ID_BY_CODE, array($code));

            if(!$query){
                throw new Exception();
            } else {
                $result = $query->row_array();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Societe_model: getSocieteIdByCode() : ' . ISociete::QUERY_SOCIETE_ID_BY_CODE . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function getInfoConnexion($ide)
    {
        $result = [];

        try {
            $query = $this->db->query(ISociete::QUERY_SOCIETE_INFO_CONN, array($ide));

            if(!$query){
                throw new Exception();
            } else {
                $result = $query->row_array();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Societe_model: getInfoConnexion() : ' . ISociete::QUERY_SOCIETE_INFO_CONN . ' ' . $e->getMessage());
        }

        return $result;
    }
}
