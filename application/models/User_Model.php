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
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    private function sendEmailVerifyCode( $email, $name, $code ) {
        $this->email->from( 'info@cabgomaurice.com', 'Taxi App' );
        $this->email->to( $email );
        $this->email->subject( 'Please verify your email.' );
        $this->email->message( "Hi, " . $name . ".<br/><br/>"  . " Verification Code: " . $code . "<br/><br/>Thank you.");
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    private function sendSMSCode($phone_number, $code) {
        $url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
                ['api_key' =>  'ce2c4084',
                'api_secret' => 'U6SzWmBQJ0RpLyia',
                'to' => $phone_number,
                'from' => 'Nexmo',
                'text' => '[cabgomaurice.com]Please verify your phone number. Verification Code: ' . $code,
                ]
            );
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        return $response;
    }

    // private function emailVerifyCode( $role, $email ) {
	// 	if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
    //         $user = $user_row[0];
    //         $code = $this->generate_token(5, 'light');
    //         $this->sendEmailVerifyCode($email, $user->first_name, $code);
    //         return 0;
    //     }

    //     return -1;
    // }
    
    public function emailVerify( $role, $email, $code ) {
		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];
            if ( $user->email_code != $code ) {
                return -1;
            }

		    if ( $profile_row = $this->db->get_where('profile', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
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

    public function smsVerifyCode( $role, $phone_number, $email ) {
        $response = array(
            'data' => '',
            'error_type' => -2
        );

		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];

		    if ( $profile_row = $this->db->get_where('profile', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                $code = $this->generate_token(5, 'light');
                $this->db->update('users', array('phone_code' => $code), array('id' => $user->id) );
                $this->db->update('profile', array('phone_number' => $phone_number), array('id' => $profile->id) );
                $this->sendSMSCode($phone_number, $code);
                $response['error_type'] = 0;
                $response['data'] = $code;
                return $response;
            } else {
                $response['error_type'] = -1;
                return $response;
            }
        }

        return $response;
    }

    public function smsVerify( $role, $email, $phone_number, $code ) {
		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];
            if ( $user->phone_code != $code ) {
                return -1;
            }

		    if ( $profile_row = $this->db->get_where('profile', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                if ( $profile->phone_number != $phone_number ) {
                    return -2;
                }
                $this->db->update('users', array('status' => 'activated'), array('id' => $user->id) );
                $this->db->update('profile', array('phone_confirmed' => 1), array('id' => $profile->id) );
                return 0;
            } else {
                return -3;
            }
        }

        return -4;
    }

    private function changeEmailCode( $token, $email ) {
		if ( $user_row = $this->db->get_where('users', array('token' => $token) )->result() ) {
            $user = $user_row[0];
            $code = $this->generate_token(5, 'light');
            $this->sendEmailVerifyCode($email, $user->first_name, $code);
            return 0;
        }

        return -1;
    }

    public function changeEmail( $token, $email, $code ) {
		if ( $user_row = $this->db->get_where('users', array('token' => $token) )->result() ) {
            $user = $user_row[0];
            if ( $user->email_code != $code ) {
                return -1;
            }

		    if ( $profile_row = $this->db->get_where('profile', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                $this->db->update('users', array('email' => $email), array('id' => $user->id) );
                $this->db->update('profile', array('email_confirmed' => 1), array('id' => $profile->id) );
                return 0;
            } else {
                return -2;
            }
        }

        return -3;
    }

    public function changeSmsCode( $token, $phone_number ) {
        $response = array(
            'data' => '',
            'error_type' => -2
        );

		if ( $user_row = $this->db->get_where('users', array('token' => $token) )->result() ) {
            $user = $user_row[0];

		    if ( $profile_row = $this->db->get_where('profile', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                $code = $this->generate_token(5, 'light');
                $this->db->update('users', array('phone_code' => $code), array('id' => $user->id) );
                $this->sendSMSCode($phone_number, $code);
                $response['error_type'] = 0;
                $response['data'] = $code;
                return $response;
            } else {
                $response['error_type'] = -1;
                return $response;
            }
        }

        return $response;
    }

    public function changeSms( $token, $phone_number, $code ) {
		if ( $user_row = $this->db->get_where('users', array('token' => $token) )->result() ) {
            $user = $user_row[0];
            if ( $user->phone_code != $code ) {
                return -1;
            }

		    if ( $profile_row = $this->db->get_where('profile', array('id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                $this->db->update('profile', array('phone_confirmed' => 1, 'phone_number' => $phone_number), array('id' => $profile->id) );
                return 0;
            } else {
                return -2;
            }
        }

        return -3;
    }

    public function forgotPassword( $role, $email ) {
		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];
            $code = $this->generate_token(5, 'light');
            
		    if ( $profile_row = $this->db->get_where('profile', array('id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                $this->sendEmailVerifyCode($email, $profile->first_name, $code);
                return 0;
            } else {
                return -1;
            }
            return 0;
        }

        return -2;
    }
    
    public function changePassword( $role, $email, $password, $code ) {
		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];
            if ( $user->email_code != $code ) {
                return -1;
            }

            $salt = $this->generate_token(10, 'middle');
            $this->db->update('users', array('slat' => $salt, 'password' => md5($password . $salt)), array('id' => $user->id) );
        }

        return -3;
    }
}