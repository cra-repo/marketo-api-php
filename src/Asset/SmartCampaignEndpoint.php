<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Asset;

use Cra\MarketoApi\Client;
use Cra\MarketoApi\EndpointInterface;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class SmartCampaignEndpoint implements EndpointInterface
{
    private const PATH_PREFIX = '/asset/v1';

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Query Smart Campaign by ID.
     *
     * @param string|int $id
     * @return object|null
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function queryById($id): ?object
    {
        $response = $this->client->get(self::PATH_PREFIX . "/smartCampaign/$id.json");
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            new SmartCampaign($response->result()[0]) : null;
    }

    /**
     * Query Smart Campaign by name.
     *
     * @param string $name
     * @return object|null
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function queryByName(string $name): ?object
    {
        $response = $this->client->get(
            self::PATH_PREFIX . '/smartCampaign/byName.json',
            ['query' => ['name' => $name]]
        );
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            new SmartCampaign($response->result()[0]) : null;
    }
}
