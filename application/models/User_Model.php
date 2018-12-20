<?php

class User_Model extends CI_Model {
    function __construct() {
        parent::__construct();
        
        $this->load->database();
        
        $this->load->library('session');
        $this->load->library('email');
    }

    private function generate_token($len = 30, $type = 'heavy') {
        $char_seed = 'bcghlpxUVW34Jq8drafs7#BCwjGHL125NOZMY06%EPX9!@QneDRAFSozmTKItuvkiy';
        if ( $type == 'light' ) {
            $char_seed = '0123456789';
        } else if ( $type == 'middle' ) {
            $char_seed = 'bcgZMEPhlnXQoziy@#CVWJmepxqakRAF!GHLNODTUstuvwjS%fBdrKIY';
        }
        
        $chars_len = strlen($char_seed);
        $ret = '';
        for($i = 0; $i < $len; $i++){
            $ret .= $char_seed[rand(0, $chars_len  - 1)];
        }
        
        return $ret;
    }

    private function sendActivateCode( $email, $name, $code ) {
        $this->email->from( 'info@cabgomaurice.com', 'Taxi App' );
        $this->email->to( $email );
        $this->email->subject( 'Please activate your account.' );
        $this->email->message( "Hi, " . $name . ".<br/><br/>"  . " Please activate your account.<br/>Linke here: " . base_url() . 'activate/'. $code . "<br/><br/>Thank you.");
        $this->email->send();
    }

    public function emailVerifyCode( $type, $email ) {
        $role = 2;
        if ($type == 'user') {
            $role = 3;
        }
		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];
            $code = $this->generate_token(5, 'light');
            $this->sendActivateCode($email, $user->first_name, $code);
            return 0;
        }

        return -1;
    }
    
    public function emailVerify( $type, $email, $code ) {
        $role = 2;
        if ($type == 'user') {
            $role = 3;
        }
		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];
            if ( $user->email_code != $code ) {
                return -1;
            }

		    if ( $profile_row = $this->db->get_where('users', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                $this->db->update('users', array('status' => 'activated'), array('id' => $user->id) );
                $this->db->update('profile', array('email_confirmed' => 1), array('id' => $profile->id) );
                return 0;
            } else {
                return -2;
            }
        }

        return -3;
    }

    public function updatePosition( $latitude, $longitude, $token ) {
		if ( $user_row = $this->db->get_where('users', array('token' => $token) )->result() ) {
            $user = $user_row[0];
            $this->db->update('users', array('latitude' => $latitude, 'longitude' => $longitude), array('id' => $user->id) );
            return 0;
        }

        return -1;
    }

    public function smsVerifyCode( $type, $phone_number, $email ) {
        $role = 2;
        if ($type == 'user') {
            $role = 3;
        }
		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];

		    if ( $profile_row = $this->db->get_where('users', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                $code = $this->generate_token(5, 'light');
                $this->db->update('phone_code', array('status' => $code), array('id' => $user->id) );
                $this->db->update('profile', array('phone_number' => $phone_number), array('id' => $profile->id) );
                return 0;
            } else {
                return -1;
            }
        }

        return -2;
    }

    public function smsVerify( $type, $email, $phone_number, $code ) {
        $role = 2;
        if ($type == 'user') {
            $role = 3;
        }
		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];
            if ( $user->phone_code != $code ) {
                return -1;
            }

		    if ( $profile_row = $this->db->get_where('users', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                if ( $profile->phone_number != $phone_number ) {
                    return -2;
                }
                $this->db->update('profile', array('phone_confirmed' => 1), array('id' => $profile->id) );
                return 0;
            } else {
                return -3;
            }
        }

        return -4;
    }

}