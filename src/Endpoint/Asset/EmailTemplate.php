<?php

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\EmailTemplate as EmailTemplateEntity;
use Exception;

class EmailTemplate implements EndpointInterface
{
    use AssetTrait;

    /**
     * Query Email Template by ID.
     *
     * @param int $id
     * @return EmailTemplateEntity|null
     * @throws Exception
     */
    public function queryById(int $id): ?EmailTemplateEntity
    {
        $response = $this->get("/emailTemplate/$id.json");
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new EmailTemplateEntity($result) : null;
    }

    /**
     * Query Email Template by name.
     *
     * @param string $name
     * @return EmailTemplateEntity|null
     * @throws Exception
     */
    public function queryByName(string $name): ?EmailTemplateEntity
    {
        $response = $this->get('/emailTemplate/byName.json', ['query' => ['name' => $name]]);
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new EmailTemplateEntity($result) : null;
    }

    /**
     * Browse all Email Templates.
     *
     * @return EmailTemplateEntity[]
     * @throws Exception
     */
    public function browse(): array
    {
        $response = $this->get('/emailTemplates.json');
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $template) => new EmailTemplateEntity($template), $response->result()) :
            [];
    }
}
