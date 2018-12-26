<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://smtp.hostinger.com';
$config['smtp_port'] = 465;
$config['smtp_user'] = 'info@dogout.co';
$config['smtp_pass'] = '';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset'] = 'utf-8';
$config['mailtype'] = 'html';
$config['wordwrap'] = TRUE;