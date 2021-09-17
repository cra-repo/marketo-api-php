<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint;

use Cra\MarketoApi\ClientInterface;

interface EndpointInterface
{
    /**
     * Class constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client);
}
