<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Act_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}



	public function act_a2_list($param, $start = 0, $limit = 20)
	{
		$where = '';
		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND TI.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND TI.ITEM_NAME LIKE '%{$param['V3']}%'";
		}

		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND TIT.TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}


		$sql = <<<SQL
		SELECT 
			AA.*
		FROM 
			(
				SELECT
				TIT.TRANS_DATE, TI.ITEM_NAME, TIT.IN_QTY, TIT.REMARK, TSH.SERIES_NM
				FROM
					T_ITEMS TI
					LEFT JOIN T_ITEMS_TRANS AS TIT ON TIT.ITEMS_IDX = TI.IDX
					LEFT JOIN T_SERIES_H AS TSH ON TSH.IDX = TI.SERIES_IDX
				WHERE
					TI.SH_QTY > 0
					AND TIT.GJ_GB != "CG"
					{$where}
				ORDER BY  TRANS_DATE DESC, SERIES_NM, ITEM_NAME, SERIES_NM
				LIMIT {$start}, {$limit}
			) as AA
		UNION ALL
		SELECT '','합계', SUM(IN_QTY) as IN_QTY, COUNT(TI.ITEM_NAME),""
		FROM 
		T_ITEMS TI
			LEFT JOIN T_ITEMS_TRANS AS TIT ON TIT.ITEMS_IDX = TI.IDX
			LEFT JOIN T_SERIES_H AS TSH ON TSH.IDX = TI.SERIES_IDX
		WHERE 
			TI.SH_QTY > 0
			AND TIT.GJ_GB != "CG"
			{$where}
SQL;

		$query = $this->db->query($sql);


		//  echo $this->db->last_query();
		return $query->result();
	}

	public function act_a2_cut($param)
	{
		if (!empty($param['V1']) && $param['V1'] != "") {
			$this->db->where("TI.SERIES_IDX", $param['V1']);
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$this->db->where("TI.ITEM_NAME", $param['V3']);
		}

		if (!empty($param['V4']) && $param['V4'] != "") {
			$this->db->where("B.ITEM_NM", $param['V4']);
		}

		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where(" TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}

		$this->db->select("COUNT(*) as CUT");
		$this->db->from("T_ITEMS as TI");
		$this->db->join("T_ITEMS_TRANS as TIT", "TIT.ITEMS_IDX = TI.IDX", "LEFT");
		$this->db->join("T_SERIES_H as TSH", "TSH.IDX = TI.SERIES_IDX", "LEFT");
		$this->db->where("TI.SH_QTY > 0");
		$this->db->where("TIT.GJ_GB != 'CG'");
		$query = $this->db->get();
		return $query->row()->CUT;
	}



	public function item_trans_list($param, $start = 0, $limit = 20)
	{
		$this->db->query('SET SESSION sql_mode = ""');

		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where(" TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}



		if (!empty($param['BK'])) {
			$GJGB = "JHBK";
		} else {
			$GJGB = $param['GJGB'];
		}

		$this->db->where("GJ_GB", $GJGB);
		$this->db->where("KIND", "IN");

		$this->db->select("IDX, TRANS_DATE");
		$this->db->group_by("TRANS_DATE");
		$this->db->order_by("TRANS_DATE DESC");
		$this->db->limit($limit, $start);
		$query = $this->db->get("t_items_trans");

		// echo $this->db->last_query();

		return $query->result();
	}


	public function item_trans_cnt($param)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			// $this->db->where(" TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
			$where .= "AND TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['BK'])) {
			$GJGB = "JHBK";
		} else {
			$GJGB = $param['GJGB'];
		}

		// $this->db->where("GJ_GB",$GJGB);
		$where .= "AND GJ_GB = '{$GJGB}'";

		$sql = <<<SQL
			SELECT
				COUNT(*) AS CUT
			FROM
			(
				SELECT
					TRANS_DATE
				FROM
					T_ITEMS_TRANS
				WHERE
					KIND = "IN"
					{$where}
				GROUP BY
					TRANS_DATE

			) AS AA

SQL;
		$query = $this->db->query($sql);

		// echo $this->db->last_query();


		return $query->row()->CUT;
	}

	/* 성형실적,정형실적 오른쪽 리스트 */
	public function item_trans_numlist($date = '', $param)
	{
		$where = '';

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND B.SERIES_IDX={$param['V1']}";
		}
		if (!empty($param['COMPONENT']) && $param['COMPONENT'] != "") {
			$where .= " AND B.ITEM_NO LIKE '%{$param['COMPONENT']}%'";
		}

		if (!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != "") {
			$where .= " AND B.ITEM_NAME LIKE '%{$param['COMPONENT_NM']}%'";
		}
		if ($date != "") {
			$where .= " AND A.TRANS_DATE = '{$date}'";
		}

		if (!empty($param['BK'])) {
			$GJGB = "JHBK";
		} else {
			$GJGB = $param['GJGB'];
		}

		$where .= " AND GJ_GB = '{$GJGB}'";

		$sql = <<<SQL
		SELECT AA.* FROM (
			SELECT
				A.IDX AS TRANS_IDX,
				H.SERIES_NM,
				B.ITEM_NAME,
				A.IN_QTY,
				A.REMARK ,
				B.JH_QTY,
				B.SH_QTY,
				A.BQTY,
				H.IDX
			FROM
				t_items_trans AS A
				JOIN t_items AS B ON B.IDX = A.ITEMS_IDX
				LEFT JOIN t_series_h AS H ON H.IDX = B.SERIES_IDX 
			WHERE
				KIND = "IN"
				{$where}
		) AS AA
			UNION ALL
			SELECT
				COUNT(B.ITEM_NAME),
				'합계' AS TEXT,
				'',
				SUM(A.IN_QTY),
				'',
				'',
				'',
				SUM(A.BQTY),
				''
			FROM
				t_items_trans AS A
				JOIN t_items AS B ON B.IDX = A.ITEMS_IDX
				LEFT JOIN t_series_h AS H ON H.IDX = B.SERIES_IDX
			WHERE
				KIND = "IN"
				{$where}
			ORDER BY
				SERIES_NM, ITEM_NAME
SQL;


		$query = $this->db->query($sql);
		// ECHO $this->db->last_query();
		return $query->result();
	}


	public function ajax_itemNum_form()
	{
		$data['title'] = "자재입고관리";
		$data['CUST'] = $this->main_model->get_custlist();
		$data['COMP'] = $this->main_model->ajax_component_select();
		$this->load->view('/amt/ajax_componentNum', $data);
	}



	public function get_inventory_list($param, $start = 0, $limit = 20)
	{

		$this->db->select("A.IDX, B.H_IDX, A.CU_DATE AS ACT_DATE, B.ITEM_NM, D.COLOR, B.QTY, A.IN_QTY,F.CUST_NM,E.SERIES_NM");
		$this->db->from("t_inventory_trans as A");
		$this->db->join("t_act_h as C", "C.IDX = A.ACT_IDX", "LEFT");
		$this->db->join("t_act_d as B", "B.IDX = A.ACT_D_IDX", "LEFT");
		$this->db->join("t_series_d as D", "D.IDX = A.SERIESD_IDX", "LEFT");
		$this->db->join("T_BIZ_REG AS F ", "C.BIZ_IDX = F.IDX", "LEFT");
		$this->db->join("T_SERIES_H AS E ", "E.IDX = D.SERIES_IDX", "LEFT");


		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("C.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$this->db->where("C.BIZ_IDX", $param['V1']);
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$this->db->where("D.SERIES_IDX", $param['V2']);
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$this->db->like("B.ITEM_NM", $param['V3']);
		}

		//if(!empty($param['V4']) && $param['V4'] != ""){
		$this->db->where("A.GJ_GB", 'CU');
		$this->db->where("not exists (select * from t_act_d tad where A.ACT_D_IDX = tad.IDX and tad.END_YN = 'Y')");
		//}

		// $this->db->select("A.IDX, B.H_IDX, C.ACT_DATE, B.ITEM_NM, D.COLOR, B.QTY, A.IN_QTY,(SELECT E.SERIES_NM FROM T_SERIES_H as E WHERE E.IDX = D.SERIES_IDX) as SE_NAME");
		$this->db->order_by("A.CU_DATE", "ASC");
		$this->db->order_by("ITEM_NM", "ASC");
		$this->db->order_by("CUST_NM", "ASC");

		$this->db->limit($limit, $start);
		$query = $this->db->get();
		// echo $this->db->last_query();

		return $query->result();
	}


	public function get_inventory_count($param)
	{
		$this->db->select("A.IDX, B.H_IDX, C.ACT_DATE, B.ITEM_NM, D.COLOR, B.QTY, A.IN_QTY,F.CUST_NM,E.SERIES_NM");
		$this->db->from("t_inventory_trans as A");
		$this->db->join("t_act_h as C", "C.IDX = A.ACT_IDX", "LEFT");
		$this->db->join("t_act_d as B", "B.IDX = A.ACT_D_IDX", "LEFT");
		$this->db->join("t_series_d as D", "D.IDX = A.SERIESD_IDX", "LEFT");
		$this->db->join("T_BIZ_REG AS F ", "C.BIZ_IDX = F.IDX", "LEFT");
		$this->db->join("T_SERIES_H AS E ", "E.IDX = D.SERIES_IDX", "LEFT");


		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("C.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$this->db->where("C.BIZ_IDX", $param['V1']);
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$this->db->where("D.SERIES_IDX", $param['V2']);
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			//$this->db->where("B.ITEM_NM",$param['V3']);
			$this->db->like("B.ITEM_NM", $param['V3']);
		}

		//if(!empty($param['V4']) && $param['V4'] != ""){
		$this->db->where("A.GJ_GB", 'CU');
		//}

		// $this->db->select("A.IDX, B.H_IDX, C.ACT_DATE, B.ITEM_NM, D.COLOR, B.QTY, A.IN_QTY,(SELECT E.SERIES_NM FROM T_SERIES_H as E WHERE E.IDX = D.SERIES_IDX) as SE_NAME");
		$this->db->order_by("ACT_DATE", "DESC");
		$this->db->order_by("ITEM_NM", "ASC");
		$this->db->order_by("CUST_NM", "ASC");
		$query = $this->db->get();

		// echo $query->num_rows();
		return $query->num_rows();
	}



	public function ajax_item_trans_insert($params)
	{
		$datas = array();

		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');


		foreach ($params['QTY'] as $k => $qty) {

			$this->db->where("IDX", $params['ITEM_IDX'][$k]);
			$que = $this->db->get("T_ITEMS");
			$chk = $que->row();
			$qty = $qty * 1;

			// $JT_QTY = ($chk->JT_QTY == null)?1:$chk->JT_QTY;
			
			// $this->db->set("COMP_IDX",1); //clay 무조건
			// $this->db->set("TRANS_DATE",$datetime);
			// $this->db->set("KIND","OT");
			// $this->db->set("OUT_QTY",$qty*$JT_QTY);
			// $this->db->set("INSERT_ID",$username);
			// $this->db->set("INSERT_DATE",$datetime);
			// $this->db->set("ITEM_IDX",$params['ITEM_IDX'][$k]);
			// $this->db->set("COL1",$qty);

			// $this->db->insert("t_component_trans");


			$datax = array(
				//"H_IDX"        => $params['H_IDX'][$k],
				"ITEMS_IDX"    => $params['ITEM_IDX'][$k],
				"TRANS_DATE"   => $params['transdate'],
				"KIND"         => "IN",
				"GJ_GB"        => "SH",
				"IN_QTY"       => $qty,
				"REMARK"       => $params['REMARK'][$k],
				"INSERT_ID"    => $username,
				"INSERT_DATE"  => $datetime
			);
			array_push($datas, $datax);
		}

		$this->db->insert_batch("T_ITEMS_TRANS", $datas);
		return $this->db->affected_rows();
	}


	public function ajax_itemv_pop($params)
	{
		$this->db->select("*, IFNULL(SH_QTY,0) as SH_QTY, IFNULL(JH_QTY,0) as JH_QTY");
		$this->db->where("SERIES_IDX", $params['s1']);

		if (!empty($params['s2']) && $params['s2'] != "") {
			$this->db->like("ITEM_NAME", $params['s2']);
		}
		if (!empty($params['s3']) && $params['s3'] != "") {
			$this->db->where("SERIESD_IDX", $params['s3']);
		}
		if ($params['type']  == "JH") {
			$this->db->where("SH_QTY > 0");
		}

		$this->db->where("USE_YN", "Y");
		$query = $this->db->get("t_items");
		// echo $this->db->last_query();
		return $query->result();
	}


	public function ajax_del_items_trans($idx)
	{
		$this->db->trans_start();


		$this->db->where("IDX", $idx);
		$this->db->delete("t_items_trans");

		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}


	public function ajax_del_items_trans_a9($idx)
	{

		$this->db->trans_start();

		// $this->db->select("A.*,B.JT_QTY");
		// $this->db->from("t_items_trans as A");
		// $this->db->join("t_items as B","B.IDX = A.ITEMS_IDX");
		// $this->db->where("A.IDX",$idx);
		// $query = $this->db->get();
		// $chkinfo = $query->row();

		$this->db->where("IDX", $idx);
		$this->db->delete("t_items_trans");

		//OT 삭제
		$this->db->where("ITEM_IDX ", $idx);
		$this->db->delete("t_items_trans");

		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}
	public function ajax_del_items_trans_bk_a9($idx)
	{

		$this->db->trans_start();

		// $this->db->select("A.*,B.JT_QTY");
		// $this->db->from("t_items_trans as A");
		// $this->db->join("t_items as B","B.IDX = A.ITEMS_IDX");
		// $this->db->where("A.IDX",$idx);
		// $query = $this->db->get();
		// $chkinfo = $query->row();

		$this->db->where("IDX", $idx);
		$this->db->delete("t_items_trans");

		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}

	public function ajax_act_a9_items_trans_insert($params)
	{
		$datain = array();
		$dataot = array();

		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');


		foreach ($params['QTY'] as $k => $qty) {

			// $this->db->where("IDX",$params['ITEM_IDX'][$k]);
			// $que = $this->db->get("T_ITEMS");
			// $chk = $que->row();

			$qty = $qty * 1;

			if ($params['BK'] != 1) {
				$GJ_GB = "JH";


				$datao = array(
					"ITEMS_IDX"    => $params['ITEM_IDX'][$k],
					"TRANS_DATE"   => $params['transdate'],
					"KIND"         => "OT",
					"GJ_GB"        => "SH",
					"OUT_QTY"      => $qty,
					"REMARK"       => $params['REMARK'][$k],
					"INSERT_ID"    => $username,
					"INSERT_DATE"  => $datetime
				);
				array_push($dataot, $datao);
			} else {
				$GJ_GB = "JHBK";
			}

			$datax = array(
				//"H_IDX"        => $params['H_IDX'][$k],
				"ITEMS_IDX"    => $params['ITEM_IDX'][$k],
				"TRANS_DATE"   => $params['transdate'],
				"KIND"         => "IN",
				"GJ_GB"        => $GJ_GB,
				"IN_QTY"       => $qty,
				"REMARK"       => $params['REMARK'][$k],
				"INSERT_ID"    => $username,
				"INSERT_DATE"  => $datetime,
				"BQTY"  	   => $params['BQTY'][$k]
			);
			array_push($datain, $datax);
		}

		$this->db->insert_batch("T_ITEMS_TRANS", $datain);
		if ($params['BK'] != 1) {
			$this->db->insert_batch("T_ITEMS_TRANS", $dataot);
		}
		return $this->db->affected_rows();
	}




	public function act_a92_list($param, $start = 0, $limit = 20)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND A.TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND B.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND B.ITEM_NAME LIKE '%{$param['V2']}%'";
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND A.GJ_GB = '{$param['V3']}'";
		} else {
			$where .= " AND A.GJ_GB != 'SH'";
		}

		$sql = <<<SQL
			SELECT 
			AA.*
			FROM 
				(
					SELECT
						A.TRANS_DATE,
						B.ITEM_NAME,
						A.IN_QTY, 
						A.REMARK,
						H.SERIES_NM,
						A.BQTY,
						CASE
							WHEN (A.GJ_GB = "JHBK") THEN "BK"
							WHEN (A.GJ_GB = "JH") THEN ""
						END as BK
					FROM
						T_ITEMS_TRANS A, 
						T_ITEMS B
						LEFT JOIN T_SERIES_H AS H ON H.IDX = B.SERIES_IDX
					WHERE
						A.ITEMS_IDX = B.IDX AND 
						A.KIND = 'IN'
						{$where}
					ORDER BY 
						A.TRANS_DATE DESC,
						SERIES_NM,
						ITEM_NAME
					LIMIT {$start}, {$limit}
				) as AA
			UNION ALL
			SELECT '','합계' AS TEXT, SUM(IN_QTY) as IN_QTY,COUNT(B.ITEM_NAME),'', SUM(BQTY),''
			FROM 
				T_ITEMS_TRANS A, 
				T_ITEMS B
				LEFT JOIN T_SERIES_H AS H ON H.IDX = B.SERIES_IDX
			WHERE 
				A.ITEMS_IDX = B.IDX
				AND A.KIND = 'IN'
				{$where}
			ORDER BY
				TRANS_DATE DESC,
				SERIES_NM,
				ITEM_NAME
			
SQL;
		$query = $this->db->query($sql);
		// ECHO $this->db->last_query();
		return $query->result();
	}


	public function act_a92_cut($param)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND A.TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND B.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND B.ITEM_NAME LIKE '%{$param['V2']}%'";
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND A.GJ_GB = '{$param['V3']}'";
		} else {
			$where .= " AND A.GJ_GB != 'SH'";
		}

		$sql = <<<SQL
			SELECT
				COUNT(A.ITEMS_IDX) as CUT
			FROM
				T_ITEMS_TRANS A, 
				T_ITEMS B
			WHERE
				A.ITEMS_IDX = B.IDX AND 
				A.KIND = 'IN'
				{$where}
SQL;
		$query = $this->db->query($sql);

		return $query->row()->CUT;
	}





	public function act_a4_list($param, $start = 0, $limit = 20)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND TAH.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND TS.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			if ($param['V2'] == "1") {
				$where .= " AND TIT.CU_DATE IS NULL AND TIT.SB_DATE IS NULL AND TIT.CG_DATE IS NULL";
			}
			if ($param['V2'] == "2") {
				$where .= " AND TIT.CU_DATE IS NOT NULL AND TIT.SB_DATE IS NULL AND TIT.CG_DATE IS NULL";
			}
			if ($param['V2'] == "3") {
				$where .= " AND TIT.CU_DATE IS NOT NULL AND TIT.SB_DATE IS NOT NULL AND TIT.CG_DATE IS NULL";
			}
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND TA.ITEM_NM LIKE '%{$param['V3']}%'";
		}

		$sql = <<<SQL
		SELECT AA.* FROM
			(
			SELECT
				TAH.ACT_DATE, TA.ITEM_NM, TA.QTY, TIT.IN_QTY, TIT.`2_QTY` as QT2, TIT.`3_QTY` as QT3, TIT.`4_QTY` as QT4,	DATE_FORMAT(TAH.DEL_DATE, '%Y-%m-%d') as DEL_DATE, TIT.CU_DATE, TIT.SB_DATE, TIS.TRANS_DATE AS CG_DATE, TS.COLOR
			FROM
				T_ACT_D as TA
				LEFT JOIN T_ACT_H as TAH ON(TAH.IDX = TA.H_IDX)
				LEFT JOIN T_INVENTORY_TRANS as TIT ON(TIT.ACT_D_IDX = TA.IDX)
				LEFT JOIN T_SERIES_D as TS ON(TS.IDX = TA.SERIESD_IDX)
				LEFT JOIN T_ITEMS_TRANS as TIS ON(TIS.ACT_IDX = TA.IDX)
			WHERE
				1
				{$where}
			ORDER BY
				 TAH.ACT_DATE DESC, TA.ITEM_NM ASC, TS.COLOR ASC
			LIMIT
				{$start}, {$limit} ) AS AA
				UNION ALL
		SELECT '합계','',SUM(TA.QTY),SUM(TIT.IN_QTY),SUM(TIT.2_QTY),SUM(TIT.3_QTY),SUM(TIT.4_QTY),COUNT(ITEM_NM),'','','',''
		FROM
			T_ACT_D AS TA
			LEFT JOIN T_ACT_H AS TAH ON ( TAH.IDX = TA.H_IDX )
			LEFT JOIN T_INVENTORY_TRANS AS TIT ON ( TIT.ACT_D_IDX = TA.IDX )
			LEFT JOIN T_SERIES_D AS TS ON ( TS.IDX = TA.SERIESD_IDX ) 
			LEFT JOIN T_ITEMS_TRANS as TIS ON(TIS.ACT_IDX = TA.IDX)
		WHERE
			1
			{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->result();
	}

	public function act_a4_cut($param)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND TAH.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND TS.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			if ($param['V2'] == "1") {
				$where .= " AND TIT.CU_DATE = NULL AND TIT.SB_DATE = NULL AND TIT.CG_DATE = NULL";
			}
			if ($param['V2'] == "2") {
				$where .= " AND TIT.CU_DATE <> NULL AND TIT.SB_DATE = NULL AND TIT.CG_DATE = NULL";
			}
			if ($param['V2'] == "3") {
				$where .= " AND TIT.CU_DATE <> NULL AND TIT.SB_DATE <> NULL AND TIT.CG_DATE = NULL";
			}
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND TA.ITEM_NM LIKE '%{$param['V3']}%'";
		}

		$sql = <<<SQL
			SELECT
				COUNT(*) as CUT
			FROM
			T_ACT_D as TA
				LEFT JOIN T_ACT_H as TAH ON(TAH.IDX = TA.H_IDX)
				LEFT JOIN T_INVENTORY_TRANS as TIT ON(TIT.ACT_D_IDX = TA.IDX)
				LEFT JOIN T_SERIES_D as TS ON(TS.IDX = TA.SERIESD_IDX)
			WHERE
				1
				{$where}
SQL;
		$query = $this->db->query($sql);
		return $query->row()->CUT;
	}



	public function act_a5_list($param, $start = 0, $limit = 20)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND TAH.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		$orderby = " TAH.ACT_DATE DESC";

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND TS.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND TBR.CUST_NM LIKE '%{$param['V2']}%'";
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND TA.ITEM_NM LIKE '%{$param['V3']}%'";
		}

		if (!empty($param['V4']) && $param['V4'] != "") {
			$where .= " AND TA.END_YN = 'Y' ";
		}

		if (!empty($param['an']) && $param['an'] == "an3") {
			$where .= " AND TIT.`2_QTY` > 0";
			$orderby = " TAH.ACT_DATE DESC, TA.ITEM_NM ASC, TS.COLOR ASC";
		}
		if (!empty($param['an']) && ($param['an'] == "an1" || $param['an'] == "a6" || $param['an'] == "a5")) {
			$orderby = " TAH.ACT_DATE DESC, TA.ITEM_NM ASC, TS.COLOR ASC";
		}


		$sql = <<<SQL
			SELECT AA.* FROM(
			SELECT
				TAH.ACT_DATE, TA.ITEM_NM, TA.QTY, TIT.IN_QTY, TIT.`1_QTY` as QT1, TIT.`2_QTY` as QT2, TIT.`3_QTY` as QT3, TIT.`4_QTY` as QT4,	DATE_FORMAT(TAH.DEL_DATE, '%Y-%m-%d') as DEL_DATE, TIT.CU_DATE, TIT.SB_DATE, TIS.TRANS_DATE, TIT.CG_DATE, TS.COLOR, TA.END_YN, TA.REMARK,
				TBR.CUST_NM as BIZ_NAME, TIT.IDX, TIT.RECYCLE, B.QTY  as XQTY
			FROM
				T_ACT_D as TA
				LEFT JOIN T_ACT_H as TAH ON(TAH.IDX = TA.H_IDX)
				LEFT JOIN T_INVENTORY_TRANS as TIT ON(TIT.ACT_D_IDX = TA.IDX)
				LEFT JOIN T_SERIES_D as TS ON(TS.IDX = TA.SERIESD_IDX)
				LEFT JOIN T_BIZ_REG as TBR ON(TBR.IDX = TAH.BIZ_IDX)
				LEFT JOIN T_ITEM_STOCK as B ON( B.ITEM_IDX = TA.ITEMS_IDX AND B.SERIESD_IDX = TA.SERIESD_IDX)
				LEFT JOIN T_ITEMS_TRANS as TIS ON(TIS.ACT_IDX = TA.IDX)
			WHERE
				1
				{$where}
			ORDER BY 
				ACT_DATE DESC,
				ITEM_NM,
				COLOR,
				QTY
			LIMIT
				{$start}, {$limit}
			) AS AA
			
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();

		return $query->result();
	}

	public function act_a5_cut($param)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND TAH.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND TS.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND TBR.CUST_NM LIKE '%{$param['V2']}%'";
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND TA.ITEM_NM LIKE '%{$param['V3']}%'";
		}

		if (!empty($param['V4']) && $param['V4'] != "") {
			$where .= " AND TA.END_YN = 'Y' ";
		}

		if (!empty($param['an']) && $param['an'] == "an3") {
			$where .= " AND TIT.`2_QTY` > 0";
		}



		$sql = <<<SQL
			SELECT
				COUNT(*) as CUT
			FROM
				T_ACT_D as TA
				LEFT JOIN T_ACT_H as TAH ON(TAH.IDX = TA.H_IDX)
				LEFT JOIN T_INVENTORY_TRANS as TIT ON(TIT.ACT_D_IDX = TA.IDX)
				LEFT JOIN T_SERIES_D as TS ON(TS.IDX = TA.SERIESD_IDX)
				LEFT JOIN T_BIZ_REG as TBR ON(TBR.IDX = TAH.BIZ_IDX)
				LEFT JOIN T_ITEMS_TRANS as TIS ON(TIS.ACT_IDX = TA.IDX)
			WHERE
				1
				{$where}
SQL;
		$query = $this->db->query($sql);
		return $query->row()->CUT;
	}


	public function act_a7_list($param, $start = 0, $limit = 20)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND TAH.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		$sql = <<<SQL
			SELECT
				TAH.ACT_DATE, TA.ITEM_NM, TA.QTY, TIT.IN_QTY, TIT.`1_QTY` as QT1, TIT.`2_QTY` as QT2, TIT.`3_QTY` as QT3, TIT.`4_QTY` as QT4,	DATE_FORMAT(TAH.DEL_DATE, '%Y-%m-%d') as DEL_DATE, TIT.CU_DATE, TIT.SB_DATE, TIT.CG_DATE, TS.COLOR, TA.END_YN,
				(SELECT A.CUST_NM FROM T_BIZ_REG as A WHERE A.IDX = TAH.BIZ_IDX) as BIZ_NAME
			FROM
				T_ACT_D as TA
				LEFT JOIN T_ACT_H as TAH ON(TAH.IDX = TA.H_IDX)
				LEFT JOIN T_INVENTORY_TRANS as TIT ON(TIT.ACT_D_IDX = TA.IDX)
				LEFT JOIN T_SERIES_D as TS ON(TS.IDX = TA.SERIESD_IDX)
			WHERE
				(`4_QTY` > 0 OR `2_QTY` > 0 OR `3_QTY` > 0)
				{$where}
			ORDER BY
				TAH.ACT_DATE DESC,
				ITEM_NM,
				COLOR
			LIMIT
				{$start}, {$limit}
SQL;

		$query = $this->db->query($sql);
		return $query->result();
	}

	public function act_a7_cut($param)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND TAH.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		$sql = <<<SQL
			SELECT
				COUNT(*) as CUT
			FROM
				T_ACT_D as TA
				LEFT JOIN T_ACT_H as TAH ON(TAH.IDX = TA.H_IDX)
				LEFT JOIN T_INVENTORY_TRANS as TIT ON(TIT.ACT_D_IDX = TA.IDX)
			WHERE
				(`4_QTY` > 0 OR `2_QTY` > 0 OR `3_QTY` > 0)
				{$where}
SQL;
		$query = $this->db->query($sql);
		return $query->row()->CUT;
	}


	public function act_an2_list($param, $start = 0, $limit = 20)
	{
		$where = "";

		if (!empty($param['AM1']) && $param['AM1'] != "") {
			$where .= " AND (TIS.QTY > 0 OR (SELECT IFNULL(SUM(B.QTY), 0) FROM T_ACT_D AS B WHERE B.ITEMS_IDX = TIS.ITEM_IDX AND B.SERIESD_IDX = TIS.SERIESD_IDX AND (STATUS IS NULL OR STATUS != 'CG')) > 0) ";
		} else {
			$where .= " AND TI.USE_YN = 'Y' ";
		}
		if (empty($param['COLOR'])) {
			$where .= " AND TIS.USE_YN = 'Y' ";
		}
		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND TSD.SERIES_IDX = '{$param['V1']}'";
		}
		if (!empty($param['V2']) && $param['V2'] != "A") {
			$where .= " AND TIS.USE_YN = '{$param['V2']}'";
		}
		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND TI.ITEM_NAME LIKE '%{$param['V3']}%'";
		}
		if (!empty($param['V4']) && $param['V4'] != "") {
			$where .= " AND COLOR LIKE '%{$param['V4']}%' ";
		}


		$sql = <<<SQL
			SELECT
				TI.ITEM_NAME, 
				TIS.ITEM_IDX, 
				TIS.SERIESD_IDX, 
				TSD.SERIES_IDX, 
				TSD.COLOR, 
				TIS.QTY, 
				TIS.USE_YN, 
				(
					SELECT 
					A.SERIES_NM 
					FROM 
					T_SERIES_H AS A 
					WHERE 
					A.IDX = TSD.SERIES_IDX
				) AS SE_NAME, 
				(
					SELECT 
					IFNULL( SUM(B.QTY), 0) 
					FROM 
					T_ACT_D AS B 
					WHERE 
					B.ITEMS_IDX = TIS.ITEM_IDX 
					AND B.SERIESD_IDX = TIS.SERIESD_IDX 
					AND (STATUS IS NULL OR STATUS != "CG")
				) AS EQTY 
			FROM
				T_ITEM_STOCK as TIS
				LEFT JOIN T_ITEMS as TI ON(TI.IDX = TIS.ITEM_IDX)
				LEFT JOIN T_SERIES_D as TSD ON(TSD.IDX = TIS.SERIESD_IDX)
			WHERE
				TSD.USE_YN = "Y"
				{$where}
			ORDER BY 
			SE_NAME, ITEM_NAME, COLOR
			LIMIT
				{$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}


	public function act_an2_cut($param, $start = 0, $limit = 20)
	{
		$where = "";

		if (!empty($param['AM1']) && $param['AM1'] != "") {
			$where .= " AND (TIS.QTY > 0 OR (SELECT IFNULL(SUM(B.QTY), 0) FROM T_ACT_D AS B WHERE B.ITEMS_IDX = TIS.ITEM_IDX AND B.SERIESD_IDX = TIS.SERIESD_IDX AND STATUS != 'CG') > 0) ";
		} else {
			$where .= " AND TI.USE_YN = 'Y' ";
		}
		if (empty($param['COLOR'])) {
			$where .= " AND TIS.USE_YN = 'Y' ";
		}
		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND TSD.SERIES_IDX = '{$param['V1']}'";
		}
		if (!empty($param['V2']) && $param['V2'] != "A") {
			$where .= " AND TIS.USE_YN = '{$param['V2']}'";
		}
		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND TI.ITEM_NAME LIKE '%{$param['V3']}%'";
		}
		if (!empty($param['V4']) && $param['V4'] != "") {
			$where .= " AND COLOR LIKE '%{$param['V4']}%' ";
		}

		$sql = <<<SQL
			SELECT
				COUNT(*) as CUT
			FROM
				T_ITEM_STOCK as TIS
				LEFT JOIN T_ITEMS as TI ON(TI.IDX = TIS.ITEM_IDX)
				LEFT JOIN T_SERIES_D as TSD ON(TSD.IDX = TIS.SERIESD_IDX)
			WHERE
				TSD.USE_YN = "Y"
				{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();

		return $query->row()->CUT;
	}






	/* 성형실적 */
	public function act_a81_list($param, $start = 0, $limit = 20)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND A.TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND B.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND B.ITEM_NAME LIKE '%{$param['V2']}%'";
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND A.GJ_GB = '{$param['V3']}'";
		} else {
			$where .= " AND A.GJ_GB = 'SH'";
		}


		$sql = <<<SQL
			SELECT 
				AA.*
			FROM 
				(
					SELECT
						A.TRANS_DATE,
						B.ITEM_NAME,
						A.IN_QTY, 
						A.REMARK,
						H.SERIES_NM
					FROM
						T_ITEMS_TRANS A, 
						T_ITEMS B
					LEFT JOIN
						T_SERIES_H AS H ON H.IDX = B.SERIES_IDX
					WHERE
						A.ITEMS_IDX = B.IDX AND 
						A.KIND = 'IN'
						{$where}
					ORDER BY TRANS_DATE DESC
					LIMIT {$start}, {$limit}
				) as AA
			UNION ALL
			SELECT '','합계' AS ITEM_NAME, SUM(IN_QTY) as IN_QTY, COUNT(IN_QTY),"합계" AS TEXT
			FROM 
				T_ITEMS_TRANS A, 
				T_ITEMS B
			LEFT JOIN
				T_SERIES_H AS H ON H.IDX = B.SERIES_IDX
			WHERE 
				A.ITEMS_IDX = B.IDX
				AND A.KIND = 'IN'  
				{$where}
			ORDER BY 
				TRANS_DATE DESC, SERIES_NM, ITEM_NAME
			
SQL;

		$query = $this->db->query($sql);
		//  echo $this->db->last_query();
		return $query->result();
	}

	/*성형실적 갯수 */
	public function act_a81_cut($param)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND A.TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND B.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND B.ITEM_NAME LIKE '%{$param['V2']}%'";
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND A.GJ_GB = '{$param['V3']}'";
		} else {
			$where .= " AND A.GJ_GB = 'SH'";
		}

		$sql = <<<SQL
			SELECT
				COUNT(A.ITEMS_IDX) as CUT
			FROM
				T_ITEMS_TRANS A, 
				T_ITEMS B
			WHERE
				A.ITEMS_IDX = B.IDX AND 
				A.KIND = 'IN'
				{$where}
SQL;
		$query = $this->db->query($sql);
		return $query->row()->CUT;
	}



	public function page_a10_left_list($param, $start = 0, $limit = 20)
	{
		$this->db->select("A.ITEM_NM, A.ITEMS_IDX, SUM(A.QTY) as SUM_QTY");
		$this->db->from("t_act_d as A");
		$this->db->join("t_act_h as B", "B.IDX = A.H_IDX", "LEFT");
		$this->db->join("t_items as C", "C.IDX = A.ITEMS_IDX", "LEFT");
		$this->db->where("(A.END_YN <> 'Y' OR A.END_YN IS NULL)");
		$this->db->where("(A.SIU_YN <> 'Y' OR A.SIU_YN IS NULL)");

		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("B.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$this->db->where("B.BIZ_IDX", $param['V1']);
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$this->db->where("C.SERIES_IDX", $param['V2']);
		}
		if (!empty($param['V3']) && $param['V3'] != "") {
			$this->db->like("A.ITEM_NM", $param['V3']);
		}


		$this->db->group_by("A.ITEM_NM, A.ITEMS_IDX");
		$this->db->order_by("ITEM_NM");
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		// echo $this->db->last_query();

		return $query->result();
	}


	public function page_a10_left_count($param)
	{
		$where = '';

		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND B.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND B.BIZ_IDX = '" . $param['V1'] . "'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND C.SERIES_IDX = '" . $param['V1'] . "'";
		}

		$sql = <<<SQL
			SELECT
				A.ITEMS_IDX
			FROM
				t_act_d as A
				LEFT JOIN t_act_h as B ON(B.IDX = A.H_IDX)
				LEFT JOIN t_items as C ON(C.IDX = A.ITEMS_IDX)
			WHERE
				(A.END_YN <> "Y" OR A.END_YN IS NULL) AND
				(A.SIU_YN <> 'Y' OR A.SIU_YN IS NULL)
				{$where}
			GROUP BY A.ITEMS_IDX
SQL;
		$query = $this->db->query($sql);


		$data = $query->num_rows();
		return $data;
	}


	public function page_a10_right_list($code, $param)
	{
		$where = '';
		$this->db->where("IDX", $code);
		$query = $this->db->get("t_items");
		$data['JH_QTY'] = $query->row()->JH_QTY;
		$data['ITEM_NAME'] = $query->row()->ITEM_NAME;
		if ($code != "") {
			$where .= " AND A.ITEMS_IDX = '{$code}'";
		}

		$where .= " AND (A.END_YN <> 'Y' OR A.END_YN IS NULL)";
		$where .= " AND (A.SIU_YN <> 'Y' OR A.SIU_YN IS NULL)";


		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND C.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND C.BIZ_IDX = {$param['V1']}";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND B.SERIES_IDX = {$param['V2']}";
		}

		$sql = <<<SQL
		SELECT AA.* FROM (
			SELECT
				C.ACT_DATE,
				A.IDX,
				A.H_IDX,
				A.ITEM_NM,
				A.ITEMS_IDX,
				A.SERIESD_IDX,
				D.COLOR,
				A.QTY,
				( SELECT E.SERIES_NM FROM T_SERIES_H AS E WHERE E.IDX = D.SERIES_IDX ) AS SE_NAME,
				( SELECT F.CUST_NM FROM T_BIZ_REG AS F WHERE F.IDX = C.BIZ_IDX ) AS CUST_NM
			FROM
				t_act_d AS A
				LEFT JOIN t_items AS B ON B.IDX = A.ITEMS_IDX
				LEFT JOIN t_act_h AS C ON C.IDX = A.H_IDX
				LEFT JOIN t_series_d AS D ON D.IDX = A.SERIESD_IDX
			WHERE
				1
				{$where}
			ORDER BY 
				C.ACT_DATE ASC, COLOR
		) AS AA
			UNION
			SELECT
				'',
				'','','','','','',SUM(A.QTY),'합계' AS TEXT,''
			FROM
				t_act_d AS A
				LEFT JOIN t_items AS B ON B.IDX = A.ITEMS_IDX
				LEFT JOIN t_act_h AS C ON C.IDX = A.H_IDX
				LEFT JOIN t_series_d AS D ON D.IDX = A.SERIESD_IDX
			WHERE
				1
				{$where}
				
SQL;

		$query = $this->db->query($sql);


		//$query = $this->db->get();
		// echo $this->db->last_query();
		$data['SLIST'] = $query->result();

		return $data;
	}


	public function ajax_act_a10_insert($params)
	{
		$datas = array();

		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');


		foreach ($params['IN_QTY'] as $k => $qty) {
			$qty = $qty * 1;


			// 			$this->db->where("IDX",$params['ITEMS_IDX'][$k]);
			// 			$que = $this->db->get("T_ITEMS");
			// 			$chk = $que->row();


			// 			if(!empty($chk->IDX)){
			// 				$sql =<<<SQL
			// 				UPDATE
			// 					t_items
			// 				SET
			// 					JH_QTY = ({$chk->JH_QTY}-{$qty})
			// 				WHERE
			// 					IDX = $chk->IDX
			// SQL;
			// 				$this->db->query($sql);		
			// 			}


			$this->db->set(array("SIU_YN" => "Y", "STATUS" => "CU"));
			$this->db->where("IDX", $params['AD_IDX'][$k]);
			$this->db->update("t_act_d");

			$sql = <<<SQL
				INSERT T_INVENTORY_TRANS
				SET
				ITEMS_IDX    = '{$params['ITEMS_IDX'][$k]}',
				SERIESD_IDX  = '{$params['SERIESD_IDX'][$k]}',
				CU_DATE      = '{$params['CU_DATE'][$k]}',
				ACT_IDX      = '{$params['ACT_IDX'][$k]}',
				ACT_D_IDX    = '{$params['AD_IDX'][$k]}',
				KIND         = 'IN',
				GJ_GB        = 'CU',
				IN_QTY       = '{$qty}',
				INSERT_ID    = '{$username}',
				INSERT_DATE  = '{$datetime}'
SQL;
			$this->db->query($sql);
			// $itemidx = $this->db->insert_id();

			$sql = <<<SQL
				INSERT INTO T_ITEMS_TRANS
				SET
					ITEMS_IDX		= '{$params['ITEMS_IDX'][$k]}',
					ACT_IDX    		= '{$params['ACT_IDX'][$k]}',
					TRANS_DATE		= '{$params['CU_DATE'][$k]}',
					KIND			= "OT",
					GJ_GB			= "JH",
					OUT_QTY			= '{$qty}',
					INSERT_ID		= '{$username}',
					INSERT_DATE		= '{$datetime}'
SQL;
			$this->db->query($sql);


			// $datax = array(
			// 	//"H_IDX"        => $params['H_IDX'][$k],
			// 	"ITEMS_IDX"    => $params['ITEMS_IDX'][$k],
			// 	"SERIESD_IDX"  => $params['SERIESD_IDX'][$k],
			// 	"CU_DATE"      => $params['CU_DATE'][$k],
			// 	"ACT_IDX"      => $params['ACT_IDX'][$k],
			// 	"ACT_D_IDX"    => $params['AD_IDX'][$k],
			// 	"KIND"         => "IN",
			// 	"GJ_GB"        => "CU",
			// 	"IN_QTY"       => $qty,
			// 	"INSERT_ID"    => $username,
			// 	"INSERT_DATE"  => $datetime
			// );
			// array_push($datas,$datax);
		}

		// $this->db->insert_batch("t_inventory_trans",$datas);
		// alert($this->db->insert_id());
		return $this->db->affected_rows();
	}



	public function get_a10_1_list($param, $start = 0, $limit = 20)
	{
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where(" CU_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}



		if (!empty($param['BK'])) {
			$GJGB = "JHBK";
		} else {
			$GJGB = $param['GJGB'];
		}

		$this->db->where("GJ_GB", $GJGB);
		$this->db->where("KIND", "IN");


		$this->db->select("CU_DATE");
		$this->db->group_by("CU_DATE");
		$this->db->order_by("CU_DATE DESC");
		$this->db->limit($limit, $start);
		$query = $this->db->get("t_inventory_trans");

		return $query->result();
	}

	public function get_a10_1_left_count($param)
	{
		$where = '';

		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND CU_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND B.BIZ_IDX = '" . $param['V1'] . "'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND C.SERIES_IDX = '" . $param['V1'] . "'";
		}

		$sql = <<<SQL
			SELECT
				CU_DATE
			FROM
				t_inventory_trans
			WHERE
				1
				{$where}
			GROUP BY CU_DATE
SQL;
		$query = $this->db->query($sql);
		$data = $query->num_rows();

		return $data;
	}


	public function get_a10_1_right_list($date = '', $param)
	{
		/*$this->db->where("IDX",$code);
		$query = $this->db->get("t_items");
		$data['JH_QTY'] = $query->row()->JH_QTY;
		$data['ITEM_NAME'] = $query->row()->ITEM_NAME;*/


		$this->db->select("A.IDX, C.ACT_DATE, B.ITEM_NM, D.COLOR, B.QTY, A.IN_QTY, A.GJ_GB, B.END_YN, (SELECT F.CUST_NM FROM T_BIZ_REG AS F WHERE F.IDX = C.BIZ_IDX ) AS CUST_NM");
		$this->db->from("t_inventory_trans as A");
		$this->db->join("t_act_d as B","B.IDX = A.ACT_D_IDX","LEFT");
		$this->db->join("t_series_d as D","D.IDX = A.SERIESD_IDX","LEFT");
		$this->db->join("t_act_h as C","C.IDX = A.ACT_IDX","LEFT");
		
		// $this->db->where("A.GJ_GB","CU");
		$this->db->where("A.KIND","IN");

		if ($date != "") {
			$this->db->where("A.CU_DATE", $date);
		}

		// if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
		// 	$this->db->where("C.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		// }

		if (!empty($param['V1']) && $param['V1'] != "") {
			$this->db->where("C.BIZ_IDX", $param['V1']);
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$this->db->where("B.SERIES_IDX", $param['V2']);
		}

		$this->db->order_by("C.ACT_DATE", "DESC");
		$this->db->order_by("B.SERIESD_IDX");
		$this->db->order_by("B.ITEM_NM");
		$this->db->order_by("COLOR");
		$query = $this->db->get();
		// echo $this->db->last_query();

		return $query->result();
	}


	public function ajax_a10_1_qty($param)
	{
		$this->db->trans_start();

		$this->db->where("IDX", $param['idx']);
		$query = $this->db->get("t_inventory_trans");
		$info = $query->row();


		if ($info->IN_QTY > $param['qty']) {
			$QTY = $info->IN_QTY - $param['qty'];
			$this->db->set("JH_QTY", "JH_QTY+{$QTY}", FALSE);
		} elseif ($info->IN_QTY < $param['qty']) {
			$QTY = ($info->IN_QTY - $param['qty']) * -1;
			$this->db->set("JH_QTY", "JH_QTY-{$QTY}", FALSE);
		}

		$this->db->where("IDX", $info->ITEMS_IDX);
		$this->db->update("t_items");

		$sql = <<<SQL
			UPDATE
				t_inventory_trans
			SET
				IN_QTY = {$param['qty']}
			WHERE
				IDX = {$param['idx']}
SQL;
		$query = $this->db->query($sql);

		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}
		return $data;
	}


	public function ajax_del_a10_1($idx)
	{

		$this->db->trans_start();

		$this->db->from("t_inventory_trans");
		$this->db->where("IDX", $idx);
		$query = $this->db->get();
		$info = $query->row();

		// 재고
		// $this->db->set("JH_QTY","JH_QTY + {$info->IN_QTY}",false);
		// $this->db->where("IDX",$info->ITEMS_IDX);
		// $this->db->update("t_items");

		$this->db->set('SIU_YN', 'NULL', false);
		$this->db->set('STATUS', 'NULL', false);
		$this->db->where("IDX", $info->ACT_D_IDX);
		$this->db->update("t_act_d");

		$this->db->where("IDX", $idx);
		$this->db->delete("t_inventory_trans");

		// $this->db->where("INSERT_DATE",$info->INSERT_DATE);
		// $this->db->where("ITEMS_IDX",$info->ITEMS_IDX);
		// $this->db->where("ACT_IDX",$info->ACT_IDX);
		$this->db->where("ITEM_IDX", $info->IDX);
		$this->db->delete("t_items_trans");

		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}


	public function act_a102_list($param, $start = 0, $limit = 20)
	{
		$this->db->query('SET SESSION sql_mode = ""');

		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND A.CU_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND D.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND B.ITEM_NM LIKE '%{$param['V2']}%'";
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND D.COLOR = '{$param['V3']}'";
		}


		$sql = <<<SQL
			SELECT 
				AA.*
			FROM 
				(
					SELECT
						A.CU_DATE,
						B.ITEM_NM,
						D.COLOR,
						B.QTY,
						A.IN_QTY,
						A.REMARK,
						H.SERIES_NM
					FROM
						T_INVENTORY_TRANS A
						LEFT JOIN T_ACT_D as B ON(B.IDX = A.ACT_D_IDX)
						LEFT JOIN T_ACT_H as C ON(C.IDX = A.ACT_IDX)
						LEFT JOIN T_SERIES_D as D ON(D.IDX = A.SERIESD_IDX)
						LEFT JOIN T_SERIES_H as H ON(H.IDX = D.SERIES_IDX)
					WHERE
						1
						{$where}
					ORDER BY
						CU_DATE DESC,
						SERIES_NM,
						ITEM_NM,
						COLOR
					LIMIT {$start},{$limit}
				) as AA 
			
			UNION ALL
			SELECT 
				'' AS TEXT,
				'합계' AS ITEM_NM,
				'' AS COLOR,
				SUM(B.QTY) as QTY,
			    SUM(A.IN_QTY) as IN_QTY,
			    COUNT(A.IDX),
			    ''
			FROM 
				T_INVENTORY_TRANS A
				LEFT JOIN T_ACT_D as B ON(B.IDX = A.ACT_D_IDX)
				LEFT JOIN T_ACT_H as C ON(C.IDX = A.ACT_IDX)
				LEFT JOIN T_SERIES_D as D ON(D.IDX = A.SERIESD_IDX)
				LEFT JOIN T_SERIES_H as H ON(H.IDX = D.SERIES_IDX)
			WHERE 
				1
				{$where}
			ORDER BY
				CU_DATE DESC,
				SERIES_NM,
				ITEM_NM,
				COLOR
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();

		return $query->result();
	}
	public function act_a102_cut($param, $start = 0, $limit = 20)
	{
		$this->db->query('SET SESSION sql_mode = ""');

		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND A.CU_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND D.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND B.ITEM_NM LIKE '%{$param['V2']}%'";
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND D.COLOR = '{$param['V3']}'";
		}


		$sql = <<<SQL
			SELECT 
				COUNT(A.IDX) AS CUT
			FROM
				T_INVENTORY_TRANS A
				LEFT JOIN T_ACT_D as B ON(B.IDX = A.ACT_D_IDX)
				LEFT JOIN T_ACT_H as C ON(C.IDX = A.ACT_IDX)
				LEFT JOIN T_SERIES_D as D ON(D.IDX = A.SERIESD_IDX)
				LEFT JOIN T_SERIES_H as H ON(H.IDX = D.SERIES_IDX)
			WHERE
				1
				{$where}
	
			
SQL;
		$query = $this->db->query($sql);
		// echo $query->row()->CUT;
		return $query->row()->CUT;
	}

	public function get_inventory_info($IDX)
	{
		$sql =<<<SQL
			SELECT
				tit.IN_QTY,ti.ITEM_NAME,tsd.COLOR
			FROM
				`t_inventory_trans` as tit,t_items as ti, t_series_d as tsd
			WHERE
				tit.IDX = '{$IDX}'
				AND tit.ITEMS_IDX = ti.IDX
				AND tit.SERIESD_IDX = tsd.IDX

SQL;
	$query=$this->db->query($sql);
		// echo $this->db->last_query();
		
		return $query->row();
	}


	public function form_a11_update($param)
	{
		$this->db->where("IDX", $param['IDX']);
		$qu = $this->db->get("t_inventory_trans");
		$info = $qu->row();

		$this->db->trans_start();

		$set = array(
			"SB_DATE"	=> $param['SB_DATE'],
			"GJ_GB"     => "SB",
			"1_QTY" 	=> $param['1_QTY'],
			"2_QTY" 	=> $param['2_QTY'],
			"3_QTY"	    => $param['3_QTY'],
			"4_QTY"  	=> $param['4_QTY'],
			"REMARK"	=> $param['REMARK'],
			"UPDATE_ID" => $this->session->userdata('user_name'),
			"UPDATE_DATE" => date("Y-m-d H:i:s", time())
		);

		if (!empty($param['IMG'])) {
			foreach ($param['IMG'] as $i => $img) {
				$n = $i + 1;
				$set['IMG_LINK' . $n] = $img;
			}
		}

		$this->db->set($set);
		$this->db->where("IDX", $info->IDX);
		$this->db->update("t_inventory_trans");

		if ($this->db->affected_rows() > 0) {


			$this->db->set("STATUS", "SB");
			$this->db->where("IDX", $info->ACT_D_IDX);
			$this->db->update("T_ACT_D");
		}

		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}


	public function get_a11_1_list_left($param)
	{
		$this->db->query('SET SESSION sql_mode = ""');

		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where(" SB_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}

		$this->db->where("GJ_GB", "SB");

		$this->db->select("IDX, SB_DATE");
		$this->db->group_by("SB_DATE");
		$this->db->order_by("SB_DATE DESC");
		//$this->db->limit($limit,$start);
		$query = $this->db->get("t_inventory_trans");
		return $query->result();
	}


	public function get_a11_1_list_right($date, $param)
	{
		$this->db->select("A.IDX, B.H_IDX, C.ACT_DATE, A.`1_QTY` as QTY1, A.`2_QTY` as QTY2, A.`3_QTY` as QTY3, A.`4_QTY` as QTY4, B.ITEM_NM, D.COLOR, B.QTY, A.IN_QTY, B.END_YN");
		$this->db->from("t_inventory_trans as A");
		$this->db->join("t_act_h as C", "C.IDX = A.ACT_IDX", "LEFT");
		$this->db->join("t_act_d as B", "B.IDX = A.ACT_D_IDX", "LEFT");
		$this->db->join("t_series_d as D", "D.IDX = A.SERIESD_IDX", "LEFT");

		// if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
		// 	$this->db->where("A.SB_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		// }

		if (!empty($param['V1']) && $param['V1'] != "") {
			$this->db->where("C.BIZ_IDX", $param['V1']);
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$this->db->where("D.SERIES_IDX", $param['V2']);
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$this->db->where("B.ITEM_NM", $param['V3']);
		}

		//if(!empty($param['V4']) && $param['V4'] != ""){
		$this->db->where("A.SB_DATE", $date);
		$this->db->where("A.GJ_GB", 'SB');
		//}



		//$this->db->limit($limit,$start);
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}


	public function get_a11_1_count()
	{
	}


	public function ajax_a11_1_update($param)
	{
		$this->db->select("ITEMS_IDX, SERIESD_IDX, 1_QTY as QTY1,ACT_D_IDX");
		$this->db->where("IDX", $param['IDX']);
		$qu = $this->db->get("t_inventory_trans");
		$info = $qu->row();

		$SUMQ = ($param['QTY1'] - $info->QTY1) * 1;



		$this->db->trans_start();

		$set = array(
			"1_QTY" => $param['QTY1'],
			"2_QTY" => $param['QTY2'],
			"3_QTY" => $param['QTY3'],
			"4_QTY" => $param['QTY4']
		);
		$this->db->set($set)
			->where("IDX", $param['IDX'])
			->update("t_inventory_trans");

		if ($this->db->affected_rows() > 0) {
			$this->db->set("STATUS", "SB");
			$this->db->where("IDX", $info->ACT_D_IDX);
			$this->db->update("T_ACT_D");
			// if ($SUMQ > 0) {
			// 	$this->db->set("QTY", "QTY + {$SUMQ}", FALSE);
			// } else {
			// 	$this->db->set("QTY", "QTY {$SUMQ}", FALSE);
			// }


			// $this->db->where(array("ITEM_IDX" => $info->ITEMS_IDX, "SERIESD_IDX" => $info->SERIESD_IDX));
			// $this->db->update("t_item_stock");
		}

		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}


	public function ajax_a11_1_delete($idx)
	{
		$this->db->select("IDX, ITEMS_IDX, SERIESD_IDX, 1_QTY as QTY1, IMG_LINK1, IMG_LINK2, IMG_LINK3, ACT_D_IDX");
		$this->db->where("IDX", $idx);
		$qu = $this->db->get("t_inventory_trans");
		$info = $qu->row_array();

		$this->db->trans_start();

		$set = array(
			"SB_DATE"	=> null,
			"GJ_GB"     => "CU",
			"1_QTY" 	=> null,
			"2_QTY" 	=> null,
			"3_QTY"	    => null,
			"4_QTY"  	=> null,
			"REMARK"	=> null,
			"UPDATE_ID" => $this->session->userdata('user_name'),
			"UPDATE_DATE" => date("Y-m-d H:i:s", time())
		);


		$filePath = FCPATH . 'uploads/';
		for ($i = 1; $i <= 3; $i++) {

			//$img.$i = $info->IMG_LINK

			if ($info['IMG_LINK' . $i] != "") {
				$file = $filePath . $info['IMG_LINK' . $i];

				if (file_exists($file)) {
					unlink($file);
				}
				$set['IMG_LINK' . $i] = '';
			}
		}

		$this->db->set($set);
		$this->db->where("IDX", $info['IDX']);
		$this->db->update("t_inventory_trans");
		if ($this->db->affected_rows() > 0) {

			$this->db->set("STATUS", "CU");
			$this->db->where("IDX", $info['ACT_D_IDX']);
			$this->db->update("T_ACT_D");
		}

		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}


	public function ajax_an3_listupdate($param)
	{
		$query = $this->db->select("`2_QTY` as QT2, ITEMS_IDX, SERIESD_IDX")
			->get("T_INVENTORY_TRANS", array("IDX" => $param['IDX']));
		$info = $query->row();

		$this->db->trans_start();

		$query = $this->db->set("RECYCLE", $param['VX'])
			->set("`2_QTY`", 0)
			->where("IDX", $param['IDX'])
			->update("T_INVENTORY_TRANS");

		$query = $this->db->set("QTY", "QTY+{$info->QT2}", false)
			->where(array("ITEM_IDX" => $info->ITEMS_IDX, "SERIESD_IDX" => $info->SERIESD_IDX))
			->update("T_ITEM_STOCK");

		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}



	public function ajax_an4_listupdate($param)
	{

		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata("user_name");

		$query = $this->db->set($param['QTY'], $param['STOCK'])
			->set("GJ_GB", "JG")
			->set("KIND", $param['KIND'])
			->set("REMARK", $param['REMARK'])
			->set("INSERT_ID", $username)
			->set("INSERT_DATE", $datetime)
			->set("KS_DATE", $datetime)
			->set("ITEMS_IDX", $param['ITEM_IDX'])
			->set("SERIESD_IDX", $param['SERIESD_IDX'])
			->insert("T_INVENTORY_TRANS");


		return $this->db->affected_rows();
	}




	public function act_a11_2_list($param, $start = 0, $limit = 20)
	{

		$this->db->query('SET SESSION sql_mode = ""');

		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND A.SB_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND D.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND C.ITEM_NM LIKE '%{$param['V2']}%'";
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND D.COLOR LIKE '%{$param['V3']}%'";
		}

		$where .= " AND A.GJ_GB != 'CU'";


		$sql = <<<SQL
			SELECT 
				AA.*
			FROM 
				(
					SELECT
						A.SB_DATE,
						C.ITEM_NM,
						D.COLOR,
						C.QTY,
						A.IN_QTY,
						A.`1_QTY` as QTY1,
						A.`2_QTY` as QTY2,
						A.`3_QTY` as QTY3,
						A.`4_QTY` as QTY4,
						A.`5_QTY` as QTY5
					FROM
						T_INVENTORY_TRANS A
						LEFT JOIN T_ACT_H as B ON(B.IDX = A.ACT_IDX)
						LEFT JOIN T_ACT_D as C ON(C.IDX = A.ACT_D_IDX)
						LEFT JOIN T_SERIES_D as D ON(D.IDX = A.SERIESD_IDX)
					WHERE
						1
						{$where}
					LIMIT {$start},{$limit}
				) as AA
			
			UNION
			SELECT 
				'' AS ACT_DATE,
				'합계' AS ITEM_NM,
				'' AS COLOR,
				SUM(C.QTY) as QTY,
				SUM(A.IN_QTY) as IN_QTY,
				SUM(A.`1_QTY`) as QTY1,
				SUM(A.`2_QTY`) as QTY2,
				SUM(A.`3_QTY`) as QTY3,
				SUM(A.`4_QTY`) as QTY4,
				SUM(A.`5_QTY`) as QTY5
			FROM 
				T_INVENTORY_TRANS A
				LEFT JOIN T_ACT_H as B ON(B.IDX = A.ACT_IDX)
				LEFT JOIN T_ACT_D as C ON(C.IDX = A.ACT_D_IDX)
				LEFT JOIN T_SERIES_D as D ON(D.IDX = A.SERIESD_IDX)
			WHERE 
				1
				{$where}
			ORDER BY 
				SB_DATE DESC,
				ITEM_NM,
				COLOR
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();

		return $query->result();
	}

	public function act_a11_2_cut($param, $start = 0, $limit = 20)
	{

		$this->db->query('SET SESSION sql_mode = ""');

		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND B.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND D.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND C.ITEM_NM LIKE '%{$param['V2']}%'";
		}

		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND D.COLOR LIKE '%{$param['V3']}%'";
		}

		$where .= " AND A.GJ_GB = 'SB'";


		$sql = <<<SQL
			SELECT 
				COUNT(*) AS CUT
			FROM 
				(
					SELECT
						B.ACT_DATE,
						C.ITEM_NM,
						D.COLOR,
						C.QTY,
						A.IN_QTY,
						A.`1_QTY` as QTY1,
						A.`2_QTY` as QTY2,
						A.`3_QTY` as QTY3,
						A.`4_QTY` as QTY4
					FROM
						T_INVENTORY_TRANS A
						LEFT JOIN T_ACT_H as B ON(B.IDX = A.ACT_IDX)
						LEFT JOIN T_ACT_D as C ON(C.IDX = A.ACT_D_IDX)
						LEFT JOIN T_SERIES_D as D ON(D.IDX = A.SERIESD_IDX)
					WHERE
						1
						{$where}
				) as AA
			
			
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();

		return $query->row()->CUT;
	}


	public function print_actpln($param, $start = 0, $limit = 20)
	{

		$this->db->query('SET SESSION sql_mode = ""');

		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND B.ACT_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND D.SERIES_IDX = '{$param['V1']}'";
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND C.ITEM_NM LIKE '%{$param['V2']}%'";
		}

		$date = date("Y-m-d", time());
		$date = "2021-01-12";
		$where .= " AND A.GJ_GB = 'SB'";
		$where .= " AND A.SB_DATE BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'";


		$sql = <<<SQL
			SELECT 
				AA.*
			FROM 
				(
					SELECT
						B.ACT_DATE,
						C.ITEM_NM,
						D.COLOR,
						C.QTY,
						A.IN_QTY,
						A.`1_QTY` as QTY1,
						A.`2_QTY` as QTY2,
						A.`3_QTY` as QTY3,
						A.`4_QTY` as QTY4
					FROM
						T_INVENTORY_TRANS A
						LEFT JOIN T_ACT_H as B ON(B.IDX = A.ACT_IDX)
						LEFT JOIN T_ACT_D as C ON(C.IDX = A.ACT_D_IDX)
						LEFT JOIN T_SERIES_D as D ON(D.IDX = A.SERIESD_IDX)
					WHERE
						1
						{$where}
					ORDER BY B.ACT_DATE DESC
				) as AA
			
			UNION
			SELECT 
				'',
				'합계' AS TEXT,C.ITEM_NM, 
				SUM(C.QTY) as QTY,
				SUM(A.IN_QTY) as IN_QTY,
				SUM(A.`1_QTY`) as QTY1,
				SUM(A.`2_QTY`) as QTY2,
				SUM(A.`3_QTY`) as QTY3,
				SUM(A.`4_QTY`) as QTY4
			FROM 
				T_INVENTORY_TRANS A
				LEFT JOIN T_ACT_H as B ON(B.IDX = A.ACT_IDX)
				LEFT JOIN T_ACT_D as C ON(C.IDX = A.ACT_D_IDX)
				LEFT JOIN T_SERIES_D as D ON(D.IDX = A.SERIESD_IDX)
			WHERE 
				1
				{$where}
			GROUP BY A.ITEMS_IDX
			-- LIMIT 0, 5
SQL;


		$query = $this->db->query($sql);
		return $query->result();
	}


	public function get_inventory_img()
	{
		$where = '';
		$date = date("Y-m-d", time());
		$date = "2021-01-12";
		$where .= " AND A.GJ_GB = 'SB'";
		$where .= " AND A.SB_DATE BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'";

		$sql = <<<SQL

			SELECT
				A.IMG_LINK1,A.IMG_LINK2,A.IMG_LINK3,B.ITEM_NAME
			FROM
				T_INVENTORY_TRANS as A
				LEFT JOIN T_ITEMS as B ON(B.IDX = A.ITEMS_IDX)
			WHERE
				1
				AND (A.IMG_LINK1 IS NOT NULL OR A.IMG_LINK2 IS NOT NULL OR A.IMG_LINK3 IS NOT NULL)
				{$where}
			GROUP BY A.ITEMS_IDX

SQL;
		$query = $this->db->query($sql);
		return $query->result();
	}


	public function ajax_color_useupdate($param)
	{
		$query = $this->db->set("USE_YN", $param['USE_YN'])
			->where(array("ITEM_IDX" => $param['ITEM_IDX'], "SERIESD_IDX" => $param['SERIESD_IDX']))
			->update("T_ITEM_STOCK");

		return $this->db->affected_rows();
	}

	/* 성형정형재고 */
	public function act_am11_list($param, $start = 0, $limit = 20)
	{
		if (!empty($param['V1']) && $param['V1'] != "") {
			$this->db->where("TI.SERIES_IDX", $param['V1']);
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$this->db->LIKE("ti.ITEM_NAME", $param['V2']);
		}

		$this->db->select("TSH.SERIES_NM, ITEM_NO, ITEM_NAME, SH_QTY, JH_QTY");
		$this->db->from("t_items as ti");
		$this->db->join("t_series_h as tsh", "tsh.idx=ti.series_idx", "LEFT");
		$this->db->where("ti.use_yn = 'Y' AND tsh.use_yn = 'Y' AND (SH_QTY > 0 OR JH_QTY > 0)");
		$this->db->limit($limit, $start);
		$this->db->order_by("SERIES_NM, ITEM_NAME");

		$query = $this->db->get();

		return $query->result();
	}

	public function act_am11_cut($param)
	{
		if (!empty($param['V1']) && $param['V1'] != "") {
			$this->db->where("TI.SERIES_IDX", $param['V1']);
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$this->db->LIKE("ti.ITEM_NAME", $param['V2']);
		}

		$this->db->select("COUNT(*) as CUT");
		$this->db->from("t_items as ti");
		$this->db->join("t_series_h as tsh", "tsh.idx=ti.series_idx", "LEFT");
		$this->db->where("ti.use_yn = 'Y' AND tsh.use_yn = 'Y' AND (SH_QTY > 0 OR JH_QTY > 0)");
		$query = $this->db->get();
		return $query->row()->CUT;
	}

	/* 시유재고 */
	public function act_am12_list($param, $start = 0, $limit = 20)
	{
		if (!empty($param['V1']) && $param['V1'] != "") {
			$this->db->where("D.SERIES_IDX", $param['V1']);
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$this->db->like("B.ITEM_NM", $param['V2']);
		}

		$this->db->select("B.ITEM_NM,D.COLOR,SUM(A.IN_QTY) as IN_QTY,H.SERIES_NM, SERIES_IDX, A.ITEMS_IDX, A.SERIESD_IDX ");
		$this->db->from("T_INVENTORY_TRANS A");
		$this->db->join("T_ACT_D AS B", "B.IDX = A.ACT_D_IDX ", "LEFT");
		$this->db->join("T_SERIES_D AS D ", "D.IDX = A.SERIESD_IDX ", "LEFT");
		$this->db->join("T_SERIES_H AS H", "H.IDX = D.SERIES_IDX ", "LEFT");
		$this->db->where("A.KIND = 'IN' AND A.GJ_GB='CU'");
		$this->db->group_by("ITEM_NM,COLOR,SERIES_NM,SERIES_IDX,A.ITEMS_IDX,A.SERIESD_IDX");
		$this->db->limit($limit, $start);

		$this->db->get();

		
		$query = $this->db->query("SELECT AA.* FROM (". $sql.")as AA ORDER BY SERIES_NM,ITEM_NM,COLOR");
		// echo $this->db->last_query();
		return $query->result();
	}

	public function act_am12_cut($param)
	{
		if (!empty($param['V1']) && $param['V1'] != "") {
			$this->db->where("D.SERIES_IDX", $param['V1']);
		}

		if (!empty($param['V2']) && $param['V2'] != "") {
			$this->db->where("B.ITEM_NM", $param['V2']);
		}

		$this->db->select("COUNT(*) as CUT");
		$this->db->from("T_INVENTORY_TRANS A");
		$this->db->join("T_ACT_D AS B", "B.IDX = A.ACT_D_IDX ", "LEFT");
		$this->db->join("T_SERIES_D AS D ", "D.IDX = A.SERIESD_IDX ", "LEFT");
		$this->db->join("T_SERIES_H AS H", "H.IDX = D.SERIES_IDX ", "LEFT");
		$this->db->where("A.KIND = 'IN' AND A.GJ_GB='CU'");
		$query = $this->db->get();
		return $query->row()->CUT;
	}

	public function act_a12_list($param, $start = 0, $limit = 20)
	{
		$where = "";

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND TSD.SERIES_IDX = '{$param['V1']}'";
		}
		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND TIS.USE_YN = '{$param['V2']}'";
		}
		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND TI.ITEM_NAME LIKE '%{$param['V3']}%'";
		}
		if (!empty($param['V4']) && $param['V4'] != "") {
			$where .= " AND COLOR LIKE '%{$param['V4']}%' ";
		}

		$sql = <<<SQL
SELECT
   TI.ITEM_NAME,
   TIT.IDX,
   TSD.COLOR,
   TAD.QTY AS IN_QTY,
   TIS.QTY,
   TAH.ACT_NAME,
   TAH.ACT_DATE,
   ( SELECT A.SERIES_NM FROM T_SERIES_H AS A WHERE A.IDX = TSD.SERIES_IDX ) AS SE_NAME 
FROM
   T_INVENTORY_TRANS AS TIT,
   T_ACT_H AS TAH ,
   T_ACT_D AS TAD ,
   T_ITEMS AS TI ,
   T_SERIES_D AS TSD, 
   T_ITEM_STOCK AS TIS
WHERE
   	TIT.GJ_GB = "SB" 
   	AND TI.IDX = TIT.ITEMS_IDX
   	AND TSD.IDX = TIT.SERIESD_IDX 
   	AND TAH.IDX = TIT.ACT_IDX
   	AND TAD.IDX = TIT.ACT_D_IDX 
   	AND TIS.ITEM_IDX = TIT.ITEMS_IDX AND TIS.SERIESD_IDX = TIT.SERIESD_IDX 
   	AND IFNULL(TAD.END_YN ,'A') != 'Y'
   	AND TI.KS_YN = 'Y' 
	AND NOT EXISTS (SELECT '*' FROM T_INVENTORY_TRANS WHERE ACT_D_IDX = TIT.ACT_D_IDX AND GJ_GB = 'KS')
	{$where}
ORDER BY
   TAH.ACT_DATE ASC,
   SE_NAME,
   TI.ITEM_NAME,
   TSD.COLOR 
   LIMIT	{$start},{$limit}
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}


	public function act_a12_cut($param)
	{
		$where = "";

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND TSD.SERIES_IDX = '{$param['V1']}'";
		}
		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND TIS.USE_YN = '{$param['V2']}'";
		}
		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND TI.ITEM_NAME LIKE '%{$param['V3']}%'";
		}
		if (!empty($param['V4']) && $param['V4'] != "") {
			$where .= " AND TSD.COLOR LIKE '%{$param['V4']}%' ";
		}


		$sql = <<<SQL
			SELECT
				COUNT(*) as CUT
			FROM
				T_INVENTORY_TRANS as TIT
				LEFT JOIN T_ITEMS as TI ON(TI.IDX = TIT.ITEMS_IDX)
				LEFT JOIN T_SERIES_D as TSD ON(TSD.IDX = TIT.SERIESD_IDX)
				LEFT JOIN T_ITEM_STOCK as TIS ON(TIS.ITEM_IDX = TIT.ITEMS_IDX AND TIS.SERIESD_IDX = TIT.SERIESD_IDX)
				LEFT JOIN T_ACT_H as TAH ON(TAH.IDX = TIT.ACT_IDX)
				LEFT JOIN T_ACT_D as TAD ON(TAD.IDX = TIT.ACT_D_IDX)
			WHERE
				TIT.GJ_GB = "SB"
				AND TI.KS_YN = 'Y' 
				{$where}
SQL;
		$query = $this->db->query($sql);

		return $query->row()->CUT;
	}

	public function ajax_a12_ksupdate($param)
	{
		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata("user_name");

		$this->db->from("t_inventory_trans");
		$this->db->where("IDX",$param['IDX']);
		$query = $this->db->get();
		$info = $query->row();


		if($param['GJ_GB'] == 'SB'){
			$sql = <<<SQL
			DELETE FROM T_INVENTORY_TRANS
			WHERE IDX = '{$param['IDX']}'
SQL;
			$query = $this->db->query($sql);
		}else{
			$query = $this->db->set("OUT_QTY","{$param['5_QTY']}",FALSE)
								->set("KS_DATE",$param['KS_DATE'])
								->set("INSERT_ID",$username)
								->set("INSERT_DATE",$datetime)
								->set("KIND",$param['KIND'])
								->set("GJ_GB",$param['GJ_GB'])
								->set("ITEMS_IDX",$info->ITEMS_IDX)
								->set("SERIESD_IDX",$info->SERIESD_IDX)
								->set("ACT_IDX",$info->ACT_IDX)
								->set("ACT_D_IDX",$info->ACT_D_IDX)
								->insert("T_INVENTORY_TRANS");
	}

		// 	$sql=<<<SQL
		// 		UPDATE 	T_ITEM_STOCK AS TIS
		// 		JOIN	T_INVENTORY_TRANS AS TIT ON TIT.IDX = {$param['IDX']}
		// 		SET 	TIS.QTY = TIS.QTY - {$param['5_QTY']}
		// 		WHERE 	TIS.ITEM_IDX = TIT.ITEMS_IDX AND TIS.SERIESD_IDX = TIT.SERIESD_IDX
		// SQL;
		// 		$query = $this->db->query($sql);


		return $this->db->affected_rows();
	}

	public function act_a121_list($param, $start = 0, $limit = 20)
	{
		$where = "";

		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND TIT.KS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND TSD.SERIES_IDX = '{$param['V1']}'";
		}
		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND TIS.USE_YN = '{$param['V2']}'";
		}
		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND TI.ITEM_NAME LIKE '%{$param['V3']}%'";
		}
		if (!empty($param['V4']) && $param['V4'] != "") {
			$where .= " AND COLOR LIKE '%{$param['V4']}%' ";
		}
		if (!empty($param['KIND']) && $param['KIND'] != "") {
			$where .= " AND KIND = '{$param['KIND']}' ";
		}
		if (!empty($param['GJGB']) && $param['GJGB'] != "JG") {
			$where .= " AND TIT.GJ_GB = '{$param['GJGB']}' ";
		}else{
			$where .= " AND TIT.GJ_GB = '{$param['GJGB']}' ";
		}


		$sql = <<<SQL
			SELECT
				TI.ITEM_NAME, TIT.IDX, TSD.COLOR, TAD.QTY AS SJ_QTY, TIS.QTY, TIT.KS_DATE, TIT.OUT_QTY, TIT.IN_QTY, TIT.REMARK, KIND,
				( SELECT A.SERIES_NM FROM T_SERIES_H AS A WHERE A.IDX = TSD.SERIES_IDX ) AS SE_NAME
			FROM
				T_INVENTORY_TRANS as TIT
				LEFT JOIN T_ITEMS as TI ON(TI.IDX = TIT.ITEMS_IDX)
				LEFT JOIN T_SERIES_D as TSD ON(TSD.IDX = TIT.SERIESD_IDX)
				LEFT JOIN T_ITEM_STOCK as TIS ON(TIS.ITEM_IDX = TIT.ITEMS_IDX AND TIS.SERIESD_IDX = TIT.SERIESD_IDX)
				LEFT JOIN T_ACT_H as TAH ON(TAH.IDX = TIT.ACT_IDX)
				LEFT JOIN T_ACT_D as TAD ON(TAD.IDX = TIT.ACT_D_IDX)
			WHERE
				1
				{$where}
			ORDER BY 
				KS_DATE desc, TIT.INSERT_DATE DESC, SE_NAME, ITEM_NAME, COLOR
			LIMIT
				{$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		//echo $this->db->last_query();
		return $query->result();
	}


	public function act_a121_cut($param)
	{
		$where = "";

		if (!empty($param['V1']) && $param['V1'] != "") {
			$where .= " AND TSD.SERIES_IDX = '{$param['V1']}'";
		}
		if (!empty($param['V2']) && $param['V2'] != "") {
			$where .= " AND TIS.USE_YN = '{$param['V2']}'";
		}
		if (!empty($param['V3']) && $param['V3'] != "") {
			$where .= " AND TI.ITEM_NAME LIKE '%{$param['V3']}%'";
		}
		if (!empty($param['V4']) && $param['V4'] != "") {
			$where .= " AND TSD.COLOR LIKE '%{$param['V4']}%' ";
		}
		if (!empty($param['GJGB']) && $param['GJGB'] != "JG") {
			$where .= " AND TIT.GJ_GB = '{$param['GJGB']}' ";
		}else{
			$where .= " AND TIT.GJ_GB = '{$param['GJGB']}' ";
		}

		$sql = <<<SQL
			SELECT
				COUNT(*) as CUT
			FROM
				T_INVENTORY_TRANS as TIT
				LEFT JOIN T_ITEMS as TI ON(TI.IDX = TIT.ITEMS_IDX)
				LEFT JOIN T_SERIES_D as TSD ON(TSD.IDX = TIT.SERIESD_IDX)
				LEFT JOIN T_ITEM_STOCK as TIS ON(TIS.ITEM_IDX = TIT.ITEMS_IDX AND TIS.SERIESD_IDX = TIT.SERIESD_IDX)
				LEFT JOIN T_ACT_H as TAH ON(TAH.IDX = TIT.ACT_IDX)
			WHERE
				1
				{$where}
SQL;
		$query = $this->db->query($sql);

		return $query->row()->CUT;
	}

	public function get_component_stock()
	{
		$this->db->select("STOCK");
		$this->db->from("T_COMPONENT");
		$this->db->where("COMPONENT_NM = '점토'");
		$query = $this->db->get();

		return $query->result();
	}
}
