<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Cra\MarketoApi\Client;
use Cra\MarketoApi\Endpoint\Asset\Folder;
use Kint\Kint as K;

require_once '../vendor/autoload.php';

$config = json_decode(file_get_contents('../config.json'), true);
$client = (new Client($config))->authenticate();
$folderEndpoint = new Folder($client);

$parentFolder = $folderEndpoint->queryByName('My Newsletter Program');
K::dump($parentFolder);
$folder = $folderEndpoint->queryByName('Assets', ['root' => $parentFolder->folderId()]);
K::dump($folder);
