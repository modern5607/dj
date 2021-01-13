<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sys_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}

	

	public function get_system_menu($lev)
	{
		$sql=<<<SQL
			SELECT
				*
			FROM
				t_menu01
			WHERE
				S_NO > 0
SQL;
		$query = $this->db->query($sql);
		$data = array();
		foreach($query->result() as $i=>$menu){
			$data[$i]['MTITLE'] = $menu->MENU_GROUP;
			$this->db->where("MENU_GROUP",$menu->S_NO);
			$query = $this->db->get("t_menu01");
			$data[$i]['MENU'] = $query->result();
		}
		return $data;
	}


	public function get_menu_list()
	{
		$query = $this->db->get('t_menu01');
		return $query->result();
	}

	public function get_user_list($param,$start=0,$limit=20)
	{
		if(!empty($param['ID']) && $param['ID'] != ""){
			$this->db->where("ID",$param['ID']);
		}

		if(!empty($param['NAME']) && $param['NAME'] != ""){
			$this->db->where("NAME",$param['NAME']);
		}

		if(!empty($param['LEVEL']) && $param['LEVEL'] != ""){
			$this->db->where("LEVEL",$param['LEVEL']);
		}
		$this->db->limit($limit,$start);
		$query = $this->db->get('t_member');
		return $query->result();
	}
	public function get_user_cut($param)
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


	public function set_menu_insert($params,$idx) 
	{
		if($idx != ""){
			$this->db->where("IDX",$idx);
			$this->db->update("t_menu01",$params);
		}else{
			$this->db->insert("t_menu01",$params);
		}
		return $this->db->affected_rows();
	}


	public function check_pageLevel($level,$code) {
		$query = $this->db->select("COUNT(*) as num")
						->where("MENUID",$code)
						->where("LEVEL >",$level)
						->get("t_menu01");
		return $query->row()->num;
	}


	public function get_member_info($idx)
	{
		$data = $this->db->where(array('IDX'=>$idx))
						->get("t_member");
		return $data->row();
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

	public function ajax_savelevel_update($param)
	{
		
		$this->db->set("LEVEL",$param['level']);
		$this->db->where("IDX",$param['idx']);
		$this->db->update("T_MEMBER");

		return $this->db->affected_rows();

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
