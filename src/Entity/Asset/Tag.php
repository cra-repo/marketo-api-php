<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Entity\Asset;

class Tag
{
    private object $apiObject;

    public function __construct(object $apiObject)
    {
        $this->apiObject = $apiObject;
    }

    public function tagType(): string
    {
        return $this->apiObject->tagType;
    }

    public function tagValue(): ?string
    {
        return $this->apiObject->tagValue ?? null;
    }

    public function idAsJson(): string
    {
        return json_encode(['tagType' => $this->tagType(), 'tagValue' => $this->tagValue()]);
    }

    public function required(): ?bool
    {
        return $this->apiObject->required ?? null;
    }

    public function applicableProgramTypes(): ?string
    {
        return $this->apiObject->applicableProgramTypes ?? null;
    }

    public function allowableValues(): ?string
    {
        return $this->apiObject->allowableValues ?? null;
    }
}
