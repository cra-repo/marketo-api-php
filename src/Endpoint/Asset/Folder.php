<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\Folder as Entity;
use Cra\MarketoApi\Entity\Asset\FolderId;
use Exception;

class Folder implements EndpointInterface
{
    use AssetTrait;

    /**
     * Query Folder by ID.
     *
     * @param int $id
     * @return Entity|null
     *
     * @throws Exception
     */
    public function queryById(int $id): ?Entity
    {
        $response = $this->get("/folder/$id.json");
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new Entity($result) : null;
    }

    /**
     * Query Folder by name.
     *
     * @param string $name
     * @param array{type: string, root: int, workSpace: string} $optional
     * @return Entity|null
     *
     * @throws Exception
     * @link https://developers.marketo.com/rest-api/endpoint-reference/asset-endpoint-reference/#!/Folders/getFolderByNameUsingGET
     */
    public function queryByName(string $name, array $optional = []): ?Entity
    {
        $query = ['name' => $name];
        foreach (['type', 'root', 'workSpace'] as $field) {
            if (isset($optional[$field])) {
                $query[$field] = $optional[$field];
            }
        }
        $response = $this->get('/folder/byName.json', ['query' => $query]);
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new Entity($result) : null;
    }

    /**
     * Browse Folder for children Folders.
     *
     * @param FolderId $parent
     * @return Entity[]
     *
     * @throws Exception
     */
    public function browse(FolderId $parent): array
    {
        $response = $this->get('/folders.json', ['query' => ['root' => $parent->asJson()]]);
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $folder) => new Entity($folder), $response->result()) :
            [];
    }

    /**
     * Create Folder.
     *
     * @param string $name
     * @param FolderId $parent
     * @param string $description
     *
     * @return Entity
     * @throws Exception
     */
    public function create(string $name, FolderId $parent, string $description = ''): Entity
    {
        $fields = [
            'parent' => $parent->asJson(),
            'name' => $name,
        ];
        if (!empty($description)) {
            $fields['description'] = $description;
        }

        $response = $this->post('/folders.json', ['form_params' => $fields]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Entity($response->result(0));
    }

    /**
     * Update Folder.
     *
     * @param int $id
     * @param string $type
     * @param string $name
     * @param string $description
     * @param bool|null $isArchive
     * @return Entity
     *
     * @throws Exception
     */
    public function update(
        int $id,
        string $type = 'Folder',
        string $name = '',
        string $description = '',
        ?bool $isArchive = null
    ): Entity {
        $fields = ['type' => $type];
        if (!empty($name)) {
            $fields['name'] = $name;
        }
        if (!empty($description)) {
            $fields['description'] = $description;
        }
        if (isset($isArchive)) {
            $fields['isArchive'] = $isArchive;
        }

        $response = $this->post("/folder/$id.json", ['form_params' => $fields]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Entity($response->result(0));
    }

    /**
     * Delete Folder.
     *
     * @param int $id
     * @return int
     *
     * @throws Exception
     */
    public function delete(int $id): int
    {
        $response = $this->post("/folder/$id/delete.json");
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return $response->result(0)->id;
    }
}
