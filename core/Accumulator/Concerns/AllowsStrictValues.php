<?php

namespace Contraption\Accumulator\Concerns;

trait AllowsStrictValues
{
    /**
     * Whether or not strict mode is enabled.
     *
     * @var bool
     */
    private $strict = false;

    /**
     * The type for the value.
     *
     * @var string|null
     */
    private $valueType;

    /**
     * Enable strict mode for this collection.
     *
     * @param string $valueType
     *
     * @return $this
     */
    public function strict(string $valueType): self
    {
        $this->valueType = $valueType;
        $this->strict    = true;

        $this->each(function ($value) {
            try {
                $this->validateInput($value);
            } catch (\TypeError $e) {
                throw new \TypeError(sprintf('The current dataset is incompatiable with provided data type %s', $this->valueType));
            }
        });

        return $this;
    }

    /**
     * Check whether or not this collection is in strict mode.
     *
     * @return bool
     */
    public function isStrict(): bool
    {
        return $this->strict;
    }

    /**
     * Validate that the provided value matches the strict settings of this collection.
     *
     * @param $value
     */
    private function validateInput($value): void
    {
        if ($this->strict) {
            $valueType  = \gettype($value);
            $valueCheck = ($valueType === $this->valueType) || ($valueType === 'object' && $value instanceof $this->valueType);

            if (! $valueCheck) {
                throw new \TypeError(sprintf('Value must be of type %s', $this->valueType));
            }
        }
    }
}