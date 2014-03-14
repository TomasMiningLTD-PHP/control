<?php
/*
* default Port to cgminer's API
* Will be used, if there is no port given for an IP
*/

$config['apiport'] = 4028;

/*
 * Commands to ask from miners
*/
$config['apicmds'] = array
(
    'version',
    'summary',
    'devs',
    'coin',
    'pools',
);

$config['mintemp'] = 60; //The "green temperature"
$config['maxtemp'] = 90; //The "red temperature" - miners shouldn't get there

/*
 * Fields to show in simple view
 * api command => array('results to display')
*/
$config['viewfields'] = array 
(                                    
    'version' => array
    (
        'CGMiner',
        'API'
    ),
    'config' => array
    (
        'GPU Count'
    ),
    'devs' => array
    (
        'Status',
        'Temperature',
        'Fan Percent',
        'Intensity',
        'Total MH',
        'MHS av',
        'MHS 5s',
        'Hardware Errors',
        'Network Difficulty'
    ),
    'coin' => array
    (
        'Hash Method',
        'Current Block Time',
        'LP',
        'Network Difficulty'
    ),
    'pools' => array
    (
        'POOL',
        'URL',
        'User',
        'Priority',
        'Status',
        'Accepted',
        'Rejected',
        'Stratum Active',
        'Last Share'
    ),
    'summary' => array
    (
        'Elapsed',
        'MHS av',
        'MHS 5s',
        'Accepted',
        'Rejected',
        'Hardware Errors',
        'Total MH',
        'Device Rejected%',
        'Pool Rejected%'
    )
);

/*
 * Fields to show in the abstract
 * api command => array('results to display')
*/
$config['abstractView'] = array
(
    'devs' => array
    (
        'Temperature',
        'Fan Percent',
        'MHS 5s',
        'Hardware Errors'
    ),
    
    'pools' => array
    (
        'URL',
        'User',
    )
);

/*
 * Rename keys for even shorter display
*/
$config['abstrmap'] = array
(
    'Temperature' =>  ' ',
    'Fan Percent' => 'Fan',
    'Hardware Errors' => 'HWErr',
    'Total MH' => 'MH/s',
    'URL' => 'Active',
    'User' => 'As'
);

/*
 * NOT USED ATM
 * fields to put into database
 * TODO: (auto)create tables if not exist?
*/
$config['dbFields'] = array           
(
    'POOL' => array
    (
        'url',
        'user',
        'accepted',
        'rejected',
        'status'
    ),
    'GPU' => array
    (
        'status',
        'temp',
        'fpercent',
        'mhav',
        'mh5s',
        'hwerrors',
        'totalmh'
    )
);

$config['dbmap'] = array
(
    'url' => 'url',
    'user' => 'user',
    'accepted' => 'accepted',
    'rejected' => 'rejected',
    'status' => 'status',
    'temperature' => 'temp',
    'fan percent' => 'fpercent',
    'mhs av' => 'mhav',
    'mhs 5s' => 'mh5s',
    'hardware errors' => 'hwerrors',
    'total mh' => 'totalmh'
);
/*
 * Tables to create in db
 *
*/
$config['tables'] = array
(
    'ipscfg',
    'fields',
    'commands',
    'abstractview'
);
?>
