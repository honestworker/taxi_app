<?php

class Smtp_Model extends CI_Model {
    function __construct() {
        parent::__construct();

        $this->load->library('email');
        
        $this->message_main_header = '<div style="width:100%!important;background:#f2f2f2;margin:0;padding:0" bgcolor="#f2f2f2">' .
                                '<div class="block">' .
                                '<table style="width:100%!important;line-height:100%!important;border-collapse:collapse;margin:0;padding:0" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f2f2f2">' .
                                '<tbody><tr>' .
                                '<td class="header" style="padding: 40px 0px;" align="center">' .
                                '<a href="https://cabgomaurice.com/">' .
                                '<img src="' . base_url() . 'assets/custom/images/favicon-96x96.png" alt="Cabgo" style="max-width: 150px">' .
                                '</a></td></tr></table></div><div class="block">' .
                                '<table style="border-collapse:collapse" width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center"><tbody><tr><td><table width="540" align="center" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse">' .
                                '<tbody><tr><td width="100%" height="30" style="border-collapse:collapse"></td></tr>';

        $this->message_element_header = '<tr><td style="vertical-align:top;font-family:Helvetica,arial,sans-serif;font-size:16px;color:#767676;text-align:left;line-height:20px;border-collapse:collapse" valign="top">';
        $this->message_element_footer = '</td></tr>';

        $this->message_element_seperator = '<tr><td width="100%" height="30" style="border-collapse:collapse;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px"></td></tr>';
        
        $this->message_content_header = '<div style="margin: 20px;">';
        $this->message_content_footer = '</div>';

        $this->message_text_header = '<div style="line-height:24px">';
        $this->message_small_text_header = '<div style="font-size:12px">';
        $this->message_text_footer = '</div>';
      
        $this->message_footer = '</table></tr></td></table></div><div class="block"><table style="width:100%!important;line-height:100%!important;border-collapse:collapse;margin:0;padding:0" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f2f2f2"><tr><td style="padding:20px 10px 20px 10px" align="center"><span style="font-family:Arial,Helvetica,Sans serif;font-size:10px;line-height:12px;color:#494949">© ' . date("Y") .  ' Cabgo.</span></td></tr></table></div>';

        $this->message_main_footer = '</div>';
    }

    public function sendActiveCode( $email, $name, $code ) {
        $this->email->from( 'info@cabgomaurice.com', 'Cabgo' );
        $this->email->to( $email );
        $this->email->subject( 'Active Your Cabgo Administrator Account.' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "Active your Cabgo administrator account.";
        $message_html .= $this->message_element_seperator;
        
        $message_html .= $this->message_content_header;
        $message_html .= $this->message_text_header;
        $message_html .= "Hi, ". $name . ". Welcome to Cabgo.";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "Please active your Cabgo administrator account.";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "Link here: ". base_url() . 'active/'. $code;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= 'Thanks.';
        $message_html .= $this->message_content_footer;
        $message_html .= $this->message_text_footer;

        $message_html .= $this->message_element_footer . $this->message_footer . $this->message_main_footer;
        $this->email->message( $message_html);
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    public function sendChangePasswordCode( $email, $name, $code ) {        
        $this->email->from( 'hello@cabgomaurice.com', 'Cabgo' );
        $this->email->to( $email );
        $this->email->subject( 'Change Your Cabgo Account Password' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "Change your Cabgo account password";
        $message_html .= $this->message_element_seperator;
        
        $message_html .= $this->message_content_header;
        $message_html .= $this->message_text_header;
        $message_html .= "Hi, ". $name . ". Welcome to Cabgo.";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "Please change your Cabgo account password.";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "Link here: ". base_url() . 'change/'. $code;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= 'Thanks.';
        $message_html .= $this->message_content_footer;
        $message_html .= $this->message_text_footer;

        $message_html .= $this->message_element_footer . $this->message_footer . $this->message_main_footer;
        $this->email->message( $message_html);
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    public function sendEmailVerifyCode( $email, $name, $code ) {
        $this->email->from( 'hello@cabgomaurice.com', 'Cabgo' );
        $this->email->to( $email );
        $this->email->subject( 'Verify Your Cabgo Account' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "Verify your Cabgo account";
        $message_html .= $this->message_element_seperator;
        
        $message_html .= $this->message_content_header;
        $message_html .= $this->message_text_header;
        $message_html .= "Hi, ". $name . ". Welcome to Cabgo.";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "To get started, please verify your email address with the code below.";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "Verification Code: " . $code;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= 'Thanks.';
        $message_html .= $this->message_content_footer;
        $message_html .= $this->message_text_footer;

        $message_html .= $this->message_element_footer . $this->message_footer . $this->message_main_footer;
        $this->email->message( $message_html);
        $this->email->set_mailtype('html');
        $this->email->send();
    }

    public function sendForgotCode( $email, $name, $code ) {
        $this->email->from( 'hello@cabgomaurice.com', 'Cabgo' );
        $this->email->to( $email );
        $this->email->subject( 'Change Your Cabgo Account Password' );
        $message_html = $this->message_main_header . $this->message_element_header;
        $message_html .= "Change your Cabgo account password";
        $message_html .= $this->message_element_seperator;
        
        $message_html .= $this->message_content_header;
        $message_html .= $this->message_text_header;
        $message_html .= "Hi, ". $name . ". Welcome to Cabgo.";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "To change your password, please verify your email address with the code below.";
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= "Verification Code: " . $code;
        $message_html .= $this->message_text_footer;
        $message_html .= $this->message_text_header;
        $message_html .= 'Thanks.';
        $message_html .= $this->message_content_footer;
        $message_html .= $this->message_text_footer;

        $message_html .= $this->message_element_footer . $this->message_footer . $this->message_main_footer;
        $this->email->message( $message_html);
        $this->email->set_mailtype('html');
        $this->email->send();
    }
}