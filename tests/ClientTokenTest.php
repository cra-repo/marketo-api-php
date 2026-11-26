<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Cra\MarketoApiTest;

use Cra\MarketoApi\Client;
use Cra\MarketoApiTest\Support\MockHttpClient;
use Exception;
use GuzzleHttp\Psr7\Response as HttpResponse;
use PHPUnit\Framework\TestCase;

/**
 * Covers access token handling: authentication response validation and re-authentication on 601/602.
 */
class ClientTokenTest extends TestCase
{
    private const IDENTITY_URL = 'https://marketo.test/identity';

    protected function setUp(): void
    {
        MockHttpClient::$queue = [];
        MockHttpClient::$history = [];
    }

    public function testAuthenticateStoresTokenWithFullLifetime(): void
    {
        $client = $this->createClient([self::authResponse('TOKEN', 3600)])->authenticate();

        $this->assertFalse($client->hasTokenExpired());
    }

    public function testAuthenticateAcceptsZeroExpiresIn(): void
    {
        $client = $this->createClient([self::authResponse('TOKEN', 0)])->authenticate();

        $this->assertTrue($client->hasTokenExpired());
    }

    /**
     * @dataProvider invalidExpiresInProvider
     */
    public function testAuthenticateRejectsInvalidExpiresIn(array $authBody): void
    {
        $client = $this->createClient([self::json($authBody)]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Empty/incorrect expires_in value.');

        $client->authenticate();
    }

    public function invalidExpiresInProvider(): array
    {
        return [
            'missing' => [['access_token' => 'TOKEN']],
            'null' => [['access_token' => 'TOKEN', 'expires_in' => null]],
            'string' => [['access_token' => 'TOKEN', 'expires_in' => '3600']],
            'float' => [['access_token' => 'TOKEN', 'expires_in' => 1.5]],
            'negative' => [['access_token' => 'TOKEN', 'expires_in' => -1]],
        ];
    }

    public function testFailedAuthenticationKeepsPreviousToken(): void
    {
        $client = $this->createClient([
            self::authResponse('OLD', 3600),
            self::json(['access_token' => 'BROKEN', 'expires_in' => '3600']),
            self::apiSuccess(),
        ])->authenticate();

        try {
            $client->authenticate();
            $this->fail('Authentication with invalid expires_in must fail.');
        } catch (Exception $e) {
            $this->assertSame('Empty/incorrect expires_in value.', $e->getMessage());
        }

        $client->get('/leads.json');

        $this->assertSame(['Bearer OLD'], self::sentApiAuthorizationHeaders());
    }

    public function testRequestWithZeroLifetimeTokenSucceedsWhenMarketoAcceptsIt(): void
    {
        $client = $this->createClient([
            self::authResponse('OLD', 0),
            self::apiSuccess(),
        ])->authenticate();

        $response = $client->get('/leads.json');

        $this->assertSame([], $response->errors());
        $this->assertSame(['Bearer OLD'], self::sentApiAuthorizationHeaders());
        $this->assertCount(2, MockHttpClient::$history);
    }

    /**
     * @dataProvider tokenRejectedErrorCodeProvider
     */
    public function testRequestReauthenticatesAndRetriesOnceWhenTokenRejected($errorCode): void
    {
        $client = $this->createClient([
            self::authResponse('OLD', 0),
            self::apiError($errorCode),
            self::authResponse('NEW', 3600),
            self::apiSuccess(),
        ])->authenticate();

        $response = $client->get('/leads.json');

        $this->assertSame([], $response->errors());
        $this->assertSame(['Bearer OLD', 'Bearer NEW'], self::sentApiAuthorizationHeaders());
        $this->assertFalse($client->hasTokenExpired());
    }

    public function tokenRejectedErrorCodeProvider(): array
    {
        return [
            'access token invalid (601)' => ['601'],
            'access token expired (602)' => ['602'],
            'numeric error code' => [602],
        ];
    }

    public function testRequestRetriesOnlyOnce(): void
    {
        $client = $this->createClient([
            self::authResponse('OLD', 3600),
            self::apiError('602'),
            self::authResponse('NEW', 3600),
            self::apiError('602'),
        ])->authenticate();

        $response = $client->get('/leads.json');

        $this->assertSame('602', $response->errors()[0]->code);
        $this->assertSame(['Bearer OLD', 'Bearer NEW'], self::sentApiAuthorizationHeaders());
        $this->assertCount(4, MockHttpClient::$history);
    }

    public function testRequestDoesNotRetryOnOtherErrors(): void
    {
        $client = $this->createClient([
            self::authResponse('TOKEN', 3600),
            self::apiError('606'),
        ])->authenticate();

        $response = $client->get('/leads.json');

        $this->assertSame('606', $response->errors()[0]->code);
        $this->assertCount(2, MockHttpClient::$history);
    }

    public function testRequestWithoutTokenFails(): void
    {
        $client = $this->createClient([]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Missing token. Use authenticate method first.');

        $client->get('/leads.json');
    }

    private function createClient(array $responses): Client
    {
        MockHttpClient::$queue = $responses;

        return new Client(
            [
                'restBaseUrl' => 'https://marketo.test/rest',
                'identityBaseUrl' => self::IDENTITY_URL,
                'clientId' => 'CLIENT_ID',
                'clientSecret' => 'CLIENT_SECRET',
            ],
            MockHttpClient::class
        );
    }

    private static function authResponse(string $token, int $expiresIn): HttpResponse
    {
        return self::json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiresIn,
            'scope' => 'api@example.com',
        ]);
    }

    private static function apiSuccess(): HttpResponse
    {
        return self::json(['requestId' => 'req', 'success' => true, 'result' => [['id' => 1]]]);
    }

    /**
     * @param string|int $code
     */
    private static function apiError($code): HttpResponse
    {
        return self::json([
            'requestId' => 'req',
            'success' => false,
            'errors' => [['code' => $code, 'message' => 'Error']],
        ]);
    }

    private static function json(array $body): HttpResponse
    {
        return new HttpResponse(200, ['Content-Type' => 'application/json'], json_encode($body));
    }

    /**
     * Authorization headers of the sent REST API requests, in order (identity requests are skipped).
     *
     * @return array<int, string>
     */
    private static function sentApiAuthorizationHeaders(): array
    {
        $headers = [];
        foreach (MockHttpClient::$history as $transaction) {
            $request = $transaction['request'];
            if (strpos((string)$request->getUri(), self::IDENTITY_URL) === 0) {
                continue;
            }
            $headers[] = $request->getHeaderLine('Authorization');
        }

        return $headers;
    }
}
