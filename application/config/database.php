<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
*/

$active_group = 'default';
$active_record = TRUE;

if (ENVIRONMENT == 'development') {
    $db['default']['hostname'] = 'localhost';
    $db['default']['username'] = 'root';
    $db['default']['password'] = 'root';
    $db['default']['database'] = 'fc2_test';
    $db['default']['dbdriver'] = 'mysql';
    $db['default']['dbprefix'] = '';
    $db['default']['pconnect'] = TRUE;
    $db['default']['db_debug'] = TRUE;
    $db['default']['cache_on'] = FALSE;
    $db['default']['cachedir'] = '';
    $db['default']['char_set'] = 'utf8';
    $db['default']['dbcollat'] = 'utf8_general_ci';
    $db['default']['swap_pre'] = '';
    $db['default']['autoinit'] = TRUE;
    $db['default']['stricton'] = FALSE;
} elseif (ENVIRONMENT == 'staging') {
    $db['default']['hostname'] = 'db697150336.db.1and1.com';
    $db['default']['username'] = 'dbo697150336';
    $db['default']['password'] = 'K7KUcYZVskFHsvGV';
    $db['default']['database'] = 'db697150336';
    $db['default']['dbdriver'] = 'mysql';
    $db['default']['dbprefix'] = '';
    $db['default']['pconnect'] = TRUE;
    $db['default']['db_debug'] = TRUE;
    $db['default']['cache_on'] = FALSE;
    $db['default']['cachedir'] = '';
    $db['default']['char_set'] = 'utf8';
    $db['default']['dbcollat'] = 'utf8_general_ci';
    $db['default']['swap_pre'] = '';
    $db['default']['autoinit'] = TRUE;
    $db['default']['stricton'] = FALSE;
}else {
    $db['default']['hostname'] = 'db579572168.db.1and1.com';
    $db['default']['username'] = 'dbo579572168';
    $db['default']['password'] = 'G080JfH30N658Yw';
    $db['default']['database'] = 'db579572168';
    $db['default']['dbdriver'] = 'mysql';
    $db['default']['dbprefix'] = '';
    $db['default']['pconnect'] = TRUE;
    $db['default']['db_debug'] = FALSE;
    $db['default']['cache_on'] = FALSE;
    $db['default']['cachedir'] = '';
    $db['default']['char_set'] = 'utf8';
    $db['default']['dbcollat'] = 'utf8_general_ci';
    $db['default']['swap_pre'] = '';
    $db['default']['autoinit'] = TRUE;
    $db['default']['stricton'] = FALSE;
}

/* CHANGE THIS TO TRUE TO CHECK FOR DATABASE ERRORS $db['default']['db_debug'] = TRUE; */
/* End of file database.php */
/* Location: ./application/config/database.php */
