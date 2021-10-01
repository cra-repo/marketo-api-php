<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\ClientInterface;
use Cra\MarketoApi\Entity\Asset\Cost;
use Cra\MarketoApi\Entity\Asset\DynamicContent;
use Cra\MarketoApi\Entity\Asset\FolderId;
use Cra\MarketoApi\Entity\Asset\Tag;
use Cra\MarketoApi\Entity\Asset\Text;
use Cra\MarketoApi\Response;
use DateTime;

trait AssetTrait
{
    protected ClientInterface $client;

    /**
     * Class constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Do GET Asset API request.
     *
     * @param string $uri URI relative from /rest/asset/v1
     * @param array $options
     *
     * @return Response
     */
    protected function get(string $uri, array $options = []): Response
    {
        return $this->client
            ->ensureTokenValid()
            ->get("/asset/v1$uri", $options);
    }

    /**
     * Do POST Asset API request.
     *
     * @param string $uri URI relative from /rest/asset/v1
     * @param array $options
     *
     * @return Response
     */
    protected function post(string $uri, array $options = []): Response
    {
        return $this->client
            ->ensureTokenValid()
            ->post("/asset/v1$uri", $options);
    }

    /**
     * Explicitly add optional fields to query.
     *
     * @param array $query Associative array of query fields (or form parameters).
     * @param string[] $fields List of possible fields to add to the query.
     * @param array $values Associative array of values to add to the query.
     */
    protected function addFieldsToQuery(array &$query, array $fields, array $values): void
    {
        foreach ($fields as $field) {
            $value = $values[$field] ?? null;
            if ($value === null) {
                continue;
            }

            if (is_scalar($value)) {
                $query[$field] = $value;
            } elseif ($value instanceof FolderId || $value instanceof Text) {
                $query[$field] = $value->asJson();
            } elseif ($value instanceof DynamicContent) {
                $query[$field] = $value->idAsJson();
            } elseif ($value instanceof DateTime) {
                $query[$field] = $value->format('c');
            } elseif ($field === 'costs') {
                $query[$field] = json_encode(array_map(static fn(Cost $cost) => $cost->asJson(), $value));
            } elseif ($field === 'tags') {
                $query[$field] = json_encode(array_map(static fn(Tag $tag) => $tag->idAsJson(), $value));
            }
        }
    }
}
