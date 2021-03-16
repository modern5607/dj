<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kpi_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}


	public function equip_chart($params,$start=0,$limit=20) 
	{
		$this->db->where("SUBSTR(INSERT_DATE,1,6) BETWEEN '{$params['SDATE']}{$params['SWEEK']} ' AND '{$params['SDATE']}{$params['EWEEK']}'");
		if(!empty($params['CHART'])){
			$this->db->order_by("INSERT_DATE","ASC");
		}else{
			$this->db->order_by("INSERT_DATE","DESC");
		}
		
		$toDate = date("Y-m-d",time());
		$this->db->SELECT("*, SUBSTR(INSERT_DATE,1,4) AS YE, SUBSTR(INSERT_DATE,5,2) AS MO , SUBSTR(INSERT_DATE,8,2) AS WE");
		$this->db->where("KPI_CODE",'MF');
		$this->db->limit($limit,$start);

		$query = $this->db->get('t_kpi');
		//  echo  $this->db->last_query();
		return $query->result();
	}
	

	public function equip_cut($params) 
	{
		$this->db->where("SUBSTR(INSERT_DATE,1,6) BETWEEN '{$params['SDATE']}{$params['SWEEK']} ' AND '{$params['SDATE']}{$params['EWEEK']}'");

		$this->db->select("COUNT(*) AS CUT");
		$this->db->from("t_kpi");
		$this->db->where("KPI_CODE",'MF');

		$query = $this->db->get();
		
		return $query->row()->CUT;
	}


	public function fair_chart($params,$start=0,$limit=20) 
	{
		$this->db->where("SUBSTR(INSERT_DATE,1,6) BETWEEN '{$params['SDATE']}{$params['SWEEK']} ' AND '{$params['SDATE']}{$params['EWEEK']}'");

		if(!empty($params['CHART'])){
			$this->db->order_by("INSERT_DATE","ASC");
		}else{
			$this->db->order_by("INSERT_DATE","DESC");
		}

		$toDate = date("Y-m-d",time());
		//$toDate = "2020-09-11";
		$this->db->SELECT("*, SUBSTR(INSERT_DATE,1,4) AS YE, SUBSTR(INSERT_DATE,5,2) AS MO , SUBSTR(INSERT_DATE,8,2) AS WE");
		$this->db->where("KPI_CODE",'PP');
		$this->db->limit($limit,$start);

		$query = $this->db->get('t_kpi');
		// echo  $this->db->last_query();
		return $query->result();
	}


	public function fair_cut($params) 
	{
		$this->db->where("SUBSTR(INSERT_DATE,1,6) BETWEEN '{$params['SDATE']}{$params['SWEEK']} ' AND '{$params['SDATE']}{$params['EWEEK']}'");

		$this->db->select("COUNT(*) AS CUT");
		$this->db->from("t_kpi");
		$this->db->where("KPI_CODE",'PP');

		$query = $this->db->get();
		return $query->row()->CUT;
	}

	public function short_chart($params,$start=0,$limit=20) 
	{
		$this->db->where("SUBSTR(INSERT_DATE,1,6) BETWEEN '{$params['SDATE']}{$params['SWEEK']} ' AND '{$params['SDATE']}{$params['EWEEK']}'");

		if(!empty($params['CHART'])){
			$this->db->order_by("INSERT_DATE","ASC");
		}else{
			$this->db->order_by("INSERT_DATE","DESC");
		}

		$toDate = date("Y-m-d",time());
		//$toDate = "2020-09-11";
		$this->db->SELECT("*, SUBSTR(INSERT_DATE,1,4) AS YE, SUBSTR(INSERT_DATE,5,2) AS MO , SUBSTR(INSERT_DATE,8,2) AS WE");
		$this->db->where("KPI_CODE",'PP');
		$this->db->limit($limit,$start);

		$query = $this->db->get('t_kpi');
		// echo  $this->db->last_query();
		return $query->result();
	}


	public function short_cut($params) 
	{
		$this->db->where("SUBSTR(INSERT_DATE,1,6) BETWEEN '{$params['SDATE']}{$params['SWEEK']} ' AND '{$params['SDATE']}{$params['EWEEK']}'");

		$this->db->select("COUNT(*) AS CUT");
		$this->db->from("t_kpi");
		$this->db->where("KPI_CODE",'GJ');

		$query = $this->db->get();
		return $query->row()->CUT;
	}


	public function equip_list($params,$start=0,$limit=20) 
	{
		$where = '';
		if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
			$where .= " AND WELD_PLN BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'";
		}

		$sql=<<<SQL
	
					SELECT
						PJT_NO,
						POR_NO,
						POR_SEQ,
						PO_QTY,
						WEIGHT,
						MCCSDESC,
						WELD_PLN,
						WELD_ACT,
						DATEDIFF(WELD_ACT, WELD_PLN) as WELD_WOR,
						CASE
							WHEN (OUTB_GBN = "R") THEN "정규"
							WHEN (OUTB_GBN = "B") THEN "긴급"
						END as OUTB_GBN,
						DESC_GBN 
					FROM
					T_ACTPLN
					WHERE
					1
					{$where}
					order by
					WELD_PLN,PJT_NO, POR_NO
					limit {$start},{$limit}

SQL;
		$query = $this->db->query($sql);
		// echo  $this->db->last_query();		
		return $query->result();
	}

	public function equip_list_cnt($params)
	{
		if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
			$this->db->where("WELD_PLN BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		
		$this->db->select("COUNT(IDX) as cut");
		$data = $this->db->get('T_ACTPLN');
		return $data->row()->cut;
	}
	
	
	/* 납기준수율 리스트 */
	public function fair_list($params,$start=0,$limit=20) 
	{
		$where = '';
		if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
			$where .= " AND PLAN_DA BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'";
		}

		$sql=<<<SQL
			SELECT
				PJT_NO,
				POR_NO,
				POR_SEQ,
				PO_QTY,
				WEIGHT,
				MCCSDESC,
				PLAN_DA,
				TRNDDA,
				DATEDIFF(PLAN_DA, TRNDDA) as WELD_WOR,
				CASE
					WHEN (OUTB_GBN = "R") THEN "정규"
					WHEN (OUTB_GBN = "B") THEN "긴급"
				END as OUTB_GBN,
				DESC_GBN 
			FROM
			T_ACTPLN
			WHERE
			1
			{$where}
			order by
			PLAN_DA,PJT_NO, POR_NO
			limit {$start},{$limit}

SQL;
		$query = $this->db->query($sql);
//		echo  $this->db->last_query();		
		return $query->result();
	}


	public function fair_list_cnt($params)
	{
		if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
			$this->db->where("PLAN_DA BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		
		$this->db->select("COUNT(IDX) as cut");
		$data = $this->db->get('T_ACTPLN');
		return $data->row()->cut;
	}


		/* 납기준수율 리스트 */
		public function short_list($params,$start=0,$limit=20) 
		{
			$where = '';
			if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
				$where .= " AND PLAN_DA BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'";
			}
	
			$sql=<<<SQL
				SELECT
					PJT_NO,
					POR_NO,
					POR_SEQ,
					PO_QTY,
					WEIGHT,
					MCCSDESC,
					PLAN_DA,
					TRNDDA,
					DATEDIFF(PLAN_DA, TRNDDA) as WELD_WOR,
					CASE
						WHEN (OUTB_GBN = "R") THEN "정규"
						WHEN (OUTB_GBN = "B") THEN "긴급"
					END as OUTB_GBN,
					DESC_GBN 
				FROM
				T_ACTPLN
				WHERE
				1
				{$where}
				order by
				PLAN_DA,PJT_NO, POR_NO
				limit {$start},{$limit}
	
	SQL;
			$query = $this->db->query($sql);
	//		echo  $this->db->last_query();		
			return $query->result();
		}
	
	
		public function short_list_cnt($params)
		{
			if((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")){
				$this->db->where("PLAN_DA BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
			}
			
			$this->db->select("COUNT(IDX) as cut");
			$data = $this->db->get('T_ACTPLN');
			return $data->row()->cut;
		}


	public function get_sold_All($params,$start,$limit)
	{
		$sql= <<<SQL
			SELECT
			ID,FLUX_TIME,FLUX_WEIGHT,SOLDER_TIME,PREHEAT_TIME,SOLDER_TEMP,TACT_TIME, DATE_FORMAT(PRODUCT_TIME,"%Y-%m-%d") AS PRODUCT_TIME
			FROM
				`T_SOLD_HISTORY`
			WHERE
				INSERT_DATE BETWEEN '{$params['INSERT_DATE']} 00:00:00' AND '{$params['INSERT_DATE']} 23:59:59'
			ORDER BY
				ID DESC
			LIMIT
			{$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		
		return $query->result();
	}


	public function get_sold_All_cut($params)
	{
		$sql= <<<SQL
			SELECT
			ID,FLUX_TIME,FLUX_WEIGHT,SOLDER_TIME,PREHEAT_TIME,SOLDER_TEMP,TACT_TIME, DATE_FORMAT(PRODUCT_TIME,"%Y-%m-%d") AS PRODUCT_TIME
			FROM
				`T_SOLD_HISTORY`
			WHERE
				INSERT_DATE BETWEEN '{$params['INSERT_DATE']} 00:00:00' AND '{$params['INSERT_DATE']} 23:59:59'
			ORDER BY
				ID DESC
SQL;
		$query = $this->db->query($sql);
		return $query->num_rows();
	}


	public function equip_mean($params) 
	{
		// if($params['SDATE'] != "" && $params['EDATE'] != ""){
		// 	$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		// }
		
		$this->db->select("'전체 평균' AS TEXT, SUM(AC_KPI) / COUNT(*) AS AV_CNT");
		$this->db->where('KPI_CODE ="MF"');
		$this->db->from("t_kpi");

		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}


	public function fair_mean($params) 
	{
		// if(!empty($params['CHART'])){
		// 	if($params['SDATE'] != "" && $params['EDATE'] != ""){
		// 		$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		// 	}
		// }else{
		// 	if($params['STA1'] != "" && $params['STA2'] != ""){
		// 		$this->db->where("INSERT_DATE BETWEEN '{$params['STA1']}' AND '{$params['STA2']}'");
		// 	}
		// }
		
		$this->db->select("'전체 평균' AS TEXT, SUM(AC_KPI) / COUNT(*) AS AV_CNT");
		$this->db->where('KPI_CODE ="PP"');
		$this->db->from("t_kpi");

		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}

	public function kpi_mean($params,$code) 
	{
		// if($params['SDATE'] != "" && $params['EDATE'] != ""){
		// 	$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		// }

		$this->db->select("'전체 평균' AS TEXT, SUM(AC_KPI) / COUNT(*) AS AV_CNT");
		$this->db->where('KPI_CODE',$code);
		$this->db->from("t_kpi");

		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}
}