<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bom_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}




	public function get_items_list($params,$start=0,$limit=20)
	{
		$where = "";
		if($params['BL_NO'] != ""){
			$where .= " AND BL_NO LIKE '%".$params['BL_NO']."%'";
		}
		if($params['ITEM_NAME'] != ""){
			$where .= " AND ITEM_NAME LIKE '%".$params['ITEM_NAME']."%'";
		}
		if($params['MSAB'] != ""){
			$where .= " AND MSAB like '%".$params['MSAB']."%'";
		}
		if($params['M_LINE'] != ""){
			$where .= " AND M_LINE = '".$params['M_LINE']."'";
		}
		if($params['GJ_GB'] != ""){
			$where .= " AND GJ_GB = '".$params['GJ_GB']."'";
		}
		if($params['USE_YN'] != ""){
			$where .= " AND USE_YN = '".$params['USE_YN']."'";
		}


		$sql=<<<SQL
			SELECT
				ti.BL_NO, ti.ITEM_NAME, ti.IDX, ti.M_LINE, ti.GJ_GB, ti.USE_YN,
				(SELECT tcd1.NAME FROM T_COCD_D as tcd1 WHERE tcd1.CODE = ti.1ST_CLASS) as CLASS1,
				(SELECT tcd2.NAME FROM T_COCD_D as tcd2 WHERE tcd2.CODE = ti.2ND_CLASS) as CLASS2,
				(SELECT tcd3.NAME FROM T_COCD_D as tcd3 WHERE tcd3.CODE = ti.MSAB) as MSAB,
				(SELECT tcd4.NAME FROM T_COCD_D as tcd4 WHERE tcd4.CODE = ti.STATE) as STATE,
				(SELECT COUNT(tb.IDX) FROM T_BOM as tb WHERE tb.H_IDX = ti.IDX) as C_COUNT
			FROM
				T_ITEMS as ti
			WHERE
				1
				{$where}
			ORDER BY INSERT_DATE DESC, IDX DESC 
			LIMIT {$start},{$limit}
SQL;
		
		$query = $this->db->query($sql);

		return $query->result();
		
	}



	public function get_testUpdate()
	{
		$this->db->insert("T_ACTPLN",array("BL_NO"=>"1AD-SA03811BA"));
	}


	public function get_items_cut($params)
	{
		$where = "";
		if($params['BL_NO'] != ""){
			$where .= " AND BL_NO LIKE '%".$params['BL_NO']."%'";
		}
		if($params['ITEM_NAME'] != ""){
			$where .= " AND ITEM_NAME LIKE '%".$params['ITEM_NAME']."%'";
		}
		if($params['MSAB'] != ""){
			$where .= " AND MSAB = '".$params['MSAB']."'";
		}
		if($params['M_LINE'] != ""){
			$where .= " AND M_LINE = '".$params['M_LINE']."'";
		}
		if($params['GJ_GB'] != ""){
			$where .= " AND GJ_GB = '".$params['GJ_GB']."'";
		}
		if($params['USE_YN'] != ""){
			$where .= " AND USE_YN = '".$params['USE_YN']."'";
		}
		$sql=<<<SQL
			SELECT
				COUNT(ti.IDX) as cnt
			FROM
				T_ITEMS as ti
			WHERE
				1
				{$where}
SQL;
		$query = $this->db->query($sql);

		return $query->row()->cnt;
		
	}

	


	public function get_trans_list($params,$start=0,$limit=20)
	{
		$subquery = "(SELECT TI.ITEM_NAME FROM T_ITEMS AS TI WHERE TI.BL_NO = TA.BL_NO AND TI.GJ_GB = TA.GJ_GB) AS ITEM_NAME";
		$kind = "(SELECT K.NAME FROM T_COCD_D as K WHERE K.H_IDX = 17 AND K.CODE = TCT.KIND) as KIND";
		$this->db->select("TC.COMPONENT, TC.COMPONENT_NM, TC.USE_YN, {$kind}, TCT.OUT_QTY, TCT.TRANS_DATE, TCT.IDX as TIDX, TA.BL_NO, {$subquery}");
		
		$this->db->from("T_COMPONENT_TRANS AS TCT");
		$this->db->join("T_COMPONENT AS TC","TC.IDX = TCT.C_IDX","left");
		$this->db->join("T_ACTPLN AS TA","TA.IDX = TCT.ACT_IDX","left");
		$this->db->limit($limit,$start);
		$query = $this->db->get();

		//echo $this->db->last_query();
		return $query->result();
	}


	public function get_trans_cut($params)
	{
		$this->db->select("COUNT(*) AS CUT");
		$this->db->from("T_COMPONENT_TRANS AS TCT");
		$this->db->join("T_COMPONENT AS TC","TC.IDX = TCT.C_IDX","left");
		$this->db->join("T_ACTPLN AS TA","TA.IDX = TCT.ACT_IDX","left");
		$query = $this->db->get();
		return $query->row()->CUT;
	}





	public function get_matform_list($param,$start=0,$limit=20)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->like("TCT.GJ_GB",$param['GJ_GB']);
		}
		if((!empty($param['TDATE1']) && $param['TDATE1'] != "") && (!empty($param['TDATE2']) && $param['TDATE2'] != "")){
			$this->db->where("TCT.TRANS_DATE BETWEEN '{$param['TDATE1']} 00:00:00' AND '{$param['TDATE2']} 23:59:59'");
		}

		$kind = "(SELECT K.NAME FROM T_COCD_D as K WHERE K.H_IDX = 17 AND K.CODE = TCT.KIND) as KIND";
		$this->db->select("TCT.*,TCT.IDX as TIDX, TC.COMPONENT, TC.COMPONENT_NM, {$kind}");
		$this->db->where("TCT.KIND","IN");
		$this->db->from("T_COMPONENT_TRANS AS TCT");
		$this->db->join("T_COMPONENT AS TC","TC.IDX = TCT.C_IDX","left");
		$this->db->order_by("TRANS_DATE","DESC");
		$this->db->limit($limit,$start);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}



	public function get_matform_cut($param)
	{
		
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->like("TCT.GJ_GB",$param['GJ_GB']);
		}
		if((!empty($param['TDATE1']) && $param['TDATE1'] != "") && (!empty($param['TDATE2']) && $param['TDATE2'] != "")){
			$this->db->where("TCT.TRANS_DATE BETWEEN '{$param['TDATE1']} 00:00:00' AND '{$param['TDATE2']} 23:59:59'");
		}
		$this->db->where("TCT.KIND","IN");

		$this->db->select("COUNT(*) AS CUT");
		$this->db->from("T_COMPONENT_TRANS AS TCT");
		$this->db->join("T_COMPONENT AS TC","TC.IDX = TCT.C_IDX","left");
		$query = $this->db->get();
		return $query->row()->CUT;
	}








	public function set_itemsInsert($params)
	{
		$this->db->insert("T_ITEMS",$params);
		return $this->db->insert_id();
	}

	public function set_itemsUpdate($params,$idx)
	{
		$this->db->update("T_ITEMS",$params,array("IDX"=>$idx));
		return $this->db->affected_rows();
	}


	public function get_items_info($idx)
	{
		$this->db->select("*,1ST_CLASS as CLASS1, 2ND_CLASS as CLASS2, 2ND_LINE as LINE2, 3ND_LINE as LINE3, 2ND_P_T as PT2, 3ND_P_T as PT3 ");
		$this->db->where(array("IDX"=>$idx));
		$data = $this->db->get("T_ITEMS");
		return $data->row();
	}



	/* marterials */
	public function get_material_list($params,$start=0,$limit=20)
	{
		if($params['COMPONENT'] != ""){
			$this->db->where("COMPONENT LIKE '%".$params['COMPONENT']."%'");
		}
		if($params['COMPONENT_NM'] != ""){
			$this->db->where("COMPONENT_NM LIKE '%".$params['COMPONENT_NM']."%'");
		}
		if($params['SPEC'] != ""){
			$this->db->where("SPEC",$params['SPEC']);
		}
		/*
		if($params['GJ_GB'] != ""){
			$this->db->where("GJ_GB",$params['GJ_GB']);
		}
		if($params['USE_YN'] != ""){
			$this->db->where("USE_YN",$params['USE_YN']);
		}*/

		$this->db->order_by("INSERT_DATE","DESC");
		$this->db->limit($limit,$start);
		$data = $this->db->get("T_COMPONENT");
		return $data->result();
		
	}

	
	/* marterials */
	public function get_material_joinlist($params,$start=0,$limit=20)
	{
		$this->db->select("TC.IDX, EX.COMPONENT as E_COMPONENT, TC.COMPONENT, EX.COMPONENT_NM, EX.UNIT, TC.STOCK, EX.STOCK as E_STOCK, EX.INTO_DATE, EX.REPL_DATE");
		$this->db->from("T_COMPONENT as TC");
		$this->db->join("T_COMPONENT_EX as EX","EX.COMPONENT = TC.COMPONENT AND EX.GJ_GB = TC.GJ_GB","right");
		$this->db->order_by("TC.STOCK","ASC");
		
		$this->db->limit($limit,$start);
		$data = $this->db->get();
		return $data->result();
		
	}



	public function get_component_mlist($param,$start=0,$limit=20)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->like("GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$this->db->like("COMPONENT",$param['COMPONENT']);
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$this->db->like("COMPONENT_NM",$param['COMPONENT_NM']);			
		}

		$this->db->limit($limit,$start);
		$query = $this->db->get("T_COMPONENT");
		//echo $this->db->last_query();
		return $query->result();
	}


	public function get_component_mlist2($param,$start=0,$limit=20)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->like("GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$this->db->like("COMPONENT",$param['COMPONENT']);
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$this->db->like("COMPONENT_NM",$param['COMPONENT_NM']);			
		}

		$this->db->select("*,IF((STOCK-SAVE_QTY) < 0,1,0) as QUICK");
		
		$this->db->limit($limit,$start);
		$this->db->order_by("QUICK","DESC");
		$query = $this->db->get("T_COMPONENT");
		//echo $this->db->last_query();
		return $query->result();
	}


	public function get_component_mcut($param)
	{
		if(!empty($param['GJ_GB']) && $param['GJ_GB'] != ""){
			$this->db->like("GJ_GB",$param['GJ_GB']);
		}

		if(!empty($param['COMPONENT']) && $param['COMPONENT'] != ""){
			$this->db->like("COMPONENT",$param['COMPONENT']);
		}

		if(!empty($param['COMPONENT_NM']) && $param['COMPONENT_NM'] != ""){
			$this->db->like("COMPONENT_NM",$param['COMPONENT_NM']);			
		}
		$this->db->select("COUNT(*) as CUT");
		$query = $this->db->get("T_COMPONENT");
		return $query->row()->CUT;
	}


	public function ajax_saveqty_update($param)
	{
		$set = array(
			"SAVE_QTY"  => $param['SAVE_QTY'],
			"SAVE_DATE" => $param['SAVE_DATE']
		);
		$this->db->update("T_COMPONENT",$set,array("IDX"=>$param['IDX']));

		return $this->db->affected_rows();

	}


	public function ajax_component_setting($param)
	{
		$this->db->select("EX.*, TC.IDX as T_IDX, TC.COMPONENT as TC_COMP");
		$this->db->from("T_COMPONENT as TC");
		$this->db->join("T_COMPONENT_EX as EX","EX.COMPONENT = TC.COMPONENT AND EX.GJ_GB = TC.GJ_GB","right");
		
		if($param['mode'] == "one"){
			$this->db->where("EX.COMPONENT",$param['component']);
		}
		
		$query = $this->db->get();
		$data = $query->result();

		$tCount = $query->num_rows();
		$successCount = 0;

		$datetime = date("Y-m-d H:i:s",time());
		$username = $this->session->userdata('user_name');


		foreach($data as $row){
			if($row->TC_COMP == NULL){
				
				
				$this->db->set("GJ_GB",$row->GJ_GB);
				$this->db->set("COMPONENT",$row->COMPONENT);
				$this->db->set("COMPONENT_NM",$row->COMPONENT_NM);
				$this->db->set("STOCK",$row->STOCK);
				$this->db->set("UNIT",$row->UNIT);
				$this->db->set("INTO_DATE",$row->INTO_DATE);
				$this->db->set("REPL_DATE",$row->REPL_DATE);
				$this->db->set("INSERT_DATE",$datetime);
				$this->db->set("INSERT_ID",$username);
				$query = $this->db->INSERT("T_COMPONENT");
				
			}else{
				
				$this->db->set("STOCK",$row->STOCK);
				$this->db->set("INTO_DATE",$row->INTO_DATE);
				$this->db->set("REPL_DATE",$row->REPL_DATE);
				$this->db->set("UPDATE_DATE",$datetime);
				$this->db->set("UPDATE_ID",$username);
				$this->db->where("IDX",$row->T_IDX);
				$query = $this->db->update("T_COMPONENT");
				
			}
			
			if($this->db->affected_rows()){
				
				$this->db->where("COMPONENT",$row->COMPONENT);
				$this->db->delete("T_COMPONENT_EX");

				$successCount++;
			}
		}

		$data['tcount'] = $tCount;
		$data['scount'] = $successCount;

		return $data;

	}



	
	public function get_material_excut($params)
	{
		$this->db->select("COUNT(*) as cut");
		$query = $this->db->get("T_COMPONENT_EX");
		$data['totnum'] = $query->row()->cut;
		
		return $data;
		
	}


	public function get_material_cut($params)
	{
		if($params['COMPONENT'] != ""){
			$this->db->where("COMPONENT LIKE '%".$params['COMPONENT']."%'");
		}
		if($params['COMPONENT_NM'] != ""){
			$this->db->where("COMPONENT_NM LIKE '%".$params['COMPONENT_NM']."%'");
		}
		if($params['SPEC'] != ""){
			$this->db->where("SPEC",$params['SPEC']);
		}/*
		if($params['GJ_GB'] != ""){
			$this->db->where("GJ_GB",$params['GJ_GB']);
		}
		if($params['USE_YN'] != ""){
			$this->db->where("USE_YN",$params['USE_YN']);
		}*/
		//if(!empty($params['set']) && $params['set'] != "") $where = $this->db->where($params['seq']." LIKE '%".$params['set']."%'");
		$this->db->select("COUNT(IDX) as cut");
		$data = $this->db->get("T_COMPONENT");
		return $data->row()->cut;
		
	}





	public function get_bom_material($data,$start=0,$limit=20)
	{
		$where = "";
		if(!empty($data['idx'])){

			if($data['set'] != ""){
				$where = " AND {$data['seq']} like '%{$data['set']}%'";
			}

			$sql=<<<SQL
				SELECT
					tc.*,
					(SELECT COUNT(tb.IDX) FROM T_BOM as tb WHERE tb.H_IDX = '{$data['idx']}' AND tb.C_IDX = tc.IDX) as CHKBOM
				FROM
					T_COMPONENT as tc
				WHERE
					1
					{$where}
					-- tc.WORK_ALLO > 0
				ORDER BY
					CHKBOM DESC, tc.INSERT_DATE DESC, tc.IDX DESC
				LIMIT
					{$start},{$limit}
SQL;
			$query = $this->db->query($sql);

		}

		return $query->result();
		
	}
	
	public function get_bom_material_cut($data)
	{
		$where = "";
		if(!empty($data['idx'])){

			if($data['set'] != ""){
				$where = " AND {$data['seq']} like '%{$data['set']}%'";
			}

			$sql=<<<SQL
				SELECT
					COUNT(tc.IDX) AS cut
				FROM
					T_COMPONENT as tc
				WHERE
					1
					{$where}
SQL;
			$query = $this->db->query($sql);

		}

		return $query->row()->cut;
		
	}



	public function get_material_info($idx)
	{
		$this->db->where(array("IDX"=>$idx));
		$data = $this->db->get("T_COMPONENT");
		return $data->row();
	}

	public function set_materialInsert($params)
	{
		$this->db->insert("T_COMPONENT",$params);
		return $this->db->insert_id();
	}

	public function set_materialUpdate($params,$idx)
	{
		$this->db->update("T_COMPONENT",$params,array("IDX"=>$idx));
		return $this->db->affected_rows();
	}

	public function get_check_bom($hidx,$cidx)
	{
		$query = $this->db->select("count(IDX) as num")
						->where(array("H_IDX"=>$hidx, "C_IDX"=>$cidx))
						->get("T_BOM");
		return $query->row();
	}



	public function get_bom_list($idx)
	{
		$query = $this->db->select("tc.COMPONENT, tc.COMPONENT_NM, tc.SPEC, tc.UNIT, tc.PRICE, tb.UPDATE_DATE, tb.UPDATE_ID, tb.IDX as BIDX, tb.WORK_ALLO, tb.PT, tb.POINT, tb.REEL_CNT")
						->from("T_BOM as tb")
						->join("T_COMPONENT as tc","tc.IDX = tb.C_IDX","left")
						->where(array("tb.H_IDX"=>$idx))
						->get();
		
		return $query->result();

	}


	public function set_bom_insertform($params)
	{
		$query = $this->db->insert_batch("T_BOM",$params);
		return $this->db->insert_id();
	
	}
	
	/* bom 자재선택 */
	public function set_bom_formUpdate($param)
	{
		$query = $this->db->insert("T_BOM",$param);
		return $this->db->insert_id();
	}
	

	/* bom 선택된자재삭제 */
	public function set_bom_formDelete($param)
	{
		$this->db->where(array("H_IDX"=>$param['H_IDX'],"C_IDX"=>$param['C_IDX']));
		$this->db->delete("T_BOM");
		return $this->db->affected_rows();
	}


	public function set_bomlistUpdate($params,$idx)
	{
		$this->db->update("T_BOM",$params,array("IDX"=>$idx));
		return $this->db->affected_rows();
	}

	
	/*	bom에 자재가 등록되어있는지 확인
		IDX : T_COMPONENT IDX
	*/
	public function check_bom_info($param,$type)
	{
		$query = $this->db->select("COUNT(IDX) as num")
						->where(array($type=>$param['IDX']))
						->get("T_BOM");
		return $query->row()->num;
	}


	public function delete_material($param)
	{
		$this->db->delete("T_COMPONENT",array("IDX"=>$param['IDX']));
		return $this->db->affected_rows();
	}

	public function delete_item($param)
	{
		$this->db->delete("T_ITEMS",array("IDX"=>$param['IDX']));
		return $this->db->affected_rows();
	}


	public function get_matform_temp_list()
	{
		$this->db->where("GUBUN","입고");
		$this->db->order_by("COMPONENTNO","DESC");
		$this->db->order_by("IPGO_DATE","DESC");
		$query = $this->db->get("T_TEMP_COM");
		//echo $this->db->last_query();
		$data['list'] = $query->result();
		$data['num']  = $query->num_rows();
		return $data;
	}



	public function ajax_matform_component_update($code,$date)
	{
		$sql=<<<SQL
			UPDATE T_COMPONENT as A
			SET
				STOCK = STOCK+(SELECT SUM(B.QTY) FROM T_TEMP_COM as B WHERE B.COMPONENTNO = A.COMPONENT),
				INTO_DATE = '{$date}'
			WHERE
				A.COMPONENT = '{$code}'
SQL;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}


	public function ajax_matform_temp_insert($params)
	{
		$sql=<<<SQL
			INSERT INTO T_COMPONENT_TRANS
			SET
				C_IDX = (SELECT A.IDX FROM T_COMPONENT as A WHERE A.COMPONENT = '{$params['C_IDX']}'),
				TRANS_DATE = '{$params['TRANS_DATE']}',
				KIND = 'IN',
				IN_QTY = '{$params['IN_QTY']}',
				GJ_GB = '{$params['GJ_GB']}',
				INSERT_ID = '{$params['INSERT_ID']}',
				INSERT_DATE = '{$params['INSERT_DATE']}'
SQL;
		$this->db->query($sql);

	}

	



}