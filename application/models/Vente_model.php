<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vente_model extends CI_Model
{
    private $_db;

    public function __construct()
    {}

    public function conn($config)
    {
        $this->_db = $this->load->database($config, TRUE);
    }

    public function allVente()
    {
        $result = [];

        try {
            $requete = " SELECT mag_nom, IFNULL(dvc_nom, '<span style=\"color:red;\">Caisse inéxistant</span>'), utl_email, utl_login, vnt_date, vnt_prix, vnt_quantite, prd_nom "
                . " FROM t_vente"
                . " INNER JOIN sys_magasin ON vnt_mag = mag_code "
                . " left JOIN t_devices ON vnt_caisse = dvc_idexterne "
                . " INNER JOIN sys_utilisateur ON vnt_userid = utl_idexterne "
                . " INNER JOIN t_produit ON vnt_prdid = prd_idexterne "
                . " ORDER BY vnt_date DESC "
                . " LIMIT 100";

            $query = $this->_db->query($requete);

            if (!$query) {
                throw new Exception("Récuperation de la liste des Ventes impossible");
            }

            $result = $query->result_array();
        } catch (\Exception $e) {
            log_message('ERROR', 'Vente_model: allVente() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function allVenteMagasin($magcode)
    {
        $result = [];

        try {
            $requete = " SELECT mag_nom, IFNULL(dvc_nom, '<span style=\"color:red;\">Caisse inéxistant</span>'), utl_email, utl_login, vnt_date, vnt_prix, vnt_quantite, prd_nom "
                . " FROM t_vente"
                . " INNER JOIN sys_magasin ON vnt_mag = mag_code "
                . " INNER JOIN sys_utilisateur ON vnt_userid = utl_idexterne "
                . " INNER JOIN t_produit ON vnt_prdid = prd_idexterne "
                . " LEFT JOIN t_devices ON vnt_caisse = dvc_idexterne "
                . " WHERE mag_code = ? "
                . " ORDER BY vnt_date DESC "
                . " LIMIT 100";

            $query = $this->_db->query($requete, [$magcode]);

            if (!$query) {
                throw new Exception("Récuperation de la liste des Ventes impossible");
            }

            $result = $query->result_array();
        } catch (\Exception $e) {
            log_message('ERROR', 'Vente_model: allVenteMagasin() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function getdataChartVenteProduit($codebarre, $magasin, $year, $month)
    {
        $result = [];
        $param = [$codebarre, $year, $month];

        try {

            $requete = " SELECT SUM(vnt_quantite) as quantite FROM t_vente INNER JOIN t_produit ON prd_idexterne = vnt_prdid WHERE prd_codebarre = ?  AND YEAR(vnt_date) = ? AND MONTH(vnt_date) = ? ";

            if ($magasin != 0) {
                $requete .= " AND vnt_mag = ? ";
                $param[] = $magasin;
            }

            $requete .= " GROUP BY prd_idexterne";

            $query = $this->_db->query($requete, $param);

            if (!$query) {
                throw new Exception("Récuperation de la liste des Ventes impossible");
            }

            $result = $query->row_array();

        } catch (\Exception $e) {
            log_message('ERROR', 'Vente_model: getdataChartVenteProduit() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function getdataChartVente($magasin, $year, $month)
    {
        $result = [];
        $param = [$year, $month];

        try {

            $requete = " SELECT SUM(vnt_prix) as montant FROM t_vente WHERE YEAR(vnt_date) = ? AND MONTH(vnt_date) = ? ";

            if ($magasin != 0) {
                $requete .= " AND vnt_mag = ? ";
                $param[] = $magasin;
            }

            $requete .= " GROUP BY YEAR(vnt_date), MONTH(vnt_date)";

            $query = $this->_db->query($requete, $param);

            if (!$query) {
                throw new Exception("Récuperation de la liste des Ventes impossible");
            }

            $result = $query->row_array();

        } catch (\Exception $e) {
            log_message('ERROR', 'Vente_model: getdataChartVente() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }
}
