<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Amt_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}


	public function component_trans_list($param,$start=0,$limit=20)
	{
		$this->db->query('SET SESSION sql_mode = ""');

		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$this->db->where(" TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}

		$this->db->select("IDX, TRANS_DATE");
		$this->db->group_by("TRANS_DATE");
		$this->db->order_by("TRANS_DATE ASC");
		$this->db->limit($limit,$start);
		$query = $this->db->get("t_component_trans");
		return $query->result();
	}

	public function component_trans_cnt($param)
	{
		//$this->db->query('SET SESSION sql_mode = ""');

		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$this->db->where(" TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}

		$this->db->select("COUNT(IDX) as CUT");
		$query = $this->db->get("t_component_trans");
		
		
		return $query->row()->CUT;
	}


	public function component_trans_am2list($param,$start=0,$limit=20)
	{
		$where = '';
		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$where .= " AND A.TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$where .= " AND B.COMPONENT LIKE '%{$param['COMPONENT']}%'";
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$where .= " AND B.COMPONENT_NM LIKE '%{$param['COMPONENT_NM']}%'";
		}


		$sql=<<<SQL
			SELECT 
				AA.TRANS_DATE,
				(SELECT CUST_NM FROM djsmart.T_BIZ_REG WHERE IDX = AA.BIZ_IDX) CUST_NM, 
				AA.COMPONENT_NM, 
				AA.UNIT, 
				AA.IN_QTY, 
				AA.REMARK
			FROM 
				(
					SELECT
						A.TRANS_DATE,
						(SELECT CUST_NM FROM djsmart.T_BIZ_REG WHERE IDX = A.BIZ_IDX) CUST_NM, 
						B.COMPONENT_NM, 
						B.UNIT, 
						A.IN_QTY, 
						A.REMARK,
						A.BIZ_IDX
					FROM
						djsmart.T_COMPONENT_TRANS A, 
						djsmart.T_COMPONENT B
					WHERE
						A.COMP_IDX = B.IDX AND 
						A.KIND = 'IN'
						{$where}
					ORDER BY A.TRANS_DATE DESC
				) as AA
			
			UNION
			SELECT '','합계' AS TEXT,B.COMPONENT_NM, B.UNIT, SUM(IN_QTY) IN_QTY,'' 
			FROM 
				T_COMPONENT_TRANS A, T_COMPONENT B
			WHERE A.COMP_IDX = B.IDX
			AND A.KIND = 'IN'
			{$where}
			GROUP BY COMP_IDX
SQL;

		
		//$subquery1 = "(SELECT C.CUST_NM FROM t_biz_reg as C WHERE C.IDX = A.BIZ_IDX) as CUST_NM";

		//$this->db->select("A.TRANS_DATE,{$subquery1}, B.COMPONENT_NM, B.UNIT, A.IN_QTY, A.REMARK");
		//$this->db->from("t_component_trans as A");
		//$this->db->join("t_component as B","B.IDX = A.COMP_IDX");
		//$this->db->order_by("A.TRANS_DATE ASC");
		//$this->db->limit($limit,$start);
		$query = $this->db->query($sql);
		return $query->result();
	}


	public function component_trans_am2list_cnt($param)
	{
		$where = '';
		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$where .= " AND A.TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$where .= " AND B.COMPONENT LIKE '%{$param['COMPONENT']}%'";
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$where .= " AND B.COMPONENT_NM LIKE '%{$param['COMPONENT_NM']}%'";
		}



		$sql=<<<SQL
			SELECT 
				COUNT(A.IDX) as CUT
			FROM 
				T_COMPONENT_TRANS A,
				T_COMPONENT B
			WHERE A.COMP_IDX = B.IDX
			AND A.KIND = 'IN'
			{$where}
SQL;
		$query = $this->db->query($sql);
		return $query->row()->CUT;

	}

	


	public function component_trans_numlist($date='',$param)
	{
		$this->db->select("B.*,A.IDX as AIDX, A.IN_QTY");
		$this->db->from("t_component_trans as A");
		$this->db->join("t_component as B","B.IDX = A.COMP_IDX");
		
		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$this->db->like("B.COMPONENT",$param['COMPONENT']);
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$this->db->like("B.COMPONENT_NM",$param['COMPONENT_NM']);
		}


		if($date != ""){
			$this->db->where("A.TRANS_DATE",$date);
		}
		$query = $this->db->get();
		
		return $query->result();
	}



	public function component_trans_am2list_out($param,$start=0,$limit=20)
	{
		$where = '';
		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$where .= " AND A.TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$where .= " AND B.COMPONENT LIKE '%{$param['COMPONENT']}%'";
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$where .= " AND B.COMPONENT_NM LIKE '%{$param['COMPONENT_NM']}%'";
		}



		$sql=<<<SQL
			SELECT 
				AA.TRANS_DATE,
				(SELECT CUST_NM FROM djsmart.T_BIZ_REG WHERE IDX = AA.BIZ_IDX) CUST_NM, 
				AA.COMPONENT_NM, 
				AA.UNIT, 
				AA.OUT_QTY, 
				AA.REMARK
			FROM 
				(
					SELECT
						A.TRANS_DATE,
						(SELECT CUST_NM FROM djsmart.T_BIZ_REG WHERE IDX = A.BIZ_IDX) CUST_NM, 
						B.COMPONENT_NM, 
						B.UNIT, 
						A.OUT_QTY, 
						A.REMARK,
						A.BIZ_IDX
					FROM
						djsmart.T_COMPONENT_TRANS A, 
						djsmart.T_COMPONENT B
					WHERE
						A.COMP_IDX = B.IDX AND 
						A.KIND = 'OUT'
						{$where}
					ORDER BY A.TRANS_DATE DESC
				) as AA
			
			UNION
			SELECT '','합계' AS TEXT,B.COMPONENT_NM, B.UNIT, SUM(OUT_QTY) OUT_QTY,'' 
			FROM 
				T_COMPONENT_TRANS A, T_COMPONENT B
			WHERE A.COMP_IDX = B.IDX
			AND A.KIND = 'OUT'
			{$where}
			GROUP BY COMP_IDX
SQL;

		
		//$subquery1 = "(SELECT C.CUST_NM FROM t_biz_reg as C WHERE C.IDX = A.BIZ_IDX) as CUST_NM";

		//$this->db->select("A.TRANS_DATE,{$subquery1}, B.COMPONENT_NM, B.UNIT, A.IN_QTY, A.REMARK");
		//$this->db->from("t_component_trans as A");
		//$this->db->join("t_component as B","B.IDX = A.COMP_IDX");
		//$this->db->order_by("A.TRANS_DATE ASC");
		//$this->db->limit($limit,$start);
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function component_trans_am2list_cnt_out($param)
	{
		$where = '';
		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$where .= " AND A.TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$where .= " AND B.COMPONENT LIKE '%{$param['COMPONENT']}%'";
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$where .= " AND B.COMPONENT_NM LIKE '%{$param['COMPONENT_NM']}%'";
		}



		$sql=<<<SQL
			SELECT 
				COUNT(A.IDX) as CUT
			FROM 
				T_COMPONENT_TRANS A,
				T_COMPONENT B
			WHERE A.COMP_IDX = B.IDX
			AND A.KIND = 'OUT'
			{$where}
SQL;
		$query = $this->db->query($sql);
		return $query->row()->CUT;

	}

	
	public function ajax_component_set_qty($params)
	{
		$this->db->trans_start();

		$this->db->set("STOCK","STOCK + ".$params['IN_QTY'],FALSE);
		$this->db->where("IDX",$params['IDX']);
		$this->db->update("t_component");


		$set = array(
			"COMP_IDX"	 => $params['IDX'],
			"TRANS_DATE" => $params['TRANS_DATE'],
			"KIND"       => "IN",
			"IN_QTY"     => $params['IN_QTY'],
			"BIZ_IDX"    => $params['BIZ_IDX'],
			"REMARK"     => $params['REMARK'],
			"INSERT_ID"  =>	$this->session->userdata('user_id'),
			"INSERT_DATE"=> date("Y-m-d H:i:s",time())
		);

		$this->db->set($set);
		$this->db->insert('t_component_trans');

		$this->db->trans_complete();
		
		$data = 0;
		if ($this->db->trans_status() !== FALSE){
			$data = 1;
		}

		return $data;

	}


	public function ajax_componentNum_form($idx)
	{
		$query = $this->db->where("IDX",$idx)
						->get("T_COMPONENT_TRANS");
		return $query->row();
	}



	public function act_am4_list($params, $start=0, $limit=20)
	{
		if(!empty($params['V1']) && $params['V1'] != ""){
			$this->db->where("TSD.SERIES_IDX",$params['V1']);
		}

		if(!empty($params['V3']) && $params['V3'] != ""){
			$this->db->where("TAD.ITEM_NM",$params['V3']);
		}

		$this->db->where("TAD.STATUS","SB");
		$this->db->where("COALESCE(TAD.END_YN,'N') <>","Y");

		$query = $this->db->select("TAH.ACT_DATE, TAD.IDX as ACT_IDX, TAD.ITEM_NM, TAD.QTY")
						->from("T_ACT_D as TAD")
						->join("T_ACT_H as TAH","TAH.IDX = TAD.H_IDX","LEFT")
						->join("T_SERIES_D as TSD","TSD.IDX = TAD.SERIESD_IDX","LEFT")
						->limit($limit, $start)
						->order_by("TAH.ACT_DATE","DESC")
						->get();
		//echo $this->db->last_query();
		return $query->result();
	}


	public function act_am4_cut($params)
	{
		if(!empty($params['V1']) && $params['V1'] != ""){
			$this->db->where("TSD.SERIES_IDX",$params['V1']);
		}

		if(!empty($params['V3']) && $params['V3'] != ""){
			$this->db->where("TAD.ITEM_NM",$params['V3']);
		}

		$this->db->where("TAD.STATUS","SB");
		$this->db->where("COALESCE(TAD.END_YN,'N') <>","Y");

		$query = $this->db->select("COUNT(*) as CUT")
						->from("T_ACT_D as TAD")
						->join("T_ACT_H as TAH","TAH.IDX = TAD.H_IDX","LEFT")
						->join("T_SERIES_D as TSD","TSD.IDX = TAD.SERIESD_IDX","LEFT")
						->get();
		return $query->row()->CUT;
	}


	public function ajax_am4_listupdate($params)
	{
		
		$data = array(
			'status' => "",
			'msg'    => ""
		);

		$query = $this->db->where("IDX",$params['ACT_IDX'])
						->get("T_ACT_D");
		$actinfo = $query->row();
		
		if(!empty($actinfo)){
			
			$this->db->where(array("ITEM_IDX"=>$actinfo->ITEMS_IDX, "SERIESD_IDX"=>$actinfo->SERIESD_IDX));
			$query = $this->db->get("T_ITEM_STOCK");
			$stockinfo = $query->row();

			if($params['QTY'] > $stockinfo->QTY){
				$data['status'] = "N";
				$data['msg']    = "재고 수량이 부족합니다.";
				return $data;
				exit;
			}

			$this->db->set("QTY","QTY-{$params['QTY']}",false);
			$this->db->where(array("ITEM_IDX"=>$stockinfo->ITEM_IDX, "SERIESD_IDX"=>$stockinfo->SERIESD_IDX));
			$this->db->update("T_ITEM_STOCK");

			$this->db->trans_start();
			
			$this->db->set(array("STATUS"=>"CC","END_YN"=>'Y'))
				->where("IDX",$params['ACT_IDX'])
				->update("T_ACT_D");

			$this->db->set(array("OUT_QTY"=>$params['QTY'], "CG_DATE"=>$params['XDATE'], "GJ_GB"=>'CC'))
					->where("ACT_D_IDX",$params['ACT_IDX'])
					->update("T_INVENTORY_TRANS");

			$this->db->set("ITEMS_IDX",$actinfo->ITEMS_IDX)
					->set("TRANS_DATE",$params['XDATE'])
					->set("KIND","OUT")
					->set("GJ_GB","CC")
					->set("ACT_IDX",$actinfo->IDX)
					->set("OUT_QTY",$params['QTY']);
			$this->db->insert("T_ITEMS_TRANS");

			$this->db->trans_complete();
		
			
			if ($this->db->trans_status() !== FALSE){
				$query = $this->db->select("COUNT(*) as CUT")
							->where("H_IDX",$actinfo->H_IDX)
							->get("T_ACT_D");
				$actCount = $query->row()->CUT;

				$query = $this->db->select("COUNT(*) as CUT")
							->where("H_IDX",$actinfo->H_IDX)
							->where("COALESCE(END_YN,'N')","Y")
							->get("T_ACT_D");
				$endCount = $query->row()->CUT;

				if($actCount == $endCount){
					$this->db->set("END_YN","Y")
							->where("IDX",$actinfo->H_IDX)
							->update("T_ACT_H");
				}
				
				$data['status'] = "Y";
				$data['msg']    = "출고수량이 등록되었습니다.";
				return $data;
				exit;
			}

			
			$data['status'] = "N";
			$data['msg']    = "error1";
			return $data;
			exit;

		}

		$data['status'] = "N";
		$data['msg']    = "error2";
		return $data;
		exit;

	}



}
