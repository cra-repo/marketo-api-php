<?php

declare(strict_types=1);

namespace Cra\MarketoApi;

use GuzzleHttp\Client as HttpClient;

interface ClientInterface
{
    /**
     * Class constructor.
     *
     * @param array{restBaseUrl: string, identityBaseUrl: string, clientId: string, clientSecret: string} $config
     * @param string $httpClientClass
     */
    public function __construct(array $config, string $httpClientClass = HttpClient::class);

    /**
     * Configure client.
     *
     * @param array{restBaseUrl: string, identityBaseUrl: string, clientId: string, clientSecret: string} $config
     *
     * @return $this
     */
    public function configure(array $config): self;

    /**
     * Perform authenticate request.
     *
     * @return $this
     */
    public function authenticate(): self;

    /**
     * Check if token has expired.
     *
     * @return bool
     */
    public function hasTokenExpired(): bool;

    /**
     * Ensures that access token is valid.
     *
     * Re-authorize if the token has expired.
     *
     * @return $this
     */
    public function ensureTokenValid(): self;

    /**
     * Do GET API request.
     *
     * @param string $uri
     * @param array $options
     *
     * @return Response
     */
    public function get(string $uri, array $options = []): Response;

    /**
     * Do POST API request.
     *
     * @param string $uri
     * @param array $options
     *
     * @return Response
     */
    public function post(string $uri, array $options = []): Response;

    /**
     * Do API request.
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @return Response
     */
    public function request(string $method, string $uri, array $options = []): Response;
}
