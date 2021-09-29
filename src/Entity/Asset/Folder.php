<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Entity\Asset;

use Cra\MarketoApi\Entity\ApiTraitWithDescription;

class Folder
{
    use ApiTraitWithDescription;

    public function url(): ?string
    {
        return $this->apiObject->url;
    }

    public function folderId(): FolderId
    {
        return new FolderId($this->apiObject->folderId);
    }

    public function folderType(): string
    {
        return $this->apiObject->folderType;
    }

    public function parent(): ?FolderId
    {
        return $this->apiObject->parent ? new FolderId($this->apiObject->parent) : null;
    }

    public function path(): string
    {
        return $this->apiObject->path;
    }

    public function isArchive(): bool
    {
        return $this->apiObject->isArchive;
    }

    public function isSystem(): bool
    {
        return $this->apiObject->isSystem;
    }

    public function accessZoneId(): int
    {
        return $this->apiObject->accessZoneId;
    }

    public function workspace(): string
    {
        return $this->apiObject->workspace;
    }
}
