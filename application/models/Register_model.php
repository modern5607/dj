<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}



	/* member 정보호출 */
	public function get_userchk($obj,$val)
	{
		$this->db->where($obj,$val);
		$res = $this->db->get("t_member");

		return $res->row();

	}


	public function set_log_insert($params)
	{
		$this->db->insert("t_log",$params);
		return $this->db->insert_id();
	}


	public function set_log_end($idx)
	{
		date_default_timezone_set('Asia/Seoul');
		$datetime = date("Y-m-d H:i:s",time());
		$this->db->set("EDATE",$datetime);
		$this->db->set("STATUS","off");
		$this->db->where("IDX",$idx);
		$this->db->update("t_log");
	}


	public function member_formupdate($params,$idx)
	{
		if(!empty($idx)){
			$this->db->update("t_member",$params,array("IDX"=>$idx));
		}else{
			$this->db->insert("t_member",$params);
			$idx = $this->db->insert_id();
		}
		return $idx;
	}

	public function ajax_chk_memberid($id)
	{
		$this->db->select("COUNT(*) as cnt");
		$this->db->where("ID",$id);
		$query = $this->db->get("t_member");
		$data['msg'] = "사용가능한 아이디입니다.";
		$data['state'] = 1;
		if($query->row()->cnt > 0){
			$data['msg'] = "사용중인 아이디입니다.";
			$data['state'] = 2;
		}
		return $data;
	}


	public function get_member_info($idx)
	{
		$data = $this->db->where(array('IDX'=>$idx))
						->get("t_member");
		return $data->row();
	}


	public function get_member_list($param,$start=0,$limit=20)
	{
		if(!empty($param['ID']) && $param['ID'] != ""){
			$this->db->like("ID",$param['ID']);
		}

		if(!empty($param['NAME']) && $param['NAME'] != ""){
			$this->db->like("NAME",$param['NAME']);
		}

		if(!empty($param['LEVEL']) && $param['LEVEL'] != ""){
			$this->db->where("LEVEL",$param['LEVEL']);
		}
		$this->db->limit($limit,$start);
		$res = $this->db->get("t_member");
		return $res->result();
	}


	public function get_member_cut($param)
	{
		$this->db->select("COUNT(*) as CUT");
		if(!empty($param['ID']) && $param['ID'] != ""){
			$this->db->where("ID",$param['ID']);
		}

		if(!empty($param['NAME']) && $param['NAME'] != ""){
			$this->db->where("NAME",$param['NAME']);
		}

		if(!empty($param['LEVEL']) && $param['LEVEL'] != ""){
			$this->db->where("LEVEL",$param['LEVEL']);
		}
		$res = $this->db->get("t_member");
		return $res->row()->CUT;
	}


	public function ajax_savelevel_update($param)
	{
		
		$this->db->set("LEVEL",$param['level']);
		$this->db->where("IDX",$param['idx']);
		$this->db->update("t_member");

		return $this->db->affected_rows();

	}


	public function get_ver_list($start,$limit)
	{
		$this->db->limit($limit,$start);
		$query = $this->db->get("t_ver");



		return $query->result();

	}


	public function get_ver_cut()
	{
		$this->db->select("COUNT(*) as CUT");
		$query = $this->db->get("t_ver");



		return $query->row()->CUT;

	}


	public function upload_ver_form($param,$MIDX = "")
	{
		if($MIDX == ""){
			$query = $this->db->insert("t_ver",$param);
			$xxx = $this->db->insert_id();
		}else{
			$this->db->where("IDX",$MIDX);
			$query = $this->db->update("t_ver",$param);
			$xxx = $MIDX;
		}
		return  $xxx;
	}


	public function delete_ver_form($param)
	{
		$this->db->where("IDX",$param['IDX']);
		$this->db->delete("t_ver");
		return $this->db->affected_rows();
	}


	public function modified_ver_form($param)
	{
		$this->db->where("IDX",$param['IDX']);
		$query = $this->db->get("t_ver");
		return $query->row();

	}



	


}