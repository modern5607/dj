<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Header extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->view('/layout/header');

	}
}
