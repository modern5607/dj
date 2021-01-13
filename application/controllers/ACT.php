<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ACT extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->helper('test');
		$this->load->model(array('pln_model','main_model','act_model'));

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




	public function a1() //보류
	{
		check_pageLevel();
		
		
		



	}

	
	//건조(재고)현황
	public function a2()
	{
		check_pageLevel();

		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['v1'] = $this->input->get('v1');
		$data['str']['v4'] = $this->input->get('v4');
		$data['str']['v3'] = $this->input->get('v3');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
		$params['V1'] = "";
		$params['V4'] = "";
		$params['V3'] = "";

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
		if(!empty($data['str']['v4'])){
			$params['V4'] = $data['str']['v4'];
			$data['qstr'] .= "&v2=".$data['str']['v4'];
		}
		if(!empty($data['str']['v3'])){
			$params['V3'] = $data['str']['v3'];
			$data['qstr'] .= "&v3=".$data['str']['v3'];
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
		$data['List'] = $this->act_model->act_a2_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->act_a2_cut($params);

		

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

		$this->load->view('/act/a2',$data);

	}


	public function a3()
	{
		check_pageLevel();
	}


	public function an2()
	{
		check_pageLevel();
	}
	
	/* 선별작업일지 */
	public function an3()
	{
		check_pageLevel();
		$data['title'] = "";
		$this->load->view('/act/an3',$data); //뷰페이지 중복
	}

	public function an4()
	{
		check_pageLevel();


		
	}

	public function an5()
	{
		check_pageLevel();
	}


	
	/* 공정별진행현황**/
	public function a4()
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['v1'] = $this->input->get('v1');
		$data['str']['v2'] = $this->input->get('v2');
		$data['str']['v3'] = $this->input->get('v3');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
		$params['V1'] = "";
		$params['V2'] = "";
		$params['V3'] = "";

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
		$data['List'] = $this->act_model->act_a4_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->act_a4_cut($params);

		

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

		$this->load->view('/act/a4',$data);
		



	}



	/* 기간별생산실적**/
	public function a5()
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['v1'] = $this->input->get('v1');
		$data['str']['v2'] = $this->input->get('v2');
		$data['str']['v3'] = $this->input->get('v3');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
		$params['V1'] = "";
		$params['V2'] = "";
		$params['V3'] = "";

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

		$params['an'] = "a5";

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

		$this->load->view('/act/a5',$data);
		



	}





	/* 공정별수율관리 **/
	public function a6()
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['v1'] = $this->input->get('v1');
		$data['str']['v2'] = $this->input->get('v2');
		$data['str']['v3'] = $this->input->get('v3');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
		$params['V1'] = "";
		$params['V2'] = "";
		$params['V3'] = "";

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

		$params['an'] = "a6";

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

		$this->load->view('/act/a6',$data);

	}




	/* 공정별불량관리 **/
	public function a7()
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		//$data['str']['v1'] = $this->input->get('v1');
		//$data['str']['v2'] = $this->input->get('v2');
		//$data['str']['v3'] = $this->input->get('v3');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
		//$params['V1'] = "";
		//$params['V2'] = "";
		//$params['V3'] = "";

		$data['qstr'] = "?P";
		
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];
		}

		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];
		}
		
		/*
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
		}*/

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
		$data['List'] = $this->act_model->act_a7_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->act_a7_cut($params);

		

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

		$this->load->view('/act/a7',$data);

	}
	


	/* 수주대비 출고내역 **/
	public function an1()
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

		$params['an'] = "an1";

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

		$this->load->view('/act/an1',$data);

	}


	




	



	public function ajax_itemNum_form()
	{
		
		$data['mode'] = $this->input->post("mode");
		$data['SERIES'] = $this->main_model->get_seriesh_select();
		$data['HIDX'] = $this->input->post("hidx");
		

		if($this->input->post("mode") == "mod"){
			//$data['data'] = $this->pln_model->get_pln_info($this->input->post("IDX"));
		}
		
		if($this->input->post("type") == "a9" || $this->input->post("type") == "a9_1"){
			$data['title'] = "정형실적등록";
			
			if($this->input->post("type") == "a9_1"){
				$data['BK'] = 1;
				$data['title'] = $data['title']."-BK";
				
			}
			return $this->load->view('/ajax/ajax_items_trans_a9_form',$data);
		}else{
			$data['title'] = "성형실적등록";
			return $this->load->view('/ajax/ajax_items_trans_form',$data);
		}
	}


	public function ajax_itemsindex_pop()
	{
		$params['s1'] = $this->input->post("s1");
		
		$data = $this->act_model->ajax_itemv_pop($params);
		echo json_encode($data);
	}


	public function ajax_act_items_trans_insert()
	{
		
		foreach($this->input->post("QTY") as $key => $qty){
			if($qty == ""){
				continue;
			}else{
				$params['QTY'][$key] = $qty;
				$params['ITEM_IDX'][$key] = $this->input->post("ITEM_IDX")[$key];
				//$params['ITEM_NM'][$key] = $this->input->post("ITEM_NM")[$key];
				//$params['SERIESD_IDX'][$key] = $this->input->post("SERIESD_IDX")[$key];
				$params['REMARK'][$key] = $this->input->post("REMARK")[$key];
				//$params['H_IDX'][$key] = $this->input->post("H_IDX")[$key];
			}
		}
		
		$num = $this->act_model->ajax_item_trans_insert($params);
		
		if($num > 0){
			$data['status'] = "ok";
			$data['msg'] = "실적이 등록되었습니다.";
		}else{
			$data['status'] = "";
			$data['msg'] = "실적 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}


	public function ajax_act_a9_items_trans_insert()
	{
		
		foreach($this->input->post("QTY") as $key => $qty){
			if($qty == ""){
				continue;
			}else{
				$params['QTY'][$key] = $qty;
				$params['ITEM_IDX'][$key] = $this->input->post("ITEM_IDX")[$key];
				//$params['ITEM_NM'][$key] = $this->input->post("ITEM_NM")[$key];
				//$params['SERIESD_IDX'][$key] = $this->input->post("SERIESD_IDX")[$key];
				$params['REMARK'][$key] = $this->input->post("REMARK")[$key];
				//$params['H_IDX'][$key] = $this->input->post("H_IDX")[$key];
			}
		}

		$params['BK'] = $this->input->post("BK");
		
		$num = $this->act_model->ajax_act_a9_items_trans_insert($params);
		
		if($num > 0){
			$data['status'] = "ok";
			$data['msg'] = "실적이 등록되었습니다.";
		}else{
			$data['status'] = "";
			$data['msg'] = "실적 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}


	public function ajax_del_items_trans()
	{
		$idx = $this->input->post("idx");
		$num = $this->act_model->ajax_del_items_trans($idx);
		if($num > 0){
			$data['status'] = "ok";
			$data['msg'] = "삭제되었습니다.";
		}else{
			$data['status'] = "no";
			$data['msg'] = "삭제에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);

	}

	public function ajax_del_items_trans_a9()
	{
		$idx = $this->input->post("idx");
		$num = $this->act_model->ajax_del_items_trans_a9($idx);
		if($num > 0){
			$data['status'] = "ok";
			$data['msg'] = "삭제되었습니다.";
		}else{
			$data['status'] = "no";
			$data['msg'] = "삭제에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);

	}


	public function ajax_act_a10_insert()
	{
		foreach($this->input->post("IN_QTY") as $key => $qty){
			if($qty == ""){
				continue;
			}else{
				$params['AD_IDX'][$key] = $this->input->post("AD_IDX")[$key];
				$params['IN_QTY'][$key] = $qty;
				$params['ACT_IDX'][$key] = $this->input->post("ACT_IDX")[$key];
				$params['CU_DATE'][$key] = $this->input->post("XDATE");
				$params['ITEMS_IDX'][$key] = $this->input->post("ITEMS_IDX")[$key];
				$params['SERIESD_IDX'][$key] = $this->input->post("SERIESD_IDX")[$key];
			}
		}
		
		$num = $this->act_model->ajax_act_a10_insert($params);
		
		if($num > 0){
			$data['status'] = "ok";
			$data['msg'] = "실적이 등록되었습니다.";
		}else{
			$data['status'] = "";
			$data['msg'] = "실적 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}



	public function ajax_a10_1_qty()
	{
		$param['org'] = $this->input->post("org");
		$param['qty'] = $this->input->post("qty");
		$param['idx'] = $this->input->post("idx");
		
		$num = $this->act_model->ajax_a10_1_qty($param);

		if($num > 0){
			$data['status'] = "ok";
			$data['msg'] = "수량이 변경되었습니다.";
		}else{
			$data['status'] = "error";
			$data['msg'] = "수량 변경에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);

	}


	public function ajax_del_a10_1()
	{
		$idx = $this->input->post("idx");
		$num = $this->act_model->ajax_del_a10_1($idx);
		if($num > 0){
			$data['status'] = "ok";
			$data['msg'] = "삭제되었습니다.";
		}else{
			$data['status'] = "no";
			$data['msg'] = "삭제에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}



	public function form_a11_update()
	{
		
		$param['SB_DATE'] = $this->input->post("A1");
		$param['1_QTY']   = $this->input->post("A2");
		$param['2_QTY']	  = $this->input->post("A3");
		$param['3_QTY']   = $this->input->post("A4");
		$param['4_QTY']   = $this->input->post("A5");
		$param['REMARK']  = $this->input->post("A6");
		$param['IDX']     = $this->input->post("OIDX");
		
		
		
		if($_FILES['setfile']['name'][0] != ""){

			$config['upload_path']          = './uploads/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 0;
			$config['max_width']            = 0;
			$config['max_height']           = 0;
			$config['encrypt_name']         = true;
			$config['remove_spaces']        = true;
			$config['file_ext_tolower']     = true;

			$this->load->library('upload', $config);

			foreach($_FILES['setfile']['name'] as $i=>$file){

				$_FILES['userfile']['name']     = $file;
				$_FILES['userfile']['type']     = $_FILES['setfile']['type'][$i];
				$_FILES['userfile']['tmp_name'] = $_FILES['setfile']['tmp_name'][$i];
				$_FILES['userfile']['error']    = $_FILES['setfile']['error'][$i];
				$_FILES['userfile']['size']     = $_FILES['setfile']['size'][$i];
				
				$this->upload->initialize($config);
				
				if ( ! $this->upload->do_upload("userfile"))
				{
						$error = array('error' => $this->upload->display_errors());
						
						//$this->load->view('upload_form', $error);
				}
				else
				{
						$data = $this->upload->data();
						$param['IMG'][$i] = $data['file_name'];

				}
				
			}

		}
		
		$data =	$this->act_model->form_a11_update($param);
		
		if($data > 0){
			alert("선별작업실적이 등록되었습니다.",base_url('ACT2/a11/'.$param['IDX']));
		}

		
	}


	public function ajax_a11_1_update()
	{
		$param['IDX'] = $this->input->post("idx");
		$param['QTY1'] = $this->input->post("qty1");
		$param['QTY2'] = $this->input->post("qty2");
		$param['QTY3'] = $this->input->post("qty3");
		$param['QTY4'] = $this->input->post("qty4");
		

		$data = $this->act_model->ajax_a11_1_update($param);
		echo $data;

	}


	public function ajax_a11_1_delete()
	{
		
		$data = $this->act_model->ajax_a11_1_delete($this->input->post("idx"));
		echo $data;
	}


	public function ajax_an3_listupdate()
	{
		$param['IDX'] = $this->input->post("idx");
		$param['VX']  = $this->input->post("vx");
		
		$data = $this->act_model->ajax_an3_listupdate($param);
		
		echo $data;
		
	}



	public function ajax_an4_listupdate()
	{
		$param['ITEM_IDX'] = $this->input->post("item");
		$param['SERIESD_IDX']  = $this->input->post("seriesd");
		$param['QTY'] = $this->input->post("stock");
		$param['REMARK'] = $this->input->post("cont");

		
		$data = $this->act_model->ajax_an4_listupdate($param);
		
		echo $data;
		
	}


	public function print_actpln()
	{
		$params = array();
		$data['List'] = $this->act_model->print_actpln($params);
		
		

		//이미지호출
		$data['IMAGE'] = $this->act_model->get_inventory_img();
		


		$this->load->view('/act/ajax_print',$data);
	}




}