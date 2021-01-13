<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		$this->data['userLevel'] = $this->session->userdata('user_level');
		
		$this->load->model('register_model');

		
		$this->data['siteTitle'] = "로그인";
		

	}

	public function _remap($method, $params = array())
	{
		if($this->input->is_ajax_request()){
            if( method_exists($this, $method) ){
                call_user_func_array(array($this,$method), $params);
            }
        }else{ //ajax가 아니면

			$user_id = $this->session->userdata('user_id');
			$this->data['member_name'] = $this->session->userdata('user_name');

			if(isset($user_id) && $user_id != ""){
				
				$this->load->view('/layout/header',$this->data);
				call_user_func_array(array($this,$method), $params);
				$this->load->view('/layout/tail');

			}else{

				alert('로그인이 필요합니다.',base_url('register/login'));

			}
        }
		
	}


	/* 로그인 */
	public function login()
	{
		$user_id = $this->session->userdata('user_id');
		if($user_id){
			redirect(base_url('intro'));
		}
		$data = "";
		$this->load->view('login',$data);

	}


	/* 로그인 */
	public function logout()
	{
		$user_id = $this->session->userdata('user_id');
        
		if(!$user_id){ //비로그인 시
            //alert(base_url('register/login'));
        }else{

			$IDX = $this->session->userdata("user_logid");
			$this->register_model->set_log_end($IDX);
            
			$this->session->sess_destroy();
            
        }

		redirect(base_url('register/login'));

	}

	public function getBrowserInfo($userAgent)
	{
		if(preg_match('/MSIE/i',$userAgent) && !preg_match('/Opera/i',$u_agent)){
			$browser = 'Internet Explorer';
		}else if(preg_match('/Firefox/i',$userAgent)){
			$browser = 'Mozilla Firefox';
		}else if (preg_match('/Chrome/i',$userAgent)){
			$browser = 'Google Chrome';
		}else if(preg_match('/Safari/i',$userAgent)){
			$browser = 'Apple Safari';
		}elseif(preg_match('/Opera/i',$userAgent)){
			$browser = 'Opera';
		}elseif(preg_match('/Netscape/i',$userAgent)){
			$browser = 'Netscape';
		}else{
			$browser = "Other";
		}
		return $browser;
	}

	public function getOsInfo($userAgent)
	{
		if (preg_match('/linux/i', $userAgent)){
			$os = 'linux';
		}elseif(preg_match('/macintosh|mac os x/i', $userAgent)){
			$os = 'mac';
		}elseif (preg_match('/windows|win32/i', $userAgent)){
			$os = 'windows';
		}else{
			$os = 'Other';
		}
		return $os;
	}


	
	/* 로그인폼 세션생성 */
	public function login_exec()
	{
		
		$res = array();
        $res['is_login'] = 'N';
        $res['msg'] = "";
        $res['url'] = "";
        
        $mid = $this->input->post('ID');
        $pw = strip_tags($this->input->post('PWD'));

		if(empty($mid)){
			alert("아이디를 입력하세요.",base_url('register/login'));
		}
         
        $userinfo = $this->register_model->get_userchk('ID',$mid);
		
		
		if($userinfo){

			if(password_verify($pw, $userinfo->PWD)){
                    
				$user['user_id']        = $userinfo->ID;
                $user['user_name']      = $userinfo->NAME;
				$user['user_level']     = $userinfo->LEVEL;
                    
                

				$userAgent = $_SERVER["HTTP_USER_AGENT"];
				$os = $this->getBrowserInfo($userAgent);
				$borwser = $this->getOsInfo($userAgent);
				

				$params = array(
					"SDATE"   => date("Y-m-d H:i:s",time()),
					"IP"      => $_SERVER["REMOTE_ADDR"],
					"MID"     => $userinfo->ID,
					"OS"      => $os,
					"BORWSER" => $borwser,
					"STATUS"  => "on"
				);

				$logid = $this->register_model->set_log_insert($params);
				
				$user['user_logid']     = $logid;
				
				$this->session->set_userdata($user);


			}else{
				alert("비밀번호를 확인 해 주세요.",base_url('register/login'));
			}


		}else{
			alert("회원정보가 존재하지 않습니다.",base_url('register/login'));
		}

		redirect(base_url('intro'));

	}








	public function index($idx="")
	{
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

		
		$data['memberList'] = $this->register_model->get_member_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->register_model->get_member_cut($params);
		
		

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


		
		$this->load->view('/register/userform',$data);
	}




	public function level($idx="")
	{
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

		
		$data['memberList'] = $this->register_model->get_member_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->register_model->get_member_cut($params);
		
		

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


		
		$this->load->view('/register/level',$data);
	}




	


	public function ajax_set_memberinfo()
	{
		$mode = $this->input->post("mode");
		$idx  = $this->input->post("idx");

		$data = array();
		if(!empty($idx)){
			$data['memInfo'] = $this->register_model->get_member_info($idx);
		}
		
		$this->load->view('/register/memberform',$data);
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
				'PWD'      => password_hash(trim($this->input->post("PWD")),PASSWORD_BCRYPT),
				'INSERT_ID' => $this->session->userdata('user_name'),
				'INSERT_DATE' => $dateTime
			);
			$text = "등록";
		}
		
		

		$data = $this->register_model->member_formupdate($params,$IDX);
		
		if($data != ""){
			alert($text."되었습니다",base_url('register/index')."/".$data);
		}

	}


	public function ajax_chk_memberid()
	{
		$data = $this->register_model->ajax_chk_memberid($this->input->post("id"));
		echo json_encode($data);
	}


	
	



	public function version()
	{
		$data['title'] = "버전관리";

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




		$data['verList'] = $this->register_model->get_ver_list($start,$config['per_page']);

		$this->data['cnt'] = $this->register_model->get_ver_cut();

		
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

		$this->load->view('/register/version',$data);
	}


	public function upload_ver_form()
	{
		$MIDX = $this->input->post("MIDX");
		$param['VER_NO'] = $this->input->post("VER_NO");
		$param['VER_REMARK'] = $this->input->post("VER_REMARK");
		$param['INSERT_ID'] = $this->session->userdata('user_id');
		$param['INSERT_DATE'] = date("Y-m-d H:i:s",time());
		
		$data = $this->register_model->upload_ver_form($param,$MIDX);
		
		if($data > 0){
			alert('등록되었습니다',base_url('register/version'));
		}
	}


	public function ajax_savelevel_update()
	{
		$param['idx'] = $this->input->post('idx');
		$param['level'] = $this->input->post('sqty');
		
		$data = $this->register_model->ajax_savelevel_update($param);
		echo $data;

	}


	public function delete_ver_form()
	{
		$param['IDX'] = $this->input->post("IDX");
		$data = $this->register_model->delete_ver_form($param);
		echo $data;
	}


	public function modified_ver_form()
	{
		$param['IDX'] = $this->input->post("IDX");
		$data = $this->register_model->modified_ver_form($param);
		echo json_encode($data);
	}


	












}//class Register end