<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Entity\Asset;

use Cra\MarketoApi\Entity\TypeValue;
use DateTime;

class Email
{
    private object $apiObject;

    public function __construct(object $apiObject)
    {
        $this->apiObject = $apiObject;
        // Example:
        // {
        //    "id":1356,
        //    "name":"sakZxhxkwV",
        //    "description":"sample description",
        //    "createdAt":"2014-12-05T02:06:21Z+0000",
        //    "updatedAt":"2014-12-05T02:06:21Z+0000",
        //    "url": null,
        //    "subject":{
        //       "type":"Text",
        //       "value":"sample subject"
        //    },
        //    "fromName":{
        //       "type":"Text",
        //       "value":"RBxEtmdQZz"
        //    },
        //    "fromEmail":null,
        //    "replyEmail":{
        //       "type":"Text",
        //       "value":"Qlikf@testmail.com"
        //    },
        //    "folder":{
        //       "type":"folder",
        //       "value":10421,
        //       "folderName": "Social Media"
        //    },
        //    "operational":false,
        //    "textOnly":false,
        //    "publishToMSI":false,
        //    "webView":false,
        //    "status":false,
        //    "template":338,
        //    "workspace":"Default",
        //    "version": 2,
        //    "autoCopyToText": true,
        //    "ccFields": [
        //       {
        //         "attributeId": "157",
        //         "objectName": "lead",
        //         "displayName": "Lead Owner Email Address",
        //         "apiName": null
        //       }
        //     ],
        //    "preHeader": "My awesome preheader!"
        // }
    }

    public function id(): int
    {
        return $this->apiObject->id;
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
