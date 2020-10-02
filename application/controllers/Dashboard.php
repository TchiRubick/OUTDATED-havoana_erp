<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	protected static $PAGE_dashboard = array(
		'page' => 'dashboard',
		'parent_menu' => 'dashboard',
		'child_menu' => 'dashboard'
	);

	private $_idu;

	public function __construct()
	{
		parent::__construct();

		if (!$this->session->has_userdata("ID") || !$this->session->has_userdata("IDS")) {
			return redirect('home');
		}

		$this->_idu = $this->session->userdata("ID");
		$this->_loadClientVente();
	}

	public function index()
	{
		$this->_PARAM = self::$PAGE_dashboard;

		$a_mag = $this->_objMag->getMagOptionNotStock();
		$a_prd = $this->_objPrd->produitOption();

		$this->_PARAM['magasins_option'] = optionFormatter($a_mag);
		$this->_PARAM['produits_option'] = optionFormatter($a_prd);
		$this->views();
	}

	public function getallvente()
	{
		$vente = $this->_objVnt->allVente();

		$this->xhrResponse($vente);
	}

	public function getMagasinVente($magCode)
	{
		$vente = $this->_objVnt->allVenteMagasin($magCode);

		$this->xhrResponse($vente);
	}

	public function chartProduitOnload()
	{
		$codebarre 	= $this->input->post("codebarre");
		$annee 		= $this->input->post("annee");
		$magasin 	= $this->input->post("magasin");

		$this->form_validation->set_rules('codebarre', 'Codebarre', 'trim|required');
		$this->form_validation->set_rules('annee', 'Annee', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->xhrResponse(false, validation_errors());
		}

		$chartData = [];

		for ($i=0; $i < 12 ; $i++) {
			$a_prd = $this->_objVnt->getdataChartVenteProduit($codebarre, $magasin, $annee, $i + 1);

			$chartData[] = empty($a_prd) ? 0 : $a_prd['quantite'];
		}

		$this->xhrResponse($chartData);
	}

	public function chartVenteOnload()
	{
		$annee 		= $this->input->post("annee");
		$magasin 	= $this->input->post("magasin");

		$this->form_validation->set_rules('annee', 'Annee', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->xhrResponse(false, validation_errors());
		}

		$chartData = [];

		for ($i=0; $i < 12 ; $i++) {
			$a_vnt = $this->_objVnt->getdataChartVente($magasin, $annee, $i + 1);

			$chartData[] = empty($a_vnt) ? 0 : $a_vnt['montant'];
		}

		$this->xhrResponse($chartData);
	}
}
