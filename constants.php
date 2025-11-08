<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);
/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */

defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
// defined('MST_PWD', 'P@ssw0rd');
define('MST_PWD', 'P@th@shr33');
define('DEFAULT_PWD', 'password');
################## DIVISION LEVEL ########################
define('STATE_ID', 1);
define('STATE_LEVEL', 1);
define('DISTRICT_LEVEL', 2);
define('BLOCK_LEVEL', 3);
define('PANCHYAT_LEVEL', 4);
##########################################################
############ COMMON ##############################
define('DIVISION', 'division');
define('LOGS_CALL', 'logs_call');
define('LOGS_TYPE', 'logs_type');
define('DESIGNATION', 'designation');
define('MONITORING', 'monitoring');
define('PCR', 'pcr');
define('PROGRESS', 'progress');
define('ROAD', 'road');
define('ROAD_TRACKING', 'road_tracking');
define('PROJECT_HD', 'project_hd');
define('PROJECT_DT', 'project_dt');
define('AGENCY', 'agency');
define('TENDER_ORGANIZER', 'tender_organizer');
define('SQM', 'sqm_user_details');
define('ADMIN_MENU', 'admin_menu');
define('ADMIN_USER_MENU', 'admin_user_menu');
define('ADMIN_MENU_ROLE', 'admin_menu_role');
define('ASSIGNMENT', 'assignment');
// define('ROLE', 'role');
define('ADMIN_USER', 'admin_user');
define('ADMIN_USER_LOGIN', 'admin_user_login');
define('DEPARTMENT', 'department');
define('SCHEDULE', 'schedule');
//define('CATEGORY', 'category');
define('REPORT_HD', 'report_hd');
define('REPORT_MONITORING', 'report_monitoring');
define('REPORT_PROGRESS', 'report_progress');
define('CATEGORY', 'category');
define('CONSTITUTION', 'constitution');
define('PROJECT_TYPE', 'project_type');
define('UNIT', 'unit');
define('PROJECT_NIT', 'project_nit');
define('PROJECT_WO', 'project_wo');
define('PROJECT_STAGE', 'project_stage');
define('INSPECTION', 'project_inspection');
define('PROJECT_PROGRESS', 'project_progress');
define('SRRP', 'srrp');
define('SURVEY_LOG', 'srrp_survey_log');
define('NOT_IMPLEMENTED_LOG', 'scheme_not_implemented');
// tender log
define('TENDER_LOG', 'tender_log');
define('SRRP_WO', 'srrp_wo');
define('SRRP_PROGRESS', 'srrp_progress');

##########################################################
############ COMMON ##############################



###################################################
######################## USER AUTH ######################################
define('LOGIN', 'user_login');
define('LOGIN_LOG', 'login_log');
// define('USER_LOGIN', 'um_user_login');
define('ROLE', 'role');
define('USER', 'user');
define('MENU', 'menu');
define('MENU_ROLE', 'menu_role');
//define('LEVEL', 'um_level');
//define('USER_MENU', 'um_user_menu');
//define('USER_LEVEL', 'um_user_level');
//define('USER_LEVEL_FARM', 6);

/*
  |--------------------------------------------------------------------------
  | Web services
  |--------------------------------------------------------------------------
  |
 */

define('WS_LOGIN', '100');
define('WS_PROJECT', '110');
define('WS_SRRP_LIST', '111');
define('WS_DIVISION', '112');
define('WS_WORK_STAGE_LIST', '120');
define('WS_SRRP_SCHEME_COUNT', '121');  // 08_03_2023 smk
define('WS_SRRP_WO_DETAILS', '122');  // 11_03_2023 smk
define('WS_SAVE', '301');
define('WS_PROGRESS_SAVE', '302');
define('WS_SAVE_SRRP', '304');
define('WS_APPROVED_PROGRESS_SAVE', '303');
define('WS_LAST_INSPECTION_LIST', '503');
define('WS_PROGRESS_APPROVAL_LIST', '504');
define('WS_CHANGE_PASSWORD', '101');
//Document upload
//
define('WS_UPLOAD_PDF', '401');
define('WS_UPLOAD_IMAGE', '402');
