<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DbSwitcher
{
    protected $CI;
    private $_objSoc;
    public $ide;
    public $conn;
    public $codeSoc;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('societe_model', '', TRUE);
        $this->_objSoc = $this->CI->societe_model;
    }

    public function getIdeBySocCode($code)
    {
        $res = $this->_objSoc->getSocieteIdByCode($code);

        if (empty($res)) {
            return FALSE;
        }

        $this->ide = $res['soc_idexterne'];
        $this->codeSoc = $code;

        if(!$this->switcher()) {
            return FALSE;
        }

        return TRUE;
    }

    public function switcher()
    {
        $conn = $this->_objSoc->getInfoConnexion($this->ide);

        if (count($conn) < 1) return FALSE;

        $this->conn = setDbConfig($conn);

        return TRUE;
    }

    public function setSwitcher($ide)
    {
        $this->ide = $ide;

        return $this->switcher();
    }
}
