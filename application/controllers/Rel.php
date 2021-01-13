<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rel extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->model(array('release_model','bom_model', 'main_model'));

		$this->data['siteTitle'] = $this->config->item('site_title');

		

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


	public function r1($idx=0)
	{
		$data['str'] = array(); //검색어관련
		$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['trans_date'] = $this->input->get('trans_date'); //TRANS_DATE
		/*
		$data['str']['customer'] = $this->input->get('customer'); //CUSTOMER
		$data['str']['pln1'] = $this->input->get('pln1'); //PLN_DATE
		$data['str']['pln2'] = $this->input->get('pln2'); //PLN_DATE
		$data['str']['insert1'] = $this->input->get('insert1'); //DATE
		$data['str']['insert2'] = $this->input->get('insert2'); //DATE
		*/
		
		$params['GJ_GB'] = "";
		$params['TRANS_DATE'] = "";
		
		/*$params['BL_NO'] = "";
		$params['CUSTOMER'] = "";
		$params['PLN1'] = "";
		$params['PLN2'] = "";
		$params['INSERT1'] = "";
		$params['INSERT2'] = "";*/

		$data['qstr'] = "?P";
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}
		
		if(!empty($data['str']['trans_date'])){
			$params['TRANS_DATE'] = $data['str']['trans_date'];
			$data['qstr'] .= "&trans_date=".$data['str']['trans_date'];
		}
		/*
		if(!empty($data['str']['customer'])){
			$params['CUSTOMER'] = $data['str']['customer'];
			$data['qstr'] .= "&customer=".$data['str']['customer'];
		}
		if(!empty($data['str']['pln1'])){
			$params['PLN1'] = $data['str']['pln1'];
			$data['qstr'] .= "&pln1=".$data['str']['pln1'];
		}
		if(!empty($data['str']['pln2'])){
			$params['PLN2'] = $data['str']['pln2'];
			$data['qstr'] .= "&pln2=".$data['str']['pln2'];
		}
		if(!empty($data['str']['insert1'])){
			$params['INSERT1'] = $data['str']['insert1'];
			$data['qstr'] .= "&insert1=".$data['str']['insert1'];
		}
		if(!empty($data['str']['insert2'])){
			$params['INSERT2'] = $data['str']['insert2'];
			$data['qstr'] .= "&insert2=".$data['str']['insert2'];
		}*/

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;
		$data['pageNum'] = $start;
		

		$data['title'] = "출고등록";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['relList']  = $this->release_model->get_release_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->release_model->get_release_cut($params);

		
		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");

		$data['idx'] = $idx;
		
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

		$this->load->view('/release/r1',$data);
		
	}

	
	/* 기간별/업체별 출고내역 */
	public function r2()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['cg_date'] = $this->input->get('cg_date'); //CG_DATE

		$params['GJ_GB'] = "";
		$params['CG_DATE'] = "";


		$data['qstr'] = "?P";
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}

		$data['qstr'] = "?P";
		if(!empty($data['str']['cg_date'])){
			$params['CG_DATE'] = $data['str']['cg_date'];
			$data['qstr'] .= "&cg_date=".$data['str']['cg_date'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;
		$data['pageNum'] = $start;

		

		$data['title'] = "기간별/업체별 출고내역";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['relList']  = $this->release_model->get_itemtrans_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->release_model->get_itemtrans_cut($params);

		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		
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

		$this->load->view('/release/r2',$data);
		
	}


	/* 제공품내역 */
	public function r3($idx=0)
	{
		$data['str'] = array(); //검색어관련
		$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['cg_date'] = $this->input->get('cg_date'); //CG_DATE

		$params['GJ_GB'] = "";
		$params['CG_DATE'] = "";


		$data['qstr'] = "?P";
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}

		$data['qstr'] = "?P";
		if(!empty($data['str']['cg_date'])){
			$params['CG_DATE'] = $data['str']['cg_date'];
			$data['qstr'] .= "&cg_date=".$data['str']['cg_date'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;
		$data['pageNum'] = $start;

		

		$data['title'] = "제공품내역";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['relList']  = $this->release_model->get_itemtrans_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->release_model->get_itemtrans_cut($params);

		$data['insertBomList'] = $this->bom_model->get_bom_list($idx);
		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");

		$data['idx'] = $idx;
		
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

		$this->load->view('/release/r3',$data);
		
	}

	
	/* 클래임등록 */
	public function r4($idx=0)
	{
		$data['str'] = array(); //검색어관련
		$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['cg_date'] = $this->input->get('cg_date'); //CG_DATE

		$params['GJ_GB'] = "";
		$params['CG_DATE'] = "";


		$data['qstr'] = "?P";
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}

		$data['qstr'] = "?P";
		if(!empty($data['str']['cg_date'])){
			$params['CG_DATE'] = $data['str']['cg_date'];
			$data['qstr'] .= "&cg_date=".$data['str']['cg_date'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;
		$data['pageNum'] = $start;

		

		$data['title'] = "클래임등록";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['relList']  = $this->release_model->get_itemtrans_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->release_model->get_itemtrans_cut($params);

		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");

		$data['idx'] = $idx;
		
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

		$this->load->view('/release/r4',$data);
		
	}



	/* 클래임내역조회 */
	public function r5($idx=0)
	{
		$data['str'] = array(); //검색어관련
		$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['cg_date'] = $this->input->get('cg_date'); //CG_DATE

		$params['GJ_GB'] = "";
		$params['CG_DATE'] = "";


		$data['qstr'] = "?P";
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}

		$data['qstr'] = "?P";
		if(!empty($data['str']['cg_date'])){
			$params['CG_DATE'] = $data['str']['cg_date'];
			$data['qstr'] .= "&cg_date=".$data['str']['cg_date'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;
		$data['pageNum'] = $start;

		

		$data['title'] = "클래임내역조회";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['relList']  = $this->release_model->get_itemtrans_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->release_model->get_itemtrans_cut($params);

		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");

		$data['idx'] = $idx;
		
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

		$this->load->view('/release/r5',$data);
		
	}


	public function ajax_trans_items()
	{
		$param['idx']     = $this->input->post("idx");
		$param['OUT_QTY'] = $this->input->post("qty");
		
		$data = $this->release_model->ajax_trans_items($param);

		echo json_encode($data);
		exit;

	}


	public function claimform_update()
	{
		$param['H_IDX']  = $this->input->post("idx");
		$param['REMARK'] = $this->input->post("REMARK");

		$data = $this->release_model->claimform_update($param);
		if($data > 0){
			alert('클래임이 등록되었습니다.',base_url('rel/r4'));
		}
	}


	public function ajax_return_form()
	{
		$param['idx']     = $this->input->post("idx");
		$data['title'] = "반품처리";
		$data['list'] = $this->release_model->ajax_return_form($param);

		return $this->load->view('/release/ajax_return',$data);
	}

	//T_ITEMS_TRANS 반품갯수 추가
	public function ajax_returnNum_form()
	{
		$params['idx'] = $this->input->post("idx");
		$params['rNum'] = $this->input->post("rNum");
		$params['userName'] = $this->session->userdata('user_name');

		$data = $this->release_model->ajax_returnNum_form($params);
		echo json_encode($data);
		exit;
	}



	public function rview()
	{

		$data['title'] = "생산현황판";
		$data['leftData'] = $this->release_model->get_left_data();
		$data['toDate'] = $this->release_model->get_todate_num();
		$data['viewList'] = $this->release_model->get_rel_view();


		return $this->load->view('/release/rel_view',$data);
	}

	public function ajax_acttime_update()
	{
		$param['ACT_TIME'] = $this->input->post("time");
		$param['M_LINE'] = $this->input->post("mline");
		$data = $this->release_model->ajax_acttime_update($param);
		echo $data;
	}

}