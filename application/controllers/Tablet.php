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
		$this->load->model(array('ord_model', 'main_model', 'act_model', 'tablet_model'));

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

	public function index()
	{
		$this->load->view('/tablet/index');
	}

	//성형지시
	public function o1($date = '')
	{
		// $this->data['siteTitle'] = "ㅁㄴㅇㄹ";
		$date = date("Y-m-d");

		$data['qstr'] = "?P";
		$data['NDATE'] = $date;

		$params['GJGB'] = "SH";

		$data['List'] = $this->tablet_model->get_sh_list($date, $params);
		// echo var_dump(($data['List']));

		$this->load->view('/tablet/o1', $data);
	}

	//성형 작업지시 ajax
	public function ajax_add_sh()
	{
		$data['title'] = '성형 작업지시';
		$data['idx'] = $this->input->post('idx');
		$data['date'] = $this->input->post('date');

		$params['IDX'] = $data['idx'];

		$data['info'] = $this->tablet_model->get_ajax_sh_info($params);
		// echo var_dump($data['info']);
		return $this->load->view('tablet/ajax_sh', $data);
	}

	//성형 입력 Update
	public function add_sh_order()
	{
		$data['idx'] = $this->input->post('idx');
		$data['qty'] = $this->input->post('FNSH_QTY');

		$params['IDX'] = $data['idx'];
		$params['F_QTY'] = $data['qty'];

		$result = $this->tablet_model->update_sh_order($params);

		if ($result > 0) {
			$data['status'] = "ok";
			$data['msg'] = "작업지시가 등록되었습니다.";
		} else {
			$data['status'] = "";
			$data['msg'] = "작업지시 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}

	//정형지시
	public function o2($date = '')
	{

		$date = date("Y-m-d");
		$data['qstr'] = "?P";

		$data['NDATE'] = $date;
		$params['GJGB'] = "JH";


		$data['List'] = $this->tablet_model->get_jh_list($date, $params);


		$this->load->view('/tablet/o2', $data);
	}

	//정형 작업지시 ajax
	public function ajax_add_jh()
	{
		$data['title'] = '정형 작업지시';
		$data['idx'] = $this->input->post('idx');
		$data['date'] = $this->input->post('date');

		$params['IDX'] = $data['idx'];

		$data['info'] = $this->tablet_model->get_ajax_jh_info($params);
		// echo var_dump($data['info']);
		return $this->load->view('tablet/ajax_jh', $data);
	}

	//정형 입력 Update
	public function add_jh_order()
	{
		$data['idx'] = $this->input->post('idx');
		$data['qty'] = $this->input->post('FNSH_QTY');

		$params['IDX'] = $data['idx'];
		$params['F_QTY'] = $data['qty'];

		$result = $this->tablet_model->update_jh_order($params);

		if ($result > 0) {
			$data['status'] = "ok";
			$data['msg'] = "작업지시가 등록되었습니다.";
		} else {
			$data['status'] = "";
			$data['msg'] = "작업지시 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}


	//시유지시
	public function o3()
	{
		$data['qstr'] = "?P";

		$param['date'] = date("Y-m-d");
		$params['GJGB'] = "CU";

		// $data['List'] = $this->ord_model->inventory_order_list($params, $start, $config['per_page']);


		$data['RList'] = $this->tablet_model->tablet_order_numlist($param);

		$this->load->view('/tablet/o3', $data);
	}



	//선별지시
	public function o4($date = '')
	{
		$params['GJGB'] = "SB";
		$data['List'] = $this->act_model->get_inventory_list($params, 0, 9999);

		$this->load->view('/tablet/o4', $data);
	}


	//시유 popup
	public function ajax_invenNum_form()
	{
		$param['idx'] 	= $this->input->post("idx");


		$data['title'] = "시유작업지시";
		$data['RList'] = $this->tablet_model->tablet_order_numlist($param);
		return $this->load->view('/tablet/ajax_items_order_o3_form', $data);
	}

	//시유 update insert
	public function cu_update_insert()
	{
		$params['IDX'] = $this->input->post("IDX");
		$params['QTY'] = $this->input->post("QTY");

		$num = $this->tablet_model->ajax_act_a10_insert($params);

		if ($num > 0) {
			alert("시유작업실적이 등록되었습니다.", base_url('TABLET/o3/'));
		} else {
			$data['status'] = "";
			$data['msg'] = "실적 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		// echo json_encode($data);
	}

	//선별 작업지시
	public function ajax_invenNum1_form()
	{
		$data['OIDX'] = $this->input->post("idx");

		$data['List'] = $this->act_model->get_inventory_list($data, 0, 9999);

		$data['title'] = "선별작업지시";
		return $this->load->view('/tablet/ajax_items_order_o4_form', $data);
	}
}
