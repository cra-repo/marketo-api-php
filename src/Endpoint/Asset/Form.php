<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\Form as Entity;
use Exception;

/**
 * Marketo REST API Asset endpoint class for Forms.
 *
 * @link https://experienceleague.adobe.com/en/docs/marketo-developer/marketo/rest/assets/forms
 */
class Form implements EndpointInterface
{
    use AssetTrait;

    /**
     * Query Form by ID.
     *
     * @param int $id
     * @return Entity|null
     * @throws Exception
     */
    public function queryById(int $id): ?Entity
    {
        $response = $this->get("/form/$id.json");
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new Entity($result) : null;
    }
}
