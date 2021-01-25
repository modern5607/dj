<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PO extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->helper('test');
		$this->load->model(array('pln_model','main_model','amt_model'));

		if(!empty($this->config->item('site_title')[$this->data['pos']][$this->data['subpos']])){
			$this->data['siteTitle'] = $this->config->item('site_title')[$this->data['pos']][$this->data['subpos']];
		}

		

	}

	public function _remap($method, $params = array())
	{
		if($this->input->is_ajax_request()){
            if( method_exists($this, $method) ){
                call_user_func_array(array($this,$method), $params);
            }
        }else{ //ajax가 아니면
			
			if (method_exists($this, $method)) {

				$user_id = $this->session->userdata('user_id');
				$this->data['member_name'] = $this->session->userdata('user_name');

				if(isset($user_id) && $user_id != ""){
					
					$this->load->view('/layout/header',$this->data);
					call_user_func_array(array($this,$method), $params);
					$this->load->view('/layout/tail');

				}else{

					alert('로그인이 필요합니다.',base_url('register/login'));

				}

            } else {
                show_404();
            }

        }
		
	}



	





	public function index($idx=0)
	{
		
		
	}


	public function p1($date='')
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['component'] = $this->input->get('component');
		$data['str']['component_nm'] = $this->input->get('component_nm');


		$params['SDATE'] = date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));
		$params['EDATE'] = date("Y-m-d");

		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";


		$data['qstr'] = "?P";
		
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];
		}

		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];
		}

		if(!empty($data['str']['component'])){
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=".$data['str']['component'];
		}
		if(!empty($data['str']['component_nm'])){
			$params['COMPONENT_NM'] = $data['str']['component_nm'];
			$data['qstr'] .= "&component_nm=".$data['str']['component_nm'];
		}
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		
		$start = $pageNum;
		$data['pageNum'] = $start;


		$data['NDATE'] = $date;
		
		
		$data['List'] = $this->amt_model->component_trans_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->amt_model->component_trans_cnt($params);
		if(empty($this->input->get('n'))){
			if($date != ""){
				$data['RList'] = $this->amt_model->component_trans_numlist($date,$params);
				$data['detail'] = $this->amt_model->component_count($date,$params);
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

		$this->load->view('/amt/am1',$data);
	}


	public function p2()
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['component'] = $this->input->get('component');
		$data['str']['component_nm'] = $this->input->get('component_nm');
		$data['str']['customer'] = $this->input->get('customer');

		$params['SDATE'] = date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));
		$params['EDATE'] = date("Y-m-d");
		
		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";
		$params['CUSTOMER'] = "";

		$data['qstr'] = "?P";
		
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];
		}

		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];
		}

		if(!empty($data['str']['component'])){
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=".$data['str']['component'];
		}
		if(!empty($data['str']['component_nm'])){
			$params['COMPONENT_NM'] = $data['str']['component_nm'];
			$data['qstr'] .= "&component_nm=".$data['str']['component_nm'];
		}
		if(!empty($data['str']['customer'])){
			$params['CUSTOMER'] = $data['str']['customer'];
			$data['qstr'] .= "&customer=".$data['str']['customer'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		
		$start = $pageNum;
		$data['pageNum'] = $start;
		
		$data['title'] = "";

		$data['List'] = $this->amt_model->component_trans_am2list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->amt_model->component_trans_cnt($params);
		
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

		$this->load->view('/amt/am2',$data);
	}



	public function p3()
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
	
		$data['str']['v1'] = $this->input->get('v1');
		$data['str']['v2'] = $this->input->get('v2');
			
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['component'] = $this->input->get('component');
		$data['str']['component_nm'] = $this->input->get('component_nm');

		$params['V1'] = "";
		$params['V2'] = "";

		$params['SDATE'] = date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));
		$params['EDATE'] = date("Y-m-d");
		
		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";

		$data['qstr'] = "?P";
		
		if(!empty($data['str']['v1'])){
			$params['V1'] = $data['str']['v1'];
			$data['qstr'] .= "&v1=".$data['str']['v1'];
		}
		if(!empty($data['str']['v2'])){
			$params['V2'] = $data['str']['v2'];
			$data['qstr'] .= "&v2=".$data['str']['v2'];
		}
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];
		}

		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];
		}

		if(!empty($data['str']['component'])){
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=".$data['str']['component'];
		}
		if(!empty($data['str']['component_nm'])){
			$params['COMPONENT_NM'] = $data['str']['component_nm'];
			$data['qstr'] .= "&component_nm=".$data['str']['component_nm'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		
		$start = $pageNum;
		$data['pageNum'] = $start;
		
		$data['title'] = "";

		$data['List'] = $this->amt_model->component_trans_am2list_out($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->amt_model->component_trans_am2list_cnt_out($params);
		
		$data['SERIES'] = $this->main_model->get_seriesh_select();

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

		$this->load->view('/amt/am3',$data);
	}

	public function am1_listupdate()
	{
		$param['ITEM_IDX'] = $this->input->post("idx");
		$param['QTY'] = $this->input->post("stock");
		$param['C_QTY'] = $this->input->post("cQty");

		$data = $this->amt_model->am1_listupdate($param);
		
		echo $data;
		
	}

	public function delete_comp_trans($idx="")
	{
		$data = array();
		$param['CQTY'] = $this->input->post("cQty");
		$param['IDX'] = $this->input->post("idx");

		$data = $this->amt_model->delete_compTrans($param);
		alert($data);


		echo $data;
		//var_dump($data['result']);
	}






}