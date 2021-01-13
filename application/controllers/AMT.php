<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AMT extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->helper('test');
		$this->load->model(array('pln_model','main_model','amt_model', 'act_model'));

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


	
	/* 완제품재고내역 **/
	public function am1()
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['v1'] = $this->input->get('v1');
		$data['str']['v2'] = $this->input->get('v2');
		$data['str']['v3'] = $this->input->get('v3');
		$data['str']['v4'] = $this->input->get('v4');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
		$params['V1'] = "";
		$params['V2'] = "";
		$params['V3'] = "";
		$params['V4'] = "";

		$data['qstr'] = "?P";
		
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];
		}

		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];
		}
		
		
		if(!empty($data['str']['v1'])){
			$params['V1'] = $data['str']['v1'];
			$data['qstr'] .= "&v1=".$data['str']['v1'];
		}
		if(!empty($data['str']['v2'])){
			$params['V2'] = $data['str']['v2'];
			$data['qstr'] .= "&v2=".$data['str']['v2'];
		}
		if(!empty($data['str']['v3'])){
			$params['V3'] = $data['str']['v3'];
			$data['qstr'] .= "&v3=".$data['str']['v3'];
		}
		if(!empty($data['str']['v4'])){
			$params['V4'] = $data['str']['v4'];
			$data['qstr'] .= "&v4=".$data['str']['v4'];
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
		$data['List'] = $this->act_model->act_an2_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->act_an2_cut($params);

		

		//$data['SERIES'] = $this->main_model->get_seriesh_select();


		
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

		$this->load->view('/act/an2',$data);

	}


	/* 재생재고내역 **/
	public function am2()
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['v1'] = $this->input->get('v1');
		$data['str']['v2'] = $this->input->get('v2');
		$data['str']['v3'] = $this->input->get('v3');
		$data['str']['v4'] = $this->input->get('v4');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
		$params['V1'] = "";
		$params['V2'] = "";
		$params['V3'] = "";
		$params['V4'] = "";

		$data['qstr'] = "?P";
		
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];
		}

		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];
		}
		
		
		if(!empty($data['str']['v1'])){
			$params['V1'] = $data['str']['v1'];
			$data['qstr'] .= "&v1=".$data['str']['v1'];
		}
		if(!empty($data['str']['v2'])){
			$params['V2'] = $data['str']['v2'];
			$data['qstr'] .= "&v2=".$data['str']['v2'];
		}
		if(!empty($data['str']['v3'])){
			$params['V3'] = $data['str']['v3'];
			$data['qstr'] .= "&v3=".$data['str']['v3'];
		}
		if(!empty($data['str']['v4'])){
			$params['V4'] = $data['str']['v4'];
			$data['qstr'] .= "&v4=".$data['str']['v4'];
		}


		$params['an'] = "an3"; //재생재고내역인경우


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
		$data['List'] = $this->act_model->act_a5_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->act_a5_cut($params);

		

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

		$this->load->view('/act/an3',$data);

	}



	/* 재고조정 **/
	public function am3()
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['v1'] = $this->input->get('v1');
		$data['str']['v2'] = $this->input->get('v2');
		$data['str']['v3'] = $this->input->get('v3');
		$data['str']['v4'] = $this->input->get('v4');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
		$params['V1'] = "";
		$params['V2'] = "";
		$params['V3'] = "";
		$params['V4'] = "";

		$data['qstr'] = "?P";
		
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];
		}

		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];
		}
		
		
		if(!empty($data['str']['v1'])){
			$params['V1'] = $data['str']['v1'];
			$data['qstr'] .= "&v1=".$data['str']['v1'];
		}
		if(!empty($data['str']['v2'])){
			$params['V2'] = $data['str']['v2'];
			$data['qstr'] .= "&v2=".$data['str']['v2'];
		}
		if(!empty($data['str']['v3'])){
			$params['V3'] = $data['str']['v3'];
			$data['qstr'] .= "&v3=".$data['str']['v3'];
		}
		if(!empty($data['str']['v4'])){
			$params['V4'] = $data['str']['v4'];
			$data['qstr'] .= "&v4=".$data['str']['v4'];
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
		$data['List'] = $this->act_model->act_an2_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->act_an2_cut($params);

		

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

		$this->load->view('/act/an4',$data);

	}

	
	

	/* 출고등록 **/
	public function am4()
	{
		check_pageLevel();

		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['v1'] = $this->input->get('v1');
		$data['str']['v2'] = $this->input->get('v2');
		$data['str']['v3'] = $this->input->get('v3');
		$data['str']['v4'] = $this->input->get('v4');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
		$params['V1'] = "";
		$params['V2'] = "";
		$params['V3'] = "";
		$params['V4'] = "";

		$data['qstr'] = "?P";
		
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];
		}

		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];
		}
		
		
		if(!empty($data['str']['v1'])){
			$params['V1'] = $data['str']['v1'];
			$data['qstr'] .= "&v1=".$data['str']['v1'];
		}
		if(!empty($data['str']['v2'])){
			$params['V2'] = $data['str']['v2'];
			$data['qstr'] .= "&v2=".$data['str']['v2'];
		}
		if(!empty($data['str']['v3'])){
			$params['V3'] = $data['str']['v3'];
			$data['qstr'] .= "&v3=".$data['str']['v3'];
		}
		if(!empty($data['str']['v4'])){
			$params['V4'] = $data['str']['v4'];
			$data['qstr'] .= "&v4=".$data['str']['v4'];
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
		$data['List'] = $this->amt_model->act_am4_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->amt_model->act_am4_cut($params);

		

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

		$this->load->view('/amt/am4',$data);

	}

	public function ajax_am4_listupdate()
	{
		$params = array(
			'ACT_IDX' => $this->input->post("actidx"),
			'XDATE'   => $this->input->post("xdate"),
			'QTY'     => $this->input->post("outqty")
		);
		$data = $this->amt_model->ajax_am4_listupdate($params);
		echo json_encode($data);
	}



	/* 기간별/업체별 출고내역 **/
	public function am5()
	{
		check_pageLevel();
	}

	/* 클레임등록 **/
	public function am6()
	{
		check_pageLevel();
	}




	public function ajax_componentNum_form()
	{
		$data['title'] = "자재입고관리";
		$data['CUST'] = $this->main_model->get_custlist();
		$data['COMP'] = $this->main_model->ajax_component_select();
		
		if($this->input->post("mode") == "mod"){
			$data['INFO'] = $this->amt_model->ajax_componentNum_form($this->input->post("idx"));
		}

		$this->load->view('/amt/ajax_componentNum',$data);
	}


	public function ajax_component_set_qty()
	{
		$params['MOD'] = $this->input->post("mod");
		$params['IDX'] = $this->input->post("component"); //t_component IDX
		$params['BIZ_IDX'] = $this->input->post("BIZ_IDX");
		$params['IN_QTY'] = $this->input->post("IN_QTY");
		$params['TRANS_DATE'] = $this->input->post("TRANS_DATE");
		$params['REMARK'] = $this->input->post("REMARK");

		$params['MIDX'] = $this->input->post("IDX");

		$num = $this->amt_model->ajax_component_set_qty($params);

		if($num > 0){
			$data['status'] = "ok";
			$data['msg'] = "자재수량을 등록되었습니다.";
		}else{
			$data['status'] = "";
			$data['msg'] = "자재수량등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);

	}


	



}