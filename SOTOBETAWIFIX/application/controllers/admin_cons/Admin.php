<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct(){
			parent::__construct();
		/*	if ($this->session->userdata('id_level')!='1') {
				header("Location:../");*/
	}
	public function index(){
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
	}
}