<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Cra\MarketoApi\Entity;

use DateTime;

trait ApiTrait
{
    protected object $apiObject;

    public function __construct(object $apiObject)
    {
        $this->apiObject = $apiObject;
    }

    public function id(): int
    {
        return $this->apiObject->id;
    }

    public function name(): string
    {
        return $this->apiObject->name;
    }

    public function description(): string
    {
        return $this->apiObject->description;
    }

    public function createdAt(): DateTime
    {
        return new DateTime($this->apiObject->createdAt);
    }

    public function updatedAt(): DateTime
    {
        return new DateTime($this->apiObject->updatedAt);
    }

    public function asJson(): string
    {
        return json_encode($this->apiObject);
    }
}
