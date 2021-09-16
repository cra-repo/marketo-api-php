<?php

declare(strict_types=1);

namespace Cra\MarketoApi;

use DateTime;
use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use Psr\Http\Client\ClientInterface;

/**
 * API Client for Marketo.
 */
class Client
{
    private string $restBaseUrl = '';

    private string $identityBaseUrl = '';

    private string $clientId = '';

    private string $clientSecret = '';

    private string $accessToken = '';

    private ?DateTime $tokenExpiresAt;

    private ClientInterface $client;

    /**
     * Class constructor.
     *
     * @param array{restBaseUrl: string, identityBaseUrl: string, clientId: string, clientSecret: string} $config
     * @param string $httpClientClass
     *
     * @throws Exception
     */
    public function __construct(array $config, string $httpClientClass = HttpClient::class)
    {
        $this->configure($config);
        $this->client = new $httpClientClass();
    }

    /**
     * Configure client.
     *
     * @param array{restBaseUrl: string, identityBaseUrl: string, clientId: string, clientSecret: string} $config
     *
     * @return $this
     * @throws Exception
     */
    public function configure(array $config): self
    {
        $requiredFields = [
            'restBaseUrl',
            'identityBaseUrl',
            'clientId',
            'clientSecret',
        ];

        $missingFields = array_diff($requiredFields, array_keys($config));
        if (!empty($missingFields)) {
            throw new InvalidArgumentException(sprintf('Missing required fields: %s', implode(', ', $missingFields)));
        }

        $emptyFields = array_filter(
            array_intersect_key($config, array_combine($requiredFields, $requiredFields)),
            static fn($value) => empty($value)
        );
        if (!empty($emptyFields)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Required fields are empty: %s',
                    implode(', ', array_keys($emptyFields))
                )
            );
        }

        $this->restBaseUrl = $config['restBaseUrl'];
        $this->identityBaseUrl = $config['identityBaseUrl'];
        $this->clientId = $config['clientId'];
        $this->clientSecret = $config['clientSecret'];

        return $this;
    }

    /**
     * Perform authenticate request.
     *
     * @return $this
     * @throws GuzzleException
     * @throws Exception
     */
    public function authenticate(): self
    {
        $response = $this->client->get($this->identityBaseUrl . '/oauth/token', [
            'query' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new Exception('Error authenticating.');
        }

        $body = json_decode((string)$response->getBody());
        if (!is_object($body)) {
            throw new Exception('Incorrect authentication response.');
        }
        if (empty($body->access_token) || !is_string($body->access_token)) {
            throw new Exception('Empty/incorrect access token.');
        }
        $this->accessToken = $body->access_token;
        if (empty($body->expires_in) || !is_int($body->expires_in)) {
            throw new Exception('Empty/incorrect expires_in value.');
        }
        $this->tokenExpiresAt = (new DateTime())->add(new \DateInterval("PT{$body->expires_in}S"));

        return $this;
    }

    /**
     * Check if token has expired.
     *
     * @return bool
     */
    public function hasTokenExpired(): bool
    {
        if (empty($this->accessToken) || empty($this->tokenExpiresAt)) {
            return true;
        }

        return (new DateTime()) > $this->tokenExpiresAt;
    }

    /**
     * Do API request.
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    public function request(string $method, string $uri, array $options = [])
    {
        if (empty($this->accessToken)) {
            throw new Exception('Missing token. Use authenticate method first.');
        }
        if ($this->hasTokenExpired()) {
            throw new Exception('Token has expired!');
        }

        $options['headers'] = $options['headers'] ?? [];
        $options['headers']['Authorization'] = "Bearer $this->accessToken";
        $response = $this->client->request($method, $this->restBaseUrl . $uri, $options);
        if ($response->getStatusCode() !== 200) {
            throw new Exception(
                sprintf(
                    'An error has occurred during request. Status code: %s. Response: %s',
                    $response->getStatusCode(),
                    $response->getBody()
                )
            );
        }

        return json_decode((string)$response->getBody());
    }
}
