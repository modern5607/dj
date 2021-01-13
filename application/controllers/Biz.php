<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Biz extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->model('biz_model');

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
	

	public function index()
	{
		/* mdm으로 이동
		$data['bizList']   = $this->biz_model->get_bizReg_list(); 
		
		//$data['H_IDX']      = $hid;
		//$data['de_show_chk']= ($hid != "")?true:false;

		$this->load->view('/biz/index',$data);
		*/
		
	}

	/* 업체등록 폼 호출 */
	public function ajax_bizReg_form()
	{
		$params['title'] = "업체등록";
		$params['mod'] = 0;
		
		
		if($_POST['mode'] == "mod"){
			$params['title'] .= " - 수정";
			$data = $this->biz_model->get_bizReg_info($_POST['IDX']);
			$params['data'] = $data;
			$params['mod'] = 1;
		}
		
		
		return $this->load->view('/ajax/biz_reg',$params);
	}


	//업체등록 update
	public function set_bizRegUpdate()
	{
		$params['mod']            = $this->input->post("mod");
		$params['IDX']             = $this->input->post("IDX");
		$params['CUST_NM']    = $this->input->post("CUST_NM");
		$params['ADDRESS']    = $this->input->post("ADDRESS");
		$params['TEL']             = $this->input->post("TEL");
		$params['CUST_NAME']    = $this->input->post("CUST_NAME");
		$params['ITEM']          = $this->input->post("ITEM");
		$params['REMARK']    = $this->input->post("REMARK");
		$params['INSERT_ID'] = $this->session->userdata('user_name');

		$ins = $this->biz_model->bizReg_update($params);
		
		$data['status'] = "";
		$data['msg']    = "";


		if($ins != ""){

			$mTit = ($params['mod'] == 1)?"수정":"등록";
			$data = array(
				'status' => 'ok',
				'msg'    => '업체가 '.$mTit.' 되었습니다.'
			);
			echo json_encode($data);
		}
	}


	/* 업체삭제 */
	public function set_bizReg_delete()
	{

		$del = $this->biz_model->delete_bizReg($this->input->post("idx"));
		echo $del;
		
	}


}