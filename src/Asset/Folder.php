<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Cra\MarketoApi\Asset;

use DateTime;

class Folder
{
    private object $apiObject;

    public function __construct(object $apiObject)
    {
        $this->apiObject = $apiObject;
    }

    public function name(): string
    {
        return $this->apiObject->name;
    }

    public function description(): string
    {
        return $this->apiObject->description;
    }

    public function createdAt(): DateTime
    {
        return new DateTime($this->apiObject->createdAt);
    }

    public function updatedAt(): DateTime
    {
        return new DateTime($this->apiObject->updatedAt);
    }

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

    public function id(): int
    {
        return $this->apiObject->id;
    }
}
