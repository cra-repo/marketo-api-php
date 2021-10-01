<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Entity\Asset;

use Cra\MarketoApi\Entity\ApiTraitWithDescription;
use Cra\MarketoApi\Entity\GenericValue;

class Email
{
    use ApiTraitWithDescription;

    public function url(): ?string
    {
        return $this->apiObject->url ?? null;
    }

    public function subject(): ?GenericValue
    {
        return $this->apiObject->subject ?
            new GenericValue($this->apiObject->subject) :
            null;
    }

    public function fromName(): ?GenericValue
    {
        return $this->apiObject->fromName ?
            new GenericValue($this->apiObject->fromName) :
            null;
    }

    public function fromEmail(): ?GenericValue
    {
        return $this->apiObject->fromEmail ?
            new GenericValue($this->apiObject->fromEmail) :
            null;
    }

    public function replyEmail(): ?GenericValue
    {
        return $this->apiObject->replyEmail ?
            new GenericValue($this->apiObject->replyEmail) :
            null;
    }

    public function folder(): ?BasicFolder
    {
        return $this->apiObject->folder ?
            new BasicFolder($this->apiObject->folder) :
            null;
    }

    public function operational(): bool
    {
        return $this->apiObject->operational;
    }

    public function textOnly(): bool
    {
        return $this->apiObject->textOnly;
    }

    public function publishToMsi(): bool
    {
        return $this->apiObject->publishToMSI;
    }

    public function webView(): bool
    {
        return $this->apiObject->webView;
    }

    /**
     * @return bool|string
     */
    public function status()
    {
        return $this->apiObject->status;
    }

    public function template(): int
    {
        return $this->apiObject->template;
    }

    public function workspace(): string
    {
        return $this->apiObject->workspace;
    }

    public function version(): int
    {
        return $this->apiObject->version;
    }

    public function autoCopyToText(): bool
    {
        return $this->apiObject->autoCopyToText;
    }

    /**
     * @return CcField[]
     */
    public function ccFields(): array
    {
        return array_map(
            static fn($ccField) => new CcField($ccField),
            $this->apiObject->ccFields ?? []
        );
    }

    public function preHeader(): string
    {
        return $this->apiObject->preHeader;
    }
}
