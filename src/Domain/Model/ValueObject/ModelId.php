<?php

namespace CodeChallenge\Domain\Model\ValueObject;

class ModelId
{
    private $value;

    public function __construct(?string $value = null)
    {
        if (!empty($value)) {
            $this->value = $value;
        } else {
            try {
                $this->value = UUID::v4();
            } catch (\Exception $e) {
            }
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(ModelId $id): bool
    {
        return $this->value == $id->value();
    }

    public function __toString()
    {
        return (string) $this->value;
    }

}