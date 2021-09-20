<?php

namespace Cra\MarketoApi\Entity\Asset;

use Cra\MarketoApi\Entity\GenericValue;

class Content
{
    protected object $apiObject;

    public function __construct(object $apiObject)
    {
        $this->apiObject = $apiObject;
    }

    public function htmlId(): string
    {
        return $this->apiObject->htmlId;
    }

    /**
     * @return GenericValue[]
     */
    public function value(): array
    {
        return array_map(
            static fn(object $value) => new GenericValue($value),
            $this->apiObject->value ?? []
        );
    }

    /**
     * @param string $html
     * @param string|null $text
     * @return Content
     */
    public function updateContent(string $html, ?string $text = null): self
    {
        $this->apiObject->value = [];
        $this->apiObject->value[] = new GenericValue(
            (object)[
                'type' => 'HTML',
                'value' => $html,
            ]
        );
        if (isset($text)) {
            $this->apiObject->value[] = new GenericValue(
                (object)[
                    'type' => 'Text',
                    'value' => $text,
                ]
            );
        }
        return $this;
    }

    public function contentType(): string
    {
        return $this->apiObject->contentType;
    }
}
