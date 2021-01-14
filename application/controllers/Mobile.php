<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mobile extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->model(array('act_model','main_model'));

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
				//if(isset($user_id) && $user_id != ""){
					
				$this->load->view('/layout/m_header',$this->data);
				call_user_func_array(array($this,$method), $params);
				$this->load->view('/layout/m_tail');

				//}else{

				//	alert('로그인이 필요합니다.',base_url('register/login'));

				//}

            } else {
                show_404();
            }

        }
		
	}

	public function m1()
	{
		$data['title'] = "일일작업일지";
		
		
		$data['str'] = array(); //검색어관련
		//$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE
		$data['M_TITLE'] = "전체";
		
		$params['GJ_GB'] = "SMT";

		$data['qstr'] = "?P";
		/*
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
		}*/
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
			
			$data['M_TITLE'] = $params['M_LINE'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):15;
		

		
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
        //$start = $config['per_page'] * ($pageNum - 1);
		
		$start = $pageNum;
		$data['pageNum'] = $start;
		
		
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['actList']  = $this->act_model->get_actplan_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->act_model->get_actplan_cut($params);

		
		
		$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");
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

		$this->load->view('/mobile/m1',$data);
	}
	

}

?>
