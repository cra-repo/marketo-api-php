<?php

declare(strict_types=1);

namespace Cra\MarketoApi;

use Exception;
use InvalidArgumentException;

class Response
{
    private object $response;

    public function __construct($response)
    {
        if (!is_object($response)) {
            throw new InvalidArgumentException(sprintf('Invalid API response: %s', var_export($response, true)));
        }
        $this->response = $response;
    }

    /**
     * Get response result.
     *
     * @param int|null $index Optional index parameter if only a single result is needed.
     * @return mixed
     */
    public function result(?int $index = null)
    {
        return null === $index ? $this->response->result : $this->response->result[$index];
    }

    /**
     * Get first response result if it is valid or null otherwise.
     *
     * @return object|null
     */
    public function singleValidResult(): ?object
    {
        return $this->isResultValid() ? $this->response->result[0] : null;
    }

    /**
     * Checks that API response is successful and throws an exception otherwise.
     *
     * @throws Exception
     */
    public function checkIsSuccess(): void
    {
        if ($this->isSuccess()) {
            throw new Exception(
                sprintf(
                    'Errors during Folder API call: %s',
                    serialize($this->response->errors ?? [])
                )
            );
        }
    }

    /**
     * Checks that API response is successful.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return !is_object($this->response) || empty($this->response->success);
    }

    /**
     * Checks API response result is valid and throws an exception otherwise.
     *
     * @throws Exception
     */
    public function checkIsResultValid(): void
    {
        if (!$this->isResultValid()) {
            throw new Exception(
                sprintf(
                    'Invalid response result: %s',
                    var_export($this->response->result ?? null, true)
                )
            );
        }
    }

    /**
     * Checks API response result is valid.
     *
     * @return bool
     */
    public function isResultValid(): bool
    {
        return !empty($this->response->result) && is_array($this->response->result);
    }
}
