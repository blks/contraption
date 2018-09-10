<?php

namespace Contraption\Conduit\Processors;

use Contraption\Conduit\Pipeline;
use Contraption\Conduit\Processors\Contracts\Processor;
use Ds\Queue;

class DelicateProcessor implements Processor
{
    /**
     * @var callable
     */
    private $check;

    public function __construct(callable $check)
    {
        $this->check = $check;
    }

    /**
     * @param $payload
     *
     * @return bool
     */
    private function check($payload): bool
    {
        return \call_user_func($this->check, $payload);
    }

    public function process(Pipeline $pipeline, Queue $pipes, $payload)
    {
        foreach ($pipes as $pipe) {
            $payload = $pipe($payload);

            if (! $this->check($payload)) {
                return $payload;
            }
        }

        return $payload;
    }
}