<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* 
| -------------------------------------------------------------------------
| Backend
| -------------------------------------------------------------------------
*/

$method = $_SERVER['REQUEST_METHOD'];

// Login & Signup
if ($method == 'GET' || $method == 'POST') {
    $route['login'] = 'auth';
    $route['signup'] = 'auth/signup';
    $route['forgot'] = 'auth/forgotPassword';
    $route['change_password'] = 'auth/changeAdminPassword';
}
if ($method == 'GET') {
    $route['logout'] = 'auth/logout';
    $route['active/(:any)'] = 'auth/active/$1';
    $route['change/(:any)'] = 'auth/changePassword/$1';
}

// Admin
if ($method == 'GET') {
    $route['dashboard'] = 'admin';
    // Admin Account Management
    $route['admins'] = 'admin/getAllAdmins';
    // Driver Account Management
    $route['drivers'] = 'admin/getAllDrivers';
    // User Account Management
    $route['users'] = 'admin/getAllUsers';
    
    $route['users/active/(:any)'] = 'admin/activeUser/$1';
    $route['users/disable/(:any)'] = 'admin/disableUser/$1';
    $route['users/delete/(:any)'] = 'admin/deleteUser/$1';
}

/* 
| -------------------------------------------------------------------------
| APP API
| -------------------------------------------------------------------------
*/

if ($method == 'POST') {
    $route['api/signup'] = 'api/signup';
    $route['api/login'] = 'api/login';
    $route['api/logout'] = 'api/logout';

    //$route['api/email_verify_code'] = 'api/emailVerifyCode';
    $route['api/email_verify'] = 'api/emailVerify';
    $route['api/update_position'] = 'api/updatePosition';
    $route['api/sms_verify_code'] = 'api/smsVerifyCode';
    $route['api/sms_verify'] = 'api/smsVerify';
    $route['api/change_email_code'] = 'api/changeEmailCode';
    $route['api/change_email'] = 'api/changeEmail';
    $route['api/change_sms_code'] = 'api/changeSmsCode';
    $route['api/change_sms'] = 'api/changeSms';
    
    $route['api/forgot_password'] = 'api/forgotPassword';
    $route['api/change_password'] = 'api/changePassword';
}