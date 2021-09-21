<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\ClientInterface;
use Cra\MarketoApi\Response;

trait AssetTrait
{
    protected ClientInterface $client;

    /**
     * Class constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Do GET Asset API request.
     *
     * @param string $uri URI relative from /rest/asset/v1
     * @param array $options
     *
     * @return Response
     */
    public function get(string $uri, array $options = []): Response
    {
        return $this->client
            ->ensureTokenValid()
            ->get("/asset/v1$uri", $options);
    }

    /**
     * Do POST Asset API request.
     *
     * @param string $uri URI relative from /rest/asset/v1
     * @param array $options
     *
     * @return Response
     */
    public function post(string $uri, array $options = []): Response
    {
        return $this->client
            ->ensureTokenValid()
            ->post("/asset/v1$uri", $options);
    }
}
