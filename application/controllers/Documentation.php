<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Documentation extends MY_Controller
{
  protected static $PAGE_tutoriel = array(
    'page' => 'tutoriel',
    'parent_menu' => 'tutoriel',
    'child_menu' => 'tutoriel'
  );

  protected static $PAGE_cgu = array(
    'page' => 'cgu',
    'parent_menu' => 'cgu',
    'child_menu' => 'cgu'
  );

  protected static $PAGE_aide = array(
    'page' => 'aide',
    'parent_menu' => 'aide',
    'child_menu' => 'aide'
  );

  public function __construct()
  {
    parent::__construct();

		if (!$this->session->has_userdata("ID") || !$this->session->has_userdata("IDS")) {
			return redirect('home');
		}
  }

  public function index()
  {
    return redirect('Documentation/tutoriel');
  }

  public function tutoriel()
  {
    $this->_PARAM = self::$PAGE_tutoriel;
    $this->views();
  }

  public function cgu()
  {
    $this->_PARAM = self::$PAGE_cgu;
    $this->views();
  }

  public function aide()
  {
    $this->_PARAM = self::$PAGE_aide;
    $this->views();
  }
}
