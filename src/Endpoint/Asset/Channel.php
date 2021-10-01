<?php

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\Channel as Entity;
use Exception;

class Channel implements EndpointInterface
{
    use AssetTrait;

    /**
     * Browse Channels.
     *
     * @param array{maxReturn: int, offset: int} $optional
     * @return Entity[]
     *
     * @throws Exception
     */
    public function browse(array $optional = []): array
    {
        $query = [];
        $this->addFieldsToQuery($query, ['maxReturn', 'offset'], $optional);

        $response = $this->get('/channels.json', ['query' => $query]);
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn($channel) => new Entity($channel), $response->result()) :
            [];
    }

    /**
     * Query Channels by name.
     *
     * @param string $name
     * @param array{maxReturn: int, offset: int} $optional
     * @return Entity[]
     *
     * @throws Exception
     */
    public function queryByName(string $name, array $optional = []): array
    {
        $query = ['name' => $name];
        $this->addFieldsToQuery($query, ['maxReturn', 'offset'], $optional);

        $response = $this->get('/channel/byName.json', ['query' => $query]);
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn($channel) => new Entity($channel), $response->result()) :
            [];
    }
}
