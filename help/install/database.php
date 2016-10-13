<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * 
 * Edit this file and upate with your own MySQL database details.
*/

$db['default']['hostname'] = '%hostname%';
$db['default']['username'] = '%username%';
$db['default']['password'] = '%password%';
$db['default']['database'] = '%database%';



/** Do not change the below values. Change only if you know what you are doing */
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
$active_group = 'default';
$active_record = TRUE;
/* End of file database.php */
/* Location: ./application/config/database.php */