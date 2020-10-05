<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public static $AUTH_ERR_MESSAGE = "Vous n'êtes pas reconnue par notre platforme";

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        unset($_SESSION);
        $this->load->view('home');
    }

    public function verification()
    {
        $sendError = function ($request, $message) {
            unset($request['password']);
            $this->session->set_flashdata("erreurformulaire",  $message);
            $this->session->set_flashdata("autoComplete",  $request);
            return $this->index();
        };

        
        $this->form_validation->set_rules('societe', '', 'trim|required|callback_existSociete');
        $this->form_validation->set_rules('login', '', 'trim|required');
        $this->form_validation->set_rules('password', '', 'trim|required');

        $request = $this->input->post();
        
        if ($this->form_validation->run() === FALSE) {
            return $sendError($request, self::$AUTH_ERR_MESSAGE);
        }

        $this->load->model('utilisateur_model', '_objUser');

        $this->_objUser->conn($this->dbswitcher->conn);
        $pass = md5($request['login'] . md5($request['password']));
        $ideUser = $this->_objUser->getUtilisateurByLoginPass(array($request['login'], $pass));

        if (empty($ideUser)) {
            return $sendError($request, self::$AUTH_ERR_MESSAGE);
        }

        $this->session->set_userdata("ID", $ideUser);
        $this->session->set_userdata("IDS", $this->dbswitcher->ide);
        $this->session->set_userdata("SOC", $this->dbswitcher->codeSoc);
        $this->session->set_userdata("LOGIN", $request['login']);
        
        return redirect('Dashboard');
    }

    public function existSociete($societe)
    {
        return $this->dbswitcher->getIdeBySocCode($societe);
    }
}
