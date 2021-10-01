<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Cra\MarketoApiTest;

use Cra\MarketoApi\Client;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{

    public function testCanBeCreatedWithValidConfig(): void
    {
        $this->assertInstanceOf(
            Client::class,
            new Client([
                           'restBaseUrl' => '/rest',
                           'identityBaseUrl' => '/identity',
                           'clientId' => 'CLIENT_ID',
                           'clientSecret' => 'CLIENT_SECRET',
                       ])
        );
    }

    public function testCannotBeCreatedWithMissingConfig(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields: restBaseUrl, identityBaseUrl, clientId, clientSecret');

        new Client([]);
    }

    public function testCannotBeCreatedWithMissingRestBaseUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields: restBaseUrl');

        new Client([
                       'identityBaseUrl' => '/identity',
                       'clientId' => 'CLIENT_ID',
                       'clientSecret' => 'CLIENT_SECRET',
                   ]);
    }

    public function testCannotBeCreatedWithMissingIdentityBaseUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields: identityBaseUrl');

        new Client([
                       'restBaseUrl' => '/rest',
                       'clientId' => 'CLIENT_ID',
                       'clientSecret' => 'CLIENT_SECRET',
                   ]);
    }

    public function testCannotBeCreatedWithMissingClientId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields: clientId');

        new Client([
                       'identityBaseUrl' => '/identity',
                       'restBaseUrl' => '/rest',
                       'clientSecret' => 'CLIENT_SECRET',
                   ]);
    }

    public function testCannotBeCreatedWithMissingClientSecret(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields: clientSecret');

        new Client([
                       'identityBaseUrl' => '/identity',
                       'restBaseUrl' => '/rest',
                       'clientId' => 'CLIENT_ID',
                   ]);
    }

    public function testCannotBeCreatedWithEmptyConfigValues(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Required fields are empty: restBaseUrl, identityBaseUrl, clientId, clientSecret'
        );

        new Client([
                       'restBaseUrl' => '',
                       'identityBaseUrl' => '',
                       'clientId' => '',
                       'clientSecret' => '',
                   ]);
    }

    public function testCannotBeCreatedWithEmptyRestBaseUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required fields are empty: restBaseUrl');

        new Client([
                       'restBaseUrl' => '',
                       'identityBaseUrl' => '/identity',
                       'clientId' => 'CLIENT_ID',
                       'clientSecret' => 'CLIENT_SECRET',
                   ]);
    }

    public function testCannotBeCreatedWithEmptyIdentityBaseUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required fields are empty: identityBaseUrl');

        new Client([
                       'restBaseUrl' => '/rest',
                       'identityBaseUrl' => '',
                       'clientId' => 'CLIENT_ID',
                       'clientSecret' => 'CLIENT_SECRET',
                   ]);
    }

    public function testCannotBeCreatedWithEmptyClientId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required fields are empty: clientId');

        new Client([
                       'identityBaseUrl' => '/identity',
                       'restBaseUrl' => '/rest',
                       'clientId' => '',
                       'clientSecret' => 'CLIENT_SECRET',
                   ]);
    }

    public function testCannotBeCreatedWithEmptyClientSecret(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required fields are empty: clientSecret');

        new Client([
                       'identityBaseUrl' => '/identity',
                       'restBaseUrl' => '/rest',
                       'clientId' => 'CLIENT_ID',
                       'clientSecret' => '',
                   ]);
    }
}
