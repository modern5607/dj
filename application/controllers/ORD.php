<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ORD extends CI_Controller
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

					$this->load->view('/layout/header', $this->data);
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
		check_pageLevel();

		$data['str'] = array(); //검색어관련

		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');
		$data['str']['v1'] = $this->input->get('v1');

		$data['str']['component'] = $this->input->get('component'); //시리즈
		$data['str']['component_nm'] = trim($this->input->get('component_nm')); //품명

		$params['SDATE'] = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
		$params['EDATE'] = date("Y-m-d");

		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";
		$params['V1'] = "";

		$data['qstr'] = "?P";

		if (!empty($data['str']['v1'])) {
			$params['V1'] = $data['str']['v1'];
			$data['qstr'] .= "&v1=" . $data['str']['v1'];
		}
		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=" . $data['str']['sdate'];
		}

		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=" . $data['str']['edate'];
		}

		if (!empty($data['str']['component'])) {
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=" . $data['str']['component'];
		}
		if (!empty($data['str']['component_nm'])) {
			$params['COMPONENT_NM'] = $data['str']['component_nm'];
			$data['qstr'] .= "&component_nm=" . $data['str']['component_nm'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "") ? $this->input->get('perpage') : 20;

		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;

		$start = $pageNum;
		$data['pageNum'] = $start;


		$data['NDATE'] = $date;
		$params['GJGB'] = "SH";


		$data['List'] = $this->ord_model->items_order_list($params, $start, $config['per_page']);
		$this->data['cnt'] = $this->ord_model->items_order_cnt($params);


		$data['SERIES'] = $this->main_model->get_seriesh_select();

		if (empty($this->input->get('n'))) {
			if ($date != "") {
				$data['RList'] = $this->ord_model->item_order_numlist($date, $params);
			}
		}

		/* pagenation start */

		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];


		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';


		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		$this->load->view('/ord/o1', $data);
	}

	//정형지시
	public function o2($date = '')
	{
		check_pageLevel();

		$data['str'] = array(); //검색어관련

		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['component'] = $this->input->get('component');
		$data['str']['component_nm'] = trim($this->input->get('component_nm')); //품명
		$data['str']['v1'] = $this->input->get('v1');

		$params['SDATE'] = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
		$params['EDATE'] = date("Y-m-d");

		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";

		$params['V1'] = "";

		$data['qstr'] = "?P";

		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=" . $data['str']['sdate'];
		}

		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=" . $data['str']['edate'];
		}

		if (!empty($data['str']['component'])) {
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=" . $data['str']['component'];
		}
		if (!empty($data['str']['component_nm'])) {
			$params['COMPONENT_NM'] = $data['str']['component_nm'];
			$data['qstr'] .= "&component_nm=" . $data['str']['component_nm'];
		}
		if (!empty($data['str']['v1'])) {
			$params['V1'] = $data['str']['v1'];
			$data['qstr'] .= "&v1=" . $data['str']['v1'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "") ? $this->input->get('perpage') : 20;

		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;

		$start = $pageNum;
		$data['pageNum'] = $start;


		$data['NDATE'] = $date;
		$params['GJGB'] = "JH";

		$params['BK'] = '';
		$data['BK'] = $params['BK'];
		$data['SERIES'] = $this->main_model->get_seriesh_select();

		$data['List'] = $this->ord_model->items_order_list($params, $start, $config['per_page']);
		$this->data['cnt'] = $this->ord_model->items_order_cnt($params);
		if (empty($this->input->get('n'))) {
			if ($date != "") {
				$data['RList'] = $this->ord_model->item_order_numlist($date, $params);
			}
		}

		/* pagenation start */

		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];


		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';


		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		$this->load->view('/ord/o2', $data);
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


	//시유지시
	public function o3($date = '')
	{
		check_pageLevel();

		$data['str'] = array(); //검색어관련

		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');
		$data['str']['v1'] = $this->input->get('v1');

		$data['str']['component'] = $this->input->get('component');
		$data['str']['component_nm'] = trim($this->input->get('component_nm')); //품명

		$params['SDATE'] = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
		$params['EDATE'] = date("Y-m-d");

		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";
		$params['V1'] = "";

		$data['qstr'] = "?P";

		if (!empty($data['str']['v1'])) {
			$params['V1'] = $data['str']['v1'];
			$data['qstr'] .= "&v1=" . $data['str']['v1'];
		}
		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=" . $data['str']['sdate'];
		}

		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=" . $data['str']['edate'];
		}

		if (!empty($data['str']['component'])) {
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=" . $data['str']['component'];
		}
		if (!empty($data['str']['component_nm'])) {
			$params['COMPONENT_NM'] = $data['str']['component_nm'];
			$data['qstr'] .= "&component_nm=" . $data['str']['component_nm'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "") ? $this->input->get('perpage') : 20;

		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;

		$start = $pageNum;
		$data['pageNum'] = $start;


		$data['NDATE'] = $date;
		$params['GJGB'] = "CU";


		$data['List'] = $this->ord_model->inventory_order_list($params, $start, $config['per_page']);
		$this->data['cnt'] = $this->ord_model->inventory_order_cnt($params);


		$data['SERIES'] = $this->main_model->get_seriesh_select();

		if (empty($this->input->get('n'))) {
			if ($date != "") {
				$data['RList'] = $this->ord_model->inventory_order_numlist($date, $params);
			}
		}

		/* pagenation start */

		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];


		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';


		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		$this->load->view('/ord/o3', $data);
	}


	//선별지시
	public function o4($date = '')
	{
		check_pageLevel();

		$data['str'] = array(); //검색어관련

		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');
		$data['str']['v1'] = $this->input->get('v1');

		$data['str']['component'] = $this->input->get('component');
		$data['str']['component_nm'] = trim($this->input->get('component_nm')); //품명

		$params['SDATE'] = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
		$params['EDATE'] = date("Y-m-d");

		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";
		$params['V1'] = "";

		$data['qstr'] = "?P";

		if (!empty($data['str']['v1'])) {
			$params['V1'] = $data['str']['v1'];
			$data['qstr'] .= "&v1=" . $data['str']['v1'];
		}
		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=" . $data['str']['sdate'];
		}

		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=" . $data['str']['edate'];
		}

		if (!empty($data['str']['component'])) {
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=" . $data['str']['component'];
		}
		if (!empty($data['str']['component_nm'])) {
			$params['COMPONENT_NM'] = $data['str']['component_nm'];
			$data['qstr'] .= "&component_nm=" . $data['str']['component_nm'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "") ? $this->input->get('perpage') : 20;

		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;

		$start = $pageNum;
		$data['pageNum'] = $start;


		$data['NDATE'] = $date;
		$params['GJGB'] = "SB";


		$data['List'] = $this->ord_model->inventory_order_list($params, $start, $config['per_page']);
		$this->data['cnt'] = $this->ord_model->inventory_order_cnt($params);


		$data['SERIES'] = $this->main_model->get_seriesh_select();

		if (empty($this->input->get('n'))) {
			if ($date != "") {
				$data['RList'] = $this->ord_model->inventory_order_numlist($date, $params);
			}
		}

		/* pagenation start */

		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];


		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';


		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		$this->load->view('/ord/o4', $data);
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
			return $this->load->view('/ord/ajax_items_order_o3_form', $data);
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
