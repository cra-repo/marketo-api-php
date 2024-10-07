<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\FolderId;
use Cra\MarketoApi\Entity\Asset\Program as Entity;
use Cra\MarketoApi\Entity\Asset\Tag;
use DateTime;
use Exception;
use InvalidArgumentException;

/**
 * Marketo REST API Asset endpoint class for Programs.
 *
 * @link https://developers.marketo.com/rest-api/assets/programs/
 */
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
        $result = $response->singleValidResult();

        return $result ? new Entity($result) : null;
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
        $result = $response->singleValidResult();

        return $result ? new Entity($result) : null;
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
     * The earliestUpdatedAt and latestUpdatedAt parameters allow you to set low and high datetime watermarks
     * for returning programs which were either updated or initially created within the given range.
     *
     * phpcs:ignore Generic.Files.LineLength.TooLong
     * @param array{status?: ?string, maxReturn?: ?int, offset?: ?int, earliestUpdatedAt?: ?DateTime, latestUpdatedAt?: ?DateTime} $optional
     * @return Entity[]
     *
     * @throws Exception
     */
    public function browse(array $optional = []): array
    {
        $query = [];
        $this->addFieldsToQuery(
            $query,
            ['status', 'maxReturn', 'offset', 'earliestUpdatedAt', 'latestUpdatedAt'],
            $optional
        );

        $response = $this->get('/programs.json', ['query' => $query]);
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $program) => new Entity($program), $response->result()) :
            [];
    }

    /**
     * Query Programs by Tag.
     *
     * @param Tag $tag
     * @param array{maxReturn?: ?int, offset?: ?int} $optional
     * @return Entity[]
     *
     * @throws Exception
     */
    public function queryByTag(Tag $tag, array $optional = []): array
    {
        $query = ['tagType' => $tag->tagType(), 'tagValue' => $tag->tagValue()];
        $this->addFieldsToQuery($query, ['maxReturn', 'offset'], $optional);

        $response = $this->get('/program/byTag.json', ['query' => $query]);
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $program) => new Entity($program), $response->result()) :
            [];
    }

    /**
     * Create Program.
     *
     * @param FolderId $folder
     * @param string $name
     * @param string $type
     * @param string $channel
     * @param array{description?: ?string, costs?: ?array, tags?: ?array} $optional
     * @return Entity
     *
     * @throws Exception
     */
    public function create(FolderId $folder, string $name, string $type, string $channel, array $optional = []): Entity
    {
        $params = [
            'folder' => $folder->asJson(),
            'name' => $name,
            'type' => $type,
            'channel' => $channel,
        ];
        $this->addFieldsToQuery($params, ['description', 'costs', 'tags'], $optional);

        $response = $this->post('/programs.json', ['form_params' => $params]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Entity($response->result(0));
    }

    /**
     * Updates the target program's metadata. Required Permissions: Read-Write Assets
     * @link https://developers.marketo.com/rest-api/endpoint-reference/asset-endpoint-reference/#!/Programs/updateProgramUsingPOST
     *
     * Note: When updating program costs, to append new costs, simply add them to your costs array.
     * To perform a destructive update, pass your new costs, along with the parameter
     * costsDestructiveUpdate set to true. To clear all costs from a program, do not pass
     * a `costs` parameter, and just pass costsDestructiveUpdate set to true.
     *
     * @param int $id
     * phpcs:ignore Generic.Files.LineLength.TooLong
     * @param array{description?: ?string, name?: ?string, costs?: ?array, tags?: ?array, costsDestructiveUpdate?: ?bool, startDate?: ?string} $fields
     * @return Entity
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function update(int $id, array $fields = []): Entity
    {
        $params = [];
        $this->addFieldsToQuery(
            $params,
            ['name', 'description', 'costsDestructiveUpdate', 'costs', 'tags', 'startDate'],
            $fields
        );
        if (empty($params)) {
            throw new InvalidArgumentException('Missing fields to update.');
        }

        $response = $this->post("/program/$id.json", ['form_params' => $params]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Entity($response->result(0));
    }

    /**
     * Approves the target program. Only valid for unapproved email programs. Required Permissions: Read-Write Assets
     * @link https://developers.marketo.com/rest-api/endpoint-reference/asset-endpoint-reference/#!/Programs/approveProgramUsingPOST
     *
     * Email Programs may be approved or unapproved remotely, which will cause
     * the program to run at the given startDate and conclude at the given endDate.
     * Both of these must be set to approve the program, and having a valid
     * and approved email and smart list configured via the UI.
     *
     * @param int $id Program ID
     * @return int Program ID
     *
     * @throws Exception
     */
    public function approve(int $id): int
    {
        $response = $this->post("/program/$id/approve.json");
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return $response->result(0)->id;
    }

    /**
     * Unapproves the target program. Only valid for approved email programs. Required Permissions: Read-Write Assets
     * @link https://developers.marketo.com/rest-api/endpoint-reference/asset-endpoint-reference/#!/Programs/unapproveProgramUsingPOST
     *
     * Email Programs may be approved or unapproved remotely, which will cause
     * the program to run at the given startDate and conclude at the given endDate.
     * Both of these must be set to approve the program, and having a valid
     * and approved email and smart list configured via the UI.
     *
     * @param int $id Program ID
     * @return int Program ID
     *
     * @throws Exception
     */
    public function unapprove(int $id): int
    {
        $response = $this->post("/program/$id/unapprove.json");
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return $response->result(0)->id;
    }

    /**
     * Clone Program.
     *
     * The name parameter must be globally unique and cannot exceed 255 characters.
     * The folder parameter is the parent folder. The folder parameter type
     * attribute must be set to “Folder”, and the target folder must be in same
     * workspace as program that is being cloned.
     *
     * Note: Programs containing certain types of assets may not be cloned via this API,
     * including Push Notifications, In-App Messages, Reports, and Social Assets.
     * In-App programs may not be cloned via this API.
     *
     * @param int $id
     * @param FolderId $folder
     * @param string $name
     * @param string|null $description
     * @return Entity
     *
     * @throws Exception
     */
    public function clone(int $id, FolderId $folder, string $name, ?string $description = null): Entity
    {
        $params = [
            'folder' => $folder->asJson(),
            'name' => $name,
        ];
        if (isset($description)) {
            $params['description'] = $description;
        }

        $response = $this->post("/program/$id/clone.json", ['form_params' => $params]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Entity($response->result(0));
    }

    /**
     * Delete Program.
     *
     * @param int $id Program ID
     * @return int Program ID
     * @throws Exception
     */
    public function delete(int $id): int
    {
        $response = $this->post("/program/$id/delete.json");
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return $response->result(0)->id;
    }
}
