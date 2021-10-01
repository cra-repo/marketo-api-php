<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Entity\Asset;

class BasicFolder
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

    public function value(): int
    {
        return $this->apiObject->value;
    }

    public function folderName(): ?string
    {
        return $this->apiObject->folderName ?? null;
    }
}
