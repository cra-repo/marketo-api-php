<?php

namespace Cra\MarketoApi;

use Exception;
use InvalidArgumentException;

class Response
{
    private object $response;

    public function __construct($response)
    {
        if (!is_object($response)) {
            throw new InvalidArgumentException(sprintf('Invalid API response: %s', var_export($response)));
        }
        $this->response = $response;
    }

    /**
     * Get response result.
     *
     * @return mixed
     */
    public function result()
    {
        return $this->response->result;
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
                    var_export($this->response->errors ?? [])
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
                    var_export($this->response->result ?? null)
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
