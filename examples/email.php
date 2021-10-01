<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Cra\MarketoApi\Client;
use Cra\MarketoApi\Endpoint\Asset\Email;
use Cra\MarketoApi\Endpoint\Asset\Folder;
use Kint\Kint as K;

require_once '../vendor/autoload.php';

$config = json_decode(file_get_contents('../config.json'), true);
$client = (new Client($config))->authenticate();
$emailEndpoint = new Email($client);
$folderEndpoint = new Folder($client);

$email = $emailEndpoint->queryByName('Monthly News 1990-12');
K::dump($email);

$folder = $folderEndpoint->queryByName('_Dev');
$emails = $emailEndpoint->browse(['folder' => $folder->folderId()]);
K::dump($emails);
