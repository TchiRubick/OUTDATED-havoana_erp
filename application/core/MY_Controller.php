<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Promise;

class MY_Controller extends CI_Controller
{

    protected $_PARAM = [];

    protected $_objPrd;
    protected $_objDvc;
    protected $_objPrm;
    protected $_objUtl;
    protected $_objTra;
    protected $_objMag;
    protected $_objVnt;

    protected function views()
    {
        $this->load->view('main', $this->_PARAM);
    }

    protected function xhrResponse($data, $message = "")
    {
        $response = array(
            'success'   => false,
            'data'      => [],
            'message'   => $message
        );

        if (is_array($data)) {
            $response['success']    = true;
            $response['data']       = $data;
        }

        exit(json_encode($response));
    }

    protected function _loadClientStock()
    {
        if (!$this->_objPrd) {
            if (!$this->dbswitcher->setSwitcher($this->session->userdata("IDS"))) {
                log_message("ERROR", "Stock: _loadClient() => Session lost or bug in switch db");
                return redirect('Home/auth');
            }

            $this->load->model('produit_model', '');
            $this->_objPrd = $this->produit_model;
            $this->_objPrd->conn($this->dbswitcher->conn);

            $this->load->model('transac_model', '');
            $this->_objTra = $this->transac_model;
            $this->_objTra->conn($this->dbswitcher->conn);

            $this->load->model('magasin_model', '');
            $this->_objMag = $this->magasin_model;
            $this->_objMag->conn($this->dbswitcher->conn);

            $this->load->model('param_model', '');
            $this->_objPrm = $this->param_model;
            $this->_objPrm->conn($this->dbswitcher->conn);
        }
    }

    protected function _loadClientVente()
    {
        if (!$this->_objVnt) {
            if (!$this->dbswitcher->setSwitcher($this->session->userdata("IDS"))) {
                log_message("ERROR", "Vente: _loadClient() => Session lost or bug in switch db");
                return redirect('Home/auth');
            }

            $this->load->model('vente_model', '');
            $this->_objVnt = $this->vente_model;
            $this->_objVnt->conn($this->dbswitcher->conn);

            $this->load->model('magasin_model', '');
            $this->_objMag = $this->magasin_model;
            $this->_objMag->conn($this->dbswitcher->conn);

            $this->load->model('produit_model', '');
            $this->_objPrd = $this->produit_model;
            $this->_objPrd->conn($this->dbswitcher->conn);
        }
    }

    protected function _loadClientParametre()
    {
        if (!$this->_objDvc) {
            if (!$this->dbswitcher->setSwitcher($this->session->userdata("IDS"))) {
                log_message("ERROR", "Device: _loadClient() => Session lost or bug in switch db");
                return redirect('Home/auth');
            }

            $this->load->model('device_model', '');
            $this->_objDvc = $this->device_model;
            $this->_objDvc->conn($this->dbswitcher->conn);

            $this->load->model('param_model', '');
            $this->_objPrm = $this->param_model;
            $this->_objPrm->conn($this->dbswitcher->conn);

            $this->load->model('utilisateur_model', '');
            $this->_objUtl = $this->utilisateur_model;
            $this->_objUtl->conn($this->dbswitcher->conn);

            $this->load->model('transac_model', '');
            $this->_objTra = $this->transac_model;
            $this->_objTra->conn($this->dbswitcher->conn);

            $this->load->model('magasin_model', '');
            $this->_objMag = $this->magasin_model;
            $this->_objMag->conn($this->dbswitcher->conn);
        }
    }

    protected function asyncPostCurl($url, $body = array())
    {
        $client = new Client(['base_uri' => WS_HOST]);
        $client->request('POST', $url, ['form_params' => $body, 'headers' => ['X-Token' => API_KEY]]);
    }

    public function deconnect()
    {
        $this->session->sess_destroy();
        return redirect('Home');
    }

    public function transacAjout($idu, $statut = 'SUC')
    {
        $date = date('Y-m-d H:i:s');
        $uuid = gen_uuid();
        $argument = [$uuid, 'ADD', $statut, 0, 'BO', $idu, null, $date, $date];
        $this->_objTra->insertTransac($argument);
        return $uuid;
    }

    public function transacConfiguration($idu, $statut = 'SUC')
    {
        $date = date('Y-m-d H:i:s');
        $uuid = gen_uuid();
        $argument = [$uuid, 'CON', $statut, 0, 'BO', $idu, null, $date, $date];
        $this->_objTra->insertTransac($argument);
        return $uuid;
    }

    public function transacModification($idu, $statut = 'SUC')
    {
        $date = date('Y-m-d H:i:s');
        $uuid = gen_uuid();
        $argument = [$uuid, 'EDI', $statut, 0, 'BO', $idu, null, $date, $date];
        $this->_objTra->insertTransac($argument);
        return $uuid;
    }

    public function transacSuppression($idu, $statut = 'SUC')
    {
        $date = date('Y-m-d H:i:s');
        $uuid = gen_uuid();
        $argument = [$uuid, 'DEL', $statut, 0, 'BO', $idu, null, $date, $date];
        $this->_objTra->insertTransac($argument);
        return $uuid;
    }

    public function transacEnvoie($idu, $statut = 'SUC', $action = "ENV")
    {
        $date = date('Y-m-d H:i:s');
        $uuid = gen_uuid();
        $argument = [$uuid, $action, 'BO', 0, $statut, $idu, null, $date, $date];
        $this->_objTra->insertTransac($argument);
        return $uuid;
    }

    public function checkAutorisation()
    {
        $idu = $this->session->userdata("ID");
        $role = $this->_objUtl->getRoleByIduAdmin($idu);

        if(empty($role)) {
            return redirect('gateway/not_authorized');
        }
    }
}
