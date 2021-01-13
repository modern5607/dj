<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//=================================================================================================
// 처리내용 : 엑셀파일 컨트롤
// 작 성 자 : 2020-08-20 이창준
//=================================================================================================


class Excel extends CI_Controller {

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

	public function upload_act()
	{
		
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		$filepath = $_FILES['xfile']['tmp_name'];
		$startRow = $this->input->post("rownum");
		$table    = $this->input->post("table");

		$this->load->dbforge();
		

		$filetype = PHPExcel_IOFactory::identify($filepath);
		$reader = PHPExcel_IOFactory::createReader($filetype);
		$php_excel = $reader->load($filepath);

		$sheet = $php_excel->getSheet(0);           // 첫번째 시트
		$maxRow = $sheet->getHighestRow();          // 마지막 라인
		$maxColumn = $sheet->getHighestColumn();    // 마지막 칼럼

		$maxCol_num = PHPExcel_Cell::columnIndexFromString($maxColumn);


		$target = "A"."{$startRow}".":"."$maxColumn"."$maxRow";
		$lines = $sheet->rangeToArray($target, NULL, TRUE, FALSE);

		$data['table'] = $table;
		$data['GJ_GB'] = $this->input->post("GJ_GB");
		
		
		/*
		* 테이블생성 주석처리 --- jun
		$this->dbforge->add_field('id');
		$fields = array();
		
		for($k=0; $k<$maxCol_num; $k++){
			$fi = array(
					't_'.$k => array(
							'type' => 'VARCHAR',
							'constraint' => '255',
							'default' => null
					)
			);
			$this->dbforge->add_field($fi);
			
		}
		$attributes = array('ENGINE' => 'MyISAM');
		$createTables = $this->dbforge->create_table($data['table'], TRUE, $attributes);

		*/
		
			
		$this->main_model->delete_actpln_ex();

		// 라인수 만큼 루프
		foreach ($lines as $key => $line) {
			
			$col = 0;
			
			for($i=0; $i<count($line); $i++){
				
				if($i == 3 || $i == 13 || $i == 14){ //날짜형 cell은 변환해서 업로드
					$item[$i] = date("Y-m-d H:i:s",PHPExcel_Shared_Date::ExcelToPHP($line[$col++]));
				}else{
					$item[$i] = $line[$col++];
				}
			}
			$data['item'] = $item;
			$this->main_model->set_temp_data($data);
			
		}
		
		
		redirect(base_url('act/temp'));


	}




	public function upload_matform()
	{
		
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		$filepath = $_FILES['xfile']['tmp_name'];
		$startRow = $this->input->post("rownum");
		$table    = $this->input->post("table");

		$this->load->dbforge();
		

		$filetype = PHPExcel_IOFactory::identify($filepath);
		$reader = PHPExcel_IOFactory::createReader($filetype);
		$php_excel = $reader->load($filepath);

		$sheet = $php_excel->getSheet(0);           // 첫번째 시트
		$maxRow = $sheet->getHighestRow();          // 마지막 라인
		$maxColumn = $sheet->getHighestColumn();    // 마지막 칼럼

		$maxCol_num = PHPExcel_Cell::columnIndexFromString($maxColumn);


		$target = "A"."{$startRow}".":"."$maxColumn"."$maxRow";
		$lines = $sheet->rangeToArray($target, NULL, TRUE, FALSE);

		$data['table'] = $table;
		$data['GJ_GB'] = $this->input->post("GJ_GB");
		
		
			
		$this->main_model->delete_matform_ex();
		

		// 라인수 만큼 루프
		foreach ($lines as $key => $line) {
			
			$col = 0;

			if($line[1] == "" && $line[2] == "" && $line[3] == ""){
				continue;
			}
			
			for($i=0; $i<count($line); $i++){

				if($i == 0){ //날짜형 cell은 변환해서 업로드
					//$item[$i] = date("Y-m-d H:i:s",PHPExcel_Shared_Date::ExcelToPHP($line[$col++]));
					list($year,$n,$time) = explode(" ",$line[$col++]);
					$item[$i] = $year." ".$time;
				}else{
					$item[$i] = $line[$col++];
				}
			}
			$data['item'] = $item;
			
			$this->main_model->set_matform_data($data);
			
		}
		
			
		
		redirect(base_url('mat/matform_temp'));


	}



	public function upload_mat()
	{
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		$filepath = $_FILES['xfile']['tmp_name'];
		$startRow = $this->input->post("rownum");
		$data['table']    = $this->input->post("table");
		$data['GJ_GB']    = $this->input->post("GJ_GB");

		$this->load->dbforge();
		

		$filetype = PHPExcel_IOFactory::identify($filepath);
		$reader = PHPExcel_IOFactory::createReader($filetype);
		$php_excel = $reader->load($filepath);

		$sheet = $php_excel->getSheet(0);           // 첫번째 시트
		$maxRow = $sheet->getHighestRow();          // 마지막 라인
		$maxColumn = $sheet->getHighestColumn();    // 마지막 칼럼

		$maxCol_num = PHPExcel_Cell::columnIndexFromString($maxColumn);


		$target = "A"."{$startRow}".":"."$maxColumn"."$maxRow";
		$lines = $sheet->rangeToArray($target, NULL, TRUE, FALSE);

		$this->main_model->delete_component_ex();

		// 라인수 만큼 루프
		foreach ($lines as $key => $line) {
			
			$col = 0;
			
			for($i=0; $i<count($line); $i++){
				
				if($i == 4 || $i == 5){ //날짜형 cell은 변환해서 업로드
					$item[$i] = date("Y-m-d H:i:s",PHPExcel_Shared_Date::ExcelToPHP($line[$col++]));
				}else{
					$item[$i] = $line[$col++];
				}
			}
			$data['item'] = $item;
			$this->main_model->set_component_data($data);
			
		}
		
		$chk = $this->main_model->get_none_count();
		if($chk > 0){
			alert('자재데이터가 없는 자재('.$chk.')건이 있습니다.\n일괄수정시 자재데이터가 자동등록됩니다.',base_url('mat/materials'));
		}
		
		redirect('/mat/materials');


	}

	

	public function index()
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
        
        /*
         echo "<pre>";
         print_r($objPHPExcel);
         echo "</pre>";
         exit;
        */
        
        $headers = array('아이디','주문번호','판매자','주문상태','결제수단','주문합계','누적주문수','누적주문수','누적주문수','누적주문수');
        $last_char = column_char( count($headers) - 1 );
        $widths = array(15, 40, 30, 20, 20, 10, 15, 30, 30, 30);
                                        
        $objPHPExcel->setActiveSheetIndex(0);
        /** 상단 스타일지정 **/
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$last_char.'1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$last_char.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9EDF7');
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$last_char.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$last_char.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Nanum Gothic')->setSize(12);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        
        $objPHPExcel->getActiveSheet()->getStyle('A:'.$last_char)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        foreach($widths as $i => $w){
            $objPHPExcel->getActiveSheet()->setCellValue(column_char($i).'1', $headers[$i]);
            $objPHPExcel->setActiveSheetIndex()->getColumnDimension( column_char($i) )->setWidth($w);
        }
        
        $param['is_state'] = $this->input->get("is_state");
        $param['is_search'] = $this->input->get("is_search");
        
        /*$this->data['orderlist'] = $this->order_model->get_order_list($param);
        foreach ($this->data['orderlist']['res'] as $k=>$row) {
            $nnn[$k]['order_id'] = $row->order_id;
            $nnn[$k]['order_code'] = $row->order_code;
            $nnn[$k]['vendor_seq'] = "ddddd<br/>".$row->vendor_seq;
            $nnn[$k]['state'] = $row->state;
            $nnn[$k]['order_code'] = $row->order_code;
            $nnn[$k]['sumprice'] = number_format($row->sumprice);
            $nnn[$k]['ordnum']   = $row->ordnum;
            
        }*/
		
		$nnn[0]['order_id'] = 123;
		$nnn[0]['order_code'] = 1111;
		$nnn[0]['vendor_seq'] = 2222;
		$nnn[0]['state'] = "안녕";
		$nnn[0]['order_code'] = "asdfasfd";
		$nnn[0]['sumprice'] = 12313;
		$nnn[0]['ordnum']   = 111111;

        $rows = $nnn;
        $data = array_merge(array($headers), $rows);
        
        $objPHPExcel->getActiveSheet()->fromArray($data,NULL,'A1');
        
        
        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        header('Content-type: application/x-msexcel;charset=utf-8');
        header('Content-Disposition: attachment;filename="테스트엑셀파일.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
		
	}
	

	


}