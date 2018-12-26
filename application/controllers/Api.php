<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('form_validation');
       
        $this->load->helper('url');
        
        $this->load->model('Auth_Model');
        $this->load->model('User_Model');
        $this->load->model('Ads_Model');

        $this->response = array(
            'status' => 'fail',
            'message' => '',
            'data' => null,
            'error_type' => 'no_fill'
        );
    }

    public function login() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[255]');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }

        if ($this->input->post('type') != 'driver' && $this->input->post('type') != 'user') {
            $this->response['error_type'] = 'no_type';
            echo json_encode($this->response);
            exit(-1);
        }
        
        $role = 2;
        if ($this->input->post('type') == 'user') {
            $role = 3;
        }
        $user_data = array(
            'role'			    => $role,
            'email'			    => strtolower(strip_tags(trim($this->input->post('email')))),
            'password'		    => strip_tags($this->input->post('password')),
        );
        $result = $this->Auth_Model->login($user_data);
        if ( $result['error_type'] == 0 ) {
            $this->response['status'] = 'success';
            $this->response['data'] = $result['token'];
            $this->response['error_type'] = '';
        } else if ( $result['error_type'] == -1 ) {
            $this->response['error_type'] = 'no_activated';
        } else if ( $result['error_type'] == -2 ) {
            $this->response['error_type'] = 'wrong_password';
        } else if ( $result['error_type'] == -3 ) {
            $this->response['error_type'] = 'no_profile';
        } else if ( $result['error_type'] == -4 ) {
            $this->response['error_type'] = 'no_user';
        }
        
        echo json_encode($this->response);
        exit(-1);
    }

    public function signup() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[255]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|min_length[6]|max_length[255]');

        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }

        if ($this->input->post('password') != $this->input->post('confirm_password')) {
            $this->response['error_type'] = 'confirm_password';
            echo json_encode($this->response);
            exit(-1);
        }

        if ($this->input->post('type') != 'driver' && $this->input->post('type') != 'user') {
            $this->response['error_type'] = 'no_type';
            echo json_encode($this->response);
            exit(-1);
        }

        $images = null;
        if ( isset( $_FILES['images'] ) ) {
            $images = $_FILES['images'];
        }

        $role = 2;
        if ($this->input->post('type') == 'user') {
            $role = 3;
        }
        $user_data = array(
            'first_name'		=> strip_tags(trim($this->input->post('first_name'))),
            'last_name'		    => strip_tags(trim($this->input->post('last_name'))),
            'email'			    => strtolower(strip_tags(trim($this->input->post('email')))),
            'password'		    => strip_tags($this->input->post('password')),
            'role'			    => $role,            
            'images'			=> $images,
        );
        
        $result = $this->Auth_Model->createUser($user_data);
        if ( $result['error_type'] == 0 ) {
            $this->response['status'] = 'success';
            $this->response['data'] = $result['user'];
            $this->response['error_type'] = '';
        } else if ( $result['error_type'] == -1 ) {
            $this->response['error_type'] = 'registered';
        } else if ( $result['error_type'] == -2 ) {
            $this->response['error_type'] = 'database';
        } else if ( $result['error_type'] == -3 ) {
            $this->response['error_type'] = 'image_error';
        }

        echo json_encode($this->response);
        exit(-1);
    }

    public function logout() {
        $this->form_validation->set_rules('token', 'Token', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
        }
        
        $result = $this->Auth_Model->logout(strip_tags(trim($this->input->post('token'))));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'token_error';
        }

        echo json_encode($this->response);
        exit(-1);
    }

    public function emailVerifyCode() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }

        if ( $this->input->post('type') != 'driver' && $this->input->post('type') != 'user' ) {
            $this->response['error_type'] = 'no_type';
            echo json_encode($this->response);
            exit(-1);
        }
        
        $role = 2;
        if ($this->input->post('type') == 'user') {
            $role = 3;
        }
        $result = $this->User_Model->emailVerifyCode($role, strtolower(strip_tags(trim($this->input->post('email')))));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'no_user';
        }

        echo json_encode($this->response);
        exit(-1);
    }

    public function emailVerify() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }
        
        if ( $this->input->post('type') != 'driver' && $this->input->post('type') != 'user' ) {
            $this->response['error_type'] = 'no_type';
            echo json_encode($this->response);
            exit(-1);
        }

        $role = 2;
        if ($this->input->post('type') == 'user') {
            $role = 3;
        }
        $result = $this->User_Model->emailVerify($role, strtolower(strip_tags(trim($this->input->post('email')))), strip_tags(trim($this->input->post('code'))));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'error_code';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'no_profile';
        } else if ( $result == -3 ) {
            $this->response['error_type'] = 'no_user';
        }

        echo json_encode($this->response);
        exit(-1);
    }

    public function updatePosition() {
        $this->form_validation->set_rules('latitude', 'Latitude', 'trim|required');
        $this->form_validation->set_rules('longitude', 'Longitude', 'trim|required');
        $this->form_validation->set_rules('token', 'Token', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }
        
        $result = $this->User_Model->updatePosition(strip_tags(trim($this->input->post('latitude'))), strip_tags(trim($this->input->post('longitude'))), strip_tags(trim($this->input->post('token'))));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'token_error';
        }

        echo json_encode($this->response);
        exit(-1);
    }

    public function smsVerifyCode() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }
        
        if ($this->input->post('type') != 'driver' && $this->input->post('type') != 'user') {
            $this->response['error_type'] = 'no_type';
            echo json_encode($this->response);
            exit(-1);
        }

        $role = 2;
        if ($this->input->post('type') == 'user') {
            $role = 3;
        }
        $result = $this->User_Model->smsVerifyCode($role, strip_tags(trim($this->input->post('phone_number'))), strtolower(strip_tags(trim($this->input->post('email')))));
        if ( $result['error_type'] == 0 ) {
            $this->response['status'] = 'success';
            $this->response['data'] = $result['data'];
            $this->response['error_type'] = '';
        } else if ( $result['error_type'] == -1 ) {
            $this->response['error_type'] = 'registered';
        } else if ( $result['error_type'] == -2 ) {
            $this->response['error_type'] = 'no_profile';
        } else if ( $result['error_type'] == -3 ) {
            $this->response['error_type'] = 'no_user';
        }

        echo json_encode($this->response);
        exit(-1);
    }

    public function smsVerify() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }

        if ($this->input->post('type') != 'driver' && $this->input->post('type') != 'user') {
            $this->response['error_type'] = 'no_type';
            echo json_encode($this->response);
            exit(-1);
        }
        
        $role = 2;
        if ($this->input->post('type') == 'user') {
            $role = 3;
        }
        $result = $this->User_Model->smsVerify($role, strtolower(strip_tags(trim($this->input->post('email')))), strip_tags(trim($this->input->post('phone_number'))), strip_tags(trim($this->input->post('code'))));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'error_code';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'error_phone';
        } else if ( $result == -3 ) {
            $this->response['error_type'] = 'no_profile';
        } else if ( $result == -4 ) {
            $this->response['error_type'] = 'no_user';
        }

        echo json_encode($this->response);
        exit(-1);
    }
    
    public function changeEmailCode() {
        $this->form_validation->set_rules('token', 'Token', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }

        $result = $this->User_Model->changeEmailCode(strip_tags(trim($this->input->post('token'))), strtolower(strip_tags(trim($this->input->post('email')))));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'registered';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'token_error';
        }

        echo json_encode($this->response);
        exit(-1);
    }

    public function changeEmail() {
        $this->form_validation->set_rules('token', 'Token', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }
        
        $result = $this->User_Model->changeEmail(strip_tags(trim($this->input->post('token'))), strtolower(strip_tags(trim($this->input->post('email')))), strip_tags(trim($this->input->post('code'))));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'error_code';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'no_profile';
        } else if ( $result == -3 ) {
            $this->response['error_type'] = 'token_error';
        }

        echo json_encode($this->response);
        exit(-1);
    }

    public function changeSmsCode() {
        $this->form_validation->set_rules('token', 'Token', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }
        
        $result = $this->User_Model->changeSmsCode(strip_tags(trim($this->input->post('token'))), strip_tags(trim($this->input->post('phone_number'))));
        if ( $result['error_type'] == 0 ) {
            $this->response['status'] = 'success';
            $this->response['data'] = $result['data'];
            $this->response['error_type'] = '';
        } else if ( $result['error_type'] == -1 ) {
            $this->response['error_type'] = 'registered';
        } else if ( $result['error_type'] == -2 ) {
            $this->response['error_type'] = 'no_profile';
        } else if ( $result['error_type'] == -3 ) {
            $this->response['error_type'] = 'token_error';
        }

        echo json_encode($this->response);
        exit(-1);
    }

    public function changeSms() {
        $this->form_validation->set_rules('token', 'Token', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }

        $result = $this->User_Model->changeSms(strip_tags(trim($this->input->post('token'))), strip_tags(trim($this->input->post('phone_number'))), strip_tags(trim($this->input->post('code'))));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'error_code';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'no_profile';
        } else if ( $result == -3 ) {
            $this->response['error_type'] = 'token_error';
        }

        echo json_encode($this->response);
        exit(-1);
    }
    
    public function forgotPassword() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }

        if ($this->input->post('type') != 'driver' && $this->input->post('type') != 'user') {
            $this->response['error_type'] = 'no_type';
            echo json_encode($this->response);
            exit(-1);
        }
        
        $role = 2;
        if ($this->input->post('type') == 'user') {
            $role = 3;
        }
        $result = $this->User_Model->forgotPassword($role, strtolower(strip_tags(trim($this->input->post('email')))));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'no_profile';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'no_user';
        }

        echo json_encode($this->response);
        exit(-1);
    }

    public function changePassword() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[255]');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }

        if ($this->input->post('type') != 'driver' && $this->input->post('type') != 'user') {
            $this->response['error_type'] = 'no_type';
            echo json_encode($this->response);
            exit(-1);
        }
        
        $role = 2;
        if ($this->input->post('type') == 'user') {
            $role = 3;
        }
        $result = $this->User_Model->changePassword($role, strtolower(strip_tags(trim($this->input->post('email')))), strip_tags($this->input->post('password')), strip_tags(trim($this->input->post('code'))));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'error_code';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'no_user';
        }

        echo json_encode($this->response);
        exit(-1);
    }

    public function getDrivers() {
        $this->form_validation->set_rules('token', 'Token', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }

        $result = $this->User_Model->getAPIUsers(2, strip_tags(trim($this->input->post('token'))));
        if ($result['error_type'] == 0) {
            $this->response['status'] = 'success';
            $this->response['data'] = $result['data'];
            $this->response['error_type'] = '';
        } else if ($result['error_type'] == -1) {
            $this->response['error_type'] = 'token_error';
        }

        echo json_encode($this->response);
        exit(-1);
    }

    public function getAd() {
        $this->form_validation->set_rules('token', 'Token', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }

        $result = $this->Ads_Model->getAPIAd(strip_tags(trim($this->input->post('token'))));
        if ($result['error_type'] == 0) {
            $this->response['status'] = 'success';
            $this->response['data'] = $result['data'];
            $this->response['error_type'] = '';
        } else if ($result['error_type'] == -1) {
            $this->response['error_type'] = 'token_error';
        }

        echo json_encode($this->response);
        exit(-1);

    }
}