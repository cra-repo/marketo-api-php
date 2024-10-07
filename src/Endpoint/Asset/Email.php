<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\Endpoint\EndpointInterface;
use Cra\MarketoApi\Entity\Asset\CcField;
use Cra\MarketoApi\Entity\Asset\Content;
use Cra\MarketoApi\Entity\Asset\DynamicContent;
use Cra\MarketoApi\Entity\Asset\Email as Entity;
use Cra\MarketoApi\Entity\Asset\FolderId;
use Cra\MarketoApi\Entity\Asset\Text;
use Exception;
use InvalidArgumentException;

class Email implements EndpointInterface
{
    use AssetTrait;

    /**
     * Query Email by ID.
     *
     * @param int $id
     * @return Entity|null
     * @throws Exception
     */
    public function queryById(int $id): ?Entity
    {
        $response = $this->get("/email/$id.json");
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new Entity($result) : null;
    }

    /**
     * Query Email by name.
     *
     * @param string $name
     * @param FolderId|null $folder Optionally filter results by specific Folder.
     * @return Entity|null
     *
     * @throws Exception
     */
    public function queryByName(string $name, ?FolderId $folder = null): ?Entity
    {
        $query = ['name' => $name];
        if ($folder) {
            $query['folder'] = $folder->asJson();
        }

        $response = $this->get('/email/byName.json', ['query' => $query]);
        $response->checkIsSuccess();
        $result = $response->singleValidResult();

        return $result ? new Entity($result) : null;
    }

    /**
     * Browse Folder for children Folders.
     *
     * @param array{status?: string, folder?: FolderId, maxReturn?: int, offset?: int} $filters
     * @return Entity[]
     *
     * @throws Exception
     */
    public function browse(array $filters = []): array
    {
        $query = [];
        $this->addFieldsToQuery($query, ['status', 'folder', 'maxReturn', 'offset'], $filters);

        $response = $this->get('/emails.json', ['query' => $query]);
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $folder) => new Entity($folder), $response->result()) :
            [];
    }

    /**
     * Query all CC Fields.
     *
     * @return CcField[]
     * @throws Exception
     */
    public function queryCcFields(): array
    {
        $response = $this->get('/email/ccFields.json');
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $field) => new CcField($field), $response->result()) :
            [];
    }

    /**
     * Query Email Content.
     *
     * @param int $id Email ID
     * @param string|null $status Optionally filter to get the sections for either the Approved or Draft versions.
     * @return Content[]
     * @throws Exception
     */
    public function queryContent(int $id, ?string $status = null): array
    {
        $query = [];
        if (isset($status)) {
            $query['status'] = $status;
        }

        $response = $this->get("/email/$id/content.json", ['query' => $query]);
        $response->checkIsSuccess();

        return $response->isResultValid() ?
            array_map(static fn(object $content) => new Content($content), $response->result()) :
            [];
    }

    /**
     * Create Email from Template in Folder.
     *
     * The following parameters are optional for creation: subject, fromName, fromEmail, replyEmail, operational.
     * If unset, subject will be empty, fromName, fromEmail and replyEmail will be set to instance defaults,
     * and operational will be false.
     *
     * @param string $name
     * @param int $templateId
     * @param FolderId $folderId
     * phpcs:ignore Generic.Files.LineLength.TooLong
     * @param array{subject: string, fromName: string, fromEmail: string, replyEmail: string, operational: bool|null} $optional
     * @return Entity
     *
     * @throws Exception
     */
    public function create(string $name, int $templateId, FolderId $folderId, array $optional = []): Entity
    {
        $params = ['name' => $name, 'folder' => $folderId->asJson(), 'template' => $templateId];
        $this->addFieldsToQuery($params, ['subject', 'fromName', 'fromEmail', 'replyEmail', 'operational'], $optional);

        $response = $this->post('/emails.json', ['form_params' => $params]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Entity($response->result(0));
    }

    /**
     * Approves the current draft of an email. Required Permissions: Approve Assets
     * @link https://developers.marketo.com/rest-api/endpoint-reference/asset-endpoint-reference/#!/Emails/approveDraftUsingPOST
     *
     * @param int $id
     * @return Entity
     * @throws Exception
     */
    public function approveDraft(int $id): Entity
    {
        $response = $this->post("/email/$id/approveDraft.json", ['form_params' => []]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Entity($response->result(0));
    }

    /**
     * Sends a sample email to the given email address. Leads may be impersonated to populate data for tokens and dynamic content. Required Permissions: Read-Write Assets
     * @link https://developers.marketo.com/rest-api/endpoint-reference/asset-endpoint-reference/#!/Emails/sendSampleEmailUsingPOST
     *
     * @param int $id
     * @param array{emailAddress?: string, leadId?: ?string} $fields
     * @return string
     * @throws Exception
     */
    public function sendSample(int $id, array $fields = []): string
    {
        $params = [];
        $this->addFieldsToQuery($params, ['emailAddress', 'leadId'], $fields);
        if (empty($params)) {
            throw new InvalidArgumentException('Missing required emailAddress and/or leadId.');
        }

        $response = $this->post("/email/$id/sendSample.json", ['form_params' => $params]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return $response->result(0);
    }

    /**
     * Update Email's name and/or description.
     *
     * @param int $id
     * @param array{name?: string, description?: string} $fields
     * @return Entity
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function update(int $id, array $fields = []): Entity
    {
        $params = [];
        $this->addFieldsToQuery($params, ['name', 'description'], $fields);
        if (empty($params)) {
            throw new InvalidArgumentException('Missing name and/or description.');
        }

        $response = $this->post("/email/$id.json", ['form_params' => $params]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return new Entity($response->result(0));
    }

    /**
     * Update subject, fromEmail, fromName, and/or replyTo.
     *
     * @param int $id Email ID
     * phpcs:ignore Generic.Files.LineLength.TooLong
     * @param array{subject?: Text|DynamicContent, fromEmail?: Text|DynamicContent, fromName?: Text|DynamicContent, replyTo?: Text|DynamicContent} $fields
     * @return int Returns Email ID
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function updateContent(int $id, array $fields = []): int
    {
        $params = [];
        $this->addFieldsToQuery($params, ['subject', 'fromEmail', 'fromName', 'replyTo'], $fields);
        if (empty($params)) {
            throw new InvalidArgumentException(
                'Missing any of the following fields subject, fromEmail, fromName, replyTo.'
            );
        }

        $response = $this->post("/email/$id/content.json", ['form_params' => $params]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return $response->result(0)->id;
    }

    /**
     * Update Editable Section of the Email.
     *
     * @param int $id Email ID
     * @param string $htmlId
     * @param Text|DynamicContent $value
     * @param string $textValue
     * @return int
     *
     * @throws Exception
     */
    public function updateEditableSection(int $id, string $htmlId, $value, string $textValue = ''): int
    {
        $params = ['type' => $value->type()];
        if ($value instanceof Text) {
            $params['value'] = $value->value();
        } else {
            $params['value'] = $value->asJson();
        }
        if (!empty($textValue)) {
            $params['textValue'] = $textValue;
        }

        $response = $this->post("/email/$id/content/$htmlId.json", [
            'form_params' => $params,
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8']]);
        $response->checkIsSuccess();
        $response->checkIsResultValid();

        return $response->result(0)->id;
    }
}
