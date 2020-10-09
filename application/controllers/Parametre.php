<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Parametre extends MY_Controller
{
	protected static $PAGE_gestion_device = array(
		'page' => 'gestion_device',
		'parent_menu' => 'gestion_device',
		'child_menu' => 'gestion_device'
	);

	protected static $PAGE_gestion_user = array(
		'page' => 'gestion_user',
		'parent_menu' => 'gestion_user',
		'child_menu' => 'gestion_user'
	);

	private $_idu;

	public function __construct()
	{
		parent::__construct();

		if (!$this->session->has_userdata("ID") || !$this->session->has_userdata("IDS")) {
			return redirect('home');
		}

		$this->_idu = $this->session->userdata("ID");

		$this->_loadClientParametre();
		$this->checkAutorisation();
	}

	public function index()
	{
		redirect('Parametre/gestion_device');
	}

	public function gestion_device()
	{
		$this->_PARAM = self::$PAGE_gestion_device;

		$utilisateur = $this->_objUtl->getAllAgentCaisseNotSup();
		$this->_PARAM["options_user"] = optionFormatter($utilisateur);

		$magasins = $this->_objMag->getMagOptionNotStock();
		$this->_PARAM["options_magasin"] = optionFormatter($magasins);

		$this->views();
	}

	public function gridDevice()
	{
		$devices 	= $this->_objDvc->getAll();
		$gridDevice = formatGridDevice($devices);
		$this->xhrResponse($gridDevice);
	}

	public function switchDevice()
	{
		$ideDevice = $this->input->post('ideDevice');

		$this->form_validation->set_rules('ideDevice', 'Appareil', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->transacConfiguration($this->_idu, 'FAI');
			$this->xhrResponse(false, validation_errors());
		}

		$limitDevice 	= $this->_objPrm->getDeviceLimit();
		$isUpdated 		= $this->_objDvc->switchDevice($ideDevice, $limitDevice);

		if (!$isUpdated) {
			$this->transacConfiguration($this->_idu, 'FAI');
			$this->xhrResponse(false, $this->_objDvc->_errorMessage);
		}

		$this->transacConfiguration($this->_idu);
		$this->xhrResponse([]);
	}

	public function changeNomDevice()
	{
		$idd = $this->input->post('idd');
		$nn  = $this->input->post('nn');
		$lu  = $this->input->post('lu');
		$lm  = $this->input->post('lm');

		$this->form_validation->set_rules('idd', 'Appareil', 'trim|required');
		$this->form_validation->set_rules('nn', 'Nom', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->transacModification($this->_idu, 'FAI');
			$this->xhrResponse(false, validation_errors());
		}

		$isUpdated = $this->_objDvc->setName(array($nn, $idd));
		if (!$isUpdated) {
			$this->transacModification($this->_idu, 'FAI');
			$this->xhrResponse(false, $this->_objDvc->_errorMessage);
		}

		if (is_iterable($lu)) {
			$this->_objDvc->deleteDroitByIde($idd);

			foreach ($lu as $l) {

				if (trim($l) === "") continue;
				$isUpdated = $this->_objDvc->setListeUser($l, $idd, $lm);

				if (!$isUpdated) {
					$this->transacModification($this->_idu, 'FAI');
					$this->xhrResponse(false, $this->_objDvc->_errorMessage);
				}
			}

			// Set support everytime in list
			$this->_objDvc->setListeUser(SUPPORT_IDUSER, $idd, $lm);
		}

		$this->transacModification($this->_idu);
		$this->xhrResponse([]);
	}

	public function getUserMagByDevice()
	{
		$ideDevice = $this->input->post('ideDevice');

		$this->form_validation->set_rules('ideDevice', 'Appareil', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->xhrResponse(false, validation_errors());
		}

		$user = $this->_objDvc->getUserMagByIde($ideDevice);

		$this->xhrResponse($user);
	}

	public function gestion_user()
	{
		$this->_PARAM = self::$PAGE_gestion_user;
		$this->views();
	}

	public function gridUser()
	{
		$utilisateur 	= $this->_objUtl->getAllUser();
		$usersFormated 	= formatGridUser($utilisateur);
		$this->xhrResponse($usersFormated);
	}

	public function switchUser()
	{
		$ideUser = $this->input->post('ideUser');

		$this->form_validation->set_rules('ideUser', 'Utilisateur', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->transacConfiguration($this->_idu, 'FAI');
			$this->xhrResponse(false, validation_errors());
		}

		$isUpdated = $this->_objUtl->switchUser($ideUser);

		if (!$isUpdated) {
			$this->transacConfiguration($this->_idu, 'FAI');
			$this->xhrResponse(false, $this->_objUtl->_errorMessage);
		}

		$this->transacConfiguration($this->_idu);
		$this->xhrResponse([]);
	}

	public function editUser()
	{
		$nem = $this->input->post('nem');
		$pas = $this->input->post('pas');
		$idu = $this->input->post('idu');

		$this->form_validation->set_rules('nem', 'Email', 'trim|required');
		$this->form_validation->set_rules('pas', 'Mot de pass', 'trim|required');
		$this->form_validation->set_rules('idu', 'Utilisateur', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->transacModification($this->_idu, 'FAI');
			$this->xhrResponse(false, validation_errors());
		}

		$login 		= $this->session->userdata("LOGIN");
		$SOC 		= $this->session->userdata("SOC");
		$password 	= md5($login . md5($pas));

		$isAuthorized = $this->_objUtl->checkIfReallyAdminByPass($password, $this->_idu);

		if (!$isAuthorized) {
			$this->transacModification($this->_idu, 'FAI');
			$this->xhrResponse(false, $this->_objUtl->_errorMessage);
		}

		if ($isAuthorized["success"] == false) {
			$this->transacModification($this->_idu, 'FAI');
			$this->xhrResponse(false, $this->_objUtl->_errorMessage);
		}

		$role = $this->_objUtl->getRoleByIdu($idu);

		if (empty($role)) {
			$this->transacModification($this->_idu, 'FAI');
			$this->xhrResponse(false, $this->_objUtl->_errorMessage);
		}

		$newPassword 	= generatePassword();
		$newLogin 		= $isAuthorized["relog"] ? $role["rl_code"] . "_" . $SOC : $role["rl_code"] . "_" . $SOC . "_" . generateLogin();

		$isInserted = $this->_objUtl->editUserInfo($nem, $idu, $newPassword, $newLogin);

		if (!$isInserted) {
			$this->transacModification($this->_idu, 'FAI');
			$this->xhrResponse(false, "Modification impossible");
		}

		$numtransac = $this->transacEnvoie($this->_idu, "ENC", "MAI");

		$this->asyncPostCurl('newUser', array(
			"email" 		=> $nem,
			"login" 		=> $newLogin,
			"password" 		=> $newPassword,
			"societe" 		=> $SOC,
			"numtransac" 	=> $numtransac
		));

		$this->transacModification($this->_idu);
		$this->xhrResponse(["relog" => $isAuthorized["relog"]]);


	}

	public function deleteDevice()
	{
		$ideDevice = $this->input->post("ideDevice");

		$this->form_validation->set_rules('ideDevice', 'Appareil', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->transacSuppression($this->_idu, 'FAI');
			$this->xhrResponse(false, validation_errors());
		}

		$isDeleted = $this->_objDvc->deleteDevice($ideDevice);

		if (!$isDeleted) {
			$this->transacSuppression($this->_idu, 'FAI');
			$this->xhrResponse(false, $this->_objDvc->_errorMessage);
		}

		$this->transacSuppression($this->_idu);
		$this->xhrResponse([]);
	}
}
