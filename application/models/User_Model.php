<?php

class User_Model extends CI_Model {
    private $images_path = 'public/images/users/';

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
            $char_seed = 'bcgZMEPhlnXQoziy@#CVWJmepxqakRAF!GHLNODTUstuvwjS%fBdrKIY';
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

    private function sendActiveCode( $email, $name, $code ) {
        $this->email->from( 'info@cabgomaurice.com', 'Taxi App' );
        $this->email->to( $email );
        $this->email->subject( 'Please active your account.' );
        $this->email->message( "Hi, " . $name . ".<br/><br/>"  . " Please active your account.<br/>Linke here: " . base_url() . 'active/'. $code . "<br/><br/>Thank you.");
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    private function sendChangePasswordCode( $email, $name, $code ) {
        $this->email->from( 'info@cabgomaurice.com', 'Taxi App' );
        $this->email->to( $email );
        $this->email->subject( 'Please change your password.' );
        $this->email->message( "Hi, " . $name . ".<br/><br/>"  . " Please change your password.<br/>Linke here: " . base_url() . 'change/'. $code . "<br/><br/>Thank you.");
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

    private function sendForgotCode( $email, $name, $code ) {
        $this->email->from( 'info@cabgomaurice.com', 'Taxi App' );
        $this->email->to( $email );
        $this->email->subject( 'Please change your password.' );
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
    //         $code = $this->generate_code(5, 'light');
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
                if ($user->role == 3) {
                    $this->db->update('users', array('status' => 'activated'), array('id' => $user->id) );
                }
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
            'error_type' => -3
        );

		if ( $user_row = $this->db->get_where('users', array('email' => $email, 'role' => $role) )->result() ) {
            $user = $user_row[0];

		    if ( $profile_row = $this->db->get_where('profile', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                if ( $phone_row = $this->db->get_where('profile', array('phone_number' => $phone_number, 'user_id' => $user->id) )->result() ) {
                    $response['error_type'] = -1;
                    return $response;
                }
                $code = $this->generate_code(5, 'light');
                $this->db->update('users', array('phone_code' => $code), array('id' => $user->id) );
                $this->db->update('profile', array('phone_number' => $phone_number), array('id' => $profile->id) );
                $this->sendSMSCode($phone_number, $code);
                $response['error_type'] = 0;
                //$response['data'] = $code;
                return $response;
            } else {
                $response['error_type'] = -2;
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

    public function changeEmailCode( $token, $email ) {
		if ( $user_row = $this->db->get_where('users', array('token' => $token) )->result() ) {
            $user = $user_row[0];
            if ( $email_row = $this->db->get_where('users', array('email' => $email) )->result() ) {
                return -1;
            }
            $code = $this->generate_code(5, 'light');
            $this->sendEmailVerifyCode($email, $user->first_name, $code);
            return 0;
        }

        return -2;
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
            'error_type' => -3
        );

		if ( $user_row = $this->db->get_where('users', array('token' => $token) )->result() ) {
            $user = $user_row[0];

		    if ( $profile_row = $this->db->get_where('profile', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                if ( $phone_row = $this->db->get_where('profile', array('phone_number' => $phone_number) )->result() ) {
                    $response['error_type'] = -1;
                    return $response;
                }
                $code = $this->generate_code(5, 'light');
                $this->db->update('users', array('phone_code' => $code), array('id' => $user->id) );
                $this->sendSMSCode($phone_number, $code);
                $response['error_type'] = 0;
                $response['data'] = $code;
                return $response;
            } else {
                $response['error_type'] = -2;
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

		    if ( $profile_row = $this->db->get_where('profile', array('user_id' => $user->id) )->result() ) {
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
            $code = $this->generate_code(5, 'light');
            $this->db->update('users', array('password_code' => $code), array('id' => $user->id) );
            
		    if ( $profile_row = $this->db->get_where('profile', array('id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                if ($user->role == 1) {
                    $code = $this->generate_code(50, 'common');
                    $this->db->update('users', array('password_code' => $code), array('id' => $user->id) );
                    $this->sendChangePasswordCode($email, $profile->first_name, $code);
                } else {
                    $code = $this->generate_code(5, 'light');
                    $this->db->update('users', array('password_code' => $code), array('id' => $user->id) );
                    $this->sendForgotCode($email, $profile->first_name, $code);
                }
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
            if ( $user->password_code != $code ) {
                return -1;
            }

            $this->db->update('users', array('password_code' => ''), array('id' => $user->id) );
            $salt = $this->generate_code(10, 'middle');
            $this->db->update('users', array('salt' => $salt, 'password' => md5($password . $salt)), array('id' => $user->id) );
            return 0;
        }

        return -2;
    }
    
    public function changeAdminPassword( $code, $password ) {
		if ( $user_row = $this->db->get_where('users', array('password_code' => $code, 'role' => 1) )->result() ) {
            $user = $user_row[0];

            $this->db->update('users', array('password_code' => ''), array('id' => $user->id) );
            $salt = $this->generate_code(10, 'middle');
            $this->db->update('users', array('salt' => $salt, 'password' => md5($password . $salt)), array('id' => $user->id) );
            return 0;
        }

        return -1;
    }
    
    public function activePassword( $code ) {
		if ( $user_row = $this->db->get_where('users', array('password_code' => $code, 'role' => 1) )->result() ) {
            $user = $user_row[0];

            $this->db->update('users', array('password_code' => ''), array('id' => $user->id) );
            $salt = $this->generate_code(10, 'middle');
            $this->db->update('users', array('salt' => $salt, 'password' => md5($password . $salt)), array('id' => $user->id) );
            return 0;
        }
    }

    /*
    *  Get user counts by according the user role
    */
    public function getUserCounts() {
        $result = array(
            'admins' => 0,
            'drivers' => 0,
            'users' => 0,
        );
        $user_rows = $this->db->get_where('users', array('role' => 1) )->result();
        if ($user_rows) {
            $result['admins'] = count($user_rows);
        }
        $user_rows = $this->db->get_where('users', array('role' => 2) )->result();
        if ($user_rows) {
            $result['drivers'] = count($user_rows);
        }
        $user_rows = $this->db->get_where('users', array('role' => 3) )->result();
        if ($user_rows) {
            $result['users'] = count($user_rows);
        }
        return $result;
    }

    /*
    *  Get all users by according the user role
    */
    public function getUsers($role) {
        $sql_query = "SELECT ut.id, ut.email, ut.created_at, ut.status, ut.latitude, ut.longitude, pt.first_name, pt.last_name, pt.phone_number, pt.email_confirmed, pt.phone_confirmed,";
        $sql_query .= " (SELECT avg(rate) FROM ratings WHERE ut.id = ratings.driver_id) AS rate ";
        $sql_query .= " FROM users as ut INNER JOIN profile as pt ON pt.user_id=ut.id";
        $type = "";
        if ($role) {
            $sql_query .= " WHERE ut.role = " . $role;
            if ($role == 1) {
                $type = 'admin';
            } else if ($role == 2) {
                $type = 'driver';
            } else if ($role == 3) {
                $type = 'user';
            }
        }
        $sql_query .= " ORDER BY ut.created_at;";
        $users = $this->db->query($sql_query)->result();
        
        return $users;
    }

    /*
    *  Get all users by according the user role
    */
    public function getAPIUsers($role, $token) {
        $response = array(
            'data' => '',
            'error_type' => -1
        );
		if ( $user_row = $this->db->get_where('users', array('token' => $token) )->result() ) {
            $sql_query = "SELECT ut.id, ut.email, ut.latitude, ut.longitude, pt.first_name, pt.last_name, pt.comment, pt.phone_number,";
            $sql_query .= " (SELECT avg(rate) FROM ratings WHERE ut.id = ratings.driver_id) AS rate ";
            $sql_query .= " FROM users as ut INNER JOIN profile as pt ON pt.user_id=ut.id";
            $sql_query .= " WHERE ut.status = 'activated' AND ut.token != ''";
            $type = "";
            if ($role) {
                $sql_query .= " AND ut.role = " . $role;
                if ($role == 1) {
                    $type = 'admin';
                } else if ($role == 2) {
                    $type = 'driver';
                } else if ($role == 3) {
                    $type = 'user';
                }
            }
            $sql_query .= " ORDER BY ut.created_at;";
            $users = $this->db->query($sql_query)->result();
            if ($users) {
                foreach($users as $user) {
                    $this->db->select('name'); // Select field
                    $this->db->from('images');
                    if ($type) {
                        $this->db->where('type', $type);
                    }
                    $this->db->where('parent_id', $user->id);
                    $user->images = $this->db->get()->result_array();
                }
            }
            $response['error_type'] = 0;
            $response['data'] = $users;
        }
        
        return $response;
    }
    
    /*
    *  Active User
    */
    public function activeUser($user_id) {
		if ( $user_row = $this->db->get_where('users', array('id' => $user_id) )->result() ) {
            $user = $user_row[0];
            if ( $user->status == 'activated' ) {
                return -1;
            }
            if ( $profile_row = $this->db->get_where('profile', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                if ( $user->role == 1 ) {
                    $code = $this->generate_code(50, 'common');
                    $this->db->update('users', array('activation_code' => $code), array('id' => $user->id) );
                    $this->sendActiveCode($user->email, $profile->first_name, $code);
                    return 0;
                } else if ( $user->role == 2) {
                    if ( $profile->email_confirmed && $profile->phone_confirmed ) {
                        $this->db->update('users', array('status' => 'activated'), array('id' => $user->id) );
                        return 0;
                    } else {
                        if ( $user->status == 'registered' ) {
                            return -1;
                        } else {
                            $this->db->update('users', array('status' => 'registered'), array('id' => $user->id) );
                            return 0;
                        }
                    }
                } else if ( $user->role == 3) {
                    $this->db->update('users', array('status' => 'activated'), array('id' => $user->id) );
                    return 0;
                }
            } else {
                return -2;
            }
        }

        return -3;
    }

    /*
    *  Disable User
    */
    public function disableUser($user_id) {
		if ( $user_row = $this->db->get_where('users', array('id' => $user_id) )->result() ) {
            $user = $user_row[0];
            if ($user->status == 'disabled') {
                return -1;
            }
            $this->db->update('users', array('status' => 'disabled'), array('id' => $user_id));
            return 0;
        }
        return -2;
    }

    /*
    *  Delete User
    */
    public function deleteUser($user_id) {
		if ( $user_row = $this->db->get_where('users', array('id' => $user_id) )->result() ) {
            $user = $user_row[0];
            if ( $profile_row = $this->db->get_where('profile', array('user_id' => $user->id) )->result() ) {
                $profile = $profile_row[0];
                $this->db->delete('profile', array('id' => $profile->id));
            }
            $image_type = '';
            if ($user->role == 1) {
                $image_type = 'admin';
            } else if ($user->role == 2) {
                $image_type = 'driver';
            } else if ($user->role == 3) {
                $image_type = 'user';
            }
            if ( $images_row = $this->db->get_where('images', array('parent_id' => $user->id, 'type' => $image_type) )->result() ) {
                foreach($images_row as $image) {
                    unlink($this->images_path . $image->name);
                    $this->db->delete('images', array('id' => $image->id));
                }
            }
            $this->db->delete('users', array('id' => $user->id));
            return 0;
        }

        return -1;
    }
}