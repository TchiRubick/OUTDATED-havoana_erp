<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produit_model extends CI_Model
{

    private $_dateNow;
    private $_db;

    public function __construct()
    {
        $this->_initialise();
    }

    public function conn($config)
    {
        $this->_db = $this->load->database($config, TRUE);
    }

    private function _initialise()
    {
        $this->_dateNow = date('Y-m-d H:i:s');
    }

    public function insert($request, $mag)
    {
        $result = TRUE;

        $param   = [];
        $param[] = gen_uuid();
        $param[] = $request['nom'];
        $param[] = $request['codebarre'];
        $param[] = $request['prixa'];
        $param[] = $this->_dateNow;
        $param[] = $this->_dateNow;
        $param[] = $request['prixa'];
        $param[] = $this->_dateNow;

        try {
            $requete = " INSERT INTO t_produit (prd_idexterne, prd_nom, prd_codebarre, prd_prixachat ,prd_datecrea, prd_datemodif) "
                . " VALUES ( ? , ? , ? , ? , ? , ? ) "
                . " ON DUPLICATE KEY UPDATE "
                . " prd_prixachat = ? , prd_datemodif = ?";

            if (!$this->_db->query($requete, $param)) {
                throw new Exception("Insertion produit impossible");
            }

            $requeteGet = "SELECT prd_idexterne FROM t_produit WHERE prd_codebarre = ?";

            $queryGet = $this->_db->query($requeteGet, [$request['codebarre']]);

            if (!$queryGet) {
                throw new Exception("Récuperation dernier produit mise à jour impossible");
            }

            $prd_id = $queryGet->row_array();

            if (!$this->createOrUpdateMagStock($prd_id['prd_idexterne'], $mag, $request['prixv'], $request['quantite'])) {
                throw new Exception();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Produit_model: insert() : ' . $requete . ' ' . $e->getMessage());
            $result = FALSE;
        }

        return $result;
    }

    public function produitOption()
    {
        $result = [];

        try {
            $requete = " SELECT prd_nom as libelle, prd_codebarre as value FROM t_produit"
                . " ORDER BY prd_nom ASC";

            $query = $this->_db->query($requete);

            if (!$query) {
                throw new Exception();
            } else {
                $result = $query->result_array();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Produit_model: produitOption() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function getProduitByCb($codebarre)
    {
        $result = [];

        try {
            $requete = " SELECT prd_idexterne, prd_nom, prd_codebarre, prd_prixachat, magst_prix FROM t_produit "
                . " INNER JOIN tr_magstock ON prd_idexterne = magst_prdid"
                . " WHERE prd_codebarre = ?";

            $query = $this->_db->query($requete, [$codebarre]);

            if (!$query) {
                throw new Exception();
            } else {
                $result = $query->row_array();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Produit_model: getProduitByCb() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function getProduitByCbMag($codebarre, $mag)
    {
        $result = [];

        try {
            $requete = " SELECT magst_id, magst_quantite, magst_prix, prd_nom, prd_codebarre FROM t_produit "
                . " INNER JOIN tr_magstock ON prd_idexterne = magst_prdid"
                . " WHERE prd_codebarre = ? AND magst_mag = ?";

            $query = $this->_db->query($requete, [$codebarre, $mag]);

            if (!$query) {
                throw new Exception();
            } else {
                $result = $query->row_array();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Produit_model: getProduitByCb() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function getAllProduit()
    {
        $result = [];

        try {
            $requete = " SELECT prd_codebarre, prd_nom, mag_nom, magst_quantite, prd_prixachat, magst_prix, magst_id, mag_type "
                . " FROM sys_magasin "
                . " INNER JOIN tr_magstock ON mag_code = magst_mag "
                . " INNER JOIN t_produit ON prd_idexterne = magst_prdid"
                . " ORDER BY prd_nom ASC";

            $query = $this->_db->query($requete);

            if (!$query) {
                throw new Exception();
            } else {
                $result = $query->result_array();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Produit_model: getAllProduit() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function allStockMagasin($magcode)
    {
        $result = [];

        try {
            $requete = " SELECT prd_codebarre, prd_nom, mag_nom, magst_quantite, prd_prixachat, magst_prix, magst_id, mag_type "
                . " FROM sys_magasin "
                . " INNER JOIN tr_magstock ON mag_code = magst_mag "
                . " INNER JOIN t_produit ON prd_idexterne = magst_prdid"
                . " WHERE magst_mag = ? "
                . " ORDER BY prd_nom ASC";

            $query = $this->_db->query($requete, [$magcode]);

            if (!$query) {
                throw new Exception();
            } else {
                $result = $query->result_array();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Produit_model: allStockMagasin() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function isExistProduit($id2)
    {
        $result = false;

        try {
            $requete = " SELECT prd_idexterne FROM t_produit WHERE prd_idexterne = ?";
            $query = $this->_db->query($requete, [$id2]);

            if (!$query) {
                throw new Exception();
            } else {
                $row = $query->row_array();

                if (count($row) > 0) {
                    $result = true;
                }
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Produit_model: isExistProduit() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function isExistCodebarre($cb, $id2)
    {
        $result = false;

        try {
            $requete = " SELECT prd_idexterne FROM t_produit WHERE prd_codebarre = ? AND prd_idexterne = ?";

            $query = $this->_db->query($requete, [$cb, $id2]);

            if (!$query) {
                throw new Exception();
            } else {
                $row = $query->result_array();

                if (count($row) > 0) {
                    $result = true;
                }
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Produit_model: isExistCodebarre() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function updateProduit($codebarre, $newname, $newprix, $id, $idmag)
    {
        $result = true;

        try {
            $requete = " UPDATE t_produit INNER JOIN tr_magstock ON magst_prdid = prd_idexterne "
                . " SET prd_codebarre = ?, prd_nom = ?, prd_datemodif = NOW(), "
                . " magst_prix = ? WHERE magst_id = ? AND prd_idexterne = ? ";

            $query = $this->_db->query($requete, [$codebarre, $newname, $newprix, $idmag, $id]);

            if (!$query) {
                throw new Exception();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Produit_model: updateProduit() : ' . $requete . ' ' . $e->getMessage());
            $result = false;
        }

        return $result;
    }

    public function deleteByCodebarre($codebarre, $idmag)
    {
        $result = true;

        try {
            $requete = " DELETE t_produit, tr_magstock FROM t_produit INNER JOIN tr_magstock ON prd_idexterne = magst_prdid "
                . " WHERE prd_codebarre = ? AND magst_id = ?";

            $query = $this->_db->query($requete, [$codebarre, $idmag]);

            if (!$query) {
                throw new Exception();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Produit_model: deleteByCodebarre() : ' . $requete . ' ' . $e->getMessage());
            $result = false;
        }

        return $result;
    }

    public function setAnomalie($produitInfo)
    {
        $result = true;

        try {
            $requete = " INSERT INTO t_vente (vnt_mag, vnt_userid, vnt_date, vnt_type, vnt_quantite, vnt_prdid) VALUES ( ? , ? , ? , ? , ? , ?)";

            $param = [];
            $param[] = $produitInfo['magst_mag'];
            $param[] = $this->session->userdata("ID");
            $param[] = date("Y-m-d H:i:s");
            $param[] = "ANO";
            $param[] = $produitInfo['magst_quantite'];
            $param[] = $produitInfo['magst_prdid'];

            $query = $this->_db->query($requete, $param);

            if (!$query) {
                throw new Exception();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Produit_model: setAnomalie() : ' . $requete . ' ' . $e->getMessage());
            $result = false;
        }

        return $result;
    }

    public function createOrUpdateMagStock($idprd, $mag, $prix, $quantite)
    {
        $result = FALSE;
        try {
            $param = [];
            $param[] = $idprd;
            $param[] = $mag;
            $param[] = $prix;
            $param[] = $quantite;
            $param[] = $quantite;
            $param[] = $prix;

            $requete = " INSERT INTO tr_magstock (magst_prdid, magst_mag, magst_prix, magst_quantite, magst_datecrea, magst_datemodif) "
                . " VALUES ( ? , ? , ? , ?, NOW(), NOW()) "
                . " ON DUPLICATE KEY UPDATE "
                . " magst_quantite = magst_quantite + ? , magst_prix = ?, magst_datemodif = NOW()";

            if (!$this->_db->query($requete, $param)) {
                throw new Exception();
            }

            $result = true;
        } catch (\Exception $e) {
            log_message('ERROR', 'Produit_model: createOrUpdateMagStock() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function getPrdIdByCode($codebarre)
    {
        $result = [];

        try {
            $requete = " SELECT prd_idexterne FROM t_produit WHERE prd_codebarre = ? ";

            $query = $this->_db->query($requete, [$codebarre]);

            if (!$query) {
                throw new Exception();
            }

            $result = $query->row_array();
        } catch (\Exception $e) {
            log_message('ERROR', 'Produit_model: getPrdIdByCode() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function getProduitIdQttByCbMag($codebarre, $codemag)
    {
        $result = [];

        try {
            $requete = " SELECT magst_prdid, magst_quantite, magst_mag FROM tr_magstock "
                    . " INNER JOIN t_produit ON magst_prdid = prd_idexterne "
                    . " WHERE prd_codebarre = ? AND magst_id = ?";

            $query = $this->_db->query($requete, [$codebarre, $codemag]);

            if (!$query) {
                throw new Exception();
            }

            $result = $query->row_array();
        } catch (\Exception $e) {
            log_message('ERROR', 'Produit_model: getProduitIdQttByCbMag() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function isEnougthQuantity($id_mag, $quantite)
    {
        $result = false;

        try {
            $requete = " SELECT magst_quantite FROM tr_magstock WHERE magst_id = ? AND magst_quantite >= ? ";

            $query = $this->_db->query($requete, [$id_mag, $quantite]);

            if (!$query) {
                throw new Exception();
            }

            $num = $query->num_rows();

            if ($num > 0) {
                $result = true;
            }
        } catch (\Exception $e) {
            log_message('ERROR', 'Produit_model: isEnougthQuantity() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function decrementMagArticle($idmag, $quantite)
    {
        $result = false;

        try {
            $requete = " UPDATE tr_magstock SET magst_quantite = magst_quantite - ? WHERE magst_id = ? ";

            $query = $this->_db->query($requete, [$quantite, $idmag]);

            if (!$query) {
                throw new Exception();
            }

            $result = true;
        } catch (\Exception $e) {
            log_message('ERROR', 'Produit_model: decrementMagArticle() : ' . $requete . ' ' . $e->getMessage());
        }

        return $result;
    }
}
