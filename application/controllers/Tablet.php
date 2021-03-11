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
		$this->load->model(array('ord_model', 'main_model', 'act_model'));

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

					$this->load->view('/layout/m_header', $this->data);
					call_user_func_array(array($this, $method), $params);
					$this->load->view('/layout/m_tail');
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
		$data['qstr'] = "?P";

		$params['GJGB'] = "SH";

		$data['List'] = $this->ord_model->item_order_numlist($date, $params);


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

		$data['qstr'] = "?P";

		$data['NDATE'] = $date;
		$params['GJGB'] = "SB";

		// $data['List'] = $this->ord_model->inventory_order_list($params, $start, $config['per_page']);

		$data['RList'] = $this->ord_model->inventory_order_numlist($date, $params);

		$this->load->view('/tablet/o4', $data);
	}

	//성형,정형 작업지시 추가 popup
	public function ajax_itemNum_form()
	{
		$data['mode'] = $this->input->post("mode");
		$data['SERIES'] = $this->main_model->get_seriesh_select();
		$data['HIDX'] = $this->input->post("hidx");
		$data['NDATE'] = $this->input->post("date");


		if ($this->input->post("type") == "o2") {
			$data['title'] = "정형작업지시";
			return $this->load->view('/ord/ajax_items_order_o2_form', $data);
		} else {
			$data['title'] = "성형작업지시";
			$data['component'] = $this->act_model->get_component_stock();
			return $this->load->view('/ord/ajax_items_order_o1_form', $data);
		}
	}

	//성형작업지시 추가
	public function ajax_act_items_order_insert()
	{
		foreach ($this->input->post("QTY") as $key => $qty) {
			if ($qty == "") {
				continue;
			} else {
				$params['QTY'][$key] = $qty;
				$params['ITEM_IDX'][$key] = $this->input->post("ITEM_IDX")[$key];
				$params['REMARK'][$key] = $this->input->post("REMARK")[$key];
				$params['transdate'] = $this->input->post("transdate");
				$params['GJGB'] = $this->input->post("GJGB");
			}
		}

		$num = $this->ord_model->ajax_item_order_insert($params);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "작업지시가 등록되었습니다.";
		} else {
			$data['status'] = "";
			$data['msg'] = "작업지시 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}

	//작업지시 삭제
	public function ajax_del_items_order()
	{
		$idx = $this->input->get("idx");
		$num = $this->ord_model->ajax_del_items_order($idx);
		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "삭제되었습니다.";
		} else {
			$data['status'] = "no";
			$data['msg'] = "삭제에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}


	//시유,선별 작업지시 추가 popup
	public function ajax_invenNum_form()
	{
		$data['mode'] 	= $this->input->post("mode");
		$data['SERIES'] = $this->main_model->get_seriesh_select();
		$data['HIDX'] 	= $this->input->post("hidx");
		$data['NDATE']	= $this->input->post("date");


		if ($this->input->post("type") == "o4") {
			$data['title'] = "선별작업지시";
			return $this->load->view('/ord/ajax_items_order_o4_form', $data);
		} else {
			$data['title'] = "시유작업지시";
			$data['component'] = $this->act_model->get_component_stock();
			return $this->load->view('/tablet/ajax_items_order_o3_form', $data);
		}
	}

	//시유,선별 작업지시 추가
	public function ajax_act_inven_order_insert()
	{

		foreach ($this->input->post("QTY") as $key => $qty) {
			if ($qty == "") {
				continue;
			} else {
				$params['QTY'][$key] = $qty;
				$params['ITEM_IDX'][$key] = $this->input->post("ITEM_IDX")[$key];
				$params['REMARK'][$key] = $this->input->post("REMARK")[$key];
				$params['SERIESD_IDX'][$key] = $this->input->post("SERIESD_IDX")[$key];
				$params['transdate'] = $this->input->post("transdate");
				$params['GJGB'] = $this->input->post("GJGB");
			}
		}

		$num = $this->ord_model->ajax_inven_order_insert($params);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "작업지시가 등록되었습니다.";
		} else {
			$data['status'] = "";
			$data['msg'] = "작업지시 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}


	//시유,선별 작업지시 삭제
	public function ajax_del_inven_order()
	{
		$idx = $this->input->get("idx");
		$num = $this->ord_model->ajax_del_inven_order($idx);
		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "삭제되었습니다.";
		} else {
			$data['status'] = "no";
			$data['msg'] = "삭제에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}

	public function ajax_invenindex_pop()
	{
		$params['s1'] = $this->input->post("s1");
		$params['s2'] = $this->input->post("s2");
		$params['type'] = $this->input->post("type");

		$data = $this->ord_model->ajax_inven_pop($params);
		echo json_encode($data);
	}
}
