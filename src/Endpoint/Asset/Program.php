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

    /**
     * Browse Programs.
     *
     * The optional status parameter allows you to filter on program status.
     * This parameter only applies to Engagement and Email programs.
     * The possible values are “on” and “off” for Engagement programs, and “unlocked” for Email programs.
     *
     * The optional maxReturn parameter controls the number of programs to return (maximum is 200, default is 20).
     * The optional offset parameter used for paging results (default is 0).
     *
     * @param array{status: string, maxReturn: int, offset: int} $optional
     * @return Entity[]
     *
     * @throws Exception
     */
    public function browse(array $optional = []): array
    {
        $query = [];
        if (isset($optional['status'])) {
            $query['status'] = $optional['status'];
        }
        $response = $this->get('/programs.json', ['query' => $query]);
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $program) => new Entity($program), $response->result()) :
            [];
    }
}
