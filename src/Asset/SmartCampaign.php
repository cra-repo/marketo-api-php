<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Cra\MarketoApi\Asset;

use DateTime;

class SmartCampaign
{
    private object $apiObject;

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

    public function status(): string
    {
        return $this->apiObject->status;
    }

    public function type(): string
    {
        return $this->apiObject->type;
    }

    public function isSystem(): bool
    {
        return $this->apiObject->isSystem;
    }

    public function isActive(): bool
    {
        return $this->apiObject->isActive;
    }

    public function isRequestable(): bool
    {
        return $this->apiObject->isRequestable;
    }

    public function isCommunicationLimitEnabled(): bool
    {
        return $this->apiObject->isCommunicationLimitEnabled;
    }

    public function recurrence(): object
    {
        return clone $this->apiObject->recurrence;
    }

    public function qualificationRuleType(): string
    {
        return $this->apiObject->qualificationRuleType;
    }

    public function workspace(): string
    {
        return $this->apiObject->workspace;
    }

    public function smartListId(): int
    {
        return $this->apiObject->smartListId;
    }

    public function flowId(): int
    {
        return $this->apiObject->flowId;
    }

    public function computedUrl(): string
    {
        return $this->apiObject->computedUrl;
    }
}
