<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Cra\MarketoApi\Entity\Asset;

use DateTime;

class Cost
{
    private object $apiObject;

    public function __construct(object $apiObject)
    {
        $this->apiObject = $apiObject;
    }

    public function startDate(): DateTime
    {
        return new DateTime($this->apiObject->startDate);
    }

    public function cost(): int
    {
        return $this->apiObject->cost;
    }

    public function note(): ?string
    {
        return $this->apiObject->note ?? null;
    }

    public function asJson(): string
    {
        return json_encode($this->apiObject);
    }
}
