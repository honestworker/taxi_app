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
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[255]');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
        }
        
        $user_data = array(
            'email'			    => strip_tags(trim($this->input->post('email'))),
            'password'		    => strip_tags(trim($this->input->post('password'))),
        );
        $result = $this->Auth_Model->login($user_data);
        if ( $result == 0 ) {
            $this->response['status'] = 'success';
            $this->response['data'] = $result['token'];
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'no_activated';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'wrong_password';
        } else if ( $result == -3 ) {
            $this->response['error_type'] = 'no_profile';
        } else if ( $result == -4 ) {
            $this->response['error_type'] = 'no_user';
        }
        
        echo json_encode($this->response);
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
        }

        if ($this->input->post('type') != 'driver' && $this->input->post('type') != 'user') {
            echo json_encode($this->response);
        }
        
        if ( isset( $_FILES['images'] ) ) {
            if ( count( $_FILES['images'] ) ) {
                foreach ( $_FILES['images']['error'] as $key => $error ) {
                    if ($error !== UPLOAD_ERR_OK) {
                        $this->response['error_type'] = 'image_upload_error';
                        $this->response['message'] = 'You did not upload images correctly, please try again.';
                        echo json_encode($this->response);
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
    }

    public function logout() {
        $this->form_validation->set_rules('token', 'Token', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
        }
        
        $this->Auth_Model->logout(strip_tags(trim($this->input->post('token'))));
        if ( $result == 0 ) {
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'token_error';
        }

        echo json_encode($this->response);
    }

    public function emailVerifyCode() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
        }

        if ( $this->input->post('type') != 'driver' && $this->input->post('type') != 'user' ) {
            $this->response['error_type'] = 'no_type';
            echo json_encode($this->response);
        }
        
        $result = $this->User_Model->emailVerifyCode(strip_tags(trim($this->input->post('type'))), strip_tags(trim($this->input->post('email'))));
        if ( $result == 0 ) {
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'no_user';
        }

        echo json_encode($this->response);
    }

    public function emailVerify() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
        }
        
        if ( $this->input->post('type') != 'driver' && $this->input->post('type') != 'user' ) {
            $this->response['error_type'] = 'no_type';
            echo json_encode($this->response);
        }

        $result = $this->User_Model->emailVerifyCode(strip_tags(trim($this->input->post('type'))), strip_tags(trim($this->input->post('email'))), strip_tags(trim($this->input->post('code'))));
        if ( $result == 0 ) {
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'error_code';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'no_profile';
        } else if ( $result == -3 ) {
            $this->response['error_type'] = 'no_user';
        }

        echo json_encode($this->response);
    }

    public function updatePosition() {
        $this->form_validation->set_rules('latitude', 'Latitude', 'trim|required');
        $this->form_validation->set_rules('longitude', 'Longitude', 'trim|required');
        $this->form_validation->set_rules('token', 'Token', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
        }
        
        $result = $this->User_Model->updatePosition(strip_tags(trim($this->input->post('latitude'))), strip_tags(trim($this->input->post('longitude'))), strip_tags(trim($this->input->post('token'))));
        if ( $result == 0 ) {
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'token_error';
        }

        echo json_encode($this->response);        
    }

    public function smsVerifyCode() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
        }
        
        $result = $this->User_Model->smsVerifyCode(strip_tags(trim($this->input->post('type'))), strip_tags(trim($this->input->post('phone_number'))), strip_tags(trim($this->input->post('email'))));
        if ( $result == 0 ) {
            $this->response['error_type'] = '';
        } else if ( $result == -1 ) {
            $this->response['error_type'] = 'no_profile';
        } else if ( $result == -2 ) {
            $this->response['error_type'] = 'no_user';
        }

        echo json_encode($this->response);       
    }

    public function smsVerify() {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            echo json_encode($this->response);
        }
        
        $result = $this->User_Model->smsVerify(strip_tags(trim($this->input->post('type'))), strip_tags(trim($this->input->post('email'))), strip_tags(trim($this->input->post('phone_number'))), strip_tags(trim($this->input->post('code'))));
        if ( $result == 0 ) {
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
        
    }
}