<?php

namespace Cra\MarketoApi\Entity\Asset;

use Cra\MarketoApi\Entity\ApiTrait;

class Channel
{
    use ApiTrait;

    public function applicableProgramType(): string
    {
        return $this->apiObject->applicableProgramType;
    }

    /**
     * @return ChannelProgression[]
     */
    public function progressionStatuses(): array
    {
        return array_map(
            static fn(object $status) => new ChannelProgression($status),
            $this->apiObject->progressionStatuses ?? []
        );
    }
}
