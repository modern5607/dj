<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->helper('test');
		$this->load->model('main_model');

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
                $this->load->view('/layout/header',$this->data);
                call_user_func_array(array($this,$method), $params);
                $this->load->view('/layout/tail');
            } else {
                show_404();
            }

        }
		
	}


	/* 닉네임 중복 체크 */
    public function ajax_nickchk()
    {
        $this->load->helper('aes_helper');
        $data = array();

        //중복검사
        $parem = $this->input->post("nickname");
        $chk = $this->register_model->ajax_dupl_chk('nickname',$parem);
        if ($chk > 0) {
            $data['state'] = "N";
            $data['msg'] = "";
        } else {
            $data['state'] = "Y";
            $data['msg'] = "";
        }

        echo json_encode($data);

    }


	/* 회원가입 완료 페이지 */
    public function reg_message()
    {
        if($this->session->userdata('user_id') && $this->session->flashdata('reg_set')){
            $this->load->view("register/mobile/reg_message");
            $this->session->keep_flashdata('reg_set');
        } else {
            redirect('/acon');
        }
    }
	