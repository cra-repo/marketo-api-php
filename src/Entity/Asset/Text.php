<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Entity\Asset;

class Text
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function type(): string
    {
        return 'Text';
    }

    public function asJson(): string
    {
        return json_encode(['type' => $this->type(), 'value' => $this->value]);
    }
}
