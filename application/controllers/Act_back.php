<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Act extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->model(array('act_model','bom_model'));

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


	public function temp()
	{
		$data = $this->act_model->get_temp_list();
		$this->load->view('/act/temp',$data);
	}


	public function temp_update()
	{
		$mode = $this->input->post('mode');

		$user_name = $this->session->userdata('user_name');

		if($mode == "update"){
			$res = $this->db->get('T_TEMP');
			foreach($res->result() as $row){
				
				$params['PLN_NO']     = '';
				$params['LOT_NO']     = $row->t_1;
				$params['BL_NO']      = $row->t_2;
				$params['MSAB']       = '';
				$params['ST_DATE']    = $row->t_3;
				$params['GJ_CODE']    = $row->t_9; //공정코드
				$params['QTY']        = $row->t_4; //생산수량
				
				$params['UNIT']       = $row->t_5; //단위
				$params['STATE']      = $row->t_6; //상태
				$params['PLN_QTY']    = $row->t_8; //지시수
				$params['GJ_QTY']     = $row->t_11; //공정지시수

				$params['SIZ_NO']     = $row->t_7; //사시즈NO
				$params['NAME']       = $row->t_10; //공정명
				$params['PT']         = ''; //제작소요시간
				$params['T_PT']       = ''; //전체제작소요시간
				$params['M_LINE']     = ''; //생산라인
				$params['CUSTOMER']   = ''; //거래처
				$params['STA_DATE']   = ''; //생산시작일
				$params['END_DATE']   = ''; //생산완료일
				$params['ACT_DATE']   = $row->t_13; //완료예정일
				$params['PLN_DATE']   = $row->t_14; //계획배포일
				$params['GJ_GB']      = ''; //공정구분
				$params['REMARK']     = ''; //비고
				$params['FINISH']     = ''; //완료여부
				$params['FINISH_DATE']= ''; //완료일
				$params['BAR_CODE']   = ''; //바코드
				$params['COL2']       = '';
				$params['COL3']       = '';
				$params['COL4']       = '';
				$params['INSERT_DATE']= date('Y-m-d H:i:s',time());
				$params['INSERT_ID']  = $user_name;

				//단위,상태,지시수,공정지시수 안들어가고 있음....

				$data = $this->act_model->ajax_temp_update($params);

			}
		}
	}


	public function index($idx=0)
	{
		
		$data['set']     = (!empty($this->input->get('set')))?$this->input->get('set'):"";
		$data['sdate']   = (!empty($this->input->get('sdate')))?$this->input->get('sdate'):"";
		$data['edate']   = (!empty($this->input->get('edate')))?$this->input->get('edate'):"";
		$data['pageNum'] = (!empty($this->input->get('pageNum')))?$this->input->get('pageNum'):0;
		$data['perpage'] = (!empty($this->input->get('perpage')))?$this->input->get('perpage'):20;
		
		
		$params['set']     = $data['set'];
		$params['sdate']   = $data['sdate'];
		$params['edate']   = $data['edate'];

		
		
		$data['qstr'] = "?set=".$data['set']."&sdate=".$data['sdate']."&edate=".$data['edate'];
		if(!empty($this->input->get('searchx')) && !empty($this->input->get('perpage'))){
			$data['qstr'] .= "&perpage=".$data['perpage'];
		}
		



		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        //$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $data['pageNum'];

		

		$data['title'] = "수주정보";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);

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

		$this->load->view('/act/actlist',$data);
	}




	public function a1($idx=0)
	{
		$data['set']     = (!empty($this->input->get('set')))?$this->input->get('set'):"";
		$data['sdate']   = (!empty($this->input->get('sdate')))?$this->input->get('sdate'):"";
		$data['edate']   = (!empty($this->input->get('edate')))?$this->input->get('edate'):"";
		$data['pageNum'] = (!empty($this->input->get('pageNum')))?$this->input->get('pageNum'):0;
		$data['perpage'] = (!empty($this->input->get('perpage')))?$this->input->get('perpage'):20;
		
		
		$params['set']     = $data['set'];
		$params['sdate']   = $data['sdate'];
		$params['edate']   = $data['edate'];

		
		
		$data['qstr'] = "?set=".$data['set']."&sdate=".$data['sdate']."&edate=".$data['edate'];
		if(!empty($this->input->get('searchx')) && !empty($this->input->get('perpage'))){
			$data['qstr'] .= "&perpage=".$data['perpage'];
		}
		



		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        //$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $data['pageNum'];

		

		$data['title'] = "수주현황";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);

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
		$this->load->view('/act/actlist_a1',$data);
	}


	public function a2($idx=0)
	{
		$data['set']     = (!empty($this->input->get('set')))?$this->input->get('set'):"";
		$data['sdate']   = (!empty($this->input->get('sdate')))?$this->input->get('sdate'):"";
		$data['edate']   = (!empty($this->input->get('edate')))?$this->input->get('edate'):"";
		$data['pageNum'] = (!empty($this->input->get('pageNum')))?$this->input->get('pageNum'):0;
		$data['perpage'] = (!empty($this->input->get('perpage')))?$this->input->get('perpage'):20;
		
		
		$params['set']     = $data['set'];
		$params['sdate']   = $data['sdate'];
		$params['edate']   = $data['edate'];

		
		
		$data['qstr'] = "?set=".$data['set']."&sdate=".$data['sdate']."&edate=".$data['edate'];
		if(!empty($this->input->get('searchx')) && !empty($this->input->get('perpage'))){
			$data['qstr'] .= "&perpage=".$data['perpage'];
		}
		



		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        //$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $data['pageNum'];

		

		$data['title'] = "수주대비 진행현황";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);

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
		$this->load->view('/act/actlist_a2',$data);
	}


	public function a3($idx=0)
	{
		$data['set']     = (!empty($this->input->get('set')))?$this->input->get('set'):"";
		$data['sdate']   = (!empty($this->input->get('sdate')))?$this->input->get('sdate'):"";
		$data['edate']   = (!empty($this->input->get('edate')))?$this->input->get('edate'):"";
		$data['pageNum'] = (!empty($this->input->get('pageNum')))?$this->input->get('pageNum'):0;
		$data['perpage'] = (!empty($this->input->get('perpage')))?$this->input->get('perpage'):20;
		
		
		$params['set']     = $data['set'];
		$params['sdate']   = $data['sdate'];
		$params['edate']   = $data['edate'];

		
		
		$data['qstr'] = "?set=".$data['set']."&sdate=".$data['sdate']."&edate=".$data['edate'];
		if(!empty($this->input->get('searchx')) && !empty($this->input->get('perpage'))){
			$data['qstr'] .= "&perpage=".$data['perpage'];
		}
		



		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        //$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $data['pageNum'];

		

		$data['title'] = "납기지연예상";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);

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
		$this->load->view('/act/actlist_a3',$data);
	}



}