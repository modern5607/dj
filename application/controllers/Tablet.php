<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tablet extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);

		$this->load->helper('test');
		$this->load->model(array('ord_model', 'main_model', 'act_model','tablet_model'));

		if (!empty($this->config->item('site_title')[$this->data['pos']][$this->data['subpos']])) {
			$this->data['siteTitle'] = $this->config->item('site_title')[$this->data['pos']][$this->data['subpos']];
		}
	}

	public function _remap($method, $params = array())
	{
		if ($this->input->is_ajax_request()) {
			if (method_exists($this, $method)) {
				call_user_func_array(array($this, $method), $params);
			}
		} else { //ajax가 아니면

			if (method_exists($this, $method)) {

				$user_id = $this->session->userdata('user_id');
				$this->data['member_name'] = $this->session->userdata('user_name');

				if (isset($user_id) && $user_id != "") {

					// $this->load->view('/layout/header', $this->data);
					$this->load->view('/layout/m_header', $this->data);
					call_user_func_array(array($this, $method), $params);
					$this->load->view('/layout/tail');
				} else {

					alert('로그인이 필요합니다.', base_url('register/login'));
				}
			} else {
				show_404();
			}
		}
	}

	//성형지시
	public function o1($date = '')
	{
		$this->data['siteTitle'] ="ㅁㄴㅇㄹ";
		$date = date("Y-m-d");
		// echo $date;
		$data['qstr'] = "?P";
		$data['NDATE'] = $date;

		$params['GJGB'] = "SH";

		$data['List'] = $this->ord_model->item_order_numlist($date, $params);
		echo var_dump(($data['List']));

		$this->load->view('/tablet/o1', $data);
	}

	//정형지시
	public function o2($date = '')
	{
		$data['qstr'] = "?P";

		$data['NDATE'] = $date;
		$params['GJGB'] = "JH";


		$data['RList'] = $this->ord_model->item_order_numlist($date, $params);


		$this->load->view('/tablet/o2', $data);
	}


	//시유지시
	public function o3($date = '')
	{
		$data['qstr'] = "?P";

		$data['NDATE'] = $date;
		$params['GJGB'] = "CU";


		// $data['List'] = $this->ord_model->inventory_order_list($params, $start, $config['per_page']);


		$data['RList'] = $this->ord_model->inventory_order_numlist($date, $params);

		$this->load->view('/tablet/o3', $data);
	}


	//선별지시
	public function o4($date = '')
	{
		$params['GJGB'] = "SB";
		$data['List'] = $this->act_model->get_inventory_list();

		$this->load->view('/tablet/o4', $data);
	}

	
}
