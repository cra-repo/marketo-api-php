<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Entity\Asset;

use Cra\MarketoApi\Entity\ApiTrait;

class SmartCampaign
{
    use ApiTrait;

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
