<?php

namespace Contraption\Conduit\Processors\Contracts;

use Contraption\Conduit\Pipeline;
use Ds\Queue;

interface Processor
{
    public function process(Pipeline $pipeline, Queue $pipes, $payload);
}