<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Cra\MarketoApi\Client;
use Cra\MarketoApi\Endpoint\Asset\Tag;
use Kint\Kint as K;

require_once '../vendor/autoload.php';

$config = json_decode(file_get_contents('../config.json'), true);
$client = (new Client($config))->authenticate();
$tagEndpoint = new Tag($client);

$tags = $tagEndpoint->browse();
K::dump($tags);

$tags = $tagEndpoint->queryByName('Marketing');
K::dump($tags);
