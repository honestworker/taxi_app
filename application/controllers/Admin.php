<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('form_validation');
       
        $this->load->helper('url');
        
        $this->load->model('User_Model');

        $this->response = array(
            'status' => 'fail',
            'message' => '',
            'data' => null,
            'error_type' => 'no_fill'
        );
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
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }

        $data['users_counts'] = $this->User_Model->getUserCounts();

        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/dashboard', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	public function getAllAdmins() {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }

        $data['admins'] = $this->User_Model->getUsers(1);

        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/admins', $data);
        $this->load->view('admin/layouts/footer');
    }

	public function getAllDrivers() {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }

        $data['drivers'] = $this->User_Model->getUsers(2);

        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/drivers', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	public function getAllUsers() {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }

        $data['users'] = $this->User_Model->getUsers(3);

        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/users', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	public function activeUser($user_id) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }

        $result = $this->User_Model->activeUser($user_id);
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The user has been actived successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_action';
            $this->response['message'] = 'The user has beed activated already.';
        } else if ($result == -2) {
            $this->response['error_type'] = 'no_profile';
            $this->response['message'] = 'Can not find this user profile.';
        } else if ($result == -3) {
            $this->response['error_type'] = 'no_user';
            $this->response['message'] = 'Can not find this user.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);

        echo json_encode($this->response);
        exit(-1);
    }

	public function disableUser($user_id) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }

        $result = $this->User_Model->disableUser($user_id);
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The user has been disabled successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_action';
            $this->response['message'] = 'The user has beed disabled already.';
        } else if ($result == -2) {
            $this->response['error_type'] = 'no_user';
            $this->response['message'] = 'Can not find this user.';
        }

        $this->session->set_flashdata('flash_data', $this->flash_data);

        echo json_encode($this->response);
        exit(-1);
    }

	public function deleteUser($user_id) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }

        $result = $this->User_Model->deleteUser($user_id);
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The user has been disabled successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_user';
            $this->response['message'] = 'Can not find this user.';
        }

        $this->session->set_flashdata('flash_data', $this->flash_data);

        echo json_encode($this->response);
        exit(-1);
    }
}