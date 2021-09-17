<?php

namespace Cra\MarketoApi\Entity;

class TypeValue
{
    private object $apiObject;

    public function __construct(object $apiObject)
    {
        $this->apiObject = $apiObject;
    }

    public function type(): string
    {
        return $this->apiObject->type;
    }

    public function value(): string
    {
        return $this->apiObject->value;
    }
}
