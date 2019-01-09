<?php

class Auth_Model extends CI_Model {
    
    protected $image_extensions = array('image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/bmp');
    
    protected $images_path = 'public/images/users/';

    function __construct() {
        parent::__construct();
        
        $this->load->database();
        
        $this->load->library('session');
        $this->load->library('email');
    }
    
    private function generate_code($len = 30, $type = 'heavy') {
        $char_seed = 'bcghlpxUVW34Jq8drafs7#BCwjGHL125NOZMY06%EPX9!@QneDRAFSozmTKItuvkiy';
        if ( $type == 'light' ) {
            $char_seed = '0123456789';
        } else if ( $type == 'middle' ) {
            $char_seed = 'bcgZMEPhlnXQoziy@#CVWJmepxqakRAF!GHLNODTUstuvwjSfBdrKIY';
        } else if ( $type == 'common' ) {
            $char_seed = 'bcgZMEPhlnXQoziyCVWJmepxqakRAFGHLNODTUstuvwjSfBdrKIY';
        }
        
        $chars_len = strlen($char_seed);
        $ret = '';
        for($i = 0; $i < $len; $i++){
            $ret .= $char_seed[rand(0, $chars_len  - 1)];
        }
        
        return $ret;
    }

    private function generate_token() {
        $token = "";
        $repeat = 1;
        while ($repeat) {
            $token = $this->generate_code(100, 'middle');
            if ( !$this->db->get_where('users', array('token' => $token))->result() ) {
                $repeat = 0;
            }
        }        
        
        return $token;
    }
	
    private function sendEmailVerifyCode( $email, $name, $code ) {
        $this->email->from( 'info@cabgomaurice.com', 'Taxi App' );
        $this->email->to( $email );
        $this->email->subject( 'Please verify your email.' );
        $this->email->message( "Hi, " . $name . ".<br/><br/>"  . " Verification Code: " . $code . "<br/><br/>Thank you.");
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    public function createUser( $data ) {
        $response = array(
            'user' => null,
            'error_type' => -2
        );
        if ( $data['images'] ) {
            if ( count( $data['images'] ) ) {
                foreach ( $data['images']['error'] as $key => $error ) {
                    if ($error !== UPLOAD_ERR_OK) {
                        $response['error_type'] = -3;
                        return $response;
                    }
                }
                foreach ( $data['images']['type'] as $key => $type ) {
                    if ( !in_array(strtolower($type), $this->image_extensions) ) {
                        $response['error_type'] = -4;
                        return $response;
                    }
                }
            }
        }
		if ( $user_row = $this->db->get_where('users', array('email' => $data['email'], 'role' => $data['role']))->result() ) {
            $user = $user_row[0];
            if ($user->status == 'activated') {
                $response['error_type'] = -1; // Already registered
                return $response;
            }

            if ( $user_row = $this->db->select('id')->from('users')->where('email', $data['email'])->where('role', $data['role'])->where('id !=', $user->id)->get()->result() ) {
                $response['error_type'] = -1; // Already registered
                return $response;
            }
		    if ( $profile_row = $this->db->get_where('profile', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                $this->db->update('profile', array('first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'updated_at' => date('Y-m-d H:i:s')), array('id' => $profile->id) );
            } else {
                $profile_data = array(
                    'user_id'		=> $user->id,
                    'first_name'	=> $data['first_name'],
                    'last_name'     => $data['last_name'],
                    'created_at'	=> date('Y-m-d H:i:s'),
                    'updated_at'	=> date('Y-m-d H:i:s'),
                );
                $this->db->insert('profile', $profile_data);
            }
            if ($data['images']) {
                $this->uploadImage($user->id, $data['role'], $data['images']);
            }
            
            if ( $user->avatar ) {
                unlink($this->images_path . $user->avatar);
            }
            $salt = $this->generate_code(10, 'middle');
            $this->db->update('users', array('salt' => $salt, 'password' => md5($data['password'] . $salt), 'updated_at' => date('Y-m-d H:i:s')), array('id' => $user->id) );
            
            $code = '';
            if ($data['role'] == 2 || $data['role'] == 3) {
                $code = $this->generate_code(5, 'light');
                $this->db->update('users', array('email_code' => $code), array('id' => $user->id) );
                $this->sendEmailVerifyCode($user->email, $data['first_name'], $code);
            }

            $response['error_type'] = 0;
            $response['user'] = array(
                'id'			    => $user->id,
                'email'			    => $user->email,
                'avatar'			=> '',
                'first_name'		=> $data['first_name'],
                'last_name'		    => $data['last_name'],
                'created_at'	    => $user->created_at,
                //'code'              => $code,
            );
            return $response;
        } else {
            $salt = $this->generate_code(10, 'middle');
            $user_data = array(
                'email'			    => $data['email'],
                'role'			    => $data['role'],
                'salt'			    => $salt,
                'avatar'			=> '',
                'password'		    => md5($data['password'] . $salt),
                'status'		    => 'registered',
                'created_at'	    => date('Y-m-d H:i:s'),
                'updated_at'	    => date('Y-m-d H:i:s'),
            );
            
            if ( $user_row = $this->db->get_where('users', array('email' => $data['email'], 'role' => $data['role']))->result() ) {
                $response['error_type'] = -1; // Already registered
                return $response;
            }

            if ( $this->db->insert('users', $user_data) ) {
                $user_id = $this->db->insert_id();
                
                $profile_data = array(
                    'user_id'		=> $user_id,
                    'first_name'	=> $data['first_name'],
                    'last_name'     => $data['last_name'],
                    'created_at'	=> date('Y-m-d H:i:s'),
                    'updated_at'	=> date('Y-m-d H:i:s'),
                );
    
                if ( $this->db->insert('profile', $profile_data) ) {
                    if ( $user_row = $this->db->get_where('users', array('id' => $user_id))->result() ) {
                        $user = $user_row[0];
                        if ($data['images']) {
                            $this->uploadImage($user->id, $data['role'], $data['images']);
                        }
                        $code = '';
                        if ($data['role'] == 2 || $data['role'] == 3) {
                            $code = $this->generate_code(5, 'light');
                            $this->db->update('users', array('email_code' => $code), array('id' => $user->id) );
                            $this->sendEmailVerifyCode($user->email, $data['first_name'], $code);
                        }
                        $response['error_type'] = 0;
                        $response['user'] = array(
                            'id'			    => $user->id,
                            'avatar'			=> $user->avatar,
                            'email'			    => $user->email,
                            'first_name'		=> $data['first_name'],
                            'last_name'		    => $data['last_name'],
                            'created_at'	    => $user->created_at,
                            //'code'              => $code,
                        );
                        return $response;
                    }
                }
            }
        }

        return $response;
    }

    public function uploadImage($user_id, $role, $images) {
        $image_type = '';
        if ($role == 1) {
            $image_type = 'admin';
        } else if ($role == 2) {
            $image_type = 'driver';
        } else if ($role == 3) {
            $image_type = 'user';
        }
		if ( $images_row = $this->db->get_where('images', array('parent_id' => $user_id, 'type' => $image_type) )->result() ) {
            foreach($images_row as $image) {
                unlink($this->images_path . $image->name);
                $this->db->delete('images', array('id' => $image->id));
            }
        }

        if ($images) {
            if ( count( $images ) ) {
                foreach ( $images['type'] as $key => $type ) {
                    $mt = explode(' ', microtime());
                    $name = ((int)$mt[1]) * 1000000 + ((int)round($mt[0] * 1000000));
                    $file_name = $name . '.' . str_replace('image/', '', $type);
                    $tmp_name = $images['tmp_name'][$key];
                    move_uploaded_file( $tmp_name, $this->images_path . $file_name );
                    list($width, $height) = getimagesize( $this->images_path . $file_name );
                    $this->db->insert('images',
                        array(
                            'type'          => $image_type,
                            'parent_id'     => $user_id,
                            'name'          => $file_name,
                            'width'         => $width,
                            'height'        => $height,
                            'created_at'    => date('Y-m-d H:i:s'),
                            'updated_at'    => date('Y-m-d H:i:s'),
                        ));
                }
            }
        }
    }

    public function activation( $activation_code ) {
		if ( $this->db->get_where('users', array('activation_code' => $activation_code) )->result() ) {
            $this->db->update('users', array('status' => 'activated'), array('activation_code' => $activation_code) );
            return 1;
        }
        return 0;
    }

    public function login( $data ) {
        $response = array(
            'token' => '',
            'error_type' => -4 // No user
        );
        
		if ( $user_row = $this->db->get_where('users', array('email' => $data['email'], 'role' => $data['role']) )->result() ) {
            $user = $user_row[0];
            if ( $user->password == md5($data['password'] . $user->salt) ) {
                if ( $user->status  != 'activated' ) {
                    $response['error_type'] = -1; // No activated
                    return $response;
                }
            } else {
                $response['error_type'] = -2; // Wrong Password
                return $response;
            }

            if ( $profile_row = $this->db->get_where('profile', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                $response['error_type'] = 0; // OK
                if ( $data['role'] == 1) {
                    if ( $profile->avatar ) {
                        $this->session->set_userdata('avatar', $profile->avatar);
                    } else {
                        $this->session->set_userdata('avatar', 'no_avatar.jpg');
                    }
                    $this->session->set_userdata('name', $profile->first_name . ' ' . $profile->last_name);
        
                    $this->session->set_userdata('id', $user->id);
                    $this->session->set_userdata('email', $user->email);
                } else {                    
                    $token = $this->generate_token();
                    $this->db->update('users', array('token' => $token), array('id' => $user->id) );
                    $response['token'] = $token;
                    return $response;
                }
            } else {
                $response['error_type'] = -3; // No Profile
                return $response;
            }
        }        

        return $response;
    }

    public function logout( $token ) {
		if ( $user_row = $this->db->get_where('users', array('token' => $token) )->result() ) {
            $user = $user_row[0];
            $this->db->update('users', array('token' => ''), array('id' => $user->id) );
            return 0;
        }
        return -1;
    }
}