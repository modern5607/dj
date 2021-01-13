<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Biz_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}



	/* 업체 리스트 */
	public function get_bizReg_list()
	{
		$res = $this->db->get("T_BIZ_REG");
		return $res->result();

	}

	

	/* 업체 등록 */
	public function bizReg_update($param)
	{

		if($param['mod'] == 1){

			$dateTime = date("Y-m-d H:i:s",time());
			
			$data = array(
				'CUST_NM'        => $param['CUST_NM'],
				'ADDRESS'        => $param['ADDRESS'],
				'TEL'                 => $param['TEL'],
				'CUST_NAME'    => $param['CUST_NAME'],
				'ITEM'               => $param['ITEM'],
				'REMARK'          => $param['REMARK'],
				'UPDATE_ID'      => $param['INSERT_ID'],
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->update("T_BIZ_REG",$data,array("IDX"=>$param['IDX']));
			return $param['IDX'];
		
		}else{

			$dateTime = date("Y-m-d H:i:s",time());

			$data = array(
				'CUST_NM'        => $param['CUST_NM'],
				'ADDRESS'        => $param['ADDRESS'],
				'TEL'                 => $param['TEL'],
				'CUST_NAME'    => $param['CUST_NAME'],
				'ITEM'               => $param['ITEM'],
				'REMARK'          => $param['REMARK'],
				'INSERT_ID'       => $param['INSERT_ID'],
				'INSERT_DATE'  => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);

			

			$this->db->insert("T_BIZ_REG",$data);

			return $this->db->insert_id();

		}

		

	}




	/* 업체정보 상세정보 */
	public function get_bizReg_info($idx)
	{
		$res = $this->db->where("IDX",$idx)
						->get("T_BIZ_REG");
		return $res->row();
	}


	public function delete_bizReg($idx)
	{
		$res = $this->db->delete("T_BIZ_REG",array('IDX'=>$idx));
		return $this->db->affected_rows();
	}


	/*
	* 특정 공통코드의 디테일리스트를 호출
	*/
	public function get_selectInfo()
	{
		$this->db->select("CUST_NM");
		$this->db->group_by("CUST_NM");
		$query = $this->db->get("T_BIZ_REG");
		return $query->result();		
	}

	


}