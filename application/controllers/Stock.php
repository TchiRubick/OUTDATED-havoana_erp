<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends MY_Controller
{
	protected static $PAGE_gestion_stock = array(
		'page' => 'gestion_stock',
		'parent_menu' => 'gestion_stock',
		'child_menu' => 'gestion_stock'
	);

	protected static $PAGE_visu_stock = array(
		'page' => 'visu_stock',
		'parent_menu' => 'visu_stock',
		'child_menu' => 'visu_stock'
	);

	private $_idu;

	public function __construct()
	{
		parent::__construct();

		if (!$this->session->has_userdata("ID") || !$this->session->has_userdata("IDS")) {
			return redirect('home');
		}

		$this->_idu = $this->session->userdata("ID");

		$this->_loadClientStock();
	}

	public function index()
	{
		redirect('stock/gestion_stock');
	}

	public function gestion_stock()
	{
		$valueOption = $this->_objPrd->produitOption();
		$this->_PARAM = self::$PAGE_gestion_stock;
		$this->_PARAM['optionsProduit'] = optionFormatter($valueOption);
		$this->views();
	}

	public function visu_stock()
	{
		$this->_PARAM = self::$PAGE_visu_stock;

		$a_mag = $this->_objMag->getMagOption();

		$this->_PARAM['magasins_option'] = optionFormatter($a_mag);
		$this->views();
	}

	public function ajout()
	{
		if ($this->input->post("action") === "add") {
			$this->form_validation->set_rules('nom', 'Nom produit', 'trim|required');
			$this->form_validation->set_rules('codebarre', 'Code-barre', 'trim|required');
			$this->form_validation->set_rules('quantite', 'Quantité', 'trim|required|numeric');
			$this->form_validation->set_rules('prixa', 'Prix d\'achat unitaire', 'trim|numeric');
			$this->form_validation->set_rules('prixv', 'Prix de vente unitaire', 'trim|required|numeric');

			if ($this->form_validation->run() === FALSE) {
				$this->session->set_flashdata("erreurformulaire",  validation_errors());
				$this->session->set_flashdata("autoComplete",  $this->input->post());
				$this->transacAjout($this->_idu, 'FAI');
			} else {
				$mag_stock = $this->_objPrm->getParam('DEFAULT_MAG_STOCK');

				$isInserted = $this->_objPrd->insert($this->input->post(), $mag_stock);

				if (!$isInserted) {
					$this->session->set_flashdata("erreurformulaire",  "Erreur lors de l'insertion! Essayez de générer un nouveau code-barre avant de réenregistrer");
					$this->session->set_flashdata("autoComplete",  $this->input->post());
					$this->transacAjout($this->_idu, 'FAI');
				} else {
					$this->session->set_flashdata("erreurformulaire",  null);
					$this->session->set_flashdata("successformulaire",  "Produit ajouté au stock avec succes");
					$this->transacAjout($this->_idu);
				}
			}

			$this->session->set_flashdata("action", "add");
		}

		redirect('stock/gestion_stock');
	}

	public function getSpecifiqueProduit()
	{
		$cb = $this->input->post("cb");

		$this->form_validation->set_rules('cb', 'Codebarre', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->xhrResponse(false, validation_errors());
		}

		$produit = $this->_objPrd->getProduitByCb($cb);

		$this->xhrResponse($produit);
	}

	public function getSpecifiqueProduitMag()
	{
		$cb = $this->input->post("cb");
		$mag = $this->input->post("mag");

		$this->form_validation->set_rules('cb', 'Codebarre', 'trim|required');
		$this->form_validation->set_rules('mag', 'Code Magasin', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->xhrResponse(false, validation_errors());
		}

		$produit = $this->_objPrd->getProduitByCb($cb, $mag);

		$this->xhrResponse($produit);
	}

	public function gridProduit()
	{
		$produit 		= $this->_objPrd->getAllProduit();
		$actionProduit 	= formatGridProduit($produit);
		$this->xhrResponse($actionProduit);
	}

	public function getMagasinStock($magCode)
	{
		$produit = $this->_objPrd->allStockMagasin($magCode);
		$actionProduit 	= formatGridProduit($produit);
		$this->xhrResponse($actionProduit);
	}

	public function getListeMag()
	{
		$li_mag = $this->_objMag->getMagOptionNotStock();

		if (empty($li_mag)) {
			$this->xhrResponse(false, "Récuperation de la liste des magasin de vente impossible");
		}

		$this->xhrResponse($li_mag);
	}

	public function migrateProduit()
	{
		$code_newmag 	= $this->input->post("new_mag");
		$quantite 		= $this->input->post("quantite");
		$codebarre 		= $this->input->post("codebarre");
		$id_stkmag 		= $this->input->post("stk_mag");
		$prix 			= $this->input->post("prix");

		$this->form_validation->set_rules('new_mag', 'Magasin', 'trim|required');
		$this->form_validation->set_rules('quantite', 'Quantite', 'trim|required|numeric|greater_than[0]');
		$this->form_validation->set_rules('prix', 'Prix', 'trim|required|numeric|greater_than[0]');

		$a_mag = $this->_objMag->getMagIdByCode($code_newmag);
		$a_prd = $this->_objPrd->getPrdIdByCode($codebarre);

		if (empty($a_mag) || empty($a_prd)) {
			$this->xhrResponse(false, "Magasin ou produit introuvable");
		}

		$isElligible = $this->_objPrd->isEnougthQuantity($id_stkmag, $quantite);

		if (!$isElligible) {
			$this->xhrResponse(false, "Pas assez de quantité en stock");
		}

		if (!$this->_objPrd->createOrUpdateMagStock($a_prd["prd_idexterne"], $code_newmag, $prix, $quantite)) {
			$this->xhrResponse(false, "Creation Stock magasin impossible");
		}

		if (!$this->_objPrd->decrementMagArticle($id_stkmag, $quantite)) {
			$this->xhrResponse(false, "Reduction de la quantite en stock impossible");
		}

		$this->xhrResponse([]);
	}

	public function editProduit()
	{
		$cb		 = $this->input->post("new_cb");
		$nm		 = $this->input->post("new_nm");
		$np		 = $this->input->post("new_prixv");
		$id 	 = $this->input->post("id");
		$mag	 = $this->input->post("mag");
		$isNewCb = $this->input->post("isNewCb");

		$this->form_validation->set_rules('new_nm', 'Nom produit', 'trim|required');
		$this->form_validation->set_rules('new_cb', 'Code-barre', 'trim|required');
		$this->form_validation->set_rules('new_prixv', 'Prix de vente', 'trim|required|numeric|greater_than[0]');
		$this->form_validation->set_rules('id', 'Produit', 'trim|required|callback_existProduit');
		$this->form_validation->set_rules('mag', 'Magasin', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->transacModification($this->_idu, 'FAI');
			$this->xhrResponse(false, validation_errors());
		}

		if ($isNewCb === 1) {
			if ($this->_objPrd->isExistCodebarre($cb, $id)) {
				$this->transacModification($this->_idu, 'FAI');
				$this->xhrResponse(false, "Code-barre déjà éxistant sur un autre produit");
			}
		}

		$updated = $this->_objPrd->updateProduit($cb, $nm, $np, $id, $mag);

		if (!$updated) {
			$this->transacModification($this->_idu, 'FAI');
			$this->xhrResponse(false, "Erreur lors de la mise à jour");
		}

		$this->transacModification($this->_idu);
		$this->xhrResponse([]);
	}

	public function existProduit($id2)
	{
		$res = $this->_objPrd->isExistProduit($id2);

		if (!$res) {
			$this->form_validation->set_message('existProduit', 'Le produit n\'existe pas.');
		}

		return $res;
	}

	public function deleteProduit()
	{
		$cb = $this->input->post("cb");
		$mag = $this->input->post("mag");

		$this->form_validation->set_rules('cb', 'Codebarre', 'trim|required');
		$this->form_validation->set_rules('mag', 'Magasin', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->transacSuppression($this->_idu, 'FAI');
			$this->xhrResponse(false, validation_errors());
		}

		$isDeleted = $this->_objPrd->deleteByCodebarre($cb, $mag);

		if (!$isDeleted) {
			$this->xhrResponse(false, "Erreur suppression");
		}

		$this->transacSuppression($this->_idu);
		$this->xhrResponse([]);
	}

	public function setAnomalie()
	{
		$cb = $this->input->post("cb");
		$mag = $this->input->post("mag");

		$this->form_validation->set_rules('cb', 'Codebarre', 'trim|required');
		$this->form_validation->set_rules('mag', 'Magasin', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->transacSuppression($this->_idu, 'FAI');
			$this->xhrResponse(false, validation_errors());
		}

		$produitInfo = $this->_objPrd->getProduitIdQttByCbMag($cb, $mag);

		if (empty($produitInfo)) {
			$this->transacSuppression($this->_idu, 'FAI');
			$this->xhrResponse(false, "Produit introuvable");
		}

		$isDeleted = $this->_objPrd->setAnomalie($produitInfo);

		if (!$isDeleted) {
			$this->transacSuppression($this->_idu, 'FAI');
			$this->xhrResponse(false, "Erreur ajout anomalie");
		}

		$isDeleted = $this->_objPrd->decrementMagArticle($mag, $produitInfo['magst_quantite']);

		if (!$isDeleted) {
			$this->transacSuppression($this->_idu, 'FAI');
			$this->xhrResponse(false, "Erreur retour solde");
		}

		$this->transacSuppression($this->_idu);
		$this->xhrResponse([]);
	}
}
