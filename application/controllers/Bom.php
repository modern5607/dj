<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bom extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		$this->data['subpos_3'] = $this->uri->segment(3);

		$this->data['userLevel'] = $this->session->userdata('user_level');
		
		$this->load->model(array('bom_model','main_model','biz_model'));

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


	public function test()
	{
		//$this->bom_model->get_testUpdate();
	}


	/* ITEMS */
	public function index($idx="")
	{
		$this->load->library('barcode');
		
		$data['str'] = array(); //검색어관련
		$data['str']['bno'] = $this->input->get('bno'); //BL_NO
		$data['str']['iname'] = $this->input->get('iname'); //ITEM_NAME
		$data['str']['mscode'] = $this->input->get('mscode'); //MSAB
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE
		$data['str']['gjcode'] = $this->input->get('gjcode'); //GJ_GB
		$data['str']['use'] = $this->input->get('use'); //USE_YN

		$params['BL_NO'] = "";
		$params['ITEM_NAME'] = "";
		$params['MSAB'] = "";
		$params['M_LINE'] = "";
		$params['GJ_GB'] = "";
		$params['USE_YN'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['bno'])){
			$params['BL_NO'] = $data['str']['bno'];
			$data['qstr'] .= "&bno=".$data['str']['bno'];
		}
		if(!empty($data['str']['iname'])){
			$params['ITEM_NAME'] = $data['str']['iname'];
			$data['qstr'] .= "&iname=".$data['str']['iname'];
		}
		if(!empty($data['str']['mscode'])){
			$params['MSAB'] = $data['str']['mscode'];
			$data['qstr'] .= "&mscode=".$data['str']['mscode'];
		}
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
		}
		if(!empty($data['str']['gjcode'])){
			$params['GJ_GB'] = $data['str']['gjcode'];
			$data['qstr'] .= "&gjcode=".$data['str']['gjcode'];
		}
		if(!empty($data['str']['use'])){
			$params['USE_YN'] = $data['str']['use'];
			$data['qstr'] .= "&use=".$data['str']['use'];
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

		$data['title'] = "BOM-ITMES";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		$data['seq'] = "";
		$data['set'] = "";
		
		
		
		
		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		//$data['qstr'] .= (!empty($this->input->get("perpage")))?"&perpage=".$this->input->get("perpage"):"";

		$data['bomList']  = $this->bom_model->get_items_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->bom_model->get_items_cut($params);

		//$data['bomInfo']  = (!empty($idx))?$this->bom_model->get_items_info($idx):"";
		
		$data['MSAB']     = $this->main_model->get_selectInfo("tch.CODE","MSAB");
		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		$data['stClass1'] = $this->main_model->get_selectInfo("tch.CODE","1ST_CLASS");
		$data['stClass2'] = $this->main_model->get_selectInfo("tch.CODE","2ND_CLASS");
		$data['STATE']    = $this->main_model->get_selectInfo("tch.CODE","STATE");
		$data['UNIT']     = $this->main_model->get_selectInfo("tch.CODE","UNIT");
		$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");

		$data['CUSTOMER'] = $this->biz_model->get_selectInfo();

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

		
		
		$this->load->view('/bom/index',$data);
	}




	/* ITEMS */
	public function index_ajax($idx="")
	{

		$idx = $this->input->post("idx");
		$data['bomInfo']  = (!empty($idx))?$this->bom_model->get_items_info($idx):"";
		
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
		
		$data['MSAB']     = $this->main_model->get_selectInfo("tch.CODE","MSAB");
		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		$data['stClass1'] = $this->main_model->get_selectInfo("tch.CODE","1ST_CLASS");
		$data['stClass2'] = $this->main_model->get_selectInfo("tch.CODE","2ND_CLASS");
		$data['STATE']    = $this->main_model->get_selectInfo("tch.CODE","STATE");
		$data['UNIT']     = $this->main_model->get_selectInfo("tch.CODE","UNIT");
		$data['M_LINE']   = $this->main_model->get_selectInfo("tch.CODE","M_LINE");

		$data['CUSTOMER'] = $this->biz_model->get_selectInfo();
		

		return $this->load->view('/bom/ajax_index',$data);
		

		
	}




	/*	자재관리 
	*	materialList : T_ITEMS LIST
	*	materialInfo : 리스트상세정보
	*/
	public function materials($idx="")
	{
		//PAGINATION
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		$data['qstr'] = "?P";
		$data['qstr'] .= (!empty($this->input->get('perpage')))?'':'';
		
		

		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		//$config['num_links'] = 3;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		$start = $pageNum;
		
		$data['pageNum'] = $start;
		$data['title'] = "재고실사관리";

		$data['seq'] = "";
		$data['set'] = "";
		
		
		$params = array();
		if(!empty($this->input->get("set"))){
			$params['seq'] = $this->input->get("seq");
			$params['set'] = $this->input->get("set");

			$data['seq'] = $this->input->get("seq");
			$data['set'] = $this->input->get("set");

			$data['qstr'] .= "&seq=".$data['seq']."&set=".$data['set'];
		}

		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		$data['qstr'] .= (!empty($this->input->get("perpage")))?"&perpage=".$this->input->get("perpage"):"";

		$data['materialList']  = $this->bom_model->get_material_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->bom_model->get_material_cut($params);

		$data['materialInfo']  = (!empty($idx))?$this->bom_model->get_material_info($idx):"";

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


		$this->load->view('/bom/material',$data);
	}


	/*	자재관리 
	*	materialList : T_ITEMS LIST
	*	materialInfo : 리스트상세정보
	*/
	public function materials_ajax()
	{
		
		$idx = $this->input->post("idx");
		$data['materialInfo']  = (!empty($idx))?$this->bom_model->get_material_info($idx):"";

		//$data['GJ_GB']         = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		

		return $this->load->view('/bom/ajax_material',$data);
	}


	/*	재고현황
	*	materialList : T_ITEMS LIST
	*	materialInfo : 리스트상세정보
	*/
	public function stocklist($idx="")
	{
		//PAGINATION
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		$data['qstr'] = "?P";
		$data['qstr'] .= (!empty($this->input->get('perpage')))?'':'';
		
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		//$config['num_links'] = 3;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		$start = $pageNum;
		
		$data['pageNum'] = $start;




		$data['seq'] = "";
		$data['set'] = "";
		
		$params = array();
		if(!empty($this->input->get("set"))){
			$params['seq'] = $this->input->get("seq");
			$params['set'] = $this->input->get("set");

			$data['seq'] = $this->input->get("seq");
			$data['set'] = $this->input->get("set");

			$data['qstr'] .= "&seq=".$data['seq']."&set=".$data['set'];
		}

		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		$data['qstr'] .= (!empty($this->input->get("perpage")))?"&perpage=".$this->input->get("perpage"):"";

		$data['title'] = "재고현황";
		$data['materialList']  = $this->bom_model->get_material_list($params,$start,$config['per_page']);
		$data['materialInfo']  = (!empty($idx))?$this->bom_model->get_material_info($idx):"";

		$this->data['cnt'] = $this->bom_model->get_material_cut($params);
		
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


		$this->load->view('/bom/stocklist',$data);
	}



	/*	자재실사관리관리 
	*	materialList : T_ITEMS LIST
	*	materialInfo : 리스트상세정보
	*/
	public function stock($idx="")
	{
		
		$data['str'] = array(); //검색어관련
		$data['str']['component'] = $this->input->get('component'); //BL_NO
		$data['str']['comp_name'] = $this->input->get('comp_name'); //ITEM_NAME
		$data['str']['gjcode'] = $this->input->get('gjcode'); //GJ_GB
		$data['str']['use'] = $this->input->get('use'); //USE_YN

		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";
		$params['GJ_GB'] = "";
		$params['SPEC'] = "";
		$params['USE_YN'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['component'])){
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=".$data['str']['component'];
		}
		if(!empty($data['str']['comp_name'])){
			$params['COMPONENT_NM'] = $data['str']['comp_name'];
			$data['qstr'] .= "&comp_name=".$data['str']['comp_name'];
		}
		if(!empty($data['str']['gjcode'])){
			$params['GJ_GB'] = $data['str']['gjcode'];
			$data['qstr'] .= "&gjcode=".$data['str']['gjcode'];
		}
		if(!empty($data['str']['use'])){
			$params['USE_YN'] = $data['str']['use'];
			$data['qstr'] .= "&use=".$data['str']['use'];
		}

		
		
		//PAGINATION
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		$data['qstr'] = "?P";
		$data['qstr'] .= (!empty($this->input->get('perpage')))?'':'';
		
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		//$config['num_links'] = 3;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		$start = $pageNum;
		
		$data['pageNum'] = $start;

		
		
		

		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		$data['qstr'] .= (!empty($this->input->get("perpage")))?"&perpage=".$this->input->get("perpage"):"";

		$data['title'] = "BOM-자재등록";
		
		$data['materialList']  = $this->bom_model->get_material_list($params,$start,$config['per_page']);
		$data['materialInfo']  = (!empty($idx))?$this->bom_model->get_material_info($idx):"";

		$this->data['cnt'] = $this->bom_model->get_material_cut($params);

		$data['GJ_GB']         = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		$data['UNIT']		   = $this->main_model->get_selectInfo("tch.CODE","UNIT");


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

		$this->load->view('/bom/stock',$data);
	}




	/*	자재실사관리관리 
	*	materialList : T_ITEMS LIST
	*	materialInfo : 리스트상세정보
	*/
	public function stock_ajax()
	{
		$idx = $this->input->post("idx");
		
		//$data['materialList']  = $this->bom_model->get_material_list($params,$start,$config['per_page']);
		$data['materialInfo']  = (!empty($idx))?$this->bom_model->get_material_info($idx):"";

		$data['GJ_GB']         = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");
		$data['UNIT']		   = $this->main_model->get_selectInfo("tch.CODE","UNIT");
		

		return $this->load->view('/bom/ajax_stock',$data);
	}



	/* BOM등록 */
	public function insert($idx="")
	{
		
		$data['str'] = array(); //검색어관련
		$data['str']['bno'] = $this->input->get('bno'); //BL_NO
		$data['str']['iname'] = $this->input->get('iname'); //ITEM_NAME
		$data['str']['mscode'] = $this->input->get('mscode'); //MSAB
		$data['str']['mline'] = $this->input->get('mline'); //M_LINE
		$data['str']['gjcode'] = $this->input->get('gjcode'); //GJ_GB
		$data['str']['use'] = $this->input->get('use'); //USE_YN

		$params['BL_NO'] = "";
		$params['ITEM_NAME'] = "";
		$params['MSAB'] = "";
		$params['M_LINE'] = "";
		$params['GJ_GB'] = "";
		$params['USE_YN'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['bno'])){
			$params['BL_NO'] = $data['str']['bno'];
			$data['qstr'] .= "&bno=".$data['str']['bno'];
		}
		if(!empty($data['str']['iname'])){
			$params['ITEM_NAME'] = $data['str']['iname'];
			$data['qstr'] .= "&iname=".$data['str']['iname'];
		}
		if(!empty($data['str']['mscode'])){
			$params['MSAB'] = $data['str']['mscode'];
			$data['qstr'] .= "&mscode=".$data['str']['mscode'];
		}
		if(!empty($data['str']['mline'])){
			$params['M_LINE'] = $data['str']['mline'];
			$data['qstr'] .= "&mline=".$data['str']['mline'];
		}
		if(!empty($data['str']['gjcode'])){
			$params['GJ_GB'] = $data['str']['gjcode'];
			$data['qstr'] .= "&gjcode=".$data['str']['gjcode'];
		}
		if(!empty($data['str']['use'])){
			$params['USE_YN'] = $data['str']['use'];
			$data['qstr'] .= "&use=".$data['str']['use'];
		}


		//PAGINATION
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		

		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		//$config['num_links'] = 3;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		$start = $pageNum;
		
		$data['pageNum'] = $start;


		$data['title'] = "BOM등록";
		
		

		$data['qstr'] .= (!empty($this->input->get("pageNum")))?"&pageNum=".$this->input->get("pageNum"):"";
		//$data['qstr'] .= (!empty($this->input->get("perpage")))?"&perpage=".$this->input->get("perpage"):"";

		$data['bomList']  = $this->bom_model->get_items_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->bom_model->get_items_cut($params);

		$data['GJ_GB']    = $this->main_model->get_selectInfo("tch.CODE","GJ_GB");

		$data['insertBomList'] = $this->bom_model->get_bom_list($idx);
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



		$this->load->view('/bom/insert',$data);
	}



	/* ITEMS */
	public function trans($idx="")
	{
		$data['str'] = array(); //검색어관련
		$data['str']['gjgb'] = $this->input->get('gjgb'); //BL_NO
		
		$params['GJ_GB'] = "";
		
		$data['qstr'] = "?P";
		if(!empty($data['str']['gjgb'])){
			$params['GJ_GB'] = $data['str']['gjgb'];
			$data['qstr'] .= "&gjgb=".$data['str']['gjgb'];
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

		$data['title'] = "자재소모현황";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
		

		$data['transList']  = $this->bom_model->get_trans_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->bom_model->get_trans_cut($params);

		
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

		
		
		$this->load->view('/bom/trans',$data);
	}



	public function ajax_bomWriteform()
	{
		$params['title'] = "자재선택";
		$params['idx'] = $this->input->post("idx");

		
		$params['qstr'] = "?P";
		$params['qstr'] .= (!empty($this->input->post('perpage')))?'':'';

		
		$data['idx'] = $this->input->post("idx");
		$data['seq'] = (!empty($this->input->post("seq")))?$this->input->post("seq"):"";
		$data['set'] = (!empty($this->input->post("set")))?$this->input->post("set"):"";


		$params['materialList'] = $this->bom_model->get_bom_material($data,0,50);
		$this->data['cnt'] = $this->bom_model->get_bom_material_cut($data);

		$params['seq'] = $data['seq'];
		$params['set'] = $data['set'];


		return $this->load->view('/ajax/bomWriteform',$params);

	}


	
	public function ajax_bomWriteform_list()
	{
		
		$params['idx'] = $this->input->post("idx");
				
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 2;
		$start = 50 * ($pageNum - 1);

		$data['idx'] = $this->input->post("idx");
		$data['seq'] = (!empty($this->input->post("seq")))?$this->input->post("seq"):"";
		$data['set'] = (!empty($this->input->post("set")))?$this->input->post("set"):"";


		$params['materialList'] = $this->bom_model->get_bom_material($data,$start,50);
		$params['pageNum'] = $pageNum;
		$cut = $this->bom_model->get_bom_material_cut($data)-$start;

		//$datan['num'] = $cut;

		return $this->load->view('/ajax/bomWriteform_list',$params);
	}




	public function ajax_bom_insertform()
	{
		$params = array();
		foreach($this->input->post("comIdx") as $i=>$cidx){
			
			$chkBom = $this->bom_model->get_check_bom($this->input->post("itemIdx"),$cidx);
			if($chkBom->num > 0){
				continue;
			}
			
			$params[] = array(
				"H_IDX"       => $this->input->post("itemIdx"),
				"C_IDX"       => $cidx,
				"INSERT_ID"   => $this->session->userdata('user_name'),
				"INSERT_DATE" => date("Y-m-d H:i:s",time())
			);
		}
		
		if(!empty($params)){
			$data['ins_id'] = $this->bom_model->set_bom_insertform($params);
			$data['msg'] = ($data['ins_id'] > 0)?"등록되었습니다.":"등록실패-관리자문의 code-0001";
		}else{
			$data['msg'] = "";
		}
		
		echo json_encode($data);

	}

	/* 
	* 자재정보삭제 
	* 삭제하려는 자재정보가 BOM리스트에 존재하는경우 삭제가 안되게 한다.
	*/
	public function ajax_delete_materials()
	{
		$param['IDX'] = $this->input->post("idx");
		$chkBom = $this->bom_model->check_bom_info($param,"C_IDX");
		if($chkBom > 0){
			$msg['set'] = 0;
			$msg['text'] = 'BOM에 등록된 자재입니다 BOM에 등록된 자재를 삭제후 다시 시도하세요';
			echo json_encode($msg);
			return false;
		}
		$data = $this->bom_model->delete_material($param);
		if($data > 0){
			$msg['set'] = 1;
			$msg['text'] = "삭제되었습니다.";
		}else{
			$msg['set'] = 0;
			$msg['text'] = "삭제처리에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($msg);

	}


	/* 
	* items삭제 
	* 
	*/
	public function ajax_delete_items()
	{
		$param['IDX'] = $this->input->post("idx");
		$chkBom = $this->bom_model->check_bom_info($param,"H_IDX");
		if($chkBom > 0){
			$msg['set'] = 0;
			$msg['text'] = 'BOM에 등록된 ITEM입니다 BOM에 등록된 item를 삭제후 다시 시도하세요';
			echo json_encode($msg);
			return false;
		}
		$data = $this->bom_model->delete_item($param);
		if($data > 0){
			$msg['set'] = 1;
			$msg['text'] = "삭제되었습니다.";
		}else{
			$msg['set'] = 0;
			$msg['text'] = "삭제처리에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($msg);

	}


	/*
	* BOM 리스트에 여유율,PT,POINT,REEL 을 업데이트
	* idx : BOM > IDX
	*/
	public function ajax_bomlist_update()
	{
		$params = array(
			"POINT"     => $this->input->post("point"),
			//"WORK_ALLO" => $this->input->post("work"),
			//"PT"        => $this->input->post("pt"),
			//"REEL_CNT"  => $this->input->post("reel"),
			"UPDATE_ID" => $this->session->userdata('user_name'),
			"UPDATE_DATE" => date("Y-m-d H:i:s",time())
		);
		$data = $this->bom_model->set_bomlistUpdate($params,$this->input->post("idx"));
		echo $data;
	}


	public function ajax_bom_update()
	{
		if($this->input->post("chk") == "1"){

			$COMPONENT = $this->bom_model->get_material_info($this->input->post("CIDX"));
			
			$param = array(
				"H_IDX"       => $this->input->post("HIDX"),
				"C_IDX"       => $this->input->post("CIDX"),
				"WORK_ALLO"   => $COMPONENT->WORK_ALLO,
				"PT"          => $COMPONENT->PT,
				"REEL_CNT"    => $COMPONENT->REEL_CNT,
				"INSERT_ID"   => $this->session->userdata('user_name'),
				"INSERT_DATE" => date("Y-m-d H:i:s",time())
			);
			$data = $this->bom_model->set_bom_formUpdate($param);

			
		}else{
			
			$param = array(
				"H_IDX"       => $this->input->post("HIDX"),
				"C_IDX"       => $this->input->post("CIDX")
			);
			$data = $this->bom_model->set_bom_formDelete($param);

		}
		
		$text['msg'] = "등록오류-관리자에게 문의하세요";
		if($data > 0){
			
			$text['msg'] = "처리되었습니다. - 팝업창을 닫기시 적용됩니다.";

		}
		
		echo json_encode($text);

	}




	public function materialUpdate()
	{
		


		$params = array(
			"COMPONENT"       => trim($this->input->post("COMPONENT")),
			"COMPONENT_NM"    => trim($this->input->post("COMPONENT_NM")),
			"SPEC"            => trim($this->input->post("SPEC")),
			"REEL_CNT"        => trim($this->input->post("REEL_CNT")),
			"WORK_ALLO"       => trim($this->input->post("WORK_ALLO")),
			//"PT"              => trim($this->input->post("PT")),
			"PRICE"           => trim($this->input->post("PRICE")),
			//"INTO_DATE"       => trim($this->input->post("INTO_DATE")),
			//"REPL_DATE"       => trim($this->input->post("REPL_DATE")),
			"REMARK"          => trim($this->input->post("REMARK")),
			"COL1"            => trim($this->input->post("COL1")),
			"COL2"            => trim($this->input->post("COL2")),
			"GJ_GB"           => trim($this->input->post("GJ_GB")),
			"USE_YN"          => $this->input->post("USE_YN"),
			"INSERT_ID"       => $this->session->userdata('user_name'),
			"INSERT_DATE"     => date("Y-m-d H:i:s",time())
		);

		if(!empty($this->input->post("midx"))){ //수정인경우
			
			$params['UPDATE_DATE'] = date("Y-m-d H:i:s",time());
			$params['UPDATE_ID'] = $this->session->userdata('user_name');
			unset($params['INSERT_DATE']);
			unset($params['INSERT_ID']);
			$data = $this->bom_model->set_materialUpdate($params,$this->input->post("midx"));
			$msg = "변경되었습니다.";
		}else{
			$data = $this->bom_model->set_materialInsert($params);
			$msg = "등록되었습니다.";
		}
		
		if($data > 0){
			echo $msg;
			//redirect('/bom/materials');
		}


		

	}


	public function itemsUpdate()
	{
		
		$params = array(
			"BL_NO"       => trim($this->input->post("BL_NO")),
			"MSAB"        => trim($this->input->post("MSAB")),
			"1ST_CLASS"   => trim($this->input->post("1ST_CLASS")),
			"2ND_CLASS"   => trim($this->input->post("2ND_CLASS")),
			"ITEM_NAME"   => trim($this->input->post("ITEM_NAME")),
			"ITEM_SPEC"   => trim($this->input->post("ITEM_SPEC")),
			"MODEL"       => trim($this->input->post("MODEL")),
			"STATE"       => trim($this->input->post("STATE")),
			"PACKING"     => trim($this->input->post("PACKING")),
			"CUSTOMER"    => trim($this->input->post("CUSTOMER")),
			"PRICE"       => trim($this->input->post("PRICE")),
			"INSERT_DATE" => $this->input->post("INSERT_DATE"),
			"INSERT_ID"   => trim($this->input->post("INSERT_ID")),
			"M_LINE"      => trim($this->input->post("M_LINE")),
			"P_T"         => trim($this->input->post("P_T")),
			"USE_YN"      => trim($this->input->post("USE_YN")),
			"2ND_LINE"    => $this->input->post("2ND_LINE"),
			"2ND_P_T"     => trim($this->input->post("2ND_P_T")),
			"3ND_LINE"    => $this->input->post("3ND_LINE"),
			"3ND_P_T"     => trim($this->input->post("3ND_P_T")),
			"REMARK"      => $this->input->post("REMARK"),
			"GJ_GB"       => $this->input->post("GJ_GB")
		);

		
		if(!empty($this->input->post("midx"))){ //수정인경우
			
			$params['UPDATE_DATE'] = date("Y-m-d H:i:s",time());
			$params['UPDATE_ID'] = $this->session->userdata('user_name');
			unset($params['INSERT_DATE']);
			unset($params['INSERT_ID']);
			$data = $this->bom_model->set_itemsUpdate($params,$this->input->post("midx"));
			$msg = "변경되었습니다.";

		}else{
			$data = $this->bom_model->set_itemsInsert($params);
			$msg = "등록되었습니다.";
		}
		
		if($data > 0){
			echo $msg;
			//alert($msg);
			//redirect('/bom/index');
		}else{
			
		}

	}


}