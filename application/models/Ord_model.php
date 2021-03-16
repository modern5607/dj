<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ord_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}


	public function items_order_list($param,$start=0,$limit=20)
	{
		$this->db->query('SET SESSION sql_mode = ""');

		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$this->db->where(" TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}

		if(!empty($param['BK'])){
			$GJGB = "JHBK";
		}else{
			$GJGB = $param['GJGB'];
		}

	
		$this->db->select("IDX, TRANS_DATE");
		$this->db->where("GJ_GB",$GJGB);
		$this->db->group_by("TRANS_DATE");
		$this->db->order_by("TRANS_DATE DESC");
		$this->db->limit($limit,$start);
		$query = $this->db->get("t_items_orders");

		// echo $this->db->last_query();
		
		return $query->result();
	}


	public function items_order_cnt($param)
	{
		$where='';
		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$where .="AND TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}

		if(!empty($param['BK'])){
			$GJGB = "JHBK";
		}else{
			$GJGB = $param['GJGB'];
		}

		$where .="AND GJ_GB = '{$GJGB}'";

		$sql=<<<SQL
			SELECT
				COUNT(*) AS CUT
			FROM
			(
				SELECT
					TRANS_DATE
				FROM
					t_items_orders
				WHERE
					1
					{$where}
				GROUP BY
					TRANS_DATE
			) AS AA
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->row()->CUT;
	}


	public function item_order_numlist($date='',$param)
	{
		$where='';
		if(!empty($param['V1']) && $param['V1'] != ""){
			$where .= " AND B.SERIES_IDX={$param['V1']}";
		}
		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$where .= " AND B.ITEM_NAME LIKE '%{$param['COMPONENT_NM']}%'";
		}
		if($date != ""){
			$where .= " AND A.TRANS_DATE = '{$date}'";
		}
		
		if(!empty($param['BK'])){
			$GJGB = "JHBK";
		}else{
			$GJGB = $param['GJGB'];
		}

		$where .=" AND GJ_GB = '{$GJGB}'";

		$sql=<<<SQL
			SELECT
				A.IDX AS TRANS_IDX,
				H.SERIES_NM,
				B.ITEM_NAME,
				A.ORDER_QTY,
				A.REMARK,
				B.SH_QTY,
				ifnull(A.PROD_QTY,0 ) as PROD_QTY,
				END_YN
			FROM
				t_items_orders AS A
				JOIN t_items AS B ON B.IDX = A.ITEMS_IDX
				LEFT JOIN t_series_h AS H ON H.IDX = B.SERIES_IDX 
			WHERE
				1
				{$where}
			UNION
			SELECT
				COUNT(B.ITEM_NAME),
				'합계' AS TEXT,
				'',
				SUM(A.ORDER_QTY),
				'',
				'',
				'',
				''
			FROM
				t_items_orders AS A
				JOIN t_items AS B ON B.IDX = A.ITEMS_IDX
				LEFT JOIN t_series_h AS H ON H.IDX = B.SERIES_IDX
			WHERE
				1
				{$where}
			order by
				SERIES_NM,ITEM_NAME
SQL;


		$query = $this->db->query($sql);
		//  ECHO $this->db->last_query();
		return $query->result();
	}


	public function ajax_item_order_insert($params)
	{
		$datas = array();

		$datetime = date("Y-m-d H:i:s",time());
		$username = $this->session->userdata('user_name');


		foreach($params['QTY'] as $k => $qty){
			$qty = $qty*1;
			
			$datax = array(
				"ITEMS_IDX"    => $params['ITEM_IDX'][$k],
				"TRANS_DATE"   => $params['transdate'],
				"KIND"         => "IN",
				"GJ_GB"        => $params['GJGB'],
				"ORDER_QTY"    => $qty,
				"REMARK"       => $params['REMARK'][$k],
				"INSERT_ID"    => $username,
				"INSERT_DATE"  => $datetime
			);
			array_push($datas,$datax);
		}

		$this->db->insert_batch("T_ITEMS_ORDERS",$datas);
		return $this->db->affected_rows();
	}


	public function ajax_del_items_order($idx)
	{
		$this->db->where("IDX",$idx);
		$this->db->delete("t_items_orders");
		
		$data = 0;
		if ($this->db->trans_status() !== FALSE){
			$data = 1;
		}
		
		return $data;

	}


	public function ajax_ord_o2_items_order_insert($params)
	{
		$datas = array();

		$datetime = date("Y-m-d H:i:s",time());
		$username = $this->session->userdata('user_name');


		foreach($params['QTY'] as $k => $qty){
			$qty = $qty*1;
			
			$datax = array(
				"ITEMS_IDX"    => $params['ITEM_IDX'][$k],
				"TRANS_DATE"   => $params['transdate'],
				"KIND"         => "IN",
				"GJ_GB"        => "JH",
				"ORDER_QTY"    => $qty,
				"REMARK"       => $params['REMARK'][$k],
				"INSERT_ID"    => $username,
				"INSERT_DATE"  => $datetime
			);
			array_push($datas,$datax);
		}

		$this->db->insert_batch("T_ITEMS_ORDERS",$datas);
		return $this->db->affected_rows();
	}


	public function inventory_order_list($param,$start=0,$limit=20)
	{
		$this->db->query('SET SESSION sql_mode = ""');

		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$this->db->where(" TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}
	
		$this->db->select("IDX, TRANS_DATE");
		$this->db->where("GJ_GB",$param['GJGB']);
		$this->db->group_by("TRANS_DATE");
		$this->db->order_by("TRANS_DATE DESC");
		$this->db->limit($limit,$start);
		$query = $this->db->get("t_inventory_orders");

		// echo $this->db->last_query();
		
		return $query->result();
	}


	public function inventory_order_cnt($param)
	{
		$where='';
		if((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")){
			$where .="AND TRANS_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}


		$sql=<<<SQL
			SELECT
				COUNT(*) AS CUT
			FROM
			(
				SELECT
					TRANS_DATE
				FROM
					t_inventory_orders
				WHERE
					GJ_GB = '{$param['GJGB']}'
					{$where}
				GROUP BY
					TRANS_DATE
			) AS AA
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->row()->CUT;
	}


	public function inventory_order_numlist($date='',$param)
	{
		$where='';
		if(!empty($param['V1']) && $param['V1'] != ""){
			$where .= " AND B.SERIES_IDX={$param['V1']}";
		}
		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$where .= " AND B.ITEM_NAME LIKE '%{$param['COMPONENT_NM']}%'";
		}
		if($date != ""){
			$where .= " AND A.TRANS_DATE = '{$date}'";
		}
		

		$where .=" AND GJ_GB = '{$param['GJGB']}'";

		$sql=<<<SQL
			SELECT
				A.IDX AS TRANS_IDX,
				H.SERIES_NM,
				B.ITEM_NAME,
				C.COLOR,
				A.ORDER_QTY,
				A.REMARK,
				B.JH_QTY,
				D.QTY AS D_QTY,
				D.STATUS
			FROM
				t_inventory_orders AS A
				LEFT JOIN t_items AS B ON B.IDX = A.ITEMS_IDX
				LEFT JOIN t_series_d AS C ON C.IDX = A.SERIESD_IDX
				LEFT JOIN t_series_h AS H ON H.IDX = B.SERIES_IDX 
				LEFT JOIN t_act_d AS D ON D.IDX = A.ACT_D_IDX 
			WHERE
				1
				{$where}
			UNION
			SELECT
				COUNT(B.ITEM_NAME),
				'합계' AS TEXT,
				'',
				'',
				SUM(A.ORDER_QTY),
				'',
				'',
				'',
				''
			FROM
				t_inventory_orders AS A
				LEFT JOIN t_items AS B ON B.IDX = A.ITEMS_IDX
				LEFT JOIN t_series_d AS C ON C.IDX = A.SERIESD_IDX
				LEFT JOIN t_series_h AS H ON H.IDX = B.SERIES_IDX 
			WHERE
				1
				{$where}
			order by
				SERIES_NM, ITEM_NAME, COLOR
SQL;


		$query = $this->db->query($sql);
		//  ECHO $this->db->last_query();
		return $query->result();
	}


	public function ajax_inven_pop($params)
	{
		if($params['type'] == "CU"){
			$this->db->select("TAD.ITEM_NM, TSD.COLOR, tad.ITEMS_IDX, tad.SERIESD_IDX, IFNULL(TI.JH_QTY,0) AS QTY, TAD.IDX AS ACT_D_IDX, TAD.H_IDX AS ACT_IDX, TAD.QTY AS D_QTY");
			$this->db->where("(TAD.STATUS is null)",null,false); // 시유받을거
			$this->db->where("( SELECT count( ACT_D_IDX ) FROM t_inventory_orders WHERE ACT_D_IDX = TAD.IDX ) != 1",null,false); // 시유받을거
		}else{
			
			$this->db->select("TAD.ITEM_NM, SUM(TIT.IN_QTY) AS QTY, TSD.COLOR, tad.ITEMS_IDX, tad.SERIESD_IDX");
			$this->db->join("T_INVENTORY_TRANS AS TIT","TIT.ACT_D_IDX = TAD.IDX");
			$this->db->group_by("TAD.ITEM_NM, TSD.COLOR, tad.ITEMS_IDX, tad.SERIESD_IDX");
			$this->db->where("TIT.GJ_GB","CU");
		}


		$this->db->where("TSH.IDX",$params['s1']);
		
		if(!empty($params['s2']) && $params['s2'] != ""){
			$this->db->like("TAD.ITEM_NM",$params['s2']);
		}
		
		$this->db->from("T_ACT_D AS TAD");
		$this->db->join("T_ITEMS AS TI","TI.IDX = TAD.ITEMS_IDX");
		$this->db->join("T_SERIES_H AS TSH","TSH.IDX = TI.SERIES_IDX");
		$this->db->join("T_SERIES_D AS TSD","TSD.IDX = TAD.SERIESD_IDX");
		$this->db->order_by("TAD.ITEM_NM");
		$this->db->order_by("TSD.COLOR");
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}


	public function ajax_inven_order_insert($params)
	{
		$datas = array();

		$datetime = date("Y-m-d H:i:s",time());
		$username = $this->session->userdata('user_name');


		foreach($params['QTY'] as $k => $qty){
			$qty = $qty*1;
			
			$datax = array(
				"ITEMS_IDX"    	=> $params['ITEM_IDX'][$k],
				"SERIESD_IDX"  	=> $params['SERIESD_IDX'][$k],
				"TRANS_DATE"   	=> $params['transdate'],
				"KIND"         	=> "IN",
				"GJ_GB"        	=> $params['GJGB'],
				"ORDER_QTY"    	=> $qty,
				"REMARK"       	=> $params['REMARK'][$k],
				"INSERT_ID"    	=> $username,
				"INSERT_DATE"  	=> $datetime,
				"ACT_IDX"  		=> $params['ACT_IDX'][$k],
				"ACT_D_IDX"  	=> $params['ACT_D_IDX'][$k]
			);
			array_push($datas,$datax);
		}

		$this->db->insert_batch("T_INVENTORY_ORDERS",$datas);
		return $this->db->affected_rows();
	}


	public function ajax_del_inven_order($idx)
	{
		$this->db->where("IDX",$idx);
		$this->db->delete("t_inventory_orders");
		
		$data = 0;
		if ($this->db->trans_status() !== FALSE){
			$data = 1;
		}
		
		return $data;

	}


	public function update_item_order($param)
	{
		$sql=<<<SQL
			SELECT
				IDX,ORDER_QTY,IFNULL(PROD_QTY,0) AS PROD_QTY,END_YN
			FROM
				t_items_orders 
			WHERE
				IDX = {$param['idx']}
SQL;
		$query = $this->db->query($sql);
		$info = $query->row();

		$this->db->trans_start();

		$this->db->set("ORDER_QTY", $param['qty']);
		if($info->PROD_QTY>=$param['qty'])
			$this->db->set("END_YN", 'Y');
		$this->db->where("IDX", $param['idx']);
		$this->db->update("t_items_orders");

		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}
		return $data;
	}


	public function update_inventory_order($param)
	{
		$this->db->trans_start();

		$this->db->set("ORDER_QTY", $param['qty']);
		$this->db->where("IDX", $param['idx']);
		$this->db->update("t_inventory_orders");

		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}
		return $data;
	}

}