<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pln_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}


	public function get_pln_list($param,$start=0,$limit=20)
	{
		
		$this->db->select("A.*,B.CUST_NM");
		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$this->db->where("A.ACT_DATE BETWEEN '{$param['SDATE']} 00:00:00' AND '{$param['EDATE']} 23:59:59'");
		}
		
		if(!empty($param['CUSTNM']) && $param['CUSTNM'] != ""){
			$this->db->like("B.CUST_NM",$param['CUSTNM']);
		}

		if(!empty($param['ACTNM']) && $param['ACTNM'] != ""){
			$this->db->like("A.ACT_NAME",$param['ACTNM']);			
		}

		$this->db->from("t_act_h as A");
		$this->db->join("t_biz_reg as B","B.IDX = A.BIZ_IDX");
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		return $query->result();

	}


	public function get_p2_list($year,$month)
	{
		$query = $this->db->like("WOEK_DATE",$year."-".$month)
						->get("T_WORKCAL");
		return $query->result();
	}

	public function get_p2_info($date)
	{
		$query = $this->db->where("WOEK_DATE",$date)->get("T_WORKCAL");
		return $query->row();
	}


	public function ajax_p2_insert($params)
	{
		$query = $this->db->where("WOEK_DATE",$params['WOEK_DATE'])
						->get("T_WORKCAL");
		$chk = $query->row();
		$data = array(
			"status" => "",
			"msg"    => ""
		);
		if(!empty($chk)){
			$this->db->set("REMARK",$params['REMARK']);
			$this->db->where("WOEK_DATE",$chk->WOEK_DATE);
			$this->db->update("T_WORKCAL");
			if($this->db->affected_rows()){
				$data['status'] = "ok";
				$data['msg'] = "수정되었습니다.";
			}
		}else{
			
			$datetime = date("Y-m-d H:i:s",time());
			$username = $this->session->userdata('user_name');

			$this->db->set("REMARK",$params['REMARK']);
			$this->db->set("WOEK_DATE",$params['WOEK_DATE']);
			$this->db->set("INSERT_DATE",$datetime);
			$this->db->set("INSERT_ID",$username);
			$this->db->insert("T_WORKCAL");
			
			if($this->db->affected_rows()){
				$data['status'] = "ok";
				$data['msg'] = "등록되었습니다.";
			}
		}

		return $data;
	}


	public function get_pln_count() {
		$this->db->select("COUNT(*) CUT");
		
		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$this->db->where(" ACT_DATE BETWEEN '{$param['SDATE']} 00:00:00' AND '{$param['EDATE']} 23:59:59'");
		}

		if(!empty($param['CUSTNM']) && $param['CUSTNM'] != ""){
			$this->db->like("CUSTNM",$param['CUSTNM']);
		}

		if(!empty($param['ACTNM']) && $param['ACTNM'] != ""){
			$this->db->like("ACTNM",$param['ACTNM']);			
		}
		
		$query = $this->db->get("t_act_h");
		return $query->row()->CUT;
	}


	public function get_pln_detail($idx='')
	{
		$this->db->select("A.*,B.COLOR");
		$this->db->from("t_act_d as A");
		$this->db->join("t_series_d as B","B.IDX = A.SERIESD_IDX");
		
		//if($idx != ""){
			$this->db->where("H_IDX",$idx);
		//}

		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}


	public function get_pln_info($idx)
	{
		$this->db->select("A.*,B.CUST_NM");
		$this->db->where("A.IDX",$idx);
		$this->db->from("t_act_h as A");
		$this->db->join("t_biz_reg as B","B.IDX = A.BIZ_IDX");
		$query = $this->db->get();
		return $query->row();
	}


	public function ajax_plnHead_insert($params)
	{
		$data = array(
			"ACT_DATE"    => $params['ACT_DATE'],
			"ACT_NAME"    => $params['ACT_NAME'],
			"DEL_DATE"    => $params['DEL_DATE'],
			"BIZ_IDX"     => $params['BIZ_IDX'],
			"REMARK"      => $params['REMARK'],
			"END_YN"      => $params['END_YN'],
			"ORD_TEXT"    => $params['ORD_TEXT'],
			"INSERT_DATE" => $params['INSERT_DATE'],
			"INSERT_ID"   => $params['INSERT_ID'],
		);
		$this->db->insert("t_act_h",$data);
		return $this->db->insert_id();
	}


	public function ajax_plnHead_update($params)
	{
		$data = array(
			"ACT_DATE"    => $params['ACT_DATE'],
			"ACT_NAME"    => $params['ACT_NAME'],
			"DEL_DATE"    => $params['DEL_DATE'],
			"BIZ_IDX"     => $params['BIZ_IDX'],
			"REMARK"      => $params['REMARK'],
			"ORD_TEXT"    => $params['ORD_TEXT'],
			"UPDATE_DATE" => $params['UPDATE_DATE'],
			"UPDATE_ID"   => $params['UPDATE_ID'],
		);
		$this->db->update("t_act_h",$data,array("IDX"=>$params['IDX']));
		return $this->db->affected_rows();
	}

	public function set_series_select($idx)
	{
		$query = $this->db->where("SERIES_IDX",$idx)
						->get("t_items");
		$data['items'] = $query->result();
		
		$query = $this->db->where("SERIES_IDX",$idx)
						->get("t_series_d");
		$data['sdetail'] = $query->result();
		//echo $this->db->last_query();

		return $data;

	}


	public function ajax_plnindex_pop($params)
	{
		$this->db->where("SERIES_IDX",$params['s1']);
		
		if(!empty($params['s2']) && $params['s2'] != ""){
			$this->db->where("ITEM_IDX",$params['s2']);
		}
		if(!empty($params['s3']) && $params['s3'] != ""){
			$this->db->where("SERIESD_IDX",$params['s3']);
		}

		$query = $this->db->get("t_item_series_v");
		
		
		return $query->result();
	}


	public function ajax_act_detail_insert($params)
	{
		$datas = array();
		
		$datetime = date("Y-m-d H:i:s",time());
		$username = $this->session->userdata('user_name');

		foreach($params['QTY'] as $k => $qty){
			
			

			$datax = array(
				"H_IDX"        => $params['H_IDX'][$k],
				"ITEMS_IDX"    => $params['ITEM_IDX'][$k],
				"ITEM_NM"      => $params['ITEM_NM'][$k],
				"SERIESD_IDX"  => $params['SERIESD_IDX'][$k],
				"QTY"          => $qty,
				"REMARK"       => $params['REMARK'][$k],
				"INSERT_ID"    => $username,
				"INSERT_DATE"  => $datetime
			);
			array_push($datas,$datax);
		}

		$this->db->insert_batch("t_act_d",$datas);
		return $this->db->affected_rows();

	}




}