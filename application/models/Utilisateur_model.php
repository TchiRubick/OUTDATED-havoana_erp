<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . "/interface/Utilisateur_interface.php";

class Utilisateur_model extends CI_Model implements IUtilisateur
{
    private $_db;
    public $_errorMessage;

    public function conn($config)
    {
        $this->_db = $this->load->database($config, TRUE);
    }

    public function getUtilisateurByLoginPass($param)
    {
        $result = [];

        $requete = " SELECT utl_idexterne FROM sys_utilisateur "
            . " INNER JOIN sys_role ON rl_idexterne = utl_role "
            . " WHERE utl_login = ? AND utl_password = ? AND utl_status = 1 AND rl_code <> 'AGC'";

        try {
            $query = $this->_db->query($requete, $param);

            if(!$query){
                throw new Exception();
            } else {
                $result = $query->row_array();
            }
        } catch (Exception $e) {
            log_message('ERROR', 'Utilisateur_model: getUtilisateurByLoginPass() : ' . $requete . ' {' . json_encode($param) . '} ' . $e->getMessage());
        }

        return $result;
    }

    public function getAllAgentCaisse()
    {
        $result = [];

        $requete = " SELECT utl_idexterne AS value, utl_login AS libelle FROM sys_utilisateur "
            . " INNER JOIN sys_role ON rl_code = 'AGC' AND rl_idexterne = utl_role ";

        try {
            $query = $this->_db->query($requete);

            if (!$query) throw new Exception();

            $result = $query->result_array();
        } catch (\Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Utilisateur_model: getAllAgentCaisse() : ' . $requete . ' {} ' . $e->getMessage());
        }

        return $result;
    }

    public function getAllAgentCaisseNotSup()
    {
        $result = [];

        $requete = " SELECT utl_idexterne AS value, utl_login AS libelle FROM sys_utilisateur "
            . " INNER JOIN sys_role ON rl_idexterne = utl_role "
            . " WHERE rl_code <> 'SUP'";

        try {
            $query = $this->_db->query($requete);

            if (!$query) throw new Exception();

            $result = $query->result_array();
        } catch (\Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Utilisateur_model: getAllAgentCaisseNotSup() : ' . $requete . ' {} ' . $e->getMessage());
        }

        return $result;
    }

    public function getAllUser()
    {
        $result = [];
        
        try {
            $query = $this->_db->query(IUtilisateur::QUERY_SELECT_ALL_NOT_SUP);

            if (!$query) throw new Exception();

            $result = $query->result_array();
        } catch (\Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Utilisateur_model: getAllUser() : ' . IUtilisateur::QUERY_SELECT_ALL_NOT_SUP . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function switchUser($ideUser)
    {
        $result = true;

        try {
            $query = $this->_db->query(IUtilisateur::QUERY_SELECT_STATUT_NOT_ADMIN, [$ideUser]);
            if (!$query) throw new Exception();

            $resUser = $query->row_array();

            if (!isset($resUser["utl_status"])) throw new Exception("Changement de statut non autorisé sur un compte Administrateur. Si ce n'est pas le cas, veuillez spécifier un adresse email");

            $newState = $resUser['utl_status'] === '0' ? 1 : 0;

            $queryUpdate = $this->_db->query(IUtilisateur::QUERY_UPDATE_STATUS_BY_IDE, [$newState, $ideUser]);

            if (!$queryUpdate) throw new Exception();
        } catch (\Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Utilisateur_model: switchUser() : ' . IUtilisateur::QUERY_SELECT_STATUT_NOT_ADMIN . ' ' . IUtilisateur::QUERY_UPDATE_STATUS_BY_IDE . ' ' . $e->getMessage());
            $result = false;
        }

        return $result;
    }

    public function checkIfReallyAdminByPass($password, $ID)
    {
        $result = array(
            "success" => true,
            "relog" => false
        );

        try {
            $query = $this->_db->query(IUtilisateur::QUERY_SELECT_ADMIN_BY_PASS, [$password]);
            if (!$query) throw new Exception();

            $temp = $query->row_array();
            if(!isset($temp['utl_idexterne'])) throw new Exception ("Mot de passe incorrect");

            if ($temp['utl_idexterne'] === $ID) $result["relog"] = true;


        } catch (\Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Utilisateur_model: checkIfReallyAdminByPass() : ' . IUtilisateur::QUERY_SELECT_ADMIN_BY_PASS . ' ' . $e->getMessage());
            $result["success"] = false;
        }

        return $result;
    }

    public function editUserInfo($email, $idu, $password, $login)
    {
        $result = true;

        try {
            $newPassword = md5($login . md5($password));
            $query = $this->_db->query(IUtilisateur::QUERY_UPDATE_MAIL_PASS_BY_IDU, [$email, $newPassword, $login, $idu]);

            if (!$query) throw new Exception();
        } catch (\Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Utilisateur_model: checkIfReallyAdminByPass() : ' . $password != null ?  IUtilisateur::QUERY_UPDATE_MAIL_PASS_BY_IDU : IUtilisateur::QUERY_UPDATE_MAIL_BY_IDU . ' ' . $e->getMessage());
            $result = false;
        }

        return $result;
    }

    public function getRoleByIdu($idu)
    {
        $result = [];

        try {
            $query = $this->_db->query(IUtilisateur::QUERY_SELECT_ROLE_BY_IDU, [$idu]);

            if (!$query) throw new Exception();

            $result = $query->row_array();
        } catch (\Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Utilisateur_model: getRoleByIdu() : ' . IUtilisateur::QUERY_SELECT_ROLE_BY_IDU . ' ' . $e->getMessage());
        }

        return $result;
    }

    public function getRoleByIduAdmin($idu)
    {
        $result = [];

        try {
            $query = $this->_db->query(IUtilisateur::QUERY_SELECT_ROLE_BY_IDU_ADMIN, [$idu]);

            if (!$query) throw new Exception();

            $result = $query->row_array();
        } catch (\Exception $e) {
            $this->_errorMessage = $e->getMessage();
            log_message('ERROR', 'Utilisateur_model: getRoleByIduAdmin() : ' . IUtilisateur::QUERY_SELECT_ROLE_BY_IDU_ADMIN . ' ' . $e->getMessage());
        }

        return $result;
    }
}
