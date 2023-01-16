<?php


namespace CodeChallenge\Domain\Model;

class Date extends \DateTimeImmutable
{
    const FORMAT = 'Y-m-d';

    public function formatted()
    {
        return $this->convertToString();
    }

    public function __toString()
    {
        return $this->convertToString();
    }

    private function convertToString()
    {
        return $this->format(self::FORMAT);
    }
}