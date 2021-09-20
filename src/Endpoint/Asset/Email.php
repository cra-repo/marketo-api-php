<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\Email as EmailEntity;
use Cra\MarketoApi\Entity\Asset\FolderId;
use Exception;

class Email implements EndpointInterface
{
    use AssetTrait;

    /**
     * Query Email by ID.
     *
     * @param int $id
     * @return EmailEntity|null
     * @throws Exception
     */
    public function queryById(int $id): ?EmailEntity
    {
        $response = $this->get("/email/$id.json");
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new EmailEntity($result) : null;
    }

    /**
     * Query Email by name.
     *
     * @param string $name
     * @param FolderId|null $folder Optionally filter results by specific Folder.
     * @return EmailEntity|null
     *
     * @throws Exception
     */
    public function queryByName(string $name, ?FolderId $folder = null): ?EmailEntity
    {
        $query = ['name' => $name];
        if ($folder) {
            $query['folder'] = $folder->asJson();
        }
        $response = $this->get('/email/byName.json', ['query' => $query]);
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new EmailEntity($result) : null;
    }

    /**
     * Browse Folder for children Folders.
     *
     * @param array{status: string, folder: FolderId, maxReturn: int, offset: int} $filters
     * @return EmailEntity[]
     *
     * @throws Exception
     */
    public function browse(array $filters = []): array
    {
        $query = [];
        if (!empty($filters['status'])) {
            $query['status'] = $filters['status'];
        }
        if (!empty($filters['folder'])) {
            $query['folder'] = $filters['folder']->asJson();
        }
        if (!empty($filters['maxReturn'])) {
            $query['maxReturn'] = $filters['maxReturn'];
        }
        if (!empty($filters['offset'])) {
            $query['offset'] = $filters['offset'];
        }
        $response = $this->get('/emails.json', ['query' => $query]);
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $folder) => new EmailEntity($folder), $response->result()) :
            [];
    }
}
