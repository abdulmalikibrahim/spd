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
$route['default_controller'] = 'Main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['spd_all'] = 'Service_Part/all';
$route['spd_remain'] = 'Service_Part/remain';
$route['spd_close'] = 'Service_Part/close';
$route['already_process'] = 'Service_Part/already_process';
$route['create_wos'] = 'Service_Part/create_wos';
$route['print_wos'] = 'Service_Part/print_wos';
$route['print_kanban_asi'] = 'Service_Part/print_kanban_asi';
$route['print_kanban_tmi'] = 'Service_Part/print_kanban_tmi';
$route['closing_planning'] = 'Service_Part/closing_planning';

$route['scan_process'] = 'Process/scan_process';
$route['scan_processing'] = 'Process/scan_processing';
$route['cancel_process'] = 'Process/cancel_process';
$route['cancel_processing'] = 'Process/cancel_processing';
$route['scan_out'] = 'Process/scan_out';
$route['update_scan_out'] = 'Process/update_scan_out';
$route['print_kanban'] = 'Process/print_kanban';
$route['failed_scan_out'] = 'Process/failed_scan_out';
$route['cancel_scan_out'] = 'Process/cancel_scan_out';

$route['planning_spd'] = 'Planning/planning_spd';
