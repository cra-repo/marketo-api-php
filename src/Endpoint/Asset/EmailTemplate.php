<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\EmailTemplate as Entity;
use Exception;

class EmailTemplate implements EndpointInterface
{
    use AssetTrait;

    /**
     * Query Email Template by ID.
     *
     * @param int $id
     * @return Entity|null
     * @throws Exception
     */
    public function queryById(int $id): ?Entity
    {
        $response = $this->get("/emailTemplate/$id.json");
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new Entity($result) : null;
    }

    /**
     * Query Email Template by name.
     *
     * @param string $name
     * @return Entity|null
     * @throws Exception
     */
    public function queryByName(string $name): ?Entity
    {
        $response = $this->get('/emailTemplate/byName.json', ['query' => ['name' => $name]]);
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new Entity($result) : null;
    }

    /**
     * Browse all Email Templates.
     *
     * @return Entity[]
     * @throws Exception
     */
    public function browse(): array
    {
        $response = $this->get('/emailTemplates.json');
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $template) => new Entity($template), $response->result()) :
            [];
    }
}
