<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kpi extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);
		$this->data['detailpos'] = $this->uri->segment(3);

		$this->load->helper('test');
		$this->load->model(array('kpi_model', 'main_model'));

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
					$this->load->view('/layout/header', $this->data);
					call_user_func_array(array($this, $method), $params);
					$this->load->view('/layout/tail');
				
			} else {
				show_404();
			}
		}
	}

	public function equip1()
	{
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['sweek'] = $this->input->get('sweek');
		$data['str']['eweek'] = $this->input->get('eweek');
		// $data['str']['edate'] = $this->input->get('edate');


		$params['SDATE'] = date("Y");
		$params['SWEEK'] = '01';
		$params['EWEEK'] = '02';
		// $params['EDATE'] = date("Y-m-d");
		$params['CHART'] = 1;

		$data['qstr'] = "?P";
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];

		}
		if(!empty($data['str']['sweek'])){
			$params['SWEEK'] = $data['str']['sweek'];
			$data['qstr'] .= "&sweek=".$data['str']['sweek'];

		}
		if(!empty($data['str']['eweek'])){
			$params['EWEEK'] = $data['str']['eweek'];
			$data['qstr'] .= "&eweek=".$data['str']['eweek'];

		}
		// if(!empty($data['str']['edate'])){
		// 	$params['EDATE'] = $data['str']['edate'];
		// 	$data['qstr'] .= "&edate=".$data['str']['edate'];
		// }

		$start = 0;
		$config['per_page'] = 9999;

		// $data['title'] = "스마트공장 KPI 반품감소율";
		$data['List']   = $this->kpi_model->equip_chart($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->kpi_model->equip_cut($params);
		$data['mean'] = $this->kpi_model->equip_mean($params);

		$data['MF']    = $this->main_model->get_selectInfo_new("tch.CODE","KPI","MF");
		
		$this->load->view('/kpi/equipchart',$data);
	}

	public function fair1()
	{
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['sweek'] = $this->input->get('sweek');
		$data['str']['eweek'] = $this->input->get('eweek');
		// $data['str']['edate'] = $this->input->get('edate');


		$params['SDATE'] = date("Y");
		$params['SWEEK'] = '01';
		$params['EWEEK'] = '02';
		// $params['EDATE'] = date("Y-m-d");
		$params['CHART'] = 1;

		$data['qstr'] = "?P";
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];

		}
		if(!empty($data['str']['sweek'])){
			$params['SWEEK'] = $data['str']['sweek'];
			$data['qstr'] .= "&sweek=".$data['str']['sweek'];

		}
		if(!empty($data['str']['eweek'])){
			$params['EWEEK'] = $data['str']['eweek'];
			$data['qstr'] .= "&eweek=".$data['str']['eweek'];

		}
		// if(!empty($data['str']['edate'])){
		// 	$params['EDATE'] = $data['str']['edate'];
		// 	$data['qstr'] .= "&edate=".$data['str']['edate'];
		// }

		
		$start = 0;
		$config['per_page'] = 9999;

		// $data['title'] = "스마트공장 KPI 전기에너지 절감율";
		$data['List']   = $this->kpi_model->fair_chart($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->kpi_model->fair_cut($params);
		$data['mean'] = $this->kpi_model->fair_mean($params);

		$data['PP']    = $this->main_model->get_selectInfo_new("tch.CODE","KPI","PP");

		$this->load->view('/kpi/fairchart',$data);
	}

	public function short1()
	{
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['sweek'] = $this->input->get('sweek');
		$data['str']['eweek'] = $this->input->get('eweek');
		// $data['str']['edate'] = $this->input->get('edate');


		$params['SDATE'] = date("Y");
		$params['SWEEK'] = '01';
		$params['EWEEK'] = '02';
		// $params['EDATE'] = date("Y-m-d");
		$params['CHART'] = 1;

		$data['qstr'] = "?P";
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];

		}
		if(!empty($data['str']['sweek'])){
			$params['SWEEK'] = $data['str']['sweek'];
			$data['qstr'] .= "&sweek=".$data['str']['sweek'];

		}
		if(!empty($data['str']['eweek'])){
			$params['EWEEK'] = $data['str']['eweek'];
			$data['qstr'] .= "&eweek=".$data['str']['eweek'];

		}
		// if(!empty($data['str']['edate'])){
		// 	$params['EDATE'] = $data['str']['edate'];
		// 	$data['qstr'] .= "&edate=".$data['str']['edate'];
		// }

		
		$start = 0;
		$config['per_page'] = 9999;

		// $data['title'] = "스마트공장 KPI 납기단축";
		$data['List']   = $this->kpi_model->short_chart($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->kpi_model->short_cut($params);
		$data['mean'] = $this->kpi_model->kpi_mean($params,'PP');

		$data['PP']    = $this->main_model->get_selectInfo_new("tch.CODE","KPI","PP");

		$this->load->view('/kpi/shortchart',$data);
	}

	public function equip2()
	{
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$params['SDATE'] = date("Y-m-d", strtotime("-1 month", time()));
		$params['EDATE'] = date("Y-m-d");

		$data['qstr'] = "?P";
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];

		}
		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];

		}

$data['perpage'] = ($this->input->get('perpage') != "") ? $this->input->get('perpage') : 20;

//PAGINATION
$config['per_page'] = $data['perpage'];
$config['page_query_string'] = true;
$config['query_string_segment'] = "pageNum";
$config['reuse_query_string'] = TRUE;

$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
//$start = $config['per_page'] * ($pageNum - 1);

$start = $pageNum;
$data['pageNum'] = $start;

		// $data['title'] = "반품감소율 리스트";		
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
	

		$data['List'] = $this->kpi_model->equip_list($params,$start, $config['per_page']);
		$this->data['cnt'] = $this->kpi_model->equip_list_cnt($params);
		$data['mean'] = $this->kpi_model->equip_mean($params);
		$data['MF']    = $this->main_model->get_selectInfo_new("tch.CODE","KPI","MF");

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

/* pagenation end */
		
		$this->load->view('/kpi/equiplist',$data);
	}

	public function fair2($idx = 0)
	{
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$params['SDATE'] = date("Y-m-d", strtotime("-1 month", time()));
		$params['EDATE'] = date("Y-m-d");

		$data['qstr'] = "?P";
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];

		}
		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];

		}

$data['perpage'] = ($this->input->get('perpage') != "") ? $this->input->get('perpage') : 20;

//PAGINATION
$config['per_page'] = $data['perpage'];
$config['page_query_string'] = true;
$config['query_string_segment'] = "pageNum";
$config['reuse_query_string'] = TRUE;

$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
//$start = $config['per_page'] * ($pageNum - 1);

$start = $pageNum;
$data['pageNum'] = $start;

		// $data['title'] = "전기에너지 절감율 리스트";		
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
	

		$data['List'] = $this->kpi_model->fair_list($params,$start, $config['per_page']);
		$this->data['cnt'] = $this->kpi_model->fair_list_cnt($params);
		$data['mean'] = $this->kpi_model->equip_mean($params);
		$data['PP']    = $this->main_model->get_selectInfo_new("tch.CODE","KPI","PP");

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

/* pagenation end */
		
		$this->load->view('/kpi/fairlist',$data);
	}
	/* 납기준수율 리스트 디테일 */
	public function sold_select_ajax()
	{
		$data = array();
		$data['idx'] = $this->input->post("idx");
		$params['INSERT_DATE'] = $data['idx'];


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;

		//alert("perpage".$data['perpage']);
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		//$start = $config['per_page'] * ($pageNum - 1);



		$start = $pageNum;
		$data['pageNum'] = $start;


		$data['soldList'] = $this->kpi_model->get_sold_All($params, $start, $config['per_page']);
		$this->data['cnt'] = $this->kpi_model->get_sold_All_cut($params);

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

		/* pagenation end */
		return $this->load->view('ajax/fair_soldselect_ajax', $data);
	}


	public function short2($idx = 0)
	{
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$params['SDATE'] = date("Y-m-d", strtotime("-1 month", time()));
		$params['EDATE'] = date("Y-m-d");

		$data['qstr'] = "?P";
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];

		}
		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];

		}

$data['perpage'] = ($this->input->get('perpage') != "") ? $this->input->get('perpage') : 20;

//PAGINATION
$config['per_page'] = $data['perpage'];
$config['page_query_string'] = true;
$config['query_string_segment'] = "pageNum";
$config['reuse_query_string'] = TRUE;

$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
//$start = $config['per_page'] * ($pageNum - 1);

$start = $pageNum;
$data['pageNum'] = $start;

		// $data['title'] = "납기단축 리스트";		
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
	

		$data['List'] = $this->kpi_model->short_list($params,$start, $config['per_page']);
		$this->data['cnt'] = $this->kpi_model->short_list_cnt($params);
		$data['mean'] = $this->kpi_model->kpi_mean($params,'PP');
		$data['PP']    = $this->main_model->get_selectInfo_new("tch.CODE","KPI","PP");

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

/* pagenation end */
		
		$this->load->view('/kpi/shortlist',$data);
	}
}
