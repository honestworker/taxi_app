<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function __construct() {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('form_validation');
       
        $this->load->helper('url');
        
        $this->load->model('Auth_Model');
    }

	public function index() {
        $flash_data = array(
            'errors' => null,
            'alerts' => array(
                'info' => null,
                'success' => null,
                'error' => null
            )
        );

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[255]');

        $result = array(
            'user' => null,
            'error_type' => -1
        );
        if ($this->form_validation->run() !== false) {
            $user_data = array(
                'email'			    => strip_tags(trim($this->input->post('email'))),
                'password'		    => strip_tags(trim($this->input->post('password'))),
                'role'              => 1,
            );
            $result = $this->Auth_Model->login($user_data);
            if ( $result['error_type'] == 0 ) {
                $flash_data['alerts']['success'][] = 'Successfully login.';
            } else if ( $result['error_type'] == -1 ) {
                $flash_data['errors'][] = 'No activated';
            } else if ( $result['error_type'] == -2 ) {
                $flash_data['errors'][] = 'Wrong Password';
            } else if ( $result['error_type'] == -3 ) {
                $flash_data['errors'][] = 'No exist';
            }
        } else {
            $flash_data['errors'] = $this->form_validation->error_array();
        }

        $this->session->set_flashdata('flash_data', $flash_data);

        if ( $result['error_type'] == 0 ) {
            redirect('admin/dashboard');
        } else {
            $this->load->view('common/layouts/auth/header');
            $this->load->view('common/pages/login');
            $this->load->view('common/layouts/auth/footer');
        }
	}
	
    public function signup() {
        $flash_data = array(
            'errors' => null,
            'alerts' => array(
                'info' => null,
                'success' => null,
                'error' => null
            )
        );

        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[255]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
        

        if ($this->form_validation->run() !== false) {
            $user_data = array(
                'first_name'		=> strip_tags(trim($this->input->post('first_name'))),
                'last_name'		    => strip_tags(trim($this->input->post('last_name'))),
                'email'			    => strip_tags(trim($this->input->post('email'))),
                'password'		    => strip_tags(trim($this->input->post('password'))),
                'role'			    => 1,
                //'activation_code'	=> $activation_code,
            );
            
            $result = $this->Auth_Model->createUser($user_data);
            if ( $result['error_type'] == 0 ) {
                $flash_data['alerts']['success'][] = 'Successfully registered. The admin will send you the activation code soon.';
            } else if ( $result['error_type'] == -1 ) {
                $flash_data['alerts']['info'][] = 'Your email has been registed.';
            } else if ( $result['error_type'] == -2 ) {
                $flash_data['alerts']['error'][] = 'Database operation failed.';
            }
        } else {
            $flash_data['errors'] = $this->form_validation->error_array();
        }
        
        $this->session->set_flashdata('flash_data', $flash_data);

        $this->load->view('common/layouts/auth/header');
        $this->load->view('common/pages/signup');
        $this->load->view('common/layouts/auth/footer');
    }
    
    public function activate( $activation_code ) {
        $flash_data = array(
            'errors' => null,
            'alerts' => array(
                'info' => null,
                'success' => null,
                'error' => null
            )
        );

        if ( $this->Auth_Model->activation( strip_tags(trim($activation_code)) ) ) {
            $flash_data['alerts']['success'][] = 'Successfully activated. Please login.';
            redirect('login');
        } else {
            $flash_data['alerts']['alert'][] = 'Fail Activation!';
        }

        $this->session->set_flashdata('flash_data', $flash_data);

        $this->load->view('common/layouts/auth/header');
        $this->load->view('common/pages/login');
        $this->load->view('common/layouts/auth/footer');
    }

    public function logout() {
        $this->session->unset_userdata('id');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('avatar');
        
        redirect('login');
    }
}
