<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}

	
	/* 공통코드 HEAD 등록 */
	public function itemHead_update($param)
	{

		if($param['mod'] == 1){

			$dateTime = date("Y-m-d H:i:s",time());
			
			$data = array(
				'ITEM_CODE'   => $param['CODE'],
				'ITEM_NAME'   => $param['NAME'],
				'REMARK'      => $param['REMARK'],
				'UPDATE_ID'   => $param['INSERT_ID'],
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->update("T_ITEM_H",$data,array("IDX"=>$param['IDX']));
			return $param['IDX'];
		
		}else{

			$dateTime = date("Y-m-d H:i:s",time());

			$data = array(
				'ITEM_CODE'   => $param['CODE'],
				'ITEM_NAME'   => $param['NAME'],
				'REMARK'      => $param['REMARK'],
				'INSERT_ID'   => $param['INSERT_ID'],
				'INSERT_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->insert("T_ITEM_H",$data);

			return $this->db->insert_id();

		}

		

	}


	/* 공통코드 Detail 등록 */
	public function itemDetail_update($param)
	{

		if($param['mod'] == 1){

			$dateTime = date("Y-m-d H:i:s",time());
			$modCode = "D".time();

			$data = array(
				'H_IDX'		  => $param['H_IDX'],
				'S_NO'		  => $param['S_NO'],
				'D_ITEM_CODE' => $param['CODE'],
				'D_ITEM_NAME' => $param['NAME'],
				'ITEM_ATT'	  => $param['ITEM_ATT'],
				'PRODUCT'	  => $param['PRODUCT'],
				'UNIT'	      => $param['UNIT'],
				'ITEM_USE'	  => $param['ITEM_USE'],
				'ITEM_GB'	  => $param['ITEM_GB'],
				'PRICE'	      => $param['PRICE'],
				'JD_GB'	      => $param['JD_GB'],
				'CUSTOMER'	  => $param['CUSTOMER'],
				'USE_YN'	  => $param['USE_YN'],
				'REMARK'      => $param['REMARK'],
				'UPDATE_ID'   => $modCode,
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->update("T_ITEM_D",$data,array("IDX"=>$param['IDX']));
			return $param['IDX'];
		
		}else{

			$dateTime = date("Y-m-d H:i:s",time());
			$newCode = "D".time();

			$data = array(
				'H_IDX'       => $param['H_IDX'],
				'S_NO'        => $param['S_NO'],
				'D_ITEM_CODE' => $param['CODE'],
				'D_ITEM_NAME' => $param['NAME'],
				'ITEM_ATT'	  => $param['ITEM_ATT'],
				'PRODUCT'	  => $param['PRODUCT'],
				'UNIT'	      => $param['UNIT'],
				'ITEM_USE'	  => $param['ITEM_USE'],
				'ITEM_GB'	  => $param['ITEM_GB'],
				'PRICE'	      => $param['PRICE'],
				'JD_GB'	      => $param['JD_GB'],
				'CUSTOMER'	  => $param['CUSTOMER'],
				'USE_YN'	  => $param['USE_YN'],
				'REMARK'      => $param['REMARK'],
				'INSERT_ID'   => $newCode,
				'INSERT_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->insert("T_ITEM_D",$data);

			return $this->db->insert_id();

		}

		

	}



	/* 픔목코드 HEAD 리스트 */
	public function get_itemHead_list()
	{
		$res = $this->db->get("T_ITEM_H");
		return $res->result();

	}

	/* 픔목코드 Detail 리스트 */
	public function get_itemDetail_list($hid = "")
	{
		$this->db->select("D.*,H.ITEM_CODE as H_CODE");
		$this->db->from("T_ITEM_D as D");
		$this->db->join("T_ITEM_H as H","H.IDX = D.H_IDX");
		if($hid){
			$this->db->where("H_IDX",$hid);
		}
		$this->db->order_by("S_NO","ASC");
		$res = $this->db->get();


		return $res->result();

	}

	
	/* 픔목코드 HEAD 상세정보 */
	public function get_itemHead_info($idx)
	{
		$res = $this->db->where("IDX",$idx)
						->get("T_ITEM_H");
		return $res->row();
	}


	/* 픔목코드 Detail 상세정보 */
	public function get_itemDetail_info($idx)
	{
		$res = $this->db->where("IDX",$idx)
						->get("T_ITEM_D");
		return $res->row();
	}


	public function delete_itemHead($code)
	{
		$res = $this->db->delete("T_ITEM_H",array('ITEM_CODE'=>$code));
		return $this->db->affected_rows();
	}

	public function delete_itemDetail($idx)
	{
		$res = $this->db->delete("T_ITEM_D",array('IDX'=>$idx));
		return $this->db->affected_rows();
	}


	/* 코드중복검사 */
	public function ajax_itemHaedchk($object,$code)
	{
		$this->db->where($object,$code);
        $query = $this->db->get('T_ITEM_H');
         
        if($query->num_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
	}



}