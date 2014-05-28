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

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/_config.php';

// Client initialization
$client = new AcApi_Client($acConfig);
// Login to the server API
$resp = $client->login();

if ($resp->isError()) {
    // If there is an error handle it somehow...
    $error = $resp->getError();
    printf("Error: code=%d, type='%s', message='%s'\n", $error->getCode(), $error->getType(), $error->getMessage());
    exit(10);
}

// Perform a 'principal-list' remote call, searching for user 'martin'
$resp = $client->api_principalList(array(
    'filter-type' => 'user',
    'filter-login' => 'novakov@cesnet.cz'
));

if ($resp->isError()) {
    // If there is an error handle it somehow...
    $error = $resp->getError();
    printf("Error: code=%d, type='%s', message='%s'\n", $error->getCode(), $error->getType(), $error->getMessage());
    exit(20);
}

// Extract response data as a SimpleXmlElement object, see http://www.php.net/manual/en/book.simplexml.php
$xmlElement = $resp->getXml();
print_r($xmlElement);
