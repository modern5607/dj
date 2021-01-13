<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SYS extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->helper('test'); //삭제금지 페이지별 권한체크
		$this->load->model(array('sys_model'));
		
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
	
	


	public function menu() 
	{
		check_pageLevel();

		$data['list'] = $this->sys_model->get_menu_list();
		$this->load->view('/sys/menu_write',$data);
	}

	public function user() 
	{
		check_pageLevel();

		$data['str'] = array(); //검색어관련
		$data['str']['mid'] = $this->input->get('mid'); //MEMBER ID
		$data['str']['mname'] = $this->input->get('mname'); //MEMBER ID
		$data['str']['level'] = $this->input->get('level'); //LEVEL
		
		$params['ID'] = "";
		$params['NAME'] = "";
		$params['LEVEL'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['mid'])){
			$params['ID'] = $data['str']['mid'];
			$data['qstr'] .= "&mid=".$data['str']['mid'];
		}
		if(!empty($data['str']['mname'])){
			$params['NAME'] = $data['str']['mname'];
			$data['qstr'] .= "&mname=".$data['str']['mname'];
		}
		if(!empty($data['str']['level'])){
			$params['LEVEL'] = $data['str']['level'];
			$data['qstr'] .= "&level=".$data['str']['level'];
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
		

		$data['title'] = "사용자등록";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		
		$data['userList'] = $this->sys_model->get_user_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->sys_model->get_user_cut($params);
		
		

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
		$this->load->view('/sys/user',$data);
	}

	public function level() 
	{
		check_pageLevel();
		
		$data['str'] = array(); //검색어관련
		$data['str']['mid'] = $this->input->get('mid'); //MEMBER ID
		$data['str']['mname'] = $this->input->get('mname'); //MEMBER ID
		$data['str']['level'] = $this->input->get('level'); //LEVEL
		
		$params['ID'] = "";
		$params['NAME'] = "";
		$params['LEVEL'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['mid'])){
			$params['ID'] = $data['str']['mid'];
			$data['qstr'] .= "&mid=".$data['str']['mid'];
		}
		if(!empty($data['str']['mname'])){
			$params['NAME'] = $data['str']['mname'];
			$data['qstr'] .= "&mname=".$data['str']['mname'];
		}
		if(!empty($data['str']['level'])){
			$params['LEVEL'] = $data['str']['level'];
			$data['qstr'] .= "&level=".$data['str']['level'];
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
		

		$data['title'] = "사용자 권한등록";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		
		$data['userList'] = $this->sys_model->get_user_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->sys_model->get_user_cut($params);
		
		

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

		$this->load->view('/sys/level',$data);
	}

	public function version() 
	{
		check_pageLevel();


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




		$data['verList'] = $this->sys_model->get_ver_list($start,$config['per_page']);

		$this->data['cnt'] = $this->sys_model->get_ver_cut();

		
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

		$this->load->view('/sys/version',$data);
	}


	/* 회원가입 */
	public function member_formUpdate()
	{
		$IDX = "";
		$dateTime = date("Y-m-d H:i:s",time());
		if(!empty($this->input->post("mod"))){ //수정인경우
			
			$params = array(
				
				'NAME'     => trim($this->input->post("NAME")),
				'NO'       => trim($this->input->post("NO")),
				'FIRSTDAY' => trim($this->input->post("FIRSTDAY")),
				'LEVEL'    => $this->input->post("LEVEL"),
				'STATE'    => $this->input->post("STATE"),
				'PART'     => trim($this->input->post("PART")),
				'TEL'      => trim($this->input->post("TEL")),
				'HP'       => trim($this->input->post("HP")),
				'JNUMBER'  => trim($this->input->post("JNUMBER")),
				'BLOOD'    => trim($this->input->post("BLOOD")),
				'SCHOOL'   => trim($this->input->post("SCHOOL")),
				'FAMILY'   => trim($this->input->post("FAMILY")),
				'EXPERIENCE' => trim($this->input->post("EXPERIENCE")),
				'LICENSE'  => trim($this->input->post("LICENSE")),
				'ARMY'     => trim($this->input->post("ARMY")),
				'IP'       => trim($this->input->post("IP")),
				'REGDATE'  => trim($this->input->post("REGDATE")),
				'EMAIL'    => trim($this->input->post("EMAIL")),
				'ADDR2'    => trim($this->input->post("ADDR2")),
				'MARRY'    => trim($this->input->post("MARRY")),
				'UPDATE_ID'=> $this->session->userdata('user_name'),
				'UPDATE_DATE' => $dateTime
			);
			
			if(!empty($this->input->post("PWD"))){
				$params['PWD'] = password_hash(trim($this->input->post("PWD")),PASSWORD_BCRYPT);
			}

			$IDX = $this->input->post("IDX");
			$text = "수정";
		
		}else{
			$params = array(
				'ID'       => trim($this->input->post("ID")),
				'PWD'      => trim($this->input->post("PWD")),
				'NAME'     => trim($this->input->post("NAME")),
				'NO'       => trim($this->input->post("NO")),
				'FIRSTDAY' => trim($this->input->post("FIRSTDAY")),
				'LEVEL'    => $this->input->post("LEVEL"),
				'STATE'    => $this->input->post("STATE"),
				'PART'     => trim($this->input->post("PART")),
				'TEL'      => trim($this->input->post("TEL")),
				'HP'       => trim($this->input->post("HP")),
				'JNUMBER'  => trim($this->input->post("JNUMBER")),
				'BLOOD'    => trim($this->input->post("BLOOD")),
				'SCHOOL'   => trim($this->input->post("SCHOOL")),
				'FAMILY'   => trim($this->input->post("FAMILY")),
				'EXPERIENCE' => trim($this->input->post("EXPERIENCE")),
				'LICENSE'  => trim($this->input->post("LICENSE")),
				'ARMY'     => trim($this->input->post("ARMY")),
				'IP'       => trim($this->input->post("IP")),
				'REGDATE'  => trim($this->input->post("REGDATE")),
				'EMAIL'    => trim($this->input->post("EMAIL")),
				'ADDR2'    => trim($this->input->post("ADDR2")),
				'MARRY'    => trim($this->input->post("MARRY")),
				'PWD'      => password_hash(trim($this->input->post("PWD")),PASSWORD_BCRYPT),
				'INSERT_ID' => $this->session->userdata('user_name'),
				'INSERT_DATE' => $dateTime
			);
			$text = "등록";
		}
		
		

		$data = $this->sys_model->member_formupdate($params,$IDX);
		
		if($data != ""){
			alert($text."되었습니다",base_url('SYS/user')."/".$data);
		}

	}






	public function menu_update()
	{
		$mode = $this->input->post("IDX");
		$params = array(
			'MENU_GROUP' => $this->input->post("MENU_GROUP"),
			'MENUID' => $this->input->post("MENUID"),
			'MENUNAME' => $this->input->post("MENUNAME"),
			'REMARK' => $this->input->post("REMARK"),
			'LEVEL' => $this->input->post("LEVEL"),
			'USE_YN' => $this->input->post("USE_YN"),
			'INSERT_ID' => $this->session->userdata('user_name'),
			'INSERT_DATE' => date("Y-m-d H:i:s",time())
		);

		$text = "등록";

		if(!empty($mode)){
			unset($params['INSERT_ID']);
			unset($params['INSERT_DATE']);
			$params['UPDATE_ID'] = $this->session->userdata('user_name');
			$params['UPDATE_DATE'] = date("Y-m-d H:i:s",time());
			$text = "수정";
		}

		$data = $this->sys_model->set_menu_insert($params,$mode);
		if($data > 0){
			alert('메뉴가 '.$text.'되었습니다.',base_url('SYS/menu'));
		}
	}
	


	public function upload_ver_form()
	{
		$MIDX = $this->input->post("MIDX");
		$param['VER_NO'] = $this->input->post("VER_NO");
		$param['VER_REMARK'] = $this->input->post("VER_REMARK");
		$param['INSERT_ID'] = $this->session->userdata('user_id');
		$param['INSERT_DATE'] = date("Y-m-d H:i:s",time());
		
		$data = $this->sys_model->upload_ver_form($param,$MIDX);
		
		if($data > 0){
			alert('등록되었습니다',base_url('SYS/version'));
		}
	}




	public function ajax_set_memberinfo()
	{
		$mode = $this->input->post("mode");
		$idx  = $this->input->post("idx");

		$data = array();
		if(!empty($idx)){
			$data['memInfo'] = $this->sys_model->get_member_info($idx);
		}
		
		$this->load->view('/sys/ajax_memberform',$data);
	}


	public function ajax_savelevel_update()
	{
		$param['idx'] = $this->input->post('idx');
		$param['level'] = $this->input->post('sqty');
		
		$data = $this->sys_model->ajax_savelevel_update($param);
		echo $data;

	}

	public function delete_ver_form()
	{
		$param['IDX'] = $this->input->post("IDX");
		$data = $this->sys_model->delete_ver_form($param);
		echo $data;
	}


	public function modified_ver_form()
	{
		$param['IDX'] = $this->input->post("IDX");
		$data = $this->sys_model->modified_ver_form($param);
		echo json_encode($data);
	}


}