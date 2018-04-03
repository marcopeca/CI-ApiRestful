<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define("DB1_GROUP",                             "db1");
define("DB2_GROUP",                             "db2");

define("AUTH_REQUEST",                          "Authorization");
define("PAG_LIMIT",                             20);
define("FLAG_CHART_SBD",                        3);

// Return State
define("STATE_OK",                              200);
define("STATE_NOT_OK",                          400);
define("STATE_NOT_FOUND",                       404);
define("STATE_FORBIDDEN",                       403);
define("USERNAME_NOT_USABLE",                   409);

define('PATH_CSS',                              '/themes/css');
define('PATH_JS',                               '/themes/js');
define('PATH_IMG',                              '/themes/img');

/* End of file constants.php */
/* Location: ./application/config/constants.php */
