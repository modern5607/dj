<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PLN extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		
		$this->load->helper('test');
		$this->load->model(array('pln_model','main_model'));

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

	/* 수주관리 */
	public function index($hidx='')
	{
		check_pageLevel();
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = $this->input->get('sdate'); //수주일
		$data['str']['edate'] = $this->input->get('edate'); //수주일
		$data['str']['custnm'] = $this->input->get('custnm'); //거래처
		$data['str']['actnm'] = $this->input->get('actnm'); //수주명
		
		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['CUSTNM'] = "";
		$params['ACTNM'] = "";

		$data['HIDX'] = (!empty($hidx))?$hidx:"";

		$data['qstr'] = "?P";
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];
			
		}
		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];
			
		}
		if(!empty($data['str']['custnm'])){
			$params['CUSTNM'] = $data['str']['custnm'];
			$data['qstr'] .= "&custnm=".$data['str']['custnm'];
			
		}
		if(!empty($data['str']['actnm'])){
			$params['ACTNM'] = $data['str']['actnm'];
			$data['qstr'] .= "&actnm=".$data['str']['actnm'];
			
		}

		
		$data['perpage'] = ($this->input->get('perpage') != "")?$this->input->get('perpage'):20;
		
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

        $pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;
		
		$start = $pageNum;
		$data['pageNum'] = $start;
		
		$data['title'] = "수주관리";
		
		$data['contList']   = $this->pln_model->get_pln_list($params,$start,$config['per_page']);
		$this->data['cnt']  = $this->pln_model->get_pln_count($params);
		
		if(empty($this->input->get('n'))){
			//상단정보
			$data['headInfo']  = $this->pln_model->get_pln_info($hidx);
			$data['detailList'] = $this->pln_model->get_pln_detail($hidx);
			
		}else{
			$data['HIDX'] = null;
		}
		
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
		$this->load->view('/pln/index',$data);
	}


	public function p1()
	{
		check_pageLevel();
	}


	public function p2()
	{
		check_pageLevel();

		$data['title'] = "";
		
		$prefs = array(
				'start_day'    => 'sunday',
				'month_type'   => 'short',
				'day_type'     => 'short',
				'show_next_prev'  => true,
				'show_other_days' => false,
				'next_prev_url'   => base_url('PLN/p2/')
		);

		$year = (!empty($this->uri->segment(3)))?$this->uri->segment(3):date("Y",time());
		$month = (!empty($this->uri->segment(4)))?$this->uri->segment(4):date("m",time());

		

		$prefs['template'] = '

				{table_open}<table border="0" cellpadding="0" cellspacing="3" id="calendar">{/table_open}

				{heading_row_start}<tr class="headset">{/heading_row_start}

				{heading_previous_cell}
				<th>
					<a href="{previous_url}" class="moveBtn">이전달</a>
				</th>
				{/heading_previous_cell}

				{heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
				
				{heading_next_cell}
				<th>
					<a href="{next_url}" class="moveBtn">다음달</a>
				</th>
				{/heading_next_cell}

				{heading_row_end}</tr>{/heading_row_end}

				{week_row_start}<tr class="week">{/week_row_start}
				{week_row_class_start}<td class="{week_class}">{/week_row_class_start}
				{week_day_cell}{week_day}{/week_day_cell}
				{week_row_class_end}</td>{/week_row_class_end}
				{week_row_end}</tr>{/week_row_end}

				{cal_row_start}<tr>{/cal_row_start}
				{cal_cell_start}<td>{/cal_cell_start}
				{cal_cell_start_today}<td>{/cal_cell_start_today}
				{cal_cell_start_other}<td class="other-month">{/cal_cell_start_other}

				{cal_cell_content}
					<div class="xday" data-date="'.$year.'-'.$month.'-{day}">
						{day}
						<div class="cont">{content}</div>
					</div>
				{/cal_cell_content}

				{cal_cell_content_today}
					<div class="xday highlight"  data-date="'.$year.'-'.$month.'-{day}">
						{day}
						<div class="cont">{content}</div>
					</div>
				{/cal_cell_content_today}

				{cal_cell_no_content}
				
					<div class="xday" data-date="'.$year.'-'.$month.'-{day}">{day}</div>			
				
				{/cal_cell_no_content}

				{cal_cell_no_content_today}
					<div class="xday highlight" data-date="'.$year.'-'.$month.'-{day}">{day}</div>
				{/cal_cell_no_content_today}

				{cal_cell_blank}&nbsp;{/cal_cell_blank}

				{cal_cell_other}{day}{cal_cel_other}

				{cal_cell_end}</td>{/cal_cell_end}
				{cal_cell_end_today}</td>{/cal_cell_end_today}
				{cal_cell_end_other}</td>{/cal_cell_end_other}
				{cal_row_end}</tr>{/cal_row_end}

				{table_close}</table>{/table_close}
		';

		$this->load->library('calendar', $prefs);

		$info = $this->pln_model->get_p2_list($year, $month);
		$contArray = array();
		foreach($info as $ndate){
			list($y,$m,$d) = explode("-",$ndate->WOEK_DATE);
			$contArray[$d] = $ndate->REMARK;
		}


		$data['calendar'] = $this->calendar->generate($this->uri->segment(3), $this->uri->segment(4),$contArray);

		$this->load->view('/pln/p2',$data);


	}


	public function ajax_set_p2_info()
	{
		$data['title'] = "생산계획등록";
		$data['setDate'] = $this->input->post("xdate");
		
		$data['INFO'] = $this->pln_model->get_p2_info($this->input->post("xdate"));
		

		$this->load->view('/pln/ajax_p2_set',$data);
	}

	
	public function ajax_p2_insert()
	{
		$params['WOEK_DATE'] = $this->input->post("WOEK_DATE");
		$params['REMARK'] = $this->input->post("REMARK");
		$data = $this->pln_model->ajax_p2_insert($params);
		echo json_encode($data);
	}


	public function p3()
	{
		check_pageLevel();
	}



	/* ajax */

	//수주관리 - 수주등록팝업
	public function ajax_plnHead_form()
	{
		$data['title'] = "수주등록";
		$data['mode'] = $this->input->post("mode");
		$data['CUST'] = $this->main_model->get_custlist();
		
		if($this->input->post("mode") == "mod"){
			$data['data'] = $this->pln_model->get_pln_info($this->input->post("IDX"));
		}

		return $this->load->view('/ajax/ajax_plnHead_form',$data);
	}


	public function ajax_plnDetail_form()
	{
		$data['title'] = "수주항목등록";
		$data['mode'] = $this->input->post("mode");
		$data['SERIES'] = $this->main_model->get_seriesh_select();
		$data['HIDX'] = $this->input->post("hidx");
		

		if($this->input->post("mode") == "mod"){
			$data['data'] = $this->pln_model->get_pln_info($this->input->post("IDX"));
		}

		return $this->load->view('/ajax/ajax_plnDetail_form',$data);
	}


	public function ajax_plnHead_insert()
	{

		$params['ACT_DATE'] = $this->input->post("ACT_DATE");
		$params['BIZ_IDX'] = $this->input->post("CUST");
		$params['ACT_NAME'] = $this->input->post("ACT_NAME");
		$params['DEL_DATE'] = $this->input->post("DEL_DATE");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['ORD_TEXT'] = $this->input->post("ORD_TEXT");
		$params['END_YN'] = "N";
		$params['INSERT_ID']   = $this->session->userdata('user_id');
		$params['INSERT_DATE'] = date("Y-m-d H:i:s",time());

		$num = $this->pln_model->ajax_plnHead_insert($params);
		if($num > 0){
			$data['status'] = "ok";
			$data['msg'] = "수주가 등록되었습니다.";
		}else{
			$data['status'] = "";
			$data['msg'] = "수주등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);

	}


	public function ajax_set_series_select()
	{
		$data = $this->pln_model->set_series_select($this->input->post("idx"));
		echo json_encode($data);
	}


	public function ajax_plnHead_update()
	{
		$params['IDX'] = $this->input->post("IDX");
		$params['ACT_DATE'] = $this->input->post("ACT_DATE");
		$params['BIZ_IDX'] = $this->input->post("CUST");
		$params['ACT_NAME'] = $this->input->post("ACT_NAME");
		$params['DEL_DATE'] = $this->input->post("DEL_DATE");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['ORD_TEXT'] = $this->input->post("ORD_TEXT");
		$params['UPDATE_ID']   = $this->session->userdata('user_id');
		$params['UPDATE_DATE'] = date("Y-m-d H:i:s",time());

		$this->pln_model->ajax_plnHead_insert($params);

	}


	public function ajax_plnindex_pop()
	{
		$params['s1'] = $this->input->post("s1");
		$params['s2'] = $this->input->post("s2");
		$params['s3'] = $this->input->post("s3");
		
		$data = $this->pln_model->ajax_plnindex_pop($params);
		echo json_encode($data);
	}


	public function ajax_act_detail_insert()
	{
		foreach($this->input->post("QTY") as $key => $qty){
			if($qty == ""){
				continue;
			}else{
				$params['QTY'][$key] = $qty;
				$params['ITEM_IDX'][$key] = $this->input->post("ITEM_IDX")[$key];
				$params['ITEM_NM'][$key] = $this->input->post("ITEM_NM")[$key];
				$params['SERIESD_IDX'][$key] = $this->input->post("SERIESD_IDX")[$key];
				$params['REMARK'][$key] = $this->input->post("REMARK")[$key];
				$params['H_IDX'][$key] = $this->input->post("H_IDX")[$key];
			}
		}

		$num = $this->pln_model->ajax_act_detail_insert($params);
		
		if($num > 0){
			$data['status'] = "ok";
			$data['msg'] = "수주항목이 추가되었습니다.";
		}else{
			$data['status'] = "";
			$data['msg'] = "수주항목 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);

	}


	

	


}//class end
