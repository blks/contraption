<?php

namespace Contraption\Conduit;

use Contraption\Conduit\Processors\Contracts\Pipe;
use Contraption\Conduit\Processors\Contracts\Processor;
use Contraption\Conduit\Processors\ConveyorProcessor;
use Ds\Queue;

class Pipeline
{
    /**
     * @var \Ds\Queue<Pipe>
     */
    private $pipes;

    /**
     * @var \Contraption\Conduit\Processors\Contracts\Processor
     */
    private $processor;

    public function __construct(Processor $processor)
    {
        $this->pipes     = new Queue;
        $this->processor = $processor ?? new ConveyorProcessor;
    }

    /**
     * @param \Contraption\Conduit\Processors\Contracts\Pipe $pipe
     *
     * @return \Contraption\Conduit\Pipeline
     */
    public function pipe(Pipe $pipe): self
    {
        $this->pipes->push($pipe);
        return $this;
    }

    /**
     * @param mixed $payload
     *
     * @return mixed
     */
    public function process($payload)
    {
        return $this->processor->process($this, $payload, $this->pipes);
    }
}