<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint;

use Cra\MarketoApi\ClientInterface;

interface EndpointInterface
{
    public function __construct(ClientInterface $client);
}
