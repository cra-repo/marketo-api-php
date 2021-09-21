<?php

namespace Cra\MarketoApi\Entity\Asset;

class DynamicContent
{
    private int $id;

    private string $default;

    public function __construct(int $id, string $default = '')
    {
        $this->id = $id;
        $this->default = $default;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function type(): string
    {
        return 'DynamicContent';
    }

    public function idAsJson(): string
    {
        return json_encode(['type' => $this->type(), 'value' => $this->id]);
    }

    public function asJson(): string
    {
        return json_encode(['type' => $this->type(), 'segmentation' => $this->id, 'default' => $this->default]);
    }
}
