<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}

	public function get_envs_day($params,$start=0,$limit=20)
	{
		$where ='';

		if($params['DATE1']!='' && $params['DATE2']!='')
		{
			$where.="AND DATE BETWEEN '{$params['DATE1']} 00:00:00' AND '{$params['DATE2']} 23:59:59'";
		}

		$sql=<<<SQL
			SELECT 
				DATE
			FROM
				T_ENV
			WHERE 
			1
				{$where}
			GROUP BY DATE_FORMAT(DATE,"%Y-%m-%d")
			ORDER BY
				DATE DESC
			LIMIT
				{$start},{$limit}
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}
	public function get_envs_day_cnt($params)
	{
		$where ='';

		if($params['DATE1']!='' && $params['DATE2']!='')
		{
			$where.="AND DATE BETWEEN '{$params['DATE1']} 00:00:00' AND '{$params['DATE2']} 23:59:59'";
		}

		$sql=<<<SQL
			SELECT 
				DATE
			FROM
				T_ENV
			WHERE 
			1
				{$where}
			GROUP BY DATE_FORMAT(DATE,"%Y-%m-%d")
			ORDER BY
				DATE DESC
SQL;

		$query = $this->db->query($sql);
		//var_dump($query->result());
		return $query->num_rows();
	}

	/*환경정보 쿼리 */
	public function get_envs_info($date,$location)
	{
		$sql =<<<SQL
		SELECT
			*
		FROM 
			t_env
		WHERE 
			LOCATION= '{$location}'
			AND DATE BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
		GROUP BY
			DATE_FORMAT(DATE,"%Y-%m-%d %H:%i:%s")
		ORDER BY 
			DATE DESC
		LIMIT 
			1000
SQL;
		
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		
		return $query->result();
	}

}