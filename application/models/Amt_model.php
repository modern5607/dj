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
			$where .= " AND (SELECT CUST_NM FROM t_biz_reg WHERE IDX = A.BIZ_IDX) LIKE '%{$param['CUSTOMER']}%'";
		}


		$sql=<<<SQL
			SELECT 
				AA.TRANS_DATE,
				(SELECT CUST_NM FROM t_biz_reg WHERE IDX = AA.BIZ_IDX) CUST_NM, 
				AA.COMPONENT_NM, 
				AA.UNIT, 
				AA.IN_QTY, 
				AA.REMARK
			FROM 
				(
					SELECT
						A.TRANS_DATE,
						(SELECT CUST_NM FROM t_biz_reg WHERE IDX = A.BIZ_IDX) CUST_NM, 
						B.COMPONENT_NM, 
						B.UNIT, 
						A.IN_QTY, 
						A.REMARK,
						A.BIZ_IDX
					FROM
						t_component_trans A, 
						t_component B
					WHERE
						A.COMP_IDX = B.IDX AND 
						A.KIND = 'IN'
						{$where}
					ORDER BY A.TRANS_DATE DESC
					LIMIT {$start},{$limit}
				) as AA
			UNION ALL
			SELECT COUNT(B.COMPONENT_NM),'합계' AS TEXT,B.COMPONENT_NM, '' UNIT, SUM(IN_QTY) IN_QTY,'' 
			FROM 
			t_component_trans A, 
			t_component B
			WHERE 
				A.COMP_IDX = B.IDX AND
				A.KIND = 'IN'
				{$where}
			GROUP BY COMP_IDX
SQL;

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
				t_component_trans A,
				t_component B
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
				AA.COMPONENT_NM, 
				AA.UNIT, 
				AA.OUT_QTY, 
				AA.REMARK,
				AA.ITEM_NAME
			FROM 
				(
					SELECT
						A.TRANS_DATE,
						B.COMPONENT_NM, 
						B.UNIT, 
						A.OUT_QTY, 
						A.REMARK,
						TI.ITEM_NAME
					FROM
						t_component_trans A, 
						t_component B,
						t_items TI
					WHERE
						A.COMP_IDX = B.IDX 
						AND TI.IDX = A.ITEM_IDX
						AND A.KIND = 'OT'
						{$where}
					ORDER BY A.TRANS_DATE DESC
					
			LIMIT {$start},{$limit}
				) as AA
			UNION ALL
			SELECT COUNT(TI.ITEM_NAME),B.COMPONENT_NM, B.UNIT, SUM(OUT_QTY) OUT_QTY,'','합계'
			FROM 
				t_component_trans A, t_component B,t_items TI
			WHERE A.COMP_IDX = B.IDX
			AND TI.IDX = A.ITEM_IDX
			AND A.KIND = 'OT'
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
				t_component_trans A,
				t_component B,
				t_items TI 
			WHERE 
				A.COMP_IDX = B.IDX
				AND TI.IDX = A.ITEM_IDX 
				AND A.KIND = 'OT'
				{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		
		return $query->row()->CUT;

	}

	
	public function ajax_component_set_qty($params)
	{
		$this->db->trans_start();

		// $this->db->set("STOCK","STOCK + ".$params['IN_QTY'],FALSE);
		// $this->db->where("IDX",$params['IDX']);
		// $this->db->update("t_component");


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
						->get("t_component_trans");
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
						->from("t_act_d as TAD")
						->join("t_act_h as TAH","TAH.IDX = TAD.H_IDX","LEFT")
						->join("t_series_d as TSD","TSD.IDX = TAD.SERIESD_IDX","LEFT")
						->join("t_series_h as TSH","TSH.IDX = TSD.SERIES_IDX","LEFT")
						->join("t_item_stock as TIS","TIS.ITEM_IDX = TAD.ITEMS_IDX AND TIS.SERIESD_IDX = TSD.IDX","LEFT")
						->join("t_biz_reg AS TBR","TBR.IDX = TAH.BIZ_IDX","LEFT")
						->limit($limit, $start)
						->order_by("TAH.ACT_DATE","ASC")
						->order_by("TSH.SERIES_NM","ASC")
						->order_by("TAD.ITEM_NM")
						->order_by("COLOR","ASC")
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
						->from("t_act_d as TAD")
						->join("t_act_h as TAH","TAH.IDX = TAD.H_IDX","LEFT")
						->join("t_series_d as TSD","TSD.IDX = TAD.SERIESD_IDX","LEFT")
						->join("t_series_h as TSH","TSH.IDX = TSD.SERIES_IDX","LEFT")
						->join("t_item_stock as TIS","TIS.ITEM_IDX = TAD.ITEMS_IDX AND TIS.SERIESD_IDX = TSD.IDX","LEFT")
						->join("t_biz_reg AS TBR","TBR.IDX = TAH.BIZ_IDX","LEFT")
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
						->get("t_act_d");
		$actinfo = $query->row();

		date_default_timezone_set('Asia/Seoul');
		$datetime = date("Y-m-d H:i:s",time());
		$username = $this->session->userdata("user_name");
		
		if(!empty($actinfo)){

			$this->db->trans_start();
			
			$this->db->set(array("STATUS"=>"CG","END_YN"=>'Y'))
				->where("IDX",$params['ACT_IDX'])
				->update("t_act_d");

			$this->db->set(array(
				"ITEMS_IDX"=>$actinfo->ITEMS_IDX, 
				"SERIESD_IDX"=>$actinfo->SERIESD_IDX, 
				"ACT_IDX"=>$actinfo->H_IDX, 
				"OUT_QTY"=>$params['QTY'], 
				"CG_DATE"=>$params['XDATE'], 
				"GJ_GB"=>'CG', 
				"KIND"=>'OT', 
				"INSERT_ID"=>$username, 
				"INSERT_DATE"=>$datetime,
				"ACT_D_IDX"=>$params['ACT_IDX']))
				->insert("t_inventory_trans");

			$this->db->set("ITEMS_IDX",$actinfo->ITEMS_IDX)
					->set("TRANS_DATE",$params['XDATE'])
					->set("KIND","OT")
					->set("GJ_GB","CG")
					->set("ACT_IDX",$actinfo->IDX)
					->set("OUT_QTY",$params['QTY'])
					->set("INSERT_ID",$username)
					->set("INSERT_DATE",$datetime);
			$this->db->insert("t_items_trans");

			$this->db->trans_complete();
		
			
			if ($this->db->trans_status() !== FALSE){
				$query = $this->db->select("COUNT(*) as CUT")
							->where("H_IDX",$actinfo->H_IDX)
							->get("t_act_d");
				$actCount = $query->row()->CUT;

				$query = $this->db->select("COUNT(*) as CUT")
							->where("H_IDX",$actinfo->H_IDX)
							->where("COALESCE(END_YN,'N')","Y")
							->get("t_act_d");
				$endCount = $query->row()->CUT;

				if($actCount == $endCount){
					$this->db->set("END_YN","Y")
							->where("IDX",$actinfo->H_IDX)
							->update("t_act_h");
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
						->update("t_component");

		$query = $this->db->set("IN_QTY",$param['QTY'])
						->set("UPDATE_ID",$username)
						->set("UPDATE_DATE",$datetime)
						->where(array("IDX"=>$param['ITEM_IDX']))
						->update("t_component_trans");
						
		
		return $this->db->affected_rows();
		
	}


	public function delete_compTrans($param)
	{
		$datetime = date("Y-m-d H:i:s",time());
		$username = $this->session->userdata("user_name");

		// $this->db->set("STOCK",$param['CQTY'])
		// 				->set("UPDATE_ID",$username)
		// 				->set("UPDATE_DATE",$datetime)
		// 				->where("COMPONENT", "CLAY")	
		// 				->update("t_component");

		//$sql = "DELETE FROM t_component_trans WHERE IDX={$param['IDX']}";
		$this->db->where('IDX',$param['IDX']);
		$this->db->delete("t_component_trans");
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
						->from("t_items_trans AS TIS")
						->join("t_act_d as TAD","TAD.IDX = TIS.ACT_IDX","LEFT")
						->join("t_act_h as TAH","TAH.IDX = TAD.H_IDX","LEFT")
						->join("t_series_d as TSD","TSD.IDX = TAD.SERIESD_IDX","LEFT")
						->join("t_series_h as TSH","TSH.IDX = TSD.SERIES_IDX","LEFT")
						->join("t_biz_reg AS TBR","TBR.IDX = TAH.BIZ_IDX","LEFT")
						->join("t_claim AS TC","TC.ACT_IDX = TAD.IDX","LEFT")
						->limit($limit, $start)
						->order_by("TIS.TRANS_DATE","DESC")
						->order_by("TSH.SERIES_NM","ASC")
						->order_by("TAD.ITEM_NM","ASC")
						->order_by("TSD.COLOR","ASC")
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
						->from("t_items_trans AS TIS")
						->join("t_act_d as TAD","TAD.IDX = TIS.ACT_IDX","LEFT")
						->join("t_act_h as TAH","TAH.IDX = TAD.H_IDX","LEFT")
						->join("t_series_d as TSD","TSD.IDX = TAD.SERIESD_IDX","LEFT")
						->join("t_series_h as TSH","TSH.IDX = TSD.SERIES_IDX","LEFT")
						->join("t_biz_reg AS TBR","TBR.IDX = TAH.BIZ_IDX","LEFT")
						->join("t_claim AS TC","TC.ACT_IDX = TAD.IDX","LEFT")
						->get();
		return $query->row()->CUT;
	}

	public function get_items_info($idx)
	{
		$data = $this->db->select("TAD.*, TSD.COLOR, TIS.OUT_QTY, TC.REMARK AS CLAIM, TC.ACT_IDX AS CIDX")
						->where(array('TAD.IDX'=>$idx))
						->from("t_act_d as TAD")
						->join("t_series_d as TSD","TSD.IDX = TAD.SERIESD_IDX","LEFT")
						->join("t_items_trans AS TIS","TAD.IDX = TIS.ACT_IDX","LEFT")
						->join("t_claim AS TC","TAD.IDX = TC.ACT_IDX","LEFT")
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
			$this->db->update("t_claim");
		}else{
			$this->db->set("REMARK",$params['REMARK'])
					->set("ACT_IDX",$params['IDX'])
					->set("INSERT_DATE",$params['DATE']);
			$this->db->insert("t_claim");
		}

		return $this->db->insert_id();
	}
}