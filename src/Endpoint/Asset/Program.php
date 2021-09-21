<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\Program as Entity;
use Exception;

class Program implements EndpointInterface
{
    use AssetTrait;

    /**
     * Query Program by ID.
     *
     * @param int $id
     * @return Entity|null
     * @throws Exception
     */
    public function queryById(int $id): ?Entity
    {
        $response = $this->get("/program/$id.json");
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            new Entity($response->result(0)) :
            null;
    }

    /**
     * Query Program by name.
     *
     * @param string $name
     * @param bool $includeTags
     * @param bool $includeCosts
     * @return Entity|null
     *
     * @throws Exception
     */
    public function queryByName(string $name, bool $includeTags = false, bool $includeCosts = false): ?Entity
    {
        $query = ['name' => $name];
        if ($includeTags) {
            $query['includeTags'] = true;
        }
        if ($includeCosts) {
            $query['includeCosts'] = true;
        }
        $response = $this->get("/program/byName.json", ['query' => $query]);
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            new Entity($response->result(0)) :
            null;
    }
}
