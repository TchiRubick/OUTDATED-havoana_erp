<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gateway extends MY_Controller
{
	protected static $PAGE_not_authorized = array(
		'page' => 'not_authorized',
		'parent_menu' => 'not_authorized',
		'child_menu' => 'not_authorized'
	);

	public function __construct()
	{
		parent::__construct();
	}

	public function not_authorized()
	{
		$this->_PARAM = self::$PAGE_not_authorized;
		$this->views();
	}
}
