<?php

class Ads_Model extends CI_Model {
    
    protected $image_extensions = array('image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/bmp');

    protected $images_path = 'public/images/ads/';

    function __construct() {
        parent::__construct();
        
        $this->load->database();
        
        $this->load->library('session');
    }
    
    public function getAll() {
        $ads_row = $this->db->from('ads')->order_by('created_at', 'DESC')->get()->result();
        return $ads_row;
    }

    /*
    *  Get Advertisement
    */
    public function getAd($ad_id) {
        $ad_row = $this->db->from('ads')->where('id', $ad_id)->get()->result();
        if ($ad_row) {
            return $ad_row[0];
        }
        return null;
    }

    /*
    *  Store Advertisement
    */
    public function storeAd($data) {
        if ( $data['images'] ) {
            if ( count( $data['images'] ) ) {
                foreach ( $data['images']['error'] as $key => $error ) {
                    if ($error !== UPLOAD_ERR_OK) {
                        return -2;
                    }
                }
                foreach ( $data['images']['type'] as $key => $type ) {
                    if ( !in_array(strtolower($type), $this->image_extensions) ) {
                        return -3;
                    }
                }
            }
        }

        if ( $data['id'] ) {
            if ($ad_row = $this->db->from('ads')->where('id', $data['id'])->get()->result()) {
                $ad = $ad_row[0];
                $this->db->update('ads', array('name' => $data['name'], 'link' => $data['link'], 'status' => $data['status'], 'updated_at' => date('Y-m-d H:i:s')), array('id' => $ad->id) );
                
                unlink($this->images_path . $ad->image);

                if ( $data['images'] ) {
                    if ( count( $data['images'] ) ) {
                        foreach ( $data['images']['type'] as $key => $type ) {
                            $mt = explode(' ', microtime());
                            $name = ((int)$mt[1]) * 1000000 + ((int)round($mt[0] * 1000000));
                            $file_name = $name . '.' . str_replace('image/', '', $type);
                            $tmp_name = $data['images']['tmp_name'][$key];
                            move_uploaded_file( $tmp_name, $this->images_path . $file_name );
                            $this->db->update('images', array('image' => $file_name), array('id' => $ad->id));
                            break;
                        }
                    }
                }
            } else {
                return -1;
            }
        } else {
            $file_name = "";
            if ( $data['images'] ) {
                if ( count( $data['images'] ) ) {
                    foreach ( $data['images']['type'] as $key => $type ) {
                        $mt = explode(' ', microtime());
                        $name = ((int)$mt[1]) * 1000000 + ((int)round($mt[0] * 1000000));
                        $file_name = $name . '.' . str_replace('image/', '', $type);
                        $tmp_name = $data['images']['tmp_name'][$key];
                        move_uploaded_file( $tmp_name, $this->images_path . $file_name );
                        break;
                    }
                }
            }
            
            $this->db->insert('ads',
                array(
                    'name'          => $data['name'],
                    'link'          => $data['link'],
                    'image'         => $file_name,
                    'status'        => $data['status'],
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ));
        }

        return 0;
    }

    /*
    *  Active Advertisement
    */
    public function activeAd($ad_id) {
		if ( $ad_row = $this->db->get_where('ads', array('id' => $ad_id) )->result() ) {
            $ad = $ad_row[0];
            if ( $ad->status == 'active' ) {
                return -1;
            }

            $this->db->update('ads', array('status' => 'active'), array('id' => $ad->id) );
            return 0;
        }

        return -2;
    }

    /*
    *  Disable Advertisement
    */
    public function disableAd($ad_id) {
		if ( $ad_row = $this->db->get_where('ads', array('id' => $ad_id) )->result() ) {
            $ad = $ad_row[0];
            if ( $ad->status == 'disable' ) {
                return -1;
            }

            $this->db->update('ads', array('status' => 'disable'), array('id' => $ad->id) );
            return 0;
        }

        return -2;
    }

    /*
    *  Delete Advertisement
    */
    public function deleteAd($ad_id) {
		if ( $ad_row = $this->db->get_where('ads', array('id' => $ad_id) )->result() ) {
            $ad = $ad_row[0];

            unlink($this->images_path . $ad->image);
            $this->db->delete('ads', array('id' => $ad->id));
            return 0;
        }

        return -1;
    }

    /*
    *  Get Advertisement From APP
    */
    public function getAPIAd($token) {
        $response = array(
            'data' => '',
            'error_type' => -1
        );
		if ( $user_row = $this->db->get_where('users', array('token' => $token) )->result() ) {
            $response['error_type'] = 0;
            $response['data'] = null;
            if ( $ads_row = $this->db->get_where('ads', array('status' => 'active') )->result() ) {
                $ad_count = count( $ads_row );
                $ad_no = rand(0, $ad_count - 1);
                $response['data'] = $ads_row[$ad_no];
            }
        }

        return $response;
    }

}