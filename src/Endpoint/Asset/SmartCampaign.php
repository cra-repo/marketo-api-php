<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\ClientInterface;
use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\SmartCampaign as SmartCampaignEntity;
use Exception;

class SmartCampaign implements EndpointInterface
{
    private const PATH_PREFIX = '/asset/v1';

    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Query Smart Campaign by ID.
     *
     * @param string|int $id
     * @return SmartCampaignEntity|null
     *
     * @throws Exception
     */
    public function queryById($id): ?SmartCampaignEntity
    {
        $response = $this->client->get(self::PATH_PREFIX . "/smartCampaign/$id.json");
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            new SmartCampaignEntity($response->result()[0]) : null;
    }

    /**
     * Query Smart Campaign by name.
     *
     * @param string $name
     * @return SmartCampaignEntity|null
     *
     * @throws Exception
     */
    public function queryByName(string $name): ?SmartCampaignEntity
    {
        $response = $this->client->get(
            self::PATH_PREFIX . '/smartCampaign/byName.json',
            ['query' => ['name' => $name]]
        );
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            new SmartCampaignEntity($response->result()[0]) : null;
    }
}
