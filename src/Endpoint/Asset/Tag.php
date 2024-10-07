<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\Tag as Entity;
use Exception;

class Tag implements EndpointInterface
{
    use AssetTrait;

    /**
     * Get Tags.
     *
     * @param array{maxReturn?: ?int, offset?: ?int} $optional
     * @return Entity[]
     *
     * @throws Exception
     */
    public function browse(array $optional = []): array
    {
        $query = [];
        $this->addFieldsToQuery($query, ['maxReturn', 'offset'], $optional);

        $response = $this->get('/tagTypes.json', ['query' => $query]);
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $tag) => new Entity($tag), $response->result()) :
            [];
    }

    /**
     * Query Tags by name.
     *
     * @param string $name
     * @return Entity|null
     *
     * @throws Exception
     */
    public function queryByName(string $name): ?Entity
    {
        $query = ['name' => $name];

        $response = $this->get('/tagType/byName.json', ['query' => $query]);
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new Entity($result) : null;
    }
}
