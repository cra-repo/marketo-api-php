<?php

namespace Cra\MarketoApi\Entity;

class GenericValue
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

    public function asJson(): string
    {
        return json_encode($this->apiObject);
    }
}
