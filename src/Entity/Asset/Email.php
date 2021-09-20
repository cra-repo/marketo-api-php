<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Entity\Asset;

use Cra\MarketoApi\Entity\ApiTrait;
use Cra\MarketoApi\Entity\TypeValue;

class Email
{
    use ApiTrait;

    public function url(): ?string
    {
        return $this->apiObject->url ?? null;
    }

    public function subject(): ?TypeValue
    {
        return $this->apiObject->subject ?
            new TypeValue($this->apiObject->subject) :
            null;
    }

    public function fromName(): ?TypeValue
    {
        return $this->apiObject->fromName ?
            new TypeValue($this->apiObject->fromName) :
            null;
    }

    public function fromEmail(): ?TypeValue
    {
        return $this->apiObject->fromEmail ?
            new TypeValue($this->apiObject->fromEmail) :
            null;
    }

    public function replyEmail(): ?TypeValue
    {
        return $this->apiObject->replyEmail ?
            new TypeValue($this->apiObject->replyEmail) :
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
