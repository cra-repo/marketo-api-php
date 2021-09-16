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
        $response = $this->client->request(
            'GET',
            self::PATH_PREFIX . "/smartCampaign/$id.json"
        );
        if (!is_object($response) || empty($response->success)) {
            throw new Exception(
                sprintf(
                    'Error querying by ID. Errors: %s',
                    var_export($response->errors ?? [])
                )
            );
        }

        return !empty($response->result) && is_array($response->result) ?
            new SmartCampaign($response->result[0]) : null;
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
        $response = $this->client->request(
            'GET',
            self::PATH_PREFIX . '/smartCampaign/byName.json',
            ['query' => ['name' => $name]]
        );
        if (!is_object($response) || empty($response->success)) {
            throw new Exception(
                sprintf(
                    'Error querying by ID. Errors: %s',
                    var_export($response->errors ?? [])
                )
            );
        }

        return !empty($response->result) && is_array($response->result) ?
            new SmartCampaign($response->result[0]) : null;
    }
}
