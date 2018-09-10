<?php

namespace Contraption\Conduit\Processors;

use Contraption\Conduit\Pipeline;
use Contraption\Conduit\Processors\Contracts\Processor;
use Ds\Queue;

class ConveyorProcessor implements Processor
{
    public function process(Pipeline $pipeline, Queue $pipes, $payload)
    {
        foreach($pipes as $pipe) {
            $payload = $pipe($payload);
        }

        return $payload;
    }
}