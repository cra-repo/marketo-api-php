<?php

declare(strict_types=1);

namespace Cra\MarketoApiTest\Support;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Guzzle client that serves queued responses and records sent requests.
 *
 * Cra\MarketoApi\Client instantiates its HTTP client by class name without arguments, so the queue and the
 * history are static and must be filled before the Marketo client is created.
 */
class MockHttpClient extends Client
{
    /**
     * @var array<int, ResponseInterface>
     */
    public static array $queue = [];

    /**
     * @var array<int, array{request: RequestInterface}>
     */
    public static array $history = [];

    public function __construct()
    {
        $stack = HandlerStack::create(new MockHandler(self::$queue));
        $stack->push(Middleware::history(self::$history));

        parent::__construct(['handler' => $stack]);
    }
}
