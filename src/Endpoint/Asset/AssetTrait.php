<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Endpoint\Asset;

use Cra\MarketoApi\ClientInterface;
use Cra\MarketoApi\Response;

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
     * Add optional pagination fields to query.
     *
     * @param array $query Associative array of query fields.
     * @param array $values Associative array of values to add to the query.
     */
    protected function addOptionalPaginationFieldsToQuery(array &$query, array $values = []): void
    {
        $this->addFieldsToQuery($query, ['maxReturn', 'offset'], $values);
    }

    /**
     * Explicitly add optional fields to query.
     *
     * @param array $query Associative array of query fields (or form parameters).
     * @param string[] $fields List of possible fields to add to the query.
     * @param array $values Associative array of values to add to the query.
     */
    protected function addFieldsToQuery(array &$query, array $fields, array $values = []): void
    {
        foreach ($fields as $field) {
            if (isset($values[$field])) {
                $query[$field] = $values[$field];
            }
        }
    }
}
