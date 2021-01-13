<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->helper('test');
		$this->load->model('item_model');

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

	public function index($hid='')
	{
		/* mdm으로 이동
		$data['headList']   = $this->item_model->get_itemHead_list();
		$data['detailList'] = $this->item_model->get_itemDetail_list($hid);

		$data['H_IDX']      = $hid;
		$data['de_show_chk']= ($hid != "")?true:false;
		//$data = '';

		$this->load->view('item',$data);
		*/
		
	}
	
	/* 품목코드 HEAD 폼 호출 */
	public function ajax_itemHead_form()
	{
		$params['title'] = "품목코드-HEAD";
		$params['mod'] = 0;
		
		
		if($_POST['mode'] == "mod"){
			$params['title'] .= " - 수정";
			$data = $this->item_model->get_itemHead_info($_POST['IDX']);
			$params['data'] = $data;
			$params['mod'] = 1;
		}
		
		
		return $this->load->view('/ajax/item_head',$params);
	}


	public function ajax_itemDetail_form()
	{
		$params['title'] = "품록코드-DETAIL";
		$params['mod'] = '';
		$params['headList']  = $this->item_model->get_itemHead_list();
		$params['hidx'] = $this->input->post("hidx");
		

		if($_POST['mode'] == "mod"){
			$params['title'] .= " - 수정";
			$data = $this->item_model->get_itemDetail_info($this->input->post("idx"));
			$params['data'] = $data;
			$params['mod'] = 1;
		}
		
		return $this->load->view('/ajax/item_detail',$params);
	}

	//품목코드 head update
	public function set_item_HeadUpdate()
	{
		$params['mod']    = $this->input->post("mod");
		$params['CODE']   = $this->input->post("CODE");
		$params['NAME']   = $this->input->post("NAME");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['IDX']        = $this->input->post("IDX");

		$params['INSERT_ID'] = $this->session->userdata('user_name');

		$ins = $this->item_model->itemHead_update($params);
		
		$data['status'] = "";
		$data['msg']    = "";


		if($ins != ""){
			$data = array(
				'status' => 'ok',
				'msg'    => '코드를 등록했습니다.'
			);
			echo json_encode($data);
		}
	}

	
	//품목코드 detail update
	public function set_item_DetailUpdate()
	{
		$ins = $this->item_model->itemDetail_update($_POST);
		
		$data['status'] = "";
		$data['msg']    = "";


		if($ins != ""){
			$data = array(
				'status' => 'ok',
				'msg'    => '코드를 등록했습니다.'
			);
			echo json_encode($data);
		}
	}



	public function set_itemHead_delete()
	{

		$del = $this->item_model->delete_itemHead($_POST['code']);
		echo $del;
		
	}

	public function set_itemDetail_delete()
	{

		$del = $this->item_model->delete_itemDetail($_POST['idx']);
		echo $del;
		
	}


	/* head code 중복체크 */
	public function ajax_itemHaedchk()
	{
		//중복검사
        $parem = $this->input->post("code");
        $chk = $this->item_model->ajax_itemHaedchk('ITEM_CODE',$parem);
        if ($chk > 0) {
            $data['state'] = "N";
            $data['msg'] = "중복된 코드입니다.";
        } else {
            $data['state'] = "Y";
            $data['msg'] = "";
        }

        echo json_encode($data);
		
	}






}
