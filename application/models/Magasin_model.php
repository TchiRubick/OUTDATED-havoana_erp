<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Magasin_model extends CI_Model
{
    private $_db;

    public function __construct()
    {}

    public function conn($config)
    {
        $this->_db = $this->load->database($config, TRUE);
    }

    public function getMagOptionNotStock()
    {
        $result = [];

        try {
            $requete = " SELECT mag_code AS value, mag_nom AS libelle FROM sys_magasin WHERE mag_type = 'VNT' ";

            $query = $this->_db->query($requete);

            if (!$query) {
                throw new Exception("Récuperation de la liste des magasin de vente impossible");
            }

            $result = $query->result_array();
        } catch (\Exception $e) {
            log_message('ERROR', 'Magasin_model: getMagOptionNotStock() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function getMagOption()
    {
        $result = [];

        try {
            $requete = " SELECT mag_code AS value, mag_nom AS libelle FROM sys_magasin ";

            $query = $this->_db->query($requete);

            if (!$query) {
                throw new Exception("Récuperation de la liste des magasin de vente impossible");
            }

            $result = $query->result_array();
        } catch (\Exception $e) {
            log_message('ERROR', 'Magasin_model: getMagOption() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function getMagIdByCode($magcode)
    {
        $result = [];

        try {
            $requete = " SELECT mag_id FROM sys_magasin WHERE mag_code = ?";

            $query = $this->_db->query($requete, [$magcode]);

            if (!$query) {
                throw new Exception("Nouveau magasin introuvable");
            }

            $result = $query->row_array();
        } catch (\Exception $e) {
            log_message('ERROR', 'Magasin_model: getMagOptionNotStock() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }
}
