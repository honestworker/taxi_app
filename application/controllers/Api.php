<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    protected $image_extensions = array('image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/bmp');

    function __construct() {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('form_validation');
       
        $this->load->helper('url');
        
        $this->load->model('Auth_Model');
        $this->load->model('User_Model');

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
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[255]');
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
            'email'			    => strip_tags(trim($this->input->post('email'))),
            'password'		    => strip_tags(trim($this->input->post('password'))),
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
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[255]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');

        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
            exit(-1);
        }

        if ($this->input->post('type') != 'driver' && $this->input->post('type') != 'user') {
            $this->response['error_type'] = 'no_type';
            echo json_encode($this->response);
            exit(-1);
        }
        
        if ( isset( $_FILES['images'] ) ) {
            if ( count( $_FILES['images'] ) ) {
                foreach ( $_FILES['images']['error'] as $key => $error ) {
                    if ($error !== UPLOAD_ERR_OK) {
                        $this->response['error_type'] = 'image_upload_error';
                        $this->response['message'] = 'You did not upload images correctly, please try again.';
                        echo json_encode($this->response);
                        exit(-1);
                    }
                }
                foreach ( $_FILES['images']['type'] as $key => $type ) {
                    if ( !in_array($type, $this->image_extensions) ) {
                        $this->response['error_type'] = 'image_type_error';
                        $this->response['message'] = 'Your images must be jpeg, png, jpg, gif, bmp!';
                        echo json_encode($this->response);
                    }
                }
            }
        }

        $role = 2;
        if ($this->input->post('type') == 'user') {
            $role = 3;
        }
        $user_data = array(
            'first_name'		=> strip_tags(trim($this->input->post('first_name'))),
            'last_name'		    => strip_tags(trim($this->input->post('last_name'))),
            'email'			    => strip_tags(trim($this->input->post('email'))),
            'password'		    => strip_tags(trim($this->input->post('password'))),
            'role'			    => $role,
        );
        
        $result = $this->Auth_Model->createUser($user_data);
        if ( $result['error_type'] == 0 ) {
            if (isset( $_FILES['images'] )) {
                $this->Auth_Model->uploadImage($result['user']['id'], $_FILES['images']);
            }
            $this->response['status'] = 'success';
            $this->response['data'] = $result['user'];
            $this->response['error_type'] = '';
        } else if ( $result['error_type'] == -1 ) {
            $this->response['error_type'] = 'registered';
        } else if ( $result['error_type'] == -2 ) {
            $this->response['error_type'] = 'database';
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
        $result = $this->User_Model->emailVerifyCode($role, strip_tags(trim($this->input->post('email'))));
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
        $result = $this->User_Model->emailVerify($role, strip_tags(trim($this->input->post('email'))), strip_tags(trim($this->input->post('code'))));
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
        $result = $this->User_Model->smsVerifyCode($role, strip_tags(trim($this->input->post('phone_number'))), strip_tags(trim($this->input->post('email'))));
        if ( $result['error_type'] == 0 ) {
            $this->response['status'] = 'success';
            $this->response['data'] = $result['data'];
            $this->response['error_type'] = '';
        } else if ( $result['error_type'] == -1 ) {
            $this->response['error_type'] = 'no_profile';
        } else if ( $result['error_type'] == -2 ) {
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
        $result = $this->User_Model->smsVerify($role, strip_tags(trim($this->input->post('email'))), strip_tags(trim($this->input->post('phone_number'))), strip_tags(trim($this->input->post('code'))));
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

        $result = $this->User_Model->changeEmailCode(strip_tags(trim($this->input->post('token'))), strip_tags(trim($this->input->post('email'))));
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
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
        
        $result = $this->User_Model->changeEmail(strip_tags(trim($this->input->post('token'))), strip_tags(trim($this->input->post('email'))), strip_tags(trim($this->input->post('code'))));
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
            $this->response['error_type'] = 'no_profile';
        } else if ( $result['error_type'] == -2 ) {
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
        $result = $this->User_Model->forgotPassword($role, strip_tags(trim($this->input->post('email'))));
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
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[255]');
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
        $result = $this->User_Model->changePassword($role, strip_tags(trim($this->input->post('email'))), strip_tags(trim($this->input->post('password'))), strip_tags(trim($this->input->post('code'))));
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
}