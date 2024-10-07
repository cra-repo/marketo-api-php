<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\FolderId;
use Cra\MarketoApi\Entity\Asset\SmartCampaign as Entity;
use DateTime;
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

    /**
     * Browse for Smart Campaigns.
     *
     * phpcs:ignore Generic.Files.LineLength.TooLong
     * @param array{earliestUpdatedAt?: DateTime, latestUpdatedAt?: DateTime, folder?: FolderId, maxReturn?: int, offset?: int, isArchive?: bool} $fields
     * @return Entity[]
     *
     * @throws Exception
     */
    public function browse(array $fields): array
    {
        $query = [];
        $this->addFieldsToQuery(
            $query,
            ['earliestUpdatedAt', 'latestUpdatedAt', 'folder', 'maxReturn', 'offset', 'isActive'],
            $fields
        );

        $response = $this->get('/smartCampaigns.json', ['query' => $query]);
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $folder) => new Entity($folder), $response->result()) :
            [];
    }

    /**
     * Create Smart Campaign.
     *
     * @param string $name
     * @param FolderId $folder
     * @param string $description
     * @return Entity
     *
     * @throws Exception
     */
    public function create(string $name, FolderId $folder, string $description = ''): Entity
    {
        $params = ['name' => $name, 'folder' => $folder->asJson()];
        if (!empty($description)) {
            $params['description'] = $description;
        }

        $response = $this->post('/smartCampaigns.json', ['form_params' => $params]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Entity($response->result(0));
    }

    /**
     * Update Smart Campaign.
     *
     * @param int $id
     * @param array{name?: string, description?: string} $fields
     * @return Entity
     * @throws Exception
     */
    public function update(int $id, array $fields): Entity
    {
        $params = [];
        $this->addFieldsToQuery($params, ['name', 'description'], $fields);

        $response = $this->post("/smartCampaign/$id.json", ['form_params' => $params]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Entity($response->result(0));
    }

    /**
     * Clone Smart Campaign.
     *
     * @param int $id
     * @param string $name
     * @param FolderId $folder
     * @param string $description
     * @return Entity
     * @throws Exception
     */
    public function clone(int $id, string $name, FolderId $folder, string $description = ''): Entity
    {
        $params = ['name' => $name, 'folder' => $folder->asJson()];
        if (!empty($description)) {
            $params['description'] = $description;
        }

        $response = $this->post("/smartCampaign/$id/clone.json", ['form_params' => $params]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Entity($response->result(0));
    }

    /**
     * Delete Smart Campaign.
     *
     * @param int $id
     * @return int
     * @throws Exception
     */
    public function delete(int $id): int
    {
        $response = $this->post("/smartCampaign/$id/delete.json");
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return $response->result(0)->id;
    }

    /**
     * Delete Smart Campaign.
     *
     * @param int $id
     * @return int
     * @throws Exception
     */
    public function activate(int $id): int
    {
        $response = $this->post("/smartCampaign/$id/activate.json");
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return $response->result(0)->id;
    }

    /**
     * Delete Smart Campaign.
     *
     * @param int $id
     * @return int
     * @throws Exception
     */
    public function deactivate(int $id): int
    {
        $response = $this->post("/smartCampaign/$id/deactivate.json");
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return $response->result(0)->id;
    }
}
