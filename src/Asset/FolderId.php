<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Asset;

class FolderId
{
    private object $apiObject;

    public function __construct(object $apiObject)
    {
        $this->apiObject = $apiObject;
    }

    public function __toString(): string
    {
        return $this->asJson();
    }

    public function id(): int
    {
        return $this->apiObject->id;
    }

    public function type(): string
    {
        return $this->apiObject->type;
    }

    public function asJson(): string
    {
        return json_encode(['id' => $this->id(), 'type' => $this->type()]);
    }
}
