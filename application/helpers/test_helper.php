<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function check_pageLevel() {
	
	$CI =& get_instance();

	$member_level =  $CI->session->userdata('user_level');
	
	$pageCode = $CI->data['pos']."/".$CI->data['subpos'];
	
	$CI->load->model(array('sys_model'));
	$chk = $CI->sys_model->check_pageLevel($member_level,$pageCode);
	
	if($chk > 0){
		alert("접근권한이 부족합니다.",base_url('intro'));
		exit;
	}
}



