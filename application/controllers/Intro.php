<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Intro extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();
		
		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);

		$this->load->model(array('sys_model'));
		$this->data['siteTitle'] = "더조은자기";
		

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

				
				
				
				//if(isset($user_id) && $user_id != ""){
					
					$this->data['menu'] = $this->sys_model->get_system_menu($this->session->userdata('user_level'));
					

					$this->load->view('/layout/header',$this->data);
					call_user_func_array(array($this,$method), $params);
					$this->load->view('/layout/tail');

				//}else{

					//alert('로그인이 필요합니다.',base_url('register/login'));

				//}

            } else {
                show_404();
            }

        }
		
	}
	
	public function index() {
		
		
		$this->load->view('layout/main');
	}

}