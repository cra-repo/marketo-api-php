<?php

declare(strict_types=1);

namespace Cra\MarketoApi\Entity\Asset;

use Cra\MarketoApi\Entity\ApiTrait;

class Program
{
    use ApiTrait;

    public function url(): string
    {
        return $this->apiObject->url;
    }

    public function type(): string
    {
        return $this->apiObject->type;
    }

    public function channel(): string
    {
        return $this->apiObject->channel;
    }

    public function folder(): BasicFolder
    {
        return new BasicFolder($this->apiObject->folder);
    }

    /**
     * @return boolean|string
     */
    public function status()
    {
        return $this->apiObject->status;
    }

    public function workspace(): string
    {
        return $this->apiObject->workspace;
    }

    /**
     * @return Tag[]
     */
    public function tags(): array
    {
        return array_map(static fn(object $tag) => new Tag($tag), $this->apiObject->tags ?? []);
    }

    public function costs(): ?object
    {
        // @todo Replace with proper entity class.
        return $this->apiObject->costs;
    }
}
