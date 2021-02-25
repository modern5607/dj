<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iot extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->model('main_model');

		$this->data['siteTitle'] = $this->config->item('site_title');

		

	}

	public function _remap($method, $params = array())
	{
		if($this->input->is_ajax_request()){
            if( method_exists($this, $method) ){
                call_user_func_array(array($this,$method), $params);
            }
        }else{ //ajax가 아니면dddd
			
			if (method_exists($this, $method)) {

				//$user_id = $this->session->userdata('user_id');
				//if(isset($user_id) && $user_id != ""){
					
					
					call_user_func_array(array($this,$method), $params);
					

				//}else{

				//	alert('로그인이 필요합니다.',base_url('register/login'));

				//}

            } else {
                show_404();
            }

        }
		
	}
	

	public function index()
	{
		$params['TEMP'] = $this->input->get("val1");
		$params['HUM'] = $this->input->get("val2");
		$params['LOC'] = $this->input->get("val3");
		
		
		$data = $this->main_model->iot_insert($params);
		

	}


}