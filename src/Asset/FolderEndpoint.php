<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Asset;

use Cra\MarketoApi\ClientInterface;
use Cra\MarketoApi\EndpointInterface;
use Exception;

class FolderEndpoint implements EndpointInterface
{
    private const PATH_PREFIX = '/asset/v1';

    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Query Folder by ID.
     *
     * @param int $id
     * @return object|null
     *
     * @throws Exception
     */
    public function queryById(int $id): ?Folder
    {
        $response = $this->client
            ->ensureTokenValid()
            ->get(self::PATH_PREFIX . "/folder/$id.json");
        $response->checkIsSuccess();

        return $response->isResultValid() ? new Folder($response->result()[0]) : null;
    }

    /**
     * Query Folder by name.
     *
     * @param string $name
     * @return object|null
     *
     * @throws Exception
     */
    public function queryByName(string $name): ?Folder
    {
        $response = $this->client
            ->ensureTokenValid()
            ->get(
                self::PATH_PREFIX . '/folder/byName.json',
                ['query' => ['name' => $name]]
            );
        $response->checkIsSuccess();

        return $response->isResultValid() ? new Folder($response->result()[0]) : null;
    }

    /**
     * Browse Folder for children Folders.
     *
     * @param FolderId $parent
     * @return Folder[]
     *
     * @throws Exception
     */
    public function browse(FolderId $parent): array
    {
        $response = $this->client
            ->ensureTokenValid()
            ->get(
                self::PATH_PREFIX . '/folders.json',
                ['query' => ['root' => $parent->asJson()]]
            );
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $folder) => new Folder($folder), $response->result()) :
            [];
    }

    /**
     * Create Folder.
     *
     * @param string $name
     * @param FolderId $parent
     * @param string $description
     *
     * @return Folder
     * @throws Exception
     */
    public function create(string $name, FolderId $parent, string $description = ''): Folder
    {
        $fields = [
            'parent' => $parent->asJson(),
            'name' => $name,
        ];
        if (!empty($description)) {
            $fields['description'] = $description;
        }

        $response = $this->client
            ->ensureTokenValid()
            ->post(
                self::PATH_PREFIX . '/folders.json',
                ['form_params' => $fields]
            );
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Folder($response->result()[0]);
    }

    /**
     * Update Folder.
     *
     * @param int $id
     * @param string $type
     * @param string $name
     * @param string $description
     * @param bool|null $isArchive
     * @return Folder
     *
     * @throws Exception
     */
    public function update(
        int $id,
        string $type = 'Folder',
        string $name = '',
        string $description = '',
        ?bool $isArchive = null
    ): Folder {
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

        $response = $this->client
            ->ensureTokenValid()
            ->post(
                self::PATH_PREFIX . "/folder/$id.json",
                ['form_params' => $fields]
            );
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Folder($response->result()[0]);
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
        $response = $this->client
            ->ensureTokenValid()
            ->post(self::PATH_PREFIX . "/folder/$id/delete.json");
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return $response->result()[0]->id;
    }
}
