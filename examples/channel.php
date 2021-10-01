<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Cra\MarketoApi\Client;
use Cra\MarketoApi\Endpoint\Asset\Channel;
use Kint\Kint as K;

require_once '../vendor/autoload.php';

$config = json_decode(file_get_contents('../config.json'), true);
$client = (new Client($config))->authenticate();
$channelEndpoint = new Channel($client);

$channels = $channelEndpoint->browse();
K::dump($channels);

$channels = $channelEndpoint->queryByName('Marketing');
K::dump($channels);
