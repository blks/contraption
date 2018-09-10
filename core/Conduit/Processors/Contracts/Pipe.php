<?php

namespace Contraption\Conduit\Processors\Contracts;

interface Pipe
{
    public function __invoke($payload);
}