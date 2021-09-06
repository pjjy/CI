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

$route['accounting/home'] 														= 'Accounting_contrl/home';
$route['accounting/habitat'] 													= 'Accounting_contrl/habitat_contrl';

$route['accounting/modal_employeee_monitoring_r'] 								= 'Accounting_contrl/modal_employeee_monitoring_contrl';
$route['accounting/hb_modal_employeee_monitoring_r'] 							= 'Accounting_contrl/hb_modal_employeee_monitoring_contrl';

$route['accounting/modal_unaudited_r'] 											= 'Accounting_contrl/modal_unaudited_contrl';
$route['accounting/modal_unaudited_habitat_r'] 									= 'Accounting_contrl/modal_unaudited_habitat_contrl';
$route['printing/payroll_list'] 												= 'Accounting_contrl/payroll_list';

$route['accounting/report_unaudited_all_r/(:any)'] 								= 'Accounting_contrl/report_unaudited_all_contrl/$1';
$route['accounting/report_unaudited_bypcc_r'] 									= 'Accounting_contrl/report_unaudited_bypcc_contrl';
$route['accounting/mhhp_summary/(:any)/(:any)/(:any)'] 							= 'Accounting_contrl/deduction_summary/$1/$2/$3';
// $route['accounting/mhhp_summary/(:any)'] 												= 'Accounting_contrl/deduction_summary/s1';



$route['accounting/report_slip_all_r'] = 'Accounting_contrl/report_slip_all_contrl';
$route['accounting/report_slip_bypcc_r'] = 'Accounting_contrl/report_slip_bypcc_contrl';


$route['accounting/hb_report_all_r'] = 'Accounting_contrl/hb_report_all_contrl';
$route['accounting/hb_report_bypcc_r'] = 'Accounting_contrl/hb_report_bypcc_contrl';
$route['accounting/hb_slip_all_r'] = 'Accounting_contrl/hb_slip_all_contrl';
$route['accounting/hb_slip_bypcc_r'] = 'Accounting_contrl/hb_slip_bypcc_contrl';


$route['balance_entry/bal_entry_mh_r'] = 'Balance_entry_contrl/bal_mh_entry_contrl';
$route['balance_entry/search_emp_mh_r'] = 'Balance_entry_contrl/search_mh_emp_contrl';
$route['balance_entry/insert_emp_mh_r'] = 'Balance_entry_contrl/insert_mh_emp_contrl';


$route['balance_entry/bal_entry_hb_r'] = 'Balance_entry_contrl/bal_entry_hb_contrl';
$route['balance_entry/search_emp_hb_r'] = 'Balance_entry_contrl/search_hb_emp_contrl';
$route['balance_entry/insert_emp_hb_r'] = 'Balance_entry_contrl/insert_hb_emp_contrl';


$route['accounting/mh_walkinpay_r'] = 'Accounting_contrl/mh_walkinpay_contrl';
$route['accounting/mh_sub_walkinpay_r'] = 'Accounting_contrl/mh_sub_walkinpay_contrl';


$route['accounting/hb_walkinpay_r'] = 'Accounting_contrl/hb_walkinpay_contrl';
$route['accounting/hb_sub_walkinpay_r'] = 'Accounting_contrl/hb_sub_walkinpay_contrl';


$route['accounting/mh_chart_r'] = 'Accounting_contrl/mh_chart_contrl';



