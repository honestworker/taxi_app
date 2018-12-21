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
        $this->load->model('User_Model');
        
        $this->flash_data = array(
            'errors' => null,
            'alerts' => array(
                'info' => null,
                'success' => null,
                'error' => null
            )
        );
    }

	public function index() {
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
                $this->flash_data['alerts']['success'][] = 'Successfully login.';
            } else if ( $result['error_type'] == -1 ) {
                $this->flash_data['errors'][] = 'No activated';
            } else if ( $result['error_type'] == -2 ) {
                $this->flash_data['errors'][] = 'Wrong Password';
            } else if ( $result['error_type'] == -3 ) {
                $this->flash_data['errors'][] = 'No exist';
            }
        } else {
            $this->flash_data['errors'] = $this->form_validation->error_array();
        }

        $this->session->set_flashdata('flash_data', $this->flash_data);

        if ( $result['error_type'] == 0 ) {
            redirect('dashboard');
        } else {
            $this->load->view('common/layouts/auth/header');
            $this->load->view('common/pages/login');
            $this->load->view('common/layouts/auth/footer');
        }
	}
	
    public function forgotPassword() {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');        

        if ($this->form_validation->run() !== false) {
            $result = $this->User_Model->forgotPassword(1, strip_tags(trim($this->input->post('email'))));
            if ( $result == 0 ) {
                $this->flash_data['alerts']['success'][] = 'We have sent your activation code to your email. Please look at your email.';
            } else if ( $result == -1 ) {
                $this->flash_data['alerts']['error'][] = 'The email does not exist.';
            } else if ( $result == -2 ) {
                $this->flash_data['alerts']['error'][] = 'The email does not exist.';
            }
        } else {
            $this->flash_data['errors'] = $this->form_validation->error_array();
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);

        $this->load->view('common/layouts/auth/header');
        $this->load->view('common/pages/forgot');
        $this->load->view('common/layouts/auth/footer');
    }

    public function changePassword($active_code) {
        $data['active_code'] = $active_code;

        $this->load->view('common/layouts/auth/header');
        $this->load->view('common/pages/change_password', $data);
        $this->load->view('common/layouts/auth/footer');
    }

    public function changeAdminPassword() {
        $this->form_validation->set_rules('active_code', 'Active Code', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[255]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');     

        if ($this->form_validation->run() !== false) {
            $result = $this->User_Model->changeAdminPassword(strip_tags(trim($this->input->post('active_code'))), strip_tags(trim($this->input->post('password'))));
            if ( $result == 0 ) {
                $this->flash_data['alerts']['success'][] = 'Password has been changed successfully.';
                $this->session->set_flashdata('flash_data', $this->flash_data);
                redirect('login');
            } else if ( $result == -1 ) {
                $this->flash_data['alerts']['error'][] = 'The activation code is expired. Please try again.';
            }
        } else {
            $this->flash_data['errors'] = $this->form_validation->error_array();
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);

        $this->load->view('common/layouts/auth/header');
        $this->load->view('common/pages/forgot');
        $this->load->view('common/layouts/auth/footer');
    }

    public function signup() {
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
                $this->flash_data['alerts']['success'][] = 'Successfully registered. The admin will send you the activation code soon.';
            } else if ( $result['error_type'] == -1 ) {
                $this->flash_data['alerts']['info'][] = 'Your email has been registered.';
            } else if ( $result['error_type'] == -2 ) {
                $this->flash_data['alerts']['error'][] = 'Database operation failed.';
            }
        } else {
            $this->flash_data['errors'] = $this->form_validation->error_array();
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);

        $this->load->view('common/layouts/auth/header');
        $this->load->view('common/pages/signup');
        $this->load->view('common/layouts/auth/footer');
    }
    
    public function active( $activation_code ) {
        if ( $this->Auth_Model->activation( strip_tags(trim($activation_code))) ) {
            $this->flash_data['alerts']['success'][] = 'Successfully activated. Please login.';
            redirect('login');
        } else {
            $this->flash_data['alerts']['error'][] = 'Fail Activation!';
        }

        $this->session->set_flashdata('flash_data', $this->flash_data);

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
