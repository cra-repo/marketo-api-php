<?php

namespace Cra\MarketoApi\Entity\Asset;

class CcField
{
    private object $apiObject;

    public function __construct(object $apiObject)
    {
        $this->apiObject = $apiObject;
    }

    public function attributeId(): string
    {
        return $this->apiObject->attributeId;
    }

    public function objectName(): string
    {
        return $this->apiObject->objectName;
    }

    public function displayName(): string
    {
        return $this->apiObject->displayName;
    }

    public function apiName(): ?string
    {
        return $this->apiObject->apiName;
    }
}
