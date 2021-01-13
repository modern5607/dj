<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}



	public function set_temp_data($param)
	{
		$data = array(
			'GJ_GB'        => $param['GJ_GB'],
			'LOT_NO'       => $param['item'][1],
			'BL_NO'        => $param['item'][2],
			'ST_DATE'      => $param['item'][3],
			'QTY'          => $param['item'][4],
			'UNIT'         => $param['item'][5],
			'STATE'        => $param['item'][6],
			'SASIZ'        => $param['item'][7],
			'PL_QTY'       => $param['item'][8],
			'GJ_CODE'      => $param['item'][9],
			'GJ_NAME'      => $param['item'][10],
			'GJ_QTY'       => $param['item'][11],
			'ACT_DATE'     => $param['item'][13],
			'PLN_DATE'     => $param['item'][14],
			'INSERT_DATE'  => date('Y-m-d H:i:s',time()),
			'INSERT_ID'    => $this->session->userdata('user_name'),
		);	

		$this->db->insert($param['table'],$data);
	}

	
	/* T_COMPONENT_EX UPLOAD*/
	public function set_component_data($param)
	{
		$data = array(
			'GJ_GB'        => $param['GJ_GB'],
			'COMPONENT'    => $param['item'][0],
			'COMPONENT_NM' => $param['item'][1],
			'STOCK'        => $param['item'][2],
			'UNIT'         => $param['item'][3],
			'INTO_DATE'    => $param['item'][4],
			'REPL_DATE'    => $param['item'][5],
			'INSERT_DATE'  => date('Y-m-d H:i:s',time()),
			'INSERT_ID'    => $this->session->userdata('user_name')
		);	
		
		$this->db->insert($param['table'],$data);
	}



	public function set_matform_data($param)
	{
		$data = array(
			'GJ_GB'        => $param['GJ_GB'],
			'IPGO_DATE'    => $param['item'][0],
			'COMPONENTNO'  => trim($param['item'][1]),
			'RANK'         => $param['item'][2],
			'NO'           => $param['item'][3],
			'LOT_NO'       => $param['item'][4],
			'QTY'          => $param['item'][5],
			'GUBUN'        => $param['item'][6],
			'STATE'        => $param['item'][7]
		);	
		
		$this->db->insert($param['table'],$data);
	}





	public function delete_component_ex()
	{
		$this->db->truncate("T_COMPONENT_EX");
	}


	public function delete_matform_ex()
	{
		$this->db->truncate("T_TEMP_COM");
	}


	public function delete_actpln_ex()
	{
		$this->db->truncate("T_ACTPLN_EX");
	}


	public function get_none_count()
	{
		$this->db->select("COUNT(*) AS XXX");
		$this->db->where("TC.COMPONENT",NULL);
		$this->db->from("T_COMPONENT as TC");
		$this->db->join("T_COMPONENT_EX as EX","EX.COMPONENT = TC.COMPONENT AND EX.GJ_GB = TC.GJ_GB","right");
		$this->db->order_by("TC.STOCK","ASC");
		$query = $this->db->get();
				
		return $query->row()->XXX;

	}


	public function get_items_list($param,$start=0,$limit=20)
	{
		if(!empty($param['SERIES_IDX']) && $param['SERIES_IDX'] != ""){
			$this->db->where("SERIES_IDX",$param['SERIES_IDX']);
		}

		if(!empty($param['ITEM_NO']) && $param['ITEM_NO'] != ""){
			$this->db->like("ITEM_NO",$param['ITEM_NO']);
		}

		if(!empty($param['ITEM_NAME']) && $param['ITEM_NAME'] != ""){
			$this->db->like("ITEM_NAME",$param['ITEM_NAME']);
		}

		if(!empty($param['USE_YN']) && $param['USE_YN'] != ""){
			$this->db->where("USE_YN",$param['USE_YN']);
		}

		$this->db->limit($limit,$start);
		$query = $this->db->get("t_items");

		return $query->result();
	}


	public function get_items_cnt($param)
	{
		$this->db->select("COUNT(*) CUT");
		if(!empty($param['SERIES_IDX']) && $param['SERIES_IDX'] != ""){
			$this->db->where("SERIES_IDX",$param['SERIES_IDX']);
		}

		if(!empty($param['ITEM_NO']) && $param['ITEM_NO'] != ""){
			$this->db->where("ITEM_NO",$param['ITEM_NO']);
		}

		if(!empty($param['ITEM_NAME']) && $param['ITEM_NAME'] != ""){
			$this->db->where("ITEM_NAME",$param['ITEM_NAME']);
		}

		if(!empty($param['USE_YN']) && $param['USE_YN'] != ""){
			$this->db->where("USE_YN",$param['USE_YN']);
		}
		
		$query = $this->db->get("t_items");
		return $query->row()->CUT;
	}

	public function get_component_list($param,$start=0,$limit=20)
	{
		
		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$this->db->like("COMPONENT",$param['COMPONENT']);
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$this->db->like("COMPONENT_NM",$param['COMPONENT_NM']);
		}

		if(!empty($param['USE_YN']) && $param['USE_YN'] != ""){
			$this->db->where("USE_YN",$param['USE_YN']);
		}

		$this->db->limit($limit,$start);
		$query = $this->db->get("t_component");

		//echo $this->db->last_query();

		return $query->result();
	}


	public function get_component_cnt($param)
	{
		$this->db->select("COUNT(*) CUT");
		
		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$this->db->where("COMPONENT",$param['COMPONENT']);
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$this->db->where("COMPONENT_NM",$param['COMPONENT_NM']);
		}

		if(!empty($param['USE_YN']) && $param['USE_YN'] != ""){
			$this->db->where("USE_YN",$param['USE_YN']);
		}
		
		$query = $this->db->get("t_component");
		return $query->row()->CUT;
	}




	
	/* 공통코드 HEAD 등록 */
	public function codeHead_update($param)
	{

		if($param['mod'] == 1){

			$dateTime = date("Y-m-d H:i:s",time());
			
			$data = array(
				'CODE'        => $param['CODE'],
				'NAME'        => $param['NAME'],
				'REMARK'      => $param['REMARK'],
				'UPDATE_ID'   => $param['INSERT_ID'],
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->update("T_COCD_H",$data,array("IDX"=>$param['IDX']));
			return $param['IDX'];
		
		}else{

			$dateTime = date("Y-m-d H:i:s",time());

			$data = array(
				'CODE'        => $param['CODE'],
				'NAME'        => $param['NAME'],
				'REMARK'      => $param['REMARK'],
				'INSERT_ID'   => $param['INSERT_ID'],
				'INSERT_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->insert("T_COCD_H",$data);

			return $this->db->insert_id();

		}

		

	}


	/* 공통코드 Detail 등록 */
	public function codeDetail_update($param)
	{

		if($param['mod'] == 1){

			$dateTime = date("Y-m-d H:i:s",time());
			

			$data = array(
				'H_IDX'           => $param['H_IDX'],
				'S_NO'            => $param['S_NO'],
				'CODE'           => $param['CODE'],
				'NAME'           => $param['NAME'],
				'REMARK'        => $param['REMARK'],
				'UPDATE_ID'    => $param['INSERT_ID'],
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->update("T_COCD_D", $data, array("IDX"=>$param['IDX']));
			return $param['IDX'];
		
		}else{

			$dateTime = date("Y-m-d H:i:s",time());
			
			$data = array(
				'H_IDX'       => $param['H_IDX'],
				'S_NO'        => $param['S_NO'],
				'CODE'        => $param['CODE'],
				'NAME'        => $param['NAME'],
				'REMARK'      => $param['REMARK'],
				'INSERT_ID'   => $param['INSERT_ID'],
				'INSERT_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			
			$this->db->insert("T_COCD_D",$data);

			return $this->db->insert_id();

		}

		

	}

	
	public function get_items_info($idx)
	{
		$query = $this->db->where("IDX",$idx)
						->get("t_items");
		return $query->row();
	}


	public function get_component_info($idx)
	{
		$query = $this->db->where("IDX",$idx)
						->get("t_component");
		return $query->row();
	}



	/* 품목관리 등록 */
	public function set_items_formUpdate($param)
	{
		$dateTime = date("Y-m-d H:i:s",time());

		if($param['mod'] == 1){

			$data = array(
				'ITEM_NO'     => $param['ITEM_NO'],
				'ITEM_NAME'   => $param['ITEM_NAME'],
				'SPEC'        => $param['SPEC'],
				'SERIES_IDX'  => $param['SERIES'],
				'UNIT'        => $param['UNIT'],
				'BK_YN'       => $param['BK_YN'],
				'JT_QTY'      => $param['JT_QTY'],
				'BIZ_IDX'     => $param['BIZ_IDX'],
				'USE_YN'      => $param['USE_YN'],
				'UPDATE_ID'   => $param['INSERT_ID'],
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => '',
				'COL3'        => ''
			);
			$this->db->update("t_items", $data, array("IDX"=>$param['IDX']));
			return $param['IDX'];
		
		}else{			
			
			$data = array(
				'ITEM_NO'     => $param['ITEM_NO'],
				'ITEM_NAME'   => $param['ITEM_NAME'],
				'SPEC'        => $param['SPEC'],
				'SERIES_IDX'  => $param['SERIES'],
				'UNIT'        => $param['UNIT'],
				'BK_YN'       => $param['BK_YN'],
				'JT_QTY'      => $param['JT_QTY'],
				'BIZ_IDX'     => $param['BIZ_IDX'],
				'USE_YN'      => $param['USE_YN'],
				'INSERT_ID'   => $param['INSERT_ID'],
				'INSERT_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => '',
				'COL3'        => ''
			);
			
			$this->db->insert("t_items",$data);

			return $this->db->insert_id();

		}

		

	}


	public function set_component_formUpdate($param)
	{
		if($param['mod'] == 1){

			$data = array(
				'COMPONENT'     => $param['COMPONENT'],
				'COMPONENT_NM'   => $param['COMPONENT_NM'],
				'SPEC'        => $param['SPEC'],
				'UNIT'        => $param['UNIT'],
				'USE_YN'      => $param['USE_YN'],
				'UPDATE_ID'   => $param['INSERT_ID'],
				'UPDATE_DATE' => $param['INSERT_DATE'],
				'COL1'        => '',
				'COL2'        => '',
				'COL3'        => ''
			);
			$this->db->update("t_component", $data, array("IDX"=>$param['IDX']));
			return $param['IDX'];
		
		}else{			
			
			$data = array(
				'COMPONENT'     => $param['COMPONENT'],
				'COMPONENT_NM'   => $param['COMPONENT_NM'],
				'SPEC'        => $param['SPEC'],
				'UNIT'        => $param['UNIT'],
				'USE_YN'      => $param['USE_YN'],
				'INSERT_ID'   => $param['INSERT_ID'],
				'INSERT_DATE' => $param['INSERT_DATE'],
				'COL1'        => '',
				'COL2'        => '',
				'COL3'        => ''
			);
			
			$this->db->insert("t_component",$data);

			return $this->db->insert_id();

		}

		

	}



	/* 공통코드 HEAD 리스트 */
	public function get_cocdHead_list()
	{
		$res = $this->db->get("T_COCD_H");
		return $res->result();

	}

	/* 공통코드 Detail 리스트 */
	public function get_cocdDetail_list($hid = "")
	{
		$this->db->select("D.*,H.CODE as H_CODE");
		$this->db->from("T_COCD_D as D");
		$this->db->join("T_COCD_H as H","H.IDX = D.H_IDX");
		if($hid){
			$this->db->where("H_IDX",$hid);
		}
		$this->db->order_by("S_NO","ASC");
		$res = $this->db->get();


		return $res->result();

	}

	
	/* 공통코드 HEAD 상세정보 */
	public function get_cocdHead_info($idx)
	{
		$res = $this->db->where("IDX",$idx)
						->get("T_COCD_H");
		return $res->row();
	}


	/* 공통코드 Detail 상세정보 */
	public function get_cocdDetail_info($idx)
	{
		$res = $this->db->where("IDX",$idx)
						->get("T_COCD_D");
		return $res->row();
	}


	/* 시리즈 HEAD 리스트 */
	public function get_seriesHead_list()
	{
		$res = $this->db->get("t_series_h");
		return $res->result();

	}

	/* 시리즈 Detail 리스트 */
	public function get_seriesDetail_list($sid = "")
	{
		$this->db->select("D.*");
		$this->db->from("t_series_d as D");
		$this->db->join("t_series_h as H","H.IDX = D.SERIES_IDX");
		if($sid){
			$this->db->where("D.SERIES_IDX",$sid);
		}
		$this->db->order_by("D.IDX","ASC");
		$res = $this->db->get();


		return $res->result();

	}

	
	/* 시리즈 HEAD 상세정보 */
	public function get_seriesHead_info($idx)
	{
		$res = $this->db->where("IDX",$idx)
						->get("t_series_h");
		return $res->row();
	}


	/* 시리즈 Detail 상세정보 */
	public function get_seriesDetail_info($idx)
	{
		$res = $this->db->where("IDX",$idx)
						->get("t_series_d");
		return $res->row();
	}

	/* 시리즈 HEAD 등록 */
	public function seriesHead_update($param)
	{
		if($param['mod'] == 1){

			$dateTime = date("Y-m-d H:i:s",time());
			
			$data = array(
				'SERIES'      => $param['SERIES'],
				'SERIES_NM'   => $param['SERIES_NM'],
				'USE_YN'      => $param['USE_YN'],
				'UPDATE_ID'   => $param['INSERT_ID'],
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->update("t_series_h",$data,array("IDX"=>$param['IDX']));
			return $param['IDX'];
		
		}else{

			$dateTime = date("Y-m-d H:i:s",time());

			$data = array(
				'SERIES'      => $param['SERIES'],
				'SERIES_NM'   => $param['SERIES_NM'],
				'USE_YN'      => $param['USE_YN'],
				'INSERT_ID'   => $param['INSERT_ID'],
				'INSERT_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->insert("t_series_h",$data);

			return $this->db->insert_id();

		}

		

	}


	/* 시리즈 Detail 등록 */
	public function seriesDetail_update($param)
	{
		if($param['mod'] == 1){

			$dateTime = date("Y-m-d H:i:s",time());
			

			$data = array(
				'SERIES_IDX'  => $param['SERIES'],
				'COLOR_CD'    => $param['COLOR_CD'],
				'COLOR'       => $param['COLOR'],
				'USE_YN'      => $param['USE_YN'],
				'UPDATE_ID'   => $param['INSERT_ID'],
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->update("t_series_d", $data, array("IDX"=>$param['IDX']));
			return $param['IDX'];
		
		}else{

			$dateTime = date("Y-m-d H:i:s",time());
			
			$data = array(
				'SERIES_IDX'  => $param['SERIES'],
				'COLOR_CD'    => $param['COLOR_CD'],
				'COLOR'       => $param['COLOR'],
				'USE_YN'      => $param['USE_YN'],
				'INSERT_ID'   => $param['INSERT_ID'],
				'INSERT_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			
			$this->db->insert("t_series_d",$data);

			return $this->db->insert_id();

		}

		

	}


	/* 코드중복검사 */
	public function ajax_seriesHaedchk($object,$code)
	{
		$this->db->where($object,$code);
        $query = $this->db->get('t_series_h');
         
        if($query->num_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
	}

	/* 코드중복검사 */
	public function ajax_seriesDetailchk($object,$code)
	{
		$this->db->where($object,$code);
        $query = $this->db->get('t_series_d');
         
        if($query->num_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
	}

	
	/*
	* 공통코드 헤드 삭제
	* 공통코드 디테일정보도 같이 삭제한다.
	*/
	public function delete_cocdHead($code)
	{
		$res = $this->db->delete("T_COCD_H",array('CODE'=>$code));
		$res1 = $this->db->delete("T_COCD_D",array('H_IDX'=>$code));

		return $this->db->affected_rows();
	}

	public function delete_cocdDetail($idx)
	{
		$res = $this->db->delete("T_COCD_D",array('IDX'=>$idx));
		return $this->db->affected_rows();
	}


	/* 코드중복검사 */
	public function ajax_cocdHaedchk($object,$code)
	{
		$this->db->where($object,$code);
        $query = $this->db->get('T_COCD_H');
         
        if($query->num_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
	}

	/* 코드중복검사 */
	public function ajax_cocdDetailchk($object,$code)
	{
		$this->db->where($object,$code);
        $query = $this->db->get('T_COCD_D');
         
        if($query->num_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
	}


	/*
	* 특정 공통코드의 디테일리스트를 호출
	*/
	public function get_selectInfo($fild,$set)
	{
		$where[$fild] = $set;
		$this->db->select("tch.IDX, tch.CODE, tch.NAME, tcd.CODE as D_CODE, tcd.NAME as D_NAME");
		$this->db->from("t_cocd_d as tcd");
		$this->db->join("t_cocd_h as tch","tch.IDX = tcd.H_IDX");
		$this->db->where($where);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();		
	}


	//거래처리스트
	public function get_custlist()
	{
		$query = $this->db->get("t_biz_reg");
		return $query->result();

	}


	public function get_seriesh_select()
	{
		$query = $this->db->get("t_series_h");
		return $query->result();
	}

	
	public function ajax_component_select()
	{
		$query = $this->db->get("t_component");
		return $query->result();
	}



}