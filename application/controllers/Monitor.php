<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitor extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);

		$this->load->model(array('monitor_model'));

		if (!empty($this->config->item('site_title')[$this->data['pos']][$this->data['subpos']])) {
			$this->data['siteTitle'] = $this->config->item('site_title')[$this->data['pos']][$this->data['subpos']];
		}
	}

	public function _remap($method, $params = array())
	{
		if ($this->input->is_ajax_request()) {
			if (method_exists($this, $method)) {
				call_user_func_array(array($this, $method), $params);
			}
		} else { //ajax가 아니면

			if (method_exists($this, $method)) {

				$user_id = $this->session->userdata('user_id');
				$this->data['member_name'] = $this->session->userdata('user_name');

				if (isset($user_id) && $user_id != "") {

					$this->load->view('/layout/header', $this->data);
					call_user_func_array(array($this, $method), $params);
					$this->load->view('/layout/tail');
				} else {

					alert('로그인이 필요합니다.', base_url('register/login'));
				}
			} else {
				show_404();
			}
		}
	}

	public function env()
	{
		$data['title'] = "온습도 모니터링";

		$data['str']['date1'] = $this->input->get('from');
		$data['str']['date2'] = $this->input->get('to');

		$params['DATE1'] = "";
		$params['DATE2'] = "";

		$data['qstr'] = "?P";

		if (!empty($data['str']['date1'])) {
			$params['DATE1'] = $data['str']['date1'];
			$data['qstr'] .= "&date2=" . $data['str']['date1'];
		}
		if (!empty($data['str']['date2'])) {
			$params['DATE2'] = $data['str']['date2'];
			$data['qstr'] .= "&date2=" . $data['str']['date2'];
		}

		$data['perpage'] = ($this->input->get('perpage') != "") ? $this->input->get('perpage') : 20;

		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->get('pageNum') > '' ? $this->input->get('pageNum') : 0;

		$start = $pageNum;
		$data['pageNum'] = $start;

		$data['daylist'] = $this->monitor_model->get_envs_day($params, $start, $config['per_page']);
		// echo var_dump($data['daylist']);
		$this->data['cnt'] = $this->monitor_model->get_envs_day_cnt($params);


		/* pagenation start */

		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
        $config['total_rows'] = $this->data['cnt'];


		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$this->pagination->initialize($config);
        $this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/monitor/env', $data);

	}

	public function monitor_ajax_envs()
	{
		$date = $this->input->post("date");

		$data['envslist1'] = $this->monitor_model->get_envs_info($date,1);
		$data['envslist2'] = $this->monitor_model->get_envs_info($date,2);

		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		return $this->load->view("monitor/ajax_envinfo", $data);
	}
}

?>
