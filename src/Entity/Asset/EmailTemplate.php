<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Entity\Asset;

use Cra\MarketoApi\Entity\ApiTraitWithDescription;

class EmailTemplate
{
    use ApiTraitWithDescription;

    public function folder(): ?BasicFolder
    {
        return $this->apiObject->folder ?
            new BasicFolder($this->apiObject->folder) :
            null;
    }

    /**
     * @return bool|string
     */
    public function status()
    {
        return $this->apiObject->status;
    }

    public function workspace(): string
    {
        return $this->apiObject->workspace;
    }
}
