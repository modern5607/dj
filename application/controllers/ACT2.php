<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ACT2 extends CI_Controller {

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


	//성형실적
	public function a8($date='')
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['component'] = $this->input->get('component');
		$data['str']['component_nm'] = $this->input->get('component_nm');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
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
		$params['GJGB'] = "SH";
		

		$data['List'] = $this->act_model->item_trans_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->item_trans_cnt($params);
		if(empty($this->input->get('n'))){
			if($date != ""){
				$data['RList'] = $this->act_model->item_trans_numlist($date,$params);
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

		$this->load->view('/act/a8',$data);
	}

	//성형실적현황
	public function a8_1()
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
		
		
		$data['title'] = "실적현황";
		$data['List'] = $this->act_model->act_a81_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->act_a81_cut($params);

		

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

		$this->load->view('/act/a8_1',$data);
	}



	//정형실적
	public function a9($date='')
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['component'] = $this->input->get('component');
		$data['str']['component_nm'] = $this->input->get('component_nm');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
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
		$params['GJGB'] = "JH";

		$params['BK'] = '';
		$data['BK'] = $params['BK']; 

		$data['List'] = $this->act_model->item_trans_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->item_trans_cnt($params);
		if(empty($this->input->get('n'))){
			if($date != ""){
				$data['RList'] = $this->act_model->item_trans_numlist($date,$params);
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

		$this->load->view('/act/a9',$data);
	}


	//정형실적
	public function a9_1($date='')
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['component'] = $this->input->get('component');
		$data['str']['component_nm'] = $this->input->get('component_nm');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
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
		$params['GJGB'] = "JH";


		$params['BK'] = 1;
		$data['BK'] = $params['BK']; 



		$data['List'] = $this->act_model->item_trans_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->item_trans_cnt($params);
		if(empty($this->input->get('n'))){
			if($date != ""){
				$data['RList'] = $this->act_model->item_trans_numlist($date,$params);
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

		$this->load->view('/act/a9',$data);
	}

	

	//정형실적현황
	public function a9_2()
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
		
		
		$data['title'] = "실적현황";
		$data['List'] = $this->act_model->act_a92_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->act_a92_cut($params);

		

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

		$this->load->view('/act/a9_2',$data);
	}


	//시유실적
	public function a10($code = '')
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
		
		//$data['title'] = "수주관리";
		
		$data['List']   = $this->act_model->page_a10_left_list($params,$start,$config['per_page']);
		$this->data['cnt']  = $this->act_model->page_a10_left_count($params);
		
		
		if(empty($this->input->get('n'))){
			//상단정보
			//$data['headInfo']  = $this->pln_model->get_pln_info($hidx);
			if($code != ""){
				$data['detail'] = $this->act_model->page_a10_right_list($code,$params);
			}
			
		}else{
			$data['HIDX'] = null;
		}
		
		$data['CODE'] = $code;
		$data['SERIES'] = $this->main_model->get_seriesh_select();
		$data['BIZ'] = $this->main_model->get_custlist();


		
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
		$this->load->view('/act/a10',$data);
	}

	
	//선별작업실적등록
	public function a10_1($date= '')
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['component'] = $this->input->get('component');
		$data['str']['component_nm'] = $this->input->get('component_nm');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
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
		$params['GJGB'] = "CU";

		$params['BK'] = '';
		$data['BK'] = $params['BK']; 

		$data['List'] = $this->act_model->get_a10_1_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_a10_1_left_count($params);
		if(empty($this->input->get('n'))){
			if($date != ""){
				$data['RList'] = $this->act_model->get_a10_1_right_list($date,$params);
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

		$this->load->view('/act/a10_1',$data);

	}

	
	//시유실적현황
	public function a10_2()
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
		
		
		$data['title'] = "실적현황";
		$data['List'] = $this->act_model->act_a102_list($params,$start,$config['per_page']);
		$this->data['cnt'] = 0;//$this->act_model->act_a102_cut($params);

		

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

		$this->load->view('/act/a10_2',$data);

	}



	//선별작업실적등록
	public function a11($oidx = '')
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
		
		$data['OIDX'] = $oidx;
		if($oidx != ""){
			$data['XXX_QTY'] = $this->act_model->get_inventory_info($oidx);
		}
		
		$data['List'] = $this->act_model->get_inventory_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_inventory_count($params);
		
		$data['BIZ'] = $this->main_model->get_custlist();
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


		$this->load->view('/act/a11',$data);

	}



	//선별작업실적2
	public function a11_1($date='')
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$data['str']['component'] = $this->input->get('component');
		$data['str']['component_nm'] = $this->input->get('component_nm');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		
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
		$params['GJGB'] = "SH";
		

		$data['List'] = $this->act_model->get_a11_1_list_left($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_a11_1_count($params);
		if(empty($this->input->get('n'))){
			if($date != ""){
				$data['RList'] = $this->act_model->get_a11_1_list_right($date,$params);
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

		$this->load->view('/act/a11_1',$data);
	}



	//선별작업실적현황
	public function a11_2()
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
		
		
		$data['title'] = "실적현황";
		$data['List'] = $this->act_model->act_a11_2_list($params,$start,$config['per_page']);
		$this->data['cnt'] = 0;//$this->act_model->act_a102_cut($params);
		
		

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

		$this->load->view('/act/a11_2',$data);

	}



}