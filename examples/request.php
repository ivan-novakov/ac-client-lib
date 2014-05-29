<?php
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
    'filter-login' => 'some.user@example.org'
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
