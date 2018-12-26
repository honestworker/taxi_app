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
        $this->load->model('Ads_Model');
        
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
            $this->flash_data['alerts']['info'][] = 'The user has been actived already.';
        } else if ($result == -2) {
            $this->response['error_type'] = 'no_profile';
            $this->flash_data['alerts']['error'][] = 'Can not find this user profile.';
        } else if ($result == -3) {
            $this->response['error_type'] = 'no_user';
            $this->flash_data['alerts']['error'][] = 'Can not find this user.';
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
            $this->flash_data['alerts']['info'][] = 'The user has beed disabled already.';
        } else if ($result == -2) {
            $this->response['error_type'] = 'no_user';
            $this->flash_data['alerts']['error'][] = 'Can not find this user.';
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
            $this->flash_data['alerts']['error'][] = 'Can not find this user.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }

    /*
    * Advertisement Management
    */
    public function getAllAds() {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }
        
        $data['ads'] = $this->Ads_Model->getAll();
        
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/ads', $data);
        $this->load->view('admin/layouts/footer');
    }

    public function createAd() {
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
        $this->load->view('admin/pages/ads_form');
        $this->load->view('admin/layouts/footer');
    }
    
    public function editAd($ad_id) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }

        $data['ad'] = $this->Ads_Model->getAd($ad_id);

        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/siderbar');
        $this->load->view('admin/pages/ads_form', $data);
        $this->load->view('admin/layouts/footer');
    }
    
	public function activeAd($ad_id) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }

        $result = $this->Ads_Model->activeAd($ad_id);
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The advertisement has been actived successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_action';
            $this->flash_data['alerts']['info'][] = 'The advertisement has beed activated already.';
        } else if ($result == -2) {
            $this->response['error_type'] = 'no_ad';
            $this->flash_data['alerts']['error'][] = 'Can not find this advertisement.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);

        echo json_encode($this->response);
        exit(-1);
    }

	public function disableAd($ad_id) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }
        
        $result = $this->Ads_Model->disableAd($ad_id);
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The advertisement has been disabled successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_action';
            $this->flash_data['alerts']['info'][] = 'The advertisement has beed disabled already.';
        } else if ($result == -2) {
            $this->response['error_type'] = 'no_ad';
            $this->flash_data['alerts']['error'][] = 'Can not find this advertisement.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }

	public function deleteAd($ad_id) {
        $user_data = $this->session->get_userdata();
        if ( !$user_data ) {
            redirect('login');
        } else {
            if ( !isset($user_data['id']) ) {
                redirect('login');
            }
        }
        
        $result = $this->Ads_Model->deleteAd($ad_id);
        if ($result == 0) {
            $this->response['error_type'] = '';
            $this->response['status'] = 'success';
            $this->flash_data['alerts']['success'][] = 'The advertisement has been disabled successfully.';
        } else if ($result == -1) {
            $this->response['error_type'] = 'no_ad';
            $this->flash_data['alerts']['error'][] = 'Can not find this advertisement.';
        }
        
        $this->session->set_flashdata('flash_data', $this->flash_data);
        
        echo json_encode($this->response);
        exit(-1);
    }

	public function storeAd() {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('link', 'Link', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        $result = array(
            'user' => null,
            'error_type' => -1
        );
        if ($this->form_validation->run() !== false) {
            $images = null;
            if ( isset( $_FILES['images'] ) ) {
                $images = $_FILES['images'];
            }
            $ad_data = array(
                'id'			    => strip_tags(trim($this->input->post('id'))),
                'name'			    => strip_tags(trim($this->input->post('name'))),
                'link'              => strip_tags(trim($this->input->post('link'))),
                'status'            => strip_tags(trim($this->input->post('status'))),
                'images'            => $images,
            );
            $result = $this->Ads_Model->storeAd($ad_data);
            if ( $result['error_type'] == 0 ) {
                $this->flash_data['alerts']['success'][] = 'Successfully stored.';
                $this->session->set_flashdata('flash_data', $this->flash_data);
                redirect('ads');
            } else if ( $result['error_type'] == -1 ) {
                $this->flash_data['alerts']['info'][] = 'The process of image uploading is failed. Pleas try again.';
            } else if ( $result['error_type'] == -2 ) {
                $this->flash_data['alerts']['info'][] = 'The image type is not accepted.';
            } else if ( $result['error_type'] == -3 ) {
                $this->flash_data['alerts']['info'][] = 'Could not find the advertisement.';
            }
        } else {
            $this->flash_data['errors'] = $this->form_validation->error_array();
        }

        $this->session->set_flashdata('flash_data', $this->flash_data);
    }    
}