<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tablet_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function test()
	{
	}
	//성형 리스트
	public function get_sh_list($date = '', $param)
	{
		$where = '';

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
				AND (END_YN IS NULL OR END_YN='')
			
SQL;


		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}


	public function tablet_order_numlist($param)
	{
		$where = '';
		if (!empty($param['idx']) && $param['idx'] != "") {
			$where .= " AND A.IDX = {$param['idx']}";
		}
		if (!empty($param['date']) && $param['date'] != "") {
			$where .= " AND A.TRANS_DATE = '{$param['date']}'";
		}

		$sql = <<<SQL
			SELECT
				A.IDX AS TRANS_IDX,
				H.SERIES_NM,
				B.ITEM_NAME,
				C.COLOR,
				A.ORDER_QTY,
				A.REMARK,
				B.JH_QTY
			FROM
				t_inventory_orders AS A
				LEFT JOIN t_items AS B ON B.IDX = A.ITEMS_IDX
				LEFT JOIN t_series_d AS C ON C.IDX = A.SERIESD_IDX
				LEFT JOIN t_series_h AS H ON H.IDX = B.SERIES_IDX 
			WHERE
				A.END_YN is null
				{$where}
			order by
				SERIES_NM, ITEM_NAME, COLOR
SQL;


		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	//성형 입력 ajax
	public function get_ajax_sh_info($params)
	{
		$sql = <<<SQL
			SELECT
				tio.IDX,
				tsh.SERIES_NM,
				ti.ITEM_NAME,
				tio.ORDER_QTY,
				IFNULL(tio.PROD_QTY,0) AS PROD_QTY
			FROM
				t_items_orders as tio,
				t_items as ti,
				t_series_h as tsh
			WHERE
				GJ_GB = 'SH' 
				AND tio.IDX = {$params['IDX']}
				AND tio.ITEMS_IDX = ti.IDX
				AND ti.SERIES_IDX = tsh.IDX
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();

		return $query->row();
	}

	//성형 Update
	public function update_sh_order($params)
	{
		//선택한 작업지시의 정보 가져오기
		$info = $this->db->query("SELECT * from t_items_orders where IDX='{$params['IDX']}'")->row();

		//끝나있으면 조기 리턴
		if ($info->END_YN == 'Y')
			return 0;

		//DB에 저장되어있던 PROD_QTY와 입력한 QTY를 서로 합함
		$sum = $info->PROD_QTY + $params['F_QTY'];

		//합해서 지시수량보다 같거나 많으면 END_YN을 Y처리, 그외 빈칸(null)
		if ($info->ORDER_QTY <= $sum)
			$endyn = 'Y';
		else
			$endyn = '';


		//해당 지시정보 업데이트
		$sql = <<<SQL
 			UPDATE
 				t_items_orders
 			SET 
				PROD_QTY = '{$sum}',END_YN = '{$endyn}',UPDATE_DATE = NOW()
			WHERE
				IDX = '{$params['IDX']}'
SQL;
		$query = $this->db->query($sql);

		$username = $this->session->userdata('user_name');

		$sql = <<<SQL
			INSERT INTO t_items_trans ( ITEMS_IDX, TRANS_DATE, KIND, IN_QTY, GJ_GB, INSERT_ID, INSERT_DATE )
			VALUES('{$info->ITEMS_IDX}',NOW(),'IN','{$params['F_QTY']}','SH','{$username}',NOW())
SQL;

		$this->db->query($sql);
		return $this->db->affected_rows();
	}

	//=======================================================


	//정형 리스트
	public function get_jh_list($date = '', $param)
	{
		$where = '';

		if ($date != "") {
			$where .= " AND A.TRANS_DATE = '{$date}'";
		}
		$GJGB = $param['GJGB'];


		$where .= " AND GJ_GB = '{$GJGB}'";

		$sql = <<<SQL
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
			AND (END_YN IS NULL OR END_YN='')
		
SQL;


		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	//정형 입력 ajax
	public function get_ajax_jh_info($params)
	{
		$sql = <<<SQL
		SELECT
			tio.IDX,
			tsh.SERIES_NM,
			ti.ITEM_NAME,
			tio.ORDER_QTY,
			IFNULL(tio.PROD_QTY,0) AS PROD_QTY
		FROM
			t_items_orders as tio,
			t_items as ti,
			t_series_h as tsh
		WHERE
			GJ_GB = 'JH' 
			AND tio.IDX = {$params['IDX']}
			AND tio.ITEMS_IDX = ti.IDX
			AND ti.SERIES_IDX = tsh.IDX
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();

		return $query->row();
	}

	//정형 Update
	public function update_jh_order($params)
	{
		//선택한 작업지시의 정보 가져오기
		$info = $this->db->query("SELECT * from t_items_orders where IDX='{$params['IDX']}'")->row();

		//끝나있으면 조기 리턴
		if ($info->END_YN == 'Y')
			return 0;

		//DB에 저장되어있던 PROD_QTY와 입력한 QTY를 서로 합함
		$sum = $info->PROD_QTY + $params['F_QTY'];

		//합해서 지시수량보다 같거나 많으면 END_YN을 Y처리, 그외 빈칸(null)
		if ($info->ORDER_QTY <= $sum)
			$endyn = 'Y';
		else
			$endyn = '';


		//해당 지시정보 업데이트
		$sql = <<<SQL
		 UPDATE
			 t_items_orders
		 SET 
			PROD_QTY = '{$sum}',END_YN = '{$endyn}',UPDATE_DATE = NOW()
		WHERE
			IDX = '{$params['IDX']}'
SQL;
		$query = $this->db->query($sql);

		$username = $this->session->userdata('user_name');

		$sql = <<<SQL
		INSERT INTO t_items_trans ( ITEMS_IDX, TRANS_DATE, KIND, IN_QTY, GJ_GB, INSERT_ID, INSERT_DATE )
		VALUES('{$info->ITEMS_IDX}',NOW(),'IN','{$params['F_QTY']}','JH','{$username}',NOW())
SQL;

		$this->db->query($sql);
		return $this->db->affected_rows();
	}

	
	public function tablet_o3_popup($code, $param)
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
		$this->db->where("IDX", $params['IDX']);
		$query = $this->db->get("t_inventory_orders");
		$info = $query->row();

		$datetime = date("Y-m-d H:i:s", time());
		$date = date("Y-m-d", time());
		$username = $this->session->userdata('user_name');




		$this->db->set(array("END_YN" => "Y", "PROD_QTY" => "{$params["QTY"]}"));
		$this->db->where("IDX", $params['IDX']);
		$this->db->update("t_inventory_orders");

		$this->db->set(array("SIU_YN" => "Y", "STATUS" => "CU"));
		$this->db->where("IDX", $info->ACT_D_IDX);
		$this->db->update("t_act_d");

		$sql = <<<SQL
				INSERT T_INVENTORY_TRANS
				SET
				ITEMS_IDX    = '{$info->ITEMS_IDX}',
				SERIESD_IDX  = '{$info->SERIESD_IDX}',
				CU_DATE      = '{$date}',
				ACT_IDX      = '{$info->ACT_IDX}',
				ACT_D_IDX    = '{$info->ACT_D_IDX}',
				KIND         = 'IN',
				GJ_GB        = 'CU',
				IN_QTY       = '{$params["QTY"]}',
				INSERT_ID    = '{$username}',
				INSERT_DATE  = '{$datetime}'
SQL;
		$this->db->query($sql);

		$sql = <<<SQL
				INSERT INTO T_ITEMS_TRANS
				SET
				ITEMS_IDX    	= '{$info->ITEMS_IDX}',
				ACT_IDX      	= '{$info->ACT_IDX}',
				TRANS_DATE      = '{$date}',
				KIND			= "OT",
				GJ_GB			= "JH",
				OUT_QTY			= '{$params["QTY"]}',
				INSERT_ID		= '{$username}',
				INSERT_DATE		= '{$datetime}'
SQL;
		$this->db->query($sql);


		return $this->db->affected_rows();
	}

	public function timer_update($params)
	{
		$this->db->set(array("REMARK" => "{$params["time"]}"));
		$this->db->where("CODE","Second");
		$this->db->update("t_cocd_d");

		return $this->db->affected_rows();
	}
}
