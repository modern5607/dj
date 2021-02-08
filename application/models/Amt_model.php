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
		$this->db->where(" KIND = 'IN'");
		$this->db->group_by("TRANS_DATE");
		$this->db->order_by("TRANS_DATE DESC");
		$this->db->limit($limit,$start);
		$query = $this->db->get("t_component_trans");
		// echo $this->db->Last_query();
		return $query->result();
	}

	public function component_trans_cnt($param)
	{
		$this->db->query('SET SESSION sql_mode = ""');

		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$this->db->where("TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}

		$this->db->select("COUNT(IDX) as CUT");
		$this->db->where(" KIND = 'IN'");
		$this->db->group_by("TRANS_DATE");
		$query = $this->db->get("t_component_trans");
		
		// echo $this->db->last_query();
		$data= $query->num_rows();
		// echo $data;
		return $data;
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

		if(!empty($param['CUSTOMER']) && $param['CUSTOMER'] != ""){
			$where .= " AND (SELECT CUST_NM FROM T_BIZ_REG WHERE IDX = A.BIZ_IDX) LIKE '%{$param['CUSTOMER']}%'";
		}


		$sql=<<<SQL
			SELECT 
				AA.TRANS_DATE,
				(SELECT CUST_NM FROM T_BIZ_REG WHERE IDX = AA.BIZ_IDX) CUST_NM, 
				AA.COMPONENT_NM, 
				AA.UNIT, 
				AA.IN_QTY, 
				AA.REMARK
			FROM 
				(
					SELECT
						A.TRANS_DATE,
						(SELECT CUST_NM FROM T_BIZ_REG WHERE IDX = A.BIZ_IDX) CUST_NM, 
						B.COMPONENT_NM, 
						B.UNIT, 
						A.IN_QTY, 
						A.REMARK,
						A.BIZ_IDX
					FROM
						T_COMPONENT_TRANS A, 
						T_COMPONENT B
					WHERE
						A.COMP_IDX = B.IDX AND 
						A.KIND = 'IN'
						{$where}
					ORDER BY A.TRANS_DATE DESC
				) as AA
			UNION
			SELECT COUNT(B.COMPONENT_NM),'합계' AS TEXT,B.COMPONENT_NM, '' UNIT, SUM(IN_QTY) IN_QTY,'' 
			FROM 
			T_COMPONENT_TRANS A, 
			T_COMPONENT B
			WHERE 
				A.COMP_IDX = B.IDX AND
				A.KIND = 'IN'
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
		// echo $this->db->last_query();
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
		$where="";
		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$where .= " AND B.COMPONENT LIKE '%{$param['COMPONENT']}%'";
		}
		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$where .= " AND B.COMPONENT_NM LIKE '%{$param['COMPONENT_NM']}%'";
		}
		if($date != ""){
			$where .= " AND A.TRANS_DATE = '{$date}'";
		}

		$sql=<<<SQL
			SELECT 
			B.COMPONENT,
			B.COMPONENT_NM,
			A.IDX AS AIDX,
			B.UNIT,
			A.IN_QTY
		FROM
			t_component_trans AS A
		JOIN t_component AS B ON B.IDX = A.COMP_IDX 
		WHERE
			KIND = 'IN' 
			{$where}
		UNION
		SELECT
			'합계','','',COUNT(COMPONENT_NM),SUM(IN_QTY)
		FROM
			t_component_trans AS A
			JOIN t_component AS B ON B.IDX = A.COMP_IDX 
		WHERE
			KIND = 'IN' 
			{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
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
		if(!empty($param['V1']) && $param['V1'] != ""){
			$where .= " AND TI.SERIES_IDX = '{$param['V1']}'";
		}

		if(!empty($param['V2']) && $param['V2'] != ""){
			$where .= " AND TI.ITEM_NAME LIKE '%{$param['V2']}%'";
		}


		$sql=<<<SQL
			SELECT 
				AA.TRANS_DATE,
				(SELECT CUST_NM FROM T_BIZ_REG WHERE IDX = AA.BIZ_IDX) CUST_NM, 
				AA.COMPONENT_NM, 
				AA.UNIT, 
				AA.OUT_QTY, 
				AA.REMARK,
				AA.ITEM_NAME, 
				AA.COL1
			FROM 
				(
					SELECT
						A.TRANS_DATE,
						(SELECT CUST_NM FROM T_BIZ_REG WHERE IDX = A.BIZ_IDX) CUST_NM, 
						B.COMPONENT_NM, 
						B.UNIT, 
						A.OUT_QTY, 
						A.REMARK,
						A.ITEM_IDX,
						A.BIZ_IDX, 
						A.COL1,
						TI.ITEM_NAME
					FROM
						T_COMPONENT_TRANS A, 
						T_COMPONENT B,
						T_ITEMS TI
					WHERE
						A.COMP_IDX = B.IDX 
						AND TI.IDX = A.ITEM_IDX
						AND A.KIND = 'OUT'
						{$where}
					ORDER BY A.TRANS_DATE DESC
				) as AA
			UNION ALL
			SELECT COUNT(TI.ITEM_NAME),'합계' AS TEXT,B.COMPONENT_NM, B.UNIT, SUM(OUT_QTY) OUT_QTY,'','',SUM(A.COL1)
			FROM 
				T_COMPONENT_TRANS A, T_COMPONENT B,T_ITEMS TI
			WHERE A.COMP_IDX = B.IDX
			AND TI.IDX = A.ITEM_IDX
			AND A.KIND = 'OUT'
			{$where}
			GROUP BY COMP_IDX
SQL;
		$query = $this->db->query($sql);
		//  echo $this->db->Last_query();
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
		if(!empty($param['V1']) && $param['V1'] != ""){
			$where .= " AND TI.SERIES_IDX = '{$param['V1']}'";
		}

		if(!empty($param['V2']) && $param['V2'] != ""){
			$where .= " AND TI.ITEM_NAME LIKE '%{$param['V2']}%'";
		}



		$sql=<<<SQL
			SELECT 
				COUNT(*) AS CUT
			FROM 
				T_COMPONENT_TRANS A,
				T_COMPONENT B,
				T_ITEMS TI 
			WHERE 
				A.COMP_IDX = B.IDX
				AND TI.IDX = A.ITEM_IDX 
				AND A.KIND = 'OUT'
				{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		
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
				-> $this->db->where("KIND","IN")
						->get("T_COMPONENT_TRANS");
		return $query->row();
	}



	public function act_am4_list($params, $start=0, $limit=20)
	{
		if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
			$this->db->where(" TAH.ACT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}

		if(!empty($params['V1']) && $params['V1'] != ""){
			$this->db->where("TSD.SERIES_IDX",$params['V1']);
		}

		if(!empty($params['V3']) && $params['V3'] != ""){
			$this->db->like("TAD.ITEM_NM",$params['V3']);
		}

		if(!empty($params['CG']) && $params['CG'] != ""){
			$this->db->where("TIS.QTY < TAD.QTY");
		}else{
			$this->db->where("TIS.QTY >= TAD.QTY");
		}

		$this->db->where("COALESCE(TAD.END_YN,'N') <>","Y");

		$query = $this->db->select("TAH.ACT_DATE, TAD.IDX as ACT_IDX, TAD.ITEM_NM, TAD.QTY,TSH.SERIES_NM, TSD.COLOR, TIS.QTY AS MAXQTY, TBR.CUST_NM")
						->from("T_ACT_D as TAD")
						->join("T_ACT_H as TAH","TAH.IDX = TAD.H_IDX","LEFT")
						->join("T_SERIES_D as TSD","TSD.IDX = TAD.SERIESD_IDX","LEFT")
						->join("T_SERIES_H as TSH","TSH.IDX = TSD.SERIES_IDX","LEFT")
						->join("T_ITEM_STOCK as TIS","TIS.ITEM_IDX = TAD.ITEMS_IDX AND TIS.SERIESD_IDX = TSD.IDX","LEFT")
						->join("T_BIZ_REG AS TBR","TBR.IDX = TAH.BIZ_IDX","LEFT")
						->limit($limit, $start)
						->order_by("TAH.ACT_DATE","ASC")
						->get();
		// echo $this->db->last_query();
		
		return $query->result();
	}


	public function act_am4_cut($params)
	{

		if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
			$this->db->where(" TAH.ACT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}

		if(!empty($params['V1']) && $params['V1'] != ""){
			$this->db->where("TSD.SERIES_IDX",$params['V1']);
		}

		if(!empty($params['V3']) && $params['V3'] != ""){
			$this->db->where("TAD.ITEM_NM",$params['V3']);
		}

		if(!empty($params['CG']) && $params['CG'] != ""){
			$this->db->where("TIS.QTY < TAD.QTY");
		}else{
			$this->db->where("TIS.QTY >= TAD.QTY");
		}

		$this->db->where("COALESCE(TAD.END_YN,'N') <>","Y");

		$query = $this->db->select("COUNT(*) as CUT")
						->from("T_ACT_D as TAD")
						->join("T_ACT_H as TAH","TAH.IDX = TAD.H_IDX","LEFT")
						->join("T_SERIES_D as TSD","TSD.IDX = TAD.SERIESD_IDX","LEFT")
						->join("T_SERIES_H as TSH","TSH.IDX = TSD.SERIES_IDX","LEFT")
						->join("T_ITEM_STOCK as TIS","TIS.ITEM_IDX = TAD.ITEMS_IDX AND TIS.SERIESD_IDX = TSD.IDX","LEFT")
						->join("T_BIZ_REG AS TBR","TBR.IDX = TAH.BIZ_IDX","LEFT")
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
		// echo $this->db->last_query();
		
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

public function component_count($date='',$param)
	{
		
		$this->db->select("STOCK");
		$this->db->from("t_component");
		$this->db->WHERE("COMPONENT", "CLAY");
		
		$query = $this->db->get();
		$data['STOCK'] = $query->row()->STOCK;
		// echo $this->db->last_query();
		
		return $data;		
	}

	public function am1_listupdate($param)
	{
		
		$datetime = date("Y-m-d H:i:s",time());
		$username = $this->session->userdata("user_name");


		$query =  $this->db->set("STOCK",$param['C_QTY'])
						->set("UPDATE_ID",$username)
						->set("UPDATE_DATE",$datetime)
						->where("COMPONENT", "CLAY")	
						->update("T_COMPONENT");

		$query = $this->db->set("IN_QTY",$param['QTY'])
						->set("UPDATE_ID",$username)
						->set("UPDATE_DATE",$datetime)
						->where(array("IDX"=>$param['ITEM_IDX']))
						->update("T_COMPONENT_TRANS");
						
		
		return $this->db->affected_rows();
		
	}


	public function delete_compTrans($param)
	{
		$datetime = date("Y-m-d H:i:s",time());
		$username = $this->session->userdata("user_name");

		$this->db->set("STOCK",$param['CQTY'])
						->set("UPDATE_ID",$username)
						->set("UPDATE_DATE",$datetime)
						->where("COMPONENT", "CLAY")	
						->update("T_COMPONENT");

		//$sql = "DELETE FROM T_COMPONENT_TRANS WHERE IDX={$param['IDX']}";
		$this->db->where('IDX',$param['IDX']);
		$this->db->delete("T_COMPONENT_TRANS");
		// if(empty($param['IDX'] || $param['IDX']=="")
		// 	return;
		return $this->db->affected_rows();
	}


	public function act_am5_list($params, $start=0, $limit=20)
	{
		if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
			$this->db->where(" TIS.TRANS_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}

		if(!empty($params['V1']) && $params['V1'] != ""){
			$this->db->where("TSD.SERIES_IDX",$params['V1']);
		}

		if(!empty($params['V3']) && $params['V3'] != ""){
			$this->db->like("TAD.ITEM_NM",$params['V3']);
		}

		if(!empty($params['CUST']) && $params['CUST'] != ""){
			$this->db->where("TAH.BIZ_IDX",$params['CUST']);
		}

		if(!empty($params['CLAIM']) && $params['CLAIM'] != ""){
			if($params['CLAIM'] == "1"){
				$this->db->where("(TC.REMARK != '' AND TC.INSERT_DATE IS NOT NULL)",NULL,FALSE);
			}else{
				$this->db->where("(TC.REMARK = '' OR TC.INSERT_DATE IS NULL)",NULL,FALSE);
			}
		}
		

		$this->db->where("TAD.END_YN","Y");

		$query = $this->db->select("TIS.TRANS_DATE, TBR.CUST_NM, TSH.SERIES_NM, TAD.ITEM_NM, TSD.COLOR, TAD.QTY, TIS.OUT_QTY, TIS.ACT_IDX, TC.INSERT_DATE AS CLAIM_DATE")
						->from("T_ITEMS_TRANS AS TIS")
						->join("T_ACT_D as TAD","TAD.IDX = TIS.ACT_IDX","LEFT")
						->join("T_ACT_H as TAH","TAH.IDX = TAD.H_IDX","LEFT")
						->join("T_SERIES_D as TSD","TSD.IDX = TAD.SERIESD_IDX","LEFT")
						->join("T_SERIES_H as TSH","TSH.IDX = TSD.SERIES_IDX","LEFT")
						->join("T_BIZ_REG AS TBR","TBR.IDX = TAH.BIZ_IDX","LEFT")
						->join("T_CLAIM AS TC","TC.ACT_IDX = TAD.IDX","LEFT")
						->limit($limit, $start)
						->order_by("TIS.TRANS_DATE","DESC")
						->order_by("TSH.IDX","ASC")
						->order_by("TIS.ITEMS_IDX","ASC")
						->order_by("TSD.IDX","ASC")
						->get();
		// echo $this->db->last_query();
		
		return $query->result();
	}


	public function act_am5_cut($params)
	{
		if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
			$this->db->where("TIS.TRANS_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}

		if(!empty($params['V1']) && $params['V1'] != ""){
			$this->db->where("TSD.SERIES_IDX",$params['V1']);
		}

		if(!empty($params['V3']) && $params['V3'] != ""){
			$this->db->like("TAD.ITEM_NM",$params['V3']);
		}

		if(!empty($params['CUST']) && $params['CUST'] != ""){
			$this->db->where("TAH.BIZ_IDX",$params['CUST']);
		}

		if(!empty($params['CLAIM']) && $params['CLAIM'] != ""){
			if($params['CLAIM'] == "1"){
				$this->db->where("TC.INSERT_DATE IS NOT NULL",NULL,FALSE);
			}else{
				$this->db->where("TC.INSERT_DATE IS NULL",NULL,FALSE);
			}
		}

		$this->db->where("TAD.END_YN","Y");

		$query = $this->db->select("COUNT(*) as CUT")
						->from("T_ITEMS_TRANS AS TIS")
						->join("T_ACT_D as TAD","TAD.IDX = TIS.ACT_IDX","LEFT")
						->join("T_ACT_H as TAH","TAH.IDX = TAD.H_IDX","LEFT")
						->join("T_SERIES_D as TSD","TSD.IDX = TAD.SERIESD_IDX","LEFT")
						->join("T_SERIES_H as TSH","TSH.IDX = TSD.SERIES_IDX","LEFT")
						->join("T_BIZ_REG AS TBR","TBR.IDX = TAH.BIZ_IDX","LEFT")
						->join("T_CLAIM AS TC","TC.ACT_IDX = TAD.IDX","LEFT")
						->get();
		return $query->row()->CUT;
	}

	public function get_items_info($idx)
	{
		$data = $this->db->select("TAD.*, TSD.COLOR, TIS.OUT_QTY, TC.REMARK AS CLAIM, TC.ACT_IDX AS CIDX")
						->where(array('TAD.IDX'=>$idx))
						->from("T_ACT_D as TAD")
						->join("T_SERIES_D as TSD","TSD.IDX = TAD.SERIESD_IDX","LEFT")
						->join("T_ITEMS_TRANS AS TIS","TAD.IDX = TIS.ACT_IDX","LEFT")
						->join("T_CLAIM AS TC","TAD.IDX = TC.ACT_IDX","LEFT")
						->get();
		return $data->row();
	}

	public function ajax_insert_claim($params)
	{
		if($params['UPD'] == "1"){
			$this->db->set("REMARK",$params['REMARK'])
					->set("ACT_IDX",$params['IDX'])
					->set("INSERT_DATE",$params['DATE'])
					->where("ACT_IDX",$params['IDX']);
			$this->db->update("T_CLAIM");
		}else{
			$this->db->set("REMARK",$params['REMARK'])
					->set("ACT_IDX",$params['IDX'])
					->set("INSERT_DATE",$params['DATE']);
			$this->db->insert("T_CLAIM");
		}

		return $this->db->insert_id();
	}
}