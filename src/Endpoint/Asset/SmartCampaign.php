<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\SmartCampaign as Entity;
use Exception;

class SmartCampaign implements EndpointInterface
{
    use AssetTrait;

    /**
     * Query Smart Campaign by ID.
     *
     * @param string|int $id
     * @return Entity|null
     *
     * @throws Exception
     */
    public function queryById($id): ?Entity
    {
        $response = $this->get("/smartCampaign/$id.json");
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new Entity($result) : null;
    }

    /**
     * Query Smart Campaign by name.
     *
     * @param string $name
     * @return Entity|null
     *
     * @throws Exception
     */
    public function queryByName(string $name): ?Entity
    {
        $response = $this->get('/smartCampaign/byName.json', ['query' => ['name' => $name]]);
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new Entity($result) : null;
    }
}
