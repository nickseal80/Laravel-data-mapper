<?php

namespace Seal\LaravelDataMapper\Wrapper;

class Wrapper
{
    protected mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function get(): mixed
    {
        return $this->value;
    }

    public function toInt(): int
    {
        return (int)$this->value;
    }

    public function toFloat(): float
    {
        return (float)$this->value;
    }

    public function toString(): string
    {
        return (string)$this->value;
    }

    public function toArray(): array
    {
        return (array)$this->value;
    }

    public function isNotEmpty(): bool
    {
        if ($this->value) {
            return true;
        }

        return false;
    }
}
