<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Entity\Asset;

class ChannelProgression
{
    private object $apiObject;

    public function __construct(object $apiObject)
    {
        $this->apiObject = $apiObject;
    }

    public function name(): ?string
    {
        return $this->apiObject->name ?? null;
    }

    public function step(): ?int
    {
        return $this->apiObject->step ?? null;
    }

    public function description(): ?string
    {
        return $this->apiObject->description ?? null;
    }

    public function hidden(): ?bool
    {
        return $this->apiObject->hidden ?? null;
    }

    public function success(): ?bool
    {
        return $this->apiObject->success ?? null;
    }
}
