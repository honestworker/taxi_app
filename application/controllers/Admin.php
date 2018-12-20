<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('form_validation');
       
        $this->load->helper('url');
        
        //$this->load->model('Admin_Model');
    }
    
	public function index() {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/dashboard');
        $this->load->view('admin/layouts/footer');
    }
}