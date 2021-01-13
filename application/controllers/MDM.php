<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MDM extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->helper('test');
		$this->load->model(array('main_model','biz_model','register_model'));

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
	

	public function index($hid='')
	{
		check_pageLevel();
		$data['title'] = "공통코드등록";
		$data['headList']   = $this->main_model->get_cocdHead_list();
		$data['detailList'] = $this->main_model->get_cocdDetail_list($hid);

		$data['H_IDX']      = $hid;
		$data['de_show_chk']= ($hid != "")?true:false;

		$this->load->view('/mdm/index',$data);
		
	}


	public function items()
	{
		check_pageLevel();

		$data['str'] = array(); //검색어관련
		$data['str']['series'] = $this->input->get('series'); //series
		$data['str']['itemno'] = $this->input->get('itemno'); //item no
		$data['str']['itemname'] = $this->input->get('itemname'); //item name
		$data['str']['use'] = $this->input->get('use'); //use yn
		
		$params['SERIES_IDX'] = "";
		$params['ITEM_NO'] = "";
		$params['ITEM_NAME'] = "";
		$params['USE_YN'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['series'])){
			$params['SERIES_IDX'] = $data['str']['series'];
			$data['qstr'] .= "&series=".$data['str']['series'];
		}
		if(!empty($data['str']['itemno'])){
			$params['ITEM_NO'] = $data['str']['itemno'];
			$data['qstr'] .= "&itemno=".$data['str']['itemno'];
		}
		if(!empty($data['str']['itemname'])){
			$params['ITEM_NAME'] = $data['str']['itemname'];
			$data['qstr'] .= "&itemname=".$data['str']['itemname'];
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
		
		$data['title'] = "품목관리";
		$data['seriesList']   = $this->main_model->get_seriesHead_list();
		$data['itemsList'] = $this->main_model->get_items_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->main_model->get_items_cnt($params);
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

		$this->load->view('/mdm/items',$data);
	}


	public function component()
	{
		check_pageLevel();

		$data['str'] = array(); //검색어관련
		$data['str']['component'] = $this->input->get('component'); //item no
		$data['str']['component_nm'] = $this->input->get('component_nm'); //item name
		$data['str']['use'] = $this->input->get('useyn'); //use yn
		
		$params['SERIES_IDX'] = "";
		$params['COMPONENT'] = "";
		$params['COMPONENT_NM'] = "";
		$params['USE_YN'] = "";

		$data['qstr'] = "?P";
		if(!empty($data['str']['component'])){
			$params['COMPONENT'] = $data['str']['component'];
			$data['qstr'] .= "&component=".$data['str']['component'];
		}
		if(!empty($data['str']['component_nm'])){
			$params['COMPONENT_NM'] = $data['str']['component_nm'];
			$data['qstr'] .= "&component_nm=".$data['str']['component_nm'];
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
		
		$data['title'] = "품목관리";
		$data['seriesList']   = $this->main_model->get_seriesHead_list();
		$data['componentList'] = $this->main_model->get_component_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->main_model->get_component_cnt($params);
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

		$this->load->view('/mdm/component',$data);
	}


	public function biz()
	{
		$data['title'] = "업체등록";
		$data['bizList']   = $this->biz_model->get_bizReg_list(); 
		$this->load->view('/biz/index',$data);
	}
	

	public function excelDown($hidx="")
	{
		error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        ini_set('memory_limit','-1');
        ini_set("max_execution_time","0");       
        define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '&lt;br /&gt;');
        date_default_timezone_set('Asia/Seoul');
        
        $this->load->library('PHPExcel');
        
        $objPHPExcel = new PHPExcel();
        
        
        $objPHPExcel->getProperties()->setCreator('Aliseon')
                                        ->setLastModifiedBy('Aliseon')
                                        ->setTitle('Aliseon_SALE LIST')
                                        ->setSubject('Aliseon_SALE LIST')
                                        ->setDescription('Aliseon_SALE LIST');

        function column_char($i) { return chr( 65 + $i ); }

        
        $headers = array('HEAD-CODE','CODE','NAME','비고');
        $last_char = column_char( count($headers) - 1 );
        $widths = array(10, 30, 40, 50);
                                        
        $objPHPExcel->setActiveSheetIndex(0);
        /** 상단 스타일지정 **/
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$last_char.'1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$last_char.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9EDF7');
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$last_char.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$last_char.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Nanum Gothic')->setSize(12);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        
        $objPHPExcel->getActiveSheet()->getStyle('A:'.$last_char)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        
        foreach($widths as $i => $w){
            $objPHPExcel->getActiveSheet()->setCellValue(column_char($i).'1', $headers[$i]);
            $objPHPExcel->setActiveSheetIndex()->getColumnDimension( column_char($i) )->setWidth($w);
        }
        
        
        
        $this->data['cDetail_list'] = $this->main_model->get_cocdDetail_list($hidx);
        foreach ($this->data['cDetail_list'] as $k=>$row) {
            $nnn[$k]['H_CODE'] = $row->H_CODE;
            $nnn[$k]['CODE'] = $row->CODE;
            $nnn[$k]['NAME'] = $row->NAME;
            $nnn[$k]['REMARK'] = $row->REMARK;            
        }
		
		

        $rows = $nnn;
        $data = array_merge(array($headers), $rows);
        
        $objPHPExcel->getActiveSheet()->fromArray($data,NULL,'A1');
        
        
        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        header('Content-type: application/x-msexcel;charset=utf-8');
        header('Content-Disposition: attachment;filename="공통코드-디테일.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
	}




	/* 품목관리 */
	public function ajax_set_items()
	{
		$data['title'] = "품목추가";
		$data['mod'] = ($this->input->post("idx"))?"1":"";
				
		$data['seriesList']   = $this->main_model->get_seriesHead_list();
		$data['data'] = $this->main_model->get_items_info($this->input->post("idx"));

		$data['UNIT']   = $this->main_model->get_selectInfo("tch.CODE","UNIT");


		return $this->load->view('/mdm/ajax_items_form',$data);
	}


	/* 자재관리 */
	public function ajax_set_component()
	{
		$data['title'] = "자재추가";
		$data['mod'] = ($this->input->post("idx"))?"1":"";
				
		//$data['seriesList']   = $this->main_model->get_seriesHead_list();
		$data['data'] = $this->main_model->get_component_info($this->input->post("idx"));
		$data['USER'] = $this->session->userdata('user_name');

		$data['UNIT']   = $this->main_model->get_selectInfo("tch.CODE","UNIT");


		return $this->load->view('/mdm/ajax_compont_form',$data);
	}


	public function set_items_formUpdate()
	{
		$params = array(
			'mod' => $this->input->post("mod"),
			'IDX' => $this->input->post("IDX"),
			'ITEM_NO' => $this->input->post("ITEM_NO"),
			'ITEM_NAME' => $this->input->post("ITEM_NAME"),
			'SPEC' => $this->input->post("SPEC"),
			'SERIES' => $this->input->post("SERIES"),
			'UNIT' => $this->input->post("UNIT"),
			'BK_YN' => $this->input->post("BK_YN"),
			'JT_QTY' => $this->input->post("JT_QTY"),
			'BIZ_IDX' => $this->input->post("BIZ_IDX"),
			'USE_YN' => $this->input->post("USE_YN"),
			'COL1' => '',
			'COL2' => '',
			'INSERT_ID' => $this->session->userdata('user_name')
		);

		$ins = $this->main_model->set_items_formUpdate($params);
		
		$data['status'] = "";
		$data['msg']    = "";


		if($ins > 0){
			$data = array(
				'status' => 'ok',
				'msg'    => '품목을 등록했습니다.'
			);
			echo json_encode($data);
		}

	}


	public function set_component_formUpdate()
	{
		$params = array(
			'mod' => $this->input->post("mod"),
			'IDX' => $this->input->post("IDX"),
			'COMPONENT' => $this->input->post("COMPONENT"),
			'COMPONENT_NM' => $this->input->post("COMPONENT_NM"),
			'SPEC' => $this->input->post("SPEC"),
			'UNIT' => $this->input->post("UNIT"),
			'USE_YN' => $this->input->post("USE_YN"),
			'COL1' => '',
			'COL2' => '',
			'INSERT_DATE' => $this->input->post("XDATE"),
			'INSERT_ID' => $this->input->post("XUSER")
		);

		
		$ins = $this->main_model->set_component_formUpdate($params);
		
		$data['status'] = "";
		$data['msg']    = "";
		
		$text = ($this->input->post("mod") == "")?"등록":"수정";

		if($ins > 0){
			$data = array(
				'status' => 'ok',
				'msg'    => '자재를 '.$text.'했습니다.'
			);
			echo json_encode($data);
		}

	}



	
	/* 공통코드 HEAD 폼 호출 */
	public function ajax_cocdHead_form()
	{
		$params['title'] = "공통코드-HEAD";
		$params['mod'] = 0;
		
		
		if($_POST['mode'] == "mod"){
			$params['title'] .= " - 수정";
			$data = $this->main_model->get_cocdHead_info($_POST['IDX']);
			$params['data'] = $data;
			$params['mod'] = 1;
		}
		
		
		return $this->load->view('/ajax/cocd_head',$params);
	}


	public function ajax_cocdDetail_form()
	{
		$params['title'] = "공통코드-DETAIL";
		$params['mod'] = '';
		$params['headList']  = $this->main_model->get_cocdHead_list();
		$params['hidx'] = $this->input->post("hidx");
		

		if($_POST['mode'] == "mod"){
			$params['title'] .= " - 수정";
			$data = $this->main_model->get_cocdDetail_info($this->input->post("idx"));
			$params['data'] = $data;
			$params['mod'] = 1;
		}
		
		return $this->load->view('/ajax/cocd_detail',$params);
	}

	//공통코드 head update
	public function set_cocd_HeadUpdate()
	{
		$params['mod']      = $this->input->post("mod");
		$params['CODE']    = $this->input->post("CODE");
		$params['NAME']    = $this->input->post("NAME");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['IDX']        = $this->input->post("IDX");

		$params['INSERT_ID'] = $this->session->userdata('user_name');

		$ins = $this->main_model->codeHead_update($params);
		
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

	
	//공통코드 detail update
	public function set_cocd_DetailUpdate()
	{
		$params['mod']       = $this->input->post("mod");
		$params['H_IDX']     = $this->input->post("H_IDX");
		$params['S_NO']     = $this->input->post("S_NO");
		$params['CODE']     = $this->input->post("CODE");
		$params['NAME']     = $this->input->post("NAME");
		$params['REMARK']  = $this->input->post("REMARK");
		$params['IDX']        = $this->input->post("IDX");

		$params['INSERT_ID'] = $this->session->userdata('user_name');



		$ins = $this->main_model->codeDetail_update($params);
		
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



	public function set_cocdHead_delete()
	{

		$del = $this->main_model->delete_cocdHead($_POST['code']);
		echo $del;
		
	}


	public function set_cocdDetail_delete()
	{

		$del = $this->main_model->delete_cocdDetail($_POST['idx']);
		echo $del;
		
	}


	/* head code 중복체크 */
	public function ajax_cocdHaedchk()
	{
		//중복검사
        $parem = $this->input->post("code");
        $chk = $this->main_model->ajax_cocdHaedchk('CODE',$parem);
        if ($chk > 0) {
            $data['state'] = "N";
            $data['msg'] = "중복된 코드입니다.";
        } else {
            $data['state'] = "Y";
            $data['msg'] = "";
        }

        echo json_encode($data);
		
	}


	/* head code 중복체크 */
	public function ajax_cocdDetailchk()
	{
		//중복검사
        $parem = $this->input->post("code");
        $chk = $this->main_model->ajax_cocdDetailchk('CODE',$parem);
        if ($chk > 0) {
            $data['state'] = "N";
            $data['msg'] = "중복된 코드입니다.";
        } else {
            $data['state'] = "Y";
            $data['msg'] = "";
        }

        echo json_encode($data);
		
	}




	public function infoform($idx="")
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
		

		$data['title'] = "인사정보등록";
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


		
		$this->load->view('/register/infoform',$data);
	}



	public function infolist($idx="")
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



	public function ajax_set_memberinfo()
	{
		$mode = $this->input->post("mode");
		$idx  = $this->input->post("idx");

		$data = array();
		if(!empty($idx)){
			$data['memInfo'] = $this->register_model->get_member_info($idx);
		}
		
		$this->load->view('/register/ajax_infoform',$data);
	}


	/* 품목관리 스타트 */
	public function series($hid='')
	{
		check_pageLevel(); //helper 페이지권한
		$data['series_headList'] = $this->main_model->get_seriesHead_list();
		$data['series_detailList'] = $this->main_model->get_seriesDetail_list($hid);
		
		$data['H_IDX'] = $hid;
		$data['de_show_chk']= ($hid != "")?true:false;

		$this->load->view('/mdm/series',$data);
	}
	
	/* 공통코드 HEAD 폼 호출 */
	public function ajax_seriesHead_form()
	{
		$params['title'] = "시리즈-HEAD";
		$params['mod'] = 0;
		
		
		if($_POST['mode'] == "mod"){
			$params['title'] .= " - 수정";
			$data = $this->main_model->get_seriesHead_info($_POST['IDX']);
			$params['data'] = $data;
			$params['mod'] = 1;
		}
		
		
		return $this->load->view('/ajax/series_head',$params);
	}


	public function ajax_seriesDetail_form()
	{
		$params['title'] = "공통코드-DETAIL";
		$params['mod'] = '';
		$params['headList']  = $this->main_model->get_seriesHead_list();
		$params['hidx'] = $this->input->post("idx");
		

		if($_POST['mode'] == "mod"){
			$params['title'] .= " - 수정";
			$params['data'] = $this->main_model->get_seriesDetail_info($this->input->post("idx"));
			$params['mod'] = 1;
		}
		
		return $this->load->view('/ajax/series_detail',$params);
	}

	//공통코드 head update
	public function set_series_HeadUpdate()
	{
		$params['mod']      = $this->input->post("mod");
		$params['SERIES']    = $this->input->post("SERIES");
		$params['SERIES_NM']    = $this->input->post("SERIES_NM");
		$params['USE_YN'] = $this->input->post("USE_YN");
		$params['IDX']        = $this->input->post("IDX");

		$params['INSERT_ID'] = $this->session->userdata('user_name');

		$ins = $this->main_model->seriesHead_update($params);
		
		$data['status'] = "";
		$data['msg']    = "";


		if($ins != ""){
			$data = array(
				'status' => 'ok',
				'msg'    => '시리즈를 등록했습니다.'
			);
			echo json_encode($data);
		}
	}

	
	//공통코드 detail update
	public function set_series_DetailUpdate()
	{
		
		$params['mod']       = $this->input->post("mod");
		$params['SERIES']     = $this->input->post("H_IDX");
		$params['COLOR_CD']     = $this->input->post("COLOR_CD");
		$params['COLOR']     = $this->input->post("COLOR");
		$params['USE_YN']  = $this->input->post("USE_YN");
		$params['IDX']        = $this->input->post("D_IDX");

		$params['INSERT_ID'] = $this->session->userdata('user_name');


		$ins = $this->main_model->seriesDetail_update($params);
		
		$data['status'] = "";
		$data['msg']    = "";

		$text = (!empty($this->input->post("mod")))?"수정":"등록";


		if($ins != ""){
			$data = array(
				'status' => 'ok',
				'msg'    => '코드를 '.$text.'했습니다.'
			);
			echo json_encode($data);
		}
	}






	/* head code 중복체크 */
	public function ajax_seriesHaedchk()
	{
		//중복검사
        $parem = $this->input->post("code");
        $chk = $this->main_model->ajax_seriesHaedchk('SERIES',$parem);
        if ($chk > 0) {
            $data['state'] = "N";
            $data['msg'] = "중복된 코드입니다.";
        } else {
            $data['state'] = "Y";
            $data['msg'] = "";
        }

        echo json_encode($data);
		
	}


	/* head code 중복체크 */
	public function ajax_seriesDetailchk()
	{
		//중복검사
        $parem = $this->input->post("code");
        $chk = $this->main_model->ajax_cocdDetailchk('CODE',$parem);
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
