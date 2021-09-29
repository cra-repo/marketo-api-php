<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Entity;

trait ApiTraitWithDescription
{
    use ApiTrait;

    public function description(): string
    {
        return $this->apiObject->description;
    }
}
