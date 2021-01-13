<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Writeupdate extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function _remap($method)
	{
		$this->load->view('/layout/header');
		$this->index();
		$this->load->view('/layout/tail');
	}
	

	public function index()
	{
		
		$this->load->view('main');
		
	}
}
