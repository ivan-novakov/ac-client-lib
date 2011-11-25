<?php
/**
 * This file is part of the AC PHP API Library.    
 *
 * The AC PHP API Library is free software: you can redistribute it and/or modify    
 * it under the terms of the GNU Lesser General Public License as published by    
 * the Free Software Foundation, either version 3 of the License, or    
 * (at your option) any later version.    
 * 
 * The AC PHP API Library is distributed in the hope that it will be useful,    
 * but WITHOUT ANY WARRANTY; without even the implied warranty of    
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the    
 * GNU Lesser General Public License for more details.    
 * 
 * You should have received a copy of the GNU Lesser General Public License    
 * along with the AC PHP API Library.  If not, see <http://www.gnu.org/licenses/>. 
 * 
 * @author Ivan Novakov <ivan.novakov@debug.cz>
 * @copyright Copyright (c) 2009 CESNET, z. s. p. o. (http://www.ces.net/)
 * @license LGPL (http://www.gnu.org/licenses/lgpl.txt)
 * 
 */

//First, we need to set the proper include paths to the Zend Framework and the AC PHP API library.
define('ZEND_FW_DIR', '/var/lib/php/zend/');
define('ACAPI_LIB_DIR', '/var/lib/php/acapi/');
set_include_path(ZEND_FW_DIR . PATH_SEPARATOR . ACAPI_LIB_DIR . PATH_SEPARATOR . get_include_path());

// The Zend autoload mechanisms initalization.
require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload('Zend_Loader');

// Client configuration
$acConfig = array(
    // Type of transport to be used
    'type' => 'xml', 
    // The URL of the remote AC API
    'uri' => 'https://aconnect.example.org/api/xml', 
    // The user for the connection
    'username' => 'acproxyuser', 
    // The user's password
    'password' => 'acproxypassword',
    // The name of the cookie holding the current session 
    'cookie' => 'BREEZESESSION',
    // Set logging to standard output, could be a file too
    'log' => 'php://output'
);

// Client initialization
$client = new AcApi_Client($acConfig);
// Login to the server API
$resp = $client->login();
if ($resp->isError()) {
    // If there is an error handle it somehow...
    printf("Error: code=%d, type='%s', message='%s'", $resp->getCode(), $resp->getType(), $resp->getMessage());
    // ...
}

// Perform a 'principal-list' remote call, searching for user 'martin'
$resp = $client->api_principalList(array(
    'filter-type' => 'user', 'filter-login' => 'martin'
));
if ($resp->isError()) {
    // If there is an error handle it somehow...
    // ...
}

// Extract response data as a SimpleXmlElement object, see http://www.php.net/manual/en/book.simplexml.php
$xmlElement = $resp->getXml();

?>